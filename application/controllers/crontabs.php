<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Crontabs extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function clean_all_cell () {
    clean_cell ('*');
  }
  public function clean_all_github_cell () {
    $log = CrontabLog::start ('清除 update_weather 完成後');
    clean_cell ('github_cell', '*');
    $log->finish ();
  }
  public function clean_query () {
    $log = CrontabLog::start ('每 30 分鐘，清除 query logs');
    
    $this->load->helper ('file');
    write_file (FCPATH . 'application/logs/query.log', '', FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);

    $log->finish ();
  }
  public function weather_all () {
    $log = CrontabLog::start ('每 60 分鐘，清除 update_weather');

    foreach (Town::all () as $town) {
      clean_cell ('town_cell', 'update_weather', $town->id);
      $town->update_weather ();
    }

    $this->clean_all_github_cell ();

    $log->finish ();
  }
}
