<?php include("partials/header_admin.php"); ?>

        <section>
          <div class="row">
            <div class="small-12 columns">
              <h2 class="summary boxed">{{h2}}</h2>
              <?php if ($disable_new == false) { ?>
              <a class="btn button" href="{{Link|Get|ADMIN_NEWTABLE|<?php echo $table; ?>}}">Nuovo</a>
              <?php } ?>
              <div class="tablecontainer" style="min-height:600px;overflow:scroll;">
                {{tableHtml}}
              </div>
            </div>
          </div>
        </section>
        <script>
          var ajax_save_endpoint = '<?php echo $Link->Get("AJAX_SAVE"); ?>';
        </script>
<?php include("partials/footer_admin.php"); ?>
