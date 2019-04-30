<?php
namespace WebEngine\Plugin;

use \PDO;
use \PDOException;

class DB {
  private $disable_cache = true;
  private $_conn;
  private $_site;
  private $_machine;
  private $_cache;
  private $pool;
  
  public function __construct($machine) {
    $this->_machine = $machine;
    $this->_site = 1;
    $this->_cache = [];
    $driver = new \Stash\Driver\FileSystem([
      "path" => './cache/db/'
    ]);
    $this->pool = new \Stash\Pool($driver);
  }
  
  public function setupMySql($db_host, $db_user, $db_pass, $db_name) {
    try {
      $this->_conn = new PDO(
        'mysql:host=' . $db_host . ';dbname=' . $db_name, 
        $db_user, 
        $db_pass,
        array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
      );
      return $this->_conn;
    } catch (PDOException $e) {
      return false;
      //die("db connection error");
    }
  }  
  
  public function setSite($n) {
    $this->_site = $n;
  }
  
  public function getSite() {
    return $this->_site;
  } 
  
  private function _getData($query, $data) {
    //$query = str_replace("\t", "", $query);
    //$query = str_replace("\r\n", " ", $query);
    $cache_index = md5($this->_site . $query . json_encode($data));
    if (isset($this->_cache[$cache_index])) {
      //file_put_contents("./logs/queries.log", "skipped - $query \r\n", FILE_APPEND);
      return $this->_cache[$cache_index];
    }
    
    $item = $this->pool->getItem($cache_index);
    $result = $item->get();
    
    if($item->isMiss() || $this->disable_cache) {
      $item->lock();
      //$msc = microtime(true);
      $result = $this->select($query, $data);
      //$msc = microtime(true)-$msc;
      //file_put_contents("./logs/queries.log", ($msc * 1000) . "ms - query $query \r\n", FILE_APPEND);
      $item->expiresAt(new \DateTime("tomorrow"));
      $this->pool->save($item->set($result));
      
      $this->_cache[$cache_index] = $result;
    }
    
    return $result;
  }
  
  public function getHomeContent() {
    $query = "
      SELECT * 
      FROM homes 
      WHERE site_id = ? 
      ORDER BY id DESC
      LIMIT 0,1
    ";
    $result = $this->_getData($query, [$this->_site]);
    return $result[0];
  }
  
  /**
   *  Set data from database
   *
   *  @param $table String The table name
   *  @param $wherePart String Where part as SQL string
   *  @param $orderbyPart String OrderBy part as SQL string
   *
   *  @return null
   */
  public function setData($table, $wherePart, $orderbyPart)
  {
    $table = $params[0];
    $wherePart = $params[1] != "" ? "WHERE " . $params[1] : "";
    $orderbyPart = $params[2] != "" ? "ORDER BY " . $params[2] : "";
    
    $data = [];
    
    $query = "
      SELECT *
      FROM $table
      $wherePart
      $orderbyPart
    ";
    
    return $this->_getData($query, $data);
  }
  
  public function getRandomTopBanner() {
    $query = "
      SELECT * 
      FROM banners 
      WHERE site_id = ? 
        AND located = 0 
        AND expire > NOW()
      ORDER BY RAND()
      LIMIT 0,1
    ";
    $result = $this->_getData($query, [$this->_site]);
    return $result[0];
  }

  public function getBanners() {
    $query = "SELECT * FROM banners WHERE site_id = ? AND expire > NOW()";
    return $this->_getData($query, [$this->_site]);
  }
  
  public function getBannerLandscape() {
    $query = "SELECT * FROM banners WHERE site_id = ? AND located = 3 AND expire > NOW()";
    return $this->_getData($query, [$this->_site]);
  }
  
  public function getCatEvents() {
    $query = "SELECT * FROM cat_btw_sites WHERE site_id = ? ORDER BY position";
    return $this->_getData($query, [$this->_site]);
  }
  
  public function getEvCategory($slug) {
    $query = "SELECT * FROM cat_btw_sites WHERE site_id = ? AND seo_url = ?";
    $result = $this->_getData($query, [$this->_site, $slug]);
    return $result[0];
  }
  
