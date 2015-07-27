<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class TownWeather extends OaModel {
  static $paths = array ('resource', 'image', 'weather', 'd4');

  static $table_name = 'town_weathers';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
    array ('town', 'class_name' => 'Town')
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function icon () {
    return base_url (array_merge (self::$paths, array ($this->icon)));
  }
  public function special_icon () {
    return base_url (array_merge (self::$paths, array ('special', $this->special_icon)));
  }
  public function has_special () {
    return $this->special_icon && $this->special_status && $this->special_describe;
  }
  public function special_to_array () {
    return $this->has_special () ? array (
              'icon' => $this->special_icon (),
              'status' => $this->special_status,
              'describe' => $this->special_describe,
              'at' => $this->special_at->format ('Y-m-d H:m:i')
            ) : array ();
  }
  public function to_array () {
    return array (
          'id' => $this->id,
          'icon' => $this->icon (),
          'describe' => $this->describe,
          'temperature' => $this->temperature,
          'humidity' => $this->humidity,
          'rainfall' => $this->rainfall,
          'sunrise' => $this->sunrise,
          'sunset' => $this->sunset,
          'created_at' => $this->created_at->format ('Y-m-d H:m:i'),
          'special' => $this->special_to_array ()
        );
  }
  public function destroy () {
    return $this->delete ();
  }
}