<?php
namespace WebEngine\Plugin;
    
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\ChangeFrequency;
    
/*
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

Eventi
0.9
hourly

Scheda evento
0.8
never

eventi-passati-locale
0.4
monthly

eventi-weekend
0.7
daily

eventi-periodo
0.4
daily

eventi-festività
0.8
weekly

eventi-passati-festivita
0.5
weekly

eventi
1.0
daily

pagine genriche privacy, cntatti etc...
0.3
yearly

staff
0.4
yearly

categorie eventi
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
    // location
    $items = $this->getLocations();
    foreach ($items as $i) {
      $sitemap->add($schema . $this->Link->Get(["LOCALE", $i["seo_url"]]), date("Y-m-d"), ChangeFrequency::WEEKLY, 0.9);
    }
    // eventi
    
    // evento
    
    // eventi passati locale
    
    // eventi weekend
    
    // eventi periodo
    
    // eventi festivita
    
    // eventi passati festivita
    
    // eventi
    
    // pagine generiche privacy, contatti, etc
    
    // staff
    
    // categorie eventi
    
    // categorie + zona geografica
    
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
}