<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

  include_once 'base.php';
  include_once 'functions/create.php';

  $results = array ();
  $temp_path = FCPATH . 'command/templates/create/';

  //       file     type         name              action
  // =============================================================
  // php   create   controller   controller_name   [site | admin | delay]
  // php   create   model        model_name        [[(-p | -pic) column_name1, column_name2...] | [(-f | -file) column_name1, column_name2...]]
  // php   create   migration    table_name        [(-a | -add) | (-e | -edit) | (-d | -delete | -del | -drop)]
  // php   create   cell         cell_name         [method_name1, method_name2...]
  // php   create   demo

  $file   = array_shift ($argv);
  $type   = array_shift ($argv);
  $name   = array_shift ($argv);
  $action = !in_array (strtolower ($type), array('model')) ? array_shift ($argv) : $argv;

  switch (strtolower ($type)) {
    case 'controller':
      $results = create_controller ($temp_path, $name, $action);
      break;

    case 'model':
      $params = params ($action, array ('-p', '-f', '-pic', '-file'));
      $images = array_merge ($images = isset ($params['-p']) ? $params['-p'] : array (), isset ($params['-pic']) ? $params['-pic'] : array ());
      $files  = array_merge ($files = isset ($params['-f']) ? $params['-f'] : array (), isset ($params['-file']) ? $params['-file'] : array ());

      $results = create_model ($temp_path, $name, $images, $files);
      break;

    case 'migration':
      $results = create_migration ($temp_path, $name, $action);
      break;

    case 'cell':
      $results = create_cell ($temp_path, $name, array_merge (array ($action), $argv));
      break;

    case 'demo':
      include 'functions/demo.php';
      $results = create_demo ();
      break;

    default:
      return console_error ('指令錯誤!', '只接受 controller、model、migration、cell、demo 四種指令。');
  }

  $results = array_map (function ($result) { $count = 1; return color ('Create: ', 'g') . str_replace (FCPATH, '', $result, $count); }, $results);
  array_unshift ($results, '新增成功!');
  call_user_func_array ('console_log', $results);
