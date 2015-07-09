<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

  include_once 'base.php';
  include_once 'functions/delete.php';
  $results = array ();
  $temp_path = FCPATH . 'command/templates/create/';

  //       file     type         name              action
  // =============================================================
  // php   delete   controller   controller_name   [site | admin | delay]
  // php   delete   model        model_name
  // php   delete   cell         cell_name
  // php   delete   demo

  $file   = array_shift ($argv);
  $type   = array_shift ($argv);
  $name   = array_shift ($argv);
  $action = array_shift ($argv);

  switch ($type) {
    case 'controller':
      $results = delete_controller ($name, $action);
      break;

    case 'model':
      $results = delete_model ($name);
      break;

    case 'cell':
      $results = delete_cell ($name);
      break;

    case 'demo':
      include 'functions/demo.php';
      $results = delete_demo ();
      break;

    default:
      return console_error ('指令錯誤!', '只接受 controller、model、cell、demo 三種指令。');
  }

  $results = array_map (function ($result) { $count = 1; return color ('Delete: ', 'r') . str_replace (FCPATH, '', $result, $count); }, $results);
  array_unshift ($results, '刪除成功!');
  call_user_func_array ('console_log', $results);
