/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('<div />').attr ('id', 'map');
  var $loadingData = $('<div />').addClass ('loading_data').text ('資料讀取中..');

  $container = $('#container').append (
                $('<div />').addClass ('map').append (
                  Array.apply (null, Array (4)).map (function (_, i) { return $('<i />'); })).append (
                  $map).append (
                  $loadingData));

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

    window.closeLoading ();
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});