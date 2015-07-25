<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Town_cell extends Cell_Controller {

  /* render_cell ('town_cell', 'update_weather', array ()); */
  public function _cache_update_weather ($town) {
    return array ('time' => 60 * 60, 'key' => $town->id);
  }
  public function update_weather ($town) {
    $this->CI->load->library ('phpQuery');
    $base_url = 'http://www.cwb.gov.tw';

    $url = 'http://www.cwb.gov.tw/m/f/town368/GT/' . $town->cwb_town_id . '.htm?_=' . time ();
    $get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url)));
    
    if (!$get_html_str)
      return $town->weather_array ();

    $php_query = phpQuery::newDocument ($get_html_str);
    $img = pq ('.icon img', $php_query);
    $describe = pq ('.icon-text-1', $php_query);
    $degree = pq ('.degree', $php_query);
    $humidity = pq ('.humidity', $php_query);
    $rainfall = pq ('.rainfall', $php_query);
    $sunrise = pq ('.sunrise', $php_query);
    $sunset = pq ('.sunset', $php_query);

    if (!(count ($img) && count ($describe) && count ($degree) && count ($humidity) && count ($rainfall) && count ($sunrise) && count ($sunset)))
      return $town->weather_array ();
    
    if (!($img_name = pathinfo ($img->attr ('src'), PATHINFO_BASENAME)))
      return $town->weather_array ();

    $file_paths = array_merge (TownWeather::$paths, array ($img_name));
    $img_url = $base_url . $img->attr ('src');

    $describe_text = $describe->text ();
    $degree_text = $degree->text ();
    $humidity_text = $humidity->parent ()->text ();
    $rainfall_text = $rainfall->parent ()->text ();
    $sunrise_text = $sunrise->parent ()->text ();
    $sunset_text = $sunset->parent ()->text ();

    if (!(file_exists (FCPATH . implode (DIRECTORY_SEPARATOR, $file_paths)) || download_web_file ($img_url, FCPATH . implode (DIRECTORY_SEPARATOR, $file_paths))))
      return $town->weather_array ();

    $params = array (
        'town_id' => $town->id,
        'icon' => $img_name,
        'describe' => $describe_text,
        'temperature' => $degree_text,
        'humidity' => $humidity_text,
        'rainfall' => $rainfall_text,
        'sunrise' => $sunrise_text,
        'sunset' => $sunset_text,
        'special_icon' => '',
        'special_status' => '',
        'special_describe' => '',
        'special_at' => date ('Y-m-d H:i:s')
      );

    $weather = TownWeather::create ($params);

    $url = 'http://www.cwb.gov.tw/V7/forecast/town368/warn/' . $town->cwb_town_id . '.js?_=' . time ();
    $get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url)));

    if (!$get_html_str)
      return $weather->to_array ();

    $pattern = '/(["\'])(?P<kv>(?>[^"\'\\\]++|\\\.|(?!\1)["\'])*)\1?/';
    preg_match_all ($pattern, $get_html_str, $result);

    if (!$result['kv'])
      return $weather->to_array ();
    
    $icons = array (
      'W21' => '/pda/images/warning/Typhoon.png',
      'W25' => '/pda/images/warning/Gale.png',
      'W26' => '/pda/images/warning/Heavy-rain.png',
      'W27' => '/pda/images/warning/Fog.png',
      'W28' => '/pda/images/warning/Hypothermia.png',
      'W30' => '/pda/images/warning/Gale.png'
      );

    $icon = $icons[$result['kv'][1]];
    $img_url = $base_url . $icons[$result['kv'][1]];
    $status = $result['kv'][0];
    $at = $result['kv'][3];
    $describe = $result['kv'][4];

    if (!($icon && $status && $at && $describe))
      return $weather->to_array ();

    if (!($img_name = pathinfo ($icon, PATHINFO_BASENAME)))
      return $weather->to_array ();

    $file_paths = array ('resource', 'image', 'weather', $img_name);
    $file_paths = array_merge (TownWeather::$paths, array ('special', $img_name));

    if (!(file_exists (FCPATH . implode (DIRECTORY_SEPARATOR, $file_paths)) || download_web_file ($img_url, FCPATH . implode (DIRECTORY_SEPARATOR, $file_paths))))
      return $weather->to_array ();

    $weather->special_icon = $img_name;
    $weather->special_status = $status;
    $weather->special_describe = $describe;
    $weather->special_at = $at;
    $weather->save ();

    return $weather->to_array ();
  }
}