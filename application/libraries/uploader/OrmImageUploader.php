<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class OrmImageUploader extends OrmUploader {
  private $configs = array ();

  public function __construct ($orm = null, $column_name = null) {
    if (!($orm && $column_name && in_array ($column_name, array_keys ($orm->attributes ()))))
      return $this->error = array ('OrmUploader 錯誤！', '初始化失敗！', '請檢查建構子參數！');

    parent::__construct ($orm, $column_name);

    $this->configs = Cfg::system ('orm_uploader', 'image_uploader');

    $this->CI->load->library ('image/ImageUtility');
  }
  // return sring
  protected function d4Url () {
    return $this->configs['d4_url'];
  }
  // return array
  protected function getVersions () {
    return $this->configs['default_version'];
  }
  // return array
  public function path ($key = '') {
    if (($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version']) && isset ($versions[$key]) && ($fileName = $key . $this->configs['separate_symbol'] . $this->getValue ()))
      return parent::path ($fileName);
    else
      return array ();
    return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
  // return boolean
  private function _utility ($image, $save, $key, $version) {
    if ($version)
      if (is_callable (array ($image, $method = array_shift ($version))))
        call_user_func_array (array ($image, $method), $version);
      else
        return $this->getDebug () ? error ('OrmImageUploader 錯誤！', 'ImageUtility 無法呼叫的 method，method：' . $method, '請程式設計者確認狀況！') : '';
    return $image->save ($save, true);
  }
  // return array
  public function getAllPaths () {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : array ();

    if (!($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version']))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', 'Versions 格式錯誤，請檢查 getVersions () 或者 default_version！', '預設值 default_version 請檢查 config/system/orm_uploader.php 設定檔！') : '';

    $paths = array ();

    switch ($this->getDriver ()) {
      case 'local':
        foreach ($versions as $key => $version)
          if (is_writable (implode (DIRECTORY_SEPARATOR, $path = array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($key . $this->configs['separate_symbol'] . $this->getValue ())))))
            array_push ($paths, $path);
        return $paths;
        break;

      case 's3':
        foreach ($versions as $key => $version)
          array_push ($paths, array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($key . $this->configs['separate_symbol'] . $this->getValue ())));
        return $paths;
        break;
    }
    return $this->getDebug () ? error ('OrmUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
  // return boolean
  protected function moveFileAndUploadColumn ($temp, $save_path, $ori_name) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : '';

    if (!($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version']))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', 'Versions 格式錯誤，請檢查 getVersions () 或者 default_version！', '預設值 default_version 請檢查 config/system/orm_uploader.php 設定檔！') : '';

    if (!is_writable (FCPATH . implode (DIRECTORY_SEPARATOR, $path = $this->getTempDirectory ())))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '暫存資料夾不可讀寫或不存在！', '請檢查暫存資料夾是否存在以及可讀寫！', '預設值 暫存資料夾 請檢查 config/system/orm_uploader.php 設定檔！') : false;

    $news = array ();
    try {
      foreach ($versions as $key => $version) {
        $image = ImageUtility::create ($temp, null);
        $name = !isset ($name) ? $this->getRandomName () . ($this->configs['auto_add_format'] ? '.' . $image->getFormat () : '') : $name;
        $new_name = $key . $this->configs['separate_symbol'] . $name;
        $new_path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($path, array ($new_name)));
        array_push ($news, array ('name' => $new_name, 'path' => $new_path));

        if (!$this->_utility ($image, $new_path, $key, $version))
          return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '圖想處理失敗！', '請程式設計者確認狀況！') : false;
      }
    } catch (Exception $e) {
      return $this->getDebug () ? call_user_func_array ('error', $e->getMessages ()) : '';
    }

    if (count ($news) != count ($versions))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '不明原因錯誤！', '請程式設計者確認狀況！') : false;

    switch ($this->getDriver ()) {
      case 'local':
        @self::uploadColumnAndUpload ('');

        foreach ($news as $new)
          if (!@rename ($new['path'], FCPATH . implode (DIRECTORY_SEPARATOR, $save_path) . DIRECTORY_SEPARATOR . $new['name']))
            return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '不明原因錯誤！', '請程式設計者確認狀況！') : false;
        return self::uploadColumnAndUpload ($name) && @unlink ($temp);
        break;

      case 's3':
        @self::uploadColumnAndUpload ('');
        foreach ($news as $new)
          if (!(S3::putObjectFile ($new['path'], $this->getS3Bucket (), implode (DIRECTORY_SEPARATOR, $save_path) . DIRECTORY_SEPARATOR . $new['name'], S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years')))) && @unlink ($new['path'])))
            return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '不明原因錯誤！', '請程式設計者確認狀況！') : false;
        return self::uploadColumnAndUpload ($name) && @unlink ($temp);
        break;
    }

    return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : false;
  }
  // return array
  public function save_as ($key, $version) {
    if ($this->error)
      return $this->getDebug () ? call_user_func_array ('error', $this->error) : array ();

    if (!($key && $version))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '參數錯誤，請檢查 save_as 函式參數！', '請程式設計者確認狀況！') : array ();

    if (!(($versions = ($versions = $this->getVersions ()) ? $versions : $this->configs['default_version'])))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', 'Versions 格式錯誤，請檢查 getVersions () 或者 default_version！', '預設值 default_version 請檢查 config/system/orm_uploader.php 設定檔！') : array ();

    if (in_array ($key, $keys = array_keys ($versions)))
      return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '已經有相符合的 key 名稱，key：' . $key, '目前的 key 有：' . implode (', ', $keys)) : array ();

    switch ($this->getDriver ()) {
      case 'local':
        foreach ($versions as $ori_key => $ori_version)
          if (is_readable (FCPATH . implode (DIRECTORY_SEPARATOR, $ori_path = array_merge ($this->getBaseDirectory (), $this->getSavePath (), array ($ori_key . $this->configs['separate_symbol'] . ($name = $this->getValue ()))))))
            break;

        if (!$ori_path)
          return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '沒有任何的檔案可以被使用！', '請確認 getVersions () 函式內有存在的檔案可被另存！', '請程式設計者確認狀況！') : array ();

        if (!file_exists (FCPATH . implode (DIRECTORY_SEPARATOR, ($path = array_merge ($this->getBaseDirectory (), $this->getSavePath ()))))) {
          $oldmask = umask (0);
          @mkdir (FCPATH . implode (DIRECTORY_SEPARATOR, $path), 0777, true);
          umask ($oldmask);
        }

        if (!is_writable (FCPATH . implode (DIRECTORY_SEPARATOR, $path)))
          return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '資料夾不能儲存！路徑：' . $path, '請程式設計者確認狀況！') : '';

        try {
          $image = ImageUtility::create (FCPATH . implode (DIRECTORY_SEPARATOR, $ori_path), null);
          $path = array_merge ($path, array ($key . $this->configs['separate_symbol'] . $name));

          if ($this->_utility ($image, FCPATH . implode (DIRECTORY_SEPARATOR, $path), $key, $version))
            return $path;
          else
            return array ();
        } catch (Exception $e) {
          return $this->getDebug () ? call_user_func_array ('error', $e->getMessages ()) : '';
        }
        break;

      case 's3':
        if (!@S3::getObject ($this->getS3Bucket (), implode (DIRECTORY_SEPARATOR, array_merge ($path = array_merge ($this->getBaseDirectory (), $this->getSavePath ()), array ($fileName = array_shift (array_keys ($versions)) . $this->configs['separate_symbol'] . ($name = $this->getValue ())))), FCPATH . implode (DIRECTORY_SEPARATOR, $fileName = array_merge ($this->getTempDirectory (), array ($fileName))))) 
          return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '沒有任何的檔案可以被使用！', '請確認 getVersions () 函式內有存在的檔案可被另存！', '請程式設計者確認狀況！') : array ();

        try {
          $image = ImageUtility::create ($fileName = FCPATH . implode (DIRECTORY_SEPARATOR, $fileName), null);
          $newPath = array_merge ($path, array ($newName = $key . $this->configs['separate_symbol'] . $name));

          if ($this->_utility ($image, FCPATH . implode (DIRECTORY_SEPARATOR, $newFileName = array_merge ($this->getTempDirectory (), array ($newName))), $key, $version) && S3::putObjectFile ($newFileName = FCPATH . implode (DIRECTORY_SEPARATOR, $newFileName), $this->getS3Bucket (), implode (DIRECTORY_SEPARATOR, $newPath), S3::ACL_PUBLIC_READ, array (), array ('Cache-Control' => 'max-age=315360000', 'Expires' => gmdate ('D, d M Y H:i:s T', strtotime ('+5 years')))) && @unlink ($newFileName) && @unlink ($fileName))
            return $newPath;  
          else
            return array ();
        } catch (Exception $e) {
          return $this->getDebug () ? call_user_func_array ('error', $e->getMessages ()) : '';
        }
        break;
    }

    return $this->getDebug () ? error ('OrmImageUploader 錯誤！', '未知的 driver，系統尚未支援 ' . $this->getDriver () . ' 的空間！', '請檢查 config/system/orm_uploader.php 設定檔！') : array ();
  }
}
