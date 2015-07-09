<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Site_frame_cell extends Cell_Controller {

  /* render_cell ('site_frame_cell', 'header', array ()); */
  // public function _cache_header () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function header () {
    $left_links = array (
        array ('name' => '首頁', 'href' => base_url (), 'is_login' => false),
      );
    $right_links = array (
        array ('name' => '更多', 'id' => 'more', 'href' => '#', 'is_login' => false),
        array ('name' => '關於', 'id' => 'about', 'href' => '#', 'is_login' => false),
      );

    return $this->setUseJsList (true)
                ->setUseCssList (true)
                ->load_view (array (
                    'left_links' => $left_links,
                    'right_links' => $right_links
                  ));
  }

  /* render_cell ('site_frame_cell', 'footer', array ()); */
  // public function _cache_footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->setUseCssList (true)
                ->load_view ();
  }
}