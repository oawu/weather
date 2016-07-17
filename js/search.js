/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

$(function () {
  var hash = window.location.hash.trim ().slice (1);
  window.onhashchange = function () {
    location.reload ();
  };

  window.vars.$container = $('#container');
  window.vars.$weather = $('#weather');

  window.fns.location.get (function (code) {
    $tmp = window.vars.$mapsA.filter ('[data-code="' + code + '"]');
    if (!$tmp.length) return false;
    var val = $tmp.data ('val');

    window.vars.$weather.append ($('<figure />').attr ('data-temperature', val.t).append ($('<img />').attr ('src', val.m)).append ($('<figcaption />').text (val.n)));
    window.vars.$weather.parent ().addClass ('show');
  });

  window.vars.townsTimer = null;
  function towns ($a, i) {
    var $tags = $a.slice (i, i + 5).removeClass ('back').addClass ('start');
    clearTimeout (window.vars.townsTimer);

    window.vars.townsTimer = setTimeout (function () {
      $tags.addClass ('end');
      setTimeout (towns.bind (this, $a, (i + 5) % 10), 100);
      setTimeout (function () { $tags.addClass ('back').removeClass ('start end'); }, 1500);
    }, 5000);
  }
  towns ($('#towns a'), 0);
  
  window.vars.$search = $('#search').val (decodeURIComponent (hash));
  window.vars.geocoder = null;

  function showMap (id) {
    var weather = null;

    for (var i = 0; i < window.vars.weathers.length; i++)
      if (window.vars.weathers[i].i == id && (weather = window.vars.weathers[i]))
        break;

    window.vars.$container.addClass ('maps');
    google.maps.event.trigger (window.vars.maps, 'resize');
    setTimeout (function () {
      google.maps.event.trigger (weather.marker, 'click');
    }, 500);
  }

  function search () {
    var val = window.vars.$search.val ().trim ();
    val = val.split (/\s+/).map (function (t) { return t.trim (); }).filter (function (t) { return t.length; });
    if (val.length <= 0) return ;
    val.reverse ();

    var a = null;
    for (var i = 0; i < val.length; i++) if ((a = window.vars.$mapsA.filter ('[title*="' + val[i] + '"]')).length) break;

    if (a.length > 0) {
      showMap (a.data ('val').i);
    } else {
      if (!window.vars.geocoder) window.vars.geocoder = new google.maps.Geocoder ();
      window.vars.geocoder.geocode ({"address": val.join (' ') }, function (r, s) {
        
        if (!((s == google.maps.GeocoderStatus.OK) && (r.length > 0) && (r = r[0]) && (r = r['address_components']))) return false;
        var postal_code = 0;
        for (var i = 0; i < r.length; i++) if (($.inArray ('postal_code', r[i]['types']) != -1) && (postal_code = r[i]['long_name'])) break;
        if (!postal_code) return false;
        a = window.vars.$mapsA.filter ('[data-code="' + postal_code + '"]');
        if (a.length > 0) showMap (a.data ('val').i);
      });
    }
  }
  window.vars.$search.keyup (function (e) {
    if (e.keyCode != 13) return false;
    window.location.hash = encodeURIComponent ($(this).val ().trim ());
  });
  $('#search + i').click (function () { window.location.hash = encodeURIComponent (window.vars.$search.val ().trim ()); });
  
  google.maps.event.addDomListener (window, 'load', function () {
    if (hash.length) search ();
  });
});