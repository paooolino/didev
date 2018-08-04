$(document).on('ready', function() {
  $('.formField textarea').each(function() {
    CKEDITOR.replace(this);
  });  
  $('.formfield textarea.ckeditor').each(function() {
    CKEDITOR.replace(this);
  });
});