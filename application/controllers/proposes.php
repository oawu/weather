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
  public function index () {
    $message  = identity ()->get_session ('_flash_message', true);
    
    $title = identity ()->get_session ('title', true);
    $latitude = identity ()->get_session ('latitude', true);
    $longitude = identity ()->get_session ('longitude', true);

    $this->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->load_view (array (
        'message' => $message,
        'title' => $title,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }
}
