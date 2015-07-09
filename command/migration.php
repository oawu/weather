<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

  include_once 'base.php';
  include_once 'functions/migration.php';

  //       file       version
  // ==========================================
  // php   migration  [0 | 1 | 2...]

  $file    = array_shift ($argv);
  $version = array_shift ($argv);

  $results = run_migration ($version);

  array_unshift ($results, '更新成功!');
  call_user_func_array ('console_log', $results);