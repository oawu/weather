<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class OrmUploader {
  protected $error = null;
  protected $CI = null;
  protected $orm = null;
  protected $column_name = null;
  protected $column_value = null;
  private $configs = array ();

  public function __construct ($orm = null, $column_name = null) {
    if (!($orm && $column_name && in_array ($column_name, array_keys ($orm->attributes ()))))
      return $this->error = array ('OrmUploader 錯誤！', '初始化失敗！', '請檢查建構子參數！');

    $this->CI =& get_instance ();

    $this->orm = $orm;
    $this->column_name = $column_name;
    $this->column_value = $orm->$column_name;
    $orm->$column_name = $this;

    $this->configs = Cfg::system ('orm_uploader', 'uploader');
    $this->error = null;

    if (!in_array ($this->configs['unique_column'], array_keys ($orm->attributes ())))
      return $this->error = array ('OrmUploader 錯誤！', '無法取得 unique 欄位資訊！', '請 ORM select，或者修改 unique 欄位名稱(' . $this->configs['unique_column'] . ')！', '修改 unique 欄位名稱至 config/system/orm_uploader.php 設定檔修改！');

    if ($this->getDriver () == 's3')
      $this->CI->load->library ('S3', Cfg::system ('s3', 'buckets', $this->getS3Bucket ()));
  }
  // return string
  public function url ($key = '') {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : '';

    switch ($this->getDriver ()) {
      case 'local':
        return ($path = $this->path ($key)) ? base_url ($path) : $this->d4Url ();
        break;
      
      case 's3':
        return implode ('/', array_merge (array (rtrim ($this->configs['s3']['url'], '/')) , $this->path ($key)));
        break;
    }

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : '';
  }
  // return array
  public function path ($fileName = '') {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : array ();

    switch ($this->getDriver ()) {
      case 'local':
        if (is_readable (FCPATH . implode(DIRECTORY_SEPARATOR, $path = array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($fileName)))))
          return $path;
        else
          return array ();
        break;

      case 's3':
        return array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($fileName));
        break;
    }

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
  // return sring
  protected function d4Url () {
    return $this->configs['d4_url'];
  }
  // return array
  protected function getSavePath () {
    return ($id = $this->getColumnValue ($this->configs['unique_column'])) ? array ($this->getTableName (), $this->getColumnName (), floor ($id / 1000000), floor (($id % 1000000) / 10000), floor ((($id % 1000000) % 10000) / 100), ($id % 100)) : array ($this->getTableName (), $this->getColumnName ());
  }
  // return sring
  protected function getTableName () {
    return $this->orm->table ()->table;
  }
  // return sring
  protected function getColumnName () {
    return $this->column_name;
  }
  // return sring
  protected function getColumnValue ($column_name) {
    return isset ($this->orm->$column_name) ? $this->orm->$column_name : '';
  }
  // return sring
  public function __toString () {
    return  $this->getValue ();
  }
  // return sring
  public function getValue () {
    return  $this->error ? call_user_func_array ('error', $this->error) : (string)$this->column_value;
  }
  // return sring
  protected function getRandomName () {
    return uniqid (rand () . '_');
  }
  // return array
  protected function getBaseDirectory () {
    return $this->configs[$this->getDriver ()]['base_directory'];
  }
  // return array
  protected function getDriver () {
    return $this->configs['driver'];
  }
  // return array
  protected function getS3Bucket () {
    return $this->configs['s3']['bucket'];
  }
  // return array
  protected function getDebug () {
    return $this->configs['debug'];
  }
  // return array
  protected function getTempDirectory () {
    return $this->configs['temp_directory'];
  }
  // return sring
  private function _moveOriFile ($fileInfo, $isUseMoveUploadedFile) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : '';

    if (!is_writable (FCPATH . implode (DIRECTORY_SEPARATOR, $path = $this->getTempDirectory ())))
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '暫存資料夾不可讀寫或不存在！', '請檢查暫存資料夾是否存在以及可讀寫！', '預設值 暫存資料夾 請檢查 config/system/orm_uploader.php 設定檔！') : false;

    $temp = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($path, array ($this->getRandomName ())));

    if ($isUseMoveUploadedFile)
      @move_uploaded_file ($fileInfo['tmp_name'], $temp);
    else
      @rename ($fileInfo['tmp_name'], $temp);

    if (!is_readable ($temp))
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '移動檔案錯誤！路徑：' . $temp, '請程式設計者確認狀況！') : '';

    $oldmask = umask (0);
    @chmod ($temp, 0777);
    umask ($oldmask);

    return $temp;
  }
  // return array ()
  private function _verifySavePath () {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : array ();

    switch ($this->getDriver ()) {
      case 'local':
        if (!is_writable ($path = FCPATH . implode (DIRECTORY_SEPARATOR, $this->getBaseDirectory ())))
          return $this->getDebug () ? error ('OrmUploader 錯誤！', '資料夾不能儲存！路徑：' . $path, '請檢查 config/system/orm_uploader.php 設定檔！') : array ();

        if (!file_exists (FCPATH . implode (DIRECTORY_SEPARATOR, $path = array_merge ($this->getBaseDirectory (), $this->getSavePath ())))) {
          $oldmask = umask (0);
          @mkdir (FCPATH . implode (DIRECTORY_SEPARATOR, $path), 0777, true);
          umask ($oldmask);
        }

        if (!is_writable (FCPATH . implode (DIRECTORY_SEPARATOR, $path)))
          return $this->getDebug () ? error ('OrmUploader 錯誤！', '資料夾不能儲存！路徑：' . $path, '請程式設計者確認狀況！') : array ();
        else
          return $path;
        break;

      case 's3':
        return array_merge ($this->getBaseDirectory (), $this->getSavePath ());
        break;
    }

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
  // return array
  public function getAllPaths () {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : array ();

    switch ($this->getDriver ()) {
      case 'local':
        return is_writable (implode (DIRECTORY_SEPARATOR, $path = array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($this->getValue ())))) ? array ($path) : array ();
        break;

      case 's3':
        return array ($path = array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($this->getValue ())));
        break;
    }

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
  // return boolean
  protected function _cleanOldFile () {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : false;

    switch ($this->getDriver ()) {
      case 'local':
        if ($paths = $this->getAllPaths ())
          foreach ($paths as $path)
            if (file_exists ($path = FCPATH . implode (DIRECTORY_SEPARATOR, $path)) && is_file ($path))
              if (!@unlink ($path))
                return $this->getDebug () ? error ('OrmUploader 錯誤！', '清除檔案發生錯誤！', '請程式設計者確認狀況！') : false;
        return true;
        break;
      
      case 's3':
        if ($paths = $this->getAllPaths ())
          foreach ($paths as $path)
            if (!S3::deleteObject ($this->getS3Bucket (), implode (DIRECTORY_SEPARATOR, $path)))
              return $this->getDebug () ? error ('OrmUploader 錯誤！', '清除檔案發生錯誤！', '請程式設計者確認狀況！') : false;
        return true;
        break;
    }

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : false;
  }
  // return boolean
  protected function uploadColumn ($value) {
    $column_name = $this->column_name;
    $this->orm->$column_name = $value;
    $this->orm->save ();
    $this->column_value = $value;
    $this->orm->$column_name = $this;
    return true;
  }
  // return boolean
  protected function uploadColumnAndUpload ($value, $isSave = true) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : false;

    if (!$this->_cleanOldFile ())
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '清除檔案發生錯誤！', '請程式設計者確認狀況！') : false;

    if ($isSave && $this->uploadColumn ($value))
      return true;

    return true;
  }
  // return boolean
  protected function moveFileAndUploadColumn ($temp, $save_path, $ori_name) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : array ();

    switch ($this->getDriver ()) {
      case 'local':
        if ($this->uploadColumnAndUpload ('') && @rename ($temp, $save_path = FCPATH . implode (DIRECTORY_SEPARATOR, $save_path) . DIRECTORY_SEPARATOR . $ori_name))
          return $this->uploadColumnAndUpload ($ori_name);
        else
          return $this->getDebug () ? error ('OrmUploader 錯誤！', '搬移預設位置時發生錯誤！', 'temp：' . $temp, 'save_path：' . $save_path, 'name：' . $ori_name, '請程式設計者確認狀況！') : false;
        break;

      case 's3':
        if ($this->uploadColumnAndUpload ('') && S3::putObjectFile ($temp, $this->getS3Bucket (), implode (DIRECTORY_SEPARATOR, $save_path) . DIRECTORY_SEPARATOR . $ori_name, S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years')))))
          return $this->uploadColumnAndUpload ($ori_name) && @unlink ($temp);
        else
          return $this->getDebug () ? error ('OrmUploader 錯誤！', '搬移預設位置時發生錯誤！', 'temp：' . $temp, 'save_path：' . $save_path, 'name：' . $ori_name, '請程式設計者確認狀況！') : false;
        break;
    }

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
  // return boolean
  public function put ($fileInfo) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : false;

    if (is_array ($fileInfo)) {
      foreach (array ('name', 'type', 'tmp_name', 'error', 'size') as $key)
        if (!isset ($fileInfo[$key]))
          return $this->getDebug () ? error ('OrmUploader 錯誤！', '參數格式錯誤！', '請程式設計者確認狀況！') : false;
        else ;
      $name = $fileInfo['name'];
      $isUseMoveUploadedFile = true;
    } else if (is_string ($fileInfo) && is_file ($fileInfo) && is_writable ($fileInfo)) {
      $name = basename ($fileInfo);
      $fileInfo = array ('name' => 'file', 'type' => '', 'tmp_name' => $fileInfo, 'error' => '', 'size' => '1');
      $isUseMoveUploadedFile = false;
    } else {
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '參數格式錯誤！', '請程式設計者確認狀況！') : false;
    }

    $format = pathinfo ($name = preg_replace ("/[^a-zA-Z0-9\\._-]/", "", $name), PATHINFO_EXTENSION);
    if (!($name = pathinfo ($name, PATHINFO_FILENAME)))
      $name = $this->getRandomName ();
    $name .= $format ? '.' . $format : '';

    if (!($temp = $this->_moveOriFile ($fileInfo, $isUseMoveUploadedFile)))
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '搬移至暫存資料夾時發生錯誤！', '請檢查暫存資料夾是否存在以及可讀寫！', '預設值 暫存資料夾 請檢查 config/system/orm_uploader.php 設定檔！') : false;

    if (!($save_path = $this->_verifySavePath ()))
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '確認儲存路徑發生錯誤！', '請程式設計者確認狀況！') : false;

    if (!($result = $this->moveFileAndUploadColumn ($temp, $save_path, $name)))
      return $this->getDebug () ? error ('OrmUploader 錯誤！', '搬移預設位置時發生錯誤！', '請程式設計者確認狀況！') : false;

    return $result;
  }
  // return OrmUploader object
  public static function bind ($column_name, $class_name = null) {
    if (!$column_name)
      return error ('OrmUploader 錯誤！', 'OrmUploader::bind 參數錯誤！', '請確認 OrmUploader::bind 的使用方法的正確性！');

    if (!($trace = debug_backtrace (DEBUG_BACKTRACE_PROVIDE_OBJECT)))
      return error ('OrmUploader 錯誤！', '取得 debug_backtrace 發生錯誤，無法取得 debug_backtrace！', '請確認 OrmUploader::bind 的使用方法的正確性！');

    if (!(isset ($trace[1]['object']) && is_object ($orm = $trace[1]['object'])))
      return error ('OrmUploader 錯誤！', '取得 debug_backtrace 回傳結構有誤，無法取得上層物件！', '請確認 OrmUploader::bind 的使用方法的正確性！');

    if (!$class_name)
      $class_name = get_class ($orm) . Cfg::system ('orm_uploader', 'instance', 'class_suffix');

    if (is_readable ($path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge (Cfg::system ('orm_uploader', 'uploader', 'instance', 'directory'), array ($class_name . EXT)))))
      require_once $path;
    else
      $class_name = get_called_class ();
    return $object = new $class_name ($orm, $column_name);
  }
  // return boolean
  public function cleanAllFiles ($isSave = true) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : false;

    return $this->uploadColumnAndUpload ('');
  }
  // return boolean
  public function put_url ($url) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : false;

    $format = pathinfo ($url, PATHINFO_EXTENSION);
    $temp = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->getTempDirectory (), array ($this->getRandomName () . ($format ? '.' . $format : ''))));

    if (($temp = download_web_file ($url, $temp)) && $this->put ($temp, false))
      return file_exists ($temp) ? @unlink ($temp) : true;
    else
      return false;

    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : false;
  }
}

include_once 'OrmImageUploader.php';

include_once 'OrmFileUploader.php';
