<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Output extends CI_Output {

 /**
  * Deletes an output cache file from a given uri
  *
  * @access public
  * @param string
  * @return bool
  */
 public function delete_cache($uri = '', $cache_append_path = null)
 {
  if (($CI =& get_instance ()) && !isset ($CI->cfg))
    $CI->load->library ('cfg');

  $path = implode (DIRECTORY_SEPARATOR, Cfg::system ('cache', 'output')) . DIRECTORY_SEPARATOR;

  $cache_path = (($path == '') ? APPPATH.'cache/' : $path) . ($cache_append_path == null ? '' : $cache_append_path);

  $uri = $CI->config->item('base_url').
    $CI->config->item('index_page').
    $uri;

  $uri = preg_replace ('/^http(s)?:\/\//', '', $uri);
  $cache_path .= preg_replace ('/\/|:|\./i', '_|_', $uri);

  log_message("debug", sprintf("%s %s: clear cache from %s", __CLASS__, __FUNCTION__, $cache_path));
  if (is_file($cache_path)) {
    $r = unlink($cache_path);
    log_message("debug", sprintf("%s %s: remove %s.", __CLASS__, __FUNCTION__, ($r)?"ok":"failed"));
    return $r;
  }
  log_message("debug", sprintf("%s %s: no file from %s", __CLASS__, __FUNCTION__, $cache_path));
  return false;
 }
 /**
  * Deletes all output cache file from a given uri
  *
  * @access public
  * @param string
  * @return bool
  */
 public function delete_all_cache($cache_append_path = null)
 {

  if (($CI =& get_instance ()) && !isset ($CI->cfg))
    $CI->load->library ('cfg');

  $path = implode (DIRECTORY_SEPARATOR, Cfg::system ('cache', 'output')) . DIRECTORY_SEPARATOR;

  $cache_path = (($path == '') ? APPPATH.'cache/' : $path);

  $CI->load->helper ('directory');
  return directory_clean (FCPATH . $cache_path);
 }
}
// END MY Output Class

/* End of file MY_Output.php */
/* Location: ./application/core/MY_Output.php */