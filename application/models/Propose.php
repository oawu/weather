<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Propose extends OaModel {

  static $table_name = 'proposes';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  
  public function picture ($width = 100, $height = 100, $zoom = 11) {
    return "http://maps.googleapis.com/maps/api/staticmap?center=" . $this->latitude . "," . $this->longitude . "&zoom=" . $zoom . "&size=" . $width . 'x' . $height . "&sensor=false";
  }
  public function destroy () {
    $this->id_enabled = 0;
    return $this->save ();
  }
  public function copy () {
    $params = array (
        'title' => $this->title,
        'icon' => '',
        'temperature' => '',
        'latitude' => $this->latitude,
        'longitude' => $this->longitude,
        'propose_id' => $this->id
      );
    if (verifyCreateOrm ($weather = Weather::create ($params))) {
      if ($this->destroy ()) {
        return true;
      } else {
        $weather->destroy ();
        return false;
      }
    } else {
      return false;
    }
  }
}