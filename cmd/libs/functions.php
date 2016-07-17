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
if (!function_exists ('mkdir777')) {
  function mkdir777 ($path) {
    $oldmask = umask (0);
    @mkdir ($path, 0777, true);
    umask ($oldmask);
    return true;
  }
}

if (!function_exists ('array_2d_to_1d')) {
  function array_2d_to_1d ($array) {
    $messages = array ();
    foreach ($array as $key => $value)
      if (is_array ($value)) $messages = array_merge ($messages, $value);
      else array_push ($messages, $value);
    return $messages;
  }
}
if (!function_exists ('color')) {
  function color ($string, $foreground_color = null, $background_color = null, $is_print = false) {
    if (!strlen ($string)) return "";
    $colored_string = "";
    $keys = array ('n' => '30', 'w' => '37', 'b' => '34', 'g' => '32', 'c' => '36', 'r' => '31', 'p' => '35', 'y' => '33');
    if ($foreground_color && in_array (strtolower ($foreground_color), array_map ('strtolower', array_keys ($keys)))) {
      $foreground_color = !in_array (ord ($foreground_color[0]), array_map ('ord', array_keys ($keys))) ? in_array (ord ($foreground_color[0]) | 0x20, array_map ('ord', array_keys ($keys))) ? '1;' . $keys[strtolower ($foreground_color[0])] : null : $keys[$foreground_color[0]];
      $colored_string .= $foreground_color ? "\033[" . $foreground_color . "m" : "";
    }
    $colored_string .= $background_color && in_array (strtolower ($background_color), array_map ('strtolower', array_keys ($keys))) ? "\033[" . ($keys[strtolower ($background_color[0])] + 10) . "m" : "";

    if (substr ($string, -1) == "\n") { $string = substr ($string, 0, -1); $has_new_line = true; } else { $has_new_line = false; }
    $colored_string .=  $string . "\033[0m";
    $colored_string = $colored_string . ($has_new_line ? "\n" : "");
    if ($is_print) printf ($colored_string);
    return $colored_string;
  }
}

if (!function_exists ('merge_array_recursive')) {
  function merge_array_recursive ($files, &$a, $k = null) {
    if (!($files && is_array ($files))) return false;
    foreach ($files as $key => $file)
      if (is_array ($file)) $key . merge_array_recursive ($file, $a, ($k ? rtrim ($k, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $key);
      else array_push ($a, ($k ? rtrim ($k, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $file);
  }
}

if (!function_exists ('directory_list')) {
  function directory_list ($source_dir, $hidden = false) {
    if ($fp = @opendir ($source_dir = rtrim ($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)) {
      $filedata = array ();

      while (false !== ($file = readdir ($fp)))
        if (!(!trim ($file, '.') || (($hidden == false) && ($file[0] == '.'))))
          array_push ($filedata, $file);

      closedir ($fp);
      return $filedata;
    }
    return array ();
  }
}

if (!function_exists ('params')) {
  function params ($params, $keys) {
    $ks = $return = $result = array ();

    if (!$params) return $return;
    if (!$keys) return $return;

    foreach ($keys as $key)
      if (is_array ($key)) foreach ($key as $k) array_push ($ks, $k);
      else  array_push ($ks, $key);

    $key = null;

    foreach ($params as $param)
      if (in_array ($param, $ks)) if (!isset ($result[$key = $param])) $result[$key] = array (); else ;
      else if (isset ($result[$key])) array_push ($result[$key], $param); else ;

    foreach ($keys as $key)
      if (is_array ($key))  foreach ($key as $k) if (isset ($result[$k])) $return[$key[0]] = isset ($return[$key[0]]) ? array_merge ($return[$key[0]], $result[$k]) : $result[$k]; else;
      else if (isset ($result[$key])) $return[$key] = isset ($return[$key]) ? array_merge ($return[$key], $result[$key]) : $result[$key]; else;

    return $return;
  }
}


if (!function_exists ('directory_map')) {
  function directory_map ($source_dir, $directory_depth = 0, $hidden = false) {
    if ($fp = @opendir ($source_dir)) {
      $filedata = array ();
      $new_depth  = $directory_depth - 1;
      $source_dir = rtrim ($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

      while (false !== ($file = readdir ($fp))) {
        if (!trim ($file, '.') || (($hidden == false) && ($file[0] == '.')) || is_link ($file) || ($file == 'cmd'))
          continue;

        if ((($directory_depth < 1) || ($new_depth > 0)) && @is_dir ($source_dir . $file))
          $filedata[$file] = directory_map ($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden);
        else
          array_push ($filedata, $file);
      }

      closedir ($fp);
      return $filedata;
    }

    return false;
  }
}

if (!function_exists ('directory_delete')) {
    function directory_delete ($dir, $is_root = true) {
      if (!file_exists ($dir)) return true;
      
      $dir = rtrim ($dir, DIRECTORY_SEPARATOR);
      if (!$current_dir = @opendir ($dir))
        return false;

      while (false !== ($filename = @readdir ($current_dir)))
        if (($filename != '.') && ($filename != '..'))
          if (is_dir ($dir . DIRECTORY_SEPARATOR . $filename)) if (substr ($filename, 0, 1) != '.') directory_delete ($dir . DIRECTORY_SEPARATOR . $filename); else;
          else unlink ($dir . DIRECTORY_SEPARATOR . $filename);

      @closedir ($current_dir);

      return $is_root ? @rmdir ($dir) : true;
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