<?php
namespace WebEngine\Plugin;
    
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\ChangeFrequency;
    
/*
attuale???
home			always	1.0
sezione/feste private 	daily	0.7
sezione/scambio link	daily	0.6
sezione/chi siamo	daily	0.6
	cercasi pr
	festa coscritti
	addio al nub
	faq
	progetto scimmia
	inserisci
	newsletter eventi	daily 0.7
	lavora con
	mappa percorso
	marco ciresola
	mettersi in lista	0.7
festivita 1.0
sezione/contatti	daily	0.4





PAGINA
IMPORTANZA
FREQUENZA AGGIORNAMENTO

Home
1.0
hourly

Categorie
0.9
daily

Schede location
0.9
weekly

pagina Eventi
0.9
hourly

Scheda evento
0.8
never

eventi-passati-locale (singolo evento)
0.4
monthly

eventi-weekend (sotto calendario)
0.7
daily

eventi-periodo (singolo giorno)
0.4
daily

eventi-festività (singola pagina festività)
0.8
weekly

eventi-passati-festivita 
0.5
weekly

pagine genriche privacy, cntatti etc...
0.3
yearly

staff
0.4
yearly

categorie eventi (live, karaoke, etc)
0.6
daily

categorie + zona geografica
0.7
weekly
*/
class SitemapPlugin {
  
  private $machine;
  private $DB;
  private $Link;
  
  public function __construct($machine) {
    $this->machine = $machine;
    $this->DB = $this->machine->plugin("DB");
    $this->Link = $this->machine->plugin("Link");
  }
  
  public function generate($dir, $mapname) {
    $schema = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https:" : "http:";
    
    $sitemap = new Sitemap();
    
    // home
    $sitemap->add($schema . $this->Link->Get("HOME"), date("Y-m-d"), ChangeFrequency::HOURLY, 1.0);
    
    // categorie
    $items = $this->getCategories();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["CATEGORIA_LOCALI", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::DAILY, 0.9);
    }
    
    // scheda location
    $items = $this->getLocations();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["LOCALE", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::WEEKLY, 0.9);
    }
    
    // eventi (pagina)
    $sitemap->add($schema . $this->Link->Get("EVENTI"), date("Y-m-d"), ChangeFrequency::HOURLY, 0.9);

    // scheda evento
    $items = $this->getEvents();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["EVENTO", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::NEVER, 0.8);
    }
    
    // eventi passati locale (pagina)
    $items = $this->getLocationPastEvents();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["EVENTI_PASSATI", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::MONTHLY, 0.4);
    }
    
    // eventi weekend (pagina)
    $sitemap->add($schema . $this->Link->Get("EVENTI_WEEKEND"), date("Y-m-d"), ChangeFrequency::DAILY, 0.7);
    
    // eventi periodo
    $items = $this->getPeriodEvents();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["EVENTI_DATA", $y, $m, $d]), date("Y-m-d"), ChangeFrequency::DAILY, 0.4);
    }
    
    // eventi festivita
    $items = $this->getFestivita();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["FESTIVITA", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::WEEKLY, 0.8);
    }
    
    // eventi passati festivita
    $items = $this->getFestivita();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["FESTIVITA_ARCHIVIO", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::WEEKLY, 0.5);
    }
        
    // pagine generiche privacy, contatti, etc
    $items = $this->getSezioni();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["SEZIONE", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::YEARLY, 0.3);
    }
    
    // staff
    $sitemap->add($schema . $this->Link->Get("PAGINA", "staff"), date("Y-m-d"), ChangeFrequency::YEARLY, 0.4);
    
    // categorie eventi
    $items = $this->getCategories();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["EVENTI_CATEGORIA", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::DAILY, 0.6);
    }
    
    // categorie + zona geografica
    $items = $this->getCategoriesZones();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["CATEGORIA_ZONA", $i["categoria"], $i["zona"]), date("Y-m-d"), ChangeFrequency::WEEKLY, 0.7);
    }
    
    // Write it to a file
    file_put_contents($dir . "/" . $mapname . '.xml', (string) $sitemap);
  }
  
  private function getCategories() {
    $query = "SELECT id, seo_url FROM typo_btw_sites WHERE site_id = ? AND active = 1 AND menu = 0 ORDER BY position";
    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
  
  private function getLocations() {
    $query = "SELECT id, seo_url FROM locations WHERE site_id = ? AND active = 1";
    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
  
  private function getEvents() {
  
  }
  
  private function getLocationPastEvents() {
  
  }
  
  private function getPeriodEvents() {
    
  }
  
  private function getFestivita() {
    $query = "SELECT id, seo_url FROM holiday_btw_sites WHERE site_id = ?";
    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
  
  private function getSezioni() {
    $query = "SELECT id, seo_url FROM sections WHERE site_id = ? AND sitemap = 1";
    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
  
  private function getCategories() {
    $query = "SELECT id, seo_url FROM cat_btw_sites WHERE site_id = ?";
    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
  
  private function getCategoriesZones() {
    $query = "SELECT id, seo_url FROM cat_btw_sites WHERE site_id = ?";
    $result = $this->DB->select($query, [$this->DB->getSite()]);
    return $result;
  }
}