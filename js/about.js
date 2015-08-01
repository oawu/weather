/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $container = $('#container');

  var top = {
    h1: "OA's Weather Maps!",
    html: "想查詢每個地方的天氣嗎！？藉由 <b>Google Maps API</b> 的地圖服務，以及<b>中央氣象局</b>網站的天氣預報，讓你快速輕鬆的查詢台灣 368 個鄉鎮的天氣概況！"
  };
  var declare = {
    h2: "聲明",
    lis: [
      "本作品授權採用 <b>姓名標示-非商業性 2.0 台灣 (CC BY-NC 2.0 TW) 授權</b>，詳見 <a href='http://creativecommons.org/licenses/by-nc/2.0/tw/' target='_blank'>http://creativecommons.org/licenses/by-nc/2.0/tw/</a>",
      "網頁內容資料主要參考<a href='http://www.cwb.gov.tw/V7/index.htm' target='_blank'>中央氣象局</a>網站所公佈之內容建置，其內容預報僅供參考，更多詳細氣像概況可至<a href='http://www.cwb.gov.tw/V7/index.htm' target='_blank'>中央氣象局</a>查詢！"
    ]
  };
  var intro = {
    h2: "簡介",
    lis: [
      "藉由 <a href='https://developers.google.com/maps/documentation/javascript/' target='_blank'>Google Maps JavaScript API v3</a> 的地圖服務，以及<a href='http://www.cwb.gov.tw/V7/index.htm' target='_blank'>中央氣象局</a>網站的天氣預報所實作的天氣地圖！",
      "基本上是利用 Google Maps API 的 <a href='https://developers.google.com/maps/documentation/javascript/tutorial' target='_blank'>Maps</a> 以及 <a href='https://developers.google.com/maps/documentation/javascript/markers' target='_blank'>Marker</a> 設計！",
      "附加使用 <a href='http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.8/docs/examples.html' target='_blank'>MarkerWithLabel</a> 加強 Google Maps 上的圖像表現。",
      "參考中央氣象局<a href='http://www.cwb.gov.tw/m/' target='_blank'>手機版本網頁</a>所提供的資料建置。",
      "全網站使用<a href='http://www.ibest.tw/page01.php' target='_blank'>響應式網站設計(RWD)</a>，所以手機也可以正常瀏覽。",
      "網站內容使用 <a href='https://developer.mozilla.org/zh-TW/docs/Using_geolocation' target='_blank'>navigator.geolocation</a> 物件取得前端 GPS 位置。",
      "搭配 <a href='https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage' target='_blank'>localStorage</a> 實作<a href='http://comdan66.github.io/weather/index.html' target='_blank'>追蹤天氣</a>、<a href='http://comdan66.github.io/weather/town.html#1' target='_blank'>已讀</a>、<a href='http://comdan66.github.io/weather/maps.html' target='_blank'>記錄上次地圖位置</a>.. 等功能。",
      "<a href='http://comdan66.github.io/weather/search.html' target='_blank'>搜尋功能</a>則使用 <a href='https://developers.google.com/maps/documentation/geocoding/intro' target='_blank'>Google Maps Geocoding API</a> 將住址搜尋更加準確化。",
      "使用 <a href='https://developers.google.com/maps/documentation/staticmaps/intro' target='_blank'>Static Maps API</a> 以及 <a href='https://developers.google.com/maps/documentation/streetview/intro' target='_blank'>Street View Image API</a> 所提供的服務，擷取地點的地圖、街景截圖。",
      "感謝 <a href='http://zeusdesign.com.tw/' target='_blank'>宙思設計</a> 提供的可愛天氣小圖示。",
      "前端開發工具主要使用 <a href='http://gulpjs.com/' target='_blank'>Gulp</a>、<a href='http://compass-style.org/' target='_blank'>Compass</a> 以及 <a href='https://jquery.com/' target='_blank'>jQuery</a> 語言所建立，主要架構則使用 <a href='https://github.com/comdan66/oaf2e/' target='_blank'>OAF2E v1.2</a>。",
      "後端語言為 <a href='http://php.net/' target='_blank'>PHP</a>，使用的 Framework 為 <a href='https://github.com/comdan66/oaci' target='_blank'>OACI version 2.3</a>。",
      "Demo 範例頁面: <a href='http://comdan66.github.io/weather/index.html' target='_blank'>http://comdan66.github.io/weather/index.html</a>"
    ]
  };
  var feature = {
    h2: "功能",
    imgs: [
      { src: 'resource/image/readme/follow.png', text: '追蹤關注各地天氣概況' },
      { src: 'resource/image/readme/position.png', text: '取得本地位置的天氣概況' },
      { src: 'resource/image/readme/search.png', text: '搜尋各鄉鎮的位置及天氣' },
      { src: 'resource/image/readme/view.png', text: '地圖、街景模式切換' },
      { src: 'resource/image/readme/weathers.png', text: '天氣變化長條圖' },
      { src: 'resource/image/readme/visited.png', text: '記錄已讀過的鄉鎮資訊' },
    ]
  };
  var about = {
    h2: "關於",
    lis: [
      "作者名稱 - <a href='http://www.ioa.tw/' target='_blank'>OA Wu</a>",
      "E-mail - <a href='mailto:comdan66@gmail.com'>comdan66@gmail.com</a>",
      "作品名稱 - <a href='http://comdan66.github.io/weather/index.html' target='_blank'>Weather Maps</a>",
      "最新版本 - 2.1.0",
      "GitHub - <a href='https://github.com/comdan66/weather/' target='_blank'>Weather Maps</a>",
      "相關資源 - <a href='https://developers.google.com/maps/documentation/javascript/' target='_blank'>Google Maps JavaScript API v3</a>",
      "相關資源 - <a href='http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.8/docs/examples.html' target='_blank'>MarkerWithLabel</a>",
      "相關資源 - <a href='http://www.cwb.gov.tw/V7/index.htm' target='_blank'>中央氣象局</a>",
      "更新日期 - 2015/07/29",
    ]
  };

  $('<section />').append ($('<h1 />').text (top.h1)).append ($('<article />').html (top.html)).appendTo ($container);
  $('<section />').append ($('<h2 />').text (declare.h2)).append ($('<article />').append ($('<ol />').append (declare.lis.map (function (t) { return $('<li />').html (t); })))).appendTo ($container);
  $('<section />').append ($('<h2 />').text (intro.h2)).append ($('<article />').append ($('<ol />').append (intro.lis.map (function (t) { return $('<li />').html (t); })))).appendTo ($container);
  $('<section />').append ($('<h2 />').text (feature.h2)).append ($('<article />').append (feature.imgs.map (function (t) { return $('<div />').append ($('<img />').attr ('src', t.src).attr ('alt', t.text).attr ('title', t.text)).append ($('<div />').text (t.text)); }))).appendTo ($container).find ('article > div').imgLiquid ({verticalAlign: 'top'});
  $('<section />').append ($('<h2 />').text (about.h2)).append ($('<article />').append ($('<ol />').append (about.lis.map (function (t) { return $('<li />').html (t); })))).appendTo ($container);

  window.closeLoading ();
});