<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Main extends Delay_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $sec = $this->input_post ('sec');
    sleep ($sec);
  }
}
