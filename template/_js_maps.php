/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

function inherits(e,t){function i(){}i.prototype=t.prototype,e.superClass_=t.prototype,e.prototype=new i,e.prototype.constructor=e}function MarkerLabel_(e,t,i){this.marker_=e,this.handCursorURL_=e.handCursorURL,this.labelDiv_=document.createElement("div"),this.labelDiv_.style.cssText="position: absolute; overflow: hidden;",this.eventDiv_=document.createElement("div"),this.eventDiv_.style.cssText=this.labelDiv_.style.cssText,this.eventDiv_.setAttribute("onselectstart","return false;"),this.eventDiv_.setAttribute("ondragstart","return false;"),this.crossDiv_=MarkerLabel_.getSharedCross(t)}function MarkerWithLabel(e){e=e||{},e.labelContent=e.labelContent||"",e.initCallback=e.initCallback||function(){},e.labelAnchor=e.labelAnchor||new google.maps.Point(0,0),e.labelClass=e.labelClass||"markerLabels",e.labelStyle=e.labelStyle||{},e.labelInBackground=e.labelInBackground||!1,"undefined"==typeof e.labelVisible&&(e.labelVisible=!0),"undefined"==typeof e.raiseOnDrag&&(e.raiseOnDrag=!0),"undefined"==typeof e.clickable&&(e.clickable=!0),"undefined"==typeof e.draggable&&(e.draggable=!1),"undefined"==typeof e.optimized&&(e.optimized=!1),e.crossImage=e.crossImage||"http"+("https:"===document.location.protocol?"s":"")+"://maps.gstatic.com/intl/en_us/mapfiles/drag_cross_67_16.png",e.handCursor=e.handCursor||"http"+("https:"===document.location.protocol?"s":"")+"://maps.gstatic.com/intl/en_us/mapfiles/closedhand_8_8.cur",e.optimized=!1,this.label=new MarkerLabel_(this,e.crossImage,e.handCursor),google.maps.Marker.apply(this,arguments)}inherits(MarkerLabel_,google.maps.OverlayView),MarkerLabel_.getSharedCross=function(e){var t;return"undefined"==typeof MarkerLabel_.getSharedCross.crossDiv&&(t=document.createElement("img"),t.style.cssText="position: absolute; z-index: 1000002; display: none;",t.style.marginLeft="-8px",t.style.marginTop="-9px",t.src=e,MarkerLabel_.getSharedCross.crossDiv=t),MarkerLabel_.getSharedCross.crossDiv},MarkerLabel_.prototype.onAdd=function(){var e,t,i,s,a,r,o,n=this,l=!1,g=!1,p=20,_="url("+this.handCursorURL_+")",v=function(e){e.preventDefault&&e.preventDefault(),e.cancelBubble=!0,e.stopPropagation&&e.stopPropagation()},h=function(){n.marker_.setAnimation(null)};this.getPanes().overlayImage.appendChild(this.labelDiv_),this.getPanes().overlayMouseTarget.appendChild(this.eventDiv_),"undefined"==typeof MarkerLabel_.getSharedCross.processed&&(this.getPanes().overlayImage.appendChild(this.crossDiv_),MarkerLabel_.getSharedCross.processed=!0),this.listeners_=[google.maps.event.addDomListener(this.eventDiv_,"mouseover",function(e){(n.marker_.getDraggable()||n.marker_.getClickable())&&(this.style.cursor="pointer",google.maps.event.trigger(n.marker_,"mouseover",e))}),google.maps.event.addDomListener(this.eventDiv_,"mouseout",function(e){!n.marker_.getDraggable()&&!n.marker_.getClickable()||g||(this.style.cursor=n.marker_.getCursor(),google.maps.event.trigger(n.marker_,"mouseout",e))}),google.maps.event.addDomListener(this.eventDiv_,"mousedown",function(e){g=!1,n.marker_.getDraggable()&&(l=!0,this.style.cursor=_),(n.marker_.getDraggable()||n.marker_.getClickable())&&(google.maps.event.trigger(n.marker_,"mousedown",e),v(e))}),google.maps.event.addDomListener(document,"mouseup",function(t){var i;if(l&&(l=!1,n.eventDiv_.style.cursor="pointer",google.maps.event.trigger(n.marker_,"mouseup",t)),g){if(a){i=n.getProjection().fromLatLngToDivPixel(n.marker_.getPosition()),i.y+=p,n.marker_.setPosition(n.getProjection().fromDivPixelToLatLng(i));try{n.marker_.setAnimation(google.maps.Animation.BOUNCE),setTimeout(h,1406)}catch(r){}}n.crossDiv_.style.display="none",n.marker_.setZIndex(e),s=!0,g=!1,t.latLng=n.marker_.getPosition(),google.maps.event.trigger(n.marker_,"dragend",t)}}),google.maps.event.addListener(n.marker_.getMap(),"mousemove",function(s){var _;l&&(g?(s.latLng=new google.maps.LatLng(s.latLng.lat()-t,s.latLng.lng()-i),_=n.getProjection().fromLatLngToDivPixel(s.latLng),a&&(n.crossDiv_.style.left=_.x+"px",n.crossDiv_.style.top=_.y+"px",n.crossDiv_.style.display="",_.y-=p),n.marker_.setPosition(n.getProjection().fromDivPixelToLatLng(_)),a&&(n.eventDiv_.style.top=_.y+p+"px"),google.maps.event.trigger(n.marker_,"drag",s)):(t=s.latLng.lat()-n.marker_.getPosition().lat(),i=s.latLng.lng()-n.marker_.getPosition().lng(),e=n.marker_.getZIndex(),r=n.marker_.getPosition(),o=n.marker_.getMap().getCenter(),a=n.marker_.get("raiseOnDrag"),g=!0,n.marker_.setZIndex(1e6),s.latLng=n.marker_.getPosition(),google.maps.event.trigger(n.marker_,"dragstart",s)))}),google.maps.event.addDomListener(document,"keydown",function(e){g&&27===e.keyCode&&(a=!1,n.marker_.setPosition(r),n.marker_.getMap().setCenter(o),google.maps.event.trigger(document,"mouseup",e))}),google.maps.event.addDomListener(this.eventDiv_,"click",function(e){(n.marker_.getDraggable()||n.marker_.getClickable())&&(s?s=!1:(google.maps.event.trigger(n.marker_,"click",e),v(e)))}),google.maps.event.addDomListener(this.eventDiv_,"dblclick",function(e){(n.marker_.getDraggable()||n.marker_.getClickable())&&(google.maps.event.trigger(n.marker_,"dblclick",e),v(e))}),google.maps.event.addListener(this.marker_,"dragstart",function(e){g||(a=this.get("raiseOnDrag"))}),google.maps.event.addListener(this.marker_,"drag",function(e){g||a&&(n.setPosition(p),n.labelDiv_.style.zIndex=1e6+(this.get("labelInBackground")?-1:1))}),google.maps.event.addListener(this.marker_,"dragend",function(e){g||a&&n.setPosition(0)}),google.maps.event.addListener(this.marker_,"position_changed",function(){n.setPosition()}),google.maps.event.addListener(this.marker_,"zindex_changed",function(){n.setZIndex()}),google.maps.event.addListener(this.marker_,"visible_changed",function(){n.setVisible()}),google.maps.event.addListener(this.marker_,"labelvisible_changed",function(){n.setVisible()}),google.maps.event.addListener(this.marker_,"title_changed",function(){n.setTitle()}),google.maps.event.addListener(this.marker_,"labelcontent_changed",function(){n.setContent()}),google.maps.event.addListener(this.marker_,"labelanchor_changed",function(){n.setAnchor()}),google.maps.event.addListener(this.marker_,"labelclass_changed",function(){n.setStyles()}),google.maps.event.addListener(this.marker_,"labelstyle_changed",function(){n.setStyles()})]},MarkerLabel_.prototype.onRemove=function(){var e;for(this.labelDiv_.parentNode.removeChild(this.labelDiv_),this.eventDiv_.parentNode.removeChild(this.eventDiv_),e=0;e<this.listeners_.length;e++)google.maps.event.removeListener(this.listeners_[e])},MarkerLabel_.prototype.draw=function(){this.setContent(),this.setTitle(),this.setStyles()},MarkerLabel_.prototype.setContent=function(){var e=this.marker_.get("labelContent");"undefined"==typeof e.nodeType?(this.labelDiv_.innerHTML=e,this.eventDiv_.innerHTML=this.labelDiv_.innerHTML):(this.labelDiv_.innerHTML="",this.labelDiv_.appendChild(e),e=e.cloneNode(!0),this.eventDiv_.innerHTML="",this.eventDiv_.appendChild(e))},MarkerLabel_.prototype.setTitle=function(){this.eventDiv_.title=this.marker_.getTitle()||""},MarkerLabel_.prototype.setStyles=function(){var e,t;this.labelDiv_.className=this.marker_.get("labelClass"),this.eventDiv_.className=this.labelDiv_.className,this.labelDiv_.style.cssText="",this.eventDiv_.style.cssText="",t=this.marker_.get("labelStyle");for(e in t)t.hasOwnProperty(e)&&(this.labelDiv_.style[e]=t[e],this.eventDiv_.style[e]=t[e]);this.setMandatoryStyles()},MarkerLabel_.prototype.setMandatoryStyles=function(){this.labelDiv_.style.position="absolute",this.labelDiv_.style.overflow="","undefined"!=typeof this.labelDiv_.style.opacity&&""!==this.labelDiv_.style.opacity&&(this.labelDiv_.style.MsFilter='"progid:DXImageTransform.Microsoft.Alpha(opacity='+100*this.labelDiv_.style.opacity+')"',this.labelDiv_.style.filter="alpha(opacity="+100*this.labelDiv_.style.opacity+")"),this.eventDiv_.style.position=this.labelDiv_.style.position,this.eventDiv_.style.overflow=this.labelDiv_.style.overflow,this.eventDiv_.style.opacity=.01,this.eventDiv_.style.MsFilter='"progid:DXImageTransform.Microsoft.Alpha(opacity=1)"',this.eventDiv_.style.filter="alpha(opacity=1)",this.setAnchor(),this.setPosition(),this.setVisible()},MarkerLabel_.prototype.setAnchor=function(){var e=this.marker_.get("labelAnchor");this.labelDiv_.style.marginLeft=-e.x+"px",this.labelDiv_.style.marginTop=-e.y+"px",this.eventDiv_.style.marginLeft=-e.x+"px",this.eventDiv_.style.marginTop=-e.y+"px"},MarkerLabel_.prototype.setPosition=function(e){var t=this.getProjection().fromLatLngToDivPixel(this.marker_.getPosition());"undefined"==typeof e&&(e=0),this.labelDiv_.style.left=Math.round(t.x)+"px",this.labelDiv_.style.top=Math.round(t.y-e)+"px",this.eventDiv_.style.left=this.labelDiv_.style.left,this.eventDiv_.style.top=this.labelDiv_.style.top,this.setZIndex()},MarkerLabel_.prototype.setZIndex=function(){var e=this.marker_.get("labelInBackground")?-1:1;"undefined"==typeof this.marker_.getZIndex()?(this.labelDiv_.style.zIndex=parseInt(this.labelDiv_.style.top,10)+e,this.eventDiv_.style.zIndex=this.labelDiv_.style.zIndex):(this.labelDiv_.style.zIndex=this.marker_.getZIndex()+e,this.eventDiv_.style.zIndex=this.labelDiv_.style.zIndex)},MarkerLabel_.prototype.setVisible=function(){this.marker_.get("labelVisible")?this.labelDiv_.style.display=this.marker_.getVisible()?"block":"none":this.labelDiv_.style.display="none",this.eventDiv_.style.display=this.labelDiv_.style.display;var e=this.marker_.get("initCallback");e(this.labelDiv_)},inherits(MarkerWithLabel,google.maps.Marker),MarkerWithLabel.prototype.setMap=function(e){google.maps.Marker.prototype.setMap.apply(this,arguments),this.label.setMap(e)};

