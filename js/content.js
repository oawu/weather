/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  window.vars.$view = $('#view');
  window.vars.$change = $('#change');

  window.fns.vieweds.add (window.vars.$maps.data ('position').i);

  var $mores = $('.more figure');
  window.fns.vieweds.get ().forEach (function (t) {
    $mores.filter ('[data-id="' + t + '"]').addClass ('viewed');
  });
  window.fns.follows.get ().forEach (function (t) {
    $mores.filter ('[data-id="' + t + '"]').parent ().addClass ('follow');
  });
  
  google.maps.event.addDomListener (window, 'load', function () {
    var sv = new google.maps.StreetViewService ();
    
    var position = new google.maps.LatLng (window.vars.$maps.data ('position').a, window.vars.$maps.data ('position').g);

    sv.getPanorama ({location: position, radius: 100}, function (data, status) {
      if (status != google.maps.StreetViewStatus.OK)
        return;

      window.vars.panorama = new google.maps.StreetViewPanorama (window.vars.$view.get (0), {
        linksControl: true,
        addressControl: false,
        position: data.location.latLng,
        pov: {
          heading: 0,
          pitch: 0,
          zoom: 0
        }
      });

      window.vars.$change.addClass ('s').click (function () {
        $(this).toggleClass ('v');
      });

      setTimeout (function () {
        window.vars.$change.click ();
      }, 2000);
    });

    setTimeout (function () {
      $('.line_chart div[data-percent]').each (function (i) {
        setTimeout (function () {
          $(this).attr ('class', 'n' + $(this).data ('percent'));
        }.bind ($(this)), i * 25);
      });
    }, 500);
  });

  var $button = $('#button');
  $button.addClass (window.fns.follows.has ($button.data ('id')) ? 'added' : null).attr ('title', $button.hasClass ('added') ? '已收藏' : '未收藏').click (function () {
    if (!$(this).hasClass ('added')) window.fns.follows.add ($(this).data ('id'));
    else window.fns.follows.del ($(this).data ('id'));
    $(this).toggleClass ('added').attr ('title', $(this).hasClass ('added') ? '已收藏' : '未收藏');
  });
  setTimeout (function () { $button.addClass ('show'); }, 1000);
});