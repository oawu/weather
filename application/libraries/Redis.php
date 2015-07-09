<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 *
 * @resource    https://github.com/joelcox/codeigniter-redis
 */

class CI_Redis {
  private $CI = null;
  private $connection = null;
  private $error = null;
  const CRLF = "\r\n";

  public function __construct ($configs = array ()) {
    $this->CI =& get_instance ();
    $this->CI->load->library ("cfg");

    $system = isset ($configs['no_cfg_cache']) && $configs['no_cfg_cache'] ? '_system' : 'system';
      if (!(isset ($configs['active_server']) && ($server = Cfg::$system ('redis', 'servers')[$configs['active_server']])))
        $server = Cfg::$system ('redis', 'servers')[Cfg::$system ('redis', 'active_server')];

    $this->connection = @fsockopen ($server['host'], $server['port'], $errno, $errstr, 3);
    if (!$this->connection)
      $this->error = '無法連接至 Redis<br/>Host: ' . $server['host'] . '<br/>Port: ' . $server['port'];

    $this->_auth ($server['password']);
  }

  public function getStatus (&$error) {
    $error = $this->error;
    return $this->connection ? true : false;
  }

  public function command ($command) {
    if ($this->error)
      return null;

    if (is_array ($command))
      $command = implode (' ', $command);

    $command = preg_split ("/\s+/", $command);
    $request = $this->_encode (array_shift ($command), $command);
    return $this->_write ($request);
  }

  public function __call ($method, $args) {
    if ($this->error)
      return null;
    $request = $this->_encode ($method, $args);
    return $this->_write ($request);
  }

  private function _auth ($password) {
    if ($password)
      if ($this->command ('AUTH ' . $password) !== 'OK')
        $this->error = '無法連接至 Redis, 密碼錯誤!';
  }

  private function _encode ($method, $args = array ()) {
    $request = array ();
    array_push ($request, '$' . strlen ($method) . self::CRLF . $method . self::CRLF);

    foreach ($args as $argument)
      if (is_array ($argument))
        foreach ($argument as $key => $value)
          array_push ($request, !is_int ($key) ? ('$' . strlen ($key) . self::CRLF . $key . self::CRLF) : ('$' . strlen ($value) . self::CRLF . $value . self::CRLF));
      else
        array_push ($request, '$' . strlen ($argument) . self::CRLF . $argument . self::CRLF);
    $request = '*' . count ($request) . self::CRLF . implode ('', $request);
    return $request;
  }

  private function _write ($request) {
    $max = 8192;
    $length = strlen ($request);

    if ($length <= 0)
      return null;

    if ($length <= $max)
      fwrite ($this->connection, $request);
    else
      for ($i = 0; $i < $length; $i += $max)
        fwrite ($this->connection, substr ($request, $i, $max));

    $return = $this->_read ();
    $this->_clear_socket ();

    return $return;
  }

  private function _read () {
    $type_error_limit = 50;
    $types = array ('+', '-', ':', '$', '*');
    $type = fgetc ($this->connection);

    for ($i = 0; !in_array ($type, $types) && ($i < 50); $i++)
      $type = fgetc ($this->connection);

    switch ($type) {
      case '+':
        return $this->_single_line_reply (); break;

      case '-':
        return $this->_error_reply (); break;

      case ':':
        return $this->_integer_reply (); break;

      case '$':
        return $this->_bulk_reply (); break;

      case '*':
        return $this->_multi_bulk_reply (); break;

      default:
        return null;
    }
  }

  private function _single_line_reply () {
    $value = rtrim (fgets ($this->connection));
    $this->_clear_socket ();
    return $value;
  }

  private function _error_reply () {
    $error = substr (rtrim (fgets ($this->connection)), 4);
    show_error ("Redis 執行錯誤!<br/>錯誤碼: " . $error);
    $this->_clear_socket ();
    return null;
  }

  private function _integer_reply () {
    return (int)rtrim (fgets ($this->connection));
  }

  private function _bulk_reply () {
    $max = 8192;
    $length = (int)fgets ($this->connection);

    if ($length <= 0)
      return null;

    $response = '';

    if ($length <= $max)
      $response = fread ($this->connection, $length);
    else
      for ($i = 0; $i < $length; $i += $max)
        $response .= fread ($this->connection, ($length - $i) > $max ? $max : $length - $i);

    $this->_clear_socket ();
    return isset ($response) ? $response : null;
  }

  private function _multi_bulk_reply () {
    $response = array ();
    $total = (int)fgets ($this->connection);

    for ($i = 0; $i < $total; $i++) {
      fgets ($this->connection, 2);

      if ($i > 0) {
        fgets ($this->connection);
        fgets ($this->connection, 2);
      }

      array_push ($response, $this->_bulk_reply ());
    }

    $this->_clear_socket ();
    return isset ($response) ? $response : null;
  }

  public function _clear_socket () {
    fflush ($this->connection);
    return null;
  }

  public function info ($section = null) {
    if ($section !== null)
      $response = $this->command ('INFO '. $section);
    else
      $response = $this->command ('INFO');

    $data = array ();
    foreach (explode (self::CRLF, $response) as $line)
      if (($parts = explode (':', $line)) && isset ($parts[1]))
        $data[$parts[0]] = $parts[1];
    return $data;
  }

  public function __destruct () {
    if ($this->connection)
      fclose ($this->connection);
  }

  // ----------------------------------------------------------------------------

  public function hGetArray ($name) {
    $values = $this->command ('HGETALL ' . trim ($name));
    $result = array ();
    while ($values && list ($key, $value) = array_splice ($values, 0, 2))
      $result[$key] = $value;
    return $result;
  }
}