  public function getEvFestivita($slug) {
    $query = "SELECT * FROM holiday_btw_sites WHERE site_id = ? AND seo_url = ?";
    $result = $this->_getData($query, [$this->_site, $slug]);
    return $result[0];
  }
  
  public function getElencoFestivita() {
    return $this->_getData("SELECT * FROM holiday_btw_sites LEFT JOIN holidays ON holidays.id = holiday_btw_sites.holiday_id WHERE site_id = ? AND active = 1 ORDER BY position", [$this->_site]);
  }
  
  public function getCities() {
    return $this->_getData("SELECT id, title_small AS name, domain AS url FROM sites ORDER BY title_small", []);
  }
  
  public function getCurrentCity() {
    return $this->_getData("SELECT id, title_small AS name, title_big, domain AS url FROM sites WHERE id = ?", [$this->_site]);
  }
  
  public function select($query, $data)
  {
    $sth = $this->_conn->prepare($query);
    $sth->execute($data);
    return $sth->fetchAll(\PDO::FETCH_ASSOC);
  }
  
  public function insert($query, $data) {
    $sth = $this->_conn->prepare($query);
    $result = $sth->execute($data);
    if (!$result) {
      echo "ERROR executing query " . $query;die();
    }
    return $this->_conn->lastInsertId();
  }
  
  public function delete($query, $data) {
    $sth = $this->_conn->prepare($query);
    return $sth->execute($data);
  }
  
