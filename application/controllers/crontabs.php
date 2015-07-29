<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Crontabs extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function test ($psw) {
    if (md5 ($psw) != '6d499b8cebdc1464c46cc22a201036bd')
      return ;
    echo "Yes";
  }

  public function clean_all_github_cell ($psw) {
    $log = CrontabLog::start ('清除 update_weather 完成後');

    if (md5 ($psw) != '6d499b8cebdc1464c46cc22a201036bd')
      return ;

    clean_cell ('github_cell', '*');
    $log->finish ();
  }
  public function clean_query ($psw) {
    $log = CrontabLog::start ('每 30 分鐘，清除 query logs');

    if (md5 ($psw) != '6d499b8cebdc1464c46cc22a201036bd')
      return ;
    
    $this->load->helper ('file');
    write_file (FCPATH . 'application/logs/query.log', '', FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);

    $log->finish ();
  }
  public function weather_all ($psw) {
    $log = CrontabLog::start ('每 60 分鐘，清除 update_weather');

    if (md5 ($psw) != '6d499b8cebdc1464c46cc22a201036bd')
      return ;

    foreach (Town::all (array ('order' => 'RAND()')) as $town) {
      clean_cell ('town_cell', 'update_weather', $town->id);
      $town->update_weather ();
    }

    $this->clean_all_github_cell ($psw);

    $log->finish ();
  }
}
