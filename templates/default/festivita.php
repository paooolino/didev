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
          <?php if (isset($breadcrumbitems)) { ?>
          <?php foreach ($breadcrumbitems as $bc) { ?>
            <li role="menuitem">
              <a title="<?php echo $bc["title"]; ?>" href="<?php echo $bc["link"]; ?>"><?php echo $bc["label"]; ?></a>
            </li>
          <?php } ?>
          <?php } ?>
          <li class="current" role="menuitem">
            <a title="{{title}}" href="{{Link|EVENTI}}">{{title}}</a>
          </li>
        </nav>
        <article class="boxed spaceB notehead">
          <hgroup>
            <h1 class="mainsummary">{{title}}</h1>
            <h2 class="summary">{{h2}}</h2>
            <div class="description">{{description}}</div>
          </hgroup>
        </article>
      </div>
    </section>

    <section class="collection">
      <div class="row">
        <?php foreach ($events as $event) { ?>
          <?php 
          $event_image = $App->img(
            "holidays", $event["id"], 315, 215,
            $event["image_file_name"]
          );
          ?>
          <div class="small-12 medium-12 large-12 columns holiday_preview">
            <div class="component">
              <div class="row collapse">
                <div class="thumbnail small-12 medium-3 large-4 columns">
                  <a title="<?php echo $event["title"]; ?>" href="{{Link|Get|FESTIVITA|<?php echo $event["seo_url"]; ?>}}">
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
                <div class="small-12 medium-9 large-8 columns texts">
                  <div class="desc">
                    <a title="<?php echo $event["title"]; ?>" class="p-name summary" href="{{Link|Get|FESTIVITA|<?php echo $event["seo_url"]; ?>}}"><?php echo $event["title"]; ?></a>
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
