<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function query () {
    $this->load->helper ('file');
    write_file (FCPATH . 'application/logs/query.log', '', FOPEN_READ_WRITE_CREATE_DESTRUCTIVE);
  }
  public function index () {
    // $this->add_meta (array ('property' => 'og:url', 'content' => current_url ()))
    //      ->add_hidden (array ('id' => 'get_weathers_url', 'value' => base_url ($this->get_class (), 'get_weathers')))
    //      ->add_js ('https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=zh-TW', false)
    //      ->add_js (base_url ('resource', 'javascript', 'markerwithlabel_d2015_06_28', 'markerwithlabel.js'))
    //      ->add_js (base_url ('resource', 'javascript', 'markerclusterer_v1.0', 'markerclusterer.js'))
    //      ->load_view (null);
  }

  public function get_weathers () {
    header ('Content-type: text/html');
    header ('Access-Control-Allow-Origin: http://dev.comdan66.github.io');

    // if (!$this->is_ajax (false))
    //   return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $north_east = $this->input_post ('NorthEast');
    $south_west = $this->input_post ('SouthWest');

    if (!(isset ($north_east['latitude']) && isset ($south_west['latitude']) && isset ($north_east['longitude']) && isset ($south_west['longitude'])))
      return $this->output_json (array ('status' => true, 'weathers' => array ()));

    $weathers = array_filter (array_map (function ($weather) {
      $info = render_cell ('weather_cell', 'icon', $weather);
      return $info ? array (
          'id' => $weather->id,
          'lat' => $weather->latitude,
          'lng' => $weather->longitude,
          'title' => $weather->title,
          'temp' => $info['temperature'],
          'icon' => $info['icon']
        ) : array ();
    }, Weather::find ('all', array ('conditions' => array ('latitude < ? AND latitude > ? AND longitude < ? AND longitude > ?', $north_east['latitude'], $south_west['latitude'], $north_east['longitude'], $south_west['longitude'])))));

    return $this->output_json (array ('status' => true, 'weathers' => $weathers));
  }
}
