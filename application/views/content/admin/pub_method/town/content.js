/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var enableUpdateTown = false;

  var $map = $('#map');
  var $bound = $('#bound');
  var $marker = $('#marker');
  var $loadingData = $('#loading_data');
  var $zoom = $('#zoom');

  var _map = null;
  var _update = false;

  function updateTown (id, position) {
    if (_update)
      return;

    _update = true;

    new google.maps.Geocoder ().geocode ({'latLng': position}, function (result, status) {
      var name = result.address_components.map (function (t) {
                    return t.types.length && ($.inArray ('administrative_area_level_3', t.types) !== -1) ? t.long_name : null;
                  }).filter (function (t) { return t; });
      var postal_code = result.address_components.map (function (t) {
                    return t.types.length && ($.inArray ('postal_code', t.types) !== -1) ? t.long_name : null;
                  }).filter (function (t) { return t; });

      name = name.length ? name[0] : '';
      postal_code = postal_code.length ? postal_code[0] : '';

      $.ajax ({
        url: $('#update_town_position_url').val (),
        data: {
          id: id,
          lat: position.lat (),
          lng: position.lng (),
          name: name,
          postal_code: postal_code
        },
        async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () { }
      })
      .done (function (result) {})
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) {
        _update = false;
      });
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
    _map.mapTypes.set ('map_style', styledMapType);
    _map.setMapTypeId ('map_style');

    marker.setMap (_map);

    // if ($bound.length) {
    //   new google.maps.Polygon ({
    //     paths: [
    //       new google.maps.LatLng ($bound.data ('northeast_latitude'), $bound.data ('northeast_longitude')),
    //       new google.maps.LatLng ($bound.data ('southwest_latitude'), $bound.data ('northeast_longitude')),
    //       new google.maps.LatLng ($bound.data ('southwest_latitude'), $bound.data ('southwest_longitude')),
    //       new google.maps.LatLng ($bound.data ('northeast_latitude'), $bound.data ('southwest_longitude'))
    //     ],
    //     strokeColor: 'rgba(255, 0, 0, 1)',
    //     strokeOpacity: 0.2,
    //     strokeWeight: 1,
    //     fillColor: 'rgba(255, 0, 0, 1)',
    //     fillOpacity: 0.1,
    //     map: _map
    //   });

    //   new google.maps.Circle ({
    //     strokeColor: '#FF0000',
    //     strokeOpacity: 0.2,
    //     strokeWeight: 1,
    //     fillColor: '#FF0000',
    //     fillOpacity: 0.1,
    //     map: _map,
    //     center: new google.maps.LatLng (($bound.data ('northeast_latitude') + $bound.data ('southwest_latitude')) / 2, ($bound.data ('northeast_longitude') + $bound.data ('southwest_longitude')) / 2),
    //     radius: Math.max (Math.abs ($bound.data ('northeast_latitude') - $bound.data ('southwest_latitude')), Math.abs ($bound.data ('northeast_longitude') - $bound.data ('southwest_longitude'))) * 111 * 1000 / 2
    //   });
    // }

    google.maps.event.addListener (marker, 'dragend', function () {
      updateTown ($marker.val (), marker.position);
    });

    google.maps.event.addListener(_map, 'zoom_changed', getTowns.bind (this, _map, $marker.val (), $loadingData, true, $zoom));
    google.maps.event.addListener(_map, 'idle', getTowns.bind (this, _map, $marker.val (), $loadingData, true, $zoom));
    
    getTowns ( _map, $marker.val (), $loadingData, true, $zoom);
  }

  google.maps.event.addDomListener (window, 'load', initialize);
});