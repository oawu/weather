<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class CrontabLog extends OaModel {
  public $start_at = 0;

  static $table_name = 'crontab_logs';

  static $has_one = array (
  );

  static $has_many = array (
  );

  static $belongs_to = array (
  );

  public function __construct ($attributes = array (), $guard_attributes = true, $instantiating_via_find = false, $new_record = true) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public static function start ($message) {
    $obj = self::create (array (
            'type' => '未完成',
            'message' => $message,
            'run_time' => '0'
          ));

    $obj->start_at = microtime (true);
    return $obj;
  }
  public function error () {
    $type->type = '錯誤';
    $this->run_time = microtime (true) - $this->start_at;
    return $this->save ();
  }
  public function finish () {
    $this->type = '完成';
    $this->run_time = round (microtime (true) - $this->start_at, 4);
    return $this->save ();
  }
  public function destroy () {
    return $this->delete ();
  }
}