{<{<{ if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2015 OA Wu Design
 */

class <?php echo ucfirst ($name);?> extends <?php echo ucfirst ($action);?>_controller {

  public function __construct () {
    parent::__construct ();
  }

  public function index () {
    $message = identity ()->get_session ('_flash_message', true);
    $tags = Tag::all ();
    $this->load_view (array ('tags' => $tags, 'message' => $message));
  }

  public function show ($id) {
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    $this->load_view (array ('tag' => $tag));
  }

  public function add () {
    $message = identity ()->get_session ('_flash_message', true);
    $this->load_view (array ('message' => $message));
  }

  public function create () {
    $name = trim ($this->input_post ('name'));

    if (!$name) {
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    if (verifyCreateOrm ($tag = Tag::create (array ('name' => $name)))) {
      identity ()->set_session ('_flash_message', '新增成功!', true);
      return redirect (array ($this->get_class (), 'index'), 'refresh');
    } else {
      identity ()->set_session ('_flash_message', '新增失敗!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }
  }

  public function edit ($id) {
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    $message = identity ()->get_session ('_flash_message', true);
    $this->load_view (array ('message' => $message, 'tag' => $tag));
  }

  public function update ($id) {
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    $name = trim ($this->input_post ('name'));

    if (!$name) {
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    $tag->name = $name;

    if ($tag->save ()) {
      identity ()->set_session ('_flash_message', '修改成功!', true);
      return redirect (array ($this->get_class (), 'index'), 'refresh');
    } else {
      identity ()->set_session ('_flash_message', '修改失敗!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }
  }

  public function destroy ($id) {
    if (!$tag = Tag::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    if ($tag->delete ())
      identity ()->set_session ('_flash_message', '刪除成功!', true);
    else
      identity ()->set_session ('_flash_message', '刪除失敗!', true);

    return redirect (array ($this->get_class (), 'index'), 'refresh');
  }
}
