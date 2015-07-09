<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
if (!function_exists ('get_upload_file')) {
  function get_upload_file ($tag_name, $type = 'all') {
    $files_list = transposed_all_files_array ($_FILES);

    $list = element ($tag_name, $files_list, array ());

    if ($type == 'one') if (count ($list)) return $list[0]; else return null;
    else if (count ($list)) return $list; else return array ();
  }
} 

if (!function_exists ('element')) {
  function element ($item, $array, $default = false) {
    if (!isset ($array[$item]) || ($array[$item] == "")) {
      return $default;
    }
    return $array[$item];
  }
}

if (!function_exists ('transposed_all_files_array')) {
  function transposed_all_files_array ($files_list) {
    $new_array = array ();

    if (count ($files_list)) {
      foreach ($files_list as $key => $files) {
        $new_array[$key] = transposed_files_array ($files);
      }
    }
    return $new_array;
  }
}

if (!function_exists ('transposed_files_array')) {
  function transposed_files_array ($files) {
    $filter_size = true;
    $new_array   = array ();
    $files_count = count ($files['name']);
    $files_keys  = array_keys ($files);

    for ($i = 0, $j = 0; $i < $files_count; $i++) {
      if ((!is_array ($files['size']) && (!$filter_size || $files['size']!=0)) || (!$filter_size || $files['size'][$i] !=0)){
        foreach ($files_keys as $key) {
          if (is_array ($files[$key])) {
            $new_array[$j][$key] = $files[$key][$i];
          } else {
            $new_array[$j][$key] = $files[$key];
          }
        }
        $j++;
      }
    }
    return $new_array;
  }
}