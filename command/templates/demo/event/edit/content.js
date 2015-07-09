/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  $('.attendees .add').click (function () {
    $(_.template ($('#_attendee').html (), {}) ({})).insertBefore ($(this).last ()).hide ().fadeIn();
  });
  $('body').on ('click', '.attendee .destroy', function () {
    $(this).parents ('div.attendee').fadeOut (function () {
      $(this).remove ();
    });
  });
});