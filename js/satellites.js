/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
$(function () {
  var $container = $('#container');
  var $satellites = null;
  var timer = null;
  var changeTime = 500;

  function changeSatellite (index, length) {
    var $first = $satellites.find ('img').first ().clone ();
    $satellites.append ($first).find ('img').first ().fadeOut (changeTime / 3 * 2, function () {
      $satellites.attr ('data-text', $.timeago ($(this).next ().attr ('alt'))).attr ('data-time', $(this).next ().attr ('alt'));
      $(this).remove ();
    });

    clearTimeout (timer);

    timer = setTimeout (changeSatellite.bind (this, (index + 1) % length, length), index + 1 == length - 1 ? 5000: changeTime);
  }
  function loadImg ($imgs, i) {
    if (i < $imgs.length)
      $imgs.eq (i)
           .imagesLoaded ()
           .done (loadImg.bind (this, $imgs, i + 1))
           .fail (loadImg.bind (this, $imgs, i + 1));

    if (!timer && (i > 6)) {
      timer = setTimeout (changeSatellite.bind (this, 0, $imgs.length), changeTime);
      window.closeLoading ();
    }
  }

  function initUI (imgs) {
    $satellites = $('<div />').addClass ('satellites').appendTo ($container).attr ('data-text', '').attr ('data-time', '');

    var $imgs = $satellites.append (imgs.reverse ().map (function (t, i) {
      return $('<img />').attr ('src', t.src + '?t=' + new Date ().getTime ()).attr ('alt', t.text);
    })).attr ('data-text', $.timeago (imgs[imgs.length - 1].text)).attr ('data-time', imgs[imgs.length - 1].text).find ('img');
    loadImg ($imgs, 0);

    setTimeout (function () {
      if (timer) return ;
      timer = setTimeout (changeSatellite.bind (this, 0, $imgs.length), changeTime);
      window.closeLoading ();
    }, 5000);
  }

  $.ajax ({
    url: window.api.getSatellitesUrl,
    data: { },
    async: true, cache: false, dataType: 'json', type: 'GET',
    beforeSend: function () {}
  })
  .done (function (result) {
    if (result.status && result.satellites.length)
      initUI (result.satellites);
    else
      window.location.assign ('index.html');
  })
  .fail (function (result) { window.location.assign ('index.html'); })
  .complete (function (result) {});
});