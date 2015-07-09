<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */
class OaModel extends ActiveRecordModel {

  public function __construct ($attributes = array (), $guard_attributes = TRUE, $instantiating_via_find = FALSE, $new_record = TRUE) {
    parent::__construct ($attributes, $guard_attributes, $instantiating_via_find, $new_record);
  }

  public function recycle () {
    if (!(class_exists ($ori_model = get_called_class ()) && class_exists ($delete_model = 'Delete' . $ori_model)))
      showError ('The origin model or delete mode error!');

    if (!(count ($delete_columns = array_keys ($delete_model::table ()->columns)) && count ($ori_columns = array_keys ($ori_model::table ()->columns)) && (count ($ori_columns) == (count ($delete_columns) - 1))))
      showError ('The origin model or delete mode columns error!');

    $origin_id = Cfg::system ('model', 'recycle', 'origin_id');
    $ori_object = $ori_model::find ('one', array ('select' => implode (',', array_map (function ($ori_column) { return '`' . $ori_column . '`'; }, $ori_columns)), 'conditions' => array ('id = ?', $this->id)));

    if (($ori_object !== null) && is_object ($ori_object)) {
      $sql = array ();

      foreach ($ori_columns as $ori_column)
        $sql[$ori_column == 'id' ? $origin_id : $ori_column] = $ori_object->$ori_column;

      return count ($sql) && is_object ($delete_object = $delete_model::create ($sql)) && $delete_object->is_valid () && $ori_object->delete () ? $ori_object : false;
    } else { return false;}
    return false;
  }

  public static function recycle_all ($conditions = array ()) {
    if (!(class_exists ($ori_model = get_called_class ()) && class_exists ($delete_model = 'Delete' . $ori_model)))
      showError ('The origin model or delete mode error!');

    if (!(count ($delete_columns = array_keys ($delete_model::table ()->columns)) && count ($ori_columns = array_keys ($ori_model::table ()->columns)) && (count ($ori_columns) == (count ($delete_columns) - 1)) && (($i = array_search ('id', $delete_columns)) !== false)))
      showError ('The origin model or delete mode columns error!');

    $origin_id = Cfg::system ('model', 'recycle', 'origin_id');
    unset ($delete_columns[$i]);
    $count = $ori_model::count ($conditions);

    $conditions['select'] = implode (',', array_map (function ($ori_column) { return '`' . $ori_column . '`'; }, $ori_columns));
    $conditions['order']  = isset ($conditions['order']) ? $conditions['order'] : 'id DESC';
    $conditions['limit']  = Cfg::system ('model', 'recycle', 'limit');

    $ori_ids = array ();

    for ($offset = isset ($conditions['offset']) ? $conditions['offset'] : 0; $offset < $count; $offset += $conditions['limit']) {
      $conditions['offset']  = $offset;
      $ori_objects = $ori_model::find ('all', $conditions);

      if (count ($ori_objects)) {
        $sqls = array_filter (array_map (function ($ori_object) use ($ori_columns, $origin_id) {
          $sql = array ();

          foreach ($ori_columns as $ori_column)
            $sql[$ori_column == 'id' ? $origin_id : $ori_column] = '"' . $ori_object->$ori_column . '"';

          return count ($sql) ? $sql : null;
        }, $ori_objects));

        if (count ($sqls) && count ($ori_objects) && count ($ori_ids = array_merge ($ori_ids, array_map (function ($ori_object) { return $ori_object->id; }, $ori_objects))))
        $delete_model::query ("INSERT INTO `" . $delete_model::table ()->table . "`(" . implode (",", array_map (function ($delete_column) { return "`" . $delete_column . "`"; }, $delete_columns)) . ") VALUES" . implode (',', array_map (function ($sql) { return "(" . implode (",", $sql) . ")"; }, $sqls)));
      }
    }
    if ($count = count ($ori_ids))
      $ori_model::delete_all (array ('conditions' => array ('id IN (?)', $ori_ids)));
    return $count ? true : false;
  }

  public static function recover ($mode, $conditions = array ()) {
    if (!(class_exists ($ori_model = get_called_class ()) && class_exists ($delete_model = 'Delete' . $ori_model)))
      showError ('The origin model or delete mode error!');

    if (!(count ($delete_columns = array_keys ($delete_model::table ()->columns)) && count ($ori_columns = array_keys ($ori_model::table ()->columns)) && (count ($ori_columns) == (count ($delete_columns) - 1)) && (($i = array_search ('id', $delete_columns)) !== false)))
      showError ('The origin model or delete mode columns error!');

    if (!in_array ($mode, array ('one', 'all')))
      showError ('The mode type error!');

    $origin_id = Cfg::system ('model', 'recycle', 'origin_id');
    $conditions['select'] = implode (',', array_map (function ($delete_column) { return '`' . $delete_column . '`'; }, $delete_columns));
    $conditions['order']  = isset ($conditions['order']) ? $conditions['order'] : 'id DESC';

    switch ($mode) {
      case 'one':
        if (is_object ($delete_object = $delete_model::find ($mode, $conditions))) {
          $sql = array ();
          foreach ($delete_columns as $delete_column)
            if ($delete_column != 'id')
              $sql[$delete_column == $origin_id ? 'id' : $delete_column] = $delete_object->$delete_column;
          return count ($sql) && is_object ($ori_object = $ori_model::create ($sql)) && $ori_object->is_valid () && $delete_object->delete () ? $ori_object : false;
        }
        break;

      case 'all':
        unset ($delete_columns[$i]);
        $count = $delete_model::count ($conditions);
        $conditions['limit'] = Cfg::system ('model', 'recycle', 'limit');

        $delete_ids = array ();
        for ($offset = isset ($conditions['offset']) ? $conditions['offset'] : 0; $offset < $count; $offset += $conditions['limit']) {
          $conditions['offset']  = $offset;
          $delete_objects = $delete_model::find ('all', $conditions);

          if (count ($delete_objects)) {
            $sqls = array_filter (array_map (function ($delete_object) use ($delete_columns, $origin_id) { $sql = array (); foreach ($delete_columns as $delete_column) if ($delete_column != 'id') $sql[$delete_column == $origin_id ? 'id' : $delete_column] = '"' . $delete_object->$delete_column . '"'; return count ($sql) ? $sql : null; }, $delete_objects));

            if (count ($sqls) && count ($delete_objects) && count ($delete_ids = array_merge ($delete_ids, array_map (function ($delete_object) { return $delete_object->id; }, $delete_objects))))
              $ori_model::query ("INSERT INTO `" . $ori_model::table ()->table . "`(" . implode (",", array_map (function ($ori_column) { return "`" . $ori_column . "`"; }, $ori_columns)) . ") VALUES" . implode (',', array_map (function ($sql) { return "(" . implode (",", $sql) . ")"; }, $sqls)));
          }
        }
        if ($count = count ($delete_ids))
          $delete_model::delete_all (array ('conditions' => array ('id IN (?)', $delete_ids)));

        return $count ? true : false;
        break;

      default: return false; break;
    }
  }
}