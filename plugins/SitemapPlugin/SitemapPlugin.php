<?php
namespace WebEngine\Plugin;
    
use Tackk\Cartographer\Sitemap;
use Tackk\Cartographer\ChangeFrequency;
    
class SitemapPlugin {
  
  private $machine;
  
  public function __construct($machine) {
    $this->machine = $machine;
  }
  
  public function generate($dir, $mapname) {
    $sitemap = new Sitemap();
    $sitemap->add('http://foo.com', '2005-01-02', ChangeFrequency::WEEKLY, 1.0);
    $sitemap->add('http://foo.com/about', '2005-01-01');

    // Write it to a file
    file_put_contents($dir . "/" . $mapname . '.xml', (string) $sitemap);
  }
}