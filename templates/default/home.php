<?php include("partials/header.php"); ?>

      <section>
        <div class="row spaceB spaceT">
          <div class="small-12 large-9 columns main">
            <h1 class="summary boxed spaceB">{{h1}}</h1>
            <div class="mainbanner pHomeSlides">
              <?php foreach ($homeslides as $slide) { 
                //$imgurl = $App->getImgCDN("home_slides", $slide["id"], "images", "front_large", $slide["image_file_name"]);
                $imgurl = $App->img("home_slides", $slide["id"], 994, 590, $slide["image_file_name"]);
                ?>
                <div>
                  <a class="slide" data-galabel="Banner_home_slider" data-gavalue="Bar Colibrì" href="<?php echo $slide["url"]; ?>">
                    <img 
                      alt="<?php echo $slide["title"]; ?>" 
                      data-interchange="
                        [<?php echo $imgurl; ?>, (default)], 
                        [<?php echo $imgurl; ?>, (small)], 
                        [<?php echo $imgurl; ?>, (medium)], 
                        [<?php echo $imgurl; ?>, (large)]
                      " 
                      src="<?php echo $imgurl; ?>" 
                      title="<?php echo $slide["title"]; ?>" 
                    />
                    <noscript>
                      <img alt="<?php echo $slide["title"]; ?>" src="<?php echo $imgurl; ?>" title="<?php echo $slide["title"]; ?>" />
                    </noscript>
                  </a>
                  <div class="caption">
                    <a title="<?php echo $slide["title"]; ?>" class="link" href="<?php echo $slide["url"]; ?>"><?php echo $slide["title"]; ?></a>
                  <p class="show-for-large-up description"><?php echo $slide["description"]; ?></p>
                  </div>
                </div>
              <?php } ?>
            </div>
            <article class="home_description">
              <div class="boxed">
                {{description}}
              </div>
            </article>
          </div>
          <div class="small-12 large-3 columns side_right">
            <?php include("partials/calendar.php"); ?>

            <div class="show-for-large-up spaceT">
              <div class="banner">
                <a title="<?php echo $banner_dx["title"]; ?>" href="<?php echo $banner_dx["url"]; ?>">
                  <img 
                    alt="<?php echo $banner_dx["title"]; ?>" 
                    title="<?php echo $banner_dx["title"]; ?>" 
                    data-gavalue="<?php echo $banner_dx["title"]; ?>" 
                    data-galabel="Banner_DX" 
                    src="<?php echo $App->img("banners", $banner_dx["id"], 311, "H", $banner_dx["image_file_name"]); ?>" />
                </a>
              </div>
            </div>
          </div>
        </div>
      </section>
      
      <section class="pShowroomEve">
        <div class="row">
          <div class="small-12 columns">
            <h2 class="summary boxed">{{h2}}</h2>
          </div>
        </div>
        <div class="row big">
          <?php foreach ($hevents_big as $hevent) {
            $imgurl = $App->img("events", $hevent["id"], 311, 175, $hevent["image_file_name"]);
            ?>
            <div class="columns h-event vevent small-12 medium-6 large-3">
              <div class="component">
                <a data-galabel="Eventi_speciali_HP" data-gavalue="<?php echo $hevent["title"]; ?>" href="{{Link|Get|EVENTO|<?php echo $hevent["seo_url"]; ?>}}" title="<?php echo $hevent["title"]; ?>">
                  <img 
                    alt="<?php echo $hevent["title"]; ?>" 
                    data-interchange="
                      [<?php echo $imgurl; ?>, (default)], 
                      [<?php echo $imgurl; ?>, (small)], 
                      [<?php echo $imgurl; ?>, (medium)], 
                      [<?php echo $imgurl; ?>, (large)]" 
                      src="<?php echo $imgurl; ?>" title="<?php echo $hevent["title"]; ?>" 
                  />
                  <noscript>
                    <img alt="<?php echo $hevent["title"]; ?>" src="<?php $imgurl; ?>" title="<?php echo $hevent["title"]; ?>" />
                  </noscript>
                </a>
                <div class="desc">
                  <span><?php echo $hevent["title_date"]; ?></span>
                  <a title="<?php echo $hevent["title"]; ?>" class="p-name summary" data-gavalue="<?php echo $hevent["title"]; ?>" data-galabel="Eventi_speciali_HP" href="<?php echo $hevent["url"]; ?>"><?php echo $hevent["title"]; ?></a>
                  <time class="dt-start dtstart" datetime="<?php echo $hevent["time_from"]; ?>" title="<?php echo $hevent["time_from"]; ?>"><?php echo $hevent["time_from"]; ?></time>
                  <time class="dt-end dtend" datetime="<?php echo $hevent["time_to"]; ?>" title="<?php echo $hevent["time_to"]; ?>"><?php echo $hevent["time_to"]; ?></time>
                  <span class="location p-location" title="<?php echo $hevent["locations_title"]; ?>"><?php echo $hevent["locations_title"]; ?></span>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="row small">
          <?php foreach ($hevents_small as $hevent) {
            $imgurl = $App->img("events", $hevent["id"], 217, 124, $hevent["image_file_name"]);
            ?>
            <div class="columns h-event vevent small-12 medium-6 large-6">
              <div class="component">
                <div class="row collapse">
                  <div class="hide-for-small medium-3 large-4 columns">
                    <a data-galabel="Eventi_speciali_HP" data-gavalue="<?php echo $hevent["title"]; ?>" href="{{Link|Get|EVENTO|<?php echo $hevent["seo_url"]; ?>}}" title="<?php echo $hevent["title"]; ?>">
                      <img 
                        alt="<?php echo $hevent["title"]; ?>" 
                        data-interchange="
                          [<?php echo $imgurl; ?>, (default)], 
                          [<?php echo $imgurl; ?>, (small)], 
                          [<?php echo $imgurl; ?>, (medium)], 
                          [<?php echo $imgurl; ?>, (large)]" 
                          src="<?php echo $imgurl; ?>" title="<?php echo $hevent["title"]; ?>" 
                      />
                      <noscript>
                        <img alt="<?php echo $hevent["title"]; ?>" src="<?php echo $hevent["image"]; ?>" title="<?php echo $hevent["title"]; ?>" />
                      </noscript>
                    </a>
                  </div>
                  <div class="small-12 medium-9 large-8 columns">
                    <div class="desc">
                      <span><?php echo $hevent["title_date"]; ?></span>
                      <a title="<?php echo $hevent["title"]; ?>" class="p-name summary" data-gavalue="<?php echo $hevent["title"]; ?>" data-galabel="Eventi_speciali_HP" href="<?php echo $hevent["url"]; ?>"><?php echo $hevent["title"]; ?></a>
                      <time class="dt-start dtstart" datetime="<?php echo $hevent["time_from"]; ?>" title="<?php echo $hevent["time_from"]; ?>"><?php echo $hevent["time_from"]; ?></time>
                      <time class="dt-end dtend" datetime="<?php echo $hevent["time_to"]; ?>" title="<?php echo $hevent["time_to"]; ?>"><?php echo $hevent["time_to"]; ?></time>
                      <span class="location p-location" title="<?php echo $hevent["locations_title"]; ?>"><?php echo $hevent["locations_title"]; ?></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
      </section>
      
      <section>
        <div class="row spaceT">
          <div class="small-12 columns">
            <h2 class="summary boxed">{{h2_2}}</h2>
          </div>
        </div>
        <div class="row" id="masonry">
          <div class="grid-sizer"></div>
            <?php foreach ($hboxes as $box) { ?>
              <?php if ($box["size"] == 1) { ?>
                <div class="masonrybox columns small-12 medium-6 large-3">
              <?php } ?>
              <?php if ($box["size"] == 2) { ?>
                <div class="masonrybox columns small-12 medium-12 large-6">
              <?php } ?>
                <?php 
                  switch ($box["type"]) {
                    case "banner":
                      ?>
                      <div class="banner">
                        <a title="<?php echo $box["titlelink"]; ?>" href="<?php echo $box["url"]; ?>">
                          <img alt="<?php echo $box["titlelink"]; ?>" title="<?php echo $box["titlelink"]; ?>" data-gavalue="<?php echo $box["titlelink"]; ?>" data-galabel="Banner_home_boxes" src="<?php echo $box["image"]; ?>" />
                        </a>
                      </div>
                      <?php
                      break;
                    case "list article yellow":
                      ?>
                      <div class="list article yellow">
                        <div class="description">
                          <?php if ($box["title"] != "") { ?>
                          <p><span class="summary"><?php echo $box["title"]; ?></span></p>
                          <?php } ?>
                          <?php echo $box["description"]; ?>
                        </div>
                      </div>
                      <?php
                      break;
                    case "list article white":
                      ?>
                      <div class="list article white">
                        <a title="<?php echo $box["title"]; ?>" href="<?php echo $box["link"]; ?>">
                          <img alt="<?php echo $box["title"]; ?>" 
                                data-interchange="
                                  [<?php echo $box["image"]; ?>, (default)], 
                                  [<?php echo $box["image"]; ?>, (small)], 
                                  [<?php echo $box["image"]; ?>, (medium)], 
                                  [<?php echo $box["image"]; ?>, (large)]" 
                                src="<?php echo $box["image"]; ?>" title="<?php echo $box["title"]; ?>" 
                          />
                          <noscript>
                            <img alt="<?php echo $box["title"]; ?>" src="<?php echo $box["image"]; ?>" title="<?php echo $box["title"]; ?>" />
                          </noscript>
                        </a>
                        <?php if ($box["title"] != "") { ?>
                        <a class="summary" href="<?php echo $box["link"]; ?>" title="<?php echo $box["title"]; ?>">
                          <?php echo $box["title"]; ?>
                          <span>
                          <i class="fa fa-angle-right"></i>
                          </span>
                        </a>
                        <?php } ?>
                        <?php if ($box["description"] != "") { ?>
                          <div class="description"><?php echo $box["description"]; ?></div>
                        <?php } ?>
                      </div>
                      <?php
                      break;
                    case "list dark":
                    case "list":
                      ?>
                      <div class="<?php echo $box["type"]; ?>">
                        <span class="summary"><?php echo $box["title"]; ?></span>
                        <p class="description"><?php echo $box["description"]; ?></p> 
                        <ul>
                          <?php foreach ($box["children"] as $boxitem) { ?>
                            <li>
                              <a title="<?php echo $boxitem["titlelink"]; ?>" href="<?php echo $boxitem["url"]; ?>"><?php echo $boxitem["label"]; ?>
                                <span> (<?php echo $boxitem["category"]; ?>)</span>
                              </a>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                      <?php
                      break;
                    case "list article dark":
                      ?>
                      <div class="list article dark">
                        <div class="description"><?php echo $box["description"]; ?></div>
                      </div>
                      <?php
                      break;
                    case "boxed":
                      ?>
                      <div class="boxed">
                        <h2 class="minisummary">
                        Discoteche, locali, ristoranti, eventi e molto altro su Discotechebrescia.it
                        </h2>
                        <div class="description">
                        <p style="text-align: justify;">
                          Su discotechebrescia.it potete trovare le schede dettagliate dei migliori bar dove bere un <em>aperitivo </em>e fare <em>happy hour</em>, i ristoranti dove saziarvi e <em>cenare con musica</em> (dalle pizzerie ai <a href="{{Link|Get|CATEGORIA|ristoranti-e-locali-etnici-a-brescia}}" onclick="_gaq.push(['_trackEvent', 'Box_home', 'click', 'Ristoranti etnici']);" title="Ristoranti etnici e ristoranti Giapponesi a Brescia">ristoranti etnici e giapponesi</a> ), i pub, le birrerie e i più gettonati locali notturni , le discoteche di Brescia e le <strong> <a href="{{Link|Get|CATEGORIA|discoteche-lago-di-garda}}" onclick="_gaq.push(['_trackEvent', 'Box_home', 'click', 'Discoteche lago di garda']);" title="Discoteche lago di garda">discoteche del lago di Garda</a></strong> come la&nbsp;discoteca <strong> <a href="{{Link|Get|CATEGORIA|coco-beach-lonato-discoteca}}" onclick="_gaq.push(['_trackEvent', 'Box_home', 'click', 'Coco Beach']);" title="Discoteca coco beach lonato">Coco Beach</a></strong> dove scatenarvi sulla pista da ballo e far mattino sia a Brescia che sul <em>lago di Garda</em>.<br>
                          <br>
                          Inoltre, per facilitare il vostro divertimento, in ogni scheda troverete i contatti o il form per mettervi in lista nelle migliori <strong>discoteche</strong> e <strong>locali</strong>! Vi ricordiamo anche che lo staff di DiscotecheBrescia.it è sempre a vostra completa disposizione per organizzare le vostre feste e le vostre serate a Brescia e sul <em>lago di Garda</em>.<br>
                          <br>
                          Scopri la nuova sezione <a href="{{Link|Get|FESTIVITA}}" onclick="_gaq.push(['_trackEvent', 'Box_home', 'click', 'Festivita']);" title="Calendario eventi festività Brescia">calendario eventi festività</a>.</p>

                        </div>
                      </div>
                      <?php
                      break;
                    case "google-badge":
                      ?>
                      <div id="google-badge">
                      <div class="g-page" data-href="http://plus.google.com/+discotechebrescia" data-width="200"></div>
                      <script type="text/javascript">
                      window.___gcfg = {lang: 'it'};
                          document.getElementsByClassName('g-page')[0].setAttribute('data-width', document.getElementById('google-badge').clientWidth);
                            (function () {
                              var po = document.createElement('script');
                              po.type = 'text/javascript';
                              po.async = true;
                              po.src = 'https://apis.google.com/js/platform.js';
                              var s = document.getElementsByTagName('script')[0];
                              s.parentNode.insertBefore(po, s);
                          })();
                      </script>
                      </div>
                      <?php
                      break;
                  }
                ?>
              </div>
            <?php } ?>  
        </div>
      </section>

<?php include("partials/footer.php"); ?>
