<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Town extends OaModel {

  static $table_name = 'towns';

  static $has_one = array (
    array ('bound', 'class_name' => 'TownBound'),
    array ('view', 'class_name' => 'TownView')
  );

  static $has_many = array (
    array ('weathers', 'class_name' => 'TownWeather', 'order' => 'id DESC')
  );

  static $belongs_to = array (
    array ('category', 'class_name' => 'TownCategory')
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    OrmImageUploader::bind ('pic', 'TownPicImageUploader');
  }
  public function update_weather () {
    return render_cell ('town_cell', 'update_weather', $this);
  }
  public static function update_weather_all () {
    foreach (self::all (array ('select' => 'id, cwb_town_id')) as $town)
      $town->update_weather ();
  }
  public function weather_array () {
    return $this->weathers && ($weather = $this->weathers[0]) ? $weather->to_array () : array ();
  }

  public function put_pic () {
    return $this->pic->put_url ($this->picture ('300x300', 'server_key'));
  }

  public function picture ($size = '60x60', $type = 'client_key', $zoom = 13, $marker_size = 'normal') {
    $marker_size = in_array ($marker_size, array ('normal', 'tiny', 'mid', 'small')) ? $marker_size : 'normal';
    return "http://maps.googleapis.com/maps/api/staticmap?center=" . $this->latitude . "," . $this->longitude . "&zoom=" . $zoom . "&size=" . $size . "&markers=size:" . $marker_size . "|color:red|" . $this->latitude . "," . $this->longitude . "&key=" . Cfg::setting ('google', ENVIRONMENT, $type);
  }

  public function destroy () {
    TownBound::delete_all (array ('conditions' => array ('town_id = ?', $this->id)));
    TownWeather::delete_all (array ('conditions' => array ('town_id = ?', $this->id)));
    TownView::delete_all (array ('conditions' => array ('town_id = ?', $this->id)));
    return $this->pic->cleanAllFiles () && $this->delete ();
  }
}