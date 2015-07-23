/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $marker = $('#marker');
  var $name = $('#name');
  var $postal_code = $('#postal_code');
  var $latitude = $('#latitude');
  var $longitude = $('#longitude');

  var _map = null;
  var _marker = null;

  function updateForm (position) {
    $latitude.val (position.lat ());
    $longitude.val (position.lng ());

    new google.maps.Geocoder ().geocode ({'latLng': position}, function (result, status) {
      var postal_code = [];
      var name = [];

      if ((status == google.maps.GeocoderStatus.OK) && result.length && (result = result[0])) {
        name = result.address_components.map (function (t) {
          return t.types.length && ($.inArray ('administrative_area_level_3', t.types) !== -1) ? t.long_name : null;
        }).filter (function (t) { return t; });
        postal_code = result.address_components.map (function (t) {
          return t.types.length && ($.inArray ('postal_code', t.types) !== -1) ? t.long_name : null;
        }).filter (function (t) { return t; });
      }
        $name.val (name.length ? name[0] : '');
        $postal_code.val (postal_code.length ? postal_code[0] : '');
    });

    mapGo (_map, position);
  }
  function initMarker (position) {
    updateForm (position);
    
    if (_marker)
      return _marker.setPosition (position);
    
    _marker = new google.maps.Marker ({
        map: _map,
        draggable: true,
        position: position
      });

    google.maps.event.addListener (_marker, 'dragend', function () {
      updateForm (_marker.position);
    });
  }
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

    var last = getLastPosition ('weather_maps_admin_last');

    if (last) {
      _map.setCenter (new google.maps.LatLng (last.lat, last.lng));
      _map.setZoom (last.zoom);
    } else {
      navigator.geolocation.getCurrentPosition (function (position) {
        _map.setZoom (14);
        mapGo (_map, new google.maps.LatLng (position.coords.latitude, position.coords.longitude), function (map) {
          setStorage.apply (this, ['weather_maps_admin_last', {
            lat: map.center.lat (),
            lng: map.center.lng (),
            zoom: map.zoom
          }]);
        });
      });
    }

    if ($latitude.val () && $longitude.val ()) {
      _map.setCenter (new google.maps.LatLng ($latitude.val (), $longitude.val ()));
      initMarker (_map.center);
    }

    google.maps.event.addListener(_map, 'click', function (e) { initMarker (e.latLng); });
    google.maps.event.addListener(_map, 'zoom_changed', getTowns.bind (this, _map, $marker.val (), $('#loading_data')));
    google.maps.event.addListener(_map, 'idle', getTowns.bind (this, _map, $marker.val (), $('#loading_data')));
  }
  if (!$('.create_cate').length)
    google.maps.event.addDomListener (window, 'load', initialize);
});