$(document).ready(function() {
  $('table.sortable tbody').sortable({
    stop: function(event, ui) {
      var endpoint = $(event.target.closest('table')).data('order_endpoint');
      if (endpoint) {
        var $trs = $('table.sortable tbody').children();
        var ids = [];
        for(var i = 0; i < $trs.length; i++) {
          ids.push($trs.eq(i).data('id'));
        }
        $.ajax({
          url: endpoint,
          method: 'post',
          data: {
            ids: ids
          },
          dataType: 'json',
          success: function(json) {
            console.log(json);
          }
        });
      }
    }
  });
});