<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'migration.php';

if (!function_exists ('create_demo')) {
  function create_demo () {
    $db_line = color (str_repeat ('=', 80), 'N') . "\n";
    $line = color (str_repeat ('-', 80), 'w') . "\n";

    echo $db_line;
    $results = array ();
    $migrations = array ('event' => array (), 'attendee' => array (), 'tag' => array (), 'tag_event_map' => array ());
    array_walk ($migrations, function ($value, $key) use (&$results) {
      array_push ($results, implode ("\n", array_map (function ($result) { $count = 1; return color ('  Create: ', 'g') . str_replace (FCPATH, '', $result, $count); }, create_migration (FCPATH . 'command/templates/demo/' . $key . '/', $key, 'add'))));
    });
    echo implode ("\n", $results) . "\n" . $line;

    $results = array ();
    $models = array ('event' => array ('-p' => array ('cover')), 'attendee' => array (), 'tag' => array (), 'tag_event_map' => array ());
    array_walk ($models, function ($value, $key) use (&$results) {
      array_push ($results, implode ("\n", array_map (function ($result) { $count = 1; return color ('  Create: ', 'g') . str_replace (FCPATH, '', $result, $count); }, create_model (FCPATH . 'command/templates/demo/' . $key . '/', $key, isset ($value['-p']) ? $value['-p'] : array (), isset ($value['-f']) ? $value['-f'] : array ()))));
    });
    echo implode ("\n", $results) . "\n" . $line;

    $results = array ();
    $cells = array ('demo' => array ('main_menu'));
    array_walk ($cells, function ($value, $key) use (&$results) {
      array_push ($results, implode ("\n", array_map (function ($result) { $count = 1; return color ('  Create: ', 'g') . str_replace (FCPATH, '', $result, $count); }, create_cell (FCPATH . 'command/templates/demo/cell/', $key, $value))));
    });
    echo implode ("\n", $results) . "\n" . $line;


    $results = array ();
    $controllers = array ('events' => array (), 'tags' => array ());
    array_walk ($controllers, function ($value, $key) use (&$results) {
      array_push ($results, implode ("\n", array_map (function ($result) { $count = 1; return color ('  Create: ', 'g') . str_replace (FCPATH, '', $result, $count); }, create_controller (FCPATH . 'command/templates/demo/' . singularize ($key) . '/', $key, 'site', array ('index', 'show', 'add', 'create', 'edit', 'update', 'destroy')))));
    });
    echo implode ("\n", $results) . "\n" . $line;

    $results = run_migration (null);
    echo color ('注意! ', 'r');
    echo implode ("\n", $results) . "\n";

    $results = array ();
    array_push ($results, "migrations(" . implode(', ', array_keys ($migrations)) . ")");
    array_push ($results, "models(" . implode(', ', array_keys ($models)) . ")");
    array_push ($results, "cells(" . implode(', ', array_keys ($cells)) . ")");
    array_push ($results, "controllers(" . implode(', ', array_keys ($controllers)) . ")");

    return $results;
  }
}

if (!function_exists ('delete_demo')) {
  function delete_demo () {
    $db_line = color (str_repeat ('=', 80), 'N') . "\n";
    $line = color (str_repeat ('-', 80), 'w') . "\n";

    echo $db_line;
    $controllers = array ('events', 'tags');
    $results = array_map (function ($name) {
      return implode ("\n", array_map (function ($result) { $count = 1; return color ('  Delete: ', 'r') . str_replace (FCPATH, '', $result, $count); }, delete_controller ($name, 'site')));
    }, $controllers);
    echo implode ("\n", $results) . "\n" . $line;

    $cells = array ('demo');
    $results = array_map (function ($name) {
      return implode ("\n", array_map (function ($result) { $count = 1; return color ('  Delete: ', 'r') . str_replace (FCPATH, '', $result, $count); }, delete_cell ($name)));
    }, $cells);
    echo implode ("\n", $results) . "\n" . $line;

    $models = array ('event', 'attendee', 'tag', 'tag_event_map');
    $results = array_map (function ($name) {
      return implode ("\n", array_map (function ($result) { $count = 1; return color ('  Delete: ', 'r') . str_replace (FCPATH, '', $result, $count); }, delete_model ($name)));
    }, $models);
    echo implode ("\n", $results) . "\n" . $line;

    echo color ('注意! ', 'r');
    echo implode ("\n", array ('Migration 並沒有刪除，請注意資料庫版本!')) . "\n";

    $results = array ();
    array_push ($results, "models(" . implode (', ', $models) . ")");
    array_push ($results, "cells(" . implode (', ', $cells) . ")");
    array_push ($results, "controllers(" . implode (', ', $controllers) . ")");
    return $results;
  }
}