<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter Minify
 *
 * A minification driver system for CodeIgniter
 *
 * NOTICE OF LICENSE
 *
 * Licensed under the Open Software License version 3.0
 *
 * This source file is subject to the Open Software License (OSL 3.0) that is
 * bundled with this package in the files license.txt / license.rst.  It is
 * also available through the world wide web at this URL:
 * http://opensource.org/licenses/OSL-3.0
 *
 * @package     ci-minify
 * @author      Eric Barnes
 * @copyright   Copyright (c) Eric Barnes. (http://ericlbarnes.com/)
 * @license     http://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 * @link        http://ericlbarnes.com
 * @since       Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Minify Driver
 *
 * @subpackage	Drivers
 */
class Minify extends CI_Driver_Library {

	/**
	 * CI Object
	 *
	 * @var object
	 */
	protected $_ci = '';

	/**
	 * valid drivers
	 *
	 * @var array
	 */
	public $valid_drivers = array('minify_css', 'minify_js');

	// ------------------------------------------------------------------------

	/**
	 * Construct
	 *
	 * Initialize params
	 *
	 * @return \Minify
	 */
	public function __construct()
	{
		$this->_ci =& get_instance();
		log_message('debug', 'CI-Minify: Library initialized.');
	}

	// ------------------------------------------------------------------------

	/**
	 * Combine Files
	 *
	 * Pass an array of files and combine them.
	 * @param array $files
	 * @param string $type
	 * @param bool $compact
	 * @param string $css_charset
	 * @return mixed
	 */
	public function combine_files($files = array(), $type = '', $compact = TRUE, $css_charset = 'utf-8')
	{
		if ( ! is_array($files) OR count($files) < 1)
		{
			log_message('error', 'Minify->combine_files missing files array');
			return FALSE;
		}

		return $this->_do_combine($files, $type, $compact, $css_charset);
	}

	// ------------------------------------------------------------------------

	/**
	 * Combine Directory
	 *
	 * Pass a directory and combine all the files into one string.
	 *
	 * @param string $directory
	 * @param array $ignore
	 * @param string $type
	 * @param bool $compact
	 * @param string $css_charset
	 * @return string
	 */
	public function combine_directory($directory = '', $ignore = array(), $type = '', $compact = TRUE, $css_charset = 'utf-8')
	{
		$available = array();

		if ($directory == '' OR ! is_dir($directory))
		{
			log_message('error', 'Minify->combine_directory missing files array');
			return FALSE;
		}

		$this->_ci->load->helper('directory');
		foreach (directory_map($directory, TRUE) as $dir => $file)
		{
			if ($this->_get_type($file) == 'js' OR $this->_get_type($file) == 'css')
			{
				$available[$file] = $directory.'/'.$file;
			}
		}

		// Finally get ignored files
		if (count($ignore) > 0)
		{
			foreach ($available AS $key => $file)
			{
				if (in_array($key, $ignore))
				{
					unset($available[$key]);
				}
			}
		}

		return $this->_do_combine($available, $type, $compact, $css_charset);
	}

	// ------------------------------------------------------------------------

	/**
	 * Do combine
	 *
	 * Combine all the files and return a string.
	 *
	 * @param array $files
	 * @param string $type
	 * @param bool $compact
	 * @param string $css_charset
	 * @return string
	 */
	private function _do_combine($files, $type, $compact = TRUE, $css_charset = 'utf-8')
	{
		$contents = '';
		$file_count = 0;

		foreach ($files AS $file)
		{
			if ( ! file_exists($file))
			{
				log_message('error', 'Minify->_do_combine missing file '.$file);
				continue;
			}

			$file_count++;

			if ($type == '')
			{
				$type = $this->_get_type($file);
			}

			$path_info = pathinfo($file, PATHINFO_BASENAME); // Referal File and path

			if ($type == 'css')
			{
				// only one charset placed at the beginning of the document is allowed
				// in order to keep standars compliance and fixing Webkit problems
				// Note: Minify_css driver yet remove all charsets previously
				if ($file_count == 1)
				{
					$contents .= '@charset "'.$css_charset.'";'."\n";
				}
				$contents .= "\n".'/* @fileRef '.$path_info.' */'."\n";
				$contents .= $this->css->min($file, $compact, $is_aggregated = TRUE);
			}
			elseif ($type == 'js')
			{
				unset($css_charset);
				$contents .= "\n".'// @fileRef '.$path_info.' '."\n";
				$contents .= $this->js->min($file, $compact);
			}
			else
			{
				$contents .= $file."\n\n";
			}
		}

		return $contents;
	}

	// ------------------------------------------------------------------------

	/**
	 * Save File
	 *
	 * Save a file
	 *
	 * @param string $contents
	 * @param string $full_path
	 * @return bool
	 */
	public function save_file($contents = '', $full_path = '')
	{
		$this->_ci->load->helper('file');

		if ( ! write_file($full_path, $contents))
		{
			log_message('error', 'Minify->save_file could not write file');
			return FALSE;
		}
		return TRUE;
	}

	// ------------------------------------------------------------------------

	/**
	 * Get Type
	 *
	 * Get the file extension to determine file type
	 *
	 * @param string $file
	 * @return string
	 */
	private function _get_type($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION);
	}
}

/* End of file Minify.php */
/* Location: ./application/libraries/Minify/Minify.php */