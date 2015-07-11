<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Call_logs extends Admin_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!identity ()->get_session ('is_login'))
      return redirect (array ('admin'));
  }

  public function index ($offset = 0) {
    $columns = array ('weather_id' => 'int');
    $configs = array ('admin', 'call_logs', '%s');

    $conditions = conditions ($columns,
                              $configs,
                              'CallLog',
                              $this->input_gets ()
                              );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = CallLog::count (array ('conditions' => $conditions));
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

    $call_logs = CallLog::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'include' => array ('weather'), 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
         ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
         ->load_view (array (
            'message' => $message,
            'pagination' => $pagination,
            'call_logs' => $call_logs,
            'columns' => $columns
          ));
  }
}
