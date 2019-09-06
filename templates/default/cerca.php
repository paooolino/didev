<?php include("partials/header.php"); ?>

<div class="row">
  <div class="small-12 large-9 columns">
    <nav aria-label="breadcrumbs" class="breadcrumbs breadcrumb" role="menubar">
      <li role="menuitem">
        <a href="{{Link|Get|HOME}}" title="home discoteche, locali, ristoranti ed eventi a Brescia">
          <i class="fa fa-home"></i>
          home
        </a>
      </li>
    </nav>
    <div class="boxed">
      <section class="generic_page sheet">
        <article>
          <h1 class="mainsummary">
            Risultati della ricerca
          </h1>
        </article>
      </section>
    
      <section class="collection">
        <ul class="searchResults">
          <?php foreach ($results["locali"] as $locale) { ?>
            <li>locale <a title="<?php echo $locale["title"]; ?>" href="{{Link|Get|LOCALE|<?php echo $locale["seo_url"]; ?>}}"><?php echo $locale["title"]; ?></a></li>
          <?php } ?>
          <?php foreach ($results["eventi"] as $evento) { ?>
            <li>evento <a title="<?php echo $evento["title"]; ?>" href="{{Link|Get|EVENTO|<?php echo $evento["seo_url"]; ?>}}"><?php echo $evento["title"]; ?></a></li>
          <?php } ?>
        </ul>
      </section>
    
    </div>
  </div>
  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <?php include("partials/calendar.php"); ?>

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
