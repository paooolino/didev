<?php include("partials/header.php"); ?>
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

                <?php if (count($map) == 1) { ?><a href="#map">vedi mappa</a><?php } ?>
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
          <div id="social_tab">
            <div class="google"><div id="___plusone_0" style="text-indent: 0px; margin: 0px; padding: 0px; background: transparent; border-style: none; float: none; line-height: normal; font-size: 1px; vertical-align: baseline; display: inline-block; width: 32px; height: 20px;"><iframe ng-non-bindable="" frameborder="0" hspace="0" marginheight="0" marginwidth="0" scrolling="no" style="position: static; top: 0px; width: 32px; margin: 0px; border-style: none; left: 0px; visibility: visible; height: 20px;" tabindex="0" vspace="0" width="100%" id="I0_1511127738275" name="I0_1511127738275" src="https://apis.google.com/u/0/se/0/_/+1/fastbutton?usegapi=1&amp;size=medium&amp;annotation=none&amp;origin=http%3A%2F%2Fwww.discotechebrescia.it&amp;url=http%3A%2F%2Fwww.discotechebrescia.it%2Fevento%2Fdomenica-discoteca-qi-clubbing-erbusco-brescia&amp;gsrc=3p&amp;ic=1&amp;jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.it.lswS_gzJLBM.O%2Fm%3D__features__%2Fam%3DAQ%2Frt%3Dj%2Fd%3D1%2Frs%3DAGLTcCMOBmQOV-D9-PWQPctIHUnbFik9QQ#_methods=onPlusOne%2C_ready%2C_close%2C_open%2C_resizeMe%2C_renderstart%2Concircled%2Cdrefresh%2Cerefresh%2Conload&amp;id=I0_1511127738275&amp;_gfid=I0_1511127738275&amp;parent=http%3A%2F%2Fwww.discotechebrescia.it&amp;pfname=&amp;rpctoken=27611064" data-gapiattached="true" title="G+"></iframe></div></div><div class="twitter"><iframe id="twitter-widget-0" scrolling="no" frameborder="0" allowtransparency="true" class="twitter-share-button twitter-share-button-rendered twitter-tweet-button" title="Twitter Tweet Button" src="http://platform.twitter.com/widgets/tweet_button.21783de9dc99fcf78a11eef96926d932.it.html#dnt=false&amp;id=twitter-widget-0&amp;lang=it&amp;original_referer=http%3A%2F%2Fwww.discotechebrescia.it%2Fevento%2Fdomenica-discoteca-qi-clubbing-erbusco-brescia&amp;related=condividi%20su%20twitter&amp;size=m&amp;text=La%20domenica%20Notte%20della%20discoteca%20QI%20Clubbing%20di%20Erbusco%2C%20Brescia.&amp;time=1511127738537&amp;type=share&amp;url=http%3A%2F%2Fwww.discotechebrescia.it%2Fevento%2Fdomenica-discoteca-qi-clubbing-erbusco-brescia&amp;via=DiscotecheBS" style="position: static; visibility: visible; width: 60px; height: 20px;"></iframe><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script></div><div class="facebook"><div class="fb-like fb_iframe_widget" data-width="button_count" fb-xfbml-state="rendered" fb-iframe-plugin-query="app_id=&amp;container_width=0&amp;href=http%3A%2F%2Fwww.discotechebrescia.it%2Fevento%2Fdomenica-discoteca-qi-clubbing-erbusco-brescia&amp;locale=it_IT&amp;sdk=joey&amp;width=button_count"><span style="vertical-align: top; width: 0px; height: 0px; overflow: hidden;"><iframe name="fb91bb8fafbcf8" height="1000px" frameborder="0" allowtransparency="true" allowfullscreen="true" scrolling="no" title="fb:like Facebook Social Plugin" src="https://www.facebook.com/v2.4/plugins/like.php?app_id=&amp;channel=http%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter%2Fr%2FlY4eZXm_YWu.js%3Fversion%3D42%23cb%3Df37b450a117d7d8%26domain%3Dwww.discotechebrescia.it%26origin%3Dhttp%253A%252F%252Fwww.discotechebrescia.it%252Ff3f2d8af032a9f8%26relation%3Dparent.parent&amp;container_width=0&amp;href=http%3A%2F%2Fwww.discotechebrescia.it%2Fevento%2Fdomenica-discoteca-qi-clubbing-erbusco-brescia&amp;locale=it_IT&amp;sdk=joey&amp;width=button_count" style="border: none; visibility: visible; width: 0px; height: 0px;"></iframe></span></div></div>
          </div>
          
          <?php if (count($map) == 1) { ?>
          <section class="sheet" id="map">
            <h3>
              Mappa
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
        <ins class="adsbygoogle" data-ad-client="ca-pub-6371727345571989" data-ad-format="vertical" data-ad-slot="7697964545" data-adsbygoogle-status="done" style="height: 600px;"><ins id="aswift_0_expand" style="display:inline-table;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><ins id="aswift_0_anchor" style="display:block;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><iframe width="300" height="600" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allowfullscreen="true" onload="var i=this.id,s=window.google_iframe_oncopy,H=s&amp;&amp;s.handlers,h=H&amp;&amp;H[i],w=this.contentWindow,d;try{d=w.document}catch(e){}if(h&amp;&amp;d&amp;&amp;(!d.body||!d.body.firstChild)){if(h.call){setTimeout(h,0)}else if(h.match){try{h=s.upd(h,i)}catch(e){}w.location.replace(h)}}" id="aswift_0" name="aswift_0" style="left:0;position:absolute;top:0;width:300px;height:600px;"></iframe></ins></ins></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </aside>
    </div>
  </aside>

</div>

<?php include("partials/footer.php"); ?>
