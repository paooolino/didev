<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
setlocale(LC_TIME, "ita.UTF-8", "it_IT");
//$result = setlocale(LC_ALL, 0);
//var_dump($result);
//echo "::." . strftime("%B", time());
//die();

  require("vendor/autoload.php");
  
  // Create engine
  $machine = new \WebEngine\WebEngine();
  $machine->append_debug_infos = true;
  
  // Plugins
	$Link = $machine->addPlugin("Link");
	$UploadS3 = $machine->addPlugin("UploadS3");
	$DB = $machine->addPlugin("DB");
	$DiscosForms = $machine->addPlugin("DiscosForms");
  
	$machine->addPlugin("Image");
	$machine->addPlugin("Form");
  
  // The App plugin
  $App = $machine->addPlugin("App");
  
  // The Backoffice plugin
  $Backoffice = $machine->addPlugin("Backoffice");
  $Backoffice->loadConfig("config/backoffice.json");
  
  $dbopts = parse_url(getenv('VHOSTING_DATABASE_URL'));
  $conn = $DB->setupMySql(
    // host
    $dbopts["host"],
    // username
    $dbopts["user"],
    // password
    $dbopts["pass"],
    // db name
    ltrim($dbopts["path"],'/')
  );
  
  if (!is_object($conn)) { die("db connection error"); };
  
  // Set the site id
  if (stripos($_SERVER["HTTP_HOST"], "discotechebrescia.it") !== false) { $DB->setSite(1); }
  if (stripos($_SERVER["HTTP_HOST"], "discotechebergamo.it") !== false) { $DB->setSite(2); }
  if (stripos($_SERVER["HTTP_HOST"], "discotecheverona.it") !== false) { $DB->setSite(3); }
  if (stripos($_SERVER["HTTP_HOST"], "discotechedimilano.it") !== false) { $DB->setSite(4); }
  if (stripos($_SERVER["HTTP_HOST"], "discotechecremona.it") !== false) { $DB->setSite(5); }
  if (stripos($_SERVER["HTTP_HOST"], "discotechepiacenza.it") !== false) { $DB->setSite(6); }
  if (stripos($_SERVER["HTTP_HOST"], "discotecheriminiriccione.it") !== false) { $DB->setSite(7); }
  if (stripos($_SERVER["HTTP_HOST"], "discotechejesolo.it") !== false) { $DB->setSite(8); }
  
  // Page definitions
  $Link->setRoute("HOME", "/");
  $Link->setRoute("EVENTI", "/eventi");
  $Link->setRoute("EVENTI_PAGINA", "/eventi/{pag}");
  $Link->setRoute("EVENTI_DATA", "/eventi-periodo/{yy}/{mm}/{gg}");
  $Link->setRoute("EVENTI_STASERA", "/eventi-stasera");
  $Link->setRoute("EVENTI_WEEKEND", "/eventi-weekend");
  $Link->setRoute("EVENTI_CATEGORIA", "/eventi-categoria/{evcategoria}");
  $Link->setRoute("EVENTO", "/evento/{slug_evento}");
  $Link->setRoute("EVENTI_PASSATI", "/eventi-passati-locale/{slug_locale}");
  $Link->setRoute("CALENDARIO", "/eventi-calendario/{anno}/{mese}");
  
  $Link->setRoute("CATEGORIA_LOCALI", "/locali/{slug_categoria}");
  $Link->setRoute("CATEGORIA_LOCALI_PAG", "/locali/{slug_categoria}/{pag:\d+}");
  $Link->setRoute("CATEGORIA_ZONA", "/locali/{slug_categoria}/{slug_zona}");
  $Link->setRoute("CATEGORIA_LOCALI_LETTERA", "/locali/{slug_categoria}/lettera/{lettera}");
  $Link->setRoute("LOCALE", "/locale/{slug_locale}");
  $Link->setRoute("AJAX_LOAD_ITEMS", "/ajax-load-items");

  $Link->setRoute("ELENCO_FESTIVITA", "/festivita");
  $Link->setRoute("FESTIVITA", "/eventi-festivita/{slug_festivita}");
  $Link->setRoute("FESTIVITA_ARCHIVIO", "/eventi-passati-festivita/{slug_festivita}");
  
  $Link->setRoute("PAGINA", "/{slug_sezione}");
  $Link->setRoute("SEZIONE", "/sezione/{slug_sezione}");
  
  $Link->setRoute("ADMIN", "/admin");
  $Link->setRoute("ADMIN_LOGIN", "/admin/login");
  $Link->setRoute("ADMIN_TABLE", "/admin/{table}");
  $Link->setRoute("ADMIN_NEWTABLE", "/admin/new/{table}");
  $Link->setRoute("ADMIN_NEWTABLE_EXT", "/admin/new/{table}/ext");
  $Link->setRoute("ADMIN_RECORD", "/admin/{table}/{id}");
  $Link->setRoute("ADMIN_RITAGLIO", "/admin/ritaglio/{table}/{id}/{fieldname}");
  $Link->setRoute("ADMIN_DELETE", "/admin/delete/{table}/{id}");
  $Link->setRoute("AJAX_SAVE", "/ajax/save");
  
  $Link->setRoute("CHECK_IMAGES", "/tools/check-images");
  $Link->setRoute("UPLOAD", "/tools/upload/{table}/{id}/{fieldname}");
  $Link->setRoute("RITAGLIO", "/tools/ritaglio/nuovo/{table}/{id}");
  
  $Link->setRoute("FORM_ONLIST", "/form/mettiti-in-lista");
  $Link->setRoute("FORM_LOCATION_SUGGEST", "/form/inserisci-locale");
  $Link->setRoute("FORM_CONTACT", "/form/contatti");
  $Link->setRoute("FORM_PARTY", "/form/organizzare-feste");
  $Link->setRoute("SEND_OK", "/form/send-ok");
   
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
        "seoTitle" => $homeContent["seo_title"],
        "hevents_big" => $hevents_big,
        "hevents_small" => $hevents_small,
        "h2" => "eventi di locali e discoteche di " . $currentCity[0]["name"] . " da non perdere",
        "h2_2" => "discotechebrescia.it: la miglior vetrina per eventi, locali e ristoranti di " . $currentCity[0]["name"],
        "h3" => $homeContent["seo_footer"],
        "hboxes" => $DB->getHomeBoxes(),
        "banner_dx" => $banner_dx[0]
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
    
    $section = $DB->getSection(str_replace("/", "", $Link->getRoute("EVENTI")));
    $events = $DB->getEventsFromDB();
    $n_events = count($events);
    
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["title"],
        "seoTitle" => $section["seo_title"],
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $section["seo_footer"],
        "calendar" => $App->getCalendar(),
        "catevents" => $DB->getCatEvents()
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("EVENTI_CATEGORIA"), function($machine, $evcategoria) {
    $App = $machine->plugin("App");
    $Link = $machine->plugin("Link");
    
    $sectionEv = $App->getSection(str_replace("/", "", $Link->getRoute("EVENTI")));
    $evcat = $App->getEvCategory($evcategoria);
    $events = $App->getEventsFromDBbyCategory($evcat["cat_id"]);
    $n_events = count($events);

    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $evcat["seo_title"],
        "seoTitle" => $evcat["title"],
        "title" => $evcat["title"],
        "seoDescription" => $evcat["description"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $evcat["seo_footer"],
        "calendar" => $App->getCalendar(),
        "catevents" => $App->getCatEvents(),
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

    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["title"],
        "seoTitle" => $section["seo_title"],
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $section["seo_footer"],
        "calendar" => $App->getCalendar(),
        "disableEventlistHeader" => true
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("ADMIN_DELETE"), function($machine, $table, $id) {
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

    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["title"],
        "seoTitle" => $section["seo_title"],
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"],
        "n_events" => $n_events,
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "events" => $events,
        "h3" => $section["seo_footer"],
        "calendar" => $App->getCalendar(),
        "disableEventlistHeader" => true,
        "disableEventsArchive" => true
      ])
    ];
  });
  
  $machine->addPage($Link->getRoute("EVENTI_DATA"), function($machine, $anno, $mese, $giorno) {
    $App = $machine->plugin("App");
    
    $day = \DateTime::createFromFormat("Y-n-j", $anno . "-" . $mese . "-" . $giorno);
    $events = $App->getEventsForRange($day, $day);
    $n_events = count($events);
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "seoTitle" => "Eventi Brescia " . strftime("%A %e %B %Y", $day->getTimestamp()),
        "title" => "Eventi Brescia - " . strftime("%A %e %B %Y", $day->getTimestamp()) . ".",
        "h2" => "Scopri tutti gli eventi in programma a Brescia per " . strftime("%A %e %B %Y", $day->getTimestamp()),
        "seoDescription" => "",
        "n_pages" => ceil($n_events / 15),
        "pag" => 1,
        "n_events" => $n_events,
        "events" => $events,
        "h3" => "Eventi " . strftime("%A %e %B %Y", $day->getTimestamp()),
        "calendar" => $App->getCalendar(),
        "disableEventlistHeader" => true
      ])
    ];
  });
 
  /**
   *  Solo calendario
   */
  $machine->addPage($Link->getRoute("CALENDARIO"), function($machine, $anno, $mese) {
    $App = $machine->plugin("App");
    return [
      "template" => "calendario.php",
      "data" => [
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
    $topBanner = $DB->getRandomTopBanner();
    $evento = $DB->getEvento($slug);

    $logo_img = $App->img("locations", $evento["locations_id"], "W", 155, $evento["locations_logo_file_name"], "logos");
    
    return [
      "template" => "evento.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events event",
        "seoTitle" => $evento["seo_title"],
        "logo_img" => $logo_img,
        "evento" => $evento,
        "h3" => $evento["seo_footer"],
        "calendar" => $App->getCalendar()
      ])
    ];
  });
  
  /**
   *  Locali
   */
   
  $machine->addPage($Link->getRoute("CATEGORIA_LOCALI"), function($machine, $slug_categoria) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $cat = $DB->getCategoriaLocali($slug_categoria);
    list($z1, $z2) = $DB->getZonesListForCategoriaLocali($slug_categoria);
    return [
      "template" => "categoria_locali.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "list" => $DB->getListCategoriaLocali($slug_categoria),
        "ntot" => $DB->countListCategoriaLocali($slug_categoria),
        "slug_categoria" => $slug_categoria,
        "z1" => $z1,
        "z2" => $z2,
        "bodyclass" => "locations typology_locations",
        "seoTitle" => $cat["seo_title"],
        "h3" => $cat["seo_footer"],
        "current_page" => 1,
        "calendar" => $App->getCalendar()
      ])
    ];
  });   

  $machine->addAction($Link->getRoute("AJAX_LOAD_ITEMS"), "POST", function($machine) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $list = $DB->getListCategoriaLocali($_POST["slug"], false, $_POST["page"]);
    $html = '';
    foreach ($list as $item) {
      $html .= $App->printLocaleItem($item);
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
    $cat = $App->getCategoriaLocali($slug_categoria);
    list($z1, $z2) = $App->getZonesListForCategoriaLocali($slug_categoria);
    return [
      "template" => "categoria_locali.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "list" => $App->getListCategoriaLocali($slug_categoria, false, $pag),
        "ntot" => $App->countListCategoriaLocali($slug_categoria),
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
  
  $machine->addPage($Link->getRoute("CATEGORIA_ZONA"), function($machine, $slug_categoria, $slug_zona) {
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $cat = $DB->getCategoriaLocali($slug_categoria);
    $zona = $DB->getZona($slug_zona);

    return [
      "template" => "categoria_locali-zona.php",
      "data" => array_merge($App->getCommonData(), [
        "cat" => $cat,
        "zona" => $zona,
        "list" => $DB->getListCategoriaLocaliZona($slug_categoria, $zona["id"]),
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
    
    $locale_events = $DB->getEventsForLocale($locale["id"]);
    
    return [
      "template" => "locale.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "locations location",
        "seoTitle" => $locale["seo_title"],
        "cat" => $cat,
        "locale" => $locale,
        "logo_img" => $logo_img,
        "showcase" => $showcase,
        "locale_events" => $locale_events,
        "photos" => $DB->getLocalePhotos($locale["id"]),
        "map" => $DB->getLocaleMap($locale["id"]),
        "h3" => $locale["seo_footer"]
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
    
    return [
      "template" => "festivita.php",
      "data" => array_merge($App->getCommonData(), [
        "bodyclass" => "events next_events",
        "h2" => $section["seo_title"],
        "seoTitle" => $section["seo_title"],
        "title" => $section["title"],
        "seoDescription" => $section["seo_description"],
        "description" => $section["description"],
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
    return [
      "template" => "eventi.php",
      "data" => array_merge($App->getCommonData(), [
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
    return [
      "template" => "sezione.php",
      "data" => array_merge($App->getCommonData(), [
        "seoTitle" => $section["seo_title"],
        "h3" => $section["seo_footer"],
        "bodyclass" => "sections section",
        "section" => $section,
        "calendar" => $App->getCalendar()
      ])
    ];
  }); 
  
  $machine->addPage($Link->getRoute("SEND_OK"), function($machine) {
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
    $machine->plugin("DiscosForms")->send(
      "Mettiti in lista",
      $_POST["form_onlist"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  $machine->addAction($Link->getRoute("FORM_LOCATION_SUGGEST"), "POST", function($machine) {
    $machine->plugin("DiscosForms")->send(
      "Inserisci locale",
      $_POST["form_location_suggest"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  $machine->addAction($Link->getRoute("FORM_CONTACT"), "POST", function($machine) {
    $machine->plugin("DiscosForms")->send(
      "Contatti",
      $_POST["form_contact"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  $machine->addAction($Link->getRoute("FORM_PARTY"), "POST", function($machine) {
    $machine->plugin("DiscosForms")->send(
      "Organizza festa",
      $_POST["form_party"]
    );
    $machine->redirect($machine->plugin("Link")->Get("SEND_OK"));
  });
  
  /**
   *  Backoffice home
   */
  $machine->addAction($Link->getRoute("ADMIN_LOGIN"), "POST", function($machine) {
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
    return [
      "template" => "login.php",
      "data" => []
    ];
  });
  
  $machine->addPage($Link->getRoute("ADMIN"), function($machine) {
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
   
  /**
   *  Backoffice: table list
   */
  $machine->addPage($Link->getRoute("ADMIN_TABLE"), function($machine, $table) {
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
  
  $machine->addAction($Link->getRoute("AJAX_SAVE"), "POST", function($engine) {
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

  /**
   *  Backoffice: record update
   */
  $machine->addPage($Link->getRoute("ADMIN_RITAGLIO"), function($machine, $table, $id, $field) {
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
  
  $machine->addAction($Link->getRoute("ADMIN_NEWTABLE"), "POST", function($engine, $table) {
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
      // aggiungo eventualmente il time_from (usato nella tabella recurrents)
      if (isset($_POST["time_from_h"]) && isset($_POST["time_from_m"])) {
        $fields[] = "time_from";
        $datefrom = isset($r["POST"]["time_from"]) ? $r["POST"]["time_from"] : date("Y-m-d");
        $values[] = $datefrom . " " . $r["POST"]["time_from_h"] . ":" . $r["POST"]["time_from_m"] . ":00";
      }
      if (isset($_POST["time_to_h"]) && isset($_POST["time_to_m"])) {
        $fields[] = "time_to";
        $datefrom = isset($r["POST"]["time_to"]) ? $r["POST"]["time_to"] : date("Y-m-d");
        $values[] = $datefrom . " " . $r["POST"]["time_to_h"] . ":" . $r["POST"]["time_to_m"] . ":00";
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
      if (isset($r["POST"]["time_from_h"]) && isset($r["POST"]["time_from_m"])) {
        $fields[] = "time_from";
        $datefrom = isset($r["POST"]["time_from"]) ? $r["POST"]["time_from"] : date("Y-m-d");
        $values[] = $datefrom . " " . $r["POST"]["time_from_h"] . ":" . $r["POST"]["time_from_m"] . ":00";
      }
      if (isset($r["POST"]["time_to_h"]) && isset($r["POST"]["time_to_m"])) {
        $fields[] = "time_to";
        $datefrom = isset($r["POST"]["time_to"]) ? $r["POST"]["time_to"] : date("Y-m-d");
        $values[] = $datefrom . " " . $r["POST"]["time_to_h"] . ":" . $r["POST"]["time_to_m"] . ":00";
      }
      $field_id = $Backoffice->getFieldId($table);
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
    $App = $machine->plugin("App");
    $DB = $machine->plugin("DB");
    $Backoffice = $machine->plugin("Backoffice");
    
    $result = $Backoffice->upload(array_values($_FILES)[0], $table, $id);
    // update db
    if ($result["result"] == "OK") {
      $field_id = $Backoffice->getFieldId($table);
      $name = end(explode("/", $result["filename"]));
      $DB->save($table, [$fieldname], [$name], $field_id, $id);
    }
    
    // back to referrer
    $machine->back();
  });
  
  $machine->run();
  
