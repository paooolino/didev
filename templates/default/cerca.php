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
        <ins class="adsbygoogle" data-ad-client="ca-pub-6371727345571989" data-ad-format="vertical" data-ad-slot="7697964545" data-adsbygoogle-status="done" style="height: 600px;"><ins id="aswift_0_expand" style="display:inline-table;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><ins id="aswift_0_anchor" style="display:block;border:none;height:600px;margin:0;padding:0;position:relative;visibility:visible;width:300px;background-color:transparent;"><iframe width="300" height="600" frameborder="0" marginwidth="0" marginheight="0" vspace="0" hspace="0" allowtransparency="true" scrolling="no" allowfullscreen="true" onload="var i=this.id,s=window.google_iframe_oncopy,H=s&amp;&amp;s.handlers,h=H&amp;&amp;H[i],w=this.contentWindow,d;try{d=w.document}catch(e){}if(h&amp;&amp;d&amp;&amp;(!d.body||!d.body.firstChild)){if(h.call){setTimeout(h,0)}else if(h.match){try{h=s.upd(h,i)}catch(e){}w.location.replace(h)}}" id="aswift_0" name="aswift_0" style="left:0;position:absolute;top:0;width:300px;height:600px;"></iframe></ins></ins></ins>
        <script>(adsbygoogle = window.adsbygoogle || []).push({});</script>
      </aside>
    </div>
  </aside>
</div>

<?php include("partials/footer.php"); ?>
