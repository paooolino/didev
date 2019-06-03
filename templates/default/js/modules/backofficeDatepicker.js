App.backofficeDatepicker = {
  mysqldate_to_short: function(mysqldate) {
    var parts = mysqldate.split('-');
    return parts[2] + '/' + parts[1] + '/' + parts[0];
  },
  short_to_mysqldate: function(shortdate) {
    var parts = shortdate.split('/');
    return parts[2] + '-' + parts[1] + '-' + parts[0];
  },
  removeEvents: function() {
    $('.backoffice-datepicker').fdatepicker('remove');
    $('.backoffice-datetimepicker').fdatepicker('remove');
    $('.newdatebutton').off('click');
  },
  attachEvents: function() {
    $('.backoffice-datepicker').each(function() {
      if ($(this).val().indexOf('-') > -1) {
        $(this).val(App.backofficeDatepicker.mysqldate_to_short($(this).val()));
      }
    });
    
    $('.backoffice-datepicker').fdatepicker({
      //format: 'yyyy-mm-dd'
      format: 'dd/mm/yyyy'
    }).on('changeDate', function(evt) {
      if (window.ajax_save_endpoint) {
        var obj = this;
        $(this).parent().append('<i class="cogspin fa fa-cog fa-spin"></i>');
        var dataTable = $(this).parents('table').data('table')
          ? $(this).parents('table')
          : $(this).parents('table').parents('table')
        var dataTr = $(this).parents('tr').data('id')
          ? $(this).parents('tr')
          : $(this).parents('tr').parents('tr')
        var dataTd = $(this).parents('td').data('field')
          ? $(this).parents('td')
          : $(this).parents('td').parents('td')
          
        App.removeEvents();
        $.ajax({
          url: ajax_save_endpoint,
          method: 'POST',
          data: {
            table: dataTable.data('table'),
            field_id: dataTable.data('field_id'),
            id: dataTr.data('id'),
            field: dataTd.data('field'),
            value: App.backofficeDatepicker.short_to_mysqldate($(this).val())
          },
          dataType: 'json',
          success: function(json) {
            if (json["newrecord"]) {
              dataTr.replaceWith(json["newrecord"]);
              $('.cogspin').remove();
              App.attachEvents();
            }
          }
        });
      }
    });
    
    $('.newdatebutton').on('click', function() {
      var el = $(this).parent().find('.backoffice-datepicker');
      if (el) {
        var newdate = App.backofficeDatepicker.mysqldate_to_short($(this).data('new'));
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

