<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Main extends Admin_controller {
  private $admon = array ();

  public function __construct () {
    parent::__construct ();

    $this->admon = array (
        'account' => 'oa',
        'password' => 'fc8140c2c1fabfc45bf467d0440cc34c'
      );
  }

  public function index () {
    if (identity ()->get_session ('is_login')) {
      return redirect (array ('admin', 'towns'));
    } else {
      return redirect (array ('admin', $this->get_class (), 'login'));
    }
  }

  public function login () {
    if (identity ()->get_session ('is_login'))
      return redirect (array ('admin', $this->get_class (), 'logout'));

    $message = identity ()->get_session ('_flash_message', true);
    $account = identity ()->get_session ('account', true);

    $this->load_view (array (
        'message' => $message,
        'account' => $account
      ));
  }

  public function logout () {
    identity ()->set_session ('is_login', false);

    return redirect ('admin');
  }
  public function signin () {
    if (!$this->has_post ())
      return redirect (array ('admin', $this->get_class (), 'login'));

    if (identity ()->user ())
      return redirect ('admin');

    $account  = trim ($this->input_post ('account'));
    $password = trim ($this->input_post ('password'));

    if (!(($account == $this->admon['account']) && (md5 ($password) == $this->admon['password'])))
      return identity ()->set_session ('_flash_message', '登入失敗，請再確認一次帳號與密碼！', true)
                        ->set_session ('account', $account, true)
                        && redirect (array ('admin', $this->get_class (), 'login'), 'refresh');

    identity ()->set_session ('is_login', true);
    return redirect ('admin');
  }
}