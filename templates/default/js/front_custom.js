var scrollToSelector = function(selector) {
  $('html, body').animate({ scrollTop: $(selector).offset().top + 'px'}, 'fast');
};

function enableDatePicker () {
  $('.date-picker').fdatepicker({ language: 'it', format: 'dd/mm/yyyy', weekStart: 1 });
}

function set_tabs(){
  if( $(window).width() <= 640 ) {
    $( ".toggle" ).each(function(){
      $(this).find( "i.fa-angle-down" ).switchClass( "fa-angle-down", "fa-angle-right" ,10, "linear");  
      $(this).next( ".contentToggle" ).hide();
    });
  } else {
    $( ".toggle" ).each(function(){
      $(this).find( "i.fa-angle-right" ).switchClass( "fa-angle-right", "fa-angle-down" ,10, "linear"); 
      $(this).next( ".contentToggle" ).show();
    });
  }   
}

var homeSlideshow = function(body) {
  body.find('.mainbanner').slick({
    dots: false,
    autoplay: false,
    pauseOnHover:true,
    speed:300,
    autoplaySpeed: 4000,
    infinite: true
  });
};

var photoGallery = function(body){
  /*body.find(".pgallery a").fancybox({
    openEffect  : 'none',
    closeEffect : 'none',
    helpers: { overlay: { locked: false } }
  });*/
}

var foundationReflow = function(time) {
  setTimeout(function(){
    $(document).foundation('reflow');
  }, time);
}

var masonryBoxes = function(body) {
  // only for medium and large screens
  if ( $(window).width() >= 640 ) {
    /* Mansory plugin behaviour */
    var masonry = body.find('#masonry');
    if (masonry.length) {
      // initialize Masonry after all images have loaded 
      masonry.imagesLoaded(function(){
        foundationReflow(1000);
        $(window).resize(function(){
          foundationReflow(200);
        });
        var msnry = new Masonry( masonry[0], {
          itemSelector: '.masonrybox',
          columnWidth: masonry[0].querySelector('.grid-sizer')
        });
      });
    }
  }
};

// used on location sheet:
var toggleFormOnlist = function(body) {
  body.find('.form-onlist-wrapper').hide();
  return body.find(".for-onlist-toggler").click(function(event) {
    event.preventDefault();
    body.find(".form-onlist-wrapper").slideToggle("slow", function() {
      foundationReflow(0);
    });
  });
};

var locationSlideshow = function(body) {
  body.find('.slideshow').slick({
    dots: false,
    autoplay: true,
    speed: 500,
    fade: true,
    arrows: false,
    cssEase: 'linear',
    infinite: true
  });
};

var sideTabs = function(body) {
  body.find( ".toggle" ).click(function() {
    $(this).find( "i.fa-angle-right" ).switchClass( "fa-angle-right", "fa-angle-down" ,10, "linear");
    $(this).find( "i.fa-angle-down" ).switchClass( "fa-angle-down", "fa-angle-right" ,10, "linear");
    $(this).next( ".contentToggle" ).toggle( 120, "linear", function() {
      // Animation complete
      foundationReflow(0);
    });
  });
};

var dropcap = function() {
  var text = $.trim($(this).html());
  var first_letter = text.substr(0,1);
  $(this).html('<span class="dropcap">' + first_letter + '</span>' + text.slice(1));
};

// box calendar days
var calendarSwitcher = function(calendar){
  calendar.find("header a").on("click", function(event) {
    $.get($(event.target).attr("href"), function(data) {
      return calendar.html(data).fadeIn();
    });
    return event.preventDefault();
  });
  calendar.ajaxStart(function() {
    calendar.find("header .calendar-title").addClass("loading");
    return calendar.find(".inner").fadeTo(100, 0.8);
  });
};

var calendarStyle = function(calendar){
  calendar.find("table .today:not(:has(a))").each(function( i ) {
    $(this).wrapInner("<span></span>");
  });
};

var toTopBehaviour = function(selector){
  $(window).scroll(function(){
    ($(document).scrollTop()>=500) ? selector.show() : selector.hide();
  });
  selector.click(function(){
    $('html, body').animate({ scrollTop: 0 }, 800);
  });
};

var boxCalendarBehaviour = function(box){
  calendarSwitcher(box);
  calendarStyle(box);
};

var iterateNavMain = function(path){
  $( ".navMain li > a" ).each(function( i ) {
    if (path == $(this).attr('href')) {
      $(this).parentsUntil(".navMain").addClass('active');
    }
  });
};

function navMainActive() {
  var currentPath= '/';
  if ( document.location.pathname != '/' ){
    currentPath = $('.breadcrumb li:nth-child(2) a').attr('href'); 
  }
  iterateNavMain(currentPath);
}

