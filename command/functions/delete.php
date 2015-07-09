<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'functions.php';

if (!function_exists ('delete_controller')) {
  function delete_controller ($name, $action) {
    $results = array ();
    $name = strtolower ($name);
    $action = $action ? $action : 'site';

    $controller_path = FCPATH . 'application/controllers/' . ($action != 'site' ? $action . '/': '') . $name . EXT;
    $contents_path = FCPATH . 'application/views/content/' . $action . '/' . $name . '/';

    directory_delete ($contents_path, true, $results);
    if (delete_file ($controller_path))
      array_push ($results, $controller_path);

    return $results;
  }
}

if (!function_exists ('delete_model')) {
  function delete_model ($name) {
    $results = array ();
    $name = singularize ($name);

    $uploader_class_suffix = 'ImageUploader';

    $image_uploader_class_suffix = 'ImageUploader';
    $file_uploader_class_suffix  = 'FileUploader';

    $uploaders_path = FCPATH . 'application/third_party/orm_uploaders/';


    $model_path = FCPATH . 'application/models/' . ucfirst (camelize ($name)) . EXT;
    $content = read_file ($model_path);

    preg_match_all ('/OrmImageUploader::bind\s*\((?P<k>.*)\);/', $content, $image_uploaders);

    $image_uploaders = array_map (function ($image_uploader) use ($name, $image_uploader_class_suffix) {
      return isset ($image_uploader[1]) ? $image_uploader[1] : ucfirst (camelize ($name)) . $image_uploader_class_suffix;
    }, array_map (function ($image_uploader) {
      $pattern = '/(["\'])(?P<kv>(?>[^"\'\\\]++|\\\.|(?!\1)["\'])*)\1?/';
      preg_match_all ($pattern, $image_uploader, $image_uploaders);
      return $image_uploaders['kv'];
    }, $image_uploaders['k']));

    array_map (function ($image_uploader) use ($uploaders_path, &$results) {
      if (delete_file ($uploaders_path . $image_uploader . EXT))
        array_push ($results, $uploaders_path . $image_uploader . EXT);
    }, $image_uploaders);


    preg_match_all ('/OrmFileUploader::bind\s*\((?P<k>.*)\);/', $content, $file_uploaders);

    $file_uploaders = array_map (function ($file_uploader) use ($name, $file_uploader_class_suffix) {
      return isset ($file_uploader[1]) ? $file_uploader[1] : ucfirst (camelize ($name)) . $file_uploader_class_suffix;
    }, array_map (function ($file_uploader) {
      $pattern = '/(["\'])(?P<kv>(?>[^"\'\\\]++|\\\.|(?!\1)["\'])*)\1?/';
      preg_match_all ($pattern, $file_uploader, $file_uploaders);
      return $file_uploaders['kv'];
    }, $file_uploaders['k']));

    array_map (function ($file_uploader) use ($uploaders_path, &$results) {
      if (delete_file ($uploaders_path . $file_uploader . EXT))
        array_push ($results, $uploaders_path . $file_uploader . EXT);
    }, $file_uploaders);

    if (delete_file ($model_path))
      array_push ($results, $model_path);
    return $results;
  }
}

if (!function_exists ('delete_cell')) {
  function delete_cell ($name) {
    $results = array ();
    $name = strtolower ($name);
    $class_suffix  = '_cell';

    $controller_path = FCPATH . 'application/cell/controllers/' . $name . $class_suffix . EXT;
    $views_path = FCPATH . 'application/cell/views/' . $name . $class_suffix . '/';

    directory_delete ($views_path, true, $results);

    if (delete_file ($controller_path))
      array_push ($results, $controller_path);

    return $results;
  }
}