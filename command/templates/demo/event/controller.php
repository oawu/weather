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
    $events = Event::all (array ('include' => array ('attendees')));
    $message = identity ()->get_session ('_flash_message', true);
    $this->load_view (array ('events' => $events, 'message' => $message));
  }

  public function show ($id) {
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    $this->load_view (array ('event' => $event));
  }

  public function add () {
    $message = identity ()->get_session ('_flash_message', true);

    $this->add_js (base_url ('resource', 'javascript', 'underscore_v1.7.0', 'underscore-min.js'), false)
         ->load_view (array ('message' => $message));
  }

  public function create () {
    $title     = trim ($this->input_post ('title'));
    $info      = trim ($this->input_post ('info'));
    $tag_ids   = $this->input_post ('tag_ids');
    $attendees = $this->input_post ('attendees');
    $cover     = $this->input_post ('cover', true, true);

    if (!($title && $info && $cover)) {
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    if (verifyCreateOrm ($event = Event::create (array ('title' => $title, 'info' => $info, 'cover' => ''))) && $event->cover->put ($cover)) {
      if ($tag_ids)
        array_map (function ($tag) use ($event) {
          return verifyCreateOrm (TagEventMap::create (array ('tag_id' => $tag->id, 'event_id' => $event->id)));
        }, Tag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $tag_ids))));

      if ($attendees)
        array_map (function ($attendee) use ($event) {
          return verifyCreateOrm (Attendee::create (array ('event_id' => $event->id, 'name' => trim ($attendee))));
        }, array_unique ($attendees));

      identity ()->set_session ('_flash_message', '新增成功!', true);
      return redirect (array ($this->get_class (), 'index'), 'refresh');
    } else {
      identity ()->set_session ('_flash_message', '新增失敗!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }
  }

  public function edit ($id) {
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    $message = identity ()->get_session ('_flash_message', true);
    $this->add_js (base_url ('resource', 'javascript', 'underscore_v1.7.0', 'underscore-min.js'), false)
         ->load_view (array ('message' => $message, 'event' => $event));
  }

  public function update ($id) {
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    $title = trim ($this->input_post ('title'));
    $info  = trim ($this->input_post ('info'));
    $tag_ids = ($tag_ids = $this->input_post ('tag_ids')) ? $tag_ids : array ();
    $old_attendees = ($old_attendees = $this->input_post ('old_attendees')) ? $old_attendees : array ();
    $cover = $this->input_post ('cover', true, true);
    $attendees = $this->input_post ('attendees');

    if (!($title && $info)) {
      identity ()->set_session ('_flash_message', '輸入資訊有誤!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }

    $event->title = $title;
    $event->info = $info;

    $old_tag_ids = column_array ($event->tag_event_maps, 'tag_id');
    if ($delete_tag_ids = array_diff ($old_tag_ids, $tag_ids))
      TagEventMap::delete_all (array ('conditions' => array ('tag_id IN (?)', $delete_tag_ids)));

    if ($create_tag_ids = array_diff ($tag_ids, $old_tag_ids))
      array_map (function ($tag) use ($event) {
        return verifyCreateOrm (TagEventMap::create (array ('tag_id' => $tag->id, 'event_id' => $event->id)));
      }, Tag::find ('all', array ('select' => 'id', 'conditions' => array ('id IN (?)', $create_tag_ids))));

    if ($delete_attendee_ids = array_diff (column_array ($event->attendees, 'id'), column_array ($old_attendees, 'id')))
      Attendee::delete_all (array ('conditions' => array ('id IN (?)', $delete_attendee_ids)));

    if ($old_attendees)
      array_map (function ($old_attendee) {
        Attendee::table ()->update ($set = array ('name' => trim ($old_attendee['name'])), array ('id' => $old_attendee['id']));
      }, $old_attendees);

    if ($attendees)
      array_map (function ($attendee) use ($event) {
        return verifyCreateOrm (Attendee::create (array ('event_id' => $event->id, 'name' => trim ($attendee))));
      }, array_unique ($attendees));

    if ($event->save () && (!$cover || $event->cover->put ($cover))) {
      identity ()->set_session ('_flash_message', '修改成功!', true);
      return redirect (array ($this->get_class (), 'index'), 'refresh');
    } else {
      identity ()->set_session ('_flash_message', '修改失敗!', true);
      return redirect (array ($this->get_class (), 'add'), 'refresh');
    }
  }

  public function destroy ($id) {
    if (!$event = Event::find_by_id ($id))
      redirect (array ($this->get_class (), 'index'));

    if ($old_tag_ids = column_array ($event->tag_event_maps, 'tag_id'))
      TagEventMap::delete_all (array ('conditions' => array ('tag_id IN (?)', $old_tag_ids)));

    if ($old_attendee_ids = column_array ($event->attendees, 'id'))
      Attendee::delete_all (array ('conditions' => array ('id IN (?)', $old_attendee_ids)));

    if ($event->cover->cleanAllFiles () && $event->delete ())
      identity ()->set_session ('_flash_message', '刪除成功!', true);
    else
      identity ()->set_session ('_flash_message', '刪除失敗!', true);

    return redirect (array ($this->get_class (), 'index'), 'refresh');
  }
}
