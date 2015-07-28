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
    foreach (Town::all () as $town) {
      $url = "http://maps.google.com/cbk?output=json&ll=" . $town->latitude . "," . $town->longitude . "&radius=100&hl=zh-TW";
      $resp_json = file_get_contents ($url);
      $result = json_decode ($resp_json, true);

      if (!($result && isset ($result['Location']['lat']) && isset ($result['Location']['lng']) && isset ($result['Location']['original_lat']) && isset ($result['Location']['original_lng'])))
        continue;

      if (isset ($result['Location']['lat']) && isset ($result['Location']['lng'])) {
        $lat = $result['Location']['lat'];
        $lng = $result['Location']['lng'];
      } else {
        $lat = $result['Location']['original_lat'];
        $lng = $result['Location']['original_lng'];
      }

      if (!$town->view) {
        if (verifyCreateOrm ($view =TownView::create (array (
                            'town_id' => $town->id,
                            'latitude' => $lat,
                            'longitude' => $lng,
                            'heading' => 0,
                            'pitch' => 0,
                            'zoom' => 1,
                          ))))
          $view->put_pic ();
      } else {
        $town->view->latitude = $lat;
        $town->view->longitude = $lng;
        $town->view->save ();
        $town->view->put_pic ();
      }

      echo "ID：" . $town->id . "\n";
    }
  }

  public function named () {
    // $name = '/m/symbol/weather/day/02@2x';
    // $img_name = str_replace ('/', '_', $name);
    // var_dump (md5 ($img_name));
    // exit ();
    // teresa

    // $t = Town::first ();
    // var_dump ($t->update_weather ());
    // exit ();;

    $this->load->helper ('directory_helper');
    
    foreach (directory_map (FCPATH . 'resource/image/weather/teresa2/') as $name) {
      rename (FCPATH . 'resource/image/weather/teresa2/' . $name, FCPATH . 'resource/image/weather/teresa2/' . pathinfo ($name, PATHINFO_FILENAME) . '@2x.' . pathinfo ($name, PATHINFO_EXTENSION));
    }

  }
  public function index () {
    if (ENVIRONMENT == 'production')
      return redirect ('http://comdan66.github.io/weather/index.html');
    else
      return redirect ('http://dev.comdan66.github.io/weather/index.html');
  }
}
