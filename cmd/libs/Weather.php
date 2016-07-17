<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Weather {
  private static $historyLimit = 12;
  private static $specialIcons = array (
    'W21' => array ('title' => '颱風警報', 'url' => '/pda/images/warning/Typhoon.png'),
    'W25' => array ('title' => '陸上強風特報', 'url' => '/pda/images/warning/Gale.png'),
    'W26' => array ('title' => '大雨特報', 'url' => '/pda/images/warning/Heavy-rain.png'),
    'W27' => array ('title' => '濃霧特報', 'url' => '/pda/images/warning/Fog.png'),
    'W28' => array ('title' => '低溫特報', 'url' => '/pda/images/warning/Hypothermia.png'),
    'W30' => array ('title' => '海上強風特報', 'url' => '/pda/images/warning/Gale.png'),
    'W33' => array ('title' => '大雷雨即時訊息', 'url' => '/pda/images/warning/Heavy-rain.png'),
  );
  public static function getWeather ($town) {
    if (!is_array ($weather = Weather::getNowWeather ($town))) return $weather;

    if (!$last_weather = Weather::getLastWeather ($town)) $weather['histories'] = array ($weather);
    else $weather['histories'] = array_merge ($last_weather['histories'], array ($weather));
    
    $weather['histories'] = array_values (array_slice (array_map (function ($history) { unset ($history['specials']); return $history; }, $weather['histories']), 0 - Weather::$historyLimit));
    return $weather;
  }
  public static function getNowWeather ($town) {
    $cate = $town['cate'];
    $cwb_url = 'http://www.cwb.gov.tw';

    $url = $cwb_url . '/m/f/town368/GT/' . $town['cwb_id'] . '.htm?_=' . time ();
    if (!$get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url))))
      return '取不到天氣原始碼';

    $php_query = phpQuery::newDocument ($get_html_str);

    $img = pq ('.icon img', $php_query);
    $describe = pq ('.icon-text-1', $php_query);
    $degree = pq ('.degree', $php_query);
    $humidity = pq ('.humidity', $php_query);
    $rainfall = pq ('.rainfall', $php_query);
    $sunrise = pq ('.sunrise', $php_query);
    $sunset = pq ('.sunset', $php_query);
 
    if (!(count ($img) && count ($describe) && count ($degree) && count ($humidity) && count ($rainfall) && count ($sunrise) && count ($sunset) && ($img_name = pathinfo ($img->attr ('src'), PATHINFO_BASENAME)) && ($img_url = $cwb_url . $img->attr ('src'))))
      return '分析原始碼內容錯誤';

    $weather = array (
        'img' => file_exists ($file_paths = PATH_IMG_WEATHERS . $img_name) || download ($img_url, $file_paths) ? $img_name : '',
        'desc' => trim ($describe->text ()),
        'temperature' => parse_number ($degree->text ()),
        'humidity' => parse_number ($humidity->parent ()->text ()),
        'rainfall' => parse_number ($rainfall->parent ()->text ()),
        'sunrise' => trim ($sunrise->parent ()->text ()),
        'sunset' => trim ($sunset->parent ()->text ()),
        'at' => date ('Y-m-d H:i:s'),
        'specials' => array ()
      );

    $url = $cwb_url . '/V7/forecast/town368/warn/' . $town['cwb_id'] . '.js?_=' . time ();
    if (!(($get_html_str = str_replace ('&amp;', '&', urldecode (file_get_contents ($url)))) && preg_match_all ('/(["\'])(?P<kv>(?>[^"\'\\\]++|\\\.|(?!\1)["\'])*)\1?/u', $get_html_str, $result) && $result['kv'] && (count ($result['kv']) > 4)))
      return $weather;

    $c = count ($result['kv']);
    for ($i = 0; $i < $c; $i += 5)
      if (!isset (Weather::$specialIcons[$result['kv'][$i + 1]])) continue;
      else
        array_push ($weather['specials'], array (
            'title' => $title = Weather::$specialIcons[$result['kv'][$i + 1]]['title'],
            'status' => $status = trim ($result['kv'][$i + 0]),
            'at' => $at = trim ($result['kv'][$i + 3]),
            'desc' => !($desc = trim ($result['kv'][$i + 4])) ? '目前已經針對 ' . $cate['name'] . ' ' . $town['name'] . ' 在 ' . $at . ' 發布' . $status . '的' . $title : $desc,
            'img' => ($icon = Weather::$specialIcons[$result['kv'][$i + 1]]['url']) && ($img_name = pathinfo ($icon, PATHINFO_BASENAME)) && (file_exists ($file_paths = PATH_IMG_SPECIALS . $img_name) || download ($cwb_url . $icon, $file_paths)) ? $img_name : '',
          ));

    return $weather;
  }

  public static function getLastWeather ($town) {
    if (($last_weather = read_file (PATH_API_WEATHERS . $town['id'] . '.json')) === false) return array ();
    return json_decode ($last_weather, true);
  }
}