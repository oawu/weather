{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class <?php echo ucfirst ($name);?> extends <?php echo ucfirst ($action);?>_controller {

  public function __construct () {
    parent::__construct ();
  }
<?php
  if ($methods) {
    foreach ($methods as $method) { ?>

  public function <?php echo $method;?> () {
    $this->load_view (null);
  }
<?php
    }
  } ?>
}
