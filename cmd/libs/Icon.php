<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Icon {
  private static $arr = array (
      '01@2x.png' => array ('icon-w01'),
      '02@2x.png' => array ('icon-w02'),
      '03@2x.png' => array ('icon-w03'),
      '04@2x.png' => array ('icon-w04'),
      '05@2x.png' => array ('icon-w05', 'icon-w03'),
      '06@2x.png' => array ('icon-w06', 'icon-w02'),
      '07@2x.png' => array ('icon-w07'),
      '08@2x.png' => array ('icon-w01', 'icon-w02'),
      '09@2x.png' => array ('icon-w01', 'icon-w07'),
      '10@2x.png' => array ('icon-w03', 'icon-w07'),
      '11@2x.png' => array ('icon-w04', 'icon-w07'),
      '12@2x.png' => array ('icon-w08'),
      '13@2x.png' => array ('icon-w08'),
      '14@2x.png' => array ('icon-w04', 'icon-w07'),
      '15@2x.png' => array ('icon-w08'),
      '16@2x.png' => array ('icon-w08'),
      '17@2x.png' => array ('icon-w09', 'icon-w08'),
      '18@2x.png' => array ('icon-w09', 'icon-w08'),
      '19@2x.png' => array ('icon-w10', 'icon-w04', 'icon-w07'),
      '20@2x.png' => array ('icon-w09', 'icon-w08'),
      '21@2x.png' => array ('icon-w09', 'icon-w08'),
      '22@2x.png' => array ('icon-w07'),
      '23@2x.png' => array ('icon-w11', 'icon-w03', 'icon-w01'),
      '24@2x.png' => array ('icon-w08', 'icon-w01'),
      '25@2x.png' => array ('icon-w04', 'icon-w01'),
      '26@2x.png' => array ('icon-w04'),
      '27@2x.png' => array ('icon-w04'),
      '28@2x.png' => array ('icon-w04'),
      '29@2x.png' => array ('icon-w09', 'icon-w08', 'icon-w01'),
      '30@2x.png' => array ('icon-w12', 'icon-w10', 'icon-w04', 'icon-w01'),
      '31@2x.png' => array ('icon-w10', 'icon-w04'),
      '32@2x.png' => array ('icon-w10', 'icon-w04'),
      '33@2x.png' => array ('icon-w10', 'icon-w04'),
      '34@2x.png' => array ('icon-w09', 'icon-w08', 'icon-w01'),
      '35@2x.png' => array ('icon-w10', 'icon-w04', 'icon-w01'),
      '36@2x.png' => array ('icon-w10', 'icon-w04'),
      '37@2x.png' => array ('icon-w10', 'icon-w04'),
      '38@2x.png' => array ('icon-w10', 'icon-w04'),
      '39@2x.png' => array ('icon-w07', 'icon-w03'),
      '40@2x.png' => array ('icon-w01', 'icon-w03', 'icon-w11'),
      '41@2x.png' => array ('icon-w04', 'icon-w07'),
      '42@2x.png' => array ('icon-w04', 'icon-w03'),
      '43@2x.png' => array ('icon-w07'),
      '44@2x.png' => array ('icon-w05'),
      '45@2x.png' => array ('icon-w07'),
      '46@2x.png' => array ('icon-w07'),
      '47@2x.png' => array ('icon-w11', 'icon-w03', 'icon-w07'),
      '48@2x.png' => array ('icon-w04', 'icon-w07'),
      '49@2x.png' => array ('icon-w08', 'icon-w07'),
      '50@2x.png' => array ('icon-w04', 'icon-w07'),
      '51@2x.png' => array ('icon-w08', 'icon-w07'),
      '52@2x.png' => array ('icon-w09', 'icon-w08'),
      '53@2x.png' => array ('icon-w07'),
      '54@2x.png' => array ('icon-w11', 'icon-w03', 'icon-w07'),
      '55@2x.png' => array ('icon-w08', 'icon-w07'),
      '56@2x.png' => array ('icon-w04', 'icon-w07'),
      '57@2x.png' => array ('icon-w04'),
      '58@2x.png' => array ('icon-w09', 'icon-w08'),
      '59@2x.png' => array ('icon-w10', 'icon-w04'),
      '60@2x.png' => array ('icon-w13', 'icon-w04'),
      '61@2x.png' => array ('icon-w13'),
      '62@2x.png' => array ('icon-w10', 'icon-w05', 'icon-w04'),
      '63@2x.png' => array ('icon-w10', 'icon-w07'),
      '64@2x.png' => array ('icon-w13'),
      '65@2x.png' => array ('icon-w13', 'icon-w14'),
    );
  public static function get ($key) {
    return !(($key = pathinfo ($key, PATHINFO_BASENAME)) && isset (self::$arr[$key]) && self::$arr[$key]) ? '' : self::$arr[$key][0];
  }
  public static function gets ($key) {
    return !(($key = pathinfo ($key, PATHINFO_BASENAME)) && isset (self::$arr[$key]) && self::$arr[$key]) ? array () : self::$arr[$key];
  }

}
      