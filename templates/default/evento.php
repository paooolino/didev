<?php include("partials/header.php"); ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "Event",
  "name": "<?php echo $evento["title"]; ?>",
  "eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode",
  "eventStatus": "https://schema.org/EventScheduled",
  "location": {
	"@type": "Place",
	"name": "<?php echo $evento["locations_title"]; ?>",
	"telephone": "<?php echo $evento["locations_phone"]; ?>",
	"address": {
	  "@type": "PostalAddress",
	  "streetAddress": "<?php echo $evento["locations_address_way"]; ?> <?php echo $evento["locations_address_number"]; ?>",
	  "addressLocality": "<?php echo $evento["locations_address_city"]; ?>",
	  "postalCode": "<?php echo $evento["locations_address_zip"]; ?>",
	  "addressRegion": "<?php echo $evento["address_province"]; ?>",
	  "addressCountry": "IT"
	}
  },
  "image": [
		<?php
		$photos_img = []; 
		foreach ($photos as $ph) { 
		$photos_img[] = ''. $App->img("photos", $ph["id"], 800, "H", $ph["image_file_name"]).'';
		}
		echo implode(',',$photos_img)
		?>
   	],
  "description": "{{seoDescription}}",

  "organizer": {
	"@type": "Organization",
	"name": "<?php echo $evento["locations_title"]; ?>}}",
	"logo": "<?php echo $logo_img; ?>",
	"url": "https:{{Link|Get|LOCALE|<?php echo $evento["locations_seo_url"]; ?>}}"
  }
}
</script>
<div class="row">
  <div class="small-12 large-9 columns">
    <section class="row" id="content">
      <div class="small-12 columns">
        <nav aria-label="breadcrumbs" class="breadcrumbs breadcrumb" role="menubar">
          <li role="menuitem">
            <a href="{{Link|Get|HOME}}" title="home discoteche, locali, ristoranti ed eventi a <?php echo $currentCity; ?>">
              <i class="fa fa-home"></i>
              home
            </a>
          </li>
          <?php if ($evento["expired"]) { ?>
          <li role="menuitem">
            <a href="{{Link|Get|EVENTI_PASSATI_ALL}}">Archivio eventi</a>
          </li>
          <?php } else { ?>
          <li role="menuitem">
            <a title="prossimi eventi a <?php echo $currentCity; ?>" href="{{Link|Get|EVENTI}}">eventi <?php echo $currentCity; ?></a>
          </li>
          <?php } ?>
          <?php if ($evento["locations_id"] != NULL) { ?>
          <li role="menuitem">
            <a title="<?php echo $evento["locations_title"]; ?>" href="{{Link|Get|LOCALE|<?php echo $evento["locations_seo_url"]; ?>}}"><?php echo $evento["locations_title"]; ?></a>
          </li>
          <?php } ?>
          <li class="current" role="menuitem">
            <a title="<?php echo $evento["title"]; ?>"><?php echo $evento["title"]; ?></a>
          </li>
        </nav>
        <section class="boxed spaceB">
          <div class="intro">
            <img alt="<?php echo $evento["locations_title"]; ?>" title="<?php echo $evento["locations_title"]; ?>" class="thumbcat" 
              src="<?php echo $logo_img; ?>">
            <hgroup id="headevent">
              <span class="summary">
                <?php echo $evento["title_date"]; ?>
                 
                <?php if ($evento["locations_id"] != NULL) { ?>
                <span>presso</span>
                <a title="<?php echo $evento["locations_title"]; ?>" href="{{Link|Get|LOCALE|<?php echo $evento["locations_seo_url"]; ?>}}"><?php echo $evento["locations_title"]; ?></a>
                <?php } ?>
              </span>
              <h1 class="mainsummary"><?php echo $evento["title"]; ?></h1>
              <span class="address">
                <i class="fa fa-map-marker color"></i>
                <?php if ($evento["locations_address_way"] != "") { ?> 
                  <?php echo $evento["locations_address_way"]; ?>,
                  <?php echo $evento["locations_address_number"]; ?>
                  - <?php echo $evento["locations_address_city"]; ?>, 
                  <?php echo $evento["locations_address_zip"]; ?> - (<?php echo $evento["locations_address_province"]; ?>), IT                  
                <?php } elseif ($evento["address_way"] != "") { ?> 
                  <?php echo $evento["address_way"]; ?>,
                  <?php echo $evento["address_number"]; ?>
                  - <?php echo $evento["address_city"]; ?>, 
                  <?php echo $evento["address_zip"]; ?> - (<?php echo $evento["address_province"]; ?>), IT                  
                <?php } ?>                

                <?php if (count($map) == 1) { ?><a href="#" onclick="jQuery('html,body').animate({scrollTop: $('#map').offset().top},'slow');">vedi mappa</a><?php } ?>
              </span>
            </hgroup>
            
            <?php if ($evento["expired"]) { ?>
            <div class="expired">
              <span class="notice bt gray rounded">! questo evento è scaduto !</span>
              <a class="next_events bt rounded" href="<?php echo $Link->Get(["LOCALE", $evento["locations_seo_url"]]); ?>">Guarda gli eventi aggiornati</a>
            </div>
            <?php } ?>
            
            <?php
            $arrinfo = array_filter([$evento["locations_phone"], $evento["locations_mobile"]]);
            if (count($arrinfo) > 0) {
              ?>
              <div class="caption booking">
                <span>Info e prenotazioni <?php echo $evento["locations_title"]; ?>:</span>
                <?php echo implode(" - ", $arrinfo); ?>
                </div>
              <?php
            }
            ?>
            
          </div>
          <article class="spaceT" id="desc">
            <?php if ($event_image != "")  { ?>
              <span class="image_detail aright">
                <img 
                  alt="<?php echo $evento["title"]; ?>" 
                  data-interchange="
                    [<?php echo $event_image ?>, (default)], 
                    [<?php echo $event_image; ?>, (medium)], 
                    [<?php echo $event_image; ?>, (large)]
                  " 
                  src="<?php echo $event_image; ?>" 
                  title="<?php echo $evento["title"]; ?>"
                >
                <noscript>
                  <img 
                    alt="<?php echo $evento["title"]; ?>" 
                    src="<?php echo $event_image; ?>" 
                    title="<?php echo $evento["title"]; ?>" />
                </noscript>
              </span>
            <?php } ?>
            <?php echo $evento["description"]; ?>
            
