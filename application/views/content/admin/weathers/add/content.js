/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $loadingData = $('.map .loading_data');
  var $loading = $('<div />').attr ('id', 'loading')
                             .append ($('<div />'))
                             .appendTo ('#container');
  var $lat = $('#lat');
  var $lng = $('#lng');
  
  var _map = null;
  var _marker = null;

  Array.prototype.diff = function (a) {
    return this.filter (function (i) { return a.map (function (t) { return t.id; }).indexOf (i.id) < 0; });
  };
  function updateLatLng (position) {
    $lat.text ('緯度：' + position.lat ()).data ('val', position.lat ());
    $lng.text ('經度：' + position.lng ()).data ('val', position.lng ());
  }
  function initMarker (position) {
    updateLatLng (position);

    if (_marker)
      return _marker.setPosition (position);
    
    _marker = new google.maps.Marker ({
        map: _map,
        draggable: true,
        position: position
      });

    google.maps.event.addListener (_marker, 'dragend', function () {
      return updateLatLng (_marker.position);
    });
  }

  function initialize () {
    var styledMapType = new google.maps.StyledMapType ([
      { featureType: 'transit.station.bus',
        stylers: [{ visibility: 'off' }]
      }, {
        featureType: 'poi',
        stylers: [{ visibility: 'off' }]
      }, {
        featureType: 'poi.attraction',
        stylers: [{ visibility: 'on' }]
      }, {
        featureType: 'poi.school',
        stylers: [{ visibility: 'on' }]
      }
    ]);

    var option = {
        zoom: 14,
        scaleControl: true,
        navigationControl: true,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        zoomControl: true,
        scrollwheel: true,
        streetViewControl: false,
        center: new google.maps.LatLng (25.022073145389157, 121.54706954956055),
      };

    _map = new google.maps.Map ($map.get (0), option);
    _map.mapTypes.set ('map_style', styledMapType);
    _map.setMapTypeId ('map_style');

    google.maps.event.addListener(_map, 'click', function (e) {
      initMarker (e.latLng);
    });

    if ($lat.data ('val') && $lng.data ('val'))
      initMarker (new google.maps.LatLng ($lat.data ('val'), $lng.data ('val')));
    
    $('#fm').submit (function () {
      console.error (!($lat.data ('val') && $lng.data ('val')));
      if (!($lat.data ('val') && $lng.data ('val'))) {
        $('.error').text ('請點選地圖，選擇地點！').addClass ('show');
        return false;
      }
      $(this).append ($('<input />').attr ('type', 'hidden').attr ('name', 'latitude').val ($lat.data ('val')));
      $(this).append ($('<input />').attr ('type', 'hidden').attr ('name', 'longitude').val ($lng.data ('val')));

      return true;
    });

    $loading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
      });
    });
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});