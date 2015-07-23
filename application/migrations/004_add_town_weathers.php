<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_town_weathers extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `town_weathers` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `town_id` int(11) NOT NULL,

        `icon` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '天氣圖示',
        `describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '描述',
        `temperature` int(11) NOT NULL COMMENT '溫度(單位°c)',
        `humidity` int(11) NOT NULL COMMENT '濕度(單位%)',
        `rainfall` float NOT NULL COMMENT '降雨量(單位mm)',
        `sunrise` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '日出時間',
        `sunset` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '日落時間',

        `special_icon` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '特報圖示',
        `special_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '特報狀態',
        `special_describe` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '特報描述',
        `special_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '特報時間',

        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`),
        KEY `town_id_index` (`town_id`),
        FOREIGN KEY (`town_id`) REFERENCES `towns` (`id`) ON DELETE CASCADE
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `town_weathers`;"
    );
  }
}