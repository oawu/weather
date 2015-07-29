/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var townCategory = '雲林縣';
  var townName = '北港鎮';

  var $container = $('#container');

  function initError () {
    $('<div />').addClass ('no_data').append ('系統維修中，已經聯絡工程師，他正在爆肝中..').append ($('<br />')).append ('請稍後一下下再試試吧：）').appendTo ($container);
  }

  function delUnit () {
    var postal_codes = getStorage ('weather_maps_follow_postal_codes');
    if (!postal_codes) postal_codes = [];

    setStorage ('weather_maps_follow_postal_codes', postal_codes.filter (function (t) {
      return t.id != $(this).data ('id');
    }.bind ($(this))));
    location.reload ();
  }
  function addUnit () {
    var name = prompt ('輸入您想追蹤的地點：', townCategory + ' ' + townName);
    
    if (!name)
      return;
    else
      name = name.trim ();
    
    if (name.length) {
      $.ajax ({
        url: window.api.getWeatherByNameUrl,
        data: {
          name: name
        },
        async: true, cache: false, dataType: 'json', type: 'POST',
        beforeSend: function () {}
      })
      .done (function (result) {
        if (!result.status) {
          alert ('取得地點失敗！');
          return;
        }
        var postal_codes = getStorage ('weather_maps_follow_postal_codes');
        if (!postal_codes) postal_codes = [];
        var obj = [{id: result.weather.id}];
        postal_codes = postal_codes.diff (obj, 'id');
        var add = obj.diff (postal_codes, 'id');
        postal_codes = postal_codes.concat (add);
        setStorage ('weather_maps_follow_postal_codes', postal_codes);
        location.reload ();
      })
      .fail (function (result) { ajaxError (result); })
      .complete (function (result) {});
    }
  }

  function initUI (specials, units) {
    var $units = $('<div />').addClass ('units').appendTo ($container);

    if (specials.length < 1 && units.length < 1)
      return initError ();
    
    if (units.length > 0)
      $units.append (units.map (function (t) {
        return $('<div />').addClass ('unit').append ($('<h2 />').text (t.title)).append ($('<a />').attr ('href', 'town.html#' + encodeURIComponent (t.info.id)).append ($('<div />').addClass ('l').append ($(t.info.content))).append ($('<div />').addClass ('r').append ($('<div />').addClass ('describe').text (t.info.weather.describe)).append ($('<div />').addClass ('sub_describe').append ($('<div />').addClass ('humidity').text ('溫濕度：' + t.info.weather.humidity + '%')).append ($('<div />').addClass ('rainfall').text ('降雨量：' + t.info.weather.rainfall + 'mm'))).append ($('<div />').addClass ('created_at').data ('time', t.info.weather.created_at).text (t.info.weather.created_at).timeago ()))).append (t.info.add ? $('<div />').addClass ('del').html ('&#10006;').data ('id', t.info.id).click (delUnit) : null);
      }));

    if (typeof (Storage) !== 'undefined')
      $('<div />').addClass ('unit').addClass ('add').append (
        Array.apply (null, Array (5)).map (function (_, i) { return $('<div />').text (i == 4 ? '新增關注追蹤地區！' : ''); })).click (addUnit).appendTo ($units);

    if (specials.length > 0) {
      $('<div />').addClass ('line').append ($('<div />')).append ($('<div />').text ('特報')).append ($('<div />')).appendTo ($container);
      
      var $specials = $('<div />').addClass ('specials').append (specials.map (function (t) {
        return $('<div />').addClass ('special').append ($('<h2 />').text (t.special.title + '特報')).append ($('<div />').addClass ('towns').append (t.towns.map (function (u) { return $('<a />').attr ('href', 'town.html#' + encodeURIComponent (u.id)).text (u.name); }))).append ($('<div />').addClass ('describe').text (t.special.describe).prepend ($('<img />').attr ('src', t.special.icon))).append ($('<div />').addClass ('at').data ('time', t.special.at).text (t.special.at).timeago ());
      })).appendTo ($container);

      new Masonry ($specials.get (0), { itemSelector: '.special', columnWidth: 1, transitionDuration: '0.3s', visibleStyle: { opacity: 1, transform: 'none' }});
    }

    window.closeLoading ();
  }
  
  getLocalInfo (function (result) {
    townCategory = result.town.category;
    townName = result.town.name;
  });

  $.ajax ({
    url: window.api.getIndexData,
    data: { postal_codes: getStorage ('weather_maps_follow_postal_codes') },
    async: true, cache: false, dataType: 'json', type: 'POST',
    beforeSend: function () {}
  })
  .done (function (result) {
    if (result.status)
      initUI (result.specials, result.units);
    else
      initError ();
  })
  .fail (function (result) { ajaxError (result); })
  .complete (function (result) {});
});