{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class <?php echo ucfirst ($file_name);?> extends Cell_Controller {

  /* render_cell ('demo_cell', 'main_menu', array ()); */
  // public function _cache_main_menu () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function main_menu () {
    return $this->setUseJsList (true)
                ->setUseCssList (true)
                ->load_view ();
  }
}