<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

if ($js_list) foreach ($js_list as $js) echo script_tag ($js) . (ENVIRONMENT !== 'production' ? "\n" : '');
