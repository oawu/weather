<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_town_views extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `town_views` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `town_id` int(11) NOT NULL,

        `latitude` DOUBLE NOT NULL COMMENT '緯度',
        `longitude` DOUBLE NOT NULL COMMENT '經度',
        `heading` int(5) NOT NULL COMMENT '水平角度',
        `pitch` int(5) NOT NULL COMMENT '垂直角度',
        `zoom` int(5) NOT NULL COMMENT '放大度',
        `pic` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '靜態圖檔',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `town_id_index` (`town_id`),
        UNIQUE KEY `town_id_unique` (`town_id`),
        FOREIGN KEY (`town_id`) REFERENCES `towns` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `town_views`;"
    );
  }
}