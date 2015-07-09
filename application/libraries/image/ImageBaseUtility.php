<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class ImageBaseUtility {
  private $fileName = null;
  protected $CI = null;
  protected $mime = null;
  protected $format = null;
  protected $image = null;
  protected $dimension = null;

  public function __construct ($fileName) {
    if (!is_readable ($fileName))
      throw new ImageUtilityException ('ImageBaseUtility 錯誤！', '檔案不可讀取或不存在，file：' . $fileName);

    $this->CI =& get_instance ();

    if (!class_exists ('ImageUtility'))
      include_once 'ImageUtility.php';

    $this->setFileName ($fileName);
  }
  // return ImageBaseUtility object
  protected function setFileName ($fileName) {
    $this->fileName = $fileName;
    return $this;
  }
  // return string
  public function getFileName () {
    return $this->fileName;
  }
  // return string
  public function getMime () {
    return $this->mime;
  }
  // return string
  public function getFormat () {
    return $this->format;
  }
  // return image object
  public function getImage () {
    return $this->image;
  }
  // return dimension format array
  protected function calcImageSizePercent ($percent, $dimension) {
    return ImageUtility::createDimension (
      ceil ($dimension['width'] * $percent / 100),
      ceil ($dimension['height'] * $percent / 100));
  }
  // return dimension format array
  protected function calcWidth ($oldDimension, $newDimension) {
    $newWidthPercentage = 100 * $newDimension['width'] / $oldDimension['width'];
    $height             = ceil ($oldDimension['height'] * $newWidthPercentage / 100);

    return ImageUtility::createDimension ($newDimension['width'], $height);
  }
  // return dimension format array
  protected function calcHeight ($oldDimension, $newDimension) {
    $newHeightPercentage  = 100 * $newDimension['height'] / $oldDimension['height'];
    $width                = ceil ($oldDimension['width'] * $newHeightPercentage / 100);
    return ImageUtility::createDimension ($width, $newDimension['height']);
  }
  // return dimension format array
  protected function calcImageSize ($oldDimension, $newDimension) {
    $newSize = ImageUtility::createDimension ($oldDimension['width'], $oldDimension['height']);

    if ($newDimension['width'] > 0) {
      $newSize = $this->calcWidth ($oldDimension, $newDimension);
      if (($newDimension['height'] > 0) && ($newSize['height'] > $newDimension['height']))
        $newSize = $this->calcHeight($oldDimension, $newDimension);
    }
    if ($newDimension['height'] > 0) {
      $newSize = $this->calcHeight ($oldDimension, $newDimension);
      if (($newDimension['width'] > 0) && ($newSize['width'] > $newDimension['width']))
        $newSize = $this->calcWidth ($oldDimension, $newDimension);
    }
    return $newSize;
  }
  // return dimension format array
  protected function calcImageSizeStrict ($oldDimension, $newDimension) {
    $newSize = ImageUtility::createDimension ($newDimension['width'], $newDimension['height']);

    if ($newDimension['width'] >= $newDimension['height']) {
      if ($oldDimension['width'] > $oldDimension['height'])  {
        $newSize = $this->calcHeight ($oldDimension, $newDimension);

        if ($newSize['width'] < $newDimension['width']) {
          $newSize = $this->calcWidth ($oldDimension, $newDimension);
        }
      } else if ($oldDimension['height'] >= $oldDimension['width']) {
        $newSize = $this->calcWidth ($oldDimension, $newDimension);

        if ($newSize['height'] < $newDimension['height']) {
          $newSize = $this->calcHeight ($oldDimension, $newDimension);
        }
      }
    } else if ($newDimension['height'] > $newDimension['width']) {
      if ($oldDimension['width'] >= $oldDimension['height']) {
        $newSize = $this->calcWidth ($oldDimension, $newDimension);

        if ($newSize['height'] < $newDimension['height']) {
          $newSize = $this->calcHeight ($oldDimension, $newDimension);
        }
      } else if ($oldDimension['height'] > $oldDimension['width']) {
        $newSize = $this->calcHeight ($oldDimension, $newDimension);

        if ($newSize['width'] < $newDimension['width']) {
          $newSize = $this->calcWidth ($oldDimension, $newDimension);
        }
      }
    }
    return $newSize;
  }
}