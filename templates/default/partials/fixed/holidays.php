<section class="collection">
  <?php foreach($holidays as $hol) { 
    $imgurl = $App->getImgCDN("holidays", $hol["holiday_id"], "images", "preview_small", $hol["image_file_name"]);
  ?>
  <div class="row">
    <div class="small-12 medium-12 large-12 columns holiday_preview">
      <div class="component">
        <div class="row collapse">
          <div class="thumbnail small-12 medium-3 large-4 columns">
            <a title="<?php echo $hol["title"]; ?>" href="{{Link|Get|FESTIVITA|<?php echo $hol["seo_url"]; ?>}}">
              <img alt="<?php echo $hol["title"]; ?>" 
                data-interchange="
                  [<?php echo $imgurl; ?>, (default)], 
                  [<?php echo $imgurl; ?>, (medium)], 
                  [<?php echo $imgurl; ?>, (large)]
                " 
                src="<?php echo $imgurl; ?>" title="<?php echo $hol["title"]; ?>" data-uuid="interchange-jdru8l160">
              <noscript>
                <img alt="<?php echo $hol["title"]; ?>" src="<?php echo $imgurl; ?>" title="<?php echo $hol["title"]; ?>" />
              </noscript>
            </a>
          </div>
          <div class="small-12 medium-9 large-8 columns texts">
            <div class="desc">
              <a title="<?php echo $hol["title"]; ?>" class="p-name summary" href="{{Link|Get|FESTIVITA|<?php echo $hol["seo_url"]; ?>}}"><?php echo $hol["title"]; ?></a>
              <p class="description p-summary description"><?php echo $hol["seo_description"]; ?></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <?php } ?>
</section>