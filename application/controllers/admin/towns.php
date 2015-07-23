<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class Towns extends Admin_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function destroy ($id = 0) {
    if (!($town = Town::find_by_id ($id)))
      return redirect (array ('admin', 'towns'));

    $message = $town->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'towns'), 'refresh');
  }

  public function edit ($id = 0) {
    if (!($town = Town::find_by_id ($id)))
      return redirect (array ('admin', 'towns'));

    $message  = identity ()->get_session ('_flash_message', true);

    $town_category_id = identity ()->get_session ('town_category_id', true);
    $name = trim (identity ()->get_session ('name', true));
    $postal_code = trim (identity ()->get_session ('postal_code', true));
    $latitude = trim (identity ()->get_session ('latitude', true));
    $longitude = trim (identity ()->get_session ('longitude', true));

    $this->add_hidden (array ('id' => 'marker', 'data-lat' => $town->latitude, 'data-lng' => $town->longitude, 'value' => $town->id))
         ->load_view (array (
        'town' => $town,
        'message' => $message,
        'town_category_id' => $town_category_id,
        'name' => $name,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }

  public function update ($id = 0) {
    if (!($town = Town::find_by_id ($id)))
      return redirect (array ('admin', 'towns'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'towns', 'edit', $town->id));

    $town_category_id = trim ($this->input_post ('town_category_id'));
    $name = trim ($this->input_post ('name'));
    $postal_code = trim ($this->input_post ('postal_code'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($town_category_id && $name && $postal_code && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'edit', $town->id), 'refresh');


    if (!($town_category = TownCategory::find_by_id ($town_category_id)))
      return identity ()->set_session ('_flash_message', '未填寫縣市分類！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    // if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('id != ? AND postal_code = ?', $town->id, $postal_code))))
    //   return identity ()->set_session ('_flash_message', '郵遞區號重複！', true)
    //                     ->set_session ('town_category_id', $town_category_id, true)
    //                     ->set_session ('name', $name, true)
    //                     ->set_session ('postal_code', $postal_code, true)
    //                     ->set_session ('latitude', $latitude, true)
    //                     ->set_session ('longitude', $longitude, true)
    //                     && redirect (array ('admin', 'towns', 'edit', $town->id), 'refresh');


    if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('id != ? AND town_category_id = ? AND name = ?', $town->id, $town_category->id, $name))))
      return identity ()->set_session ('_flash_message', '' . $town_category->name . ' 分類下名稱重複！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    if (($town->latitude == $latitude) && ($town->longitude == $longitude))
      $is_update_pic = false;
    else
      $is_update_pic = true;

    $town->town_category_id = $town_category->id;
    $town->name = $name;
    $town->postal_code = $postal_code;
    $town->latitude = $latitude;
    $town->longitude = $longitude;

    if (!$town->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'edit', $town->id), 'refresh');

    if ($is_update_pic)
      $town->put_pic ();

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'towns'), 'refresh');
  }

  public function add () {
    $message  = identity ()->get_session ('_flash_message', true);
    
    $town_category_id = identity ()->get_session ('town_category_id', true);
    $name = trim (identity ()->get_session ('name', true));
    $postal_code = trim (identity ()->get_session ('postal_code', true));
    $latitude = trim (identity ()->get_session ('latitude', true));
    $longitude = trim (identity ()->get_session ('longitude', true));

    $this->load_view (array (
        'message' => $message,
        'town_category_id' => $town_category_id,
        'name' => $name,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }

  public function create () {
    if (!$this->has_post ())
      return redirect (array ('admin', 'towns', 'add'));

    $town_category_id = trim ($this->input_post ('town_category_id'));
    $name = trim ($this->input_post ('name'));
    $postal_code = trim ($this->input_post ('postal_code'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($town_category_id && $name && $postal_code && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    if (!($town_category = TownCategory::find_by_id ($town_category_id)))
      return identity ()->set_session ('_flash_message', '未填寫縣市分類！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    // if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('postal_code = ?', $postal_code))))
    //   return identity ()->set_session ('_flash_message', '郵遞區號重複！', true)
    //                     ->set_session ('town_category_id', $town_category_id, true)
    //                     ->set_session ('name', $name, true)
    //                     ->set_session ('postal_code', $postal_code, true)
    //                     ->set_session ('latitude', $latitude, true)
    //                     ->set_session ('longitude', $longitude, true)
    //                     && redirect (array ('admin', 'towns', 'add'), 'refresh');

    if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('town_category_id = ? AND name = ?', $town_category->id, $name))))
      return identity ()->set_session ('_flash_message', '' . $town_category->name . ' 分類下名稱重複！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    $params = array (
        'town_category_id' => $town_category->id,
        'name' => $name,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'pic' => '',
        'cwb_town_id' => ''
      );

    if (!verifyCreateOrm ($town = Town::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    if (!$town->put_pic () && ($town->destroy () || true))
      return identity ()->set_session ('_flash_message', '新增失敗(取得 Static 失敗)！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'add'), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'towns'), 'refresh');
  }

  public function refresh_weather () {
    if (!$this->is_ajax (false))
      return show_error ("It's not Ajax request!<br/>Please confirm your program again.");

    $id = trim ($this->input_post ('id'));

    if (!($id && ($town = Town::find_by_id ($id, array ('select' => 'id, cwb_town_id')))))
      return $this->output_json (array ('status' => false));

    clean_cell ('town_cell', 'update_weather', $town->id);

    if ($town->update_weather ())
      return $this->output_json (array ('status' => true));
  }
  public function index ($offset = 0) {
    $columns = array ('id' => 'int', 'name' => 'string');
    $configs = array ('admin', 'towns', '%s');

    $conditions = conditions (
                    $columns,
                    $configs,
                    'Town',
                    $this->input_gets ()
                  );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = Town::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();

    $towns = Town::find ('all', array ('include' => array ('category', 'weathers'), 'offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->add_hidden (array ('id' => 'refresh_weather_url', 'value' => base_url ('admin', 'towns', 'refresh_weather')))
         ->load_view (array (
        'message' => $message,
        'pagination' => $pagination,
        'towns' => $towns,
        'columns' => $columns
      ));
  }

  public function cate_destroy ($id = 0) {
    if (!($town_category = TownCategory::find_by_id ($id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    $message = $town_category->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'towns', 'cate_index'), 'refresh');
  }
  public function cate_edit ($id = 0) {
    if (!($town_category = TownCategory::find_by_id ($id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    $message  = identity ()->get_session ('_flash_message', true);

    $name = trim (identity ()->get_session ('name', true));

    $this->load_view (array (
        'town_category' => $town_category,
        'message' => $message,
        'name' => $name
      ));
  }

  public function cate_update ($id = 0) {
    if (!($town_category = TownCategory::find_by_id ($id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'towns', 'cate_edit', $town_category->id));

    $name = trim ($this->input_post ('name'));

    if (!$name)
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'towns', 'cate_edit', $town_category->id), 'refresh');

    if (TownCategory::find ('one', array ('conditions' => array ('id != ? AND name = ?', $town_category->id, $name))))
      return identity ()->set_session ('_flash_message', '名稱重複！', true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'towns', 'cate_add'), 'refresh');

    $town_category->name = $name;

    if (!$town_category->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'towns', 'cate_edit', $town_category->id), 'refresh');

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'towns', 'cate_index'), 'refresh');
  }

  public function cate_destroy_town ($id = 0) {
    if (!($town = Town::find_by_id ($id)))
      return redirect (array ('admin', 'towns'));

    $message = $town->destroy () ? '刪除成功！' : '刪除失敗！';

    return identity ()->set_session ('_flash_message', $message, true)
                    && redirect (array ('admin', 'towns', 'cate_index'), 'refresh');
  }
  public function cate_edit_town ($id = 0) {
    if (!($town = Town::find_by_id ($id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    $message  = identity ()->get_session ('_flash_message', true);

    $name = trim (identity ()->get_session ('name', true));
    $postal_code = trim (identity ()->get_session ('postal_code', true));
    $latitude = trim (identity ()->get_session ('latitude', true));
    $longitude = trim (identity ()->get_session ('longitude', true));

    $this->add_hidden (array ('id' => 'marker', 'data-lat' => $town->latitude, 'data-lng' => $town->longitude, 'value' => $town->id))
         ->load_view (array (
        'town' => $town,
        'message' => $message,
        'name' => $name,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }
  public function cate_update_town ($id = 0) {
    if (!($town = Town::find_by_id ($id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'towns', 'cate_edit_town', $town->id));

    $name = trim ($this->input_post ('name'));
    $postal_code = trim ($this->input_post ('postal_code'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($name && $postal_code && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_edit_town', $town->id), 'refresh');

    // if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('id != ? AND postal_code = ?', $town->id, $postal_code))))
    //   return identity ()->set_session ('_flash_message', '郵遞區號重複！', true)
    //                     ->set_session ('town_category_id', $town_category_id, true)
    //                     ->set_session ('name', $name, true)
    //                     ->set_session ('postal_code', $postal_code, true)
    //                     ->set_session ('latitude', $latitude, true)
    //                     ->set_session ('longitude', $longitude, true)
    //                     && redirect (array ('admin', 'towns', 'cate_edit_town', $town->id), 'refresh');

    if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('id != ? AND town_category_id = ? AND name = ?', $town->id, $town_category->id, $name))))
      return identity ()->set_session ('_flash_message', '' . $town_category->name . ' 分類下名稱重複！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_edit_town', $town->id), 'refresh');

    if (($town->latitude == $latitude) && ($town->longitude == $longitude))
      $is_update_pic = false;
    else
      $is_update_pic = true;

    $town->name = $name;
    $town->postal_code = $postal_code;
    $town->latitude = $latitude;
    $town->longitude = $longitude;

    if (!$town->save ())
      return identity ()->set_session ('_flash_message', '修改失敗！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_edit_town', $town->id), 'refresh');

    if ($is_update_pic)
      $town->put_pic ();

    return identity ()->set_session ('_flash_message', '修改成功！', true)
                      && redirect (array ('admin', 'towns', 'cate_index'), 'refresh');
  }

  public function cate_add_town ($cate_id = 0) {
    if (!($town_category = TownCategory::find_by_id ($cate_id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    $message  = identity ()->get_session ('_flash_message', true);
    
    $name = trim (identity ()->get_session ('name', true));
    $postal_code = trim (identity ()->get_session ('postal_code', true));
    $latitude = trim (identity ()->get_session ('latitude', true));
    $longitude = trim (identity ()->get_session ('longitude', true));

    $this->load_view (array (
        'message' => $message,
        'town_category' => $town_category,
        'name' => $name,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude
      ));
  }
  public function cate_create_town ($cate_id = 0) {
    if (!($town_category = TownCategory::find_by_id ($cate_id)))
      return redirect (array ('admin', 'towns', 'cate_index'));

    if (!$this->has_post ())
      return redirect (array ('admin', 'towns', 'add'));

    $town_category_id = trim ($this->input_post ('town_category_id'));
    $name = trim ($this->input_post ('name'));
    $postal_code = trim ($this->input_post ('postal_code'));
    $latitude = trim ($this->input_post ('latitude'));
    $longitude = trim ($this->input_post ('longitude'));

    if (!($town_category_id && $name && $postal_code && $latitude && $longitude))
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_add_town', $town_category_id), 'refresh');

    if (!($town_category = TownCategory::find_by_id ($town_category_id)))
      return identity ()->set_session ('_flash_message', '未填寫縣市分類！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_add_town', $town_category_id), 'refresh');

    // if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('postal_code = ?', $postal_code))))
    //   return identity ()->set_session ('_flash_message', '郵遞區號重複！', true)
    //                     ->set_session ('town_category_id', $town_category_id, true)
    //                     ->set_session ('name', $name, true)
    //                     ->set_session ('postal_code', $postal_code, true)
    //                     ->set_session ('latitude', $latitude, true)
    //                     ->set_session ('longitude', $longitude, true)
    //                     && redirect (array ('admin', 'towns', 'cate_add_town', $town_category_id), 'refresh');


    if (Town::find ('one', array ('select' => 'id', 'conditions' => array ('town_category_id = ? AND name = ?', $town_category->id, $name))))
      return identity ()->set_session ('_flash_message', '' . $town_category->name . ' 分類下名稱重複！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_add_town', $town_category_id), 'refresh');

    $params = array (
        'town_category_id' => $town_category->id,
        'name' => $name,
        'postal_code' => $postal_code,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'pic' => '',
        'cwb_town_id' => ''
      );

    if (!verifyCreateOrm ($town = Town::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_add_town', $town_category_id), 'refresh');

    if (!$town->put_pic () && ($town->destroy () || true))
      return identity ()->set_session ('_flash_message', '新增失敗(取得 Static 失敗)！', true)
                        ->set_session ('town_category_id', $town_category_id, true)
                        ->set_session ('name', $name, true)
                        ->set_session ('postal_code', $postal_code, true)
                        ->set_session ('latitude', $latitude, true)
                        ->set_session ('longitude', $longitude, true)
                        && redirect (array ('admin', 'towns', 'cate_add_town', $town_category_id), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'towns', 'cate_index'), 'refresh');
  }
  public function cate_add () {
    $message  = identity ()->get_session ('_flash_message', true);
    
    $name = trim (identity ()->get_session ('name', true));

    $this->load_view (array (
        'message' => $message,
        'name' => $name
      ));
  }

  public function cate_create () {
    if (!$this->has_post ())
      return redirect (array ('admin', 'towns', 'cate_add'));

    $name = trim ($this->input_post ('name'));

    if (!$name)
      return identity ()->set_session ('_flash_message', '填寫資訊有少！', true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'towns', 'cate_add'), 'refresh');

    if (TownCategory::find ('one', array ('conditions' => array ('name = ?', $name))))
      return identity ()->set_session ('_flash_message', '名稱重複！', true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'towns', 'cate_add'), 'refresh');

    $params = array (
        'name' => $name
      );

    if (!verifyCreateOrm ($town_category = TownCategory::create ($params)))
      return identity ()->set_session ('_flash_message', '新增失敗！', true)
                        ->set_session ('name', $name, true)
                        && redirect (array ('admin', 'towns', 'cate_add'), 'refresh');

    return identity ()->set_session ('_flash_message', '新增成功！', true)
                      && redirect (array ('admin', 'towns', 'cate_index'), 'refresh');
  }
  public function cate_index ($offset = 0) {
    $columns = array ('id' => 'int', 'name' => 'string');
    $configs = array ('admin', 'towns', 'cate_index', '%s');

    $conditions = conditions (
                    $columns,
                    $configs,
                    'TownCategory',
                    $this->input_gets ()
                  );

    $conditions = array (implode (' AND ', $conditions));

    $limit = 25;
    $total = TownCategory::count (array ('conditions' => $conditions));
    $offset = $offset < $total ? $offset : 0;

    $this->load->library ('pagination');
    $configs = array_merge (array ('total_rows' => $total, 'num_links' => 5, 'per_page' => $limit, 'uri_segment' => 0, 'base_url' => '', 'page_query_string' => false, 'first_link' => '第一頁', 'last_link' => '最後頁', 'prev_link' => '上一頁', 'next_link' => '下一頁', 'full_tag_open' => '<ul class="pagination">', 'full_tag_close' => '</ul>', 'first_tag_open' => '<li>', 'first_tag_close' => '</li>', 'prev_tag_open' => '<li>', 'prev_tag_close' => '</li>', 'num_tag_open' => '<li>', 'num_tag_close' => '</li>', 'cur_tag_open' => '<li class="active"><a href="#">', 'cur_tag_close' => '</a></li>', 'next_tag_open' => '<li>', 'next_tag_close' => '</li>', 'last_tag_open' => '<li>', 'last_tag_close' => '</li>'), $configs);
    $this->pagination->initialize ($configs);
    $pagination = $this->pagination->create_links ();

    $town_categories = TownCategory::find ('all', array ('include' => array ('towns'), 'offset' => $offset, 'limit' => $limit, 'order' => 'id DESC', 'conditions' => $conditions));

    $message = identity ()->get_session ('_flash_message', true);

    $this->load_view (array (
        'message' => $message,
        'pagination' => $pagination,
        'town_categories' => $town_categories,
        'columns' => $columns
      ));
  }
}
