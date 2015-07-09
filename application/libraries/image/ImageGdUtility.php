<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'ImageBaseUtility.php';

class ImageGdUtility extends ImageBaseUtility {
  private $options = array ();
  private $configs = array ();

  public function __construct ($fileName, $options = array ()) {
    parent::__construct ($fileName);

    $this->configs = Cfg::system ('image_gd_utility');

    $this->_init ()
         ->_setOptions ($options);
  }

  // return ImageGdUtility object
  private function _init () {
    if (!function_exists ('mime_content_type'))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '尚未找尋到 mime_content_type 函式！', '請確認 php.ini 中的 fileinfo 有開啟！');

    if (!$this->mime = mime_content_type ($this->getFileName ()))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '取不到檔案的 mime！', '請確認你的檔案格式正確性！');

    if (!(isset ($this->configs['mime_formats'][$this->mime]) && ($this->format = $this->configs['mime_formats'][$this->mime]) && in_array ($this->format, $this->configs['allow_formats'])))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '找尋不到符合的 mime，或者不支援處理的檔案格式！mime：' . $mime, '請檢查 config/system/image_gd_utility.php 設定檔！');

    if (!$this->image = $this->_getOldImage ($this->format))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', 'Create image 失敗！', '請程式設計者確認狀況！');

    if (!ImageUtility::verifyDimension ($this->dimension = $this->getDimension ($this->image)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '取得尺寸失敗！', '請程式設計者確認狀況！');

    return $this;
  }

  // return gd image object
  private function _getOldImage ($format) {
    switch ($format) {
      case 'gif':  return imagecreatefromgif ($this->getFileName ()); break;
      case 'jpg': return imagecreatefromjpeg ($this->getFileName ()); break;
      case 'png': return imagecreatefrompng ($this->getFileName ()); break;
      default: throw new ImageUtilityException ('ImageGdUtility 錯誤！', '不支援處理的檔案格式！format：' . $format, '請檢查 config/system/image_gd_utility.php 設定檔！'); return null; break;
    }
  }

  // return dimension format array
  public function getDimension ($image = null) {
    $image = $image ? $image : $this->_getOldImage ($this->format);
    return ImageUtility::createDimension (imagesx ($image), imagesy ($image));
  }

  // return ImageGdUtility object
  private function _setOptions ($options) {
    $this->options = array_merge ($this->configs['d4_options'], $options);
    return $this;
  }

  // return image object
  private function _preserveAlpha ($image) {
    if (($this->format == 'png') && ($this->options['preserveAlpha'] === true)) {
      imagealphablending ($image, false);
      imagefill ($image, 0, 0, imagecolorallocatealpha ($image, $this->options['alphaMaskColor'][0], $this->options['alphaMaskColor'][1], $this->options['alphaMaskColor'][2], 0));
      imagesavealpha ($image, true);
    }

    if (($this->format == 'gif') && ($this->options['preserveTransparency'] === true)) {
      imagecolortransparent ($image, imagecolorallocate ($image, $this->options['transparencyMaskColor'][0], $this->options['transparencyMaskColor'][1], $this->options['transparencyMaskColor'][2]));
      imagetruecolortopalette ($image, true, 256);
    }
    return $image;
  }

  // return ImageGdUtility object
  private function _copyReSampled ($newImage, $oldImage, $newX, $newY, $oldX, $oldY, $newWidth, $newHeight, $oldWidth, $oldHeight) {
    imagecopyresampled ($newImage, $oldImage, $newX, $newY, $oldX, $oldY, $newWidth, $newHeight, $oldWidth, $oldHeight);
    return $this->_updateImage ($newImage);
  }

  // return ImageGdUtility object
  private function _updateImage ($image) {
    $this->image = $image;
    $this->dimension = $this->getDimension ($this->image);
    return $this;
  }

  // return boolean
  public function save ($save) {
    if (!$save)
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '錯誤的儲存路徑，save' . $save, '請再次確認儲存路徑！');

    if ($this->options['interlace'] === true)
      imageinterlace ($this->image, 1);
    else if ($this->options['interlace'] === false)
      imageinterlace ($this->image, 0);

    switch ($this->format) {
      case 'jpg': return @imagejpeg ($this->image, $save, $this->options['jpegQuality']);
      case 'gif': return @imagegif ($this->image, $save);
      case 'png': return @imagepng ($this->image, $save);
      default: return false;
    }
  }

  // return ImageGdUtility object
  public function pad ($width, $height, $color = array (255, 255, 255)) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    if (!ImageUtility::verifyColor ($color))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '色碼錯誤！', '請確認色碼格式，目前只支援 字串HEX、陣列RGB 格式！');

    if (($width < $this->dimension['width']) || ($height < $this->dimension['height']))
      return $this->resize ($width, $height);

    $newImage = function_exists ('imagecreatetruecolor') ? imagecreatetruecolor ($width, $height) : imagecreate ($width, $height);
    imagefill ($newImage, 0, 0, imagecolorallocate ($newImage, $color[0], $color[1], $color[2]));

    return $this->_copyReSampled ($newImage, $this->image, intval (($width - $this->dimension['width']) / 2), intval (($height - $this->dimension['height']) / 2), 0, 0, $this->dimension['width'], $this->dimension['height'], $this->dimension['width'], $this->dimension['height']);
  }

  // return ImageGdUtility object
  public function resize ($width, $height, $method = 'both') {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    switch ($method) {
      case 'b': case 'both': default: $newDimension = $this->calcImageSize ($this->dimension, $newDimension); break;
      case 'w': case 'width': $newDimension = $this->calcWidth ($this->dimension, $newDimension); break;
      case 'h': case 'height': $newDimension = $this->calcHeight ($this->dimension, $newDimension); break;
    }

    if (!ImageUtility::verifyDimension ($newDimension))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $newImage = function_exists ('imagecreatetruecolor') ? imagecreatetruecolor ($newDimension['width'], $newDimension['height']) : imagecreate ($newDimension['width'], $newDimension['height']);
    $newImage = $this->_preserveAlpha ($newImage);

    return $this->_copyReSampled ($newImage, $this->image, 0, 0, 0, 0, $newDimension['width'], $newDimension['height'], $this->dimension['width'], $this->dimension['height']);
  }

  // return ImageGdUtility object
  public function adaptiveResizePercent ($width, $height, $percent) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (!(($percent > -1) && ($percent < 101)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '比例錯誤，percent：' . $percent, '百分比要在 0 ~ 100 之間！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension = $this->calcImageSizeStrict ($this->dimension, $newDimension)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $this->resize ($newDimension['width'], $newDimension['height']);

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $newImage = function_exists ('imagecreatetruecolor') ? imagecreatetruecolor ($newDimension['width'], $newDimension['height']) : imagecreate ($newDimension['width'], $newDimension['height']);
    $newImage = $this->_preserveAlpha ($newImage);

    $cropX = $cropY = 0;

    if ($this->dimension['width'] > $newDimension['width'])
      $cropX = intval (($percent / 100) * ($this->dimension['width'] - $newDimension['width']));
    else if ($this->dimension['height'] > $newDimension['height'])
      $cropY = intval (($percent / 100) * ($this->dimension['height'] - $newDimension['height']));

    return $this->_copyReSampled ($newImage, $this->image, 0, 0, $cropX, $cropY, $newDimension['width'], $newDimension['height'], $newDimension['width'], $newDimension['height']);
  }

  // return ImageGdUtility object
  public function adaptiveResize ($width, $height) {
    return $this->adaptiveResizePercent ($width, $height, 50);
  }

  // return ImageGdUtility object
  public function resizePercent ($percent = 0) {
    if ($percent < 1)
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '比例錯誤，percent：' . $percent, '百分比要大於 1！');

    if ($percent == 100)
      return $this;

    if (!ImageUtility::verifyDimension ($newDimension = $this->calcImageSizePercent ($percent, $this->dimension)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    return $this->resize ($newDimension['width'], $newDimension['height']);
  }

  // return ImageGdUtility object
  public function crop ($startX, $startY, $width, $height) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (!(($startX >= 0) && ($startY >= 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '起始點錯誤，startX：' . $startX . '，startY：' . $startY, '水平、垂直的起始點一定要大於 0！');

    if (($startX == 0) && ($startY == 0) && ($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $width  = $this->dimension['width'] < $width ? $this->dimension['width'] : $width;
    $height = $this->dimension['height'] < $height ? $this->dimension['height'] : $height;

    $startX = ($startX + $width) > $this->dimension['width'] ? $this->dimension['width'] - $width : $startX;
    $startY = ($startY + $height) > $this->dimension['height'] ? $this->dimension['height'] - $height : $startY;

    $newImage = function_exists ('imagecreatetruecolor') ? imagecreatetruecolor ($width, $height) : imagecreate ($width, $height);
    $newImage = $this->_preserveAlpha ($newImage);

    return $this->_copyReSampled ($newImage, $this->image, 0, 0, $startX, $startY, $width, $height, $width, $height);
  }

  // return ImageGdUtility object
  public function cropFromCenter ($width, $height) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    if (($width > $this->dimension['width']) && ($height > $this->dimension['height']))
      return $this->pad ($width, $height);

    $startX = intval (($this->dimension['width'] - $width) / 2);
    $startY = intval (($this->dimension['height'] - $height) / 2);
    $width  = $this->dimension['width'] < $width ? $this->dimension['width'] : $width;
    $height = $this->dimension['height'] < $height ? $this->dimension['height'] : $height;

    return $this->crop ($startX, $startY, $width, $height);
  }

  // return ImageGdUtility object
  public function rotate ($degree, $color = array (255, 255, 255)) {
    if (!function_exists ('imagerotate'))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '尚未找尋到 imagerotate 函式！', '請確認 GD 函式庫中是否有支援 imagerotate 函式！');

    if (!is_numeric ($degree))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '角度一定要是數字，degree：' . $degree, '請確認 GD 函式庫中是否有支援 imagerotate 函式！');

    if (!ImageUtility::verifyColor ($color))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '色碼錯誤！', '請確認色碼格式，目前只支援 字串HEX、陣列RGB 格式！');

    if (!($degree % 360))
      return $this;

    $temp = function_exists ('imagecreatetruecolor') ? imagecreatetruecolor (1, 1) : imagecreate (1, 1);
    $newImage = imagerotate ($this->image, 0 - $degree, imagecolorallocate ($temp, $color[0], $color[1], $color[2]));

    return $this->_updateImage ($newImage);
  }

  // return ImageGdUtility object
  public function adaptiveResizeQuadrant ($width, $height, $item = 'c') {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;


    if (!ImageUtility::verifyDimension ($newDimension = $this->calcImageSizeStrict ($this->dimension, $newDimension)))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $this->resize ($newDimension['width'], $newDimension['height']);

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $newImage = function_exists ('imagecreatetruecolor') ? imagecreatetruecolor ($newDimension['width'], $newDimension['height']) : imagecreate ($newDimension['width'], $newDimension['height']);
    $newImage = $this->_preserveAlpha ($newImage);

    $cropX = $cropY = 0;

    if ($this->dimension['width'] > $newDimension['width']) {
      switch ($item) {
        case 'l': case 'L': $cropX = 0; break;
        case 'r': case 'R': $cropX = intval ($this->dimension['width'] - $newDimension['width']); break;
        case 'c': case 'C': default: $cropX = intval (($this->dimension['width'] - $newDimension['width']) / 2); break;
      }
    } else if ($this->dimension['height'] > $newDimension['height']) {
      switch ($item) {
        case 't': case 'T': $cropY = 0; break;
        case 'b': case 'B': $cropY = intval ($this->dimension['height'] - $newDimension['height']); break;
        case 'c': case 'C': default: $cropY = intval(($this->dimension['height'] - $newDimension['height']) / 2); break;
      }
    }

    return $this->_copyReSampled ($newImage, $this->image, 0, 0, $cropX, $cropY, $newDimension['width'], $newDimension['height'], $newDimension['width'], $newDimension['height']);
  }

  // return boolean
  public static function make_block9 ($files, $save, $interlace = null, $jpegQuality = 100) {
    if (!(count ($files) >= 9))
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '參數錯誤，files count：' . count ($files), '參數 files 數量一定要大於 9！');

    if (!$save)
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '錯誤的儲存路徑，save' . $save, '請再次確認儲存路徑！');

    if (!class_exists ('ImageUtility'))
      include_once 'ImageUtility.php';

    $positions = array (
      array ('left' =>   2, 'top' =>   2, 'width' => 130, 'height' => 130),
      array ('left' => 134, 'top' =>   2, 'width' =>  64, 'height' =>  64),
      array ('left' => 200, 'top' =>   2, 'width' =>  64, 'height' =>  64),
      array ('left' => 134, 'top' =>  68, 'width' =>  64, 'height' =>  64),
      array ('left' => 200, 'top' =>  68, 'width' =>  64, 'height' =>  64),
      array ('left' =>   2, 'top' => 134, 'width' =>  64, 'height' =>  64),
      array ('left' =>  68, 'top' => 134, 'width' =>  64, 'height' =>  64),
      array ('left' => 134, 'top' => 134, 'width' =>  64, 'height' =>  64),
      array ('left' => 200, 'top' => 134, 'width' =>  64, 'height' =>  64),
    );

    $image = imagecreatetruecolor (266, 200);
    imagefill ($image, 0, 0, imagecolorallocate ($image, 255, 255, 255));
    for ($i = 0; $i < 9; $i++)
      imagecopymerge ($image,
                      ImageUtility::create ($files[$i])->getImage (),
                      $positions[$i]['left'],
                      $positions[$i]['top'],
                      0,
                      0,
                      $positions[$i]['width'],
                      $positions[$i]['height'],
                      100);

    if ($interlace === true)
      imageinterlace ($image, 1);
    else if ($interlace === false)
      imageinterlace ($image, 0);

    switch (pathinfo ($save, PATHINFO_EXTENSION)) {
      case 'jpg': return @imagejpeg ($image, $save, $jpegQuality);
      case 'gif': return @imagegif ($image, $save);
      default: case 'png': return @imagepng ($image, $save);
    }
  }
}