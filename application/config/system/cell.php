<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

$cell['folders'] = array (
    'cache'      => array ('application', 'cell', 'cache'),
    'controller' => array ('application', 'cell', 'controllers'),
    'view'       => array ('application', 'cell', 'views')
  );

$cell['is_enabled'] = true;
$cell['d4_cache_time'] = 60; // sec

$cell['driver'] = 'file'; // 'file', 'redis'

$cell['redis_main_key'] = array ('cell');

$cell['file_prefix'] = '_cell';
$cell['file_is_md5'] = true;

$cell['class_suffix']  = '_cell';
$cell['method_prefix'] = '_cache_';
