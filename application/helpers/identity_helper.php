<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
if (!function_exists ('identity')) {
  function identity () {
    $CI =& get_instance ();
    if (!isset ($CI->identity))
      $CI->load->library ('identity');
    return $CI->identity;
  }
}
