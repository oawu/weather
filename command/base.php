<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

  date_default_timezone_set ('Asia/Taipei');

  define ('EXT', '.php');
  define ('SELF', pathinfo (__FILE__, PATHINFO_BASENAME));
  define ('FCPATH', dirname (str_replace (SELF, '', __FILE__)) . '/');

  define ('BASEPATH', FCPATH . 'system/');
  define ('APPPATH', 'application/');

  define ('ENVIRONMENT', 'console');
