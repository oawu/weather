<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_proposes extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `proposes` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '標題',
        
        `ip` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT 'IP',
        `latitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '緯度',
        `longitude` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '經度',
        `id_enabled` int(11) NOT NULL,

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`),
        KEY `latitude_longitude_index` (`latitude`, `longitude`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `proposes`;"
    );
  }
}