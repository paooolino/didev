<?php include("partials/header.php"); ?>
<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "Organization",
	  "logo": "https:<?php echo $logo_img; ?>",
      "image": [
	<?php
	$photos_img = [];  
	foreach ($photos as $ph) { 
	$photos_img[] = '"https:'. $App->img("photos_img", $ph["id"], 800, "H", $ph["image_file_name"]).'"';
	}
	echo implode(',',$photos_img)
	?>
   ],
      "name": "<?php echo $locale["title"]; ?>",
      "address": {
		"@type": "PostalAddress",
		"streetAddress": "<?php echo $locale["address_way"]; ?> <?php echo $locale["address_number"]; ?>",
		"addressLocality": "<?php echo $locale["address_city"]; ?>",
		"postalCode": "<?php echo $locale["address_zip"]; ?>",
		"addressRegion": "<?php echo $locale["address_province"]; ?>",
		"addressCountry": "IT"
      },
      <?php /*?>"geo": {
        "@type": "GeoCoordinates",
        "latitude": 37.293058,
        "longitude": -121.988331
      },<?php */?>
      "url": "https:{{Link|Get|LOCALE|<?php echo $locale["seo_url"]; ?>}}",
	  "email": "<?php echo $locale["email"]; ?>",
      "telephone": "<?php echo $locale["phone"]; ?>",
	  "tel": "<?php echo $locale["mobile"]; ?>"
    }
    </script>

