<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class ActiveRecordModel extends ActiveRecord\Model {
  protected $CI = null;

  public function __construct ($attributes = array (), $guard_attributes = TRUE, $instantiating_via_find = FALSE, $new_record = TRUE) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);

    $this->CI =& get_instance ();

    $this->CI->load->helper ('oa');
    $this->CI->load->library ('uploader/OrmUploader');
    $this->CI->load->library ('JsonBind');
    $this->CI->load->library ('Cfg');
  }
}
