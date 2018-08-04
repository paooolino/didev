App.backofficeListImage = {
  removeEvents: function() {
    $('.form_imgform input[type="file"]').off('change');
  },
  attachEvents: function() {
    $('.form_imgform input[type="file"]').on('change', function() {
      $(this).closest('form').submit();
    });
  }
};
