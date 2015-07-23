<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class TownPicImageUploader extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '50x50c' => array ('adaptiveResizeQuadrant', 50, 50, 'c'),
        '100x100c' => array ('adaptiveResizeQuadrant', 100, 100, 'c'),
        '200x200c' => array ('adaptiveResizeQuadrant', 200, 200, 'c'),
        '400x400c' => array ('adaptiveResizeQuadrant', 400, 400, 'c'),
        '800x800c' => array ('adaptiveResizeQuadrant', 800, 800, 'c'),
      );
  }
}