$(function () {
  window.vars.$maps = $('#maps').empty ().append (<?php echo json_encode ($weathers);?>.map (function (t) {
    return $('<a />').attr ('href', t.l).attr ('data-val', JSON.stringify (t)).attr ('data-code', t.p).attr ('title', t.c + ' ' + t.n);
  }));
  window.vars.$mapsA = $('#maps > a');

  window.vars.weathers = window.vars.$mapsA.map (function () {
    return $(this).data ('val');
  }).toArray ();

  google.maps.event.addDomListener (window, 'load', function () {


    var lastPosition = getStorage ('weathers.last.position');

    var zoom = lastPosition && lastPosition.zoom && !isNaN (lastPosition.zoom) ? lastPosition.zoom : 12;
    var lat  = lastPosition && lastPosition.lat &&  !isNaN (lastPosition.lat)  ? lastPosition.lat :  25.056678157775092;
    var lng  = lastPosition && lastPosition.lng &&  !isNaN (lastPosition.lng)  ? lastPosition.lng :  121.53488159179688;
    
    if (window.vars.$maps.data ('position')) {
      zoom = window.vars.$maps.data ('position').z;
      lat = window.vars.$maps.data ('position').a;
      lng = window.vars.$maps.data ('position').g;
    }

    window.vars.maps = new google.maps.Map (window.vars.$maps.get (0), { zoom: zoom, zoomControl: true, scrollwheel: true, scaleControl: true, mapTypeControl: false, navigationControl: true, streetViewControl: false, disableDoubleClickZoom: true, center: new google.maps.LatLng (lat, lng)});
    window.vars.maps.mapTypes.set ('map_style', new google.maps.StyledMapType ([{
      stylers: [{gamma: 0}, {weight: 0.75}] },
      {featureType: 'all', stylers: [{ visibility: 'on' }]},
      {featureType: 'administrative', stylers: [{ visibility: 'off' }]},
      {featureType: 'landscape', stylers: [{ visibility: 'on' }]},
      {featureType: 'poi', stylers: [{ visibility: 'off' }]},
      {featureType: 'road', stylers: [{ visibility: 'simplified' }]},
      {featureType: 'road.arterial', stylers: [{ visibility: 'on' }]},
      {featureType: 'transit', stylers: [{ visibility: 'off' }]},
      {featureType: 'water', stylers: [{ color: '#b3d1ff', visibility: 'on' }]},
      {elementType: "labels.icon", stylers:[{ visibility: 'off' }]}
      ]));
    window.vars.maps.setMapTypeId ('map_style');

    if (!(lastPosition || window.vars.$maps.data ('position'))) window.fns.location.get (function (code) { $tmp = window.vars.$mapsA.filter ('[data-code="' + code + '"]'); if (!$tmp.length) return false; window.vars.maps.setCenter (new google.maps.LatLng ($tmp.data ('val').a, $tmp.data ('val').g)); });

    window.vars.info = new MarkerWithLabel ({position: new google.maps.LatLng (25.056678157775092, 121.53488159179688), draggable: false, raiseOnDrag: false, clickable: true, labelContent: '', labelAnchor: new google.maps.Point (300 / 2,  -25), icon: {path: 'M 0 0'}, zIndex: 999});
    google.maps.event.addListener (window.vars.info, 'click', function () { window.location.assign (window.vars.info.link); });

    window.vars.inBoundWeathers = [];

    window.vars.weathers = window.vars.weathers.map (function (t) {
      t.position = new google.maps.LatLng (t.a, t.g);
      t.marker = new MarkerWithLabel ({
              position: t.position,
              draggable: false,
              raiseOnDrag: false,
              clickable: true,
              labelContent: '<figure data-temperature="' + t.t + '°c">' +
                              '<img src="' + t.m + '" />' +
                              '<figcaption>' + t.n + '</figcaption>' +
                            '</figure>',
              labelAnchor: new google.maps.Point (120 / 2, 140 - 25),
              labelClass: "weather",
              icon: {path: 'M 0 0'},
            });

      google.maps.event.addListener (t.marker, 'click', function () {
        var bounds = new google.maps.LatLngBounds ();
        bounds.extend (t.position);
        bounds.extend (new google.maps.LatLng ((window.vars.$maps.data ('position') ? 0 : 0.05) + parseFloat (t.a),  0.06 + parseFloat (t.g)));
        bounds.extend (new google.maps.LatLng (-0.06 + parseFloat (t.a), -0.06 + parseFloat (t.g)));
        window.vars.maps.fitBounds (bounds);

        window.vars.info.setOptions ({map: null});
        window.vars.info.setOptions ({position: t.position});
        window.vars.info.setOptions ({labelContent: infoContent (t)});
        window.vars.info.setOptions ({labelClass: 'info' + (t.s ? ' s' : '')});
        window.vars.info.link = t.l;
        
        window.vars.infoTimer = null;
        clearTimeout (window.vars.infoTimer);
        window.vars.infoTimer = setTimeout (function () {
          window.vars.info.setOptions ({map: window.vars.maps});
        }, 500);
      });
      return t;
    });

    function infoContent (t) {
      return '<div>' +
              '<h3>' + t.n + '</h3>' +
              '<div>' +
                '<div><span>濕度</span><span>：</span><span>' + t.h + '%</span></div>' +
                '<div><span>雨量</span><span>：</span><span>' + t.r + 'mm</span></div>' +
              '</div>' +
              (t.s ? '<span>' + t.s.imgs.map (function (t) {
                return '<img src=' + t + ' />';
              }).join ('') + t.s.desc + '</span>' : '') +
              '<a>詳細內容</a>' +
            '</div>';
    }
    function loadWeathers () {
      var ne = window.vars.maps.getBounds ().getNorthEast (), sw = window.vars.maps.getBounds ().getSouthWest (), zoom = window.vars.maps.zoom, weathers = window.vars.weathers.filter (function (t) { return (t.z <= zoom) && (t.a >= (sw.lat () - 0.1)) && (t.g > sw.lng ()) && (t.a <= ne.lat ()) && (t.g <= ne.lng ()); }), deletes = window.vars.inBoundWeathers.diff (weathers, 'i'), adds = weathers.diff (window.vars.inBoundWeathers, 'i'), delete_ids = deletes.map (function (t) { t.marker.setMap (null); return t.i; }), add_ids = adds.map (function (t) { t.marker.setMap (window.vars.maps); return t.i; });
      window.vars.inBoundWeathers = window.vars.inBoundWeathers.filter (function (t) { return $.inArray (t.i, delete_ids) == -1; }).concat (weathers.filter (function (t) { return $.inArray (t.i, add_ids) != -1; }));
    }

    window.vars.zoomTimer = null;
    google.maps.event.addListener (window.vars.maps, 'idle', function () {
      if (!window.vars.$maps.data ('position')) setStorage ('weathers.last.position', {zoom: window.vars.maps.zoom, lat: window.vars.maps.center.lat (), lng: window.vars.maps.center.lng ()});
      
      window.vars.info.setOptions ({map: null});
      clearTimeout (window.vars.zoomTimer);
      window.vars.zoomTimer = setTimeout (loadWeathers, 10);
    });
  });
});