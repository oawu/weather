<?php
namespace ActiveRecord;

class File
{

  private $CI;

  /**
   * Creates a File instance.
   * @param array $options
   */
  public function __construct($options=array())
  {
    $this->CI =& get_instance();
    $this->CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
  }

  public function flush()
  {
    $this->CI->cache->file->clean();
  }

  public function read($key)
  {
    return $this->CI->cache->file->get($key);
  }

  public function write($key, $value, $expire="300")
  {
    $this->CI->cache->file->save($key, $value, $expire);
  }
}
?>
