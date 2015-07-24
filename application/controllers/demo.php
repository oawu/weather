<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Demo extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function d () {
    // 
    // download_web_file ();
    // echo sprintf('http://www.cwb.gov.tw/m/symbol/weather/day/%02d@2x.png', 1);
    // for ($i = 0; $i < 100; $i++) {
    //   $url = sprintf ('http://www.cwb.gov.tw/m/symbol/weather/day/%02d@2x.png', $i);
    //   download_web_file ($url, FCPATH . '/temp/' . sprintf('%02d@2x.png', $i));
    // }
    echo md5 (str_replace ('/', '_', '/m/symbol/weather/day/02@2x'));
    echo "\n";
  }
  public function weather_all () {
    foreach (Town::all () as $town) {
      clean_cell ('town_cell', 'update_weather', $town->id);
      $town->update_weather ();
    }
  }

  public function town () {
    foreach (Town::all (array ('conditions' => array ('id > ?', 0))) as $town)
      if (!$town->put_pic ())
        echo $town->id . " Error!\n";
      else
        echo $town->id . " OK!\n";
  }
}
