<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'ci.php';
include_once 'functions.php';

if (!function_exists ('run_migration')) {
  function run_migration ($version = null) {
    $version = $version !== null ? is_numeric($version) ? $version : null : null;

    $results = array ();
    $CI = new CI_Controller ();
    $CI->load->library ('migration');

    if ((($version === null) && !is_bool ($version = $CI->migration->latest ())) || !is_bool ($version = $CI->migration->version ($version)))
        array_push ($results, '目前 Migration 已經更新到 ' . sprintf ("%03s", $version) . ' 版本!');
    else
        array_push ($results, 'Migration 版本沒有任何更動!');

    return $results;
  }
}