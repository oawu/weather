{<{<{ defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_<?php echo ucfirst ($action);?>_<?php echo pluralize ($name);?> extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `attendees` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `event_id` int(11) NOT NULL,
        `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "',
        PRIMARY KEY (`id`),
        KEY `event_id_index` (`event_id`),
        FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `attendees`;"
    );
  }
}