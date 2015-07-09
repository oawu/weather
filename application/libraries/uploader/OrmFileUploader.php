<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class OrmFileUploader extends OrmUploader {
  private $configs = array ();

  public function __construct ($orm = null, $column_name = null) {
    if (!($orm && $column_name && in_array ($column_name, array_keys ($orm->attributes ()))))
      return $this->error = array ('OrmUploader 錯誤！', '初始化失敗！', '請檢查建構子參數！');

    parent::__construct ($orm, $column_name);

    $this->configs = Cfg::system ('orm_uploader', 'file_uploader');
  }
  // return string
  public function url () {
    return parent::url ('');
  }
  // return array
  public function path () {
    return parent::path ($this->getValue ());
  }
}
