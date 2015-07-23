/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

function getWeathers (map, weatherId, $loadingData, notSaveLast) {
  clearTimeout (map.getWeathersTimer);

  map.getWeathersTimer = setTimeout (function () {
    if (map.isGetWeathers)
      return;

    if(!map.markers)
      map.markers = [];
    
    if(!map.markerCluster)
      map.markerCluster = new MarkerClusterer(_map);

    if ($loadingData)
      $loadingData.addClass ('show');
    map.isGetWeathers = true;

    var northEast = map.getBounds().getNorthEast ();
    var southWest = map.getBounds().getSouthWest ();

    $.ajax ({
      url: 'http://dev.weather.ioa.tw/main/get_weathers',
      data: { NorthEast: {latitude: northEast.lat (), longitude: northEast.lng ()},
              SouthWest: {latitude: southWest.lat (), longitude: southWest.lng ()},
              weatherId: weatherId ? weatherId : 0
            },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {}
    })
    .done (function (result) {
        if (result.status) {
          var markers = result.weathers.map (function (t) {
            var markerWithLabel = new MarkerWithLabel ({
              position: new google.maps.LatLng (t.lat, t.lng),
              draggable: false,
              raiseOnDrag: false,
              clickable: true,
              labelContent: "<div class='temperature'>" + t.temp + "℃</div><div class='bottom'><div class='title'>" + t.title + "</div></div>",
              labelAnchor: new google.maps.Point (65, 95),
              labelClass: "marker_label",
              icon: t.icon
            });
            return {
              id: t.id,
              markerWithLabel: markerWithLabel
            };
          });

          var deletes = map.markers.diff (markers);
          var adds = markers.diff (map.markers);
          var delete_ids = deletes.map (function (t) { return t.id; });
          var add_ids = adds.map (function (t) { return t.id; });

          map.markerCluster.removeMarkers (deletes.map (function (t) { return t.markerWithLabel; }));
          map.markerCluster.addMarkers (adds.map (function (t) { return t.markerWithLabel; }));

          map.markers = map.markers.filter (function (t) { return $.inArray (t.id, delete_ids) == -1; }).concat (markers.filter (function (t) { return $.inArray (t.id, add_ids) != -1; }));

          $loadingData.removeClass ('show');
          _isGetPictures = false;
        }
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  }, 100);

  if (!notSaveLast)
    setStorage.apply (this, ['weather_maps_last', {
      lat: map.center.lat (),
      lng: map.center.lng (),
      zoom: map.zoom
    }]);
}
$(function () {
  $container = $('#container');
  $map = $container.find ('#map');
  $loadingData = $container.find ('.loading');

  function initialize () {
    _map = new google.maps.Map ($map.get (0), {
        zoom: 14,
        zoomControl: true,
        scrollwheel: true,
        scaleControl: true,
        mapTypeControl: false,
        navigationControl: true,
        streetViewControl: false,
        disableDoubleClickZoom: true,
        center: new google.maps.LatLng (25.04, 121.55),
      });

    var last = getLastPosition ('weather_maps_last');

    if (last) {
      _map.setCenter (new google.maps.LatLng (last.lat, last.lng));
      _map.setZoom (last.zoom);
    } else {
      navigator.geolocation.getCurrentPosition (function (position) {
        _map.setZoom (14);
        mapGo (_map, new google.maps.LatLng (position.coords.latitude, position.coords.longitude), function (map) {
          setStorage.apply (this, ['weather_maps_last', {
            lat: map.center.lat (),
            lng: map.center.lng (),
            zoom: map.zoom
          }]);
        });
      });
    }

    google.maps.event.addListener(_map, 'zoom_changed', getWeathers.bind (this, _map, 0, $loadingData));
    google.maps.event.addListener(_map, 'idle', getWeathers.bind (this, _map, 0, $loadingData));

    window.mainLoading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
      });
    });
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});