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
            $imgurl = $App->getImg("events", $hevent["id"], 311, 175, $hevent["image_file_name"]);
            ?>
            <div class="columns h-event vevent small-12 medium-6 large-3">
              <div class="component">
                <a data-galabel="Eventi_speciali_HP" data-gavalue="<?php echo $hevent["title"]; ?>" href="<?php echo $hevent["url"]; ?>" title="<?php echo $hevent["title"]; ?>">
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
                  <time class="dt-start dtstart" datetime="<?php echo $hevent["startdate"]; ?>" title="<?php echo $hevent["startdate"]; ?>"><?php echo $hevent["startdate"]; ?></time>
                  <time class="dt-end dtend" datetime="<?php echo $hevent["enddate"]; ?>" title="<?php echo $hevent["enddate"]; ?>"><?php echo $hevent["enddate"]; ?></time>
                  <span class="location p-location" title="<?php echo $hevent["location"]; ?>"><?php echo $hevent["location"]; ?></span>
                </div>
              </div>
            </div>
          <?php } ?>
        </div>
        <div class="row small">
          <?php foreach ($hevents_small as $hevent) {
            $imgurl = $App->getImg("events", $hevent["id"], 217, 124, $hevent["image_file_name"]);
            ?>
            <div class="columns h-event vevent small-12 medium-6 large-6">
              <div class="component">
                <div class="row collapse">
                  <div class="hide-for-small medium-3 large-4 columns">
                    <a data-galabel="Eventi_speciali_HP" data-gavalue="<?php echo $hevent["title"]; ?>" href="<?php echo $hevent["url"]; ?>" title="<?php echo $hevent["title"]; ?>">
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
                      <time class="dt-start dtstart" datetime="<?php echo $hevent["startdate"]; ?>" title="<?php echo $hevent["startdate"]; ?>"><?php echo $hevent["startdate"]; ?></time>
                      <time class="dt-end dtend" datetime="<?php echo $hevent["enddate"]; ?>" title="<?php echo $hevent["enddate"]; ?>"><?php echo $hevent["enddate"]; ?></time>
                      <span class="location p-location" title="<?php echo $hevent["location"]; ?>"><?php echo $hevent["location"]; ?></span>
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
                          <p><span class="summary"><?php echo $box["title"]; ?></span></p>
                          <p><?php echo $box["description"]; ?></p>
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
                      ?>
                      <div class="list dark">
                        <span class="summary"><?php echo $box["title"]; ?></span>
                        <p class="description"><?php echo $box["description"]; ?></p> 
                        <ul>
                          <?php foreach ($box["children"] as $boxitem) { ?>
                            <a title="<?php echo $boxitem["titlelink"]; ?>" href="<?php echo $boxitem["url"]; ?>"><?php echo $boxitem["label"]; ?>
                            <span> (<?php echo $boxitem["category"]; ?>)</span>
                          <?php } ?>
                        </ul>
                      <?php
                      break;
                    case "list article dark":
                      ?>
                      <div class="list article dark">
                        <div class="description"><?php echo $box["description"]; ?></div>
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
