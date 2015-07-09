<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2006 - 2012 EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 2.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Memcached Caching Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Core
 * @author		ExpressionEngine Dev Team
 * @link
 */

class CI_Cache_file extends CI_Driver {

	protected $_cache_path = null;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$CI =& get_instance();
		$CI->load->helper('file');
		!isset ($CI->cfg) && $CI->load->library ('cfg');
  	$path = implode (DIRECTORY_SEPARATOR, Cfg::_system ('cache', 'file')) . DIRECTORY_SEPARATOR;
		$this->set_cache_path (($path == '') ? APPPATH.'cache/' : $path);
	}

	// ------------------------------------------------------------------------
	public function set_cache_path ($_cache_path) {
		$this->_cache_path = ($_cache_path == '') ? APPPATH . 'cache/' : $_cache_path;
	}

	/**
	 * Fetch from cache
	 *
	 * @param 	mixed		unique key id
	 * @return 	mixed		data on success/false on failure
	 */
	public function get($id, $path = null)
	{
		if ( ! file_exists(($path ? $path : $this->_cache_path).$id))
		{
			return FALSE;
		}

		$data = read_file(($path ? $path : $this->_cache_path).$id);
		$data = unserialize($data);

		if (time() >  $data['time'] + $data['ttl'])
		{
			unlink(($path ? $path : $this->_cache_path).$id);
			return FALSE;
		}

		return $data['data'];
	}

	// ------------------------------------------------------------------------

	/**
	 * Save into cache
	 *
	 * @param 	string		unique key
	 * @param 	mixed		data to store
	 * @param 	int			length of time (in seconds) the cache is valid
	 *						- Default is 60 seconds
	 * @return 	boolean		true on success/false on failure
	 */
	public function save($id, $data, $ttl = 60, $path = null)
	{
		$contents = array(
				'time'		=> time(),
				'ttl'		=> $ttl,
				'data'		=> $data
			);

		if (write_file(($path ? $path : $this->_cache_path).$id, serialize($contents)))
		{
			@chmod(($path ? $path : $this->_cache_path).$id, 0777);
			return TRUE;
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Delete from Cache
	 *
	 * @param 	mixed		unique identifier of item in cache
	 * @return 	boolean		true on success/false on failure
	 */
	public function delete($id, $path = null)
	{
		return unlink(($path ? $path : $this->_cache_path).$id);
	}

	// ------------------------------------------------------------------------

	/**
	 * Clean the Cache
	 *
	 * @return 	boolean		false on failure/true on success
	 */
	public function clean($path = null, $is_dir = false)
	{
		return delete_files($path ? $path : $this->_cache_path, $is_dir);
	}

	// ------------------------------------------------------------------------

	/**
	 * Cache Info
	 *
	 * Not supported by file-based caching
	 *
	 * @param 	string	user/filehits
	 * @return 	mixed 	FALSE
	 */
	public function cache_info($type = NULL, $path = null)
	{
		return get_dir_file_info($path ? $path : $this->_cache_path);
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Cache Metadata
	 *
	 * @param 	mixed		key to get cache metadata on
	 * @return 	mixed		FALSE on failure, array on success.
	 */
	public function get_metadata($id, $path = null)
	{
		if ( ! file_exists(($path ? $path : $this->_cache_path).$id))
		{
			return FALSE;
		}

		$data = read_file(($path ? $path : $this->_cache_path).$id);
		$data = unserialize($data);

		if (is_array($data))
		{
			$mtime = filemtime(($path ? $path : $this->_cache_path).$id);

			if ( ! isset($data['ttl']))
			{
				return FALSE;
			}

			return array(
				'expire'	=> $mtime + $data['ttl'],
				'mtime'		=> $mtime
			);
		}

		return FALSE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Is supported
	 *
	 * In the file driver, check to see that the cache directory is indeed writable
	 *
	 * @return boolean
	 */
	public function is_supported($path = null)
	{
		return is_really_writable($path ? $path : $this->_cache_path);
	}

	// ------------------------------------------------------------------------
}
// End Class

/* End of file Cache_file.php */
/* Location: ./system/libraries/Cache/drivers/Cache_file.php */