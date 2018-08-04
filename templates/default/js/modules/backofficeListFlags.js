var App = App || {};
App.backofficeListFlags = {
  removeEvents: function() {
    $('button.flag').off('click');
  },
  attachEvents: function() {
    $('button.flag').each(function() {
      if ($(this).data("value") == 1) {
        $(this).html('<i class="fa fa-check-square-o"></i>');
      } else {
        $(this).html('<i class="fa fa-square-o"></i>');
      }
    });
    
    $('button.flag').on('click', function() {
      $(this).html('<i class="fa fa-cog fa-spin"></i>');
      var obj = this;
      var new_value = $(this).data('value') == 0 ? 1 : 0;
      $.ajax({
        url: ajax_save_endpoint,
        method: 'POST',
        data: {
          table: $(this).closest('table').data('table'),
          field_id: $(this).closest('table').data('field_id'),
          id: $(this).closest('tr').data('id'),
          field: $(this).closest('td').data('field'),
          value: new_value
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

