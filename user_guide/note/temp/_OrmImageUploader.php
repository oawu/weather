<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class OrmImageUploader {
  private $CI = null;
  private $orm = null;
  private $column_name = null;
  private $column_value = null;
  private $configs = array ();
  private $error = null;

  public function __construct ($orm = null, $column_name = null) {
    if (!($orm && $column_name && in_array ($column_name, array_keys ($orm->attributes ()))))
      return $this->error = array ('OrmImageUploader 錯誤！', '初始化失敗！', '請檢查建構子參數！');

    $this->CI =& get_instance ();
    $this->CI->load->library ('image/ImageUtility');
    // $this->CI->load->helper ('directory');
    // $this->CI->load->helper ('file');
    // $this->CI->load->library ("cfg");

    $this->orm = $orm;
    $this->column_name = $column_name;
    $this->column_value = $orm->$column_name;
    $orm->$column_name = $this;
    $this->configs = Cfg::system ('orm_image_uploader');
    $this->error = null;

    if (!in_array ($this->configs['unique_column'], array_keys ($orm->attributes ())))
      return $this->error = array ('OrmImageUploader 錯誤！', '無法取得 unique 欄位資訊！', '請 ORM select，或者修改 unique 欄位名稱(' . $this->configs['unique_column'] . ')！', '修改 unique 欄位名稱至 config/system/orm_image_uploader.php 設定檔修改！');
  }

  // return sring
  public function __toString () {
    return  $this->error ? call_user_func_array ('error', $this->error) : (string)$this->column_value;
  }
  // return sring
  public function getColumnValue ($column_name) {
    return isset ($this->orm->$column_name) ? $this->orm->$column_name : '';
  }
  // return sring
  public function getTableName () {
    return $this->orm->table ()->table;
  }
  // return sring
  public function getColumnName () {
    return $this->column_name;
  }
  // return array
  public function getSavePath () {
    return ($id = $this->getColumnValue ($this->configs['unique_column'])) ? array ($this->getTableName (), $this->getColumnName (), floor ($id / 1000000), floor (($id % 1000000) / 10000), floor ((($id % 1000000) % 10000) / 100), ($id % 100)) : array ($this->getTableName (), $this->getColumnName ());
  }
  // return array
  public function getVersions () {
    return $this->configs['default_version'];
  }
  // return sring
  public function d4_url () {
    return $this->configs['d4_url'];
  }
  // return sring
  public function getFileName () {
    return uniqid (rand () . '_');
  }

  // return array
  public function path ($key = '') {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : array ();

    switch ($this->configs['bucket']) {
      case 'local':
      if (($fileName = (string)$this) && ($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version']) && isset ($versions[$key]) && is_readable (FCPATH . implode(DIRECTORY_SEPARATOR, $path = array_merge ($this->configs['base_directory'][$this->configs['bucket']], $this->getSavePath (), array ($key . $this->configs['separate_symbol'] . $fileName)))))
        return $path;
      else
        return array ();
        break;
    }

    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : array ();
  }

  // return string
  public function url ($key = '') {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : '';

    switch ($this->configs['bucket']) {
      case 'local':
        return ($path = $this->path ($key)) ? base_url ($path) : $this->d4_url ();
        break;
    }

    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : '';
  }

  // return string
  public function _createNewFiles ($fileInfo, $isUseMoveUploadedFile = false) {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : '';

    if (!($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version']))
      return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', 'Versions 格式錯誤，請檢查 getVersions () 或者 default_version！', '預設值 default_version 請檢查 config/system/orm_image_uploader.php 設定檔！') : '';

    switch ($this->configs['bucket']) {
      case 'local':
        if (!is_writable ($path = FCPATH . implode (DIRECTORY_SEPARATOR, $this->configs['base_directory']['local'])))
          return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '資料夾不能儲存！路徑：' . $path, '請檢查 config/system/orm_image_uploader.php 設定檔！') : '';

        if (!file_exists (FCPATH . implode (DIRECTORY_SEPARATOR, $path = array_merge ($this->configs['base_directory']['local'], $this->getSavePath ())))) {
          $oldmask = umask (0);
          @mkdir (FCPATH . implode (DIRECTORY_SEPARATOR, $path), 0777, true);
          umask ($oldmask);
        }

        if (!is_writable (FCPATH . implode (DIRECTORY_SEPARATOR, $path)))
          return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '資料夾不能儲存！路徑：' . $path, '請程式設計者確認狀況！') : '';

        $temp = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['temp_directory'], array ($this->configs['temp_file_name'])));

        if ($isUseMoveUploadedFile)
          @move_uploaded_file ($fileInfo['tmp_name'], $temp);
        else
          @rename ($fileInfo['tmp_name'], $temp);

        if (!is_readable ($temp))
          return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '移動檔案錯誤！路徑：' . $temp, '請程式設計者確認狀況！') : '';

        $oldmask = umask (0);
        @chmod ($temp, 0777);
        umask ($oldmask);

        $result = true;

        try {
          $image = ImageUtility::create ($temp, null);
          $name = $this->getFileName () . ($this->configs['auto_add_format'] ? '.' . $image->getFormat () : '');

          foreach ($versions as $key => $version) {
            $new = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($path, array ($key . $this->configs['separate_symbol'] . $name)));
            $result &= $this->_utility ($image, $new, $key, $version);
          }
        } catch (Exception $e) {
          return $this->configs['debug'] ? call_user_func_array ('error', $e->getMessages ()) : '';
        }

        return ($result &= @unlink ($temp)) ? $name : '';
        break;
    }
    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : '';
  }

  // return boolean
  private function _cleanOldFiles ($saveFileName) {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : false;

    if (!(($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version']) && ($versions = array_keys ($versions))))
      return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', 'Versions 格式錯誤，請檢查 getVersions () 或者 default_version！', '預設值 default_version 請檢查 config/system/orm_image_uploader.php 設定檔！') : false;

    switch ($this->configs['bucket']) {
      case 'local':
        $result = true;

        foreach ($versions as $version)
          if (file_exists ($path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['base_directory']['local'], $this->getSavePath (), array ($version . $this->configs['separate_symbol'] . $this->column_value)))))
            $result &= @unlink ($path);

        if ($result) {
          $column_name = $this->column_name;
          $this->orm->$column_name = $saveFileName;
          $this->orm->save ();
          $this->column_value = $saveFileName;
          $this->orm->$column_name = $this;
        }
        return $result;
        break;
    }

    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : false;
  }

  // return boolean
  public function put ($fileInfo, $isUseMoveUploadedFile = true) {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : false;

    if (is_array ($fileInfo))
      foreach (array ('name', 'type', 'tmp_name', 'error', 'size') as $key)
        if (!isset ($fileInfo[$key]))
          return false;
        else ;
    else if (is_string ($fileInfo) && is_writable ($fileInfo))
      $fileInfo = array ('name' => 'file', 'type' => '', 'tmp_name' => $fileInfo, 'error' => '', 'size' => '1');
    else
      return false;

    return ($saveFileName = $this->_createNewFiles ($fileInfo, $isUseMoveUploadedFile)) && $this->_cleanOldFiles ($saveFileName);
  }

  // return boolean
  public function cleanAllFiles ($isAutoSave = true) {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : false;

    switch ($this->configs['bucket']) {
      case 'local':
        return $this->_cleanOldFiles ('');
        break;
    }

    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : false;
  }

  // return boolean
  public function put_url ($url) {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : false;

    $temp = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['temp_directory'], array ($this->configs['temp_file_name'])));

    if (($temp = download_web_file ($url, $temp)) && $this->put ($temp, false))
      return file_exists ($temp) ? @unlink ($temp) : true;
    else
      return false;

    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : false;
  }

  // return boolean
  private function _utility ($image, $save, $key, $version) {
    if ($version)
      if (is_callable (array ($image, $method = array_shift ($version))))
        call_user_func_array (array ($image, $method), $version);
      else
        return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', 'ImageUtility 無法呼叫的 method，method：' . $method, '請程式設計者確認狀況！') : '';
    return $image->save ($save, true);
  }

  // return array
  public function save_as ($key, $version) {
    if ($this->error)
      return $this->configs['debug'] ? call_user_func_array ('error', $this->error) : array ();

    if (!($key && $version))
      return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '參數錯誤，請檢查 save_as 函式參數！', '請程式設計者確認狀況！') : array ();

    if (!(($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version'])))
      return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', 'Versions 格式錯誤，請檢查 getVersions () 或者 default_version！', '預設值 default_version 請檢查 config/system/orm_image_uploader.php 設定檔！') : array ();

    switch ($this->configs['bucket']) {
      case 'local':
        if (in_array ($key, array_keys ($versions)))
          return is_readable (FCPATH . implode (DIRECTORY_SEPARATOR, $ori_path = array_merge ($this->configs['base_directory'][$this->configs['bucket']], $this->getSavePath (), array ($key . $this->configs['separate_symbol'] . (string)$this)))) ? $ori_path : '';

        foreach ($versions as $ori_key => $ori_version)
          if (is_readable (FCPATH . implode (DIRECTORY_SEPARATOR, $ori_path = array_merge ($this->configs['base_directory'][$this->configs['bucket']], $this->getSavePath (), array ($ori_key . $this->configs['separate_symbol'] . ($name = (string)$this))))))
            break;

        if (!$ori_path)
          return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '沒有任何的檔案可以被使用！', '請確認 getVersions () 函式內有存在的檔案可被另存！', '請程式設計者確認狀況！') : array ();

        if (!file_exists (FCPATH . implode (DIRECTORY_SEPARATOR, ($path = array_merge ($this->configs['base_directory'][$this->configs['bucket']], $this->getSavePath ()))))) {
          $oldmask = umask (0);
          @mkdir (FCPATH . implode (DIRECTORY_SEPARATOR, $path), 0777, true);
          umask ($oldmask);
        }

        if (!is_writable (FCPATH . implode (DIRECTORY_SEPARATOR, $path)))
          return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '資料夾不能儲存！路徑：' . $path, '請程式設計者確認狀況！') : '';

        try {
          $image = ImageUtility::create (FCPATH . implode (DIRECTORY_SEPARATOR, $ori_path), null);
          $path = array_merge ($path, array ($key . $this->configs['separate_symbol'] . $name));

          if ($this->_utility ($image, FCPATH . implode (DIRECTORY_SEPARATOR, $path), $key, $version))
            return $path;
          else
            return array ();
        } catch (Exception $e) {
          return $this->configs['debug'] ? call_user_func_array ('error', $e->getMessages ()) : '';
        }
        break;
    }

    return $this->configs['debug'] ? error ('OrmImageUploader 錯誤！', '未知的 bucket，系統尚未支援' . $this->configs['bucket'] . ' 的空間！', '請檢查 config/system/orm_image_uploader.php 設定檔！') : '';
  }

  // return OrmImageUploader object
  public static function bind ($column_name, $class_name = null) {
    if (!$column_name)
      return error ('OrmImageUploader 錯誤！', 'OrmImageUploader::bind 參數錯誤！', '請確認 OrmImageUploader::bind 的使用方法的正確性！');

    if (!($trace = debug_backtrace (DEBUG_BACKTRACE_PROVIDE_OBJECT)))
      return error ('OrmImageUploader 錯誤！', '取得 debug_backtrace 發生錯誤，無法取得 debug_backtrace！', '請確認 OrmImageUploader::bind 的使用方法的正確性！');

    if (!(isset ($trace[1]['object']) && is_object ($orm = $trace[1]['object'])))
      return error ('OrmImageUploader 錯誤！', '取得 debug_backtrace 回傳結構有誤，無法取得上層物件！', '請確認 OrmImageUploader::bind 的使用方法的正確性！');

    if (!$class_name)
      $class_name = get_class ($orm) . Cfg::system ('orm_image_uploader', 'instance', 'class_suffix');

    if (is_readable ($path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge (Cfg::system ('orm_image_uploader', 'instance', 'directory'), array ($class_name . EXT)))))
      require_once $path;
    else
      $class_name = get_called_class ();

    return $object = new $class_name ($orm, $column_name);
  }
}
