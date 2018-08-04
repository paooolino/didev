    </div>
    <footer class="row">
      <h3>{{h3}}</h3>
      <ul class="navFooter inline-list text-center">
        <li>
        <a href="{{Link|Get|PAGINA|staff}}" title="Chi siamo &amp; staff">
        Chi siamo
        </a>
        </li>
        <li>
        <a href="{{Link|Get|CATEGORIA_LOCALI|ricette-cocktail-internazionali}}" title="Ricette cocktail internazionali">
        Ricette cocktail
        </a>
        </li>
      </ul>
      <p>ARS di Alberto Ragnoli - Via X Giornate 14, Castel Mella 25030 - BRESCIA ITALY - P.IVA 03304640984 <br /> Iscrizione presso la Camera di Commercio di Brescia, REA 52265<br />Copyright 2006-<?php echo date("Y"); ?> - Ogni riproduzione, anche parziale, è vietata</p>
    </footer>
    <span class="rounded" id="totop">
      <i class="fa fa-angle-up fa-lg"></i>
    </span>
    <a class="exit-off-canvas"></a>
  </div>
</div>

<!-- jquery -->
<script src="{{templatePath}}vendor/js/jquery.js"></script>
<!-- jquery ui -->
<script src="{{templatePath}}vendor/js/jquery-ui.min.js"></script>
<!-- modernizr -->
<!--<script src="{{templatePath}}vendor/js/modernizr.js"></script>-->
<!-- ckeditor -->
<script src="{{templatePath}}vendor/ckeditor/ckeditor.js"></script>
<!-- foundation -->
<script src="{{templatePath}}vendor/js/foundation.min.js"></script>
<!-- foundation-datepicker -->
<script src="{{templatePath}}vendor/js/foundation-datepicker.min.js"></script>
<!-- slick carousel -->
<script src="{{templatePath}}vendor/js/slick.min.js"></script>
<!-- masonry layout -->
<script src="{{templatePath}}vendor/js/masonry.pkgd.min.js"></script>
<!-- imagesloaded -->
<script src="{{templatePath}}vendor/js/imagesloaded.pkgd.min.js"></script>
<!-- jquery-validation.js -->
<script src="{{templatePath}}vendor/js/jquery.validate.min.js"></script>
<!-- custom code (previous site) -->
<script src="{{templatePath}}js/front_custom.js"></script>

<!-- my customs -->
<script>
var AJAX_LOAD_ITEMS_ENDPOINT = '<?php echo $Link->Get("AJAX_LOAD_ITEMS"); ?>';
</script>
<script src="{{templatePath}}js/modules/ckeditor.js"></script>
<script src="{{templatePath}}js/modules/backofficeListFlags.js"></script>
<script src="{{templatePath}}js/modules/ajaxCalendar.js"></script>
<script src="{{templatePath}}js/modules/ajaxLoadItems.js"></script>
<script src="{{templatePath}}js/modules/formvalidators.js"></script>

</body>
</html>