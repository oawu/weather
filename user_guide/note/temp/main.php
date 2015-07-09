<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $this->load_view (null);
    // $this->load_view (null, false, 10);
  }

  public function a () {
    // $this->output->delete_cache ('main');
    // $this->output->delete_all_cache ();
  }

  public function b () {
    // return $this->output_json (array ('status' => true), 100);
  }

  public function delay () {
    delay_job ('main', 'index', array ('sec' => 5));
  }
}
