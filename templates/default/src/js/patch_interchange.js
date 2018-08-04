// this patch was made on 2015-02-23 by Mirco Frison <free3zone@gmail.com>
// due to a bug on the last image of Slick Slider
// purpose of this patch is overwriting entirely the Foundation.libs.interchange.settings.directives.replace function
// based on this solution: http://foundation.zurb.com/forum/posts/18344-issue-with-interchange-and-slick-slider

Foundation.libs.interchange.settings.directives.replace = function (el, path, trigger) {
    // The trigger argument, if called within the directive, fires
    // an event named after the directive on the element, passing
    // any parameters along to the event that you pass to trigger.
    //
    // ex. trigger(), trigger([a, b, c]), or trigger(a, b, c)
    //
    // This allows you to bind a callback like so:
    // $('#interchangeContainer').on('replace', function (e, a, b, c) {
    //   console.log($(this).html(), a, b, c);
    // });

    if (/IMG/.test(el[0].nodeName)) {
      var orig_path = el[0].src;

      if (new RegExp(path, 'i').test(orig_path)) {
        return;
      }

      $.each(el, function(){this.src = path;});

      return trigger(el[0].src);
    }
    var last_path = el.data(this.data_attr + '-last-path'),
        self = this;

    if (last_path == path) {
      return;
    }

    if (/\.(gif|jpg|jpeg|tiff|png)([?#].*)?/i.test(path)) {
      $(el).css('background-image', 'url(' + path + ')');
      el.data('interchange-last-path', path);
      return trigger(path);
    }

    return $.get(path, function (response) {
      el.html(response);
      el.data(self.data_attr + '-last-path', path);
      trigger();
    });

  };
