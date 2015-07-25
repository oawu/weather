<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Crontab_logs extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    if (!identity ()->get_session ('is_login'))
      return redirect (array ('admin', 'main', 'login'));
  }

  public function destroy ($id = 0) {
    if (!($crontab_log = CrontabLog::find_by_id ($id)))
      return redirect (array ('admin', 'crontab_logs'));

    $message = $crontab_log->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'crontab_logs'), 'refresh');
  }

  public function index ($offset = 0) {

    $columns = array ('id' => 'int', 'type' => 'string', 'message' => 'string', 'run_time' => 'int');
    $configs = array ('admin', 'crontab_logs', '%s');

    $conditions = conditions (
                    $columns,
                    $configs,
                    'CrontabLog',
                    $this->input_gets ()
                  );

    $has_search = $conditions ? true : false;
    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = CrontabLog::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();

    $crontab_logs = CrontabLog::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array (
        'message' => $message,
        'pagination' => $pagination,
        'crontab_logs' => $crontab_logs,
        'has_search' => $has_search,
        'columns' => $columns
      ));
  }
}
