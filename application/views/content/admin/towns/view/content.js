/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $view = $('#view');
  var $marker = $('#marker');
  var $form = $('form');

  var $latitude = $('#latitude');
  var $longitude = $('#longitude');
  var $heading = $('#heading');
  var $pitch = $('#pitch');
  var $zoom = $('#zoom');

  var $name = $('#name');
  var $postal_code = $('#postal_code');

  var $mapLoadingData = $('.map .loading_data');
  var $viewLoadingData = $('.view .loading_data').addClass ('show');

  var _map = null;
  var _marker = null;
  var _panorama = null;
  var _povChangedTimer = null;
  var _hasView = false;

  function initialize () {

    _marker = new google.maps.Marker ({
        draggable: true,
        position: new google.maps.LatLng ($marker.data ('lat'), $marker.data ('lng')),
      });

    _panorama = new google.maps.StreetViewPanorama ($view.get (0), {
      linksControl: true,
      addressControl: false,
      position: new google.maps.LatLng ($latitude.val (), $longitude.val ()),
      pov: {
        heading: parseInt ($heading.val (), 10),
        pitch: parseInt ($pitch.val (), 10),
        zoom: parseInt ($zoom.val (), 10)
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
        center: new google.maps.LatLng ($latitude.val (), $longitude.val ()),
        streetViewControl: true,
        disableDoubleClickZoom: true,
      });

    _marker.setMap (_map);

    $name.text ($marker.data ('name'));
    $postal_code.text ($marker.data ('postal_code'));
    
    google.maps.event.addListener (_marker, 'dragend', function () {
        updateTown (_map, $marker.val (), _marker.position, $name, $postal_code);
    });

    new google.maps.StreetViewService ().getPanoramaByLocation (_panorama.position, 50, function (data, status) {
      if (_hasView = status == google.maps.StreetViewStatus.OK) {
        $viewLoadingData.removeClass ('show');
      } else {
        $viewLoadingData.addClass ('show');

        $latitude.val ($marker.data ('lat'));
        $longitude.val ($marker.data ('lng'));
        $heading.val (0);
        $pitch.val (0);
        $zoom.val (0);
      }
    });
    google.maps.event.addListener (_panorama, 'visible_changed', function () {
      if (_hasView = this.getVisible ()) {
        $viewLoadingData.removeClass ('show');
      } else {
        $viewLoadingData.addClass ('show');
        
        $latitude.val ($marker.data ('lat'));
        $longitude.val ($marker.data ('lng'));
        $heading.val (0);
        $pitch.val (0);
        $zoom.val (0);
      }
    });

    google.maps.event.addListener (_panorama, 'position_changed', function () {
      if (_hasView) {
        $latitude.val (_panorama.getPosition ().lat ());
        $longitude.val (_panorama.getPosition ().lng ());
        mapGo (_map, _panorama.getPosition ());
      }
    });
    google.maps.event.addListener (_panorama, 'pov_changed', function () {
      clearTimeout (_povChangedTimer);

      _povChangedTimer = setTimeout (function () {
        if (_hasView) {
          $heading.val (_panorama.getPov ().heading);
          $pitch.val (_panorama.getPov ().pitch);
          $zoom.val (_panorama.getPov ().zoom);
        }
      }, 500);
    });

    google.maps.event.addListener(_map, 'zoom_changed', getTowns.bind (this, _map, $marker.val (), $mapLoadingData));
    google.maps.event.addListener(_map, 'idle', getTowns.bind (this, _map, $marker.val (), $mapLoadingData));

    $form.submit (function () {
      if (!_hasView) {
        alert ('此處沒有任何街景，請選擇有街景的地區！');
        return false;
     }
    });
  }
  if (!$('.create_cate').length)
    google.maps.event.addDomListener (window, 'load', initialize);
});