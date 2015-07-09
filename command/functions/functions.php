<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

if (!function_exists ('directory_map')) {
  function directory_map ($source_dir, $directory_depth = 0, $hidden = FALSE) {
    if ($fp = @opendir ($source_dir)) {
      $filedata = array ();
      $new_depth  = $directory_depth - 1;
      $source_dir = rtrim ($source_dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

      while (false !== ($file = readdir ($fp)))
        if (!trim ($file, '.') || (($hidden == false) && ($file[0] == '.')))
          continue;
        else
          if ((($directory_depth < 1) || ($new_depth > 0)) && @is_dir ($source_dir . $file))
            $filedata[$file] = directory_map ($source_dir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden);
          else
            $filedata[] = $file;

      closedir ($fp);
      return $filedata;
    }

    return false;
  }
}

if (!function_exists ('delete_file')) {
  function delete_file ($file_path) {
    $temp = file_exists ($file_path);
    @unlink ($file_path);
    return $temp && !file_exists ($file_path);
  }
}

if (!function_exists ('directory_delete')) {
  function directory_delete ($dir, $is_root = true, &$path = null) {
    $dir = rtrim ($dir, DIRECTORY_SEPARATOR);

    if (!$current_dir = @opendir ($dir))
      return false;

    while (false !== ($filename = @readdir ($current_dir)))
      if (($filename != '.') and ($filename != '..'))
        if (is_dir ($dir . DIRECTORY_SEPARATOR . $filename)) {
          if (substr ($filename, 0, 1) != '.')
            directory_delete ($dir . DIRECTORY_SEPARATOR . $filename, true, $path);
        } else {
          if (delete_file ($p = $dir . DIRECTORY_SEPARATOR . $filename) && ($path !== null))
            array_push ($path, $p);
        }
    @closedir ($current_dir);

    return $is_root ? @rmdir ($dir) : true;
  }
}
if (!function_exists ('read_file')) {
  function read_file ($file) {
    if (!file_exists ($file)) return false;

    if (function_exists ('file_get_contents')) return file_get_contents ($file);

    if (!$fp = @fopen ($file, FOPEN_READ)) return false;

    flock ($fp, LOCK_SH);

    $data = '';
    if (filesize($file) > 0)
      $data =& fread ($fp, filesize ($file));

    flock ($fp, LOCK_UN);
    fclose ($fp);

    return $data;
  }
}

if (!function_exists ('write_file')) {
  function write_file ($path, $data, $mode = 'wb') {
    if (!$fp = @fopen($path, $mode))
      return false;

    flock ($fp, LOCK_EX);
    fwrite ($fp, $data);
    flock ($fp, LOCK_UN);
    fclose ($fp);
    return true;
  }
}

if (!function_exists ('delete_files')) {
  function delete_files ($path, $del_dir = false, $level = 0) {
    $path = rtrim ($path, DIRECTORY_SEPARATOR);

    if (!$current_dir = @opendir ($path)) return false;

    while (false !== ($filename = @readdir ($current_dir)))
      if (($filename != ".") && ($filename != "..")) {
        if (is_dir ($path . DIRECTORY_SEPARATOR . $filename)) {
          if (substr ($filename, 0, 1) != '.')
            delete_files ($path . DIRECTORY_SEPARATOR . $filename, $del_dir, $level + 1);
        } else {
          unlink ($path . DIRECTORY_SEPARATOR . $filename);
        }
      }

    @closedir ($current_dir);

    if (($del_dir == true) && ($level > 0)) return @rmdir ($path);

    return true;
  }
}

if (!function_exists ('load_view')) {
  function load_view ($_oa_path = '', $data = array ()) {
    if (!$_oa_path) return '';

    extract ($data);
    global $_navbar_mobile, $_footer, $_list_more, $_mobile_right_slides, $_nav_items, $_pins, $_tags, $_list, $_title, $_url, $_author, $_keywords, $_description, $_og;
    ob_start ();

    if (((bool)@ini_get ('short_open_tag') === FALSE) && (false == TRUE)) echo eval ('?>'.preg_replace ("/;*\s*\?>/", "; ?>", str_replace ('<?=', '<?php echo ', file_get_contents ($_oa_path))));
    else include $_oa_path;

    $buffer = ob_get_contents ();
    @ob_end_clean ();

    return preg_replace ('/{<{<{([\n| ])/i', '<?php$1', $buffer);
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

if (!function_exists ('console_error')) {
  function console_error () {
    $messages = array_filter (func_get_args ());
    $db_line = color (str_repeat ('=', 80), 'N') . "\n";
    $line = color (str_repeat ('-', 80), 'w') . "\n";

    echo "\n" .
          $db_line .
          color ('  ERROR!', 'r') . color (" - ", 'R') . color (array_shift ($messages), 'W') . "\n" .
          $db_line;
          $messages = implode ("", array_map (function ($message) {
                                    return '  ' . color ('Message: ', 'R') . color ($message, 'w') . "\n";
                                  }, $messages));
    echo $messages ? $messages . $db_line : '';
    echo "\n";
    exit ();
  }
}
if (!function_exists ('console_log')) {
  function console_log () {
    $messages = array_filter (func_get_args ());
    $db_line = color (str_repeat ('=', 80), 'N') . "\n";
    $line = color (str_repeat ('-', 80), 'w') . "\n";

    echo "\n" .
          $db_line .
          color ('  Success!', 'C') . color (" - ", 'R') . color (array_shift ($messages), 'W') . "\n" .
          $db_line;
          $messages = implode ("", array_map (function ($message) {
                                    return color ('  ' . $message, 'w') . "\n";
                                  }, $messages));
    echo $messages ? $messages . $db_line : '';
    echo "\n";
    exit ();
  }
}



if (!function_exists ('pluralize')) {
  function pluralize ($string) {
    $uncountable = array ('sheep', 'fish', 'deer', 'series', 'species', 'money', 'rice', 'information', 'equipment');
    $irregular = array ('move' => 'moves', 'foot' => 'feet', 'goose' => 'geese', 'sex' => 'sexes', 'child' => 'children', 'man' => 'men', 'tooth' => 'teeth', 'person' => 'people');
    $plural = array (
        '/(quiz)$/i' => "$1zes",
        '/^(ox)$/i' => "$1en",
        '/([m|l])ouse$/i' => "$1ice",
        '/(matr|vert|ind)ix|ex$/i' => "$1ices",
        '/(x|ch|ss|sh)$/i' => "$1es",
        '/([^aeiouy]|qu)y$/i' => "$1ies",
        '/(hive)$/i' => "$1s",
        '/(?:([^f])fe|([lr])f)$/i' => "$1$2ves",
        '/(shea|lea|loa|thie)f$/i' => "$1ves",
        '/sis$/i' => "ses",
        '/([ti])um$/i' => "$1a",
        '/(tomat|potat|ech|her|vet)o$/i'=> "$1oes",
        '/(bu)s$/i' => "$1ses",
        '/(alias)$/i' => "$1es",
        '/(octop)us$/i' => "$1i",
        '/(ax|test)is$/i' => "$1es",
        '/(us)$/i' => "$1es",
        '/s$/i' => "s",
        '/$/' => "s"
    );

    if (in_array (strtolower ($string), $uncountable))
      return $string;

    foreach ($irregular as $pattern => $result ) {
      $pattern = '/' . $pattern . '$/i';

      if (preg_match ($pattern, $string))
        return preg_replace ($pattern, $result, $string);
    }

    foreach ($plural as $pattern => $result) {
      if (preg_match ($pattern, $string))
        return preg_replace ($pattern, $result, $string);
    }

    return $string;
  }
}

if (!function_exists ('singularize')) {
  function singularize ($string) {
    $uncountable = array ('sheep', 'fish', 'deer', 'series', 'species', 'money', 'rice', 'information', 'equipment');
    $irregular = array ('move' => 'moves', 'foot' => 'feet', 'goose' => 'geese', 'sex' => 'sexes', 'child' => 'children', 'man' => 'men', 'tooth' => 'teeth', 'person' => 'people');
    $singular = array(
        '/(quiz)zes$/i' => "$1",
        '/(matr)ices$/i' => "$1ix",
        '/(vert|ind)ices$/i' => "$1ex",
        '/^(ox)en$/i' => "$1",
        '/(alias)es$/i' => "$1",
        '/(octop|vir)i$/i' => "$1us",
        '/(cris|ax|test)es$/i' => "$1is",
        '/(shoe)s$/i' => "$1",
        '/(o)es$/i' => "$1",
        '/(bus)es$/i' => "$1",
        '/([m|l])ice$/i' => "$1ouse",
        '/(x|ch|ss|sh)es$/i' => "$1",
        '/(m)ovies$/i' => "$1ovie",
        '/(s)eries$/i' => "$1eries",
        '/([^aeiouy]|qu)ies$/i' => "$1y",
        '/([lr])ves$/i' => "$1f",
        '/(tive)s$/i' => "$1",
        '/(hive)s$/i' => "$1",
        '/(li|wi|kni)ves$/i' => "$1fe",
        '/(shea|loa|lea|thie)ves$/i' => "$1f",
        '/(^analy)ses$/i' => "$1sis",
        '/((a)naly|(b)a|(d)iagno|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => "$1$2sis",
        '/([ti])a$/i' => "$1um",
        '/(n)ews$/i' => "$1ews",
        '/(h|bl)ouses$/i' => "$1ouse",
        '/(corpse)s$/i' => "$1",
        '/(us)es$/i' => "$1",
        '/(us|ss)$/i' => "$1",
        '/s$/i' => ""
    );

    if (in_array (strtolower ($string), $uncountable))
      return $string;

    foreach ($irregular as $result => $pattern) {
      $pattern = '/' . $pattern . '$/i';

      if (preg_match ($pattern, $string))
        return preg_replace ($pattern, $result, $string);
    }

    foreach ($singular as $pattern => $result) {
      if (preg_match ($pattern, $string))
        return preg_replace ($pattern, $result, $string);
    }

    return $string;
  }
}

if (!function_exists ('camelize')) {
  function camelize ($s) {
    $s = preg_replace ('/[_-]+/','_', trim ($s));
    $s = str_replace (' ', '_', $s);

    $camelized = '';

    for ($i = 0, $n = strlen ($s); $i < $n; ++$i)
      $camelized .= ($s[$i] == '_') && (($i + 1) < $n) ? strtoupper ($s[++$i]) : $s[$i];

    $camelized = trim ($camelized,' _');

    if (strlen ($camelized) > 0)
      $camelized[0] = strtolower ($camelized[0]);

    return $camelized;
  }
}
if (!function_exists ('params')) {
  function params ($params, $keys) {
    if (!$params)
      return array ();

    if (!$keys)
      return $params;

    $result = array ();
    $key = null;

    foreach ($params as $param)
      if (in_array ($param, $keys))
        if (!isset ($result[$key = $param]))
          $result[$key] = array ();
        else ;
      else
        if (isset ($result[$key]))
          array_push ($result[$key], $param);
        else ;

    return $result;
  }
}