<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

if ($hidden_list) foreach ($hidden_list as $hidden) echo oa_hidden ($hidden) . (ENVIRONMENT !== 'production' ? "\n" : '');