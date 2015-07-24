/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $container = $('#container');
  $map = $container.find ('#map');
  $loadingData = $container.find ('.loading_data');

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
        getWeathers (_map, 0, $loadingData);
        $(this).remove ();
      });
    });
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});