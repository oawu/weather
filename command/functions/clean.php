<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'ci.php';
include_once 'functions.php';

if (!function_exists ('clean_cache_cell')) {
  function clean_cache_cell ($CI) {
    $CI->load->library ('cell');

    if ($CI->cell->clean_cell (array ('*')))
      return '已經清除所有 cell cache!';
    else
      return '清除 cell cache 失敗!';
  }
}

if (!function_exists ('clean_cache_static')) {
  function clean_cache_static ($CI) {
    $CI->load->library ('cfg');
    $static = Cfg::system ('static', 'assets_folder');

    if (directory_delete (FCPATH . implode (DIRECTORY_SEPARATOR, $static), false))
      return '已經清除所有 static cache!';
    else
      return '清除 static cache 失敗!';
  }
}

if (!function_exists ('clean_cache_file')) {
  function clean_cache_file ($CI) {
    $CI->load->library ('cfg');

    $cache = array ();
    array_push ($cache, Cfg::system ('cache', 'file'));
    array_push ($cache, Cfg::system ('cache', 'output'));
    array_push ($cache, Cfg::system ('cache', 'config'));
    array_push ($cache, Cfg::system ('cache', 'model'));

    if (array_filter (array_map (function ($t) { return directory_delete (FCPATH . implode (DIRECTORY_SEPARATOR, $t), false); }, $cache)))
      return '已經清除所有 file cache!';
    else
      return '清除 file cache 失敗!';
  }
}

if (!function_exists ('clean_cache_model')) {
  function clean_cache_model ($CI) {
    $CI->load->library ('cfg');
    if (ActiveRecord\Cache::flush ())
      return '已經清除所有 model cache!';
    else
      return '清除 model cache 失敗!';
  }
}

if (!function_exists ('clean_cache')) {
  function clean_cache ($name = null) {
    $results = array ();
    $CI = new CI_Controller ();

    switch (strtolower ($name)) {
      case 'cell':
        array_push ($results, clean_cache_cell ($CI));
        break;

      case 'static': case 'assets':
        array_push ($results, clean_cache_static ($CI));
        break;

      case 'file':
        array_push ($results, clean_cache_file ($CI));
        break;

      case 'model':
        array_push ($results, clean_cache_model ($CI));
        break;

      default:
        array_push ($results, clean_cache_cell ($CI));
        array_push ($results, clean_cache_static ($CI));
        array_push ($results, clean_cache_file ($CI));
        array_push ($results, clean_cache_model ($CI));
        break;
    }

    return $results;
  }
}