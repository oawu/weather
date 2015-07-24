/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

function getTowns (map, town_id, $loadingData, notSaveLast, $zoom) {
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
              town_id: town_id ? town_id : 0,
              zoom: map.zoom
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
              icon: '/resource/image/map/spotlight-poi-blue.png',
              initCallback: function (t) {
              }
            });

          // google.maps.event.addListener (markerWithLabel, 'click', function () {
          //   $.ajax ({
          //     url: $('#update_town_zoom_url').val (),
          //     data: {
          //       id: t.id,
          //       zoom: map.zoom + 1,
          //     },
          //     async: true, cache: false, dataType: 'json', type: 'POST',
          //     beforeSend: function () { }
          //   })
          //   .done (function (result) {
          //     if (result.status) {
          //       markerWithLabel.setMap (null);
          //       map.markers = map.markers.filter (function (u) { return u.id != t.id; });
          //     }
          //   })
          //   .fail (function (result) { ajaxError (result); })
          //   .complete (function (result) {});
          // });

          // google.maps.event.addListener (markerWithLabel, 'rightclick', function () {
          //   $.ajax ({
          //     url: $('#update_town_zoom_url').val (),
          //     data: {
          //       id: t.id,
          //       zoom: map.zoom - 1,
          //     },
          //     async: true, cache: false, dataType: 'json', type: 'POST',
          //     beforeSend: function () { }
          //   })
          //   .done (function (result) {})
          //   .fail (function (result) { ajaxError (result); })
          //   .complete (function (result) {});
          // });

          return {
            id: t.id,
            markerWithLabel: markerWithLabel
          };
        });

        var deletes = map.markers.diff (markers);
        var adds = markers.diff (map.markers);
        var delete_ids = deletes.map (function (t) { return t.id; });
        var add_ids = adds.map (function (t) { return t.id; });

        deletes.map (function (t) {
          t.markerWithLabel.setMap (null);
        });
        adds.map (function (t) {
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

    
  if ($zoom.length)
    $zoom.text (map.zoom);

  if (!notSaveLast)
    setStorage.apply (this, ['weather_maps_admin_last', {
      lat: map.center.lat (),
      lng: map.center.lng (),
      zoom: map.zoom
    }]);
}
$(function () {
  $('.created_at').timeago ();
});