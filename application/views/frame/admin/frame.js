/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

function getTowns (map, town_id, $loadingData, notSaveLast) {
  clearTimeout (map.getGoalsTimer);

  map.getGoalsTimer = setTimeout (function () {
    if (map.isGetGoals)
      return;
    
    if(!map.markers)
      map.markers = [];
        
    if ($loadingData)
      $loadingData.addClass ('show');
    map.isGetGoals = true;

    var northEast = map.getBounds().getNorthEast ();
    var southWest = map.getBounds().getSouthWest ();

    $.ajax ({
      url: $('#get_towns_url').val (),
      data: { NorthEast: {latitude: northEast.lat (), longitude: northEast.lng ()},
              SouthWest: {latitude: southWest.lat (), longitude: southWest.lng ()},
              town_id: town_id ? town_id : 0
            },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {}
    })
    .done (function (result) {
      if (result.status) {
        var markers = result.towns.map (function (t) {
          var markerWithLabel = new MarkerWithLabel ({
              position: new google.maps.LatLng (t.lat, t.lng),
              draggable: false,
              raiseOnDrag: false,
              clickable: true,
              labelContent: t.name,
              labelAnchor: new google.maps.Point (50, 0),
              labelClass: "marker_label",
              icon: '/resource/image/map/spotlight-poi-blue.png'
            });
          
          var polygon = (t.bound !== null ? new google.maps.Polygon ({
                        paths: [
                          new google.maps.LatLng (t.bound.northeast.lat, t.bound.northeast.lng),
                          new google.maps.LatLng (t.bound.southwest.lat, t.bound.northeast.lng),
                          new google.maps.LatLng (t.bound.southwest.lat, t.bound.southwest.lng),
                          new google.maps.LatLng (t.bound.northeast.lat, t.bound.southwest.lng)
                        ],
                        strokeColor: 'rgba(1, 50, 162, 1)',
                        strokeOpacity: 0.8,
                        strokeWeight: 2,
                        fillColor: 'rgba(96, 144, 255, 1)',
                        fillOpacity: 0.35
                      }) : null);
          return {
            id: t.id,
            markerWithLabel: markerWithLabel,
            polygon: polygon
          };
        });

        var deletes = map.markers.diff (markers);
        var adds = markers.diff (map.markers);
        var delete_ids = deletes.map (function (t) { return t.id; });
        var add_ids = adds.map (function (t) { return t.id; });

        deletes.map (function (t) {
          if (t.polygon)
            t.polygon.setMap (null);
          t.markerWithLabel.setMap (null);
        });
        adds.map (function (t) {
          if (t.polygon)
            t.polygon.setMap (map);
          t.markerWithLabel.setMap (map);
        });

        map.markers = map.markers.filter (function (t) { return $.inArray (t.id, delete_ids) == -1; }).concat (markers.filter (function (t) { return $.inArray (t.id, add_ids) != -1; }));

        if ($loadingData)
          $loadingData.removeClass ('show');
        map.isGetGoals = false;
      }
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  }, 200);

  if (!notSaveLast)
    setStorage.apply (this, ['weather_maps_admin_last', {
      lat: map.center.lat (),
      lng: map.center.lng (),
      zoom: map.zoom
    }]);
}
$(function () {
});