<?php include("partials/header_admin.php"); ?>
        <section>
          <div class="row">
            <div class="small-12 columns">
              <h2 class="summary boxed">{{h2}}</h2>
              <a class="btn button" href="{{Link|Get|ADMIN_NEWTABLE|<?php echo $table; ?>}}">Nuovo</a>
              <div class="tablecontainer" style="overflow:scroll;">
                <form action="{{Link|Get|ADMIN_RITAGLIO|<?php echo $table; ?>|<?php echo $id; ?>|<?php echo $field; ?>}}" method="POST">
                  <input type="hidden" name="cX1" value="">
                  <input type="hidden" name="cX2" value="">
                  <input type="hidden" name="cY1" value="">
                  <input type="hidden" name="cY2" value="">
                  <input type="hidden" name="cW" value="">
                  <input type="hidden" name="cH" value="">
                  <div>
                    <img class="cropper" src="{{imageUrl}}" />
                  </div>
                  <div class="formfooter">
                    <button type="submit">Conferma ritaglio</button>
                    <button type="button">Annulla</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </section>
        <script>
          var ajax_save_endpoint = '<?php echo $Link->Get("AJAX_SAVE"); ?>';
          <?php if ($crop_info !== false) { ?>
          var setSelect = <?php echo $crop_info; ?>
          <?php } ?>
        </script>
<?php include("partials/footer_admin.php"); ?>