  public function newRecord($table, $fields, $values) {
    // TO DO: controllare le tabelle con campi esterni e salvarli
    $query = "INSERT INTO $table (
      " . implode(",", $fields) . "
    ) VALUES (
      " . implode(",", array_map(function($item) { return "?"; }, $fields)) . "
    )";
    $sth = $this->_conn->prepare($query);
    $result = $sth->execute($values);
    if (!$result) {
      print_r($sth->errorInfo());
      die("Save to database failed");
    }
    return $this->_conn->lastInsertId();
  }
  
  public function save($table, $fields, $values, $field_id, $id)
  {
    // build query
    $fields_arr = array_map(function($f) {
      return $f . " = ?";
    }, $fields);
    
    $tablequery = $table;
    $joinPart = $this->_machine->plugin("Backoffice")->getJoinPart($table);
    if ($joinPart != "") {
      $tablequery .= " " . $joinPart;
    }
    $query = "
      UPDATE $tablequery SET
      " . implode(",", $fields_arr) . "
      WHERE $table.$field_id = $id
    ";

    $sth = $this->_conn->prepare($query);
    $result = $sth->execute($values);
    if (!$result) {
      print_r($sth->errorInfo());
      die("Save to database failed");
    }
    return $sth->rowCount();
  }
  
  private function insert_m2m_typo_ids($v, $main_id) {
    $query = "INSERT INTO typo_btw_locations (
      site_id,
      location_id,
      typo_id,
      level,
      created_at,
      updated_at
    ) VALUES (
      ?, ?, ?, ?, ?, ?
    )";
    $data = [
      $this->_site,
      $main_id,
      $v,
      0,
      date("Y-m-d H:i:s"),
      date("Y-m-d H:i:s")
    ];
    $this->insert($query, $data);
  }

  private function delete_m2m_typo_ids($v, $main_id) {
    $query = "DELETE FROM typo_btw_locations WHERE 
      typo_id = ? AND location_id = ? AND site_id = ?
    ";
    $data = [$v, $main_id, $this->_site];
    $this->delete($query, $data);
  }
  
  private function insert_m2m_music_ids($v, $main_id) {
    $query = "INSERT INTO music_btw_locations (
      site_id,
      location_id,
      music_id
    ) VALUES (
      ?, ?, ?
    )";
    $data = [
      $this->_site,
      $main_id,
      $v
    ];
    $this->insert($query, $data);
  }

  private function delete_m2m_music_ids($v, $main_id) {
    $query = "DELETE FROM music_btw_locations WHERE 
      music_id = ? AND location_id = ? AND site_id = ?
    ";
    $data = [$v, $main_id, $this->_site];
    $this->delete($query, $data);
  }
  
  private function insert_m2m_report_ids($v, $main_id) {
    $query = "INSERT INTO report_btw_locations (
      site_id,
      location_id,
      report_id
    ) VALUES (
      ?, ?, ?
    )";
    $data = [
      $this->_site,
      $main_id,
      $v
    ];
    $this->insert($query, $data);
  }

  private function delete_m2m_report_ids($v, $main_id) {
    $query = "DELETE FROM report_btw_locations WHERE 
      report_id = ? AND location_id = ? AND site_id = ?
    ";
    $data = [$v, $main_id, $this->_site];
    $this->delete($query, $data);
  }
  
  public function saveManyToMany($arrm2m, $table, $main_id) {
    foreach ($arrm2m as $k => $values) {
      // ottengo i valori correnti in $current_value[]
      $opts = $this->_machine->plugin("Backoffice")->getFieldOptions($table, $k . "[]");
      $query = $opts["queryvalues"];
      $result = $this->_machine->plugin("DB")->_getData($query, [$main_id]);
      $current_value = [];
      foreach ($result as $row) {
        $current_value[] = array_values($row)[0];
      }      
      
      // cerca i valori da aggiungere
      foreach ($values as $v) {
        if ($v != "") {
          // se $v esiste in $current_value[] allora non fare nulla.
          // se $v non esiste in $current_value[] allora lo inserisce.
          if (in_array($v, $current_value)) {
            // do nothing
          } else {
            $func = "insert_m2m_" . $k;
            $this->{$func}($v, $main_id);
          }
        }
      }
      
      // cerca i valori da cancellare
      foreach ($current_value as $cv) {
        // se $cv esiste in $values allora non fare nulla.
        // se $cv non esiste in $values allora lo cancella.
        if (in_array($cv, $values)) {
          // do nothing
        } else {
          $func = "delete_m2m_" . $k;
          $this->{$func}($cv, $main_id);
        }
      }
      /*
      $query = str_replace("SELECT * FROM", "DELETE FROM", $opts["queryvalues"]);
      $parts = explode(" ", $opts["queryvalues"]);
      $m2mtable = $parts[3];
      $main_field_id = $parts[5];
      $field_id = $parts[1];
      $this->delete("DELETE FROM $m2mtable WHERE $main_field_id = ?", [$main_id]);
      foreach ($values as $v) {
        if ($v != "") {
          $this->insert("
            INSERT INTO $m2mtable
            (
              site_id,
              $main_field_id,
              $field_id
            )
            VALUES
            (
              ?, ?, ?
            )
            ", [
              $this->_site,
              $main_id,
              $v
            ]
          );
        }
      }
      */
    }
  }
  
  public function getMenuitems() {
    $items = [];
    
    // first item is homepage
    $items[] = [
      "label" => "Home page",
      "url" => $this->_machine->plugin("Link")->Get("HOME"),
      "title" => "Home page Discoteche Brescia",
      "iconclass" => "fa fa-home"
    ];    
    
    // extract left menu items
    $query = "
      SELECT 
        navigators.navigatorable_type,
        navigators.title, 
        navigators.title_tag, 
        navigators.url, 
        navigators.font_icon, 
        sections.seo_url,
        sections.sitemap
      FROM 
        navigators 
      LEFT JOIN 
        sections 
        ON navigatorable_id = sections.id 
      WHERE 
        navigators.site_id = ? 
        AND located = 2 
      ORDER BY 
        position
    ";
    $result = $this->_getData($query, [$this->_site]);
    foreach ($result as $r) {
      $url = $r["url"];
      if ($r["navigatorable_type"] == "Section") {
        $slug = $r["seo_url"];
        
        if ($r["sitemap"] == 0) {
          $url = $this->_machine->plugin("Link")->Get("/" . $slug);
        } else {
          $url = $this->_machine->plugin("Link")->Get([
            "SEZIONE",
            $slug
          ]);
        }
      }

      $items[] = [
        "title" => $r["title_tag"],
        "url" => $url,
        "label" => $r["title"],
        "iconclass" => "fa fa-" . $r["font_icon"]
      ];
    }
    
    // extract categories
    $categories = [];
    $query = "SELECT * FROM typo_btw_sites WHERE site_id = ? AND active = 1 AND menu = 0 ORDER BY position";
    $result = $this->_getData($query, [$this->_site]);
    foreach ($result as $r) {
      $categories[] = [
        "id" => $r["typo_id"],
        "title" => $r["title"],
        "url" => $r["seo_url"],
        "label" => $r["title"]
      ];
    }    
    
    // insert categories at index 2
    $item = [[
      "label" => "Categorie locali",
      "url" => "#",
      "title" => "",
      "iconclass" => "fa fa-glass",
      "children" => $categories
    ]];
    array_splice($items, 2, 0, $item);
    
    // return value
    return $items;
  }
  
  public function getNavbanners() {
    $items = [];
    
    $query = "
      SELECT 
        * 
      FROM banners 
      WHERE 
        site_id = ? 
        AND located = 1 
        AND NOW() < expire
      ORDER BY position
    ";
    $result = $this->_getData($query, [$this->_site]);
    
    return $result;
  }
  
  public function getHomeslides() {
    $items = [];
    
    $query = "
      SELECT 
        *
      FROM home_slides
      WHERE
        site_id = ?
        AND active = 1
        AND NOW() < expire
      ORDER BY position
    ";
    $result = $this->_getData($query, [$this->_site]);
    
    return $result;
  }
  
  public function getNavtopitems() {
    $items = [];
    
    // extract top menu items
    $query = "
      SELECT 
        navigators.navigatorable_type,
        navigators.title, 
        navigators.title_tag, 
        navigators.url, 
        navigators.font_icon, 
        sections.seo_url 
      FROM 
        navigators 
      LEFT JOIN 
        sections 
        ON navigatorable_id = sections.id 
      WHERE 
        navigators.site_id = ? 
        AND located = 0
      ORDER BY 
        position
    ";
    $result = $this->_getData($query, [$this->_site]);
    foreach ($result as $r) {
      $items[] = [
        "title" => $r["title_tag"],
        "url" => $this->_machine->plugin("Link")->Get(["SEZIONE", $r["navigatorable_type"] == "Section" ? $r["seo_url"] : $r["url"]]),
        "label" => $r["title"]
      ];
    }
    
    return $items;
  }
  
  public function getEventsFromDB($wherecond="", $limitcond="", $past=false) {
    $ordcond = 'time_to ASC';
    $timecond = 'AND time_to > NOW()';
    if ($past) {
      $ordcond = 'time_to DESC';
      $timecond = 'AND time_to <= NOW()';
    }
    $query = "
      SELECT * FROM (SELECT 
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
        $wherecond
        $timecond
      ORDER BY
        events.time_to ASC) AS A
        
      GROUP BY
        A.seo_url
      ORDER BY 
        $ordcond
      
      $limitcond
    ";
    
    $events = $this->_getData($query, [$this->_site]);
    
    return $events;
  }
  
  public function getBannerForBoxHome() {
    $query = "SELECT * FROM banners WHERE site_id = ? AND located = 4 AND expire > NOW()";
    return $this->_getData($query, [$this->_site]);
  }
  
  public function getBoxesWithImages() {
    $query = "
      SELECT *
      FROM home_boxes
      WHERE 
        site_id = ?
        AND active = 1 
        AND behaviour = 0
        AND image_file_name <> ''
    ";
    return $this->_getData($query, [$this->_site]);
  }
  
  public function getHomeBoxes()
  {
    $query = "
      SELECT *
      FROM home_boxes
      WHERE 
        site_id = ?
        AND active = 1
      ORDER BY
        disposition    
    ";
    
    $result = $this->_getData($query, [
      $this->_site
    ]);
    
    $boxes = [];
    foreach ($result as $row) {
      if ($row["behaviour"] == 3) { // banner grande
        $bannerBoxHome = $this->getBannerForBoxHome();
        foreach ($bannerBoxHome as $b) {
          $boxes[] = [
            "type" => "banner",
            "titlelink" => $b["title"],
            "url" => $b["url"],
            "image" => $this->_machine->plugin("App")->img("banners", $b["id"], 653, "H", $b["image_file_name"]),
            "size" => 2
          ];
        }
      }
      if ($row["behaviour"] == 0) { // personalizzato
        $types_from_styles = ["list article white", "list article yellow", "list article dark"];
        $boxes[] = [
          "title" => $row["title"],
          "link" => $row["link"],
          "description" => $row["description"],
          "size" => 1,
          "type" => $types_from_styles[$row["style"]],
          "image" => $row["image_file_name"] != "" ? $this->_machine->plugin("App")->img("home_boxes", $row["id"], 311, 190, $row["image_file_name"]) : ""
        ];
      }
      if ($row["behaviour"] == 1) { // locations ultime inserite
        $boxes[] = [
          "title" => "Ultimi locali inseriti",
          "link" => "",
          "description" => '',
          "size" => 1,
          "type" => "list",
          "image" => "",
          "children" => array_map(function($item) {
            $item["url"] = $this->_machine->plugin("Link")->Get(["LOCALE", $item["url"]]);
            return $item;
          }, $this->getLocaliUltimiInseriti())
        ];
      }
      if ($row["behaviour"] == 2) { // locations in evidenza
        $currentCity = $this->getCurrentCity()[0]["name"];
        $boxes[] = [
          "title" => "Locali in evidenza a $currentCity",
          "link" => "",
          "description" => 'I locali della provincia che nelle ultime 24h hanno scalato più posizioni in popolarità',
          "size" => 1,
          "type" => "list dark",
          "image" => "",
          "children" => array_map(function($item) {
            $item["url"] = $this->_machine->plugin("Link")->Get(["LOCALE", $item["url"]]);
            return $item;
          }, $this->getLocaliInEvidenzaHome())
        ];
      }      
      if ($row["behaviour"] == 4) { // testo home promo
        $boxes[] = [
          "type" => "boxed",
          "size" => 2
        ];
      }
      if ($row["behaviour"] == 5) { // Google Plus
        $boxes[] = [
          "type" => "google-badge",
          "size" => 1
        ];
      }
      
    }
    
    return $boxes;
  }
  
  public function getCategoriaLocali($slug) {
    $query = "SELECT typo_btw_sites.*, typos.image_file_name, typos.logic_title FROM typo_btw_sites LEFT JOIN typos ON typos.id = typo_btw_sites.typo_id WHERE site_id = ? AND seo_url = ?";
    $result = $this->_getData($query, [
      $this->_site,
      $slug
    ]);
    return $result[0];
  }
  
  public function getZona($slug) {
    $query = "SELECT * FROM zones WHERE site_id = ? AND seo_url = ?";
    $result = $this->_getData($query, [
      $this->_site,
      $slug
    ]);
    return $result[0];
  }
  
  public function getCatById($locale_id)
  {
    $query = "SELECT * FROM typo_btw_sites WHERE site_id = ? AND id = ?";
    $result = $this->_getData($query, [
      $this->_site,
      $locale_id
    ]);
    return $result[0];
  }
  
  public function getRecords($tablename) {
    $query = "SELECT * FROM $tablename";
    $result = $this->_getData($query, []);
    return $result;
  }
  
  public function countListCategoriaLocali($slug) {
    $query = '
      SELECT count(*) as counter
      FROM
        location_visibilities
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0) 
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
    ';
    $result = $this->_getData($query, [
      $this->_site,
      $slug
    ]);
    return $result[0]["counter"];
  }
  
  public function countListCategoriaLocaliLettera($slug, $lettera) {
    $query = '
      SELECT count(*) as counter
      FROM
        location_visibilities
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0) 
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
        AND locations.title like \'' . $lettera . '%\'
    ';
    $result = $this->_getData($query, [
      $this->_site,
      $slug
    ]);
    return $result[0]["counter"];
  }
  
  public function getLocaliInEvidenzaHome() {
    $query = '
      SELECT 
        locations.seo_title as titlelink,
        locations.seo_url as url,
        locations.title as label,
        typo_btw_sites.title as category
      FROM locations
      LEFT JOIN typo_btw_sites ON locations.typo_id = typo_btw_sites.id
      WHERE 
        locations.site_id = ?
        AND locations.active = 1
		  AND locations.on_home = 1
    ';
    $result = $this->_getData($query, [
      $this->_site
    ]);
    return $result;    
  }
  
  public function getLocaliUltimiInseriti() {
    $query = '
      SELECT 
        locations.seo_title as titlelink,
        locations.seo_url as url,
        locations.title as label,
        typo_btw_sites.title as category
      FROM locations
      LEFT JOIN typo_btw_sites ON locations.typo_id = typo_btw_sites.id
      WHERE 
        locations.site_id = ?
        AND locations.active = 1
      ORDER BY locations.created_at DESC
		  LIMIT 0, 5
    ';
    $result = $this->_getData($query, [
      $this->_site
    ]);
    return $result;    
  }
  
  // da dismettere - usare quella bytypoid sotto
  public function getListCategoriaLocali($slug, $all=false, $pag=1, $nopagination=false) {
    $limitcond = '';
    if (!$nopagination) {
      $items_per_page = 10;
      $offset = ($pag - 1) * 10; 
      $limitcond = 'LIMIT ' . $offset . ', ' . $items_per_page;
    }
    $visibility_condition = "";
    if (!$all) {
      $visibility_condition = ' AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0) ';
    }
    $query = '
      SELECT
        location_visibilities.id as main_id,
        location_visibilities.level as level,
        location_visibilities.expire_at as expire_at,
        location_visibilities.location_id,
        typo_btw_sites.title as typo_title,
        locations.*
      FROM
        location_visibilities
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        ' . $visibility_condition . '
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
      ORDER BY
        location_visibilities.level DESC
      ' . $limitcond;

    $result = $this->_getData($query, [
      $this->_site,
      $slug
    ]);
    return $result;
  }
  
  public function getListCategoriaLocaliByTypoId($typo_id, $all=false, $pag=1, $nopagination=false) {
    $limitcond = '';
    if (!$nopagination) {
      $items_per_page = 10;
      $offset = ($pag - 1) * 10; 
      $limitcond = 'LIMIT ' . $offset . ', ' . $items_per_page;
    }
    $visibility_condition = "";
    if (!$all) {
      $visibility_condition = ' AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0) ';
    }
    $query = '
      SELECT
        location_visibilities.id as main_id,
        location_visibilities.level as level,
        location_visibilities.expire_at as expire_at,
        location_visibilities.location_id,
        typo_btw_sites.title as typo_title,
        locations.*
      FROM
        location_visibilities
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      LEFT JOIN
        typo_btw_sites 
        ON locations.typo_id = typo_btw_sites.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        ' . $visibility_condition . '
        AND location_visibilities.typo_id = ?
        AND locations.active = 1
      ORDER BY
        location_visibilities.level DESC,
        location_visibilities.expire_at DESC
      ' . $limitcond;

    /*echo $query;
    print_r([
      $this->_site,
      $typo_id
    ]);
    die();*/
    $result = $this->_getData($query, [
      $this->_site,
      $typo_id
    ]);
    return $result;
  }
  
  public function getListCategoriaLocaliLettera($slug, $all=false, $lettera) {
    $visibility_condition = "";
    if (!$all) {
      $visibility_condition = ' AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0) ';
    }
    $query = '
      SELECT
        location_visibilities.id as main_id,
        location_visibilities.level as level,
        location_visibilities.expire_at as expire_at,
        location_visibilities.location_id,
        typo_btw_sites.title as typo_title,
        locations.*
      FROM
        location_visibilities
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        ' . $visibility_condition . '
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
        AND locations.title like \'' . $lettera . '%\'
      ORDER BY
        location_visibilities.level DESC
    ';

    $result = $this->_getData($query, [
      $this->_site,
      $slug
    ]);
    return $result;
  }
  
  public function getListCategoriaLocaliZona($slug_categoria, $zona) {
    $query = '
      SELECT
        location_visibilities.level as level,
        location_visibilities.*,
		  locations.*
      FROM
        location_visibilities
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
		WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypoZone"
        AND zone_id = ?
		  AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0)
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
      ORDER BY
        location_visibilities.level DESC
    ';
    $result = $this->_getData($query, [
      $this->_site,
      $zona,
      $slug_categoria
    ]);
    return $result;  
  }
  
  public function getZonesListForCategoriaLocali($slug_categoria)
  {
    $query = '
      SELECT
        distinct(zones.id),
        zones.*
      FROM
        location_visibilities
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      RIGHT JOIN 
        zone_btw_locations
        ON zone_btw_locations.location_id = locations.id
		LEFT JOIN
		  zones
		  ON zone_btw_locations.zone_id = zones.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0)
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
      ORDER BY
        town
    ';
    $result = $this->_getData($query, [
      $this->_site,
      $slug_categoria
    ]);
    
    $z1 = [];
    $z2 = [];
    foreach ($result as $z) {
      if ($z["town"] == 0) {
        array_push($z1, $z);
      } else {
        array_push($z2, $z);
      }
    }
    return [$z1, $z2];
  }
  
  public function getZonesListForCategoriaLocaliLettera($slug_categoria, $lettera)
  {
    $query = '
      SELECT
        distinct(zones.id),
        zones.*
      FROM
        location_visibilities
      LEFT JOIN 
        typo_btw_sites
        ON location_visibilities.typo_id = typo_btw_sites.id
      LEFT JOIN
        locations
        ON location_visibilities.location_id = locations.id
      RIGHT JOIN 
        zone_btw_locations
        ON zone_btw_locations.location_id = locations.id
		LEFT JOIN
		  zones
		  ON zone_btw_locations.zone_id = zones.id
      WHERE
        location_visibilities.site_id = ?
        AND location_visibilities.type = "LocationVisibilityTypo"
        AND (location_visibilities.expire_at > NOW() || location_visibilities.level = 0)
        AND typo_btw_sites.seo_url = ?
        AND locations.active = 1
        AND locations.title like \'' . $lettera . '%\'
      ORDER BY
        town
    ';
    $result = $this->_getData($query, [
      $this->_site,
      $slug_categoria
    ]);
    
    $z1 = [];
    $z2 = [];
    foreach ($result as $z) {
      if ($z["town"] == 0) {
        array_push($z1, $z);
      } else {
        array_push($z2, $z);
      }
    }
    return [$z1, $z2];
  }
  
  public function getShowcase($id_locale)
  {
    $query = '
      SELECT *
      FROM location_showcases
      WHERE
        site_id = ?
        AND location_id = ?
      ORDER BY disposition
    ';
    $result = $this->_getData($query, [
      $this->_site,
      $id_locale
    ]);
    return $result;
  }
  
  public function getShowcases()
  {
    $query = '
      SELECT *
      FROM location_showcases
      WHERE
        site_id = ?
      ORDER BY disposition
    ';
    $result = $this->_getData($query, [
      $this->_site
    ]);
    return $result;
  }
  
  public function getEventsForLocale($id_locale) {
    $query = "
      SELECT * FROM event_btw_locations as L
      LEFT JOIN events as E ON L.event_id = E.id
      WHERE L.site_id = ?
      AND L.location_id = ?
      AND time_to > NOW()
      ORDER BY time_to DESC
    ";
    $result = $this->_getData($query, [
      $this->_site,
      $id_locale
    ]);
    return $result;
  }
  
  public function getEventsForLocalePast($id_locale) {
    $query = "
      SELECT * FROM (SELECT 
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
        AND event_btw_locations.location_id = ?
        AND time_to <= NOW()
      ORDER BY
        time_to DESC) AS A
        
      GROUP BY
        A.seo_url
      ORDER BY 
        time_to DESC
    ";
    $result = $this->_getData($query, [
      $this->_site,
      $id_locale
    ]);
    return $result;
  }
  
  public function getAllLocations() {
    $result = $this->_getData("SELECT * FROM locations WHERE site_id = ?", [$this->_site]);
    return $result;
  }
  
  public function getLocale($slug_locale) {
    $result = $this->_getData("SELECT * FROM locations WHERE seo_url = ?", [$slug_locale]);
    return $result[0];
  }
  
  public function search($phrase) {
    $locali = $this->_getData("
      SELECT
        *
      FROM
        locations
      WHERE
        locations.site_id = ?
        AND (
          seo_title LIKE ?
          OR address_way LIKE ?
          OR address_city LIKE ?
        )
    ", [
      $this->_site,
      '%' . $phrase . '%',
      '%' . $phrase . '%',
      '%' . $phrase . '%'
    ]);
    
    $eventi = $this->_getData("
      SELECT
        *
      FROM
        events
      WHERE
        events.site_id = ?
        AND (
          seo_title LIKE ?
          OR description LIKE ?
        )
    ", [
      $this->_site,
      '%' . $phrase . '%',
      '%' . $phrase . '%'
    ]);
    
    return [
      "locali" => $locali,
      "eventi" => $eventi
    ];
  }
  
  public function getLocalePhotos($id_locale)
  {
    $query = "
      SELECT
        *
      FROM
        photos
      WHERE
        site_id = ?
        AND photoable_type = 'Location'
        AND photoable_id = ?
        ORDER BY position, id
    ";
    $result = $this->_getData($query, [
      $this->_site,
      $id_locale
    ]);
    return $result;
  }
  
  public function getLocaleMap($id_locale)
  {
    $query = "
      SELECT
        *
      FROM
        maps
      WHERE
        site_id = ?
        AND mapable_type = 'Location'
        AND mapable_id = ?
    ";
    $result = $this->_getData($query, [
      $this->_site,
      $id_locale
    ]);
    return $result;
  }
  
  public function getEvento($slug)
  {
    $query = "
      SELECT
        events.*,
        locations.id as locations_id,
        locations.title as locations_title,
        locations.address_way as locations_address_way,
        locations.address_number as locations_address_number,
        locations.address_city as locations_address_city,
        locations.address_zip as locations_address_zip,
        locations.address_province as locations_address_province,
        locations.phone as locations_phone,
        locations.mobile as locations_mobile,
        locations.email as locations_email,
        locations.seo_url as locations_seo_url,
        locations.logo_file_name as locations_logo_file_name,
        events.time_to <= NOW() as expired
      FROM events
      LEFT JOIN event_btw_locations 
        ON events.id = event_btw_locations.event_id 
      LEFT JOIN locations
        ON event_btw_locations.location_id = locations.id

      WHERE events.seo_url = ?
    ";
    $result = $this->_getData($query, [$slug]);
    return $result[0];
  }
  
  public function getSection($slug) {
    $query = "
      SELECT * FROM sections
      WHERE seo_url = ?
      AND site_id = ?
    ";
    $result = $this->_getData($query, [$slug, $this->_site]);
    return $result[0];
  }
  
  public function getEventsFromDBbyCategory($cat_id) {
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
        
      LEFT JOIN cat_btw_events
        ON events.id = cat_btw_events.event_id
        
      WHERE
        events.site_id = ?
        AND time_to > NOW()
        AND locations.active = 1
        
        AND cat_btw_events.cat_id = ?
        
      GROUP BY
        events.seo_url
      ORDER BY
        time_to ASC
    ";
    $events = $this->_getData($query, [$this->_site, $cat_id]);
    
    return $events;
  }
  
  public function getEventsFromDBbyFestivita($holiday_id, $past=false) {
    $timecond = 'AND time_to > NOW()';
    $ordercond = 'time_to ASC';
    if ($past) {
      $timecond = 'AND time_to <= NOW()';
      $ordercond = 'time_to DESC';
    }
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
        
      LEFT JOIN holiday_btw_events
        ON events.id = holiday_btw_events.event_id
        
      WHERE
        events.site_id = ?
        $timecond
        AND locations.active = 1
        
        AND holiday_btw_events.holiday_id = ?
        
      GROUP BY
        events.seo_url
      ORDER BY
        $ordercond
    ";
    $events = $this->_getData($query, [$this->_site, $holiday_id]);
    
    return $events;
  }
}
