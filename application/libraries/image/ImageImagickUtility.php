<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

include_once 'ImageBaseUtility.php';

class ImageImagickUtility extends ImageBaseUtility {
  private $options = null;
  private $configs = array ();

  public function __construct ($fileName, $options = array ()) {
    parent::__construct ($fileName);

    $this->configs = Cfg::system ('image_gd_utility');

    $this->_init ()
         ->_setOptions ($options);
  }

  // return ImageImagickUtility object
  private function _init () {
    if (!function_exists ('mime_content_type'))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '尚未找尋到 mime_content_type 函式！', '請確認 php.ini 中的 fileinfo 有開啟！');

    if (!$this->mime = mime_content_type ($this->getFileName ()))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '取不到檔案的 mime！', '請確認你的檔案格式正確性！');

    if (!(isset ($this->configs['mime_formats'][$this->mime]) && ($this->format = $this->configs['mime_formats'][$this->mime]) && in_array ($this->format, $this->configs['allow_formats'])))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '找尋不到符合的 mime，或者不支援處理的檔案格式！mime：' . $mime, '請檢查 config/system/image_imgk_utility.php 設定檔！');

    if (!$this->image = new Imagick ($this->getFileName()))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', 'Create image 失敗！', '請程式設計者確認狀況！');

    if (!ImageUtility::verifyDimension ($this->dimension = $this->getDimension ($this->image)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '取得尺寸失敗！', '請程式設計者確認狀況！');

    return $this;
  }

  // return dimension format array
  public function getDimension ($image = null) {
    $image = $image ? $image : $this->image->clone ();

    if (!(($imagePage = $image->getImagePage ()) && isset ($imagePage['width']) && isset ($imagePage['height']) && (intval ($imagePage['width']) > 0) && (intval ($imagePage['height']) > 0)))
      $imagePage = $image->getImageGeometry ();

    if (isset ($imagePage['width']) && isset ($imagePage['height']) && (intval ($imagePage['width']) > 0) && (intval ($imagePage['height']) > 0))
      return ImageUtility::createDimension ($imagePage['width'], $imagePage['height']);
    else
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '取得尺寸失敗！', '請程式設計者確認狀況！');
  }

  // return ImageImagickUtility object
  private function _setOptions ($options) {
    $this->options = array_merge ($this->configs['d4_options'], $options);
    return $this;
  }

  // return image object
  private function _machiningImageResize ($newDimension) {
    $newImage = $this->image->clone ()->coalesceImages ();

    if ($this->format == 'gif')
      do {
        $newImage->thumbnailImage ($newDimension['width'], $newDimension['height'], false);
      } while ($newImage->nextImage () || !$newImage = $newImage->deconstructImages ());
    else
      $newImage->thumbnailImage ($newDimension['width'], $newDimension['height'], false);

    return $newImage;
  }

  // return image object
  private function _machiningImageCrop ($cropX, $cropY, $width, $height, $color = 'transparent') {
    $newImage = new Imagick ();
    $newImage->setFormat ($this->format);

    if ($this->format == 'gif') {
      $imagick = $this->image->clone ()->coalesceImages ();
      do {
        $temp = new Imagick ();
        $temp->newImage ($width, $height, new ImagickPixel ($color));
        $imagick->chopImage ($cropX, $cropY, 0, 0);
        $temp->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, 0, 0);

        $newImage->addImage ($temp);
        $newImage->setImageDelay ($imagick->getImageDelay ());
      } while ($imagick->nextImage ());
    } else {
      $imagick = $this->image->clone ();
      $imagick->chopImage ($cropX, $cropY, 0, 0);
      $newImage->newImage ($width, $height, new ImagickPixel ($color));
      $newImage->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, 0, 0 );
    }
    return $newImage;
  }

  // return image object
  private function _machiningImageRotate ($degree, $color = 'transparent') {
    $newImage = new Imagick ();
    $newImage->setFormat ($this->format);
    $imagick = $this->image->clone ();

    if ($this->format == 'gif') {
      $imagick->coalesceImages();
      do {
        $temp = new Imagick ();

        $imagick->rotateImage (new ImagickPixel ($color), $degree);
        $newDimension = $this->getDimension ($imagick);
        $temp->newImage ($newDimension['width'], $newDimension['height'], new ImagickPixel ($color));
        $temp->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, 0, 0);

        $newImage->addImage ($temp);
        $newImage->setImageDelay ($imagick->getImageDelay ());
      } while ($imagick->nextImage ());
    } else {
      $imagick->rotateImage (new ImagickPixel ($color), $degree);
      $newDimension = $this->getDimension ($imagick);
      $newImage->newImage ($newDimension['width'], $newDimension['height'], new ImagickPixel ($color));
      $newImage->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, 0, 0);
    }
    return $newImage;
  }

  // return ImageImagickUtility object
  private function _updateImage ($image) {
    $this->image = $image;
    $this->dimension = $this->getDimension ($image);
    return $this;
  }

  // return ImageImagickUtility object
  private function _machiningImageFilter ($radius, $sigma, $channel) {
    if ($this->format == 'gif') {
      $newImage = $this->image->clone ()->coalesceImages ();
      do {
        $newImage->adaptiveBlurImage ($radius, $sigma, $channel);
      } while ($newImage->nextImage () || !$newImage = $newImage->deconstructImages ());
    } else {
      $newImage = $this->image->clone ();
      $newImage->adaptiveBlurImage ($radius, $sigma, $channel);
    }
    return $newImage;
  }

  // return ImagickDraw object
  private function _createFont ($font, $fontSize, $color, $alpha) {
    $draw = new ImagickDraw ();
    $draw->setFont ($font);
    $draw->setFontSize ($fontSize);
    $draw->setFillColor ($color);
    $draw->setFillAlpha ($alpha);
    return $draw;
  }

  // return boolean
  public function save ($save, $rawData = true) {
    if (!$save)
      throw new ImageUtilityException ('ImageGdUtility 錯誤！', '錯誤的儲存路徑，save' . $save, '請再次確認儲存路徑！');

    return $this->image->writeImages ($save, $rawData);
  }

  // return ImageImagickUtility object
  public function pad ($width, $height, $color = 'transparent') {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    if (!is_string ($color))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '色碼錯誤！', '請確認色碼格式，目前只支援 字串HEX 格式！');

    if (($width < $this->dimension['width']) || ($height < $this->dimension['height']))
      return $this->resize ($width, $height);

    $newImage = new Imagick ();
    $newImage->setFormat ($this->format);

    if ($this->format == 'gif') {
      $imagick = $this->image->clone ()->coalesceImages ();
      do {
        $temp = new Imagick ();
        $temp->newImage ($width, $height, new ImagickPixel ($color));
        $temp->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, intval (($width - $this->dimension['width']) / 2), intval (($height - $this->dimension['height']) / 2) );

        $newImage->addImage ($temp);
        $newImage->setImageDelay ($imagick->getImageDelay ());
      } while ($imagick->nextImage ());
    } else {
      $newImage->newImage ($width, $height, new ImagickPixel ($color));
      $newImage->compositeImage ($this->image->clone (), imagick::COMPOSITE_DEFAULT, intval (($width - $this->dimension['width']) / 2), intval (($height - $this->dimension['height']) / 2));
    }

    return $this->_updateImage ($newImage);
  }

  // return ImageImagickUtility object
  public function resize ($width, $height, $method = 'b') {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

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
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $workingImage = $this->_machiningImageResize ($newDimension);

    return $this->_updateImage ($workingImage);
  }

  // return ImageImagickUtility object
  public function adaptiveResizePercent ($width, $height, $percent) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (!(($percent > -1) && ($percent < 101)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '比例錯誤，percent：' . $percent, '百分比要在 0 ~ 100 之間！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension = $this->calcImageSizeStrict ($this->dimension, $newDimension)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $this->resize ($newDimension['width'], $newDimension['height']);

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $cropX = $cropY = 0;

    if ($this->dimension['width'] > $newDimension['width'])
      $cropX = intval (($percent / 100) * ($this->dimension['width'] - $newDimension['width']));
    else if ($this->dimension['height'] > $newDimension['height'])
      $cropY = intval (($percent / 100) * ($this->dimension['height'] - $newDimension['height']));

    $workingImage = $this->_machiningImageCrop ($cropX, $cropY, $newDimension['width'], $newDimension['height']);
    return $this->_updateImage ($workingImage);
  }

  // return ImageImagickUtility object
  public function adaptiveResize ($width, $height) {
    return $this->adaptiveResizePercent ($width, $height, 50);
  }

  // return ImageImagickUtility object
  public function resizePercent ($percent = 0) {
    if ($percent < 1)
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '比例錯誤，percent：' . $percent, '百分比要大於 1！');

    if ($percent == 100)
      return $this;

    if (!ImageUtility::verifyDimension ($newDimension = $this->calcImageSizePercent ($percent, $this->dimension)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    return $this->resize ($newDimension['width'], $newDimension['height']);
  }

  // return ImageImagickUtility object
  public function crop ($startX, $startY, $width, $height) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (!(($startX >= 0) && ($startY >= 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '起始點錯誤，startX：' . $startX . '，startY：' . $startY, '水平、垂直的起始點一定要大於 0！');

    if (($startX == 0) && ($startY == 0) && ($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $width  = $this->dimension['width'] < $width ? $this->dimension['width'] : $width;
    $height = $this->dimension['height'] < $height ? $this->dimension['height'] : $height;

    if (($startX + $width) > $this->dimension['width']) $startX = $this->dimension['width'] - $width;
    if (($startY + $height) > $this->dimension['height']) $startY = $this->dimension['height'] - $height;

    $workingImage = $this->_machiningImageCrop ($startX, $startY, $width, $height);

    return $this->_updateImage ($workingImage);
  }

  // return ImageImagickUtility object
  public function cropFromCenter ($width, $height) {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

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

  // return ImageImagickUtility object
  public function rotate ($degree, $color = 'transparent') {
    if (!is_numeric ($degree))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '角度一定要是數字，degree：' . $degree, '請確認 GD 函式庫中是否有支援 imagerotate 函式！');

    if (!is_string ($color))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '色碼錯誤！', '請確認色碼格式，目前只支援 字串HEX 格式！');

    if (!($degree % 360))
      return $this;

    $workingImage = $this->_machiningImageRotate ($degree, $color);

    return $this->_updateImage ($workingImage);
  }

  // return ImageImagickUtility object
  public function adaptiveResizeQuadrant ($width, $height, $item = 'c') {
    if (!((($width = intval ($width)) > 0) && (($height = intval ($height)) > 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤，width：' . $width . '，height：' . $height, '尺寸寬高一定要大於 0！');

    if (($width == $this->dimension['width']) && ($height == $this->dimension['height']))
      return $this;

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension = $this->calcImageSizeStrict ($this->dimension, $newDimension)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

    $this->resize ($newDimension['width'], $newDimension['height']);

    $newDimension['width']  = ($this->options['resizeUp'] === false) && ($width > $this->dimension['width']) ? $this->dimension['width'] : $width;
    $newDimension['height'] = ($this->options['resizeUp'] === false) && ($height > $this->dimension['height']) ? $this->dimension['height'] : $height;

    if (!ImageUtility::verifyDimension ($newDimension))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '新尺寸錯誤！', '尺寸寬高一定要大於 0！');

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

    $workingImage = $this->_machiningImageCrop ($cropX, $cropY, $newDimension['width'], $newDimension['height']);

    return $this->_updateImage ($workingImage);
  }

  // return ImageImagickUtility object
  public function filter ($radius, $sigma, $channel = Imagick::CHANNEL_DEFAULT) {
    $items = array (imagick::CHANNEL_UNDEFINED, imagick::CHANNEL_RED,     imagick::CHANNEL_GRAY,  imagick::CHANNEL_CYAN,
                    imagick::CHANNEL_GREEN,     imagick::CHANNEL_MAGENTA, imagick::CHANNEL_BLUE,  imagick::CHANNEL_YELLOW,
                    imagick::CHANNEL_ALPHA,     imagick::CHANNEL_OPACITY, imagick::CHANNEL_MATTE, imagick::CHANNEL_BLACK,
                    imagick::CHANNEL_INDEX,     imagick::CHANNEL_ALL,     imagick::CHANNEL_DEFAULT);

    if (!is_numeric ($radius))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，radius：' . $radius, '參數 radius 要為數字！');

    if (!is_numeric ($sigma))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，sigma：' . $sigma, '參數 sigma 要為數字！');

    if (!in_array ($channel, $items))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，channel：' . $channel, '參數 channel 格式不正確！');

    $workingImage = $this->_machiningImageFilter ($radius, $sigma, $channel);

    return $this->_updateImage ($workingImage);
  }

  // return ImageImagickUtility object
  public function lomography () {
    $newImage = new Imagick ();
    $newImage->setFormat ($this->format);

    if ($this->format == 'gif') {
      $imagick = $this->image->clone ()->coalesceImages ();
      do {
        $temp = new Imagick ();

        $imagick->setimagebackgroundcolor ("black");
        $imagick->gammaImage (0.75);
        $imagick->vignetteImage (0, max ($this->dimension['width'], $this->dimension['height']) * 0.2, 0 - ($this->dimension['width'] * 0.05), 0 - ($this->dimension['height'] * 0.05));

        $temp->newImage ($this->dimension['width'], $this->dimension['height'], new ImagickPixel ('transparent'));
        $temp->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, 0, 0);

        $newImage->addImage ($temp);
        $newImage->setImageDelay ($imagick->getImageDelay ());
      } while ($imagick->nextImage ());
    } else {
      $newImage = $this->image->clone ();
      $newImage->setimagebackgroundcolor("black");
      $newImage->gammaImage (0.75);
      $newImage->vignetteImage (0, max ($this->dimension['width'], $this->dimension['height']) * 0.2, 0 - ($this->dimension['width'] * 0.05), 0 - ($this->dimension['height'] * 0.05));
    }
    return $this->_updateImage ($newImage);
  }

  // return array
  public function getAnalysisDatas ($maxCount = 10) {
    if (!($maxCount > 0))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，maxCount：' . $maxCount, '參數 radius 一定要大於 0！');

    $temp = $this->image->clone ();

    $temp->quantizeImage ($maxCount, Imagick::COLORSPACE_RGB, 0, false, false );
    $pixels = $temp->getImageHistogram ();

    $datas = array ();
    $index = 0;
    $pixelCount = $this->dimension['width'] * $this->dimension['height'];

    if ($pixels && $maxCount)
      foreach ($pixels as $pixel)
        if ($index++ < $maxCount)
          array_push ($datas, array ('color' => $pixel->getColor (), 'count' => $pixel->getColorCount (), 'percent' => round ($pixel->getColorCount () / $pixelCount * 100)));
        else
          break;

    return sort2dArray ('count', $datas);
  }

  // return ImageImagickUtility object
  public function saveAnalysisChart ($fileName, $font, $maxCount = 10, $fontSize = 14, $rawData = true) {
    if (!is_readable ($font))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，font：' . $font, '字型檔案不存在！');

    if (!($maxCount > 0))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，maxCount：' . $maxCount, '參數 radius 一定要大於 0！');

    if (!($fontSize > 0))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，fontSize：' . $fontSize, '文字大小一定要大於 0！');

    $format = pathinfo ($fileName, PATHINFO_EXTENSION);
    if (!($format && in_array ($format, $this->configs['allow_formats'])))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '不支援處理的檔案格式！format：' . $format, '請檢查 config/system/image_imgk_utility.php 設定檔！');

    if (!$datas = $this->getAnalysisDatas ($maxCount))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '圖像分析錯誤！', '請程式設計者確認狀況！');

    $newImage = new Imagick ();

    foreach ($datas as $data) {
      $newImage->newImage (400, 20, new ImagickPixel ('white'));

      $draw = new ImagickDraw ();
      $draw->setFont ($font);
      $draw->setFontSize ($fontSize);
      $newImage->annotateImage ($draw, 25, 14, 0, 'Percentage of total pixels : ' . (strlen ($data['percent'])<2?' ':'') . $data['percent'] . '% (' . $data['count'] . ')');

      $tile = new Imagick ();
      $tile->newImage (20, 20, new ImagickPixel ('rgb(' . $data['color']['r'] . ',' . $data['color']['g'] . ',' . $data['color']['b'] . ')'));

      $newImage->compositeImage ($tile, Imagick::COMPOSITE_OVER, 0, 0);
    }

    $newImage = $newImage->montageImage (new imagickdraw (), '1x' . count ($datas) . '+0+0', '400x20+4+2>', imagick::MONTAGEMODE_UNFRAME, '0x0+3+3');
    $newImage->setImageFormat ($format);
    $newImage->setFormat ($format);
    $newImage->writeImages ($fileName, $rawData);

    return $this;
  }

  // return ImageImagickUtility object
  public function addFont ($text, $font, $startX = 0, $startY = 12, $color = 'black', $fontSize = 12, $alpha = 1, $degree = 0) {
    if (!$text)
      return $this;

    if (!is_readable ($font))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，font：' . $font, '字型檔案不存在！');

    if (!(($startX >= 0) && ($startY >= 0)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '起始點錯誤，startX：' . $startX . '，startY：' . $startY, '水平、垂直的起始點一定要大於 0！');

    if (!is_string ($color))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '色碼錯誤！', '請確認色碼格式，目前只支援 字串HEX 格式！');

    if (!($fontSize > 0))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，fontSize：' . $fontSize, '文字大小一定要大於 0！');

    if (!($alpha && is_numeric ($alpha) && ($alpha >= 0) && ($alpha <= 1)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，alpha：' . $alpha, '參數 alpha 一定要是 0 或 1！');

    if (!is_numeric ($degree %= 360))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '角度一定要是數字，degree：' . $degree, '請確認 GD 函式庫中是否有支援 imagerotate 函式！');

    if (!($draw = $this->_createFont ($font, $fontSize, $color, $alpha)))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', ' Create 文字物件失敗', '請程式設計者確認狀況！');

    if ($this->format == 'gif') {
      $newImage = new Imagick ();
      $newImage->setFormat ($this->format);
      $imagick = $this->image->clone ()->coalesceImages ();
      do {
        $temp = new Imagick ();
        $temp->newImage ($this->dimension['width'], $this->dimension['height'], new ImagickPixel ('transparent'));
        $temp->compositeImage ($imagick, imagick::COMPOSITE_DEFAULT, 0, 0);
        $temp->annotateImage ($draw, $startX, $startY, $degree, $text);
        $newImage->addImage ($temp);
        $newImage->setImageDelay ($imagick->getImageDelay ());
      } while ($imagick->nextImage ());
    } else {
      $newImage = $this->image->clone ();
      $newImage->annotateImage ($draw, $startX, $startY, $degree, $text);
    }

    return $this->_updateImage ($newImage);
  }

  // return boolean
  public function make_block9 ($files, $save = null, $rawData = true) {
    if (!(count ($files) >= 9))
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '參數錯誤，files count：' . count ($files), '參數 files 數量一定要大於 9！');

    if (!$save)
      throw new ImageUtilityException ('ImageImagickUtility 錯誤！', '錯誤的儲存路徑，save' . $save, '請再次確認儲存路徑！');

    $newImage = new Imagick ();
    $newImage->newImage (266, 200, new ImagickPixel ('white'));
    $newImage->setFormat (pathinfo ($save, PATHINFO_EXTENSION));

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

    for ($i = 0; $i < 9; $i++)
      $newImage->compositeImage (ImageUtility::create ($files[$i])->getImage (),
                                 imagick::COMPOSITE_DEFAULT,
                                 $positions[$i]['left'],
                                 $positions[$i]['top']);

    return $newImage->writeImages ($save, $rawData);
  }
}