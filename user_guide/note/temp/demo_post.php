<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Demo_post extends Site_controller {

  public function __construct () {
    parent::__construct ();
  }


  public function index () {
    $this->load_view (null);
  }
  public function post1_submit () {
    $account = $this->input_post ('account');
    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($account);
    echo "</pre><hr/><a href='" . base_url (array ($this->get_class ())) . "'>back</a>";
  }
  public function post2_submit () {
    $items = $this->input_post ('items');
    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($items);
    echo "</pre><hr/><a href='" . base_url (array ($this->get_class ())) . "'>back</a>";
  }
  public function post3_submit () {
    $pic = $this->input_post ('pic', true, true);
    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($pic);
    echo "</pre><hr/><a href='" . base_url (array ($this->get_class ())) . "'>back</a>";
  }
  public function post4_submit () {
    $pics = $this->input_post ('pics[]', true, true);
    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($pics);
    echo "</pre><hr/><a href='" . base_url (array ($this->get_class ())) . "'>back</a>";
  }
  public function post5_submit () {
    $pic = $this->input_post ('pic', true, true);

    $picture = Picture::create (array ('file_name' => ''));
    $picture->file_name->put ($pic);

    echo '<meta http-equiv="Content-type" content="text/html; charset=utf-8" /><pre>';
    var_dump ($picture->file_name->url ());
    echo "</pre><hr/><a href='" . base_url (array ($this->get_class ())) . "'>back</a>";
  }

}