<section class="row headbox">
  <div class="small-12 columns">
    <nav aria-label="breadcrumbs" class="breadcrumbs breadcrumb" role="menubar">
      <li role="menuitem">
        <a href="/" title="home discoteche, locali, ristoranti ed eventi a Brescia">
          <i class="fa fa-home"></i>
          home
        </a>
      </li>
      <li role="menuitem">
        <a title="<?php echo $cat["title"]; ?>" href="/locali/<?php echo $cat["seo_url"]; ?>"><?php echo $cat["title"]; ?></a>
      </li>
      <li class="current" role="menuitem">
        <a title="<?php echo $locale["title"]; ?>" href="<?php echo $locale["seo_url"]; ?>"><?php echo $locale["title"]; ?></a>
      </li>
    </nav>
    
    <div id="heading">
      <div class="slideshow">
        <?php foreach($showcase as $k => $slide) { ?>
        <div>
          <?php 
            if ($slide["image_fingerprint"] != null)
              $img = $App->img("location_showcases", $slide["id"], 1335, 516, $slide["image_fingerprint"] . "-" . $slide["image_file_name"]);
            else
              $img = $App->img("location_showcases", $slide["id"], 1335, 516, $slide["image_file_name"]);
          ?>
          <div class="slide">
            <img alt="<?php echo $slide["title"]; ?>" data-interchange="
                [<?php echo $img; ?>, (default)], 
                [<?php echo $img; ?>, (small)], 
                [<?php echo $img; ?>, (medium)], 
                [<?php echo $img; ?>, (large)]
              " 
              src="<?php echo $img; ?>" title="<?php echo $slide["title"]; ?>" data-uuid="interchange-jc2h9duk0">
            <noscript>
              <img alt="<?php echo $slide["title"]; ?>" src="<?php echo $img; ?>" title="<?php echo $slide["title"]; ?>" />
            </noscript>
          </div>
        </div>
        <?php } ?>
      </div>
      <img id="logos" class="hide-for-large-up" src="<?php echo $logo_img; ?>" alt="Df6c727551ad634b0a77a003d1970e02 convento  dinner and dance music restaurantlonatodelgardalagogardalogo">
      <div class="infos vcard h-card" >
        <img id="logos" class="show-for-large-up" src="<?php echo $logo_img; ?>" alt="Df6c727551ad634b0a77a003d1970e02 convento  dinner and dance music restaurantlonatodelgardalagogardalogo">
        <h1 class="main_summary fn org p-name" ><?php echo $locale["title"]; ?></h1>
        <div class="show-for-large-up large">
          <span class="bt rounded phones" >
            <i class="fa fa-phone"></i>
            <span>INFO E PRENOTAZIONI:</span>
            <span class="phone" ><?php echo $locale["phone"]; ?></span>
            <?php if ($locale["mobile"] != "") { ?>
              - 
            <span class="phone" ><?php echo $locale["mobile"]; ?></span>
            <?php } ?>
          </span>
          <?php if ($locale["email"] != "") { ?>
          <span>
            <a aria-haspopup="" class="has-tip bt white rounded email u-email" data-galabel="Link_MAIL" data-gavalue="<?php echo $locale["title"]; ?>" data-tooltip="" href="mailto:<?php echo $locale["email"]; ?>" data-selector="tooltip-jc2h9dut0" aria-describedby="tooltip-jc2h9dut0" title="">
            <i class="fa fa-paper-plane"></i>
            email
            </a>
          </span>
          <?php } ?>
          <?php if ($locale["url"] != "") { ?>
          <span >
            <a class="bt gray rounded website url u-url" data-galabel="Link_WEB" data-gavalue="<?php echo $locale["title"]; ?>" href="<?php echo $locale["url"]; ?>" rel="nofollow" title="sito web, blog, ecc...">
            <i class="fa fa-globe"></i>
            Sito web
            </a>
          </span>
          <?php } ?>
        </div>
        <div class="hide-for-large-up not-large">
          <a class="bt rounded" href="tel:<?php echo $locale["phone"]; ?>" title="">
            <i class="fa fa-phone"></i>
            <?php echo $locale["phone"]; ?>
          </a>
          <?php if ($locale["mobile"] != "") { ?>
          <a class="bt rounded" href="tel:<?php echo $locale["mobile"]; ?>" title="">
            <i class="fa fa-phone"></i>
            <?php echo $locale["mobile"]; ?>
          </a>
          <?php } ?>
        </div>
        <div class="address">
          <span class="adr" >
            <i class="fa fa-map-marker color"></i>
            <span class="street-address" i><?php echo $locale["address_way"]; ?>, <?php echo $locale["address_number"]; ?> - </span>
            <span class="postal-code" ><?php echo $locale["address_zip"]; ?> - </span>
            <span class="locality" ><?php echo $locale["address_city"]; ?>, </span>
            <span class="region" >(<?php echo $locale["address_province"]; ?>)</span>
            <span class="country-name" >, IT</span>
          </span>
           - 
          <a title="" class="show-map" href="#" onclick="jQuery('html,body').animate({scrollTop: $('#maps').offset().top},'slow');">vedi mappa</a>
        </div>
        <div class="hide-for-large-up not-large">
          <?php if ($locale["email"] != "") { ?>
          <a aria-haspopup="" class="has-tip bt white rounded email u-email" data-galabel="Link_MAIL" data-gavalue="<?php echo $locale["title"]; ?>" data-tooltip="" href="mailto:<?php echo $locale["email"]; ?>" data-selector="tooltip-jc2h9dut1" aria-describedby="tooltip-jc2h9dut1" title="">
            <i class="fa fa-paper-plane"></i>
            email
          </a>
          <?php } ?>
          <?php if ($locale["url"] != "") { ?>
          <a class="bt gray rounded website url u-url" data-galabel="Link_WEB" data-gavalue="<?php echo $locale["title"]; ?>" href="<?php echo $locale["url"]; ?>" rel="nofollow" title="sito web, blog, ecc...">
            <i class="fa fa-globe"></i>
            Sito web
          </a>
          <?php } ?>
        </div>
        
      </div>
    </div>
  </div>
</section>

<section class="content" id="wrap_content">
  <div class="row collapse innerwrap">
    <div class="small-12 medium-12 large-9 columns" id="wrapper">
      <div class="sheet">
        <div class="caption booking">
          <span>INFO E PRENOTAZIONI <?php echo $locale["title"]; ?>:</span>
          <?php echo $locale["phone"]; ?> 
          <?php if ($locale["mobile"] != "") { ?> - <?php echo $locale["mobile"]; } ?>
          <?php if ($locale["email"] != "") { ?> - <a href="mailto:<?php echo $locale["email"]; ?>"><?php echo $locale["email"]; ?></a><?php } ?>
        </div>
      </div>
      
      <section class="sheet" id="desc">
        <article>
          <h2 class="subsummary text-justify sub_summary">
            <?php echo $locale["sub_title"]; ?>
          </h2>
			<p>Date le numerose e frequenti modifiche alle <strong>restrizioni imposte per il COVID (coronavirus)</strong> ti preghiamo di contattare il locale prima di organizzare la tua serata.</p>
          <?php echo $locale["description"]; ?>

