<?php
namespace WebEngine\Plugin;

use Intervention\Image\ImageManager;

class App {
  public $AUTH_SALT;
  public $LOGGED_USER;
  private $_machine;
  private $_imageManager;
  private $DB;
  
  public function __construct($machine) {
    $this->AUTH_SALT = "1$(=>6zGa}S4NqW/R5|KZ6lDyzWuGg%x.(Yi-4@O Q<<FK=(2+N n!W}q+oN-@:/";
    $this->_machine = $machine;
    $this->_imageManager = new ImageManager(array('driver' => 'gd'));
    $this->DB = $this->_machine->plugin("DB");
    $this->LOGGED_USER = null;
  }

  public function getCurrentUrl() {
    return (isset($_SERVER['HTTPS']) ? "https" : "http") 
      . "://" . $_SERVER["HTTP_HOST"] 
      . $this->_machine->basepath . $this->_machine->getCurrentPath();
  }
  
  public function checkLogin() {
    $result = $this->DB->select("SELECT * FROM administrators", []);
    $cookie = isset($_COOKIE["auth"]) ? $_COOKIE["auth"] : "";
    if ($cookie == "")
      return;
    foreach ($result as $row) {
      $test = md5($row["email"] . $this->AUTH_SALT . $row["encrypted_password"]);
      //echo $test . "<br>";
      if ($test == $cookie) {
        $this->LOGGED_USER = $row;
        return true;
      }
    }
    return false;
  }
  
  public function getCommonData() {
    $DB = $this->DB;
    $topBanner = $DB->getRandomTopBanner();
    $cities = $DB->getCities();
    $currentCity = $DB->getCurrentCity();  
    return [
      "menuitems" => $DB->getMenuitems(),
      "navbanners" => $DB->getNavbanners(),
      "navtopitems" => $DB->getNavtopitems(),
      "linktopitems" => $this->getLinktopitems(),
      "topBannerTitle" => $topBanner["title"],
      "topBannerUrl" => $topBanner["url"],
      "topBannerImage" => $topBanner["image_file_name"],
      "imageUrl" => $this->img("banners", $topBanner["id"], 728, 90, $topBanner["image_file_name"]),
      "currentCity" => $currentCity[0]["name"],
      "cities" => $cities
    ];
  }   
  
  public function printLocaleItem($item) {
    $imgurl = $this->img("locations", $item["id"], 248, 224, $item["logo_file_name"], "logos");
    $level_names = [
      "3" => "platinum",
      "2" => "gold",
      "1" => "silver",
      "0" => "free"
    ];
    
    $html = '
      <div class="item ' . $level_names[$item["level"]] . ' row collapse">
        ' . (
          $item["level"] == 0 
            ? '<div class="thumbnail columns hide-for-small medium-3 large-2">'
            : '<div class="thumbnail columns hide-for-small medium-4 large-3">'
          ) . '
          <a class="thumb" title="' . $item["title"] . '" href="{{Link|Get|LOCALE|' . $item["seo_url"] . '}}">
            <img alt="' . $item["title"] . '" 
              data-interchange="
                [' . $imgurl . ', (default)], 
                [' . $imgurl . ', (medium)], 
                [' . $imgurl . ', (large)]
              " 
              src="' . $imgurl . '" 
              title="' . $item["title"] . '" 
              data-uuid="">
            <noscript>
              <img alt="' . $item["title"] . '" src="' . $imgurl . '" title="' . $item["title"] . '" />
            </noscript>
          </a>
        </div>
        
