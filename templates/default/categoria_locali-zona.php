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
        <a title="<?php echo $cat["title"]; ?>" href="{{Link|Get|CATEGORIA_LOCALI|<?php echo $cat["seo_url"]; ?>}}"><?php echo $cat["title"]; ?></a>
      </li>
      <li class="current" role="menuitem">
        <a title="<?php echo $App->_zTitle($cat, $zona); ?>" href="{{Link|Get|CATEGORIA_ZONA|<?php echo $cat["seo_url"]; ?>|<?php echo $zona["seo_url"]; ?>}}"><?php echo $App->_zName($zona); ?></a>
      </li>
    </nav>
    <article class="boxed spaceB notehead">
      <hgroup>
        <h1 class="mainsummary"><?php echo $App->_zTitle($cat, $zona); ?></h1>
      </hgroup>
      <div class="description">
        <?php echo $App->_zTypo($zona["description"], $cat); ?>
      </div>
    </article>
  </div>
</section>
<div class="row collection">
  <section class="small-12 large-9 columns">
    <div class="items">
      <?php foreach ($list as $item) { 
        $imgurl = $App->img("locations", $item["id"], 248, 224, $item["logo_file_name"], "logos");
        $level_names = [
          "3" => "platinum",
          "2" => "gold",
          "1" => "silver",
          "0" => "free"
        ];
        ?>
        <div class="item <?php echo $level_names[$item["level"]]; ?> row collapse">
          
          <?php if ($item["level"] == 0) { ?>
          <div class="thumbnail columns hide-for-small medium-3 large-2">
          <?php } else { ?>
          <div class="thumbnail columns hide-for-small medium-4 large-3">
          <?php } ?>
          
            <a class="thumb" title="<?php echo $item["title"]; ?>" href="{{Link|Get|LOCALE|<?php echo $item["seo_url"]; ?>}}">
              <img alt="<?php echo $item["title"]; ?>" 
                data-interchange="
                  [<?php echo $imgurl; ?>, (default)], 
                  [<?php echo $imgurl; ?>, (medium)], 
                  [<?php echo $imgurl; ?>, (large)]
                " 
                src="<?php echo $imgurl; ?>" 
                title="<?php echo $item["title"]; ?>" 
                data-uuid="">
              <noscript>
                <img alt="<?php echo $item["title"]; ?>" src="<?php echo $imgurl; ?>" title="<?php echo $item["title"]; ?>" />
              </noscript>
            </a>
          </div>

          <?php if ($item["level"] == 0) { ?>
          <div class="info small-12 medium-9 large-10 columns">
          <?php } else { ?>
          <div class="info small-12 medium-8 large-9 columns">
          <?php } ?>
          
            <div class="desc">
              <a class="summary" title="<?php echo $item["title"]; ?>" href="{{Link|Get|LOCALE|<?php echo $item["seo_url"]; ?>}}">
                <?php echo $item["title"]; ?>
              </a>
              <span class="address"><?php echo $item["title"]; ?> - <?php echo $item["address_way"]; ?>, <?php echo $item["address_number"]; ?> - <?php echo $item["address_city"]; ?>, <?php echo $item["address_zip"]; ?> - (<?php echo $item["address_province"]; ?>), IT</span>
              <p class="description"><?php echo $item["seo_description"]; ?></p>
              <p class="booking">INFO E PRENOTAZIONI: <?php echo $item["phone"]; ?> - <?php echo $item["mobile"]; ?></p>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </section>
  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <?php include("partials/calendar.php"); ?>
    
    <div class="show-for-large-up spaceT">
      <aside class="adsBanner responsive visible-for-large-up">
        <ins class="adsbygoogle" data-ad-client="ca-pub-6371727345571989" data-ad-format="vertical" data-ad-slot="7697964545" data-adsbygoogle-status="done" style="height: 600px;"><ins id="aswift_0_expand" style="display:inline-table;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><ins id="aswift_0_anchor" style="display:block;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><iframe width="300" height="600" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allowfullscreen="true" onload="var i=this.id,s=window.google_iframe_oncopy,H=s&amp;&amp;s.handlers,h=H&amp;&amp;H[i],w=this.contentWindow,d;try{d=w.document}catch(e){}if(h&amp;&amp;d&amp;&amp;(!d.body||!d.body.firstChild)){if(h.call){setTimeout(h,0)}else if(h.match){try{h=s.upd(h,i)}catch(e){}w.location.replace(h)}}" id="aswift_0" name="aswift_0" style="left:0;position:absolute;top:0;width:300px;height:600px;"></iframe></ins></ins></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </aside>
    </div>
  </aside>
</div>

<?php include("partials/footer.php"); ?>
