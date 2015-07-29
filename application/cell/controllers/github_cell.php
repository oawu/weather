<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Github_cell extends Cell_Controller {

  /* render_cell ('github_cell', 'get_more', $id); */
  public function _cache_get_more ($id) {
    return array ('time' => 60 * 60, 'key' => $id);
  }
  public function get_more ($id) {
    if (!($id && ($town = Town::find_by_id ($id, array ('select' => 'id, town_category_id')))))
      return array ();

    $towns = Town::find ('all', array ('include' => array ('view'), 'limit' => 12, 'order' => 'RAND()', 'conditions' => array ('town_category_id = ? AND id != ?', $town->town_category_id, $town->id)));

    if (count ($towns) < 12)
      $towns = array_merge ($towns, Town::find ('all', array ('include' => array ('view'), 'limit' => 12 - count ($towns), 'order' => 'RAND()', 'conditions' => count ($towns) ? array ('id NOT IN (?) AND id != ?', column_array ($towns, 'id'), $town->id) : array ('id != ?', $town->id))));
    
    return array_map (function ($town) {
      return array (
          'id' => $town->id,
          'src' => $town->view ? $town->view->pic->url ('200x200c') : $town->pic->url ('200x200c'),
          'name' => $town->name
        );
    }, $towns);
  }

  /* render_cell ('github_cell', 'get_town', $key, $this); */
  public function _cache_get_town ($key, $that) {
    return array ('time' => 60 * 60, 'key' => $key);
  }
  public function get_town ($key, $that) {

    if (!$key) return array ();

    $conditions = is_numeric ($key) ? array ('id = ?', $key) : array ('name LIKE CONCAT("%", ? ,"%")', $key);
    
    $town = Town::find ('one', array ('conditions' => $conditions));

    if (!($town && $town->update_weather ()))
      return array ();

    if (!($content = $that->_content_format ($town)))
      return array ();
      
    if (!($weather = $that->_weather_format ($town)))
      return array ();
    
    return array_filter (array_merge (array (
        'id' => $town->id,
        'lat' => $town->latitude,
        'lng' => $town->longitude,
        'name' => $town->name,
        'category' => $town->category->name,
      ), $content, $weather, $that->_weather_view ($town), $that->_town_temperatures ($town))); 
  }

  /* render_cell ('github_cell', 'index_specials', $this); */
  public function _cache_index_specials ($that) {
    return array ('time' => 60 * 60, 'key' => null);
  }
  public function index_specials ($that) {
    if (!(($last = TownWeather::last (array ('select' => 'created_at'))) && ($last = $last->created_at->format ('Y-m-d H:00:00'))))
      return array ();

    $weathers = TownWeather::find ('all', array ('conditions' => array ('created_at > ? AND special_icon != ? AND special_status != ? AND special_describe != ?', $last, '', '', '')));
    $town_ids = array_unique (column_array ($weathers, 'town_id'));

    $towns = array ();
    if ($town_ids)
      foreach (Town::find ('all', array ('include' => array ('category'), 'select' => 'id, name, town_category_id', 'conditions' => array ('id IN (?)', $town_ids))) as $town)
        $towns[$town->id] = $town;

    $specials = array ();
    foreach ($weathers as $weather) {
      if (!isset ($specials[$weather->special_status . '-' . $towns[$weather->town_id]->category->name]))
        $specials[$weather->special_status . '-' . $towns[$weather->town_id]->category->name] = array ('special' => array_merge (array ('title' => $towns[$weather->town_id]->category->name . ' - ' . $weather->special_status), $weather->special_to_array ()), 'towns' => array ());

      array_push ($specials[$weather->special_status . '-' . $towns[$weather->town_id]->category->name]['towns'], array ('id' => $towns[$weather->town_id]->id, 'name' => $towns[$weather->town_id]->name));
    }

    $units = array ();

    if ($weather = TownWeather::find ('one', array ('order' => 'temperature DESC', 'conditions' => array ('created_at > ?', $last))))
      array_push ($units, array ('title' => '目前最高溫', 'info' => array_merge (array (
                  'id' => $weather->town_id,
                ), $that->_content_format (null, $weather), $that->_weather_format (null, $weather))));

    if ($weather = TownWeather::find ('one', array ('order' => 'temperature ASC', 'conditions' => array ('created_at > ?', $last))))
      array_push ($units, array ('title' => '目前最低溫', 'info' => array_merge (array (
                  'id' => $weather->town_id,
                ), $that->_content_format (null, $weather), $that->_weather_format (null, $weather))));

    if ($weather = TownWeather::find ('one', array ('order' => 'humidity DESC', 'conditions' => array ('created_at > ?', $last))))
      array_push ($units, array ('title' => '目前濕度最高', 'info' => array_merge (array (
                  'id' => $weather->town_id,
                ), $that->_content_format (null, $weather), $that->_weather_format (null, $weather))));

    if ($weather = TownWeather::find ('one', array ('order' => 'humidity ASC', 'conditions' => array ('created_at > ?', $last))))
      array_push ($units, array ('title' => '目前濕度最低', 'info' => array_merge (array (
                  'id' => $weather->town_id,
                ), $that->_content_format (null, $weather), $that->_weather_format (null, $weather))));

    if ($weather = TownWeather::find ('one', array ('order' => 'rainfall DESC', 'conditions' => array ('created_at > ? AND rainfall > ?', $last, 0))))
      array_push ($units, array ('title' => '目前雨量最多', 'info' => array_merge (array (
                  'id' => $weather->town_id,
                ), $that->_content_format (null, $weather), $that->_weather_format (null, $weather))));

    if ($weather = TownWeather::find ('one', array ('order' => 'rainfall ASC', 'conditions' => array ('created_at > ? AND rainfall > ?', $last, 0))))
      array_push ($units, array ('title' => '目前雨量最少', 'info' => array_merge (array (
                  'id' => $weather->town_id,
                ), $that->_content_format (null, $weather), $that->_weather_format (null, $weather))));

    return array (
        'specials' => array_values ($specials),
        'units' => $units
      );
  }
}