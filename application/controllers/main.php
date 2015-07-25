<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function name () {
    // $name = '/m/symbol/weather/day/02@2x';
    // $img_name = str_replace ('/', '_', $name);
    // var_dump (md5 ($img_name));
    // exit ();
    // teresa

    // $t = Town::first ();
    // var_dump ($t->update_weather ());
    // exit ();;

    $this->load->helper ('directory_helper');
    foreach (directory_map (FCPATH . 'resource/image/weather/teresa_ori/') as $name) {
      rename (FCPATH . 'resource/image/weather/teresa_ori/' . $name, FCPATH . 'resource/image/weather/teresa_ori/' . pathinfo ($name, PATHINFO_FILENAME) . '@2x.' . pathinfo ($name, PATHINFO_EXTENSION));
    }

  }
  public function index () {
    if (ENVIRONMENT == 'production')
      return redirect ('http://comdan66.github.io/weather/index.html');
    else
      return redirect ('http://dev.comdan66.github.io/weather/index.html');
  }
}
