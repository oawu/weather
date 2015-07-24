<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

if ($meta_list) foreach ($meta_list as $meta) echo oa_meta ($meta) . (ENVIRONMENT !== 'production' ? "\n" : '');
