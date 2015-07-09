<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Identity {
  private $CI = null;
  private $session = null;
  private $user = null;
  private $admin_user_ids = array ('1');

  public function __construct () {
    $this->CI =& get_instance ();
    $this->CI->load->library ('session');
    $this->session = $this->CI->session;
    $this->_init ();
  }

  private function _init () {
    $user_id  = $this->session->userdata ('user_id');
    if (!$user_id)
      $this->set_identity ('sign_out');
    return $this;
  }

  public function user () {
    return (!$this->user && $this->get_identity ('sign_in') && ($user_id = $this->get_session ('user_id'))) ? $this->user = User::find ('one', array ('conditions' => array ('id = ?', $user_id))) : $this->user;
  }
  public function get_identity ($identity) {

    $user_id  = $this->session->userdata ('user_id');

    $return = false;

    switch ($identity) {
      default: $return = false; break;
      case 'sign_in': $return = $user_id && true; break;
      case 'admins': $return = $user_id && $this->admin_user_ids && in_array ($user_id, $this->admin_user_ids); break;
    }
    return $return;
  }

  public function set_identity ($identity, $values = null) {
    switch (strtolower ($identity)) {
      default:
      case 'sign_out':
        $this->set_session ('user_id', 0);
        break;
    }
    return $this;
  }

  public function set_session ($key, $value, $is_flashdata = false) {
    if (!$is_flashdata)
      $this->session->set_userdata ($key, $value);
    else
      $this->session->set_flashdata ($key, $value);
    return $this;
  }
  public function get_session ($key, $is_flashdata = false) {
    $value = !$is_flashdata ? $this->session->userdata ($key) : $this->session->flashdata ($key);
    return $value ? $value : null;
  }
}