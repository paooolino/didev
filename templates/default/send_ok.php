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
          La richiesta è stata inviata correttamente.<br>
          Il nostro staff risponderà al più presto.
        </div>
      </article>
    </section>
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
