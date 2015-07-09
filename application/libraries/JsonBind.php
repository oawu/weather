<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * ! Bind column_name must be select.
 *
 * Develop by OA Wu 2013.11.08
 * Update by OA Wu 2014.03.10
 */
class JsonBind extends stdClass {
  private $active_object = null;
  private $column_name = null;
  private $column_value = null;
  private $is_init_success = false;

  public function __construct ($configs = array ()) {
    if (count ($configs) && is_array ($configs) && $this->_check_config_format ($configs)) {
      $this->active_object = $configs['active_object'];
      $this->column_name   = $configs['column_name'];

      $this->is_init_success = $this->_init ();
    }
  }

  private function _check_config_format ($configs) {
    $must_keys = array ('active_object', 'column_name');
    return count ($must_keys) == count (array_filter (array_map (function ($must_key) use ($configs) { return array_key_exists ($must_key, $configs) ? $must_key : null; }, $must_keys))) ? true : false;
  }

  private function _init () {
    $active_object = $this->active_object;
    $column_name = $this->column_name;
    if (isset ($active_object->$column_name)) {
      $this->column_value = $active_object->$column_name;
      $active_object->$column_name = $this;

      $json = json_decode ((string)$this->column_value);
      $list = isset ($json) && ($json !== null) && is_object ($json) ? get_object_vars ($json) : array ();

      if (isset ($list) && is_array ($list) && count ($list)) {
        foreach ($list as $key => $value) {
          $this->$key = $value;
        }
      }
      return true;
    } else { return false; }
  }

  public function __toString () {
    return (string)$this->column_value;
  }

  // $user->memo->reset_column (array ('ids' => $array, .....));
  public function reset_column ($array) {
    $is_success = false;
    if ($this->is_init_success) {

      $active_object = $this->active_object;
      $column_name = $this->column_name;
      $active_object->$column_name = (string)json_encode ($array);
      $active_object->save ();

      $this->is_init_success = $this->_init ();
    }
    return $is_success && $this->is_init_success;
  }

  // $user->memo->ids = $array;
  // $user->memo->save ();
  public function save () {
    $active_object = $this->active_object;
    $column_name = $this->column_name;

    $list = array_diff (array_keys (get_object_vars ($active_object->$column_name)), array_keys (get_class_vars (get_class ($this))));

    if (isset ($list) && is_array ($list) && count ($list)) {
      $data = array ();
      foreach ($list as $key) {
        if (is_array ($active_object->$column_name->$key)) {
          $data[$key] = $active_object->$column_name->$key;
        } else if (is_object ($active_object->$column_name->$key)) {
          $data[$key] = (array)$active_object->$column_name->$key;
        } else if (is_string ($active_object->$column_name->$key) || is_numeric ($active_object->$column_name->$key)) {
          $data[$key] = $active_object->$column_name->$key;
        }
      }
      $this->reset_column ($data);
    }
  }

  // $user->memo->clear ();
  public function clear () {
    $active_object = $this->active_object;
    $column_name = $this->column_name;
    try {
      $active_object->$column_name = null;
      $active_object->save ();
    } catch (Exception $e) {
      $active_object->$column_name = '';
      $active_object->save ();
    }
  }

  // CI =& get_instance ();
  // CI->jsonbind->bind ('memo');
  public static function bind ($column_name) {
    $trace = debug_backtrace (DEBUG_BACKTRACE_PROVIDE_OBJECT);
    if (isset ($trace) && count ($trace) > 1 && isset ($trace[1]) && isset ($trace[1]['object']) && is_object ($trace[1]['object']) && isset ($column_name) && is_string ($column_name) && ($column_name != '')) {
      $active_object = $trace[1]['object'];
      $json_object = new JsonBind (array ('active_object' => $active_object, 'column_name' => $column_name));
    }
  }
}