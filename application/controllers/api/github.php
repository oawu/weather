<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Github extends Api_controller {

  public function __construct () {
    parent::__construct ();

    header ('Content-type: text/html');
    header ('Access-Control-Allow-Origin: http://dev.comdan66.github.io');
  }

  private function _weather_format ($town) {
    return $town && ($weather = $town->update_weather ()) ? array (
      'id' => $town->id,
      'lat' => $town->latitude,
      'lng' => $town->longitude,
      'c' => $this->set_method ('weather')->load_content (array (
          'town' => $town,
          'weather' => $weather
        ), true)
    ) : array ();
  }

  public function get_weather_by_postal_code () {
    $postal_code = trim ($this->input_post ('postal_code'));

    if (!$postal_code)
      return $this->output_json (array ('status' => false));

    if (!($town = Town::find ('one', array ('conditions' => array ('postal_code = ?', $postal_code)))))
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true, 'weather' => $this->_weather_format ($town)));
  }

  public function get_weather_by_name () {
    $name = trim ($this->input_post ('name'));

    if (!$name)
      return $this->output_json (array ('status' => false));

    if (!($town = Town::find ('one', array ('conditions' => array ('name LIKE CONCAT("%", ? ,"%")', $name)))))
      return $this->output_json (array ('status' => false));

    return $this->output_json (array ('status' => true, 'weather' => $this->_weather_format ($town)));
  }

  public function get_towns () {
    $limit = 10;
    $towns = array_map (function ($t) {
      return array (
          'id' => $t->id,
          'name' => $t->name
        );
    }, Town::find ('all', array ('select' => 'id, name', 'limit' => $limit, 'order' => 'RAND()')));
    
    return $this->output_json (array ('status' => true, 'towns' => $towns));
  }
  public function get_weathers () {
    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');
    $townId = $this->input_post ('townId');
    $zoom = $this->input_post ('zoom');

    if (!($north_east && $south_west && isset ($north_east['latitude']) && isset ($south_west['latitude']) && isset ($north_east['longitude']) && isset ($south_west['longitude'])))
      return $this->output_json (array ('status' => true, 'weathers' => array ()));

    $that = $this;
    $weathers = array_filter (array_map (function ($town) use ($that) {
      return $that->_weather_format ($town);
    }, Town::find ('all', array ('conditions' => array ('id != ? AND zoom <= ? AND (latitude BETWEEN ? AND ?) AND (longitude BETWEEN ? AND ?)', $townId, $zoom, $south_west['latitude'], $north_east['latitude'], $south_west['longitude'], $north_east['longitude'])))));

    return $this->output_json (array ('status' => true, 'weathers' => $weathers));
  }
}
