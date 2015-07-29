/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var hash = window.location.hash.trim ().slice (1);
  var changeWidth = 800;

  window.onhashchange = function () {
    location.reload ();
  };

  if (!hash) {
    window.location.assign ('index.html');
    return;
  }

  var $container = $('#container');
  var hasClickPanorama = false;

  var $infos = null;
  var $info = null;
  var $mapPanel = null;
  var $map = null;
  var $view = null;
  var $loadingData = null;
  var $viewButton = null;
  var $content = null;
  var $describe = null;
  var $humidity = null;
  var $rainfall = null;
  var $createdAt = null;
  var $special = null;
  
  var $specialStatus = null;
  var $specialIcon = null;
  var $specialDescribe = null;
  var $specialAt = null;
  var $tempers = null;
  var $times = null;
  var $details = null;

  var $moreTowns = null;
  var $towns = null;
  var $moreLoading = null;

  function initUI (town) {
    $('title').text (town.name + '的天氣概況 - Weather Maps');

    $map = $('<div />').attr ('id', 'map');
    $view = $('<div />').attr ('id', 'view');
    $loadingData = $('<div />').addClass ('loading_data').text ('資料讀取中..');
    $viewButton = $('<botton />').addClass ('view_button');
    $mapPanel = $('<div />').addClass ('map').append (
                  Array.apply (null, Array (4)).map (function (_, i) { return $('<i />'); })).append (
                  $map).append (
                  $view).append (
                  $loadingData).append (
                  $viewButton);

    $content = $('<div />').addClass ('l');
    $describe = $('<div />').addClass ('describe');
    $humidity = $('<div />').addClass ('humidity');
    $rainfall = $('<div />').addClass ('rainfall');
    $createdAt = $('<div />').addClass ('created_at');

    $specialStatus = $('<h2 />');
    $specialIcon = $('<img />');
    $specialDescribe = $('<div />').addClass ('describe');
    $specialAt = $('<div />').addClass ('created_at');
    $special = $('<div />').addClass ('special').append ($specialStatus).append ($('<div />').addClass ('describes').append ($specialIcon).append ($specialDescribe)).append ($specialAt);

    $tempers = $('<div />').addClass ('tempers');
    $times = $('<div />').addClass ('times');
    $details = $('<div />').addClass ('details').append (
                $tempers).append (
                $times);
    $info = $('<div />').addClass ('info').append (
              $('<div />').addClass ('weather').append (
                $content).append (
                $('<div />').addClass ('r').append (
                  $describe).append (
                  $('<div />').addClass ('sub_describe').append (
                    $humidity).append (
                    $rainfall)).append (
                  $createdAt))).append (
              $special).append (
              $details);
    $infos = $('<div />').addClass ('infos').append ($mapPanel).append ($info);

    $container.prepend ($infos);
    
    $towns = $('<div />').addClass ('towns');
    $moreLoading = $('<div />').addClass ('loading');
    $moreTowns = $('<div />').addClass ('more_towns').append (
      $('<h2 />').text ('更多地方天氣！')).append (
      $towns).append (
      $moreLoading);
    
    $container.append ($moreTowns);

  }
  function initTown (town) {
    var panorama = null;
    var map = null;

    initUI (town);

    if (town.view)
      panorama = new google.maps.StreetViewPanorama ($view.get (0), {
                    linksControl: true,
                    addressControl: false,
                    position: new google.maps.LatLng (town.view.lat, town.view.lng),
                    pov: {
                      heading: town.view.heading,
                      pitch: town.view.pitch,
                      zoom: town.view.zoom,
                    }
                  });

    map = new google.maps.Map ($map.get (0), {
            zoom: $(window).height () < changeWidth ? 11 : 12,
            zoomControl: true,
            scrollwheel: true,
            scaleControl: true,
            streetView: panorama ? panorama : null,
            mapTypeControl: false,
            navigationControl: true,
            streetViewControl: false,
            disableDoubleClickZoom: true,
            center: new google.maps.LatLng (town.lat, town.lng),
          });
    
    var markerWithLabel = initWeatherFeature (town, map, false);
    map.setCenter (markerWithLabel.position);
    markerWithLabel.setMap (map);

    google.maps.event.addListener(map, 'zoom_changed', getWeathers.bind (this, map, town.id, $loadingData, false));
    google.maps.event.addListener(map, 'idle', getWeathers.bind (this, map, town.id, $loadingData, false));

    $content.append ($(town.content));
    $describe.text (town.weather.describe);
    $humidity.text ('溫濕度：' + town.weather.humidity + '%');
    $rainfall.text ('降雨量：' + town.weather.rainfall + 'mm');
    $createdAt.text (town.weather.created_at).data ('time', town.weather.created_at).timeago ();

    if (town.weather.special.length === 0) {
      $special.remove ();
      $details.addClass ('heighter');
    } else {
      $specialStatus.text (town.weather.special.status + '特報');
      $specialIcon.attr ('src', town.weather.special.icon);
      $specialDescribe.text (town.weather.special.describe);
      $specialAt.text (town.weather.special.at).data ('time', town.weather.special.at).timeago ();
    }
    
    if (town.weathers) {
      $details.addClass ('c' + town.weathers.length);
      $times.append (town.weathers.map (function (t) {
        return $('<div />').addClass ('time').text (t.hour);
      }));
      
      var max = town.weathers.max ('temperature');
      var min = (town.weathers.min ('temperature') * 3 - max) / 2;
      $tempers.append (town.weathers.column ('temperature').map (function (t) {
        return $('<div />').addClass ('temper').data ('height', 'calc((100% - 10px) * ' + (t - min) / (max - min) + ')').append ($('<div />').text (t + '°c')).append ($('<div />'));
      }));
    } else {
      $details.remove ();
    }

    $(window).scroll (function () {
      if ($tempers.data ('has_loaded') || ($(this).scrollTop () + $(this).height () < $tempers.offset ().top))
        return;
        
        $tempers.data ('has_loaded', true);

        clearTimeout ($tempers.timer);
        $tempers.timer = setTimeout (function () {
          $tempers.find ('.temper').each (function () {
            $(this).css ('height', $(this).data ('height'));
          });
        }, 1000);

    }).scroll (function () {
      if ($moreTowns.data ('has_loaded') || ($(this).scrollTop () + $(this).height () < $moreLoading.offset ().top - 10))
        return;

        $moreTowns.data ('has_loaded', true);

        $.ajax ({
          url: window.api.getMoreTownsUrl,
          data: { id: town.id },
          async: true, cache: false, dataType: 'json', type: 'POST',
          beforeSend: function () { }
        })
        .done (function (result) {
          if (!result.status)
            return $moreTowns.remove ();

            var now = new Date ().getTime ();
            var ids = getStorage ('weather_maps_viewed');
            if (!ids) ids = [];
            ids = ids.filter (function (t) { return now - t.t < 1 * 86400 * 1000; });

            var obj = [{id: town.id, t: now}];
            ids = ids.diff (obj, 'id');
            var add = obj.diff (ids, 'id');
            ids = ids.concat (add);
            setStorage ('weather_maps_viewed', ids);

            ids = ids.map (function (t) { return parseInt (t.id, 10); });
          $towns.append (result.towns.map (function (t) {
            return $('<a />').addClass ($.inArray (t.id, ids) != -1 ? 'viewed' : null).attr ('href', window.url + 'town.html#' + encodeURIComponent (t.id)).addClass ('town').append ($('<img />').attr ('src', t.src)).append ($('<div />').addClass ('name').text (t.name)).click (function () { window.location.hash = encodeURIComponent (t.id); }).append ($('<div />').addClass ('tick').addClass ('icon-uniE63B'));
          })).find ('a').imgLiquid ({verticalAlign: 'center'});

          $moreLoading.remove ();
        })
        .fail (function (result) { ajaxError (result); })
        .complete (function (result) {});

    }).scroll ().resize (function () {
      if ($(this).width () > changeWidth)
        $mapPanel.css ({'height': $info.height ()});
      else
        $mapPanel.removeAttr ('style');
    }).resize ();

    if (panorama) {
      $viewButton.click (function () {
        hasClickPanorama = true;
        if ($mapPanel.hasClass ('panorama'))
          $mapPanel.removeClass ('panorama');
        else
          $mapPanel.addClass ('panorama');
      });
      if (!hasClickPanorama)
        setTimeout (function () {
          if (hasClickPanorama)
            return;
          else
            $viewButton.click ();
        }, 2000);
    } else {
      $viewButton.remove ();
    }

    window.closeLoading ();
  }

  $.ajax ({
    url: window.api.getTownUrl,
    data: { key: hash },
    async: true, cache: false, dataType: 'json', type: 'POST',
    beforeSend: function () {}
  })
  .done (function (result) {
    if (result.status)
      initTown (result.town);
    else
      window.location.assign ('index.html');
  })
  .fail (function (result) { ajaxError (result); })
  .complete (function (result) {});

});