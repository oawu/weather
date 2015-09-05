/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

// var ENVIRONMENT = 'dev';
var ENVIRONMENT = 'production';

if (ENVIRONMENT == 'dev') {
  window.url = 'http://dev.comdan66.github.io/weather/';
  window.api = {
    getTownUrl: 'http://dev.weather.ioa.tw/api/github/get_town/',
    getTownsUrl: 'http://dev.weather.ioa.tw/api/github/get_towns/',
    getIndexData: 'http://dev.weather.ioa.tw/api/github/get_index_data/',
    getWeathersUrl: 'http://dev.weather.ioa.tw/api/github/get_weathers/',
    getMoreTownsUrl: 'http://dev.weather.ioa.tw/api/github/get_more_town/',
    getSatellitesUrl: 'http://dev.weather.ioa.tw/api/github/get_satellites/',
    getWeatherByNameUrl: 'http://dev.weather.ioa.tw/api/github/get_weather_by_name/',
    getWeatherContentByPostalCodeUrl: 'http://dev.weather.ioa.tw/api/github/get_weather_content_by_postal_code/',
  };
} else {
  window.url = 'http://comdan66.github.io/weather/';
  window.api = {
    getTownUrl: 'http://weather.ioa.tw/api/github/get_town/',
    getTownsUrl: 'http://weather.ioa.tw/api/github/get_towns/',
    getIndexData: 'http://weather.ioa.tw/api/github/get_index_data/',
    getWeathersUrl: 'http://weather.ioa.tw/api/github/get_weathers/',
    getMoreTownsUrl: 'http://weather.ioa.tw/api/github/get_more_town/',
    getSatellitesUrl: 'http://weather.ioa.tw/api/github/get_satellites/',
    getWeatherByNameUrl: 'http://weather.ioa.tw/api/github/get_weather_by_name/',
    getWeatherContentByPostalCodeUrl: 'http://weather.ioa.tw/api/github/get_weather_content_by_postal_code/',
  };

  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-46121102-7', 'auto');
  ga('send', 'pageview');
}

function initLocalInfo (postal_code, isTrue, callback, errorCallback) {
  $.ajax ({
    url: window.api.getWeatherContentByPostalCodeUrl,
    data: { postal_code: postal_code },
    async: true, cache: false, dataType: 'json', type: 'POST',
    beforeSend: function () {}
  })
  .done (function (result) {
    if (result.status) {
        if (isTrue)
          setStorage ('weather_maps_last_postal_code', {code: postal_code, result: result, category: result.town.category, name: result.town.name, t: new Date ().getTime ()});
        if (callback)
          callback (result);
    }
    else
      if (errorCallback)
        errorCallback ();
  })
  .fail (function (result) { ajaxError (result); })
  .complete (function (result) {});
}

