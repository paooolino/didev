<?php include("partials/header.php"); ?>
<div class="row">
  <section class="small-12 large-9 columns">
    <section class="row" id="content">
      <div class="small-12 columns">
        <nav aria-label="breadcrumbs" class="breadcrumbs breadcrumb" role="menubar">
          <li role="menuitem">
            <a href="{{Link|Get|HOME}}" title="home discoteche, locali, ristoranti ed eventi a Brescia">
              <i class="fa fa-home"></i>
              home
            </a>
          </li>
          <li class="current" role="menuitem">
            <a title="Eventi" href="{{Link|Get|EVENTI_DATA|<?php echo date("Y"); ?>|<?php echo date("n"); ?>|<?php echo date("j"); ?>}}">Eventi</a>
          </li>
        </nav>
        <article class="boxed spaceB notehead">
          <hgroup>
            <h1 class="mainsummary">{{seoTitle}}</h1>
            <h2 class="summary">{{h2}}</h2>
          </hgroup>
        </article>
      </div>
    </section>
    <section class="info_choice_wrapper" id="filter">
      <nav class="top-bar expanded" data-topbar="" role="navigation">
        <ul class="title-area info_panel">
          <li class="name">
            <p>
            <span class="counter">{{n_events}}</span>
            Eventi
             a 
            Brescia
            </p>
          </li>
        </ul>
      </nav>
    </section>
    <section class="collection">
      <div class="row">
        <?php foreach ($events as $event) { ?>
          <?php 
          $event_image = "";
          if (is_null($event["recurrent_id"]) && $event["image_file_name"] != "") {
            $event_image = $App->getImgCDN(
              "events", $event["id"], "images", "preview_large",
              $event["image_file_name"]
            );
          } else {
            if ($event["recurrent_image"] != "") {
              $event_image = $App->getImgCDN(
                "recurrents", $event["recurrent_id"], "images", "preview_small",
                $event["recurrent_image"]
              );
            }
          }
          ?>
          <div class="small-12 medium-12 large-12 columns event_preview h-event vevent" id="hcalendar-event<?php echo $event["id"]; ?>">
            <div class="component">
              <div class="row collapse">
                <?php if ($event_image != "")  { ?>
                  <div class="thumbnail small-12 medium-3 large-4 columns">
                    <a title="<?php echo $event["title"]; ?>" href="{{Link|Get|EVENTO|<?php echo $event["seo_url"]; ?>}}">
                      
                      <img 
                        alt="<?php echo $event["title"]; ?>" 
                        data-interchange="
                          [<?php echo $event_image ?>, (default)], 
                          [<?php echo $event_image; ?>, (medium)], 
                          [<?php echo $event_image; ?>, (large)]
                        " 
                        src="<?php echo $event_image; ?>" 
                        title="<?php echo $event["title"]; ?>"
                      >
                      <noscript>
                        <img 
                          alt="<?php echo $event["title"]; ?>" 
                          src="<?php echo $event_image; ?>" 
                          title="<?php echo $event["title"]; ?>" />
                      </noscript>
                    </a>
                  </div>
                <?php } ?>
                <?php if ($event_image != "")  { ?>
                  <div class="small-12 medium-9 large-8 columns texts">
                <?php } else { ?>
                  <div class="small-12 medium-12 large-12 columns texts">
                <?php } ?>
                    <div class="desc">
                      <span class="info">
                      <time class="dt-start dtstart" datetime="2017-11-19T22:00:00+01:00" title="2017-11-19T22:00:00+01:00">2017-11-19T22:00:00+01:00</time>
                      <time class="dt-end dtend" datetime="2017-11-19T23:59:00+01:00" title="2017-11-19T23:59:00+01:00">2017-11-19T23:59:00+01:00</time>
                      <?php echo $event["title_date"]; ?>
                       - 
                      <a title="<?php echo $event["locations_title"]; ?>" href="/locale/<?php echo $event["locations_seo_url"]; ?>"><?php echo $event["locations_title"]; ?></a>
                      <span class="location p-location" title="<?php echo $event["locations_address_city"]; ?>, <?php echo $event["locations_address_province"]; ?>"><?php echo $event["locations_address_city"]; ?></span>
                      </span>
                      <a title="<?php echo $event["title"]; ?>" class="p-name summary" href="{{Link|Get|EVENTO|<?php echo $event["seo_url"]; ?>}}"><?php echo $event["title"]; ?></a>
                      <p class="description p-summary description"><?php echo $event["seo_description"]; ?></p>
                    </div>
                  </div>
                
              </div>
            </div>
          </div>
        <?php } ?>
      </div>
    </section>

  </section>
  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <div class="show-for-large-up spaceT">
      <aside class="adsBanner responsive visible-for-large-up">
        <ins class="adsbygoogle" data-ad-client="ca-pub-6371727345571989" data-ad-format="vertical" data-ad-slot="7697964545" data-adsbygoogle-status="done" style="height: 600px;"><ins id="aswift_0_expand" style="display:inline-table;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><ins id="aswift_0_anchor" style="display:block;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><iframe width="300" height="600" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allowfullscreen="true" onload="var i=this.id,s=window.google_iframe_oncopy,H=s&amp;&amp;s.handlers,h=H&amp;&amp;H[i],w=this.contentWindow,d;try{d=w.document}catch(e){}if(h&amp;&amp;d&amp;&amp;(!d.body||!d.body.firstChild)){if(h.call){setTimeout(h,0)}else if(h.match){try{h=s.upd(h,i)}catch(e){}w.location.replace(h)}}" id="aswift_0" name="aswift_0" style="left:0;position:absolute;top:0;width:300px;height:600px;"></iframe></ins></ins></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </aside>
    </div>
  </aside>

</div>

<?php include("partials/footer.php"); ?>
