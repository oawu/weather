<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class TownCategory extends OaModel {

  static $table_name = 'town_categories';

  static $has_one = array (
    array ('town', 'class_name' => 'Town', 'order' => 'RAND()')
  );

  static $has_many = array (
    array ('towns', 'class_name' => 'Town')
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }
  public function destroy () {
    Town::delete_all (array ('conditions' => array ('town_category_id = ?', $this->id)));
    return $this->delete ();
  }
}