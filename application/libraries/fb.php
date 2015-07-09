<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
require_once 'facebook_api.php';

class Fb extends Facebook_api {
  private $CI = null;

  public function __construct ($config = array ()) {
    $this->CI =& get_instance ();
    $this->CI->load->helper ('oa');
    $this->CI->load->library ("cfg");
    
    parent::__construct (array_merge (array ('appId'  => Cfg::system ('facebook', 'appId'),
                                             'secret' => Cfg::system ('facebook', 'secret')), $config));
  }

  public function fql ($query) {
    return $this->getUser () ? $this->api (array ('method' => 'fql.query', 'query' => $query)) : null;
  }

  public function getLoginUrl ($config = array ()) {
    return parent::getLoginUrl (array_merge (array ('scope'  => Cfg::system ('facebook', 'scope')), $config));
  }

  public function login_url () {
    return $this->getLoginUrl (array ('redirect_uri' => base_url (func_get_args ())));
  }
}