{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class <?php echo $name;?> extends OrmImageUploader {

  public function getVersions () {
    return array (
        '' => array (),
        '100w' => array ('resize', 100, 100, 'width'),
        '120x80c' => array ('adaptiveResizeQuadrant', 120, 80, 'c')
      );
  }
}