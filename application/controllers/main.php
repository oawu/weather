<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function query () {
    $this->load->helper ('file');
    write_file (FCPATH . 'application/logs/query.log', '', FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
  }
  public function weather_all () {
    foreach (Town::all () as $town) {
      clean_cell ('town_cell', 'update_weather', $town->id);
      $town->update_weather ();
    }
  }
  public function index () {
    if (ENVIRONMENT == 'production')
      return redirect ('http://comdan66.github.io/weather/index.html');
    else
      return redirect ('http://dev.comdan66.github.io/weather/index.html');
  }
}
