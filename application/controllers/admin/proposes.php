<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Proposes extends Admin_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function map ($id = 0) {
    if (!($propose = Propose::find ('one', array ('conditions' => array ('id_enabled = ? AND id = ?', 1, $id)))))
      return redirect (array ('admin', 'proposes'));

    $this->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->load_view (array (
            'propose' => $propose
          ));
  }
  public function destroy ($id = 0) {
    if (!($propose = Propose::find ('one', array ('conditions' => array ('id_enabled = ? AND id = ?', 1, $id)))))
      return redirect (array ('admin', 'proposes'));

    $message = $propose->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'proposes'), 'refresh');
  }

  public function check ($id = 0) {
    if (!($propose = Propose::find ('one', array ('conditions' => array ('id_enabled = ? AND id = ?', 1, $id)))))
      return redirect (array ('admin', 'proposes'));

    $message = $propose->copy () ? '確認成功！' : '確認失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'proposes'), 'refresh');
  }
  public function index ($offset = 0) {
    $columns = array ('latitude' => 'string', 'longitude' => 'string');
    $configs = array ('admin', 'proposes', '%s');

    $conditions = conditions ($columns,
                              $configs,
                              'Propose',
                              $this->input_gets ()
                              );

    $conditions = array (implode (' AND ', array_merge ($conditions, array ('id_enabled = 1'))));

    $limit = 25;
    $total = Propose::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array (
          'total_rows' => $total,
          'num_links' => 5,
          'per_page' => $limit,
          'uri_segment' => 0,
          'base_url' => '',
          'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>',
        ), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();

    $proposes = Propose::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox.css'))
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.css'))
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.css'))
         ->add_css (base_url ('resource', 'css', 'fancyBox_v2.1.5', 'my.css'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox.js'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-buttons.js'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-thumbs.js'))
         ->add_js (base_url ('resource', 'javascript', 'fancyBox_v2.1.5', 'jquery.fancybox-media.js'))
         ->load_view (array (
        'message' => $message,
        'pagination' => $pagination,
        'proposes' => $proposes,
        'columns' => $columns
      ));
  }
}
