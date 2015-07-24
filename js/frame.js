/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

window.url = 'http://comdan66.github.io/weather/';
window.getWeathersUrl = 'http://weather.ioa.tw/api/github/get_weathers/';
window.getTownsUrl = 'http://weather.ioa.tw/api/github/get_towns/';
window.getWeatherByNameUrl = 'http://d.weather.ioa.tw/api/github/get_weather_by_name/';
window.getWeatherByPostalCodeUrl = 'http://weather.ioa.tw/api/github/get_weather_by_postal_code/';

function initWeatherFeature (t, map) {
  var markerWithLabel = new MarkerWithLabel ({
                          id: t.id,
                          position: new google.maps.LatLng (t.lat, t.lng),
                          draggable: false,
                          raiseOnDrag: false,
                          clickable: true,
                          labelContent: t.c,
                          labelAnchor: new google.maps.Point (65, 130),
                          labelClass: "marker_label",
                          icon: {path: 'M 0 0'},
                          initCallback: function (t) {
                            $(t).find ('.icon').imgLiquid ({verticalAlign: 'center'});
                          }
                        });
  google.maps.event.addListener (markerWithLabel, 'click', function () {
    if (map.zoom < 13) {
      mapGo (map, new google.maps.LatLng (t.lat, t.lng), function (map) {
        setStorage.apply (this, ['weather_maps_last', {
          lat: map.center.lat (),
          lng: map.center.lng (),
          zoom: map.zoom
        }]);
        map.setZoom (map.zoom + 1);
      });
    } else {
      
    }
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
      url: window.getWeathersUrl,
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
              markerWithLabel: initWeatherFeature (t, map)
            };
          });


          var deletes = map.markers.diff (markers);
          var adds = markers.diff (map.markers);
          var delete_ids = deletes.map (function (t) { t.markerWithLabel.setMap (null); return t.id; });
          var add_ids = adds.map (function (t) { t.markerWithLabel.setMap (map); return t.id; });

          map.markers = map.markers.filter (function (t) { return $.inArray (t.id, delete_ids) == -1; }).concat (markers.filter (function (t) { return $.inArray (t.id, add_ids) != -1; }));

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

Array.prototype.diff = function (a) {
  return this.filter (function (i) { return a.map (function (t) { return t.id; }).indexOf (i.id) < 0; });
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
    ],
    right: [
      {name: '關於', file: 'about.html', target: '_self'},
    ]
  };

  var footInfos = [
    "Weather Maps © 2015",
    "如有相關問題歡迎與<a href='https://www.facebook.com/' target='_blank'>作者</a>討論。",
  ];

  var sideLinks = links.left.concat (links.right);
  if (!sideLinks.length) return;

  var now = document.URL.replace (/^.*[\\\/]/, '');
  var nowLink = sideLinks.filter (function (t) { return t.file == now; });
  if (nowLink.length && (nowLink = nowLink[0]))
    $('title').text (nowLink.name);

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
                              return $('<a />').addClass (t.file == now ? 'active' : null).attr ('href', window.url + t.file).attr ('target', t.target).text (t.name);
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
});