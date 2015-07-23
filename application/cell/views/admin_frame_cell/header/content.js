/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$(function () {
  var $header = $('#header');
  var $option = $header.find ('.option');
  var $mobileTitle = $header.find ('.c').click (function () {$("html, body").stop ().animate ({ scrollTop: 0 - 50 }, 500);});
  var $headerRightSlide = $('#header_right_slide');
  var $headerSlideCover = $('#header_slide_cover');
  var overflow = $('body').css ('overflow');

  $option.click (function () {
    if ($headerRightSlide.hasClass ('close')) {
      $headerRightSlide.removeClass ('close');
      $('body').css ('overflow', 'hidden');
      $option.addClass ('close');
    } else {
      $headerRightSlide.addClass ('close');
      $('body').css ('overflow', overflow);
      $option.removeClass ('close');
    }
  });
  $headerSlideCover.click (function () {
    if (!$headerRightSlide.hasClass ('close')) {
      $headerRightSlide.addClass ('close');
      $('body').css ('overflow', overflow);
      $option.removeClass ('close');
    }
  });
});