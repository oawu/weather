/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
$(function () {
  var $satellites = $('#satellites');
  var timer = null;
  var changeTime = 700;
  
  function changeSatellite (index, length) {
    var $first = $satellites.find ('img').last ().clone ();
    $satellites.prepend ($first).find ('img').last ().fadeOut (changeTime / 3 * 2, function () {
      $satellites.attr ('data-text', $.timeago ($(this).prev ().attr ('alt'))).attr ('data-time', $(this).prev ().attr ('alt'));
      $(this).remove ();
    });

    clearTimeout (timer);
    
    timer = setTimeout (changeSatellite.bind (this, (index + 1) % length, length), index + 1 == length - 1 ? 5000: changeTime);
  }

  function initUI (imgs) {
    $satellites.prepend (imgs.map (function (t, i) {
      return $('<img />').attr ('src', t.src).attr ('alt', t.text);
    })).attr ('data-text', $.timeago (imgs[imgs.length - 1].text)).attr ('data-time', imgs[imgs.length - 1].text);

    // $satellites.imagesLoaded (function () {
    timer = setTimeout (changeSatellite.bind (this, 0, imgs.length), changeTime);
    window.closeLoading ();
    // });
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