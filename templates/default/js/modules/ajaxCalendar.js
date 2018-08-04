
function calendar_clicklink_handler(evt) {
  evt.preventDefault();
  $.ajax({
    url: this.href,
    success: function(data) {
      $('#events_calendar .header a').off('click');
      $('.box_calendar').html(data);
      $('#events_calendar .header a').on('click', calendar_clicklink_handler);
    }
  });
}

$('#events_calendar .header a').on('click', calendar_clicklink_handler);