        ' . (
          $item["level"] == 0
            ? '<div class="info small-12 medium-9 large-10 columns">'
            : '<div class="info small-12 medium-8 large-9 columns">'
          ) . '
          <div class="desc">
            <a class="summary" title="' . $item["title"] . '" href="{{Link|Get|LOCALE|' . $item["seo_url"] . '}}">
              ' . $item["title"] . '
            </a>
            <span class="address">' . $item["title"] . ' - ' . $item["address_way"] . ', ' . $item["address_number"] . ' - ' . $item["address_city"] . ', ' . $item["address_zip"] . ' - (' . $item["address_province"] . '), IT</span>
            <p class="description">' . $item["seo_description"] . '</p>
            <p class="booking">INFO E PRENOTAZIONI: ' . $item["phone"] . ' - ' . $item["mobile"] . '</p>
          </div>
        </div>
      </div>
    ';
    
    return $html;
  }
  
  // ritorna solo il nome di riferimento della zona, dato il record.
  public function _zName($zRecord) {
    $parts = explode("[[", $zRecord["title"]);
    $zName = str_replace("]]", "", $parts[1]);
    return $zName;
  }
  
  public function _zTitle($cRecord, $zRecord) {
    $name = $this->_zName($zRecord);
    $zTitle = str_replace("__typo__", $cRecord["logic_title"], $zRecord["title"]);
    $zTitle = str_replace("[[", "", $zTitle);
    $zTitle = str_replace("]]", "", $zTitle);
    return $zTitle;
  }
  
  public function _zTypo($string, $cRecord) {
    return str_replace("__typo__", $cRecord["logic_title"], $string);
  }
  
  public function getImgCDN($tablename, $item_id, $itemtype, $thumbsize, $imagename)
  {
    $idstr = str_pad($item_id, 9, "0", STR_PAD_LEFT);
    $dir1 = substr($idstr, 0, 3);
    $dir2 = substr($idstr, 3, 3);
    $dir3 = substr($idstr, 6, 3);
    return "//cdn.discotecheitalia.it/uploads/$tablename/$itemtype/$dir1/$dir2/$dir3/$thumbsize/" . $imagename;
  }
  
  public function getImg($table, $id, $itemtype, $value, $W, $H) {
    // redirecting to img...
    return $this->img($table, $id, $W, $H, $value);
    //
    $idstr = str_pad($id, 9, "0", STR_PAD_LEFT);
    $dir1 = substr($idstr, 0, 3);
    $dir2 = substr($idstr, 3, 3);
    $dir3 = substr($idstr, 6, 3);
    $src_original = "./uploads/$table/$itemtype/$dir1/$dir2/$dir3/original/" . $value;
    return $this->_machine->plugin("Image")->Get([$src_original, $W, $H]);
  }
  
  public function img($table, $id, $w, $h, $filename, $section="images") {
    $r = $this->_machine->getRequest();
    $UploadS3 = $this->_machine->plugin("UploadS3");
    
    // ritorna la thumb se esiste.
    $thumb_url = "uploads/$table/$w" . "_" . "$h/$filename";
    if ($UploadS3->file_exists_in_bucket($thumb_url)) {
      return $UploadS3->get($thumb_url); 
    }
    
    // se non esiste, cerca l'originale e genera la thumb.
    $original_url = "uploads/$table/original/$filename";
    if ($UploadS3->file_exists_in_bucket($original_url)) {
      $this->generateLocalThumb($table, $filename, $w, $h);
      return $UploadS3->get($thumb_url); 
    }
    
    // se non esiste, cerca su cdn.
    $idstr = str_pad($id, 9, "0", STR_PAD_LEFT);
    $dir1 = substr($idstr, 0, 3);
    $dir2 = substr($idstr, 3, 3);
    $dir3 = substr($idstr, 6, 3);
    //$cdn_url = "//cdn.discotecheitalia.it/uploads/$table/$section/$dir1/$dir2/$dir3/original/" . $filename;
    $cdn_url = "uploads/$table/$section/$dir1/$dir2/$dir3/original/" . $filename;
    //if ($this->file_exists_remote("http:" . $cdn_url)) {
    if ($UploadS3->file_exists_in_old_bucket($cdn_url)) {
      // prima...
      //$this->downloadCdnImage("http:" . $cdn_url, $original_url);
      
      // poi...
      // fare download da cdn direttamente nel bucket
      //$file = file_get_contents("http:" . $cdn_url);
      //$UploadS3->put($original_url, $file);
      
      // ottimizzato...
      $UploadS3->copy($cdn_url, $original_url);
      
      $this->generateLocalThumb($table, $filename, $w, $h);
      return $UploadS3->get($thumb_url);     
    }
  }
  
  public function creaRitaglio($table, $filename, $w, $h, $x, $y) {
    $original_url = "uploads/$table/original/$filename";
    $cut_url = "uploads/$table/cut/$filename";
    if (($w == 0 || $w == "") && ($h == 0 || $h == "")) {
      @unlink($cut_url);
      @unlink($cut_url . ".info");
    } else {
      $img = $this->_imageManager->make($original_url);
      $img->crop($w, $h, $x, $y);
      $arr_local = explode("/", $cut_url);
      array_pop($arr_local);
      @mkdir("./" . implode("/", $arr_local), 0777, true);
      $img->save($cut_url);
      file_put_contents($cut_url . ".info", json_encode([
        "w" => $w,
        "h" => $h,
        "x" => $x,
        "y" => $y
      ]));
    }
  }
  
  private function downloadCdnImage($from_remote, $to_local) {
    $file = file_get_contents($from_remote);
    $arr_local = explode("/", $to_local);
    array_pop($arr_local);
    @mkdir("./" . implode("/", $arr_local), 0777, true);
    file_put_contents($to_local, $file);
  }
  
  private function generateLocalThumb($table, $filename, $dest_w, $dest_h) {
    $UploadS3 = $this->_machine->plugin("UploadS3");
    
    // se esiste il cut file lo usa
    //  altrimenti usa l'originale
    $cut_url = "uploads/$table/cut/$filename";
    $original_url = "uploads/$table/original/$filename";
    $thumb_url = "uploads/$table/$dest_w" . "_" . "$dest_h/$filename";
    
    $source = $original_url;
    if ($UploadS3->file_exists_in_bucket($cut_url)) {
      $source = $cut_url;
    }
    $image = $this->_imageManager->make($UploadS3->get($source));
    $w = $image->width();
    $h = $image->height();
    if ($dest_h == "H" && is_int($dest_w)) {
      $dest_h = ($h * $dest_w) / $w;
    }
    if ($dest_w == "W" && is_int($dest_h)) {
      $dest_w = ($w * $dest_h) / $h;
    }
    $image->fit(intval($dest_w), intval($dest_h));
    $UploadS3->put($thumb_url, $image->stream());
    
    //$arr = explode("/", $thumb_url);
    //array_pop($arr);
    //@mkdir("./" . implode("/", $arr), 0777, true);
    //$image->save($thumb_url);
  }
  
  private function downloadImageCDN($src_cdn) {
    $src_local = explode("uploads/", $src_cdn)[1];
    
    if (!file_exists("./uploads/" . $src_local)) {
      $file = file_get_contents($src_cdn);
      $arr_local = explode("/", $src_local);
      array_pop($arr_local);
      @mkdir("./uploads/" . implode("/", $arr_local), 0777, true);
      file_put_contents("./uploads/" . $src_local, $file);
    }
  }
  
  public function file_exists_remote($filename) {
    $file_headers = @get_headers($filename);
    if (!$file_headers)
      return false;
    if($file_headers[0] == 'HTTP/1.1 404 Not Found'){
      return false;
    } else if ($file_headers[0] == 'HTTP/1.1 302 Found' && $file_headers[7] == 'HTTP/1.1 404 Not Found'){
      return false;
    } else {
      return true;
    }
  }
  
  public function checkImage($tablename, $item_id, $itemtype, $imagename) {
    $errors = [];
    
    // cerca l'originale su cdn
    $src_original = "http:" . $this->getImgCDN($tablename, $item_id, $itemtype, "original", $imagename);

    // se non esiste l'originale, segnala l'errore e ritorna
    if (!$this->file_exists_remote($src_original)) {
      $errors[] = "...";
      return;
    }
    
    // l'originale esiste, viene scaricata localmente in /uploads con lo stesso percorso
    $this->downloadImageCDN($src_original);
  }
  
  private function _getDay($dt, $first, $eventDates) {
    $today = new \DateTime("midnight");
    
    $dayTimeReference = "today";
    if ($dt < $today) { $dayTimeReference = "past"; }
    if ($dt > $today) { $dayTimeReference = "future"; }
    
    $whichmonth = "";
    if ($dt->format('Y-m') === $first->format('Y-m')) {
      $whichmonth = "current";
    } else {
      if ($dt < $first) { $whichmonth = "prev"; }
      if ($dt > $first) { $whichmonth = "next"; }
    }
    return [
      "dayTimeReference" => $dayTimeReference,
      "whichmonth" => $whichmonth,
      "weekdaynumber" => $dt->format("w"),
      "hasevents" => array_key_exists($dt->format("Y-m-d"), $eventDates),
      "daynumber" => $dt->format("j"),
      "monthName" => $dt->format("F"),
      "url" => $this->_machine->plugin("Link")->Get(["EVENTI_DATA", $dt->format("Y"), $dt->format("n"), $dt->format("j")])
    ];
  }

  public function getCalendar($anno="", $mese="")
  {
    if ($anno == "") $anno = date("Y");
    if ($mese == "") $mese = date("n");
    
    // get the textual representation of the given month
    $dtMonth = \DateTime::createFromFormat('!m', $mese);
    $monthName = $dtMonth->format('F');
    
    // calcola il primo e l'ultimo giorno del mese
    $first = new \DateTimeImmutable('first day of ' . $monthName . " " . $anno);
    $last = new \DateTimeImmutable('last day of this month ' . $monthName . " " . $anno);
    
    // calculate next and prev links
    $nextmese = $mese + 1;
    $nextanno = $anno;
    if ($nextmese > 12) { $nextmese = 1; $nextanno++; }
    $prevmese = $mese - 1;
    $prevanno = $anno;
    if ($prevmese == 0) { $prevmese = 12; $prevanno--; }
    $next_link = $this->_machine->plugin("Link")->Get(["CALENDARIO", $nextanno, $nextmese]);
    $prev_link = $this->_machine->plugin("Link")->Get(["CALENDARIO", $prevanno, $prevmese]);
    if ($first <= new \DateTime("today midnight")) {
      $prev_link = '<span class="empty"></span>';
    }
    
    // calcola l'offset iniziale
    $offset = - ($first->format("N") - 1);
    
    // ottiene le informazioni sugli eventi
    $eventInfos = $this->getEventInfos();
    
    // compone la tabella con i giorni
    $dayRows = [];
    $dayRow = [];
    $count = 0;
    do {
      $modifier = $offset + $count;
      $dt = $first->modify(($modifier >= 0 ? "+" : "") . $modifier . " day");
      $dayRow[] = $this->_getDay($dt, $first, $eventInfos["dates"]);
      if (count($dayRow) == 7) {
        $dayRows[] = array_merge([], $dayRow);
        $dayRow = [];
      }
      $count++;
    } while($dt < $last || $dt->format("N") != 7);

    return [
      "tot_events" => $eventInfos["tot"],
      "tot_events_today" => count($eventInfos["today"]),
      "tot_events_weekend" => count($eventInfos["next_weekend"]),
      "current_year" => $anno,
      "current_month" => $monthName,
      "dayRows" => $dayRows,
      "prev_link" => $first <= new \DateTime("today midnight") ? '<span class="empty"></span>' : '<a href="' . $prev_link . '">&lt;</a>',
      "next_link" => '<a href="' . $next_link . '">&gt;</a>'
    ];
  }
  
  public function getLinktopitems()
  {
    $items = [];
    
    $items[] = [
      "url" => "http://www.facebook.com/discotechebrescia",
      "class" => "facebook",
      "classicon" => "fa-facebook",
      "title" => "collegamento esterno: Facebook"
    ];
    
    $items[] = [
      "url" => "https://twitter.com/#!/DiscotecheBS",
      "class" => "twitter",
      "classicon" => "fa-twitter",
      "title" => "collegamento esterno: Twitter"
    ];
    
    $items[] = [
      "url" => "https://plus.google.com/+discotechebrescia",
      "class" => "google_plus",
      "classicon" => "fa-google-plus",
      "title" => "collegamento esterno: Google +"
    ];
    
    $items[] = [
      "url" => "/rss/prossimi-eventi.xml",
      "class" => "rss",
      "classicon" => "fa-rss",
      "title" => "ultimi eventi in formato RSS"
    ];
    
    return $items;
  }
  
  private function _insertDate($dates_array, $date) {
    $dt = $date->format("Y-m-d");
    if (!isset($dates_array[$dt])) {
      $dates_array[$dt] = 0;
    }
    $dates_array[$dt]++;
    return $dates_array;
  }
  
  // utile per il calendario. se serve solo una lista di eventi, 
  // usare getEventsForRange() o getEventsFromDB()
  public function getEventInfos() {
    // today will be useful 
    $today = new \DateTime("today midnight");
    
    // get all events
    $events = $this->_machine->plugin("DB")->getEventsFromDB();
    
    // retrieve dates with events
    $dates = [];
    foreach ($events as $ev) {
      $from = new \DateTimeImmutable($ev["time_from"]);
      $to = new \DateTimeImmutable($ev["time_to"]);
      $date = $from;
      while ($date <= $to) {
        $dates = $this->_insertDate($dates, $date);
        $date = $date->modify("+1 day");
      }
    }
    
    // retrieve events for today
    $today_events = $this->getEventsForRange(
      $today, $today
    );
    
    // retrieve events for next weekend
    $next_weekend_events = $this->getNextWeekendEvents();

    $result = [
      "tot" => count($events),
      "dates" => $dates,
      "today" => $today_events,
      "next_weekend" => $next_weekend_events,
      "events" => $events
    ];
    
    return $result;
  }
  
  public function getNextWeekendEvents() {
    $today = new \DateTime("today midnight");
    switch ($today->format("w")) {
      case 0: // sunday
        $dt1 = new \DateTime("last friday");    
        $dt2 = $today;
        break;
      case 5: // friday
        $dt1 = $today;
        $dt2 = new \DateTime("next sunday");      
        break;
      case 6: // saturday
        $dt1 = new \DateTime("last friday");    
        $dt2 = new \DateTime("next sunday");        
        break;
      default:
      $dt1 = new \DateTime("next friday");
      $dt2 = new \DateTime("next sunday");
    }
    return $this->getEventsForRange(
      $dt1, $dt2
    );
  }
  
  // ritorna gli eventi definiti per almeno uno dei giorni compresi tra dt1 e dt2
  public function getEventsForRange($dt1, $dt2) {
    $events = $this->_machine->plugin("DB")->getEventsFromDB();
    $events = array_filter($events, function($ev) use ($dt1, $dt2) {
      $from = new \DateTime($ev["time_from"]);
      $to = new \DateTime($ev["time_to"]);
      $from->setTime(0,0);
      $to->setTime(0,0);
      if (($from <= $dt1 && $dt1 <= $to) || ($from <= $dt2 && $dt2 <= $to)) {
        return true;
      }
      return false;
    });
    return $events;
  }
  
  /**
   *  to count: getEvents(null, true);
   *  to count: getEvents(null, "today");
   *  to count: getEvents(null, "weekend");
   *  first page: getEvents(1);
   *  vary items per page: getEvents(1, false, 20);
   */
  public function ___getEvents($page=null, $count=false, $items_per_page=15, $special=false)
  {
    // app/queries/event_query.rb?
    $specialcond = $special ? "AND events.special = 1" : "";
    $query = "
      SELECT 
        events.*,
        locations.title as locations_title,
        locations.address_city as locations_address_city,
        locations.address_province as locations_address_province,
        locations.seo_url as locations_seo_url,
        recurrents.image_file_name as recurrent_image    
      FROM events
      LEFT JOIN event_btw_locations 
        ON events.id = event_btw_locations.event_id
      LEFT JOIN locations
        ON event_btw_locations.location_id = locations.id
      LEFT JOIN recurrents
        ON events.recurrent_id = recurrents.id
      WHERE
        events.site_id = ?
        AND time_to > '2018-01-10' /* NOW() */
        AND locations.active = 1
        $specialcond
      GROUP BY
        events.seo_url
      ORDER BY
        time_to ASC
    ";
    if ($count) {
      $query = "
        SELECT COUNT(*) AS counter FROM (
          $query
        ) AS A
      ";
    } else {
      if (!is_null($page)) {
        $offset = ($page - 1) * $items_per_page;
        $query .= "
          LIMIT $offset, $items_per_page
        ";
      }
    }
    $result = $this->select($query, [$this->_site]);
    if ($count) {
      return $result[0]["counter"];
    }
    return $result;
  }
  
  public function getAdminMenuitems()
  {
    $currentpath = $this->_machine->basepath;
    $admin_items = [];
    if (isset($this->LOGGED_USER["role"]) && $this->LOGGED_USER["role"] == 0) {
      // root user
      $admin_items = [[
        "label" => "Utenti",
        "url" => $this->_machine->plugin("Link")->Get("/admin/administrators"),
        "title" => "Utenti",
        "iconclass" => "fa fa-users"
      ]];
    }
    return array_merge($admin_items, [
      [
        "label" => "Banners",
        "url" => $this->_machine->plugin("Link")->Get("/admin/banners"),
        "title" => "Banners",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Tipologie locations",
        "url" => $this->_machine->plugin("Link")->Get("/admin/typo_btw_sites"),
        "title" => "Tipologie locations",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Categorie eventi",
        "url" => $this->_machine->plugin("Link")->Get("/admin/cat_btw_sites"),
        "title" => "Categorie eventi",
        "iconclass" => "fa fa-table"
      ],  
      [
        "label" => "Eventi",
        "url" => $this->_machine->plugin("Link")->Get("/admin/events"),
        "title" => "Eventi",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Eventi ricorrenti",
        "url" => $this->_machine->plugin("Link")->Get("/admin/recurrents"),
        "title" => "Eventi ricorrenti",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Festività",
        "url" => $this->_machine->plugin("Link")->Get("/admin/holiday_btw_sites"),
        "title" => "Festività",
        "iconclass" => "fa fa-table"
      ],   
      [
        "label" => "Locations",
        "url" => $this->_machine->plugin("Link")->Get("/admin/locations"),
        "title" => "Locations",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Public relators",
        "url" => $this->_machine->plugin("Link")->Get("/admin/report_btw_sites"),
        "title" => "Public relators",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Elementi del menu",
        "url" => $this->_machine->plugin("Link")->Get("/admin/navigators"),
        "title" => "Elementi del menu",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Pagine",
        "url" => $this->_machine->plugin("Link")->Get("/admin/sections"),
        "title" => "Pagine",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Galleria home",
        "url" => $this->_machine->plugin("Link")->Get("/admin/home_slides"),
        "title" => "Galleria home",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Zone",
        "url" => $this->_machine->plugin("Link")->Get("/admin/zones"),
        "title" => "Zone",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Scadenze visibilità locations",
        "url" => $this->_machine->plugin("Link")->Get("/admin/location_visibilities"),
        "title" => "Scadenze visibilità locations",
        "iconclass" => "fa fa-table"
      ],
      [
        "label" => "Boxes home",
        "url" => $this->_machine->plugin("Link")->Get("/admin/home_boxes"),
        "title" => "Boxes home",
        "iconclass" => "fa fa-table"
      ]
    ]);
  }
  
}