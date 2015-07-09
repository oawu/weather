<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Cell {
  private $CI = null;
  private $configs = array ();

  public function __construct ($configs = array ()) {
    $this->CI =& get_instance ();
    $this->CI->load->library ("cfg");
    $this->configs = array_merge (Cfg::system ('cell'), $configs);

    if ($this->configs['driver'] == 'redis') {
      $this->CI->load->library ("redis");
      $this->configs['driver'] = $this->CI->redis->getStatus ($error) ? 'redis' : 'file';
    }
  }

  public function render_cell ($class, $method, $params = array ()) {
    if (!preg_match ('/(' . $this->configs['class_suffix'] . ')$/', $class))
      return show_error ("The class name doesn't have suffix!<br/>class name: " . $class . "<br/>suffix: " . Cfg::system ('cell', 'class_suffix'));

    if (!is_readable ($path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['folders']['controller'], array ($class . EXT)))))
      return show_error ("The Cell's controllers is not exist or can't read!<br/>File: " . $path);

    include_once ($path);
    $Class = ucfirst ($class);
    $object = new $Class ();

    if (!is_callable (array ($object, $method)))
      return show_error ("The class: " . $path . " not exist method: " . $method);

    if ($this->configs['is_enabled'] && is_callable (array ($object, $cache_method = $this->configs['method_prefix'] . $method)) && ($option = call_user_func_array (array ($object, $cache_method), $params))) {
      $option['time'] = isset ($option['time']) && $option['time'] > 0 ? $option['time'] : $this->configs['d4_cache_time'];
      $option['key'] = isset ($option['key']) && $option['key'] ? $option['key'] : null;
      $name = array_filter (is_array ($option['key']) ? array_merge (array ($class, $method), $option['key']) : array ($class, $method, $option['key']));

      if ($this->configs['driver'] == 'redis') {
        if ((array_unshift ($name, implode (':', $this->configs['redis_main_key']))) && ($value = $this->CI->redis->hGetArray ($key = implode (':', $name))) && (time () < $value['time'])) {
          $js_list = unserialize ($value['js_list']);
          $css_list = unserialize ($value['css_list']);
          $view = unserialize ($value['view']);
        } else {
          $view = call_user_func_array (array ($object, $method), $params);
          $js_list = call_user_func_array (array ($object, 'getJsList'), array ());
          $css_list = call_user_func_array (array ($object, 'getCssList'), array ());
          $this->CI->redis->hmset ($key, 'view', serialize ($view), 'js_list', serialize ($js_list), 'css_list', serialize ($css_list), 'time', time () + $option['time']);
        }
      } else {
        $key = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['folders']['cache'], $name));

        if (!is_dir (dirname ($key))) {
          $oldmask = umask (0);
          @mkdir (dirname ($key), 0777, true);
          umask ($oldmask);
        }

        if (!($value = $this->CI->cache->file->get ($name = $this->configs['file_is_md5'] ? md5 (basename ($key) . $this->configs['file_prefix']) : basename ($key) . $this->configs['file_prefix'], dirname ($key) . DIRECTORY_SEPARATOR))) {
          $view = call_user_func_array (array ($object, $method), $params);
          $js_list = call_user_func_array (array ($object, 'getJsList'), array ());
          $css_list = call_user_func_array (array ($object, 'getCssList'), array ());
          $value = array ('view' => serialize ($view), 'js_list' => serialize ($js_list), 'css_list' => serialize ($css_list));

          $this->CI->cache->file->save ($name, $value, $option['time'], dirname ($key) . DIRECTORY_SEPARATOR);
        } else {
          $js_list = unserialize ($value['js_list']);
          $css_list = unserialize ($value['css_list']);
          $view = unserialize ($value['view']);
        }
      }
    } else {
      $view = call_user_func_array (array ($object, $method), $params);
      $js_list = call_user_func_array (array ($object, 'getJsList'), array ());
      $css_list = call_user_func_array (array ($object, 'getCssList'), array ());
    }

    if ($js_list)
      foreach ($js_list as $js)
        $this->CI->add_js ($js['path'], $js['is_minify']);

    if ($css_list)
      foreach ($css_list as $css)
        $this->CI->add_css ($css['path'], $css['is_minify']);

    return $view;
  }

  public function clean_cell ($keys) {
    if ($this->configs['driver'] == 'redis') {
      array_unshift ($keys, $this->configs['redis_main_key']);

      $keys = implode (':', $keys);
      $keys = !preg_match ('/\*$/', $keys) ? $this->CI->redis->exists ($keys) ? array ($keys) : array () : $this->CI->redis->keys ($keys);

      if ($keys)
        foreach ($keys as $key)
          $this->CI->redis->del ($keys);

      return true;
    } else {
      if (($last = array_pop ($keys)) != '*')
        array_push ($keys, $this->configs['file_is_md5'] ? md5 ($last . $this->configs['file_prefix']) : $last . $this->configs['file_prefix']);

      $keys = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['folders']['cache'], $keys));

      if (is_file ($keys) && !preg_match ('/\*$/', $keys)) {
        @unlink ($keys);
        return !file_exists ($keys);
      } else if (is_dir ($keys)) {
        $this->CI->load->helper ('directory');
        return directory_delete ($keys, $keys != FCPATH . implode (DIRECTORY_SEPARATOR, $this->configs['folders']['cache']));
      } else {
        return false;
      }
    }
  }
}

