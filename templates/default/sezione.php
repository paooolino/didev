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
      <li class="current" role="menuitem">
        <a title="<?php echo $section["title"]; ?>" href="{{Link|Get|SEZIONE|<?php echo $section["seo_url"]; ?>}}"><?php echo $section["title"]; ?></a>
      </li>
    </nav>
    <section class="generic_page sheet boxed">
      <article>
        <h1 class="mainsummary">
          <?php echo $section["title"]; ?>
        </h1>
        <div class="description">
          <?php echo $section["description"]; ?>
        </div>
      </article>
      <?php if ($section["fixed_name"] != "") { include("partials/fixed/" . $section["fixed_name"] . ".php"); } ?>
    </section>
  </div>
  <aside class="show-for-large-up large-3 columns spaceT side_right">
    <?php include("partials/calendar.php"); ?>

    <div class="show-for-large-up spaceT">
      <aside class="adsBanner responsive visible-for-large-up">
        <ins class="adsbygoogle" data-ad-client="ca-pub-6371727345571989" data-ad-format="vertical" data-ad-slot="7697964545"></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </aside>
    </div>
  </aside>
</div>

<?php include("partials/footer.php"); ?>
