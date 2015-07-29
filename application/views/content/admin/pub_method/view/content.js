/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var enableUpdateTown = true;
  var enableUpdateView = true;

  var $map = $('#map');
  var $view = $('#view');
  var $marker = $('#marker');
  var $panorama = $('#panorama');
  var $mapLoadingData = $('#loading_data');
  var $viewLoadingData = $('#viewLoadingData');
  
  var $name = $('#name');
  var $postal_code = $('#postal_code');

  var _map = null;
  var _marker = null;
  var _panorama = null;
  var _povChangedTimer = null;
  var _hasView = false;

  function initialize () {

    _marker = new google.maps.Marker ({
        draggable: enableUpdateTown,
        position: new google.maps.LatLng ($marker.data ('lat'), $marker.data ('lng')),
      });

    _panorama = new google.maps.StreetViewPanorama ($view.get (0), {
      linksControl: true,
      addressControl: false,
      position: $panorama.length ? new google.maps.LatLng ($panorama.data ('lat'), $panorama.data ('lng')) : new google.maps.LatLng ($marker.data ('lat'), $marker.data ('lng')),
      pov: {
        heading: parseInt ($panorama.length ? $panorama.data ('heading') : 0, 10),
        pitch: parseInt ($panorama.length ? $panorama.data ('pitch') : 0, 10),
        zoom: parseInt ($panorama.length ? $panorama.data ('zoom') : 0, 10)
      }
    });

    _map = new google.maps.Map ($map.get (0), {
        zoom: 17,
        zoomControl: true,
        scrollwheel: true,
        scaleControl: true,
        streetView: _panorama,
        mapTypeControl: false,
        navigationControl: true,
        streetViewControl: true,
        disableDoubleClickZoom: true,
        center: new google.maps.LatLng ($marker.data ('lat'), $marker.data ('lng')),
      });

    _marker.setMap (_map);

    $name.text ($marker.data ('name'));
    $postal_code.text ($marker.data ('postal_code'));

    google.maps.event.addListener (_marker, 'dragend', function () {
      if (enableUpdateTown)
        updateTown (_map, $marker.val (), _marker.position, $name, $postal_code);
    });

    new google.maps.StreetViewService ().getPanoramaByLocation (_panorama.position, 50, function (data, status) {
      if (_hasView = status == google.maps.StreetViewStatus.OK)
        $viewLoadingData.removeClass ('show');
      else
        $viewLoadingData.addClass ('show');
    });

    google.maps.event.addListener (_panorama, 'visible_changed', function () {
      if (_hasView = this.getVisible ())
        $viewLoadingData.removeClass ('show');
      else
        $viewLoadingData.addClass ('show');
    });
    google.maps.event.addListener (_panorama, 'position_changed', function () {
      if (_hasView) {
        if (enableUpdateView)
          updateTownView (_map, $marker.val (), _panorama.getPosition ().lat (), _panorama.getPosition ().lng (), _panorama.getPov ().heading, _panorama.getPov ().pitch, _panorama.getPov ().zoom);
        mapGo (_map, _panorama.getPosition ());
      }
    });
    google.maps.event.addListener (_panorama, 'pov_changed', function () {
      clearTimeout (_povChangedTimer);

      _povChangedTimer = setTimeout (function () {
        if (_hasView && enableUpdateView)
          updateTownView (_map, $marker.val (), _panorama.getPosition ().lat (), _panorama.getPosition ().lng (), _panorama.getPov ().heading, _panorama.getPov ().pitch, _panorama.getPov ().zoom);
      }, 500);
    });

    google.maps.event.addListener(_map, 'zoom_changed', getTowns.bind (this, _map, $marker.val (), $mapLoadingData, false));
    google.maps.event.addListener(_map, 'idle', getTowns.bind (this, _map, $marker.val (), $mapLoadingData, false));
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});