<?php if (isset($locale["ads"]) && $locale["ads"] == 0) {} else { ?>
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

        </article>
      </section>
      
      <?php if ($locale["food"] != "") { ?>
      <section class="food_wrap sheet spaceB" id="food">
        <h3 class="toggle">
          Il ristorante
          <i class="fa fa-angle-down" style=""></i>
        </h3>
        <div class="contentToggle" style="display: block;">
          <?php echo $locale["food"]; ?>
        </div>
      </section>
      <?php } ?>
      
      <?php if (count($locale_events) > 0) { ?>
      <div class="sheet next_events" id="events">
        <h2 class="toggle">
          Eventi <?php echo $locale["title"]; ?>
          <i class="fa fa-angle-down"></i>
        </h2>
        <div class="contentToggle">
          <?php foreach ($locale_events as $event) { ?>
          <?php 
          $event_img = "";
          if (is_null($event["recurrent_id"]) && $event["image_file_name"] != "") {
            $event_img = $App->img(
              "events", $event["id"], 315, 177,
              $event["image_file_name"]
            );
          } else {
            if ($event["recurrent_image"] != "") {
              $event_img = $App->img(
                "recurrents", $event["recurrent_id"], 315, 177,
                $event["recurrent_image"]
              );
            }
          }          
          ?>
          <div class="row collapse abstract">
            <div class="thumbnail small-12 medium-3 large-4 columns">
              <a title="<?php echo $event["title"]; ?>" href="{{Link|Get|EVENTO|<?php echo $event["seo_url"]; ?>}}">
                <img 
                  alt="<?php echo $event["title"]; ?>" 
                  data-interchange="
                    [<?php echo $event_img; ?>, (default)], 
                    [<?php echo $event_img; ?>, (medium)], 
                    [<?php echo $event_img; ?>, (large)]
                  " 
                  src="<?php echo $event_img; ?>" title="<?php echo $event["title"]; ?>" 
                  data-uuid="interchange-jc2h9duk6">
              <noscript>
                <img alt="<?php echo $event["title"]; ?>" src="<?php echo $event_img; ?>" title="<?php echo $event["title"]; ?>" />
              </noscript>
              </a>
            </div>
            <div class="small-12 medium-9 large-8 columns texts">
              <div class="desc">
                <span><?php echo $event["title_date"]; ?></span>
                <a title="<?php echo $event["title"]; ?>" class="p-name summary" href="{{Link|Get|EVENTO|<?php echo $event["seo_url"]; ?>}}">
                  <?php echo $event["title"]; ?>
                </a>
                <p class="description p-summary description"><?php echo $event["seo_description"]; ?></p>
              </div>
            </div>
          </div>
          <?php } ?>

          <?php if (count($past_events) > 0) { ?>
          <p class="sheet past_events" id="events">
            <a href="{{Link|Get|EVENTI_PASSATI|<?php echo $locale["seo_url"]; ?>}}">
              <i class="fa fa-calendar"></i>
              Archivio eventi e serate del <?php echo $locale["title"]; ?>
            </a>
          </p>
          <?php } ?>
        </div>
      </div>
      <?php } else { ?>
        <?php if (count($past_events) > 0) { ?>
          <div class="sheet next_events" id="events">      
            <a href="{{Link|Get|EVENTI_PASSATI|<?php echo $locale["seo_url"]; ?>}}">
              <i class="fa fa-calendar"></i>
              Archivio eventi e serate del <?php echo $locale["title"]; ?>
            </a>
          </div>
        <?php } ?>
      <?php } ?>
      <?php if ($locale["prices"] != "") { ?>
      <div class="sheet prices" id="prices">
        <h3 class="toggle">
          Prezzi <?php echo $locale["title"]; ?>
          <i class="fa fa-angle-down"></i>
        </h3>
        <div class="contentToggle">
          <?php echo $locale["prices"]; ?>
        </div>
      </div>
      <?php } ?>
      
      <?php if ($locale["info"] != "") { ?>
      <section class="sheet booking_wrap" id="booking">
        <h3 class="toggle">
          Info e prenotazioni <?php echo $locale["title"]; ?>
          <i class="fa fa-angle-down"></i>
        </h3>
        <div class="contentToggle">
          <?php echo $locale["info"]; ?>
          <div class="reports_wrap spaceB">
            <div class="spaceB">
              <?php echo $locale["phone"]; ?> 
              <?php if ($locale["mobile"] != "") { ?> - <?php echo $locale["mobile"]; } ?>
              <?php if ($locale["email"] != "") { ?> - <a href="mailto:<?php echo $locale["email"]; ?>"><?php echo $locale["email"]; ?></a><?php } ?>
            </div>
          </div>
          <?php if ($locale["on_list"] == 1) { ?>
          <div>
            <div class="onlist_wrap">
              <p>
                <a class="bt for-onlist-toggler" href="#">Mettiti in lista o prenota tavolo</a>
              </p>
            </div>
          </div>
          <?php } ?>
        </div>
      </section>
      <div class="onlistwrapper">
        <?php include("partials/fixed/onlist.php"); ?>
      </div>
      <?php } ?>
      
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
      
      <?php if ($locale["video"] != "") { ?>
      <section class="video_wrap sheet" id="video">
        <h3 class="toggle">
          Video
          <i class="fa fa-angle-down"></i>
        </h3>
        <div class="contentToggle">
          <div class="flex-video">
            <?php echo $locale["video"]; ?>
          </div>
        </div>
      </section>
      <?php } ?>

      <?php if (count($map) > 0) { ?>
      <section class="sheet" id="maps">
        <h3 class="toggle">
          dove siamo
          <i class="fa fa-angle-down"></i>
        </h3>
        <div class="contentToggle">
          <?php foreach ($map as $m) { ?>
          <p><?php echo $m["address"]; ?></p>
          <div class="">
            <div class="geoMapWrap">
              <iframe width="100%" height="400" src="https://maps.google.com/maps?q=<?php echo urlencode($m["address"]); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
          </div>
          <br><br>
          <?php } ?>
        </div>
      </section>
      <?php } ?>
      
      <?php
        $u = "https:" . $Link->Get(["LOCALE", $locale["seo_url"]]);
        $t = $locale["title"];
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
        <p class="errata">
          Se avete riscontrato inesattezze in questa pagina <a href="/sezione/contatti">segnalatecelo</a>.
        </p>
      </section>
    </div>
    
    <div class="show-for-large-up large-3 columns box_subnav" id="boxsubnav">
      <div id="subnav" style="margin-top: 0px;">
        <ul>
          <li class="description">
            <a href="#desc" title="<?php echo $locale["title"]; ?>" class="active">
            <i class="fa fa-align-justify fa-fw"></i>
              <?php echo $locale["title"]; ?>
            </a>
          </li>
          <?php if ($locale["food"] != "") { ?>
          <li class="food">
            <a href="#food" title="Il ristorante">
              <i class="fa fa-cutlery fa-fw"></i>
              Il ristorante
            </a>
          </li>
          <?php } ?>
          <?php if (count($locale_events) > 0) { ?>
          <li class="events">
            <a href="#events" title="Eventi" class="">
              <i class="fa fa-calendar fa-fw"></i>
              Eventi
            </a>
          </li>
          <?php } ?>
          <?php if ($locale["prices"] != "") { ?>
          <li class="prices">
            <a href="#prices" title="Prezzi" class="">
              <i class="fa fa-money fa-fw"></i>
              Prezzi
            </a>
          </li>
          <?php } ?>
          <?php if ($locale["info"] != "") { ?>
          <li class="booking">
            <a href="#booking" title="Info e prenotazioni" class="">
              <i class="fa fa-phone fa-fw"></i>
              Info e prenotazioni
            </a>
          </li>
          <?php } ?>
          <?php if (count($photos) > 0) { ?>
          <li class="photos">
            <a href="#gallery" title="Photogallery" class="">
              <i class="fa fa-camera-retro fa-fw"></i>
              Photogallery
            </a>
          </li>
          <?php } ?>
          <?php if ($locale["video"] != "") { ?>
          <li class="video">
            <a href="#video" title="Video" class="">
              <i class="fa fa-video-camera fa-fw"></i>
              Video
            </a>
          </li>
          <?php } ?>
          <?php if (count($map) > 0) { ?>
          <li class="maps">
            <a href="#maps" title="Mappa" class="">
              <i class="fa fa-calendar fa-fw"></i>
              Mappa
            </a>
          </li>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php include("partials/footer.php"); ?>
