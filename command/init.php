<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

  include_once 'base.php';
  include_once 'functions/create.php';

  //       file       username       password       database       hostname
  // =============================================================================
  // php   init       [root          [password      [table         [127.0.0.1]]]]

  $file     = array_shift ($argv);
  $username = ($username = array_shift ($argv)) ? $username : 'root';
  $password = ($password = array_shift ($argv)) ? $password : 'password';
  $database = ($database = array_shift ($argv)) ? $database : 'table';
  $hostname = ($hostname = array_shift ($argv)) ? $hostname : '127.0.0.1';
  $temp_path = FCPATH . 'command/templates/init/';

  $results = array ();

  if (!is_writable ($path_config = FCPATH . 'application/config/'))
    console_error ("無法有 application/config/ 的寫入權限!");

  if (!is_writable ($path_logs = FCPATH . 'application/logs/'))
    console_error ("無法有 application/logs/ 的寫入權限!");

  $directories = array ('assets', 'temp', 'upload', 'application/cell/cache', 'application/cache/file', 'application/cache/config', 'application/cache/output', 'application/cache/model');
  $results = array_merge ($results, array_map (function ($directory) {
    $oldmask = umask (0);
    @mkdir ($path = FCPATH . $directory . '/', 0777, true);
    umask ($oldmask);

    return $path;
  }, $directories));

  $files = array (
      array ('name' => 'database.php',  'path' => $path_config, 'params' => array ('hostname' => $hostname, 'username' => $username, 'password' => $password, 'database' => $database)),
      array ('name' => 'query.log',     'path' => $path_logs,   'params' => array ()),
      array ('name' => 'delay_job.log', 'path' => $path_logs,   'params' => array ())
    );
  $results = array_merge ($results, array_map (function ($file) use ($temp_path) {
      $date = load_view ($temp_path . $file['name'], $file['params']);

      if (!write_file ($path = $file['path'] . $file['name'], $date))
        console_error ("寫入 " . $file['name'] . " 失敗!");

      $oldmask = umask (0);
      @chmod ($path, 0777);
      umask ($oldmask);

      return $path;
    }, $files));


  $results = array_map (function ($result) { $count = 1; return color ('Create: ', 'g') . str_replace (FCPATH, '', $result, $count); }, $results);
  array_unshift ($results, '初始化成功!');
  call_user_func_array ('console_log', $results);
