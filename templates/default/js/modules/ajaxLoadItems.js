// ITEMS
(function() {
  function scroll_handler() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      $(window).off('scroll');
      $('.ajaxLoadItems').append('<div class="loader"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i> Caricamento...</div>');
      $(document).foundation();
      var page = $('.ajaxLoadItems').data('page'); 
      $.ajax({
        url: AJAX_LOAD_ITEMS_ENDPOINT,
        type: 'post',
        data: {
          slug: $('.ajaxLoadItems').data('slug'),
          page:  page + 1
        },
        success: function(html) {
          $('.ajaxLoadItems .loader').remove();
          $(document).foundation();
          $('.ajaxLoadItems').data('page', page + 1);
          if (html != '') {
            $('.ajaxLoadItems').append(html);
            $(document).foundation();
            $(window).on('scroll', scroll_handler);
          }
        }
      });
      
    }
  };
  $(document).ready(function() {
    if ($('.ajaxLoadItems').length == 1) {
      $(window).on('scroll', scroll_handler);
    }
  });
})();

// EVENTS
(function() {
  function scroll_handler() {
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
      $(window).off('scroll');
      $('.ajaxLoadEvents').append('<div class="loader"><i class="fa fa-circle-o-notch fa-spin" style="font-size:24px"></i> Caricamento...</div>');
      $(document).foundation();
      var page = $('.ajaxLoadEvents').data('page'); 
      var past = $('.ajaxLoadEvents').data('past'); 
      $.ajax({
        url: AJAX_LOAD_EVENTS_ENDPOINT,
        type: 'post',
        data: {
          past: past,
          page: page + 1
        },
        success: function(html) {
          $('.ajaxLoadEvents .loader').remove();
          $(document).foundation();
          $('.ajaxLoadEvents').data('page', page + 1);
          if (html != '') {
            $('.ajaxLoadEvents').append(html);
            $(document).foundation();
            $(window).on('scroll', scroll_handler);
          }
        }
      });
      
    }
  };
  $(document).ready(function() {
    if ($('.ajaxLoadEvents').length == 1) {
      $(window).on('scroll', scroll_handler);
    }
  });
})();