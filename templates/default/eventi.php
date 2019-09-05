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
            <h1 class="mainsummary"><?php echo isset($mainSummary) ? $mainSummary : $seoTitle; ?></h1>
            <h2 class="summary">{{h2}}</h2>
            <div class="description">{{description}}</div>
          </hgroup>
        </article>
      </div>
    </section>
    <?php if (!isset($disableEventlistHeader)) { ?>
    <section class="info_choice_wrapper" id="filter">
      <nav class="top-bar expanded" data-topbar="" role="navigation">
        <ul class="title-area info_panel">
          <li class="name">
            <p>
            <span class="counter">{{n_events}}</span>
             <?php echo isset($past) && $past == true ? "Archivio eventi" : "Eventi"; ?>
             a 
            <?php echo $currentCity; ?>
            </p>
          </li>
        </ul>
        <?php if (!(isset($past) && $past == true)) { ?>
        <section class="top-bar-section choice">
          <ul class="right">
            <li class="has-dropdown">
              <a href="#">Filtra per categoria</a>
              <ul class="dropdown">
                <?php foreach ($catevents as $cat) { ?>
                <li>
                  <a title="<?php echo $cat["title"]; ?>" href="{{Link|Get|EVENTI_CATEGORIA|<?php echo $cat["seo_url"]; ?>}}"><?php echo $cat["title"]; ?></a>
                </li>
                <?php } ?>
              </ul>
            </li>
          </ul>
        </section>
        <?php } ?>
      </nav>
    </section>
    <?php } ?>
    <section class="row collection <?php if (isset($enable_ajax) && $enable_ajax == true) { ?>ajaxLoadEvents<?php } ?>"
      data-page="1" data-past="<?php echo isset($past) && $past == true ? "true" : "false"; ?>"
      >
      <?php if (count($events) == 0) { ?>
        <div class="small-12 medium-12 large-12 columns">Nessun elemento trovato.</div>
      <?php } ?>
      <?php foreach ($events as $event) { ?>
        <?php echo $App->printEvento($event); ?>
      <?php } ?>
    </section>
    <?php if (false) { ?>
    <div class="paginate_number">
      <ul aria-label="Pagination" class="pagination" role="menubar">

        <?php for ($i = 0; $i < $n_pages; $i++) { 
          if ($i == 0) {
            $url = $Link->Get("EVENTI");
          } else {
            $url = $Link->Get(["EVENTI_PAGINA", ($i+1)]);
          }
          ?>
          
          <li<?php if (($i+1) == $pag) { ?> class="current"<?php } ?>>
            <a href="<?php echo $url; ?>"><?php echo $i+1; ?></a>
          </li>

          <!--
          <li>
            <a rel="next" href="/eventi/2">2</a>
          </li>

          <li class="arrow">
            <a rel="next" href="/eventi/2">Succ. »</a>
          </li>
          -->
        <?php } ?>

      </ul>
      <p class="empty"></p>
    </div>
    <?php } ?>
    
    <?php if (!(isset($disableEventsArchive) && $disableEventsArchive == true)) { ?>
    <div class="boxed promo">
      <p class="archive_events">
        <a class="bt" href="{{archivelink}}">
          <i class="fa fa-calendar"></i>
          <?php echo isset($archivelabel) ? $archivelabel : "vedi eventi passati"; ?>
        </a>
      </p>
      <?php 
      if (isset($description2) && $description2 != "") { 
        echo $description2;
      }
      ?>
    </div>
    <?php } ?>

    <?php if (isset($showaperti) && $showaperti === true) { ?>
    
      <br><br>
      <div class="locations">
        <section class="row">
          <div class="small-12 columns">
          <article class="boxed spaceB notehead">
          <div>
          <h2 class="summary">{{aperti_summary}}</h2>
          <div class="description">discotechebrescia.it vi consiglia comunque di contattare il locale per verificare la reale apertura in quanto può capitare che i gestori dei locali non ci avvisino sempre sulle variazioni dei giorni di apertura</div>
          </div>
          </article>
          </div>
        </section>
        <section>
          <div class="items">

          </div>
        </section>
      </div>    
    <?php } ?>
  
  
  
  
  </section>
  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <?php 
    if (isset($calendar)) {
      include("partials/calendar.php"); 
    }
    ?>
    <div class="show-for-large-up spaceT">
      <aside class="adsBanner responsive visible-for-large-up">
        <ins class="adsbygoogle" data-ad-client="ca-pub-6371727345571989" data-ad-format="vertical" data-ad-slot="7697964545"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </aside>
    </div>
  </aside>

</div>

<?php include("partials/footer.php"); ?>
