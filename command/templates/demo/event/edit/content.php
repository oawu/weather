<div class='container'>
{<{<{ echo render_cell ('demo_cell', 'main_menu', array ()); ?>
  <a class='list' href='{<{<{ echo base_url (array ('events', 'index'));?>'>列表</a>
  {<{<{
    if (isset ($message) && $message) { ?>
      <div class='error'>{<{<{ echo $message;?></div>
  {<{<{
    } ?>
  <form action='{<{<{ echo base_url (array ('events', 'update', $event->id));?>' method='post' enctype='multipart/form-data'>
    <table class='table-form'>
      <tbody>
        <tr>
          <th>標題</th>
          <td>
            <input type='text' name='title' value='{<{<{ echo $event->title;?>' placeholder='請輸入活動標題..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
          </td>
        </tr>
        <tr>
          <th>資訊</th>
          <td>
            <textarea name='info' placeholder='請輸入活動資訊..' pattern='.{1,}' required title='輸入至少 1 個字元!' >{<{<{ echo $event->info;?></textarea>
          </td>
        </tr>
        <tr>
          <th>封面</th>
          <td>
            {<{<{ echo img ($event->cover->url ('100w'));?>
            <hr />
            <input type='file' name='cover' value='' accept="image/gif, image/jpeg, image/png" />
          </td>
        </tr>
        <tr>
          <th>標籤</th>
          <td>
      {<{<{ if ($tags = Tag::all ()) {
              foreach ($tags as $tag) { ?>
                <div class='checkbox'>
                  <input type='checkbox' name='tag_ids[]' id='tag_{<{<{ echo $tag->id;?>' value='{<{<{ echo $tag->id;?>'{<{<{ echo $event->tag_event_maps && in_array($tag->id, column_array ($event->tag_event_maps, 'tag_id')) ? ' checked' : '';?> />
                  <span class='ckb-check'></span>
                  <label for='tag_{<{<{ echo $tag->id;?>'>{<{<{ echo $tag->name;?></label>
                </div>
        {<{<{ }
            }?>
          </td>
        </tr>
        <tr>
          <th>參與者</th>
          <td>
            <div class='attendees'>
        {<{<{ if ($event->attendees) {
                foreach ($event->attendees as $index => $attendee) { ?>
                  <div class='attendee'>
                    <input type='hidden' name='old_attendees[{<{<{ echo $index;?>][id]' value='{<{<{ echo $attendee->id;?>' placeholder='請輸入參與者名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
                    <input type='text' name='old_attendees[{<{<{ echo $index;?>][name]' value='{<{<{ echo $attendee->name;?>' placeholder='請輸入參與者名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
                    <button type='button' class='button destroy'>-</button>
                  </div>
          {<{<{ }
              } ?>
              <button type='button' class='button add'>+</button>
            </div>
          </td>
        </tr>
        <tr>
          <td colspan='2'>
            <button type='reset' class='button'>重填</button>
            <button type='submit' class='button'>確定</button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

<script id='_attendee' type='text/x-html-template'>
  <div class='attendee'>
    <input type='text' name='attendees[]' value='' placeholder='請輸入參與者名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
    <button type='button' class='button destroy'>-</button>
  </div>
</script>
