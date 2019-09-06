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
      <li class="current" role="menuitem">
        <a title="<?php echo $cat["title"]; ?>" href="/locali/<?php echo $cat["seo_url"]; ?>"><?php echo $cat["title"]; ?></a>
      </li>
    </nav>
    <article class="boxed spaceB notehead">
      <?php
      if ($cat["image_file_name"] != "") {
        $imgurl = $App->img("typos", $cat["id"], "W", 57, $cat["image_file_name"]);
        ?>
        <img alt="<?php echo $cat["title"]; ?>" title="<?php echo $cat["title"]; ?>" class="thumbcat" 
          src="<?php echo $imgurl; ?>">
      <?php } ?>
      <hgroup>
        <h1 class="mainsummary"><?php echo $cat["title"]; ?></h1>
        <h2 class="summary"><?php echo $cat["seo_subtitle"]; ?></h2>
      </hgroup>
      <div class="description">
        <?php echo $cat["description"]; ?>
      </div>
    </article>
  </div>
</section>
<div class="row collection">
  <section class="small-12 large-9 columns">
    <section id="filter">
      <nav class="top-bar" data-topbar="" role="navigation">
        <ul class="title-area info_panel">
          <li class="name">
            <p>
              <span class="counter"><?php echo $ntot; ?></span>
              <strong><?php echo $cat["logic_title"]; ?></strong>
               a 
              <?php echo $currentCity; ?>
            </p>
          </li>
        </ul>
        <?php if (count($z1) > 0 || count($z2) > 0) { ?>
        <section class="top-bar-section zone_panel">
          <ul class="right">
            <li class="has-dropdown not-click">
              <a href="#">Filtra per zona
                <i class="fa fa-angle-down"></i>
              </a>
              <ul class="dropdown">
                <li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li>
                <li class="parent-link show-for-small">
                  <a class="parent-link js-generated" href="#">Filtra per zona
                    <i class="fa fa-angle-down"></i>
                  </a>
                </li>
                <?php 
                if (count($z1) > 0) { 
                  ?>
                  <li>
                    <span>Aree geografiche</span>
                  </li>
                  <?php
                  foreach ($z1 as $z) { 
                    ?>
                    <li class="zone">
                      <a title="<?php echo $z["seo_title"]; ?>" href="{{Link|Get|CATEGORIA_ZONA|<?php echo $cat["seo_url"]; ?>|<?php echo $z["seo_url"]; ?>}}">
                        <?php echo $App->_zName($z); ?>
                      </a>
                    </li>
                    <?php
                  }
                }
                ?>
                <?php 
                if (count($z2) > 0) { 
                  ?>
                  <li>
                    <span>Comuni</span>
                  </li>
                  <?php
                  foreach ($z2 as $z) { 
                    ?>
                    <li class="zone">
                      <a title="<?php echo $z["seo_title"]; ?>" href="{{Link|Get|CATEGORIA_ZONA|<?php echo $cat["seo_url"]; ?>|<?php echo $z["seo_url"]; ?>}}">
                        <?php echo $App->_zName($z); ?>
                      </a>
                    </li>
                    <?php
                  }
                }
                ?>
              </ul>
            </li>
          </ul>
        </section>
        <?php } ?>
      </nav>
      <ul class="paginate_alpha show-for-medium-up">
        <li>
          <span>Filtra per lettera</span>
        </li>
        <?php 
          $chars = str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZ");
          foreach ($chars as $letter) { ?>
          <?php if (stristr($empty_letters, $letter) !== false) { ?>
            <li>
              <span><?php echo $letter; ?></span>
            </li>
          <?php } else { ?>
            <li>
              <a class="<?php echo isset($current_letter) && $current_letter == $letter ? "active" : ""; ?>" href="{{Link|Get|CATEGORIA_LOCALI_LETTERA|<?php echo $slug_categoria; ?>|<?php echo $letter; ?>}}" rel="nofollow" title="che iniziano con la lettera <?php echo $letter; ?>">
                <?php echo $letter; ?>
              </a>
            </li>
          <?php } ?>
        <?php } ?>
      </ul>
    </section>
    <div 
      class="items <?php if (isset($enable_ajax) && $enable_ajax == true) { ?>ajaxLoadItems<?php } ?>" 
      data-slug="{{slug_categoria}}" data-page="1">
      <?php 
      foreach ($list as $item) { 
        echo $App->printLocaleItem($item);
      }
      ?>
    </div>
    
    <?php if (false) { ?>
    <div class="paginate_number">
      <ul aria-label="Pagination" class="pagination" role="menubar">

        <?php
        $n_pages = ceil($ntot / 10);
        for ($p = 0; $p < $n_pages; $p++) {
          $route = $p == 0 ? "CATEGORIA_LOCALI" : "CATEGORIA_LOCALI_PAG";
          $appendpag = $p == 0 ? "" : "|" . ($p + 1);
          $class = ($p + 1) == $current_page ? "current" : ""; 
          ?>
          <li class="<?php echo $class; ?>">
            <a href="{{Link|Get|<?php echo $route; ?>|<?php echo $slug_categoria . $appendpag; ?>}}"><?php echo $p + 1; ?></a>
          </li>
          <?php
        }
        ?>

      </ul>
      <p class="empty"></p>
    </div>
    <?php } ?>
  </section>
  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <?php if (isset($no_calendar) && $no_calendar == true) { ; } else { ?>
    <?php include("partials/calendar.php"); ?>
    <?php } ?>
    
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
