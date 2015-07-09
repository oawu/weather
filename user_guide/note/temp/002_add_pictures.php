<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class Migration_Add_pictures extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `pictures` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `user_id` int(11) NOT NULL,
          `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "',
          `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "',
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );

    $this->db->query (
      "CREATE TABLE `delete_pictures` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `origin_id` int(11) NOT NULL,
          `user_id` int(11) NOT NULL,
          `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
          `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "',
          `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "',
          PRIMARY KEY (`id`),
          FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `pictures`;"
    );
    $this->db->query (
      "DROP TABLE `delete_pictures`;"
    );
  }
}