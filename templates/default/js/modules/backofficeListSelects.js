App.backofficeListSelect = {
  removeEvents: function() {
    $('select.select').off('change');
  },
  attachEvents: function() {
    $('select.select').on('change', function() {
      var obj = this;
      $(this).parent().append('<i class="fa fa-cog fa-spin"></i>');
      $.ajax({
        url: ajax_save_endpoint,
        method: 'POST',
        data: {
          table: $(this).closest('table').data('table'),
          field_id: $(this).closest('table').data('field_id'),
          id: $(this).closest('tr').data('id'),
          field: $(this).closest('td').data('field'),
          value: $(this).val()
        },
        dataType: 'json',
        success: function(json) {
          if (json["newrecord"]) {
            App.removeEvents();
            $(obj).closest('tr').replaceWith(json["newrecord"]);
            App.attachEvents();
          }
        }
      });
    });
  }
};