function getLocalInfo (callback, errorCallback) {
  var now = new Date ().getTime ();
  var postal_code = getStorage ('weather_maps_last_postal_code');

  if (postal_code && (now - postal_code.t < 60 * 60 * 1000)) {
    if (callback)
      callback (postal_code.result);
  } else
    navigator.geolocation.getCurrentPosition (function (position) {
      new google.maps.Geocoder ().geocode ({'latLng': new google.maps.LatLng (position.coords.latitude, position.coords.longitude)}, function (result, status) {
        var postal_code = [];
        if ((status == google.maps.GeocoderStatus.OK) && result.length && (result = result[0]))
          postal_code = result.address_components.map (function (t) { return t.types.length && ($.inArray ('postal_code', t.types) !== -1) ? t.long_name : null; }).filter (function (t) { return t; });

        postal_code = postal_code.length ? postal_code[0] : '';

        if (postal_code === '')
          initLocalInfo (100, false, callback, errorCallback);
        else {
          initLocalInfo (postal_code, true, callback, errorCallback);
        }
      });
    }, function () {
      initLocalInfo (100, false, callback, errorCallback);
    });
}
function initWeatherFeature (t, map, hasAction) {
  var markerWithLabel = new MarkerWithLabel ({
                          id: t.id,
                          position: new google.maps.LatLng (t.lat, t.lng),
                          draggable: false,
                          raiseOnDrag: false,
                          clickable: hasAction,
                          labelContent: t.content,
                          labelAnchor: new google.maps.Point (65, 100),
                          labelClass: "marker_label",
                          icon: {path: 'M 0 0'},
                          initCallback: function (t) {
                            $(t).find ('.icon').imgLiquid ({verticalAlign: 'center'});
                          }
                        });

  if (hasAction)
    google.maps.event.addListener (markerWithLabel, 'click', function () {
      mapGo (map, new google.maps.LatLng (t.lat, t.lng), function (map) {
        setStorage.apply (this, ['weather_maps_last', {
          lat: map.center.lat (),
          lng: map.center.lng (),
          zoom: map.zoom
        }]);
        map.setZoom (map.zoom + 1);
      });

      clearTimeout (map.toTownTimmer);
      map.toTownTimmer = setTimeout (function () {
        window.location.assign ('town.html#' + encodeURIComponent (t.id));
      }, 500);
    });
  return markerWithLabel;
}
function getWeathers (map, townId, $loadingData, notSaveLast) {
  clearTimeout (map.getWeathersTimer);

  map.getWeathersTimer = setTimeout (function () {
    if (map.isGetWeathers)
      return;

    if(!map.markers)
      map.markers = [];

    if ($loadingData)
      $loadingData.addClass ('show');
    map.isGetWeathers = true;

    var northEast = map.getBounds().getNorthEast ();
    var southWest = map.getBounds().getSouthWest ();

    $.ajax ({
      url: window.api.getWeathersUrl,
      data: { NorthEast: {latitude: northEast.lat (), longitude: northEast.lng ()},
              SouthWest: {latitude: southWest.lat (), longitude: southWest.lng ()},
              townId: townId ? townId : 0,
              zoom: map.zoom
            },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {}
    })
    .done (function (result) {

        if (result.status) {
          var markers = result.weathers.map (function (t) {
            return {
              id: t.id,
              markerWithLabel: initWeatherFeature (t, map, true)
            };
          });

          var deletes = map.markers.diff (markers, 'id');
          var adds = markers.diff (map.markers, 'id');
          var delete_ids = deletes.map (function (t) { t.markerWithLabel.setMap (null); return t.id; });
          var add_ids = adds.map (function (t) { t.markerWithLabel.setMap (map); return t.id; });

          map.markers = map.markers.filter (function (t) { return $.inArray (t.id, delete_ids) == -1; }).concat (markers.filter (function (t) { return $.inArray (t.id, add_ids) != -1; }));

          if ($loadingData)
            $loadingData.removeClass ('show');
          map.isGetWeathers = false;
        }
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {});
  }, 100);

  if (!notSaveLast)
    setStorage.apply (this, ['weather_maps_last', {
      lat: map.center.lat (),
      lng: map.center.lng (),
      zoom: map.zoom
    }]);
}
function getStorage (key) {
  if ((typeof (Storage) !== 'undefined') && (last = localStorage.getItem (key)) && (last = JSON.parse (last)))
    return last;
  else
    return;
}

function setStorage (key, data) {
  if (typeof (Storage) !== 'undefined') {
    localStorage.setItem (key, JSON.stringify (data));
  }
}

function getLastPosition (key) {
  if ((typeof (Storage) !== 'undefined') && (last = getStorage (key)) && (!isNaN (last.lat) && !isNaN (last.lng) && !isNaN (last.zoom) && (last.lat < 86) && (last.lat > -86)))
    return last;
  else
    return;
}

Array.prototype.column = function (k) {
  return this.map (function (t) { return k ? eval ("t." + k) : t; });
};
Array.prototype.diff = function (a, k) {
  return this.filter (function (i) { return a.column (k).indexOf (eval ("i." + k)) < 0; });
};
Array.prototype.max = function (k) {
  return Math.max.apply (null, this.column (k));
};
Array.prototype.min = function (k) {
  return Math.min.apply (null, this.column (k));
};

function getUnit (will, now) {
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
}

function mapMove (map, unitLat, unitLng, unitCount, unit, callback) {
  if (unit > unitCount) {
    map.setCenter (new google.maps.LatLng (map.getCenter ().lat () + unitLat, map.getCenter ().lng () + unitLng));
    clearTimeout (window.mapMoveTimer);
    window.mapMoveTimer = setTimeout (function () {
      mapMove (map, unitLat, unitLng, unitCount + 1, unit, callback);
    }, 25);
  } else {
    if (callback)
      callback (map);
  }
}

function mapGo (map, will, callback) {
  var now = map.center;

  var Unit = getUnit (will, now);
  if (!Unit)
    return false;

  mapMove (map, Unit.lat, Unit.lng, 0, Unit.unit, callback);
}
window.ajaxError = function (result) {
  console.error (result.responseText);
};

$(function () {
  var title = 'Weather Maps';
  var links = {
    left: [
      {name: '首頁', file: 'index.html', target: '_self'},
      {name: '搜尋', file: 'search.html', target: '_self'},
      {name: '地圖', file: 'maps.html', target: '_self'},
    ],
    right: [
      {name: '關於', file: 'about.html', target: '_self'},
      {name: '更多', file: 'http://comdan66.github.io/index.html', target: '_blank'},
    ]
  };

  var footInfos = [
    "Weather Maps © 2015",
    "如有相關問題歡迎與<a href='https://www.facebook.com/comdan66' target='_blank'>作者</a>討論。",
  ];

  var sideLinks = links.left.concat (links.right);
  if (!sideLinks.length) return;

  var now = document.URL.replace (/^.*[\\\/]/, '');
  var nowLink = sideLinks.filter (function (t) { return t.file == now; });
  if (nowLink.length && (nowLink = nowLink[0]))
    $('title').text (nowLink.name + ' - Weather Maps');

  var $body = $('body');
  var overflow = $body.css ('overflow');
  var $option = null;

  var $header = $('<div />').attr ('id', 'header').append ($('<div />').addClass ('header_container')
                            .append ($('<div />').addClass ('l').append ($('<a />').addClass ('home').addClass ('icon-home').attr ('href', window.url + 'index.html').attr ('target', '_self')).append (links.left.map (function (t) {
                              return $('<a />').addClass (t.file == now ? 'active' : null).attr ('href', window.url + t.file).attr ('target', t.target).text (t.name);
                            })))
                            .append ($('<div />').addClass ('c').text (title).click (function () { $('html, body').stop ().animate ({ scrollTop: 0 - 50 }, 500); }))
                            .append ($('<div />').addClass ('r').append ($option = $('<a />').addClass ('option').addClass ('icon-th-menu').click (function () {
                              if ($headerRightSlide.hasClass ('close')) {
                                $headerRightSlide.removeClass ('close');
                                $('body').css ('overflow', 'hidden');
                                $(this).addClass ('close');
                              } else {
                                $headerRightSlide.addClass ('close');
                                $('body').css ('overflow', overflow);
                                $(this).removeClass ('close');
                              }}))
                            .append (links.right.map (function (t) {
                              var regex = /(http(s?))\:\/\//gi;
                              return $('<a />').addClass (t.file == now ? 'active' : null).attr ('href', regex.test (t.file) ? t.file : window.url + t.file).attr ('target', t.target).text (t.name);
                            })))).prependTo ($body);

  var $headerSlideCover = $('<div />').attr ('id', 'header_slide_cover').click (function () {
                            if (!$headerRightSlide.hasClass ('close')) {
                              $headerRightSlide.addClass ('close');
                              $('body').css ('overflow', overflow);
                              $option.removeClass ('close');
                            }
                          }).prependTo ($body);

  var $headerRightSlide = $('<div />').attr ('id', 'header_right_slide').addClass ('close').append ($('<div />').addClass ('right_slide_container').append (sideLinks.map (function (t) {
                             return $('<a />').addClass (t.file == now ? 'active' : null).addClass ('sub').attr ('href', window.url + t.file).attr ('target', t.target).text (t.name);
                           }))).prependTo ($body);

  var $footer = $('<div />').attr ('id', 'footer').append ($('<div />').addClass ('l')).append ($('<div />').addClass ('c').append (footInfos.map (function (t) {
                  return $('<div />').html (t);
                }))).append ($('<div />').addClass ('r')).appendTo ($body);

  window.mainLoading = $('<div />').attr ('id', 'main_loading').append ($('<div />')).appendTo ($body);

  window.closeLoading = function () {
    this.mainLoading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
      });
    });
  };
});