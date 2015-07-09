<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class CreateDemo {
  static $text = '';
  static $pics = array ();

  public function __construct () {
  }

  public static function email () {
    $elements = array_merge (range ('a', 'z'));
    shuffle ($elements);
    return implode (array_slice ($elements, 0, $length = rand (3, 8))) . '@' . implode (array_slice ($elements, 0, $length = rand (3, 5))) . '.' . implode (array_slice ($elements, 0, $length = rand (3, 5))) . '.' . implode (array_slice ($elements, 0, $length = rand (3, 5)));
  }

  public static function password ($length = 8) {
    $elements = array_merge (
                  range ('A', 'Z'),
                  range ('a', 'z'),
                  range ('0', '9'),
                  array ('_', '+'));
    shuffle ($elements);
    return implode (array_slice ($elements, 0, $length));
  }

  public static function pics ($min = 1, $max = 4, $tags = array ('台灣之美')) {
    if (!count (self::$pics))
      self::$pics = self::rand_pics ($tags);

    $pics = array_slice (self::$pics, 0, $l = rand ($min, $max));
    self::$pics = array_slice (self::$pics, $l);
    return $pics;
  }

  public static function rand_pics ($tags = array ('台灣之美')) {
    $url = 'https://api.flickr.com/services/rest/?';
    $params = array (
        'jsoncallback' => '?',
        'method' => 'flickr.photos.search',
        'api_key' => '09dc017022847889346d048182b9515f',
        'tags' => implode (',', $tags),
        'per_page' => '200',
        'extras' => 'url_m',
        'sort' => 'interestingness-desc',
        'format' => 'json',
        );

    $options = array (
      CURLOPT_URL => $url, CURLOPT_POST => false,
      CURLOPT_POSTFIELDS => http_build_query ($params),
      CURLOPT_TIMEOUT => 120, CURLOPT_HEADER => false, CURLOPT_MAXREDIRS => 10,
      CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36",
    );
    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    $data = curl_exec ($ch);
    curl_close ($ch);

    $data = str_replace ('jsonFlickrApi(', '', $data);
    $data = substr ($data, 0, strlen ( $data ) - 1); //strip out last paren
    $object = json_decode ($data);

    return array_filter (array_map (function ($p) {
      return isset ($p->url_m) && $p->url_m && isset ($p->title) && $p->title ? array ('title' => $p->title, 'url' => $p->url_m) : null;
    }, $object->photos->photo));
  }

  public static function text ($min = 5, $max = 15) {
    if (!self::$text)
      self::$text = self::rand_text ();

    $text = mb_substr (self::$text, 0, $l = rand ($min, $max), 'UTF-8');
    self::$text = mb_substr (self::$text, $l, null, 'UTF-8');
    return $text;
  }

  public static function rand_text ($words = 500) {
    $CI =& get_instance ();
    $CI->load->library ('phpQuery');
    $url = 'http://www.richyli.com/tool/loremipsum/';

    $options = array (
      CURLOPT_URL => $url, CURLOPT_POST => true,
      CURLOPT_POSTFIELDS => http_build_query (array ('words' => $words)),
      CURLOPT_TIMEOUT => 120, CURLOPT_HEADER => false, CURLOPT_MAXREDIRS => 10,
      CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36",
    );

    $ch = curl_init ($url);
    curl_setopt_array ($ch, $options);
    $data = curl_exec ($ch);
    curl_close ($ch);

    $php_query = phpQuery::newDocument ($data);

    $data = preg_replace ('/\s*(.*)\s*\n*/', '$1', pq ("#text p", $php_query)->text ());

    return $data;
  }
}
