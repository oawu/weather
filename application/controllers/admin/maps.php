<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Maps extends Admin_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $this->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
         ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
         ->add_hidden (array ('id' => 'get_weathers_url', 'value' => base_url ('admin', $this->get_class (), 'get_weathers')))
         ->add_hidden (array ('id' => 'update_weather_url', 'value' => base_url ('admin', $this->get_class (), 'update_weather')))
         ->add_hidden (array ('id' => 'delete_weather_url', 'value' => base_url ('admin', $this->get_class (), 'delete_weather')))
         ->load_view (null);
  }
  public function delete_weather () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $id = $this->input_post ('id');

    if (!($id && ($weather = Weather::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    if (!$weather->destroy ())
      return $this->output_json (array ('status' => false));
      
    return $this->output_json (array ('status' => true));
  }
  public function update_weather () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $id = $this->input_post ('id');
    $lat = $this->input_post ('lat');
    $lng = $this->input_post ('lng');

    if (!($id && $lat && $lng && ($weather = Weather::find_by_id ($id))))
      return $this->output_json (array ('status' => false));

    $weather->latitude = $lat;
    $weather->longitude = $lng;

    if (!$weather->save ())
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true));
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
}
