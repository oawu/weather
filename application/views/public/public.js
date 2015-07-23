/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */


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

function getUnit (will, now) {
  var addLat = will.lat () - now.lat ();
  var addLng = will.lng () - now.lng ();
  var aveAdd = ((Math.abs (addLat) + Math.abs (addLng)) / 2);
  var unit = aveAdd < 10 ? aveAdd < 1 ? aveAdd < 0.1 ? aveAdd < 0.01 ? aveAdd < 0.001 ? aveAdd < 0.0001 ? 3 : 6 : 9 : 12 : 15 : 24 : 21;
  var lat = addLat / unit;
  var lng = addLng / unit;

  if (!((Math.abs (lat) > 0) || (Math.abs (lng) > 0)))
    return null;

  return {
    unit: unit,
    lat: lat,
    lng: lng
  };
}

function mapMove (map, unitLat, unitLng, unitCount, unit, callback) {
  if (unit > unitCount) {
    map.setCenter (new google.maps.LatLng (map.getCenter ().lat () + unitLat, map.getCenter ().lng () + unitLng));
    clearTimeout (window.mapMoveTimer);
    window.mapMoveTimer = setTimeout (function () {
      mapMove (map, unitLat, unitLng, unitCount + 1, unit, callback);
    }, 25);
  } else {
    if (callback)
      callback (map);
  }
}

function mapGo (map, will, callback) {
  var now = map.center;

  var Unit = getUnit (will, now);
  if (!Unit)
    return false;

  mapMove (map, Unit.lat, Unit.lng, 0, Unit.unit, callback);
}

window.ajaxError = function (result) {
  console.error (result.responseText);
};

$(function () {
});