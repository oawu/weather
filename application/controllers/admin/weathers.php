<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Weathers extends Admin_controller {

  public function __construct () {
    parent::__construct ();
    
    if (!identity ()->get_session ('is_login'))
      return redirect (array ('admin'));
  }

  public function destroy ($id = 0) {
    if (!($weather = Weather::find_by_id ($id)))
      return redirect (array ('admin', 'weathers'));

    $message = $weather->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'weathers'), 'refresh');
  }

  public function edit ($id = 0) {
    if (!($weather = Weather::find_by_id ($id)))
      return redirect (array ('admin', 'weathers'));

    $message  = identity ()->get_session ('_flash_message', true);
    $title = identity ()->get_session ('title', true);
    $latitude = identity ()->get_session ('latitude', true);
    $longitude = identity ()->get_session ('longitude', true);

    $this->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'get_weathers_url', 'value' => base_url ('admin', $this->get_class (), 'get_weathers')))
         ->load_view (array (
        'weather' => $weather,
        'message' => $message,
        'title' => $title,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }

  public function update ($id = 0) {
    if (!($weather = Weather::find_by_id ($id)))
      return redirect (array ('admin', 'weathers'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'weathers', 'edit', $weather->id));

    $title = trim ($this->input_post ('title'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($title && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'weathers', 'edit', $weather->id), 'refresh');

    $weather->title = $title;
    $weather->latitude = $latitude;
    $weather->longitude = $longitude;

    if (!$weather->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'weathers', 'edit', $weather->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'weathers'), 'refresh');
  }

  public function add () {
    $message  = identity ()->get_session ('_flash_message', true);
    
    $title = identity ()->get_session ('title', true);
    $latitude = identity ()->get_session ('latitude', true);
    $longitude = identity ()->get_session ('longitude', true);

    $this->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'get_weathers_url', 'value' => base_url ('admin', $this->get_class (), 'get_weathers')))
         ->load_view (array (
        'message' => $message,
        'title' => $title,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('admin', 'weathers', 'add'));

    $title = trim ($this->input_post ('title'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($title && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'weathers', 'add'), 'refresh');

    $params = array (
        'title' => $title,
        'icon' => '',
        'temperature' => '',
        'latitude' => $latitude,
        'longitude' => $longitude,
        'propose_id' => 0
      );

    if (!verifyCreateOrm ($weather = Weather::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('title', $title, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'weathers', 'add'), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'weathers'), 'refresh');
  }

  public function get_weathers () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');
    $weather_id = ($weather_id = $this->input_post ('weather_id')) ? $weather_id : 0;

    if (!(isset ($north_east['latitude']) && isset ($south_west['latitude']) && isset ($north_east['longitude']) && isset ($south_west['longitude'])))
      return $this->output_json (array ('status' => true, 'weathers' => array ()));

    $weathers = array_map (function ($weather) {
      return array (
          'id' => $weather->id,
          'lat' => $weather->latitude,
          'lng' => $weather->longitude,
          'title' => $weather->title,
        );
    }, Weather::find ('all', array ('conditions' => array ('latitude < ? AND latitude > ? AND longitude < ? AND longitude > ? AND id != ?', $north_east['latitude'], $south_west['latitude'], $north_east['longitude'], $south_west['longitude'], $weather_id))));

    return $this->output_json (array ('status' => true, 'weathers' => $weathers));
  }
  public function index ($offset = 0) {
    $columns = array ('latitude' => 'string', 'longitude' => 'string', 'temperature' => 'string');
    $configs = array ('admin', 'weathers', '%s');

    $conditions = conditions ($columns,
                              $configs,
                              'Weather',
                              $this->input_gets ()
                              );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = Weather::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array (
          'total_rows' => $total,
          'num_links' => 5,
          'per_page' => $limit,
          'uri_segment' => 0,
          'base_url' => '',
          'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>',
        ), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();

    $weathers = Weather::find ('all', array ('offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'include' => array ('log'), 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'jquery.timeago.js'))
         ->add_js (base_url ('resource', 'javascript', 'jquery-timeago_v1.3.1', 'locales', 'jquery.timeago.zh-TW.js'))
         ->load_view (array (
        'message' => $message,
        'pagination' => $pagination,
        'weathers' => $weathers,
        'columns' => $columns
      ));
  }
}
