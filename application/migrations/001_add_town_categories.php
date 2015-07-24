<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_town_categories extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `town_categories` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(10) COLLATE utf8_unicode_ci NOT NULL COMMENT '名稱',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        PRIMARY KEY (`id`),
        KEY `name_index` (`name`),
        UNIQUE KEY `name_unique` (`name`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
    $this->db->query (
      "INSERT INTO `town_categories` (`id`, `name`, `updated_at`, `created_at`)
        VALUES
          (1, '台北市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (2, '基隆市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (3, '新北市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (4, '連江縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (5, '宜蘭縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (6, '新竹市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (7, '新竹縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (8, '桃園市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (9, '苗栗縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (10, '台中市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (11, '彰化縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (12, '南投縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (13, '嘉義市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (14, '嘉義縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (15, '雲林縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (16, '台南市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (17, '高雄市', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (18, '澎湖縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (19, '金門縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (20, '屏東縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (21, '台東縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "'),
          (22, '花蓮縣', '" . date ('Y-m-d H:i:s') . "', '" . date ('Y-m-d H:i:s') . "');"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `town_categories`;"
    );
  }
}