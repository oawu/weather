/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

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
  var now = document.URL.replace (/^.*[\\\/]/, '');

  var side = links.left.concat (links.right);
  if (!side.length) return;

  var $body = $('body');
  var overflow = $body.css ('overflow');
  var $option = null;

  var $headerRightSlide = $('<div />').attr ('id', 'header_right_slide').addClass ('close').append ($('<div />').addClass ('right_slide_container').append (side.map (function (t) {
                             return $('<a />').addClass (t.file == now ? 'active' : null).addClass ('sub').attr ('href', window.url + t.file).attr ('target', t.target).text (t.name);
                           }))).prependTo ($body);

  var $headerSlideCover = $('<div />').attr ('id', 'header_slide_cover').click (function () {
                            if (!$headerRightSlide.hasClass ('close')) {
                              $headerRightSlide.addClass ('close');
                              $('body').css ('overflow', overflow);
                              $option.removeClass ('close');
                            }
                          }).prependTo ($body);
  
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
});