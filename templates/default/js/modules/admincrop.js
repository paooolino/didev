
function showCoords(c) {
  // variables can be accessed here as
  // c.x, c.y, c.x2, c.y2, c.w, c.h
  $('input[name=cX1]').val(c.x);
  $('input[name=cY1]').val(c.y);
  $('input[name=cX2]').val(c.x2);
  $('input[name=cY2]').val(c.y2);
  $('input[name=cW]').val(c.w);
  $('input[name=cH]').val(c.h);
};
  
$(document).on('ready', function() {
  var opts = {
    onSelect: showCoords,
    onChange: showCoords
  };
  if (window.setSelect) {
    opts.setSelect = window.setSelect;
  }
  $('img.cropper').Jcrop(opts);
});