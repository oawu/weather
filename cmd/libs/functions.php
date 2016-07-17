<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

if (!function_exists ('town_img_info')) {
  function town_img_info ($town) {
    if (file_exists ($p = PATH_IMG_TOWNS . $town['cate']['id'] . DIRECTORY_SEPARATOR . $town['id'] . DIRECTORY_SEPARATOR . 'v.jpg'))
      return array (
          'img' => URL_IMG_TOWNS . $town['cate']['id'] . '/' . $town['id'] . '/' . 'v.jpg',
          'dimension' => ($d = getimagesize ($p)) ? array (
              'width' => $d[0],
              'height' => $d[1]
            ) : array ()
        );
    else if (file_exists ($p = PATH_IMG_TOWNS . $town['cate']['id'] . DIRECTORY_SEPARATOR . $town['id'] . DIRECTORY_SEPARATOR . 'm.png'))
      return array (
          'img' => URL_IMG_TOWNS . $town['cate']['id'] . '/' . $town['id'] . '/' . 'm.png',
          'dimension' => ($d = getimagesize ($p)) ? array (
              'width' => $d[0],
              'height' => $d[1]
            ) : array ()
        );
    else
      return array (
          'img' => URL_OG_INDEX,
          'dimension' => array (
              'width' => 1200,
              'height' => 630
            )
        );
  }
}
if (!function_exists ('url_encode')) {
  function url_encode ($n) {
    return rawurlencode (preg_replace ('/[\/%]/u', ' ', preg_replace ('/[\(\)]/u', '', $n)));
  }
}
if (!function_exists ('func')) {
  function func ($m, $n) {
    if ($m - $n == 0) return array ('x' => 0, 'y' => 0);
    $x = 90 / ($m - $n);
    $y = 55 - (($m + $n) * $x) / 2;
    return array ('x' => $x, 'y' => $y);
  }
}
if (!function_exists ('parse_number')) {
  function parse_number ($str) {
    return preg_replace ('/[^\d\.]+/u', '', preg_replace ('/\s/u', '', $str));
  }
}

if (!function_exists ('load_view')) {
  function load_view ($__o__p__ = '', $__o__d__ = array ()) {
    if (!$__o__p__) return '';

    extract ($__o__d__);
    ob_start ();
    if (((bool)@ini_get ('short_open_tag') === FALSE) && (false == TRUE)) echo eval ('?>' . preg_replace ("/;*\s*\?>/u", "; ?>", str_replace ('<?=', '<?php echo ', file_get_contents ($__o__p__))));
    else include $__o__p__;
    $buffer = ob_get_contents ();
    @ob_end_clean ();

    return $buffer;
  
  }
}

if (!function_exists ('read_file')) {
  function read_file ($file) {
    if (!file_exists ($file)) return false;
    if (function_exists ('file_get_contents')) return file_get_contents ($file);
    if (!$fp = @fopen ($file, 'rb')) return false;

    $data = '';
    flock ($fp, LOCK_SH);
    if (filesize ($file) > 0) $data =& fread ($fp, filesize ($file));
    flock ($fp, LOCK_UN);
    fclose ($fp);

    return $data;
  }
}
if (!function_exists ('write_file')) {
  function write_file ($path, $data, $mode = 'wb') {
    if (!$fp = @fopen ($path, $mode)) return false;

    flock($fp, LOCK_EX);
    fwrite($fp, $data);
    flock($fp, LOCK_UN);
    fclose($fp);

    return true;
  }
}
function download ($url, $fileName = null, $is_use_reffer = false, $cainfo = null) {
  if (is_readable ($cainfo)) $url = str_replace (' ', '%20', $url);

  $options = array (CURLOPT_URL => $url, CURLOPT_TIMEOUT => 120, CURLOPT_HEADER => false, CURLOPT_MAXREDIRS => 10, CURLOPT_AUTOREFERER => true, CURLOPT_CONNECTTIMEOUT => 30, CURLOPT_RETURNTRANSFER => true, CURLOPT_FOLLOWLOCATION => true, CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1700.76 Safari/537.36");
  if (is_readable ($cainfo)) $options[CURLOPT_CAINFO] = $cainfo;
  if ($is_use_reffer) $options[CURLOPT_REFERER] = $url;

  $ch = curl_init ($url);
  curl_setopt_array ($ch, $options);
  $data = curl_exec ($ch);
  curl_close ($ch);

  if (!$fileName) return $data;

  $write = fopen ($fileName, 'w');
  fwrite ($write, $data);
  fclose ($write);

  $oldmask = umask (0);
  @chmod ($fileName, 0777);
  umask ($oldmask);

  return filesize ($fileName) ?  $fileName : null;
}
if (!function_exists ('column_array')) {
  function column_array ($objects, $key) {
    return array_map (function ($object) use ($key) {
      return !is_array ($object) ? is_object ($object) ? $object->$key : $object : $object[$key];
    }, $objects);
  }
}
if (!function_exists ('enable_link')) {
  function enable_link ($text, $maxLength = 0, $linkText = '', $attributes = 'target="_blank"') {
    return preg_replace_callback ('/(https?:\/\/)[~\S]+/u', function ($matches) use ($maxLength, $linkText, $attributes) {
      $text = $linkText ? $linkText : $matches[0];
      $text = $maxLength > 0 ? mb_strimwidth ($text, 0, $maxLength, '…','UTF-8') : $text;
      return '<a href="' . $matches[0] . '"' . ($attributes ? ' ' . $attributes : '') . '>' . urldecode($text) . '</a>';
    }, $text);
  }
}
if (!function_exists ('avatar_url')) {
  function avatar_url ($fb_id, $w = 100, $h = 100) {
    $size = array ();
    array_push ($size, isset ($w) && $w ? 'width=' . $w : '');
    array_push ($size, isset ($h) && $h ? 'height=' . $h : '');

    return 'https://graph.facebook.com/' . $fb_id . '/picture' . (($size = implode ('&', array_filter ($size))) ? '?' . $size : '');
  }
}
