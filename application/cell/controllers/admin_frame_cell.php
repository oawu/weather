<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Admin_frame_cell extends Cell_Controller {

  /* render_cell ('admin_frame_cell', 'header', array ()); */
  // public function _cache_header () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function header () {
    $links = array (
        array ('name' => '天氣管理', 'href' => base_url ('admin', 'weathers')),
        array ('name' => '推薦管理', 'href' => base_url ('admin', 'proposes')),
        array ('name' => '紀錄管理', 'href' => base_url ('admin', 'call_logs')),
      );
    return $this->setUseCssList (true)
                ->load_view (array (
                    'links' => $links
                  ));
  }

  /* render_cell ('admin_frame_cell', 'footer', array ()); */
  // public function _cache_footer () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function footer () {
    return $this->setUseCssList (true)
                ->load_view ();
  }
  
  /* render_cell ('admin_frame_cell', 'pagination', $pagination); */
  // public function _cache_pagination () {
  //   return array ('time' => 60 * 60, 'key' => null);
  // }
  public function pagination ($pagination) {
    return $this->setUseCssList (true)
                ->load_view (array ('pagination' => $pagination));
  }
}