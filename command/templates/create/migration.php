{<{<{ defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_<?php echo ucfirst ($action);?>_<?php echo pluralize ($name);?> extends CI_Migration {
  public function up () {
    $this->db->query (
      ""
    );
  }
  public function down () {
    $this->db->query (
      ""
    );
  }
}