var alphaLocationsActive = function(path){
  return;
  /*
  var current_alpha = document.location.pathname.match(/\/([A-Z])$/)[1];
  $(".paginate_alpha").find("li a").each(function( i ) {
    if (current_alpha == $(this).text().replace(/\s/g, '')) {
      $(this).addClass('active');
    }
  });
  */
}

var page_home = $("body.index.home");
var pageSection = $("body.sections.section");
var pageLocation = $("body.locations.location");
var pageEvent = $("body.events.event");
var pageTypologyLocation = $("body.locations.typology_locations");
var boxCalendar = $("#main").find(".box_calendar");

$(document).ready(function(){

  function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  
  function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }

  $(document).foundation();

  enableDatePicker();

  navMainActive();

  // cookie notice
  (function() {
    if (getCookie('cookie_notice') != 'true') {
      var html = '\
        <div class="cookiesNotice alert-box secondary" data-alert>\
        <div class="row">\
        <div class="notice medium-7 large-8 columns">\
        Questo sito utilizza cookie per offrirti una migliore esperienza di navigazione sul sito. Maggiori informazioni sui cookie in <a data-gavalue="Bergamo" data-galabel="Cookies_informativa" href="/sezione/cookie-notice">questa pagina</a>. Proseguendo la navigazione acconsenti all’uso dei cookie.\
        </div>\
        <div class="confirmation medium-5 large-4 columns">\
        <a class="button tiny" data-gavalue="Bergamo" data-galabel="Cookies_consenso" data-remote="true" href="/cookie_notice">Accetto le Condizioni</a>\
        </div>\
        </div>\
        </div>\
      ';
      $('body').prepend(html);
      $(".cookiesNotice").find(".confirmation a").click(function(evt) {
        evt.preventDefault();
        evt.stopPropagation();
        
        setCookie('cookie_notice', 'true', 365*5);
        $('.cookiesNotice').slideUp("slow");
      });
    }
  })();

  if (page_home.length) {
    homeSlideshow(page_home);
    masonryBoxes(page_home);
    
  }
  if (pageSection.length) {
    photoGallery(pageSection);
    sideTabs(pageSection);
  }

  if (pageLocation.length) {
    //toggleFormOnlist(pageLocation);
    locationSlideshow(pageLocation);
    //photoGallery(pageLocation);
    sideTabs(pageLocation);
  }

  if (pageTypologyLocation.length) {
    alphaLocationsActive();
  }

  if (pageEvent.length) {
    photoGallery(pageEvent);
  }

  if (boxCalendar.length) {
    boxCalendarBehaviour(boxCalendar);
  }
  
});

  /* Location subnav behaviour */

  $(window).scroll(function(e){
    if (pageLocation.length) {
      sideTabsScrolling();
    }
  });

  function sideTabsScrolling(){
    var yTop=$(window).scrollTop();
    var offset=$('.headbox').outerHeight()
    if (yTop >= $('#wrapper').offset().top){
      $('#subnav').stop();
      $('#subnav').animate({'margin-top':yTop-offset-155},700,"easeOutBack");
    }
    else {
      $('#subnav').stop();
      $('#subnav').animate({'margin-top':0},700,"easeOutBack");
    }
    var activeIndex = 0;
    $('.toggle').each(function(){
      if( $(this).offset().top < $(window).scrollTop() + 40 ) {
        activeIndex++;
      }
    });
    var maxScroll = $(document).height() - $(window).height();
    if( $(window).scrollTop() == maxScroll ) {
      activeIndex = $('#subnav a').length-1;
    }
    $('#subnav a.active').removeClass('active');
    $('#subnav a').eq(activeIndex).addClass('active');
  }
  
  $(function() {
    $('#subnav a[href*=#]').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var $target = $(this.hash);
        $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');
        if ($target.length) {
          var targetOffset = $target.offset().top;
          $('html,body').stop();
          $('html,body').animate({scrollTop: targetOffset}, 700);
          return false;
        }
      }
  });

  /* retrn at the top of the form when submitted */
  var topFormScroller = $('#main').find('.top-form-enabled');
  if (topFormScroller.length) {
    topFormScroller.on('click', ".bt", function() { scrollToSelector(topFormScroller) });
  }

  /* apply dropcap */
  $('#main').find('#desc > p:first-of-type').each( dropcap ); 

  /* ToTop button */
  toTopBehaviour($("#totop"));

});



$( document ).ajaxComplete(function() {
  enableDatePicker();
  boxCalendarBehaviour(boxCalendar);
});


set_tabs();



