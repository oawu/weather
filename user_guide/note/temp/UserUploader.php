<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class UserUploader extends OrmImageUploader {

  public function d4_url () {
    return 'https://graph.facebook.com/100000100541088/picture?width=100&height=100';
  }

  public function getVersions () {
    return array (
        '' => array (),
        '50x50' => array ('resize', 50, 50, 'width'),
        '120x120' => array ('adaptiveResizeQuadrant', 120, 120, 'c')
      );
  }
}