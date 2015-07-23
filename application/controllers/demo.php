<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Demo extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function weather_all () {
    Town::update_weather_all();
  }
  public function weather () {
    $towns = Town::find ('all', array ('conditions' => array ('id IN (50, 51)')));
    foreach ($towns as $town) {
      var_dump ($town->weather->id);
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
