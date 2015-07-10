/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $map = $('#map');
  var $loading = $('<div />').attr ('id', 'loading')
                             .append ($('<div />'))
                             .appendTo ('#container');
  var $lat = $('#lat');
  var $lng = $('#lng');
  var $latlng_bar = $('#latlng_bar');
  
  var _map = null;
  var _marker = null;
  var _markers = [];
  var _isGetPictures = false;
  var _getPicturesTimer = null;

  Array.prototype.diff = function (a) {
    return this.filter (function (i) { return a.map (function (t) { return t.id; }).indexOf (i.id) < 0; });
  };
  function getStorage () {
    if ((typeof (Storage) !== 'undefined') && (last = localStorage.getItem ('weather_map')) && (last = JSON.parse (last)))
      return last;
    else
      return;
  }
  function setStorage () {
    if (typeof (Storage) !== 'undefined') {
      localStorage.setItem ('weather_map', JSON.stringify ({
        lat: _map.center.lat (),
        lng: _map.center.lng (),
        zoom: _map.zoom
      }));
    }
  }
  function getWeathers () {
    clearTimeout (_getPicturesTimer);

    _getPicturesTimer = setTimeout (function () {
      if (_isGetPictures)
        return;
      
      _isGetPictures = true;

      var northEast = _map.getBounds().getNorthEast ();
      var southWest = _map.getBounds().getSouthWest ();

      $.ajax ({
        url: $('#get_weathers_url').val (),
        data: { NorthEast: {latitude: northEast.lat (), longitude: northEast.lng ()},
                SouthWest: {latitude: southWest.lat (), longitude: southWest.lng ()}
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
              labelContent: t.title,
              labelAnchor: new google.maps.Point (50, 0),
              labelClass: "marker_label",
              icon: '/resource/image/spotlight-poi-blue.png'
            });
            return {
              id: t.id,
              markerWithLabel: markerWithLabel
            };
          });

          var deletes = _markers.diff (markers);
          var adds = markers.diff (_markers);
          var delete_ids = deletes.map (function (t) { return t.id; });
          var add_ids = adds.map (function (t) { return t.id; });

          deletes.map (function (t) { t.markerWithLabel.setMap (null); });
          adds.map (function (t) { t.markerWithLabel.setMap (_map); });

          _markers = _markers.filter (function (t) { return $.inArray (t.id, delete_ids) == -1; }).concat (markers.filter (function (t) { return $.inArray (t.id, add_ids) != -1; }));

          _isGetPictures = false;
        }
      })
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) {});
    }, 500);

    setStorage ();
  }
  var getUnit = function (will, now) {
    var addLat = will.lat () - now.lat ();
    var addLng = will.lng () - now.lng ();
    var aveAdd = ((Math.abs (addLat) + Math.abs (addLng)) / 2);
    var unit = aveAdd < 10 ? aveAdd < 1 ? aveAdd < 0.1 ? aveAdd < 0.01 ? aveAdd < 0.001 ? aveAdd < 0.0001 ? 3 : 6 : 9 : 12 : 15 : 24 : 21;
    var lat = addLat / unit;
    var lng = addLng / unit;

    if (!((Math.abs (lat) > 0) || (Math.abs (lng) > 0)))
      return null;

    return {
      unit: unit,
      lat: lat,
      lng: lng
    };
  };
  var mapMove = function (unitLat, unitLng, unitCount, unit, callback) {
    if (unit > unitCount) {
      _map.setCenter (new google.maps.LatLng (_map.getCenter ().lat () + unitLat, _map.getCenter ().lng () + unitLng));
      setTimeout (function () {
        mapMove (unitLat, unitLng, unitCount + 1, unit, callback);
      }, 50);
    } else {
      if (callback)
        callback ();
    }
  };

  var mapGo = function (will, callback) {
    var now = _map.getCenter ();

    var Unit = getUnit (will, now);
    if (!Unit)
      return false;

    mapMove (Unit.lat, Unit.lng, 0, Unit.unit, callback);
  };

  function updateLatLng (position) {
    if (!$latlng_bar.hasClass ('show'))
      $latlng_bar.addClass ('show');
    
    $lat.text ('緯度：' + position.lat ()).data ('val', position.lat ());
    $lng.text ('經度：' + position.lng ()).data ('val', position.lng ());
    mapGo (position);
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
        zoom: 13,
        minZoom: 7,
        scaleControl: true,
        navigationControl: true,
        disableDoubleClickZoom: true,
        mapTypeControl: false,
        zoomControl: true,
        scrollwheel: true,
        streetViewControl: false,
        center: new google.maps.LatLng (25, 121.5),
      };

    _map = new google.maps.Map ($map.get (0), option);
    _map.mapTypes.set ('map_style', styledMapType);
    _map.setMapTypeId ('map_style');

    var last = getStorage ();
    if (last) {
      _map.setCenter (new google.maps.LatLng (last.lat, last.lng));
      _map.setZoom (last.zoom);
    } else {
      navigator.geolocation.getCurrentPosition (function (position) {
        _map.setZoom (14);
        mapGo (new google.maps.LatLng (position.coords.latitude, position.coords.longitude), setStorage);
      });
    }

    google.maps.event.addListener(_map, 'zoom_changed', getWeathers);
    google.maps.event.addListener(_map, 'dragend', getWeathers);

    google.maps.event.addListener(_map, 'click', function (e) {
      initMarker (e.latLng);
    });

    if ($lat.data ('val') && $lng.data ('val')) {
      _map.setCenter (new google.maps.LatLng ($lat.data ('val'), $lng.data ('val')));
      initMarker (_map.center);
    }
    
    $('#fm').submit (function () {
      if (!($lat.data ('val') && $lng.data ('val'))) {
        $('.error').text ('請點選地圖，選擇地點!').addClass ('show');
        return false;
      }
      $(this).append ($('<input />').attr ('type', 'hidden').attr ('name', 'latitude').val ($lat.data ('val')));
      $(this).append ($('<input />').attr ('type', 'hidden').attr ('name', 'longitude').val ($lng.data ('val')));

      if (!(($('#title').val ().length > 0) && ($('#title').val ().length < 201))) {
        $('.error').text ('輸入 1~200 個字元!').addClass ('show');
        return false;
      }
      return true;
    });

    $('.weathers').map (function () {
      new MarkerWithLabel ({
        position: new google.maps.LatLng ($(this).data ('lat'), $(this).data ('lng')),
        draggable: false,
        raiseOnDrag: false,
        clickable: true,
        labelContent: $(this).val (),
        labelAnchor: new google.maps.Point (50, 0),
        labelClass: "marker_label",
        map: _map,
        icon: '/resource/image/spotlight-poi-blue.png'
      });
    });
    
    $loading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
        getWeathers();
      });
    });
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});