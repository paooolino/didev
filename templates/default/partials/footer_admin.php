    </div>
    <span class="rounded" id="totop">
      <i class="fa fa-angle-up fa-lg"></i>
    </span>
    <a class="exit-off-canvas"></a>
  </div>
</div>
<script src="https://apis.google.com/js/platform.js" async="async" defer="defer"></script>

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
<!-- cropper -->
<script src="{{templatePath}}vendor/js/jquery.jCrop.min.js"></script>
<!-- chosen -->
<script src="{{templatePath}}vendor/js/chosen.jquery.min.js"></script>
<!-- custom code (previous site) -->
<script src="{{templatePath}}js/front_custom.js"></script>

<!-- my customs -->
<script>
  var App = {};
  App.removeEvents = function() {
    for (var module in App) {
      if (App[module].removeEvents) {
        App[module].removeEvents();
      }
    }
  }
  App.attachEvents = function() {
    for (var module in App) {
      if (App[module].attachEvents) {
        App[module].attachEvents();
      }
    }
  }
</script>
<script src="{{templatePath}}js/modules/ckeditor.js"></script>
<script src="{{templatePath}}js/modules/backofficeListFlags.js"></script>
<script src="{{templatePath}}js/modules/backofficeListSelects.js"></script>
<script src="{{templatePath}}js/modules/backofficeListImage.js"></script>
<script src="{{templatePath}}js/modules/backofficeDatepicker.js"></script>
<script src="{{templatePath}}js/modules/ajaxCalendar.js"></script>
<script src="{{templatePath}}js/modules/admincrop.js"></script>
<script src="{{templatePath}}js/modules/chosen.js"></script>
<script>App.attachEvents();</script>
<script>$(document).foundation();</script>
</body>
</html>