<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

namespace ActiveRecord;

class OrmCache {

  private $CI;
  private $configs;
  private $redis;

  public function __construct () {
    $this->CI =& get_instance();
    $this->CI->load->library ('cfg');
    $this->configs = \Cfg::_system ('model', 'cache');

    if ($this->configs['driver'] == 'redis') {
      $this->CI->load->library ("redis");
      $this->redis = new \CI_Redis (array ('no_cfg_cache' => true));
      $this->configs['driver'] = $this->redis->getStatus ($error) ? 'redis' : 'file';
    }
  }

  public function flush () {
    if ($this->configs['driver'] == 'redis') {
      $keys = implode (':', $this->configs['redis_main_key']) . ':' . '*';
      $keys = !preg_match ('/\*$/', $keys) ? $this->redis->exists ($keys) ? array ($keys) : array () : $this->redis->keys ($keys);

      if ($keys)
        foreach ($keys as $key)
          $this->redis->del ($keys);

      return true;
    } else {
      return $this->CI->cache->file->clean (FCPATH . implode (DIRECTORY_SEPARATOR, $this->configs['file_path']) . DIRECTORY_SEPARATOR);
    }
  }

  public function read ($key) {
    if ($this->configs['driver'] == 'redis')
      if (($value = $this->redis->hGetArray (implode (':', $this->configs['redis_main_key']) . ':' . $key)) && (time () < $value['time']))
        return unserialize ($value['value']);
      else
        return null;
    else
      return $this->CI->cache->file->get ($key, FCPATH . implode (DIRECTORY_SEPARATOR, $this->configs['file_path']) . DIRECTORY_SEPARATOR);
  }

  public function write ($key, $value, $expire = null) {
    if (!$this->configs['is_enabled'])
      return;

    if ($this->configs['driver'] == 'redis')
      return $this->redis->hmset (implode (':', $this->configs['redis_main_key']) . ':' . $key, 'value', serialize ($value), 'time', time () + ($expire ? $expire : $this->configs['d4_cache_time']));
    else
      return $this->CI->cache->file->save ($key, $value, $expire ? $expire : $this->configs['d4_cache_time'], FCPATH . implode (DIRECTORY_SEPARATOR, $this->configs['file_path']) . DIRECTORY_SEPARATOR);
  }
}