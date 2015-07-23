/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

window.url = 'http://dev.comdan66.github.io/weather/';
window.getWeathersUrl = 'http://dev.weather.ioa.tw/main/get_weathers';

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
                            .append ($('<div />').addClass ('l').append ($('<a />').addClass ('home').addClass ('icon-home').attr ('href', window.url).attr ('target', '_self')).append (links.left.map (function (t) {
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