/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
    var $about = $('#about');
    var $more = $('#more');
    var $popUp = $('#pop_up');

    var popUp = [{t: '關於 Weather Maps', is: [
          {t: '作者名稱', h: 'http://www.ioa.tw', c: 'OA Wu'},
          {t: 'E-mail', h: '', c: 'comdan66@gmail.com'},
          {t: '作品名稱', h: '', c: 'Weather Maps'},
          {t: '最新版本', h: '', c: '1.0.0'},
          {t: 'GitHub', h: 'https://github.com/comdan66/weather', c: 'Weather Maps'},
          {t: '相關資源', h: 'https://developers.google.com/maps/documentation/javascript/markers', c: 'Google maps API'},
          {t: '相關資源', h: 'http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.8/docs/examples.html', c: 'MarkerWithLabel'},
          {t: '相關資源', h: 'http://openweathermap.org/', c: 'OpenWeatherMap'},
          {t: '更新日期', h: '', c: '2015/07/10'},
        ]}, {t: '更多 OA 的作品', is: [
          {t: 'Google Maps 大富翁', h: 'https://github.com/comdan66/OA-richman', c: 'OA-richman'},
          {t: 'Material Web UI', h: 'https://github.com/comdan66/OA-material', c: 'OA-material'},
          {t: '北港媽祖野生網站', h: 'https://github.com/comdan66/matsu', c: '北港迎媽祖'},
          {t: 'iOS CapMap', h: 'https://github.com/comdan66/catmap_ios', c: "OA's CapMap"},
          {t: 'Instagram 地圖模式', h: 'https://github.com/comdan66/OA-instagram_maps', c: "OA-instagram_maps"},
          {t: 'javascript 迷宮', h: 'https://github.com/comdan66/OA-maze', c: "OA-maze"},
          {t: 'OA\'s imgLiquid', h: 'https://github.com/comdan66/OA-imgLiquid', c: "OA-imgLiquid"},
        ]}];

    $about.click (function () {
      $popUp.find ('.paper').empty ().append ($('<h2 />').text (popUp[0].t)).append ($('<div />').addClass ('pop_up').append (popUp[0].is.map (function (t) {
        return $('<div />').addClass ('i').append ($('<div />').addClass ('l').text (t.t)).append ($('<div />').addClass ('r').append (t.h === '' ? t.c : $('<a />').attr ('href', t.h).attr ('target', '_blank').text (t.c)));
      }))).append ($('<div />').addClass ('close').html ('&#10006;'));

      $popUp.removeClass ('hide');
      return false;
    });

    $more.click (function () {
      $popUp.find ('.paper').empty ().append ($('<h2 />').text (popUp[1].t)).append ($('<div />').addClass ('pop_up').append (popUp[1].is.map (function (t) {
        return $('<div />').addClass ('i').append ($('<div />').addClass ('l').text (t.t)).append ($('<div />').addClass ('r').append (t.h === '' ? t.c : $('<a />').attr ('href', t.h).attr ('target', '_blank').text (t.c)));
      }))).append ($('<div />').addClass ('close').html ('&#10006;'));

      $popUp.removeClass ('hide');
      return false;
    });

    $popUp.on ('click', '.close', function () {
      $popUp.find ('.paper').empty ();
      $popUp.addClass ('hide');
    });
});