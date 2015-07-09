<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 *
 * @note        Must use try catch
 */

include_once 'ImageUtilityException.php';

class ImageUtility {
  private $object = null;
  private $configs = array ();
  private $error = null;

  public function __construct ($file = '', $module = '', $options = array ()) {
    if (!($file && is_readable ($file)))
      return $this->error = array ('ImageUtility 錯誤！', '初始化失敗！', '檔案不可讀取，或者不存在！file：' . $file, '請檢查建構子參數！');

    $this->configs = Cfg::system ('image_utility');

    if (!(($module = $module ? strtolower ($module) : $this->configs['module']) && in_array ($module, array_keys ($modules = $this->configs['modules']))))
      throw new ImageUtilityException ('ImageUtility 錯誤！', '初始化失敗！', '參數 module 錯誤！module：' . $module, '請檢查建構子參數！');

    if (!class_exists ($modules[$module]))
      include_once $modules[$module] . EXT;

    $this->object = new $modules[$module] ($file, $options);
    $this->error = null;
  }

  // return array
  public function getError () {
    return $this->error;
  }
  // return ImageBaseUtility
  public function getObject () {
    return $this->object;
  }

  // return ImageBaseUtility
  public static function create ($fileName, $module = null, $options = array ()) {
    $uti = new ImageUtility ($fileName, $module = null, $options);

    if ($uti->getError ())
      throw new ImageUtilityException ($uti->getError ());

    return $uti->getObject () ? $uti->getObject () : null;
  }

  // return dimension format array
  public static function createDimension ($width, $height) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageBaseUtility 錯誤！', '取得尺寸失敗！width：' . $width . '，height：' . $height);

    return array (
      'width'  => $width,
      'height' => $height
    );
  }

  // return boolean
  public static function verifyDimension ($dimension) {
    return isset ($dimension['width']) && isset ($dimension['height']) && ($dimension['width'] > 0) && ($dimension['height'] > 0);
  }

  // return boolean
  public static function verifyColor (&$color) {
    $color = is_string ($color) ? self::colorHex2Rgb ($color) : $color;
    return is_array ($color) && (count ($color) == 3) && ($color[0] >= 0) && ($color[0] <= 255) && ($color[1] >= 0) && ($color[1] <= 255) && ($color[2] >= 0) && ($color[2] <= 255);
  }

  // return array
  public static function colorHex2Rgb ($hex) {
    if (($hex = str_replace ('#', '', $hex)) && ((strlen ($hex) == 3) || (strlen ($hex) == 6))) {
      if(strlen ($hex) == 3) {
        $r = hexdec (substr ($hex, 0, 1) . substr ($hex, 0, 1));
        $g = hexdec (substr ($hex, 1, 1) . substr ($hex, 1, 1));
        $b = hexdec (substr ($hex, 2, 1) . substr ($hex, 2, 1));
      } else {
        $r = hexdec (substr ($hex, 0, 2));
        $g = hexdec (substr ($hex, 2, 2));
        $b = hexdec (substr ($hex, 4, 2));
      }
      return array ($r, $g, $b);
    } else {
      return array ();
    }
  }

  // return boolean
  public static function make_block9 ($files, $save) {
    if (!(count ($files) >= 9))
      throw new ImageUtilityException ('ImageUtility 錯誤！', '參數錯誤，files count：' . count ($files), '參數 files 數量一定要大於 9！');

    if (!(($module = Cfg::system ('image_utility', 'module')) && in_array ($module, array_keys ($modules = Cfg::system ('image_utility', 'modules')))))
      throw new ImageUtilityException ('ImageUtility 錯誤！', '預設 module 錯誤！module：' . $module, '請檢查 config/system/image_utility.php 設定檔！');

    if (!class_exists ($modules[$module]))
      include_once $modules[$module] . EXT;

    return call_user_func_array (array ($modules[$module], 'make_block9'), array ($files, $save));
  }
}