<?php if (isset($evento["ads"]) && $evento["ads"] == 0) {} else { ?>
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Annuncio responsive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-6371727345571989"
     data-ad-slot="7697964545"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>
<?php } ?>

            <?php
            $arrinfo = array_filter([$evento["locations_phone"], $evento["locations_mobile"]]);
            if (count($arrinfo) > 0) {
              ?>
              <div class="caption booking">
                <span>Info e prenotazioni <?php echo $evento["locations_title"]; ?>:</span>
                <?php echo implode(" - ", $arrinfo); ?>
                </div>
              <?php
            }
            ?>
            
          </article>
              
          <?php if (count($photos) > 0) { ?>
          <section class="photos_wrap sheet" id="gallery">
            <h3 class="toggle">
              Photogallery <?php echo $locale["title"]; ?>
              <i class="fa fa-angle-down"></i>
            </h3>
            <div class="contentToggle">
              <div class="pgallery">
                <?php foreach ($photos as $photo) { ?>
                  <?php 
                    $photourl = $App->img("photos", $photo["id"], 800, "H", $photo["image_file_name"]);
                    $photothumb = $App->img("photos", $photo["id"], 84, 56, $photo["image_file_name"]); 
                  ?>              
                  <a title="<?php echo $photo["title"]; ?>" rel="gallery" class="th" 
                    href="<?php echo $photourl; ?>">
                    <img alt="<?php echo $photo["title"]; ?>" title="<?php echo $photo["title"]; ?>" 
                      src="<?php echo $photothumb; ?>"></a>
                <?php } ?>
              </div>
            </div>
          </section>
          <?php } ?>
      
          <?php if (count($map) == 1) { ?>
          <section class="sheet" id="map">
            <h3>
              <?php echo $evento["locations_title"]; ?>: come arrivare.
            </h3>
            <div class="contentToggle">
              <p><?php echo $map[0]["address"]; ?></p>
              <div class="">
                <div class="geoMapWrap">
                  <iframe width="100%" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo urlencode($map[0]["address"]); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
                </div>
              </div>
            </div>
          </section>
          <?php } ?>
          
          <?php
            $u = "https:" . $Link->Get(["EVENTO", $evento["seo_url"]]);
            $t = $evento["title"];
          ?>
          <section class="sheet share_wrap" id="share">
            <ul class="button-group">
              <li>
                <a class="button fb" href="http://www.facebook.com/share.php?u=<?php echo $u; ?>&amp;title=<?php echo $t; ?>" target="_blank" title="Condividi su Facebook">
                  <i class="fa fa-facebook fa-lg"></i>
                  Condividi su Facebook
                </a>
              </li>
              <li>
                <a class="button tw" href="http://twitter.com/home?status=<?php echo $t; ?>+<?php echo $u; ?>" target="_blank" title="Condividi su Twitter">
                  <i class="fa fa-twitter fa-lg"></i>
                  Condividi su Twitter
                </a>
              </li>
            </ul>
          </section>
        </section>
        <div class="row all_next_events">
          <div class="small-12 columns cnt spaceT spaceB">
            <a class="bt" href="/eventi" title="Eventi Brescia">
              <i class="fa fa-calendar"></i>
              Visualizza tutti gli eventi a <?php echo $currentCity; ?>
            </a>
          </div>
        </div>
      </div>
    </section>
  </div>

  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <?php 
    if (isset($calendar)) {
      include("partials/calendar.php"); 
    }
    ?>
    <div class="show-for-large-up spaceT">
      <aside class="adsBanner responsive visible-for-large-up">
        <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
        <!-- Annuncio responsive -->
        <ins class="adsbygoogle"
             style="display:block"
             data-ad-client="ca-pub-6371727345571989"
             data-ad-slot="7697964545"
             data-ad-format="auto"
             data-full-width-responsive="true"></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>
      </aside>
    </div>
  </aside>

</div>

<?php include("partials/footer.php"); ?>
