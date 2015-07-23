<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Pub_method extends Admin_controller {

  public function __construct () {
    parent::__construct ();

    if (!identity ()->get_session ('is_login'))
      return redirect ('admin', 'main', 'login');
  }

  public function town ($id = 0) {
    if (!($town = Town::find ('one', array ('conditions' => array ('id = ?', $id)))))
      return show_404();

    if ($town->bound)
      $this->add_hidden (array ('id' => 'bound', 'data-northeast_latitude' => $town->bound->northeast_latitude, 'data-northeast_longitude' => $town->bound->northeast_longitude, 'data-southwest_latitude' => $town->bound->southwest_latitude, 'data-southwest_longitude' => $town->bound->southwest_longitude, 'value' => $town->bound->id));

    $this->add_hidden (array ('id' => 'marker', 'data-lat' => $town->latitude, 'data-lng' => $town->longitude, 'value' => $town->id))
         ->load_view (array (
            'town' => $town
          ));
  }
  public function get_towns () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");
    
    $is_use_bound = false;

    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');
    $town_id = ($town_id = $this->input_post ('town_id')) ? $town_id : 0;

    if (!(isset ($north_east['latitude']) && isset ($south_west['latitude']) && isset ($north_east['longitude']) && isset ($south_west['longitude'])))
      return $this->output_json (array ('status' => true, 'towns' => array ()));

    $towns = array_map (function ($town) use ($is_use_bound) {
      return array (
          'id' => $town->id,
          'lat' => $town->latitude,
          'lng' => $town->longitude,
          'name' => $town->name,
          'bound' => $is_use_bound && $town->bound ? array (
              'northeast' => array (
                'lat' => $town->bound->northeast_latitude,
                'lng' => $town->bound->northeast_longitude,
                ),
              'southwest' => array (
                'lat' => $town->bound->southwest_latitude,
                'lng' => $town->bound->southwest_longitude,
                )
            ) : null
        );
    }, Town::find ('all', array ('conditions' => array ('latitude < ? AND latitude > ? AND longitude < ? AND longitude > ? AND id != ?', $north_east['latitude'], $south_west['latitude'], $north_east['longitude'], $south_west['longitude'], $town_id))));

    return $this->output_json (array ('status' => true, 'towns' => $towns));
  }
  public function update_town_position ($id = 0) {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $id = trim ($this->input_post ('id'));
    $lat = trim ($this->input_post ('lat'));
    $lng = trim ($this->input_post ('lng'));
    $name = trim ($this->input_post ('name'));
    $postal_code = trim ($this->input_post ('postal_code'));

    if (!($id && $lat && $lng && ($town = Town::find_by_id ($id))))
      return $this->output_json (array ('status' => false));
    
    if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('id != ? AND postal_code = ?', $town->id, $postal_code))))
      return $this->output_json (array ('status' => false));

    if (($town->latitude == $lat) && ($town->longitude == $lng))
      $is_update_pic = false;
    else
      $is_update_pic = true;

    $town->latitude = $lat;
    $town->longitude = $lng;
    
    if ($name)
      $town->name = $name;

    if ($postal_code)
      $town->postal_code = $postal_code;

    if (!$town->save ())
      return $this->output_json (array ('status' => false));

    if ($is_update_pic)
      $town->put_pic ();

    return $this->output_json (array ('status' => true));
  }
}
