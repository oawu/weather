/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.fancybox_town').click (function () {
    $.fancybox ({
        href: '/admin/pub_method/town/' + $(this).data ('id') + '?t=' + new Date ().getTime (),
        type: 'iframe',
        padding: 0,
        margin: '70 30 30 30',
        margin: 100,
        width: '100%',
        maxWidth: '1200',
        afterClose: function () {
          // location.reload ();
        }
    });
  });
});