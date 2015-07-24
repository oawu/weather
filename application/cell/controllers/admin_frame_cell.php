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

    $left_links = array (
        array ('name' => '首頁', 'href' => base_url (), 'show' => true),
        array ('name' => '縣市列表', 'href' => base_url ('admin', 'towns', 'cate_index'), 'show' => identity ()->get_session ('is_login') ? true : false),
        array ('name' => '鄉鎮列表', 'href' => base_url ('admin', 'towns'), 'show' => identity ()->get_session ('is_login') ? true : false),
        array ('name' => '排程列表', 'href' => base_url ('admin', 'crontab_logs'), 'show' => identity ()->get_session ('is_login') ? true : false),
      );
    $right_links = array (
        array ('name' => '登出', 'href' => base_url ('admin', 'main', 'logout'), 'show' => identity ()->get_session ('is_login') ? true : false),
        array ('name' => '登入', 'href' => base_url ('admin', 'main', 'login'), 'show' => identity ()->get_session ('is_login') ? false : true),
      );

    // $this->set_sides (array (
    //       '縣市' => array (
    //           array ('name' => '縣市列表', 'href' => base_url ('admin', 'towns', 'cate_index')),
    //           array ('name' => '新增縣市', 'href' => base_url ('admin', 'towns', 'cate_add')),
    //         ),
    //       '鄉鎮' => array (
    //           array ('name' => '鄉鎮列表', 'href' => base_url ('admin', 'towns', 'index')),
    //           array ('name' => '新增鄉鎮', 'href' => base_url ('admin', 'towns', 'add')),
    //         ))
    //       );
    return $this->setUseJsList (true)
                ->setUseCssList (true)
                ->load_view (array (
                    'left_links' => $left_links,
                    'right_links' => $right_links
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