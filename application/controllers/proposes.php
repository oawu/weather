<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Proposes extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('proposes'));

    if ((time () - identity ()->get_session ('post_time')) < (60 * 1))
      return identity ()->set_session ('_flash_message', '別急！一分鐘後再推薦新地點吧！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('proposes'), 'refresh');

    $title = trim ($this->input_post ('title'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($title && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('proposes'), 'refresh');

    $params = array (
        'title' => $title,
        'ip' => $this->input->ip_address (),
        'latitude' => $latitude,
        'longitude' => $longitude,
        'id_enabled' => 1
      );

    if (!verifyCreateOrm ($propose = Propose::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('proposes'), 'refresh');

    identity ()->set_session ('post_time', time ());

    return identity ()->set_session ('_flash_message', '新增成功，等待管理員審核通過！', true)
                      && redirect (array ('proposes'), 'refresh');
  }
  public function get_weathers () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');

    if (!(isset ($north_east['latitude']) && isset ($south_west['latitude']) && isset ($north_east['longitude']) && isset ($south_west['longitude'])))
      return $this->output_json (array ('status' => true, 'weathers' => array ()));

    $weathers = array_map (function ($weather) {
      return array (
          'id' => $weather->id,
          'lat' => $weather->latitude,
          'lng' => $weather->longitude,
          'title' => $weather->title,
        );
    }, Weather::find ('all', array ('conditions' => array ('latitude < ? AND latitude > ? AND longitude < ? AND longitude > ?', $north_east['latitude'], $south_west['latitude'], $north_east['longitude'], $south_west['longitude']))));

    return $this->output_json (array ('status' => true, 'weathers' => $weathers));
  }
  public function index () {
    $message  = identity ()->get_session ('_flash_message', true);
    
    $title = identity ()->get_session ('title', true);
    $latitude = identity ()->get_session ('latitude', true);
    $longitude = identity ()->get_session ('longitude', true);

    $this->add_meta (array ('property' => 'og:url', 'content' => current_url ()))
         ->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'get_weathers_url', 'value' => base_url ($this->get_class (), 'get_weathers')))
         ->load_view (array (
          'message' => $message,
          'title' => $title,
          'latitude' => $latitude,
          'longitude' => $longitude
        ));
  }
}
