<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class HTMLMin {
  public static function minify ($html) {
    if (!MINIFY) return $html;
    return trim (preg_replace (array ('/\>[^\S ]+/su', '/[^\S ]+\</su', '/(\s)+/su'), array ('>', '<', '\\1'), $html));
  }
}