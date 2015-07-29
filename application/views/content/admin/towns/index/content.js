/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.icon-refresh').click (function () {
    $.ajax ({
      url: $('#refresh_weather_url').val (),
      data: { id: $(this).data ('id') },
      async: true, cache: false, dataType: 'json', type: 'POST',
      beforeSend: function () {
        $(this).attr ('class', 'icon-hour-glass');
      }.bind ($(this))
    })
    .done (function (result) {
      if (result.status)
        location.reload ();
    })
    .fail (function (result) { ajaxError (result); })
    .complete (function (result) {
      $(this).attr ('class', 'icon-refresh');
    }.bind ($(this)));
  });
  
  $('.fancybox_town').click (function () {
    $.fancybox ({
        href: '/admin/pub_method/town/' + $(this).data ('id') + '?t=' + new Date ().getTime (),
        type: 'iframe',
        padding: 0,
        margin: '70 30 30 30',
        width: '100%',
        maxWidth: '1200',
        afterClose: function () {
          // location.reload ();
        }
    });
  });
  
  $('.fancybox_view').click (function () {
    $.fancybox ({
        href: '/admin/pub_method/view/' + $(this).data ('id') + '?t=' + new Date ().getTime (),
        type: 'iframe',
        padding: 0,
        margin: '70 30 30 30',
        width: '100%',
        maxWidth: '1200',
        afterClose: function () {
          location.reload ();
        }
    });
  });

  $('.pic[href]').fancybox ({
              padding: 0,
              margin: '70 30 30 30',
              helpers: {
                overlay: { locked: false },
                title: { type: 'over' },
                thumbs: { width: 50, height: 50 }
              }
           });
});