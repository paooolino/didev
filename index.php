<?php
//error_reporting(E_ALL);
ini_set("display_errors", 0);
set_time_limit(300);
setlocale(LC_TIME, "ita.UTF-8", "it_IT");
//$result = setlocale(LC_ALL, 0);
//var_dump($result);
//echo "::." . strftime("%B", time());
//die();

  require("vendor/autoload.php");
  
  // Create engine
  $machine = new \WebEngine\WebEngine();
  $machine->append_debug_infos = true;
  $machine->disabled_cache_routes = [
    "SEARCH",
    "ADMIN_DELETE",
    "SEND_OK",
    "FORM_ONLIST",
    "FORM_LOCATION_SUGGEST",
    "FORM_CONTACT",
    "FORM_PARTY",
    "ADMIN_LOGIN",
    "ADMIN",
    "ADMIN_GENERATE_THUMBNAILS",
    "ADMIN_TABLE",
    "ADMIN_NEWTABLE_EXT",
    "ADMIN_RECORD_NEWMAP",
    "ADMIN_RECORD_MAP",
    "ADMIN_RECORD_NEWSHOWCASE",
    "ADMIN_RECORD_SHOWCASE",
    "ADMIN_RECORD_NEWPHOTO",
    "ADMIN_RECORD_PHOTO",
    "ADMIN_NEWTABLE",
    "AJAX_SAVE",
    "ADMIN_RITAGLIO",
    "ADMIN_RECORD",
    "ADMIN_NEWTABLE",
    "CHECK_IMAGES",
    "UPLOAD",
    "CACHE_RESET",
    "GENERATE_SITEMAP",
    "ADMIN_CAT_ZONA_ORDINAMENTO"
  ];
  
  // Plugins
	$Link = $machine->addPlugin("Link");
	$UploadS3 = $machine->addPlugin("UploadS3");
	$DB = $machine->addPlugin("DB");
	$DiscosForms = $machine->addPlugin("DiscosForms");
  
	$machine->addPlugin("Image");
	$machine->addPlugin("Form");
  
  // DB plugin: BEFORE App
  //$dbopts = parse_url(getenv('VHOSTING_DATABASE_URL'));
  //$dbopts = parse_url("mysql://b8c687af90f0d3:8a9736f9@us-cdbr-east-06.cleardb.net/heroku_fe4025b10f6b406");
  //$dbopts = parse_url("mysql://root:tYmxYbNj1fnzSI7hiqq4w02Y7sxT8FpG@1fpu64.stackhero-network.com:3579/discos");
  //$dbopts = parse_url(getenv('LOCAL_DATABASE_URL'));
  $dbopts = parse_url(getenv('PRODUCTION_DATABASE_URL'));
  $conn = $DB->setupMySql(
    // host
    $dbopts["host"],
    // username
    $dbopts["user"],
    // password
    $dbopts["pass"],
    // db name
    ltrim($dbopts["path"],'/'),
    $dbopts["port"]
  );
  if (!is_object($conn)) { die("db connection error"); };

  // Set the site id
  // se non siamo su un dominio online, mi affido al cookie
  $domains = [
    "discotechebrescia.it",
    "discotechebergamo.it",
    "discotecheverona.it",
    "discotechedimilano.it",
    "discotechecremona.it",
    "discotechepiacenza.it",
    "discotecheriminiriccione.it",
    "discotechejesolo.it",
    
    "didev.bs",
    "didev.bg",
    "didev.vr",
    "didev.mi",
    "didev.cr",
    "didev.pc",
    "didev.ri",
    "didev.je"
  ];
  $is_online = false;
  foreach ($domains as $d) {
    if (stripos($_SERVER["HTTP_HOST"], $d) !== false) {
      $is_online = true;
      break;
    }
  }
  
  if ($is_online) {
    if (stripos($_SERVER["HTTP_HOST"], "discotechebrescia.it") !== false) { $DB->setSite(1); }
    if (stripos($_SERVER["HTTP_HOST"], "discotechebergamo.it") !== false) { $DB->setSite(2); }
    if (stripos($_SERVER["HTTP_HOST"], "discotecheverona.it") !== false) { $DB->setSite(3); }
    if (stripos($_SERVER["HTTP_HOST"], "discotechedimilano.it") !== false) { $DB->setSite(4); }
    if (stripos($_SERVER["HTTP_HOST"], "discotechecremona.it") !== false) { $DB->setSite(5); }
    if (stripos($_SERVER["HTTP_HOST"], "discotechepiacenza.it") !== false) { $DB->setSite(6); }
    if (stripos($_SERVER["HTTP_HOST"], "discotecheriminiriccione.it") !== false) { $DB->setSite(7); }
    if (stripos($_SERVER["HTTP_HOST"], "discotechejesolo.it") !== false) { $DB->setSite(8); }
    
    if (stripos($_SERVER["HTTP_HOST"], "didev.bs") !== false) { $DB->setSite(1); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.bg") !== false) { $DB->setSite(2); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.vr") !== false) { $DB->setSite(3); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.mi") !== false) { $DB->setSite(4); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.cr") !== false) { $DB->setSite(5); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.pc") !== false) { $DB->setSite(6); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.ri") !== false) { $DB->setSite(7); }
    if (stripos($_SERVER["HTTP_HOST"], "didev.je") !== false) { $DB->setSite(8); }
  } else {
    if (isset($_COOKIE["n_site"])) {
      $DB->setSite($_COOKIE["n_site"]);
    }
  }
  
  // The App plugin
  // AFTER DB
  $App = $machine->addPlugin("App");
  $App->is_online = $is_online;
  
  // The Backoffice plugin
  $Backoffice = $machine->addPlugin("Backoffice");
  $Backoffice->loadConfig("config/backoffice.json");
  
  // Page definitions
  $Link->setRoute("HOME", "/");
  
  $Link->setRoute("SEARCH", "/cerca");
  
  $Link->setRoute("EVENTI", "/eventi");
  $Link->setRoute("EVENTI_PASSATI_ALL", "/eventi-passati");
  $Link->setRoute("EVENTI_PAGINA", "/eventi/{pag}");
  $Link->setRoute("EVENTI_DATA", "/eventi-periodo/{yy}/{mm}/{gg}");
  $Link->setRoute("EVENTI_STASERA", "/eventi-stasera");
  $Link->setRoute("EVENTI_WEEKEND", "/eventi-weekend");
  $Link->setRoute("EVENTI_CATEGORIA", "/eventi-categoria/{evcategoria}");
  $Link->setRoute("EVENTO", "/evento/{slug_evento}");
  $Link->setRoute("EVENTI_PASSATI", "/eventi-passati-locale/{slug_locale}");
  $Link->setRoute("CALENDARIO", "/eventi-calendario/{anno}/{mese}");
  
  $Link->setRoute("CATEGORIA_LOCALI", "/locali/{slug_categoria}");
  $Link->setRoute("CATEGORIA_LOCALI_ORDERACTION", "/locali/{id_categoria}/orderaction");
  $Link->setRoute("CATEGORIA_LOCALI_PAG", "/locali/{slug_categoria}/{pag:\d+}");
  $Link->setRoute("CATEGORIA_ZONA", "/locali/{slug_categoria}/{slug_zona}");
  $Link->setRoute("ZONA_ORDERACTION", "/zona/{slug_zona}");
  $Link->setRoute("CATEGORIA_LOCALI_LETTERA", "/locali/{slug_categoria}/lettera/{lettera}");
  $Link->setRoute("LOCALE", "/locale/{slug_locale}");
  $Link->setRoute("AJAX_LOAD_ITEMS", "/ajax-load-items");
  $Link->setRoute("AJAX_LOAD_EVENTS", "/ajax-load-events");

  $Link->setRoute("ELENCO_FESTIVITA", "/festivita");
  $Link->setRoute("FESTIVITA", "/eventi-festivita/{slug_festivita}");
  $Link->setRoute("FESTIVITA_ARCHIVIO", "/eventi-passati-festivita/{slug_festivita}");
  
  $Link->setRoute("PAGINA", "/{slug_sezione}");
  $Link->setRoute("SEZIONE", "/sezione/{slug_sezione}");
  
  $Link->setRoute("ADMIN", "/admin");
  $Link->setRoute("ADMIN_LOGIN", "/admin/login");
  $Link->setRoute("ADMIN_GENERATE_THUMBNAILS", "/admin/generate-thumbnails");
  $Link->setRoute("ADMIN_TABLE", "/admin/{table}");
  $Link->setRoute("ADMIN_NEWTABLE", "/admin/new/{table}");
  $Link->setRoute("ADMIN_NEWTABLE_EXT", "/admin/new/{table}/ext");
  $Link->setRoute("ADMIN_RECORD", "/admin/{table}/{id}");
  
  $Link->setRoute("ADMIN_RECORD_NEWMAP", "/admin/{table}/{id}/new/maps");
  $Link->setRoute("ADMIN_RECORD_MAP", "/admin/{table}/{id}/maps/{id_map}");
  $Link->setRoute("ADMIN_RECORD_MAP_DELETE", "/admin/{table}/{id}/maps/{id_map}/delete");
  
  $Link->setRoute("ADMIN_RECORD_NEWSHOWCASE", "/admin/{table}/{id}/new/location_showcases");
  $Link->setRoute("ADMIN_RECORD_SHOWCASE", "/admin/{table}/{id}/location_showcases/{id_showcase}");
  $Link->setRoute("ADMIN_RECORD_SHOWCASE_DELETE", "/admin/{table}/{id}/location_showcases/{id_showcase}/delete");

  $Link->setRoute("ADMIN_RECORD_NEWPHOTO", "/admin/{table}/{id}/new/photos");
  $Link->setRoute("ADMIN_RECORD_PHOTO", "/admin/{table}/{id}/photos/{id_showcase}");
  $Link->setRoute("ADMIN_RECORD_PHOTO_DELETE", "/admin/{table}/{id}/photos/{id_showcase}/delete");

  $Link->setRoute("ADMIN_CAT_ZONA_ORDINAMENTO", "/admin/{id_cat}/{id_zone}/order");
  $Link->setRoute("ADMIN_RITAGLIO", "/admin/ritaglio/{table}/{id}/{fieldname}");
  $Link->setRoute("ADMIN_DELETE", "/admin/delete/{table}/{id}");
  $Link->setRoute("AJAX_SAVE", "/ajax/save");
  $Link->setRoute("AJAX_MODIFY_EVENT_DATE", "/ajax/modify-event-date");
  $Link->setRoute("SET_SITE_COOKIE", "/admin/set-site-cookie");
  
  $Link->setRoute("CHECK_IMAGES", "/tools/check-images");
  $Link->setRoute("UPLOAD", "/tools/upload/{table}/{id}/{fieldname}");
  $Link->setRoute("RITAGLIO", "/tools/ritaglio/nuovo/{table}/{id}");
  $Link->setRoute("CACHE_RESET", "/tools/cache-reset");
  $Link->setRoute("GENERATE_SITEMAP", "/tools/generate-sitemap");
  
  $Link->setRoute("FORM_ONLIST", "/form/mettiti-in-lista");
  $Link->setRoute("FORM_LOCATION_SUGGEST", "/form/inserisci-locale");
  $Link->setRoute("FORM_CONTACT", "/form/contatti");
  $Link->setRoute("FORM_PARTY", "/form/organizzare-feste");
  $Link->setRoute("FORM_NEWSLETTER", "/form/newsletter");
  $Link->setRoute("SEND_OK", "/form/send-ok");
  
  
  $machine->addPage("/rss/prossimi-eventi.xml", function($machine) {
    $DB = $machine->plugin("DB");
    $events = $DB->getEventsFromDB("AND events.active = 1");
    $currentCity = $DB->getCurrentCity();  
    
    $rss_items = '';
    foreach ($events as $ev) {
      $rss_items .= '
<item>
  <title>' . $ev["title"] . '</title>
  <link>https://www.' . $currentCity[0]["url"] . $ev["seo_url"] . '</link>
  <description><![CDATA[' . substr($ev["description"], 0, 120) . ']]></description>
  <guid>' . $currentCity[0]["url"] . $ev["seo_url"] . '</guid>
</item>
      ';
    }
    
    $title = count($events) . " eventi a " . $currentCity[0]["name"] . " - www." . $currentCity[0]["url"];
    $link = 'https://www.' . $currentCity[0]["url"];
    $description = "";
    $rss = '
<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
  <channel>
    <title>' . $title . '</title>
    <link>' . $link . '</link>
    <description>' . $description . '</description>
    ' . trim($rss_items) . '
  </channel>
</rss>
    ';
    
    header('Content-Type: application/rss+xml; charset=utf-8');
    echo trim($rss);
    die();
  });
  
  /**
   *  Home page
   */
  $machine->addPage($Link->getRoute("HOME"), function($machine) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $homeContent = $DB->getHomeContent();
    
    $specialEvents = $DB->getEventsFromDB("AND events.special = 1", "LIMIT 0,8");
    $hevents_big = array_slice($specialEvents, 0, 4);
    $hevents_small = array_slice($specialEvents, 4, 4);
    $currentCity = $DB->getCurrentCity();  
    
    $banner_dx = $DB->getBannerLandscape();

    return [
      "template" => "home.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "index home",
        "h1" => $homeContent["title"],
        "description" => $homeContent["description"],
        "seoDescription" => $homeContent["seo_description"],
        "seoKeywords" => $homeContent["seo_keyword"],
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "twitterTitle" => $homeContent["title"],
        "twitterDescription" => $homeContent["seo_description"],
        "homeslides" => $DB->getHomeslides(),
        "calendar" => $App->getCalendar(),
        "seoTitle" => $homeContent["seo_title"] . ".",
        "hevents_big" => $hevents_big,
        "hevents_small" => $hevents_small,
        "h2" => "eventi di locali e discoteche di " . $currentCity[0]["name"] . " da non perdere",
        "h2_2" => "discotechebrescia.it: la miglior vetrina per eventi, locali e ristoranti di " . $currentCity[0]["name"],
        "h3" => $homeContent["seo_footer"],
        "promo_description" => $homeContent["promo_description"],
        "promo_title" => $homeContent["promo_title"],
        "hboxes" => $DB->getHomeBoxes(),
        "banner_dx" => $banner_dx[0]
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("SEARCH"), function($machine) {
    $machine->plugin("DB")->disable_cache = true;
        
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $homeContent = $DB->getHomeContent();
    
    $results = $DB->search($_GET["phrase"]);

    $currentCity = $DB->getCurrentCity(); 
    return [
      "template" => "cerca.php",
      "data" => array_merge($App->getCommonData(), [
        "calendar" => $App->getCalendar(),
        "results" => $results,
        "seoTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia..",
        "seoDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca..",
        "seoKeywords" => "",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
      ])
    ];
  });  
  
  /**
   *  Eventi
   */
  $machine->addPage($Link->getRoute("EVENTI"), function($machine) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $current_page = 1;
    $section = $DB->getSection(str_replace("/", "", $Link->getRoute("EVENTI")));
    $events = $DB->getEventsFromDB("AND events.active = 1");
    $n_events = count($events);
    $list = array_slice($events, ($current_page-1) * 10, 10);
    $currentCity = $DB->getCurrentCity();  
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["title"],
        "seoTitle" => $section["seo_title"] . ".",
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"],
        "seoKeywords" => $section["seo_keyword"] . ".",
        "description" => $section["seo_description"],
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $list,
        "h3" => $section["seo_footer"],
        "archivelink" => $Link->Get("EVENTI_PASSATI_ALL"),
        "enable_ajax" => true,
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents()
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("EVENTI_PASSATI_ALL"), function($machine) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $current_page = 1;
    //$section = $DB->getSection(str_replace("/", "", $Link->getRoute("EVENTI_PASSATI_ALL")));
    $events = $DB->getEventsFromDB("AND events.active = 1", "", true);
    $n_events = count($events);
    $list = array_slice($events, ($current_page-1) * 10, 10);
    $currentCity = $DB->getCurrentCity();  
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => "Archivio eventi e serate",
        "mainSummary" => "Archivio eventi delle discoteche di " . $currentCity[0]["name"],
        "seoTitle" => "Archivio eventi delle discoteche di " . $currentCity[0]["name"] . ".",
        "title" => "Archivio eventi e serate",
        "seoDescription" => "In questa sezione potrete trovare l'archivio di tutti gli eventi e serate dei migliori locali e delle migliori discoteche di " . $currentCity[0]["name"] . " e provincia.",
        "seoKeywords" => "archivio eventi, archivio serate, serate discoteca, eventi discoteca, eventi " . $currentCity[0]["name"] . ".",
        "description" => "In questa sezione potrete trovare l'archivio di tutti gli eventi e serate dei migliori locali e delle migliori discoteche di " . $currentCity[0]["name"] . " e provincia",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "past" => true, // serve per la chiamata ajax
        "events" => $list,
        "h3" => "Archivio eventi e serate delle migliori discoteche e dei locali di " . $currentCity[0]["name"],
        "enable_ajax" => true,
        "catevents" => $DB->getCatEvents()
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("EVENTI_CATEGORIA"), function($machine, $evcategoria) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $sectionEv = $DB->getSection(str_replace("/", "", $Link->getRoute("EVENTI")));
    $evcat = $DB->getEvCategory($evcategoria);
    $events = $DB->getEventsFromDBbyCategory($evcat["id"]);
    $n_events = count($events);
    $currentCity = $DB->getCurrentCity();
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $evcat["seo_title"],
        "seoTitle" => $evcat["seo_title"] . ".",
        "title" => $evcat["title"],
        "mainSummary" => $evcat["title"],
        "seoDescription" => $evcat["seo_description"] . ".",
        "description" => $evcat["description"],
        "seoKeywords" => $evcat["seo_keyword"] . ".",
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "canonical" => $App->getCurrentUrl(),
        "pag" => 1,
        "events" => $events,
        "h3" => $evcat["seo_footer"],
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents(),
        "disableEventlistHeader" => true,
        "breadcrumbitems" => [
          [
            "title" => $sectionEv["title"],
            "link" => $Link->Get("EVENTI"),
            "label" => $sectionEv["title"]
          ]
        ]
      ])
    ];    
  });
  
  $machine->addPage($Link->getRoute("EVENTI_STASERA"), function($machine) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $section = $DB->getSection("eventi-oggi");
    $day = new \DateTime(date("Y") . "-" . date("m") . "-" . date("d"));
    $events = $App->getEventsForRange($day, $day);
    $n_events = count($events);

    $currentCity = $DB->getCurrentCity();
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["title"],
        "seoTitle" => $section["seo_title"],
        "title" => $section["title"],
        "description" => $section["seo_description"],
        "seoDescription" => $section["seo_description"] . ".",
        "seoKeywords" => $section["seo_keyword"] . ".",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $section["seo_footer"],
        "calendar" => $App->getCalendar(),
        "canonical" => $App->getCurrentUrl(),
        "disableEventlistHeader" => true,
        "disableEventsArchive" => true
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("EVENTI_PASSATI"), function($machine, $slug_locale) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $locale = $DB->getLocale($slug_locale);
    $cat = $DB->getCatById($locale["typo_id"]);
    $events = $DB->getEventsForLocalePast($locale["id"]);
    $n_events = count($events);
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $locale["title"],
        "seoTitle" => "Archivio eventi " . $locale["title"] . ".",
        "mainSummary" => "Archivio eventi passati",
        "description" => "",
        "title" => $locale["title"],
        "seoDescription" => "Visualizza tutto l′archivio degli eventi e delle serate di: " . $cat["title"] . " " . $locale["title"] . ". In questa sezione puoi scoprire tutte le serate e gli eventi organizzati da " . $locale["title"] . ".",
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => "Archivio eventi location",
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents(),
        "disableEventlistHeader" => true,
        "disableEventsArchive" => true,
        "breadcrumbitems" => [
          [
            "title" => "Archivio eventi e serate",
            "link" => $Link->Get("EVENTI_PASSATI_ALL"),
            "label" => "Archivio eventi e serate",
          ]
        ]
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("ADMIN_DELETE"), function($machine, $table, $id) {
    $machine->plugin("DB")->disable_cache = true;
    
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Backoffice = $machine->plugin("Backoffice");
    $Link = $machine->plugin("Link");
    
    // get record data
    $field_id = $Backoffice->getFieldId($table);
    $row = $Backoffice->getRecordData([
      "table" => $table,
      "field_id" => $field_id,
      "id" => $id
    ]);

    // TO DO: delete files
    // ...
    
    // TO DO: delete manytomany
    // ...
    
    // delete record
    $query = "DELETE FROM $table WHERE $field_id = ?";
    $data = [$id];
    $DB->delete($query, $data);

    // delete recurrents
    if ($table == "recurrents") {
      $query = "DELETE FROM events WHERE recurrent_id = ?";
      $data = [$id];
      $DB->delete($query, $data);
    }
    
    // redirect
    $machine->redirect($Link->Get(["ADMIN_TABLE", $table]));
  });
  
  $machine->addPage($Link->getRoute("EVENTI_WEEKEND"), function($machine) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $section = $DB->getSection("eventi-weekend");
    $day = new \DateTime(date("Y") . "-" . date("m") . "-" . date("d"));
    $events = $App->getNextWeekendEvents();
    $n_events = count($events);
    
    $currentCity = $DB->getCurrentCity();

    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["title"],
        "seoTitle" => $section["seo_title"] . ".",
        "title" => $section["title"],
        "description" => $section["seo_description"],
        "seoDescription" => $section["seo_description"] . ".",
        "seoKeywords" => $section["seo_keyword"] . ".",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 50),
        "pag" => 1,
        "events" => $events,
        "h3" => $section["seo_footer"],
        "calendar" => $App->getCalendar(),
        "canonical" => $App->getCurrentUrl(),
        "next" => $App->getCurrentUrl() . "/2",
        "calendar" => $App->getCalendar(),
        "disableEventlistHeader" => true,
        "disableEventsArchive" => true
      ])
    ];
  });
    
  $machine->addPage($Link->getRoute("EVENTI_DATA"), function($machine, $anno, $mese, $giorno) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    
    $day1 = \DateTime::createFromFormat("Y-n-j", $anno . "-" . $mese . "-" . $giorno);
    $day2 = \DateTime::createFromFormat("Y-n-j", $anno . "-" . $mese . "-" . $giorno);
    $events = $App->getEventsForRange($day1, $day2);
    $n_events = count($events);
    $currentCity = $DB->getCurrentCity();
    $cityname = $currentCity[0]["name"];
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "seoTitle" => "Eventi $cityname - " . strftime("%A %e %B %Y", $day1->getTimestamp()) . ".",
        "title" => "Eventi",
        "mainSummary" => "Eventi $cityname " . strftime("%A %e %B %Y", $day1->getTimestamp()),
        "h2" => "Scopri tutti gli eventi in programma a $cityname per " . strftime("%A %e %B %Y", $day1->getTimestamp()),
        "description" => "",
        "seoDescription" => "Eventi a $cityname nei migliori locali e discoteche. Scopri gli eventi a $cityname e provincia di -  " . strftime("%A %e %B %Y", $day1->getTimestamp()) . ".",
        "seoKeywords" => "eventi $cityname, eventi discoteche, eventi discoteca, eventi provincia.",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "n_events" => $n_events,
        "events" => $events,
        "canonical" => $App->getCurrentUrl(),
        "h3" => "Eventi " . strftime("%A %e %B %Y", $day1->getTimestamp()),
        "calendar" => $App->getCalendar(),
        "disableEventsArchive" => true,
        "disableEventlistHeader" => true,
        "showaperti" => true,
        "aperti_summary" => "Locali, discoteche, disco bar, ristoranti e birrerie di $cityname aperti " . strftime("%A %e %B %Y", $day1->getTimestamp())
      ])
    ];
  });
 
  /**
   *  Solo calendario
   */
  $machine->addPage($Link->getRoute("CALENDARIO"), function($machine, $anno, $mese) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    
    $currentCity = $DB->getCurrentCity();
    return [
      "template" => "calendario.php",
      "data" => [
        "currentCity" => $currentCity[0]["name"],
        "calendar" => $App->getCalendar($anno, $mese)
      ]
    ];
  });
  
  /**
   *  Eventi
   */
  $machine->addPage($Link->getRoute("EVENTI_PAGINA"), function($machine, $pag) {
    $App = $machine->plugin("App");
    $topBanner = $App->getRandomTopBanner();
    
    $n_events = $App->getEvents(null, true);
    return [
      "template" => "eventi.php",
      "data" => [
        "bodyclass" => "events next_events",
        "cities" => $DB->getCities(),
        "menuitems" => $App->getMenuitems(),
        "navbanners" => $App->getNavbanners(),
        "navtopitems" => $DB->getNavtopitems(),
        "linktopitems" => $App->getLinktopitems(),
        "topBannerTitle" => $topBanner["title"],
        "topBannerUrl" => $topBanner["url"],
        "topBannerImage" => $topBanner["image_file_name"],
        
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => $pag,
        "events" => $App->getEvents($pag)
      ]
    ];
  });
  
  $machine->addPage($Link->getRoute("EVENTO"), function($machine, $slug) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    $topBanner = $DB->getRandomTopBanner();
    $evento = $DB->getEvento($slug);
    
    if ($evento !== null) {
      $currentCity = $DB->getCurrentCity();  
      
      $logo_img = $App->img("locations", $evento["locations_id"], "W", 155, $evento["locations_logo_file_name"], "logos");
      $event_image = "";
      if (is_null($evento["recurrent_id"]) && $evento["image_file_name"] != "") {
        $event_image = $App->img(
          "events", $evento["id"], 315, 177,
          $evento["image_file_name"]
        );
      } else {
        if (isset($evento["recurrent_image"]) && $evento["recurrent_image"] != "") {
          $event_image = $App->img(
            "recurrents", $evento["recurrent_id"], 315, 177,
            $evento["recurrent_image"]
          );
        }
      }
      
      return [
        "template" => "evento.php",
        "data" => array_merge($App->getCommonData(), [
          "bodyclass" => "events event",
          "seoTitle" => $evento["seo_title"],
          "logo_img" => $logo_img,
          "event_image" => $event_image,
          "evento" => $evento,
          "photos" => $DB->getEventPhotos($evento["id"]),
          "h3" => $evento["seo_footer"],
          "seoDescription" => $evento["seo_description"],
          "seoKeywords" => $evento["seo_keyword"],
          "ogTitle" => $evento["title"],
          "ogDescription" => $evento["seo_description"],
          "ogUrl" => $App->getCurrentUrl(),
          "ogSiteName" => $currentCity[0]["title_big"],
          "currentCity" => $currentCity[0]["name"],
          "canonical" => $App->getCurrentUrl(),
          "twitterTitle" => $evento["seo_title"],
          "twitterDescription" => $evento["seo_description"],
          "map" => $DB->getLocaleMap($evento["locations_id"]),
          "calendar" => $App->getCalendar(),
          "ogImage" => $logo_img
        ])
      ];
    } else {
      $machine->redirect($Link->Get(["HOME"]));
    }
  });
  
  /**
   *  Locali
   */
   
  $machine->addPage($Link->getRoute("CATEGORIA_LOCALI"), function($machine, $slug_categoria) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    $cat = $DB->getCategoriaLocali($slug_categoria);
    if ($cat === NULL) {
      $machine->redirect($Link->Get("HOME"));
      return;
    }
    
    $current_page = 1;
    //$tutti = $DB->getListCategoriaLocali($slug_categoria, false, 1, true);
    $tutti = $DB->getListCategoriaLocaliByTypoId($cat["id"], false, 1, true);
    $list = array_slice($tutti, ($current_page-1) * 10, 10);
    
    $empty_letters = $App->getEmptyLetters(array_map(function($item) {
      return $item["title"];
    }, $tutti));
    
    list($z1, $z2) = $DB->getZonesListForCategoriaLocali($slug_categoria);
    $currentCity = $DB->getCurrentCity();  
    return [
      "template" => "categoria_locali.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "list" => $list,
        "ntot" => count($tutti),
        "slug_categoria" => $slug_categoria,
        "z1" => $z1,
        "z2" => $z2,
        "bodyclass" => "locations typology_locations",
        "seoTitle" => $cat["seo_title"],
        "h3" => $cat["seo_footer"],
        "current_page" => $current_page,
        "seoDescription" => $cat["seo_description"],
        "seoKeywords" => $cat["seo_keyword"],
        "ogTitle" => $cat["title"],
        "ogDescription" => $cat["seo_description"],
        "ogSiteName" => $currentCity[0]["title_big"],
        "currentCity" => $currentCity[0]["name"],
        "empty_letters" => $empty_letters,
        "twitterTitle" => "",
        "twitterDescription" => "",
        "canonical" => $App->getCurrentUrl(),
        "enable_ajax" => true,
        "calendar" => $App->getCalendar(),
        "next" => $App->getCurrentUrl() . "/2",
      ])
    ];
  });   

  $machine->addAction($Link->getRoute("AJAX_LOAD_ITEMS"), "POST", function($machine) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $cat = $DB->getCategoriaLocali($_POST["slug"]);
    
    //$list = $DB->getListCategoriaLocali($_POST["slug"], false, $_POST["page"]);
    $list = $DB->getListCategoriaLocaliByTypoId($cat["id"], false, $_POST["page"]);
    $html = '';
    foreach ($list as $item) {
      $html .= $machine->populateTemplate($App->printLocaleItem($item), []);
    }
    echo $html;
    die();
  });
  
  $machine->addAction($Link->getRoute("AJAX_LOAD_EVENTS"), "POST", function($machine) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    
    $offset = ($_POST["page"] - 1) * 10;
    $list = $DB->getEventsFromDB(
      "AND events.active = 1", 
      "LIMIT $offset, 10", 
      $_POST["past"] == "true" ? true : false
    );
    $html = '';
    foreach ($list as $event) {
      $html .= $machine->populateTemplate($App->printEvento($event), []);
    }
    echo $html;
    die();
  });
  
  $machine->addPage($Link->getRoute("CATEGORIA_LOCALI_PAG"), function($machine, $slug_categoria, $pag) {
    $Link = $machine->plugin("Link");
    if ($pag == 1) {
      $machine->redirect($Link->Get(["CATEGORIA_LOCALI", $slug_categoria]));
      return;
    }
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $cat = $DB->getCategoriaLocali($slug_categoria);
    list($z1, $z2) = $DB->getZonesListForCategoriaLocali($slug_categoria);
    return [
      "template" => "categoria_locali.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "list" => $DB->getListCategoriaLocali($slug_categoria, false, $pag),
        "ntot" => $DB->countListCategoriaLocali($slug_categoria),
        "z1" => $z1,
        "z2" => $z2,
        "slug_categoria" => $slug_categoria,
        "current_page" => $pag,
        "bodyclass" => "locations typology_locations",
        "seoTitle" => $cat["seo_title"],
        "h3" => $cat["seo_footer"],
        "calendar" => $App->getCalendar()
      ])
    ];
  }); 
  
  $machine->addPage($Link->getRoute("CATEGORIA_LOCALI_LETTERA"), function($machine, $slug_categoria, $lettera) {
    $Link = $machine->plugin("Link");
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $cat = $DB->getCategoriaLocali($slug_categoria);
    list($z1, $z2) = $DB->getZonesListForCategoriaLocaliLettera($slug_categoria, $lettera);
    //$tutti = $DB->getListCategoriaLocali($slug_categoria, false, 1, true);
    $tutti = $DB->getListCategoriaLocaliByTypoId($cat["id"], false, 1, true);
    $empty_letters = $App->getEmptyLetters(array_map(function($item) {
      return $item["title"];
    }, $tutti));
    
    $list = $DB->getListCategoriaLocaliLettera($slug_categoria, false, $lettera);
    
    $currentCity = $DB->getCurrentCity();  
    return [
      "template" => "categoria_locali.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "list" => $list,
        "ntot" => count($list),
        "z1" => $z1,
        "z2" => $z2,
        "slug_categoria" => $slug_categoria,
        "bodyclass" => "locations typology_locations",
        "seoTitle" => $cat["seo_title"] . ' - Lettera ' . $lettera . '.',
        "seoDescription" => $cat["seo_description"] . ' - Lettera ' . $lettera . '.',
        "seoKeywords" => $cat["seo_keyword"],
        "ogTitle" => $cat["title"],
        "ogDescription" => $cat["seo_description"] . ' - Lettera ' . $lettera . '.',
        "ogSiteName" => $currentCity[0]["title_big"],
        "currentCity" => $currentCity[0]["name"],
        "twitterTitle" => "",
        "twitterDescription" => "",
        "canonical" => $App->getCurrentUrl(),
        "empty_letters" => $empty_letters,
        "h3" => $cat["seo_footer"],
        "current_letter" => $lettera,
        "no_calendar" => true
      ])
    ];
  }); 
  
  $machine->addPage($Link->getRoute("CATEGORIA_ZONA"), function($machine, $slug_categoria, $slug_zona) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    $cat = $DB->getCategoriaLocali($slug_categoria);
    $zona = $DB->getZona($slug_zona);
    if ($zona === NULL) {
      $machine->redirect($Link->Get("HOME"));
      return;
    }
    
    return [
      "template" => "categoria_locali-zona.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "zona" => $zona,
        "list" => $DB->getListCategoriaLocaliZona($slug_categoria, false, $zona["id"]),
        "bodyclass" => "locations typology_locations",
        "seoTitle" => $cat["seo_title"],
        "h3" => $cat["seo_footer"],
        "calendar" => $App->getCalendar()
      ])
    ];
  });   
  
  $machine->addPage($Link->getRoute("LOCALE"), function($machine, $slug_locale) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    
    $locale = $DB->getLocale($slug_locale);
    $cat = $DB->getCatById($locale["typo_id"]);
    
    // logo
    $logo_img = $App->img("locations", $locale["id"], 157, 157, $locale["logo_file_name"], "logos");
     
    $showcase = $DB->getShowcase($locale["id"]);
    $img = "";
    if (count($showcase) > 0) {
      if ($showcase[0]["image_fingerprint"] != null) {
        $img = $App->img("location_showcases", $showcase[0]["id"], 1335, 516, $showcase[0]["image_fingerprint"] . "-" . $showcase[0]["image_file_name"]);
      } else {
        $img = $App->img("location_showcases", $showcase[0]["id"], 1335, 516, $showcase[0]["image_file_name"]);
      }
    }
    
    $locale_events = $DB->getEventsForLocale($locale["id"]);
    $past_events = $DB->getEventsForLocalePast($locale["id"]);
    
    $currentCity = $DB->getCurrentCity(); 
    
    return [
      "template" => "locale.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "locations location",
        "seoTitle" => $locale["seo_title"] . ".",
        "cat" => $cat,
        "locale" => $locale,
        "logo_img" => $logo_img,
        "showcase" => $showcase,
        "canonical" => $App->getCurrentUrl(),
        "locale_events" => $locale_events,
        "past_events" => $past_events,
        "photos" => $DB->getLocalePhotos($locale["id"]),
        "map" => $DB->getLocaleMap($locale["id"]),
        "h3" => $locale["seo_footer"],
        "seoDescription" => $locale["seo_description"],
        "seoKeywords" => $locale["seo_keyword"],
        "ogTitle" => $locale["title"],
        "ogDescription" => $locale["seo_description"],
        "ogSiteName" => $currentCity[0]["title_big"],
        "twitterTitle" => $locale["seo_title"],
        "twitterDescription" => $locale["seo_description"] 
          . ' -- ' . $locale["address_city"] . ',  (' . $locale["address_province"] . ')',
        "ogImage" => $img
      ])
    ];
  });   
  
  /**
   *  Festività
   */
  
  $machine->addPage($Link->getRoute("ELENCO_FESTIVITA"), function($machine) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $section = $DB->getSection(str_replace("/", "", $Link->getRoute("ELENCO_FESTIVITA")));
    $events = $DB->getElencoFestivita();
    $currentCity = $DB->getCurrentCity();

    return [
      "template" => "festivita.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["seo_title"],
        "seoTitle" => $section["seo_title"] . ".",
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"] . ".",
        "description" => $section["description"],
        "seoKeywords" => $section["seo_keyword"] . ".",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "canonical" => $App->getCurrentUrl(),
        "events" => $events,
        "h3" => $section["seo_footer"],
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents(),
        "section" => $section,
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("FESTIVITA"), function($machine, $slug_festivita) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $festivita = $DB->getEvFestivita($slug_festivita);
    $events = $DB->getEventsFromDBbyFestivita($festivita["id"]);
    $n_events = count($events);
    $currentCity = $DB->getCurrentCity();
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $festivita["seo_title"],
        "mainSummary" => $festivita["title"], 
        "seoTitle" => $festivita["seo_title"] . " " . date("Y") . ".",
        "title" => $festivita["seo_title"],
        "seoDescription" => $festivita["seo_description"],
        "description" => $festivita["description"],
        "description2" => $festivita["description2"],
        "seoKeywords" => $festivita["seo_keyword"] . ".",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $festivita["seo_footer"],
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents(),
        "canonical" => $App->getCurrentUrl(),
        "disableEventlistHeader" => true,
        "archivelabel" => "Archivio eventi " . $festivita["title"],
        "archivelink" => $Link->Get(["FESTIVITA_ARCHIVIO", $slug_festivita]),
        "breadcrumbitems" => []
      ])
    ];
  }); 
  
  $machine->addPage($Link->getRoute("FESTIVITA_ARCHIVIO"), function($machine, $slug_festivita) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    $DB = $machine->plugin("DB");
    
    $festivita = $DB->getEvFestivita($slug_festivita);
    $events = $DB->getEventsFromDBbyFestivita($festivita["id"], true);
    $n_events = count($events);
    $currentCity = $DB->getCurrentCity();
    
    $description = '<a class="button radius" title="' . $festivita["title"] . '" href="' . $Link->Get(["FESTIVITA", $slug_festivita]) . '">Accedi agli eventi ' . $festivita["title"] . ' aggiornati</a>';
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => "Questi eventi riguardano gli anni scorsi!",
        "mainSummary" => "Archivio eventi passati " . $festivita["title"],// . " a " . $currentCity[0]["name"], 
        "seoTitle" => $festivita["seo_title"] . " " . date("Y") . ".",
        "title" => $festivita["seo_title"],
        "seoDescription" => $festivita["seo_description"],
        "description" => $description,
        "seoKeywords" => $festivita["seo_keyword"] . ".",
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $festivita["seo_footer"],
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents(),
        "canonical" => $App->getCurrentUrl(),
        "disableEventlistHeader" => true,
        "disableEventsArchive" => true,
        "breadcrumbitems" => []
      ])
    ];
  });
  
  /**
   *  Altro
   */
   
  $machine->addPage($Link->getRoute("SEZIONE"), function($machine, $slug_sezione) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $topBanner = $DB->getRandomTopBanner();
    $section = $DB->getSection($slug_sezione);
    
    $currentCity = $DB->getCurrentCity();
    
    return [
      "template" => "sezione.php",
      "data" => array_merge($App->getCommonData(), [
        "seoTitle" => $section["seo_title"] . ".",
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"],
        "seoKeywords" => $section["seo_keyword"] . ".",
        "description" => $section["description"],
        "ogTitle" => "Discoteche, locali, eventi, ristoranti etnici, birrerie a " . $currentCity[0]["name"] . " e provincia.",
        "ogDescription" => "Eventi e info di discoteche, locali, ristoranti (etnici, giapponesi...), birrerie e pub a " . $currentCity[0]["name"] . " e provincia. Discoteche" . $currentCity[0]["name"] . " organizza feste e eventi a " . $currentCity[0]["name"] . " dall′aperitivo alla discoteca.",
        "ogUrl" => $App->getCurrentUrl(),
        "ogSiteName" => $currentCity[0]["title_big"],
        "currentCity" => $currentCity[0]["name"],
        "canonical" => $App->getCurrentUrl(),
        "h3" => $section["seo_footer"],
        "bodyclass" => "sections section",
        "section" => $section,
        "calendar" => $App->getCalendar()
      ])
    ];
  }); 
  
  $machine->addPage($Link->getRoute("SEND_OK"), function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $topBanner = $DB->getRandomTopBanner();
    return [
      "template" => "send_ok.php",
      "data" => array_merge($App->getCommonData(), [
        "seoTitle" => "Richiesta inviata",
        "h3" => "Richiesta inviata",
        "bodyclass" => "sections section",
        "calendar" => $App->getCalendar()
      ])
    ];
  }); 

  /**
   *  Forms
   */
  $machine->addAction($Link->getRoute("FORM_ONLIST"), "POST", function($machine) {
    $machine->plugin("DB")->disable_cache = true;

    if (isset($_POST["form_onlist"]["nickname"]) && $_POST["form_onlist"]["nickname"] != "")
      die("Sorry, You have been identified as spam.");
    
    if (
      stripos($_POST["form_onlist"]["info"], "http://") !== false
      || stripos($_POST["form_onlist"]["info"], "https://") !== false
    ) {
      die("Sorry, the message can not contain links.");
    }
    
    if (strlen($_POST["form_onlist"]["info"]) > 600)
      die("Sorry, the message is too long.");
    
    $machine->plugin("DiscosForms")->send(
      "Mettiti in lista",
      $_POST["form_onlist"],
      $machine->plugin("DB")->getCodeCustomMail()
    );
    $machine->plugin("App")->iubenda_register_consent(
      $_POST["form_onlist"]["email"], 
      $_POST["form_onlist"]["name"], 
      $_POST["form_onlist"]["surname"], 
      "form_onlist", 
      json_encode($_POST["form_onlist"]), 
      $_POST["form_onlist"]["newsletter"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  $machine->addAction($Link->getRoute("FORM_LOCATION_SUGGEST"), "POST", function($machine) {
    $machine->plugin("DB")->disable_cache = true;

    if (isset($_POST["form_location_suggest"]["nickname"]) && $_POST["form_location_suggest"]["nickname"] != "")
      die("Sorry, You have been identified as spam.");
    
    if (
      stripos($_POST["form_location_suggest"]["info"], "http://") !== false
      || stripos($_POST["form_location_suggest"]["info"], "https://") !== false
    ) {
      die("Sorry, the message can not contain links.");
    }
    
    if (strlen($_POST["form_location_suggest"]["info"]) > 600)
      die("Sorry, the message is too long.");
    
    $machine->plugin("DiscosForms")->send(
      "Inserisci locale",
      $_POST["form_location_suggest"],
      $machine->plugin("DB")->getCodeCustomMail()
    );
    $machine->plugin("App")->iubenda_register_consent(
      $_POST["form_location_suggest"]["email"], 
      $_POST["form_location_suggest"]["name"], 
      $_POST["form_location_suggest"]["surname"], 
      "form_location_suggest", 
      json_encode($_POST["form_location_suggest"]), 
      $_POST["form_location_suggest"]["newsletter"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  $machine->addAction($Link->getRoute("FORM_CONTACT"), "POST", function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    if (isset($_POST["form_contact"]["nickname"]) && $_POST["form_contact"]["nickname"] != "")
      die("Sorry, You have been identified as spam.");
    
    if (
      stripos($_POST["form_contact"]["info"], "http://") !== false
      || stripos($_POST["form_contact"]["info"], "https://") !== false
    ) {
      die("Sorry, the message can not contain links.");
    }
    
    if (strlen($_POST["form_contact"]["info"]) > 600)
      die("Sorry, the message is too long.");

    $machine->plugin("DiscosForms")->send(
      "Contatti",
      $_POST["form_contact"],
      $machine->plugin("DB")->getCodeCustomMail()
    );
    $machine->plugin("App")->iubenda_register_consent(
      $_POST["form_contact"]["email"], 
      $_POST["form_contact"]["name"], 
      $_POST["form_contact"]["surname"], 
      "form_contact", 
      json_encode($_POST["form_contact"]), 
      $_POST["form_contact"]["newsletter"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  $machine->addAction($Link->getRoute("FORM_PARTY"), "POST", function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    if (isset($_POST["form_party"]["nickname"]) && $_POST["form_party"]["nickname"] != "")
      die("Sorry, You have been identified as spam.");
    
    if (
      stripos($_POST["form_party"]["info"], "http://") !== false
      || stripos($_POST["form_party"]["info"], "https://") !== false
    ) {
      die("Sorry, the message can not contain links.");
    }
    
    if (strlen($_POST["form_party"]["info"]) > 600)
      die("Sorry, the message is too long.");
    
    $machine->plugin("DiscosForms")->send(
      "Organizza festa",
      $_POST["form_party"],
      $machine->plugin("DB")->getCodeCustomMail()
    );
    $machine->plugin("App")->iubenda_register_consent(
      $_POST["form_party"]["email"], 
      $_POST["form_party"]["name"], 
      $_POST["form_party"]["surname"], 
      "form_party", 
      json_encode($_POST["form_party"]), 
      $_POST["form_party"]["newsletter"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });

  $machine->addAction($Link->getRoute("FORM_NEWSLETTER"), "POST", function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    if (isset($_POST["form_newsletter_subscription"]["nickname"]) && $_POST["form_newsletter_subscription"]["nickname"] != "")
      die("Sorry, You have been identified as spam.");
    
    $machine->plugin("DiscosForms")->send(
      "Iscrizione alla Newsletter",
      $_POST["form_newsletter_subscription"],
      $machine->plugin("DB")->getCodeCustomMail()
    );
    $machine->plugin("App")->iubenda_register_consent(
      $_POST["form_newsletter_subscription"]["email"], 
      $_POST["form_newsletter_subscription"]["name"], 
      $_POST["form_newsletter_subscription"]["surname"], 
      "form_newsletter_subscription", 
      json_encode($_POST["form_newsletter_subscription"]), 
      true
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  /**
   *  Backoffice home
   */
  $machine->addAction($Link->getRoute("ADMIN_LOGIN"), "POST", function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    
    $username = $_POST["username"];
    $password = md5(md5($_POST["password"]));
    
    $result = $machine->plugin("DB")->select("
      SELECT * FROM administrators WHERE email = ? AND encrypted_password = ?
    ", [$username, $password]);

    if (count($result) == 1) {
      $machine->setCookie("auth", md5($username . $App->AUTH_SALT . $password), 0, "/");
    }
    $machine->redirect($Link->Get("ADMIN"));
  });
    
  $machine->addPage($Link->getRoute("ADMIN_LOGIN"), function($machine) {
    $machine->plugin("DB")->disable_cache = true;    
    
    return [
      "template" => "login.php",
      "data" => []
    ];
  });
  
  $machine->addPage($Link->getRoute("ADMIN"), function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
    
      $App = $machine->plugin("App");
      $DB = $machine->plugin("DB");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();  
      return [
        "template" => "admin.php",
        "data" => [
          "seoTitle" => "Pannello di amministrazione",
          "bodyclass" => "",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $cities,
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione"
        ]
      ];
    }
  });
  
  $machine->addAction($Link->getRoute("SET_SITE_COOKIE"), "GET", function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    $admin = (isset($_GET["admin"]) && $_GET["admin"] == 1) ? true : false;
    if ($admin && !$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      setcookie("n_site", $_GET["n_site"], 0, "/");
      if ($_GET["admin"] == 0) {
        $machine->redirect($machine->plugin("Link")->Get("HOME"));
      } else {
        $machine->redirect($machine->plugin("Link")->Get("ADMIN"));
      }
    }
  });
   
  $machine->addPage($Link->getRoute("ADMIN_GENERATE_THUMBNAILS"), function($machine) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    if (false) { //(!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {  
      $images = [
        "banners" => [
          "sizes" => ["205,H", "311,H", "653_H", "728_90", "W,40"]
        ],
        "events" => [
          "sizes" => ["217,124", "311,175", "315,177"]
        ],
        "holidays" => [
          "sizes" => ["315,215"]
        ],
        "home_boxes" => [
          "sizes" => ["311,190"]
        ],
        "home_slides" => [
          "sizes" => ["994,590"]
        ],
        "location_showcases" => [
          "sizes" => ["1335,516"]
        ],
        "locations" => [
          "sizes" => ["157,157", "248,224", "W_155"]
        ],
        "photos" =>[
          "sizes" => ["800_H", "84,56"]
        ],
        "recurrents" => [
          "sizes" => []
        ],
        "reports" => [
          "sizes" => []
        ],
        "typos" => [
          "sizes" => ["W_57"]
        ]
      ];
      
      $banners = $DB->getBanners();
      foreach ($banners as $banner) {
        // ["205,H", "311,H", "653_H", "728_90", "W,40"]
        $App->img("banners", $banner["id"], 205, "H", $banner["image_file_name"]);
        $App->img("banners", $banner["id"], 311, "H", $banner["image_file_name"]);
        $App->img("banners", $banner["id"], 653, "H", $banner["image_file_name"]);
        $App->img("banners", $banner["id"], 728, 90, $banner["image_file_name"]);
        $App->img("banners", $banner["id"], "W", 40, $banner["image_file_name"]);
      }

      $events = $DB->getEventsFromDB();
      foreach ($events as $event) {
        //"217,124", "311,175", "315,177"
        $App->img("events", $event["id"], 217, 124, $event["image_file_name"]);
        $App->img("events", $event["id"], 311, 175, $event["image_file_name"]);
        $App->img("events", $event["id"], 315, 177, $event["image_file_name"]);
      }
      
      $holidays = $DB->getElencoFestivita();
      foreach ($holidays as $holiday) {
        $App->img("holidays", $holiday["id"], 315, 215, $holiday["image_file_name"]);
      }
      
      $hboxes = $DB->getHomeBoxes();
      foreach ($hboxes as $box) {
        $App->img("home_boxes", $box["id"], 311, 190, $box["image_file_name"]);
      }      
      
      $homeslides = $DB->getHomeslides();
      foreach ($homeslides as $slide) {
        $imgurl = $App->img("home_slides", $slide["id"], 994, 590, $slide["image_file_name"]);
      }
      
      $showcases = $DB->getShowcases();
      foreach ($showcases as $showcase) {
        $App->img("location_showcases", $showcase["id"], 1335, 516, $showcase["image_fingerprint"] . "-" . $showcase["image_file_name"]);
      }

      $locations = $DB->getAllLocations();
      foreach ($locations as $location) {
        $App->img("locations", $item["id"], 248, 224, $location["logo_file_name"], "logos");
        // mancano 157x157, W_155
      }
      
      // TO DO
      // photos, typos
    }
  });
  
  /**
   *  Backoffice: table list
   */
  $machine->addPage($Link->getRoute("ADMIN_TABLE"), function($machine, $table) {
    $machine->plugin("DB")->disable_cache = true;
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
    
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();
      
      $disable_new = false;
      //if ($table == "typo_btw_sites") { $disable_new = true; }
      //if ($table == "cat_btw_sites") { $disable_new = true; }
      //if ($table == "holiday_btw_sites") { $disable_new = true; }
      //if ($table == "report_btw_sites") { $disable_new = true; }
      return [
        "template" => "admin_table.php",
        "data" => [
          "table" => $table,
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $cities,
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione / " . $table,
          "disable_new" => $disable_new,
          "tableHtml" => $Backoffice->getTableHtml([
            "table" => $table,
            "field_id" => $Backoffice->getFieldId($table),
            "orderby" => $Backoffice->getOrderByField($table),
            "fields_list" => $Backoffice->getFeaturedFields($table),
            "edit_page" => $Link->Get(["ADMIN_RECORD", $table, "{{id}}"])
          ])
        ]
      ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_NEWTABLE_EXT"), function($machine, $table) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
    $DB = $machine->plugin("DB");
    $Form = $machine->plugin("Form");
    $Link = $machine->plugin("Link");
    $Backoffice = $machine->plugin("Backoffice");
    $cities = $DB->getCities();
    $currentCity = $DB->getCurrentCity();    
    
    $orphans = array_map(function($item) {
      return [$item["id"], $item["logic_title"]];
    }, $Backoffice->getExternOrphans($table));
    $html = "";
    $html .= '<form action="' . $Link->Get(["ADMIN_NEWTABLE", $table]) . '" method="get" enctype="multipart/form-data" class="form_inner">';
    $html .= "<p>Inserisci una nuova tipologia o scegli dalla lista se già presenti in un altro sito:</p>";
    $html .= $Form->select("ext_id", array_merge([["0", " "]], $orphans), "");
    $html .= '  <div class="form_footer">';
    $html .= '    <button type="submit">Invia</button>';
    $html .= '  </div>';
    $html .= '</form>';
    
    return [
      "template" => "admin_newrecord_ext.php",
      "data" => [
        "table" => $table,
        "bodyclass" => "",
        "seoTitle" => "Pannello di amministrazione",    
        "currentCity" => $currentCity[0]["name"],
        "cities" => $cities,
        "menuitems" => $App->getAdminMenuitems(),
        "navbanners" => [],
        "navtopitems" => $DB->getNavtopitems(),
        "linktopitems" => $App->getLinktopitems(), 
        "h2" => "Pannello di amministrazione / Nuovo / " . $table,
        "extSelectFormHtml" => $html
      ]
    ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_NEWTABLE"), function($machine, $table) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();   
      
      $joinPart = $machine->plugin("Backoffice")->getJoinPart($table);
      $ext_id = 0;
      if ($joinPart != "") {
        if (!isset($_GET["ext_id"])) {
          $machine->redirect($Link->Get(["ADMIN_NEWTABLE_EXT", $table]));
        } else {
          $ext_id = $_GET["ext_id"];
        }
      }
      
      return [
        "template" => "admin_newrecord.php",
        "data" => [
          "table" => $table,
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",    
          "currentCity" => $currentCity[0]["name"],
          "cities" => $cities,
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(), 
          "h2" => "Pannello di amministrazione / Nuovo / " . $table,
          "newFormHtml" => $Backoffice->getNewFormHtml([
            "table" => $table
          ])
        ]
      ];
    }
  });
  
  $machine->addAction($Link->getRoute("CATEGORIA_LOCALI_ORDERACTION"), "POST", function($engine, $cat_id) {
    $engine->plugin("DB")->disable_cache = true;  
    if (!$engine->plugin("App")->checkLogin()) {
      die();
    } else {  
      $result = $engine->plugin("DB")->saveOrder(
        "location_visibilities",
        $_POST["ids"]
      );
      
      echo json_encode($result);
      die();
    }
  });
  
  $machine->addAction($Link->getRoute("ZONA_ORDERACTION"), "POST", function($engine, $zona_id) {
    $engine->plugin("DB")->disable_cache = true;  
    if (!$engine->plugin("App")->checkLogin()) {
      die();
    } else {  
      $result = $engine->plugin("DB")->saveOrder(
        "location_visibilities",
        $_POST["ids"]
      );
      
      echo json_encode($result);
      die();
    }
  });
  
  $machine->addAction($Link->getRoute("AJAX_SAVE"), "POST", function($engine) {
    $engine->plugin("DB")->disable_cache = true;    
    
    if (!$engine->plugin("App")->checkLogin()) {
        die();
    } else {

      $result = [];
      
      $value = $engine->POST("value");
      if (
        $engine->POST("field") == "time_from"
        || $engine->POST("field") == "time_to"
      ) {
        $row = $engine->plugin("Backoffice")->getRecordData([
          "table" => $engine->POST("table"),
          "field_id" => $engine->POST("field_id"),
          "id" => $engine->POST("id")
        ]); 
        $time = date("H:i:00", strtotime($row[0][$engine->POST("field")]));
        $value .= " " . $time; 
      }
        
      $result["saved"] = $engine->plugin("DB")->save(
        $engine->POST("table"),
        [$engine->POST("field")],
        [$value],
        $engine->POST("field_id"),
        $engine->POST("id")
      );
      
      $result["newrecord"] = $engine->plugin("Backoffice")->getRecordHtml(
        $engine->POST("table"),
        $engine->POST("id"),
        $engine->basepath . "/admin/" . $engine->POST("table") . "/{{id}}"
      );

      if ($_POST["table"] == "recurrents") {
        if ($_POST["field"] == "recurrence_from" || $_POST["field"] == "recurrence_to") {
          $engine->plugin("Backoffice")->updateRecurrents($_POST["id"]);
        }
      }
      
      echo json_encode($result);
      die();
    }
  });
  
  $machine->addAction($Link->getRoute("AJAX_MODIFY_EVENT_DATE"), "POST", function($engine) {
    $engine->plugin("DB")->disable_cache = true;  
    if (!$engine->plugin("App")->checkLogin()) {
      die();
    } else {
      
      $result = [];
      
      $result["saved"] = $engine->plugin("DB")->modify_event_date(
        $engine->POST("table"),
        $engine->POST("variation"),
        $engine->POST("id")
      );
      
      $result["newrecord"] = $engine->plugin("Backoffice")->getRecordHtml(
        $engine->POST("table"),
        $engine->POST("id"),
        $engine->basepath . "/admin/" . $engine->POST("table") . "/{{id}}"
      );
      
      echo json_encode($result);
      die();
    }
  });
  
  /**
   *  Backoffice: record update
   */
  $machine->addPage($Link->getRoute("ADMIN_RITAGLIO"), function($machine, $table, $id, $field) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
    
    $Link = $machine->plugin("Link");
    $Backoffice = $machine->plugin("Backoffice");
    $DB = $machine->plugin("DB");
    $UploadS3 = $machine->plugin("UploadS3");
    $cities = $DB->getCities();
    $currentCity = $DB->getCurrentCity();
    
    $result = $Backoffice->getRecordData([
      "table" => $table,
      "field_id" => $Backoffice->getFieldId($table),
      "id" => $id
    ]);
    $filename = $result[0][$field];
    
    // define if the image is in an extern table
    $tableimage = $Backoffice->getTableForField($table, $field);
    $r = $machine->getRequest();
    $original_url = "uploads/$tableimage/original/" . $filename;
    $imageUrl = $UploadS3->get($original_url);  
    
    $crop_info = false;
    $cut_url = "uploads/$tableimage/cut/$filename.info";
    if ($UploadS3->file_exists_in_bucket($cut_url)) {
      $i = json_decode($UploadS3->getObject($cut_url), true);
      $x1 = $i["x"];
      $y1 = $i["y"];
      $x2 = $i["x"] + $i["w"];
      $y2 = $i["y"] + $i["h"];
      $crop_info = "[$x1, $y1, $x2, $y2]";
    }
    
    return [
      "template" => "admin_ritaglio.php",
      "data" => [
        "table" => $table,
        "id" => $id,
        "field" => $field,
        "bodyclass" => "",
        "seoTitle" => "Pannello di amministrazione",
        "currentCity" => $currentCity[0]["name"],
        "cities" => $cities,
        "menuitems" => $App->getAdminMenuitems(),
        "navbanners" => [],
        "navtopitems" => $DB->getNavtopitems(),
        "linktopitems" => $App->getLinktopitems(),
        "h2" => "Pannello di amministrazione / " . $table . " / " . $id . " / " . $field,
        "imageUrl" => $imageUrl,
        "crop_info" => $crop_info
      ]
    ];
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RITAGLIO"), "POST", function($machine, $table, $id, $field) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
    $Backoffice = $machine->plugin("Backoffice");
    $Link = $machine->plugin("Link");
    $UploadS3 = $machine->plugin("UploadS3");

    $result = $Backoffice->getRecordData([
      "table" => $table,
      "field_id" => $Backoffice->getFieldId($table),
      "id" => $id
    ]);
    $filename = $result[0][$field];
    
    // da qui (usare listobjects col prefisso per listare le dir in $table e cercare poi nelle varie w,h)
    // https://stackoverflow.com/questions/18683206/list-objects-in-a-specific-folder-on-amazon-s3
    // cerca in tutte le cartelle /uploads/$table/<w>_<h>/$filename
    //  ed elimina le thumb
    $UploadS3->clearThumbs($table, $filename);
    $tableimage = $Backoffice->getTableForField($table, $field);
    //$to_delete = glob("uploads/$tableimage/*_*/$filename");
    //foreach ($to_delete as $f) {
    //  unlink($f);
    //}

    // crea il ritaglio salvando il file e creando il file .info
    // (n.b. se cW, cH sono 0 o vuote, elimina il ritaglio esistente)
    $App->creaRitaglio($tableimage, $filename, $_POST["cW"], $_POST["cH"], $_POST["cX1"], $_POST["cY1"]);

    // redirect
    $machine->redirect($Link->Get(["ADMIN_TABLE", $table]));
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD"), function($machine, $table, $id) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();
      return [
        "template" => "admin_record.php",
        "data" => [
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $DB->getCities(),
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione / " . $table . " / " . $id,
          "updateFormHtml" => $Backoffice->getUpdateFormHtml([
            "table" => $table,
            "field_id" => $Backoffice->getFieldId($table),
            "id" => $id
          ])
        ]
      ];
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_MAP_DELETE"), "GET", function($machine, $original_table, $id_location, $id_map) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      
      $DB->deleteMap(
        $id_map
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_SHOWCASE_DELETE"), "GET", function($machine, $original_table, $id_location, $id_showcase) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      
      $DB->deleteShowcase(
        $id_showcase
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_PHOTO_DELETE"), "GET", function($machine, $original_table, $id_location, $id_photo) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      
      $DB->deletePhoto(
        $id_photo
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_MAP"), "POST", function($machine, $original_table, $id_location, $id_map) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      // salva 
      $r = $machine->getRequest();
      
      $DB->updateMap(
        $id_map,
        $r["POST"]["title"],
        $r["POST"]["address"],
        $r["POST"]["lat"],
        $r["POST"]["lng"],
        $r["POST"]["position"]
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD_MAP"), function($machine, $original_table, $id_location, $id_map) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();
      return [
        "template" => "admin_record.php",
        "data" => [
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $DB->getCities(),
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione / " . $original_table . " / " . $id_location . " / Maps / " . $id_map,
          "updateFormHtml" => $Backoffice->getUpdateFormHtml([
            "table" => "maps",
            "field_id" => $Backoffice->getFieldId("maps"),
            "link_action" => $Link->Get(["ADMIN_RECORD_MAP", $original_table, $id_location, $id_map]),
            "id" => $id_map
          ])
        ]
      ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD_SHOWCASE"), function($machine, $original_table, $id_location, $id_showcase) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();
      return [
        "template" => "admin_record.php",
        "data" => [
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $DB->getCities(),
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione / " . $original_table . " / " . $id_location . " / SHOWCASES / " . $id_showcase,
          "updateFormHtml" => $Backoffice->getUpdateFormHtml([
            "table" => "location_showcases",
            "field_id" => $Backoffice->getFieldId("location_showcases"),
            "link_action" => $Link->Get(["ADMIN_RECORD_SHOWCASE", $original_table, $id_location, $id_showcase]),
            "id" => $id_showcase
          ])
        ]
      ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD_PHOTO"), function($machine, $original_table, $id_location, $id_photo) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();
      return [
        "template" => "admin_record.php",
        "data" => [
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $DB->getCities(),
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione / " . $original_table . " / " . $id_location . " / PHOTOS / " . $id_photo,
          "updateFormHtml" => $Backoffice->getUpdateFormHtml([
            "table" => "photos",
            "field_id" => $Backoffice->getFieldId("photos"),
            "link_action" => $Link->Get(["ADMIN_RECORD_PHOTO", $original_table, $id_location, $id_photo]),
            "id" => $id_photo
          ])
        ]
      ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD_NEWMAP"), function($machine, $original_table, $id_location) {
    $table = "maps";
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();   
      
      $joinPart = $machine->plugin("Backoffice")->getJoinPart($table);
      $ext_id = 0;
      if ($joinPart != "") {
        if (!isset($_GET["ext_id"])) {
          $machine->redirect($Link->Get(["ADMIN_NEWTABLE_EXT", $table]));
        } else {
          $ext_id = $_GET["ext_id"];
        }
      }
      
      return [
        "template" => "admin_newrecord.php",
        "data" => [
          "table" => $table,
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",    
          "currentCity" => $currentCity[0]["name"],
          "cities" => $cities,
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(), 
          "h2" => "Pannello di amministrazione / Nuovo / " . $table,
          "newFormHtml" => $Backoffice->getNewFormHtml([
            "table" => $table,
            "link_action" => $Link->Get(["ADMIN_RECORD_NEWMAP", $original_table, $id_location])
          ])
        ]
      ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD_NEWSHOWCASE"), function($machine, $original_table, $id_location) {
    $table = "location_showcases";
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();   
      
      return [
        "template" => "admin_newrecord.php",
        "data" => [
          "table" => $table,
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",    
          "currentCity" => $currentCity[0]["name"],
          "cities" => $cities,
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(), 
          "h2" => "Pannello di amministrazione / Nuovo / " . $table,
          "newFormHtml" => $Backoffice->getNewFormHtml([
            "table" => $table,
            "link_action" => $Link->Get(["ADMIN_RECORD_NEWSHOWCASE", $original_table, $id_location])
          ])
        ]
      ];
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_RECORD_NEWPHOTO"), function($machine, $original_table, $id_location) {
    $table = "photos";
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();   
      
      return [
        "template" => "admin_newrecord.php",
        "data" => [
          "table" => $table,
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",    
          "currentCity" => $currentCity[0]["name"],
          "cities" => $cities,
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(), 
          "h2" => "Pannello di amministrazione / Nuovo / " . $table,
          "newFormHtml" => $Backoffice->getNewFormHtml([
            "table" => $table,
            "link_action" => $Link->Get(["ADMIN_RECORD_NEWPHOTO", $original_table, $id_location])
          ])
        ]
      ];
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_NEWMAP"), "POST", function($machine, $original_table, $id_location) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      // salva 
      $r = $machine->getRequest();
      
      $type = $original_table == "locations" ? "Location" : "Event";
      $DB->saveMap(
        $id_location,
        $type,
        $r["POST"]["title"],
        $r["POST"]["address"],
        $r["POST"]["lat"],
        $r["POST"]["lng"],
        $r["POST"]["position"]
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_NEWSHOWCASE"), "POST", function($machine, $original_table, $id_location) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      // salva 
      $r = $machine->getRequest();
      
      $DB->saveShowcase(
        $id_location,
        $r["POST"]["title"],
        $r["POST"]["disposition"]
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD_NEWPHOTO"), "POST", function($machine, $original_table, $id_location) {
    $machine->plugin("DB")->disable_cache = true;  
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Link = $machine->plugin("Link");
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      // salva 
      $r = $machine->getRequest();
      
      $DB->savePhoto(
        $id_location,
        $original_table == "events" ? "Event" : "Location",
        $r["POST"]["title"],
        $r["POST"]["position"]
      );
      
      // redirect alla singola location
      $machine->redirect($Link->Get(["ADMIN_RECORD", $original_table, $id_location]));
    }
  });
  
  $machine->addPage($Link->getRoute("ADMIN_CAT_ZONA_ORDINAMENTO"), function($machine, $id_cat, $id_zone) {
    $machine->plugin("DB")->disable_cache = true;    
    
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $machine->plugin("DB");
      $Link = $machine->plugin("Link");
      $Backoffice = $machine->plugin("Backoffice");
      $cities = $DB->getCities();
      $currentCity = $DB->getCurrentCity();
      
      $cat = $DB->getCatById($id_cat);
      $zona = $DB->getZonaById($id_zone);
      $endpoint = $Link->Get(["CATEGORIA_LOCALI_ORDERACTION", $id_cat]);
      $extern_table = "location_visibilities";
      $field_id = "id";
      $list = $DB->getListCategoriaLocaliZona($cat["seo_url"], true, $id_zone);
      $html = '<br><a href="' . $Link->Get(["ADMIN_RECORD", "typo_btw_sites", $id_cat]) . '"><<< Torna a ' . $cat["title"] . '</a>'
        . '<br><br>' . $Backoffice->getHtmlOrderList($endpoint, $extern_table, $field_id, $list);
      return [
        "template" => "admin_record.php",
        "data" => [
          "bodyclass" => "",
          "seoTitle" => "Pannello di amministrazione",
          "currentCity" => $currentCity[0]["name"],
          "cities" => $DB->getCities(),
          "menuitems" => $App->getAdminMenuitems(),
          "navbanners" => [],
          "navtopitems" => $DB->getNavtopitems(),
          "linktopitems" => $App->getLinktopitems(),
          "h2" => "Pannello di amministrazione / " 
            . '<a href="' . $Link->Get(["ADMIN_RECORD", "typo_btw_sites", $id_cat]) . '">' . $cat["title"] . "</a> / " 
            . $App->_zName($zona),
          "updateFormHtml" => $html
        ]
      ];
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_NEWTABLE"), "POST", function($engine, $table) {
    $engine->plugin("DB")->disable_cache = true;    
    
    $App = $engine->plugin("App");
    if (!$engine->plugin("App")->checkLogin()) {
      $engine->redirect($engine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      
      $DB = $engine->plugin("DB");
      $Link = $engine->plugin("Link");
      $Backoffice = $engine->plugin("Backoffice");
      $r = $engine->getRequest();
      
      // salto i maytomany[]
      // salto anche time_from_h e time_from_m, che verranno uniti in time_from
      // salto anche time_to_h e time_to_m, che verranno uniti in time_to
      // salto anche gli extern col join...
      // salto l'ext_id, perchè il valore viene riscritto in un campo diverso
      $post = [];
      $manytomanypost = [];
      $externjoin = [];
      $foreign_keys = [];
      foreach ($_POST as $k => $v) {
        if ($k == "time_from") continue;
        if ($k == "time_to") continue;
        if ($k == "time_from_h") continue;
        if ($k == "time_from_m") continue;
        if ($k == "time_to_h") continue;
        if ($k == "time_to_m") continue;
        if ($k == "time_from_day") continue;
        if ($k == "time_from_month") continue;
        if ($k == "time_from_year") continue;
        if ($k == "time_to_day") continue;
        if ($k == "time_to_month") continue;
        if ($k == "time_to_year") continue;
        if ($k == "recurrence_to_day") continue;
        if ($k == "recurrence_to_month") continue;
        if ($k == "recurrence_to_year") continue;
        if ($k == "recurrence_to_h") continue;
        if ($k == "recurrence_to_m") continue;
        if ($k == "recurrence_from_day") continue;
        if ($k == "recurrence_from_month") continue;
        if ($k == "recurrence_from_year") continue;
        if ($k == "recurrence_from_m") continue;
        if ($k == "recurrence_from_h") continue;
        if ($k == "ext_id") continue;
        if ($k == "encrypted_password") {
          if ($v == "") continue;
          $post[$k] = md5(md5($v));
          continue;
        }
        $fieldJoin = $Backoffice->getFormFieldJoin($table, $k);
        if (is_array($fieldJoin)) {
          $externjoin[$k] = $v;
          continue;
        }
        $fieldType = $Backoffice->getFormFieldType($table, $k);

        if ($fieldType == "manytomany") {
          $manytomanypost[$k] = $v;
        } else {
          $post[$k] = $v;
        }
      }

      // valori di default (campi espliciti)
      foreach ($post as $k => $v) {
        if ($table == "recurrents" && $k == "recurrence_from" && $v == "") {
          $post[$k] = date("Y-m-d");
        }
        if ($table == "recurrents" && $k == "recurrence_to" && $v == "") {
          $post[$k] = date("Y-m-d", strtotime("+3 months"));
        }
        if ($k == "priority" && $v == "") {
          $post[$k] = 0;
        }
      }

      // valori di default (campi impliciti non presenti nei form)
      $data = array_merge($post, [
        "created_at" => date("Y-m-d H:i:s"),
        "updated_at" => date("Y-m-d H:i:s")
      ], $Backoffice->getSiteField($table) != "" ? ["site_id" => $DB->getSite()] : []);
      
      // salvo eventuali valori externjoin
      $foreignkey = $Backoffice->getForeignKey($table);
      if ($foreignkey != "") {
        $foreigntable = $Backoffice->getForeignTable($table);
        if (isset($_POST["ext_id"])) {
          $ext_id = $_POST["ext_id"];
          $DB->save($foreigntable, array_keys($externjoin), array_values($externjoin), "id", $ext_id);
        } else {
          // valori di default...
          $externdata = array_merge($externjoin, [
            "created_at" => date("Y-m-d H:i:s"),
            "updated_at" => date("Y-m-d H:i:s")
          ]);
          $ext_id = $DB->newRecord(
            $foreigntable,
            array_keys($externdata),
            array_values($externdata)
          );
        }
        $data[$foreignkey] = $ext_id;
      }
      /*
      foreach ($externjoin as $extern_table => $arr) {
        $extern_id = $DB->newRecord(
          $extern_table,
          array_keys($arr),
          array_values($arr)
        );
        $post[$foreign_keys[$extern_table]] = $extern_id;
      }
      print_r($post);die();
      */
      
      // salvataggio valori (tranne i manytomany)
      $fields = array_keys($data);
      // eventuali valori passati come array devono essere trasformati in stringa
      $values = array_map(function($item) {
        if (is_array($item)) {
          return implode(",", $item);
        }
        return $item;
      }, array_values($data));
      // aggiungo eventualmente il time_from e time_to (usati nella tabella recurrents e nella tabella eventi)
      // e recurrence_from e recurrence_to
      $checks = ["time_from", "time_to", "recurrence_from", "recurrence_to"];
      foreach ($checks as $c) {
        if (isset($r["POST"][$c . "_h"]) && isset($r["POST"][$c . "_m"])) {
          $fields[] = $c;
          if (isset($r["POST"][$c . "_day"]) && isset($r["POST"][$c . "_month"]) && isset($r["POST"][$c . "_year"])) {
            $datefrom = $r["POST"][$c . "_year"]
              . "-" . $r["POST"][$c . "_month"]
              . "-" . $r["POST"][$c . "_day"];
          } else {
            $datefrom = isset($r["POST"][$c]) ? $Backoffice->shortDateToMysql($r["POST"][$c]) : date("Y-m-d");
          }
          $values[] = $datefrom . " " . $r["POST"][$c . "_h"] . ":" . $r["POST"][$c . "_m"] . ":00";
        }
      }

      $id = $DB->newRecord(
        $table,
        $fields,
        $values
      );

      // salvo i manytomany
      $DB->saveManyToMany($manytomanypost, $table, $id);
      
      // upload files
      foreach($_FILES as $fieldname => $filearr) {
        $result = $Backoffice->upload(array_values($_FILES)[0], $table, $id);
        // update db
        if ($result["result"] == "OK") {
          $field_id = $Backoffice->getFieldId($table);
          $name = explode("/", $result["filename"]);
          $DB->save($table, [$fieldname], [end($name)], $field_id, $id);
        }
      }
      
      // inserisco i recurrents
      if ($table == "recurrents") {
        $Backoffice->updateRecurrents($id);
      }
      
      $engine->redirect($Link->Get(["ADMIN_TABLE", $table]));
    }
  });
  
  $machine->addAction($Link->getRoute("ADMIN_RECORD"), "POST", function($machine, $table, $id) {
    $machine->plugin("DB")->disable_cache = true;    
    
    // build fields and values array
    $App = $machine->plugin("App");
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {    
      
      $DB = $machine->plugin("DB");
      $Backoffice = $machine->plugin("Backoffice");
      $Link = $machine->plugin("Link");
      $r = $machine->getRequest();

      // upload files
      foreach($_FILES as $fieldname => $filearr) {
        $tableimage = $Backoffice->getTableForField($table, $fieldname);
        $idImage = $Backoffice->getIdValueForField($table, $id, $fieldname);
        $result = $Backoffice->upload($filearr, $tableimage, $id);
        // update db
        if ($result["result"] == "OK") {
          $field_id = $Backoffice->getFieldId($tableimage);
          $name = explode("/", $result["filename"]);
          $DB->save($tableimage, [$fieldname], [end($name)], $field_id, $idImage);
        }
      }
      
      // salto i maytomany[]
      // salto anche time_from_h e time_from_m, che verranno uniti in time_from
      // salto anche time_to_h e time_to_m, che verranno uniti in time_to
      $post = [];
      $manytomanypost = [];
      foreach ($r["POST"] as $k => $v) {
        if ($k == "time_from") continue;
        if ($k == "time_to") continue;
        if ($k == "time_to_h") continue;
        if ($k == "time_to_m") continue;
        if ($k == "time_from_h") continue;
        if ($k == "time_from_m") continue;
        if ($k == "time_from_day") continue;
        if ($k == "time_from_month") continue;
        if ($k == "time_from_year") continue;
        if ($k == "time_to_day") continue;
        if ($k == "time_to_month") continue;
        if ($k == "time_to_year") continue;
        if ($k == "recurrence_to_day") continue;
        if ($k == "recurrence_to_month") continue;
        if ($k == "recurrence_to_year") continue;
        if ($k == "recurrence_to_h") continue;
        if ($k == "recurrence_to_m") continue;
        if ($k == "recurrence_from_day") continue;
        if ($k == "recurrence_from_month") continue;
        if ($k == "recurrence_from_year") continue;
        if ($k == "recurrence_from_m") continue;
        if ($k == "recurrence_from_h") continue;
        if ($k == "encrypted_password") {
          if ($v == "") continue;
          $post[$k] = md5(md5($v));
          continue;
        }
        $fieldType = $Backoffice->getFormFieldType($table, $k);

        if ($fieldType == "manytomany") {
          $manytomanypost[$k] = $v;
        } else {
          $post[$k] = $v;
        }
      }

      // salvo i manytomany
      $DB->saveManyToMany($manytomanypost, $table, $id);
      
      // qui salvo tutti i valori (a parte i manytomany)
      $fields = array_keys($post);
      // eventuali valori passati come array devono essere trasformati in stringa
      $values = array_map(function($item) {
        if (is_array($item)) {
          return implode(",", $item);
        }
        return $item;
      }, array_values($post));
      // aggiungo eventualmente il time_from e time_to (usati nella tabella recurrents e nella tabella eventi)
      // e recurrence_from e recurrence_to
      $checks = ["time_from", "time_to", "recurrence_from", "recurrence_to"];
      foreach ($checks as $c) {
        if (isset($r["POST"][$c . "_h"]) && isset($r["POST"][$c . "_m"])) {
          $fields[] = $c;
          if (isset($r["POST"][$c . "_day"]) && isset($r["POST"][$c . "_month"]) && isset($r["POST"][$c . "_year"])) {
            $datefrom = $r["POST"][$c . "_year"]
              . "-" . $r["POST"][$c . "_month"]
              . "-" . $r["POST"][$c . "_day"];
          } else {
            $datefrom = isset($r["POST"][$c]) ? $Backoffice->shortDateToMysql($r["POST"][$c]) : date("Y-m-d");
          }
          $values[] = $datefrom . " " . $r["POST"][$c . "_h"] . ":" . $r["POST"][$c . "_m"] . ":00";
        }
      }
      
      $field_id = $Backoffice->getFieldId($table);
      
      // rilevo le date short e le trasformo in date mysql
      for ($i = 0; $i < count($values); $i++) {
        if (strlen($values[$i]) == 10) {
          $parts = explode("/", $values[$i]);
          if (count($parts) == 3) {
            $values[$i] = $Backoffice->shortDateToMysql($values[$i]);
          }            
        }
      }
      
      $DB->save($table, $fields, $values, $field_id, $id);
      
      // aggiorno i recurrents
      if ($table == "recurrents") {
        $Backoffice->updateRecurrents($id);
      }
      
      $machine->redirect($Link->Get("/admin/$table"));
    }
  });

  $machine->addPage($Link->getRoute("PAGINA"), function($machine, $slug_sezione) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $section = $DB->getSection($slug_sezione);
    if (!$section) {
      die("Not found");
    }
    $result = [
      "template" => "sezione.php",
      "data" => array_merge($App->getCommonData(), [
        "seoTitle" => $section["seo_title"],
        "h3" => $section["seo_footer"],
        "bodyclass" => "sections section",
        "section" => $section,
        "calendar" => $App->getCalendar()
      ])
    ];
    if ($section["fixed_name"] == "holidays") {
      $result["data"]["holidays"] = $DB->getElencoFestivita();
    }
    return $result;
  });  
  
  // IMAGE CHECKER
  $machine->addAction($Link->getRoute("CHECK_IMAGES"), "GET", function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    $App = $machine->plugin("App");
    
    $image_infos = [];
    /*
    $image_infos[] = [
      "tablename" => "",
      "image_field" => "",
      "thumbsizes" => []
    ];
    */
    $image_infos[] = [
      "tablename" => "banners",
      "image_field" => "image_file_name"
    ];
    $image_infos[] = [
      "tablename" => "typos",
      "image_field" => "image_file_name"
    ];
    
    foreach ($image_infos as $info) {
      $records = $App->getRecords($info["tablename"]);
      foreach ($records as $row) {
        $App->checkImage(
          $info["tablename"],
          $row["id"],
          "images",
          $row[$info["image_field"]]
        );
      }
    }
  });
  
  // IMAGE UPLOAD
  $machine->addAction($Link->getRoute("UPLOAD"), "POST", function($machine, $table, $id, $fieldname) {
    $machine->plugin("DB")->disable_cache = true;
    
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Backoffice = $machine->plugin("Backoffice");
    
    $result = $Backoffice->upload(array_values($_FILES)[0], $table, $id);
    // update db
    if ($result["result"] == "OK") {
      $field_id = $Backoffice->getFieldId($table);
      $name = end(explode("/", $result["filename"]));
      $DB->save($table, [$fieldname], [$name], $field_id, $id);
      if ($table == "location_showcases")
        $DB->save($table, ["image_fingerprint"], [null], $field_id, $id);
    }
    
    // back to referrer
    $machine->back();
  });
  
  // CACHE RESET
  $machine->addPage($Link->getRoute("CACHE_RESET"), function($machine) {
    $machine->plugin("DB")->disable_cache = true;
    
    if (!$machine->plugin("App")->checkLogin()) {
      $machine->redirect($machine->plugin("Link")->Get("ADMIN_LOGIN"));
    } else {
      $machine->plugin("DB")->pool->clear();
      $machine->pool->clear();
      
      echo 'Cache cancellata. <a href="javascript:history.back();">Torna indietro</a>';
      die();
    }
  });  
  
  // GENERATE SITEMAP
  $machine->addPage($Link->getRoute("GENERATE_SITEMAP"), function($machine) {
    $DB = $machine->plugin("DB");
    $DB->disable_cache = true;
    
    $Sitemap = $machine->addPlugin("SitemapPlugin");
    $currentCity = $DB->getCurrentCity();
    $code = $currentCity[0]["code"];
    
    $Sitemap->generate(__DIR__ . "/sitemaps", "sitemap.$code");
  });
  
  $machine->run();
  
