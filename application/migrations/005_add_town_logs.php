<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Migration_Add_town_logs extends CI_Migration {
  public function up () {
    $this->db->query (
      "CREATE TABLE `town_logs` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '錯誤類型',
        `message` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '錯誤訊息',
        `updated_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '新增時間',
        `created_at` datetime NOT NULL DEFAULT '" . date ('Y-m-d H:i:s') . "' COMMENT '更新時間',
        PRIMARY KEY (`id`)
      ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;"
    );
  }
  public function down () {
    $this->db->query (
      "DROP TABLE `town_logs`;"
    );
  }
}