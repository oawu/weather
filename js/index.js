/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $container = $('#container');


  function initUI (specials, units) {
    if (units.length > 0)
      $('<div />').addClass ('units').append (units.map (function (t) {
        return $('<a />').attr ('href', 'town.html#' + encodeURIComponent (t.info.id)).append ($('<h2 />').text (t.title)).append ($('<div />').addClass ('content').append ($('<div />').addClass ('l').append ($(t.info.content))).append ($('<div />').addClass ('r').append ($('<div />').addClass ('describe').text (t.info.weather.describe)).append ($('<div />').addClass ('sub_describe').append ($('<div />').addClass ('humidity').text ('溫濕度：' + t.info.weather.humidity + '%')).append ($('<div />').addClass ('rainfall').text ('降雨量：' + t.info.weather.rainfall + 'mm'))).append ($('<div />').addClass ('created_at').data ('time', t.info.weather.created_at).text (t.info.weather.created_at).timeago ())));
      })).appendTo ($container);

    if (specials.length > 0) {
      $('<div />').addClass ('line').append ($('<div />')).append ($('<div />').text ('特報')).append ($('<div />')).appendTo ($container);
      
      var $specials = $('<div />').addClass ('specials').append (specials.map (function (t) {
        return $('<div />').addClass ('special').append ($('<h2 />').text (t.special.title + '特報')).append ($('<div />').addClass ('towns').append (t.towns.map (function (u) { return $('<a />').attr ('href', 'town.html#' + encodeURIComponent (u.id)).text (u.name); }))).append ($('<div />').addClass ('describe').text (t.special.describe).prepend ($('<img />').attr ('src', t.special.icon))).append ($('<div />').addClass ('at').data ('time', t.special.at).text (t.special.at).timeago ());
      })).appendTo ($container);
      var masonry = new Masonry ($specials.get (0), {
                      itemSelector: '.special',
                      columnWidth: 1,
                      transitionDuration: '0.3s',
                      visibleStyle: {
                        opacity: 1,
                        transform: 'none'
                      }});

    }
    window.mainLoading.fadeOut (function () {
      $(this).hide (function () {
        $(this).remove ();
      });
    });
  }

  $.ajax ({
    url: window.api.getIndexData,
    data: { },
    async: true, cache: false, dataType: 'json', type: 'POST',
    beforeSend: function () {}
  })
  .done (function (result) {
    if (result.status)
      initUI (result.specials, result.units);
    else
    console.error ('x');
  })
  .fail (function (result) { ajaxError (result); })
  .complete (function (result) {});


});