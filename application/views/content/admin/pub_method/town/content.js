/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var enableUpdateTown = false;

  var $map = $('#map');
  var $marker = $('#marker');
  var $loadingData = $('#loading_data');
  var $zoom = $('#zoom');
  
  var $name = $('#name');
  var $postal_code = $('#postal_code');

  var _map = null;

  function initialize () {
    var marker = new google.maps.Marker ({
        draggable: enableUpdateTown,
        position: new google.maps.LatLng ($marker.data ('lat'), $marker.data ('lng')),
      });

    _map = new google.maps.Map ($map.get (0), {
        zoom: 14,
        zoomControl: true,
        scrollwheel: true,
        scaleControl: true,
        mapTypeControl: false,
        navigationControl: true,
        center: marker.position,
        streetViewControl: false,
        disableDoubleClickZoom: true,
      });

    marker.setMap (_map);

    $name.text ($marker.data ('name'));
    $postal_code.text ($marker.data ('postal_code'));

    google.maps.event.addListener (marker, 'dragend', function () {
      if (enableUpdateTown)
        updateTown (_map, $marker.val (), _marker.position, $name, $postal_code);
    });

    google.maps.event.addListener(_map, 'zoom_changed', getTowns.bind (this, _map, $marker.val (), $loadingData, true, $zoom));
    google.maps.event.addListener(_map, 'idle', getTowns.bind (this, _map, $marker.val (), $loadingData, true, $zoom));
    
    getTowns ( _map, $marker.val (), $loadingData, true, $zoom);
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});