class Cell_Controller {
  protected $CI = null;
  private $configs = array ();
  private $js_list = array ();
  private $css_list = array ();
  private $is_use_js_list = false;
  private $is_use_css_list = false;

  public function __construct ($configs = array ()) {
    $this->CI =& get_instance ();
    $this->CI->load->library ("cfg");
    $this->configs = array_merge (Cfg::system ('cell'), $configs);
    $this->setUseJsList (false);
    $this->setUseCssList (false);
  }

  public function add_js ($path, $is_minify = true) {
    array_push ($this->js_list, array ('path' => $path, 'is_minify' => $is_minify));
    return $this;
  }
  public function add_css ($path, $is_minify = true) {
    array_push ($this->css_list, array ('path' => $path, 'is_minify' => $is_minify));
    return $this;
  }

  public function getJsList () {
    return $this->js_list;
  }
  public function getCssList () {
    return $this->css_list;
  }

  public function setUseJsList ($is_use_js_list = false) {
    $this->is_use_js_list = $is_use_js_list;
    return $this;
  }
  public function setUseCssList ($is_use_css_list = false) {
    $this->is_use_css_list = $is_use_css_list;
    return $this;
  }

  protected function load_view ($data = array (), $set_method = null, $set_class = null) {
    $trace = debug_backtrace (DEBUG_BACKTRACE_PROVIDE_OBJECT);

    if (!(isset ($trace) && (count ($trace) > 1) && isset ($trace[1]) && isset ($trace[1]['class']) && isset ($trace[1]['function']) && is_string ($class = strtolower ($trace[1]['class'])) && is_string ($method = strtolower ($trace[1]['function'])) && strlen ($class) && strlen ($method)))
      return show_error ('The debug_backtrace Error!');;

    if (!is_readable ($_ci_path = FCPATH . implode (DIRECTORY_SEPARATOR, array_merge ($this->configs['folders']['view'], array ($set_class ? $set_class : $class, ($set_method ? $set_method : $method), 'content' . EXT)))))
      return show_error ("The Cell's controllers is not exist or can't read!<br/>File: " . $_ci_path);

    if ($this->is_use_js_list && is_readable (implode (DIRECTORY_SEPARATOR, $path = array_merge ($this->configs['folders']['view'], array ($set_class ? $set_class : $class, ($set_method ? $set_method : $method), 'content.js')))))
      $this->add_js (base_url (implode ('/', $path)));

    if ($this->is_use_css_list && is_readable (implode (DIRECTORY_SEPARATOR, $path = array_merge ($this->configs['folders']['view'], array ($set_class ? $set_class : $class, ($set_method ? $set_method : $method), 'content.css')))))
      $this->add_css (base_url (implode ('/', $path)));

    extract ($data);
    ob_start();

    if (((bool)@ini_get ('short_open_tag') === FALSE) && (config_item ('rewrite_short_tags') == TRUE))
      echo eval ('?>'.preg_replace ("/;*\s*\?>/", "; ?>", str_replace ('<?=', '<?php echo ', file_get_contents ($_ci_path))));
    else
      include ($_ci_path);

    $buffer = ob_get_contents ();
    @ob_end_clean ();
    return $buffer;
  }
}