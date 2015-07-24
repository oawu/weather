/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var hash = window.location.hash.trim ().slice (1);
  window.onhashchange = function () {
    location.reload ();
  };
  
  $container = $('#container');

  var $input = $('<input />').addClass ('search').attr ('type', 'text').attr ('placeholder', '快來搜尋一下天氣吧！').val (decodeURIComponent (hash)).keyup (function (e) {
    if ((e.keyCode == 13) && $(this).val ().trim ().length)
      window.location.hash = $(this).val ().trim ();
  });
  var $button = $('<button />').addClass ('go_search').text ('搜尋').click (function () {
    if ($input.val ().trim ().length)
      window.location.hash = $input.val ().trim ();
  });
  
  var $weather = $('<div />').addClass ('r').append (
        $('<div />').addClass ('loading'));

  var $search = $('<div />').attr ('id', 'search').append (
                  $('<a />').attr ('href', window.url + 'index.html').addClass ('l').append (
                    $('<div />').addClass ('logo').append (
                      $('<span />').text ('Weather'))).append (
                    $('<div />').addClass ('title').text ('Maps'))).append (
                  $('<div />').addClass ('c').append (
                    $input).append (
                    $button)).append (
                    $weather).prependTo ($container);

  function towns ($a, i) {
    var $tags = $a.slice (i, i + 5).removeClass ('back').addClass ('start');
    setTimeout (function () {
      $tags.addClass ('end');
      setTimeout (towns.bind (this, $a, (i + 5) % 10), 100);
      setTimeout (function () {
        $tags.addClass ('back').removeClass ('start end');
      }, 1500);
    }, 5000);
  }
  function initNoData () {
    $.ajax ({
      url: window.api.getTownsUrl,
      data: { },
      async: true, cache: false, dataType: 'json', type: 'GET',
      beforeSend: function () {}
    })
    .done (function (result) {
      if (result.status) {
        var $noData = $('<div />').attr ('id', 'no_data').addClass ('show').append (
                        $('<div />').addClass ('title').text ('您可以試著搜尋..')).append (
                        $('<div />').addClass ('towns').append (result.towns.map (function (t) {
                          return $('<a />').text (t.name).click (function () {
                            window.location.hash = $(this).text ();
                          });
                        }))).appendTo ($container);
        setTimeout (towns.bind (this, $noData.find ('a'), 0), 100);
        
        window.mainLoading.fadeOut (function () {
          $(this).hide (function () {
            $(this).remove ();
          });
        });
      }
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  }
  function initMap (result) {
    $map = $('<div />').attr ('id', 'map');
    $loadingData = $('<div />').addClass ('loading_data').text ('資料讀取中..');

    $('<div />').addClass ('map').append (
      Array.apply (null, Array (4)).map (function (_, i) { return $('<i />'); })).append (
      $map).append (
      $loadingData).appendTo ($container);

    var map = new google.maps.Map ($map.get (0), {
                zoom: 14,
                zoomControl: true,
                scrollwheel: true,
                scaleControl: true,
                mapTypeControl: false,
                navigationControl: true,
                streetViewControl: false,
                disableDoubleClickZoom: true
              });
    var markerWithLabel = initWeatherFeature (result, map);
    map.setCenter (markerWithLabel.position);
    markerWithLabel.setMap (map);

    google.maps.event.addListener(map, 'zoom_changed', getWeathers.bind (this, map, markerWithLabel.id, $loadingData, false));
    google.maps.event.addListener(map, 'idle', getWeathers.bind (this, map, markerWithLabel.id, $loadingData, false));
    
    window.mainLoading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
      });
    });
  }

  if (hash.length)
    $.ajax ({
      url: window.api.getWeatherByNameUrl,
      data: { name: hash },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {}
    })
    .done (function (result) {
      if (result.status)
        initMap (result.weather);
      else
        initNoData ();
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  else
    initNoData ();


  function initWeather (postal_code) {
    $.ajax ({
      url: window.api.getWeatherByPostalCodeUrl,
      data: { postal_code: postal_code },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {}
    })
    .done (function (result) {
      if (result.status)
        $weather.empty ().append ($(result.weather.c));
      else
        $search.addClass ('no_weather');
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  }

  navigator.geolocation.getCurrentPosition (function (position) {
    new google.maps.Geocoder ().geocode ({'latLng': new google.maps.LatLng (position.coords.latitude, position.coords.longitude)}, function (result, status) {
      var postal_code = [];
      if ((status == google.maps.GeocoderStatus.OK) && result.length && (result = result[0]))
        postal_code = result.address_components.map (function (t) { return t.types.length && ($.inArray ('postal_code', t.types) !== -1) ? t.long_name : null; }).filter (function (t) { return t; });
      postal_code = postal_code.length ? postal_code[0] : '';

      initWeather (postal_code);
    });
  }, function () {
    initWeather (100);
  });
});