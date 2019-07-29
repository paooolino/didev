<?php include("partials/header.php"); ?>

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
            $img = $App->img("location_showcases", $slide["id"], 1335, 516, $slide["image_fingerprint"] . "-" . $slide["image_file_name"]);
          ?>
          <div class="slide">
            <img alt="Convento, lago di Garda" data-interchange="
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
      <div class="infos vcard h-card" itemscope="" itemtype="http://schema.org/Restaurant">
        <img id="logos" class="show-for-large-up" src="<?php echo $logo_img; ?>" alt="Df6c727551ad634b0a77a003d1970e02 convento  dinner and dance music restaurantlonatodelgardalagogardalogo">
        <h1 class="main_summary fn org p-name" itemprop="name"><?php echo $locale["title"]; ?></h1>
        <div class="show-for-large-up large">
          <span class="bt rounded phones" itemprop="ContactPoint" itemscope="" itemtype="http://schema.org/ContactPoint">
            <i class="fa fa-phone"></i>
            <span itemprop="contactType">INFO E PRENOTAZIONI:</span>
            <span class="phone" itemprop="servicePhone"><?php echo $locale["phone"]; ?></span>
            <?php if ($locale["mobile"] != "") { ?>
              - 
            <span class="phone" itemprop="servicePhone"><?php echo $locale["mobile"]; ?></span>
            <?php } ?>
          </span>
          <?php if ($locale["email"] != "") { ?>
          <span>
            <a aria-haspopup="" class="has-tip bt white rounded email u-email" data-galabel="Link_MAIL" data-gavalue="<?php echo $locale["title"]; ?>" data-tooltip="" href="mailto:<?php echo $locale["email"]; ?>" itemprop="email" data-selector="tooltip-jc2h9dut0" aria-describedby="tooltip-jc2h9dut0" title="">
            <i class="fa fa-paper-plane"></i>
            email
            </a>
          </span>
          <?php } ?>
          <?php if ($locale["url"] != "") { ?>
          <span itemscope="" itemtype="http://schema.org/Thing">
            <a class="bt gray rounded website url u-url" data-galabel="Link_WEB" data-gavalue="<?php echo $locale["title"]; ?>" href="<?php echo $locale["url"]; ?>" itemprop="url" rel="nofollow" title="sito web, blog, ecc...">
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
          <span class="adr" itemprop="address" itemscope="" itemtype="http://schema.org/PostalAddress">
            <i class="fa fa-map-marker color"></i>
            <span class="street-address" itemprop="streetAddress"><?php echo $locale["address_way"]; ?>, <?php echo $locale["address_number"]; ?> - </span>
            <span class="postal-code" itemprop="postalCode"><?php echo $locale["address_zip"]; ?> - </span>
            <span class="locality" itemprop="addressLocality"><?php echo $locale["address_city"]; ?>, </span>
            <span class="region" itemprop="addressRegion">(<?php echo $locale["address_province"]; ?>)</span>
            <span class="country-name" itemprop="addressCountry">, IT</span>
          </span>
           - 
          <a title="" class="show-map" href="#maps">vedi mappa</a>
        </div>
        <div class="hide-for-large-up not-large">
          <?php if ($locale["email"] != "") { ?>
          <a aria-haspopup="" class="has-tip bt white rounded email u-email" data-galabel="Link_MAIL" data-gavalue="<?php echo $locale["title"]; ?>" data-tooltip="" href="mailto:<?php echo $locale["email"]; ?>" itemprop="email" data-selector="tooltip-jc2h9dut1" aria-describedby="tooltip-jc2h9dut1" title="">
            <i class="fa fa-paper-plane"></i>
            email
          </a>
          <?php } ?>
          <?php if ($locale["url"] != "") { ?>
          <a class="bt gray rounded website url u-url" data-galabel="Link_WEB" data-gavalue="<?php echo $locale["title"]; ?>" href="<?php echo $locale["url"]; ?>" itemprop="url" rel="nofollow" title="sito web, blog, ecc...">
            <i class="fa fa-globe"></i>
            Sito web
          </a>
          <?php } ?>
        </div>
        <span class="geo" itemprop="geo" itemscope="" itemtype="http://schema.org/GeoCoordinates">
          <meta content="45.4604" itemprop="latitude">
          <meta content="10.4944" itemprop="longitude">
          <span class="latitude">
            <span class="value-title" title="45.4604"></span>
          </span>
          <span class="longitude">
            <span class="value-title" title="10.4944"></span>
          </span>
        </span>
      </div>
    </div>
  </div>
</section>

<section class="content" id="wrap_content">
  <div class="row collapse innerwrap">
    <div class="small-12 medium-12 large-9 columns" id="wrapper">
      <div class="sheet">
        <div class="spaceT" id="social_tab">
          <div class="google"><div id="___plusone_0" style="text-indent: 0px; margin: 0px; padding: 0px; background: transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 32px; height: 20px;"><iframe ng-non-bindable="" frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 32px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 20px;" tabindex="0" vspace="0" width="100%" id="I0_1515190427335" name="I0_1515190427335" src="https://apis.google.com/u/0/se/0/_/+1/fastbutton?usegapi=1&amp;size=medium&amp;annotation=none&amp;origin=http%3A%2F%2Fwww.discotechebrescia.it&amp;url=http%3A%2F%2Fwww.discotechebrescia.it%2Flocale%2Fdisco-restaurant-al-convento-lonato&amp;gsrc=3p&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.it.iQNRC7gtC5o.O%2Fm%3D__features__%2Fam%3DAQ%2Frt%3Dj%2Fd%3D1%2Frs%3DAGLTcCN4Z8wjz7c10eMYV9TjmfORzuvrBA#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Cdrefresh%2Cerefresh%2Conload&amp;id=I0_1515190427335&amp;_gfid=I0_1515190427335&amp;parent=http%3A%2F%2Fwww.discotechebrescia.it&amp;pfname=&amp;rpctoken=10042135" data-gapiattached="true" title="G+"></iframe></div></div><div class="twitter"><iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" title="Twitter Tweet Button" src="https://platform.twitter.com/widgets/tweet_button.eaf4b750247dd4d0c4a27df474e7e934.it.html#dnt=false&amp;id=twitter-widget-0&amp;lang=it&amp;original_referer=http%3A%2F%2Fwww.discotechebrescia.it%2Flocale%2Fdisco-restaurant-al-convento-lonato&amp;related=condividi%20su%20twitter&amp;size=m&amp;text=Il%20Convento%3A%20Disco%20%26%20music%20restaurant%20al%20Convento%20di%20Lonato.&amp;time=1515190427554&amp;type=share&amp;url=http%3A%2F%2Fwww.discotechebrescia.it%2Flocale%2Fdisco-restaurant-al-convento-lonato&amp;via=DiscotecheBS" style="position: static; visibility: visible; width: 60px; height: 20px;"></iframe><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><div class="fb-like fb_iframe_widget" data-width="button_count" fb-xfbml-state="rendered" fb-iframe-plugin-query="app_id=&amp;container_width=0&amp;href=http%3A%2F%2Fwww.discotechebrescia.it%2Flocale%2Fdisco-restaurant-al-convento-lonato&amp;locale=it_IT&amp;sdk=joey&amp;width=button_count"><span style="vertical-align: top; width: 0px; height: 0px; overflow: hidden;"><iframe name="f3b3ad7c37a56cc" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" title="fb:like Facebook Social Plugin" src="https://www.facebook.com/v2.4/plugins/like.php?app_id=&amp;channel=http%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2FlY4eZXm_YWu.js%3Fversion%3D42%23cb%3Df5941f100cf9%26domain%3Dwww.discotechebrescia.it%26origin%3Dhttp%253A%252F%252Fwww.discotechebrescia.it%252Ffa7cd1d10ea668%26relation%3Dparent.parent&amp;container_width=0&amp;href=http%3A%2F%2Fwww.discotechebrescia.it%2Flocale%2Fdisco-restaurant-al-convento-lonato&amp;locale=it_IT&amp;sdk=joey&amp;width=button_count" style="border: none; visibility: visible; width: 0px; height: 0px;"></iframe></span></div></div>
        </div>
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
          <?php echo $locale["description"]; ?>
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

          <p class="sheet past_events" id="events">
            <a href="{{Link|Get|EVENTI_PASSATI|<?php echo $locale["seo_url"]; ?>}}">
              <i class="fa fa-calendar"></i>
              Archivio eventi e serate del <?php echo $locale["title"]; ?>
            </a>
          </p>
        </div>
      </div>
      
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
              <div>
                Roberto
                320.05.66.704 oppure <a title="invia una email a Pubbliche relazioni e responsabile commerciale Roberto Sottini" class="email" href="mailto:r.sottini@discotechebrescia.it">r.sottini@discotechebrescia.it</a>
              </div>
              <div>
                Alessandro "minimale"
                349.46.25.654 oppure <a title="invia una email a Pubbliche relazioni discoteche brescia Alessandro &quot;minimale&quot; Gavazzi" class="email" href="mailto:a.gavazzi@discotechebrescia.it">a.gavazzi@discotechebrescia.it</a>
              </div>
            </div>
          </div>
        </div>
      </section>
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

      <?php if (count($map) == 1) { ?>
      <section class="sheet" id="maps">
        <h3 class="toggle">
          dove siamo
          <i class="fa fa-angle-down"></i>
        </h3>
        <div class="contentToggle">
          <p><?php echo $map[0]["address"]; ?></p>
          <div class="show-for-medium-up">
            <div class="geoMapWrap">
              <iframe width="100%" height="400" id="gmap_canvas" src="https://maps.google.com/maps?q=<?php echo urlencode($map[0]["address"]); ?>&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>
            </div>
          </div>
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
          <li class="events">
            <a href="#events" title="Eventi" class="">
              <i class="fa fa-calendar fa-fw"></i>
              Eventi
            </a>
          </li>
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
          <?php if (count($map) == 1) { ?>
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
