App.backofficeDatepicker = {
  removeEvents: function() {
    $('.backoffice-datepicker').fdatepicker('remove');
    $('.backoffice-datetimepicker').fdatepicker('remove');
    $('.newdatebutton').off('click');
  },
  attachEvents: function() {
    $('.backoffice-datepicker').fdatepicker({
      format: 'yyyy-mm-dd'
    }).on('changeDate', function(evt) {
      if (window.ajax_save_endpoint) {
        var obj = this;
        $(this).parent().append('<i class="fa fa-cog fa-spin"></i>');
        var dataTable = $(this).parents('table').data('table')
          ? $(this).parents('table')
          : $(this).parents('table').parents('table')
        var dataTr = $(this).parents('tr').data('id')
          ? $(this).parents('tr')
          : $(this).parents('tr').parents('tr')
        var dataTd = $(this).parents('td').data('field')
          ? $(this).parents('td')
          : $(this).parents('td').parents('td')
        $.ajax({
          url: ajax_save_endpoint,
          method: 'POST',
          data: {
            table: dataTable.data('table'),
            field_id: dataTable.data('field_id'),
            id: dataTr.data('id'),
            field: dataTd.data('field'),
            value: $(this).val()
          },
          dataType: 'json',
          success: function(json) {
            if (json["newrecord"]) {
              App.removeEvents();
              dataTr.replaceWith(json["newrecord"]);
              App.attachEvents();
            }
          }
        });
      }
    });
    
    $('.newdatebutton').on('click', function() {
      var el = $(this).parent().find('.backoffice-datepicker');
      if (el) {
        var newdate = $(this).data('new');
        $(el).val(newdate).trigger('changeDate');
      }
    });
    /*
    $('.backoffice-datetimepicker').fdatepicker({
      format: 'yyyy-mm-dd hh:ii',
      pickTime: true
    }).on('changeDate', function(evt) {
      if (window.ajax_save_endpoint) {
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
      }
    });
    */
  }
};

