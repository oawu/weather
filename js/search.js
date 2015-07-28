/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var hash = window.location.hash.trim ().slice (1);
  window.onhashchange = function () {
    location.reload ();
  };
  var _timer = null
  $container = $('#container');

  var $input = $('<input />').addClass ('search').attr ('type', 'text').attr ('placeholder', '快來搜尋一下天氣吧！').val (decodeURIComponent (hash)).keyup (function (e) {
    if ((e.keyCode == 13) && $(this).val ().trim ().length)
      window.location.hash = encodeURIComponent ($(this).val ().trim ());
  });
  var $button = $('<button />').addClass ('go_search').text ('搜尋').click (function () {
    if ($input.val ().trim ().length)
      window.location.hash = encodeURIComponent ($input.val ().trim ());
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
    clearTimeout (_timer);

    _timer = setTimeout (function () {
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
                            window.location.hash = encodeURIComponent ($(this).text ());
                          });
                        }))).appendTo ($container);
        setTimeout (towns.bind (this, $noData.find ('a'), 0), 100);
        
        window.closeLoading ();
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
    var markerWithLabel = initWeatherFeature (result, map, true);
    map.setCenter (markerWithLabel.position);
    markerWithLabel.setMap (map);

    google.maps.event.addListener(map, 'zoom_changed', getWeathers.bind (this, map, markerWithLabel.id, $loadingData, false));
    google.maps.event.addListener(map, 'idle', getWeathers.bind (this, map, markerWithLabel.id, $loadingData, false));
    
    window.closeLoading ();
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

  getLocalInfo (function (result) {
    $weather.empty ().append ($(result.weather));
  }, function () {
    $search.addClass ('no_weather');
  });
});