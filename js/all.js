/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */
 
$(function () {
  var hash = window.location.hash.trim ().slice (1);
  var move = function () { window.vars.$body.animate ({ scrollTop: $('header[title="' + decodeURIComponent (hash) + '"]').offset ().top - 50 - 15 }, 'slow'); };

  window.onhashchange = function () {
    hash = window.location.hash.trim ().slice (1);
    move ();
  };
  if (hash) setTimeout (move, 100);


  var $towns = $('#container figure');
  window.fns.vieweds.get ().forEach (function (t) {
    $towns.filter ('[data-id="' + t + '"]').addClass ('viewed');
  });
  window.fns.follows.get ().forEach (function (t) {
    $towns.filter ('[data-id="' + t + '"]').parent ().addClass ('follow');
  });


  var $top = $('#top').click (function () {
    window.vars.$body.animate ({ scrollTop: 0 }, 'slow');
  });
  $(window).scroll (function () {
    if ($(this).scrollTop () > $(this).height () / 2) $top.fadeIn ('s');
    else $top.fadeOut ('s');
  });
});