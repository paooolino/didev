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
        <a title="Richiesta inviata" href="{{Link|Get|SEND_OK}}">Richiesta inviata</a>
      </li>
    </nav>
    <section class="generic_page sheet boxed">
      <article>
        <h1 class="mainsummary">
          Grazie per averci contattato
        </h1>
        <div class="description">
          <p></p>
          <p>Richiesta contatto inviata con successo!<br>
          Un nostro operatore ti ricontatterà al più presto.</p>
          <p><a href="{{Link|Get|HOME}}">Torna alla home page</a></p>
        </div>
      </article>
    </section>
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
