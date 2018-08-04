var ga_track_event;

$(document).ready(function() {
  var page_home, page_location;
  
  //### record stats about tenants_choicer
  $('.mainMenu').find('.choiceTenants a').bind("click", function(event) {
    return ga_track_event(event);
  });
  //### record stats about internal banners
  $('.banner').find('img').bind("click", function(event) {
    return ga_track_event(event);
  });
  //### record stats about cookies
  $('.cookiesNotice').find('a').bind("click", function(event) {
    return ga_track_event(event);
  });
  // home page
  page_home = $("body.index.home");
  if (page_home.length) {
    //### record stats about showroom events on home page
    $('.pShowroomEve').find('a.summary').bind("click", function(event) {
      return ga_track_event(event);
    });
    //### record stats about showroom events on home page
    $('.pHomeSlides').find('a.slide').bind("click", function(event) {
      return ga_track_event(event);
    });
  }
  // location
  page_location = $("body.locations.location");
  if (page_location.length) {
    //### record stats about showroom events on home page
    $('.infos').find('a.u-email').bind("click", function(event) {
      return ga_track_event(event);
    });
    //### record stats about showroom events on home page
    return $('.infos').find('a.u-url').bind("click", function(event) {
      return ga_track_event(event);
    });
  }
});

//########### functions: #####
ga_track_event = function(event) {
  return ga('send', 'event', $(event.target).attr("data-galabel"), 'click', $(event.target).attr("data-gavalue"));
};
