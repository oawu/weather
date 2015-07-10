/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.fancybox').click (function () {
    $.fancybox({
        href : 'proposes/map/' + $(this).data ('id'),
        type: 'iframe',
        padding: 0
    });
  });
});