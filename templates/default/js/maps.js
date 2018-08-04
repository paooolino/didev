$(document).ready(function() {
  var handler, map_with_markers, points_data, points_length;
  map_with_markers = $('body.locations.location, body.events.event, body.sections.section').find('.geoMap');
  if (map_with_markers.length) {
    points_data = map_with_markers.data('points');
    points_length = Object.keys(points_data).length;
    handler = Gmaps.build("Google");
    return handler.buildMap({
      provider: {
        scrollwheel: false
      },
      internal: {
        id: "googleMap"
      }
    }, function() {
      var markers;
      markers = handler.addMarkers(points_data);
      handler.bounds.extendWith(markers);
      if (points_length > 1) {
        handler.fitMapToBounds();
      } else {
        handler.map.centerOn(markers[0]);
        handler.getMap().setZoom(15);
      }
    });
  }
});