<div class='container'>
{<{<{ echo render_cell ('demo_cell', 'main_menu', array ()); ?>
  <a class='list' href='{<{<{ echo base_url (array ('events', 'index'));?>'>列表</a>

  <table class='table-form'>
    <tbody>
      <tr>
        <th>編號</th>
        <td>
          {<{<{ echo $event->id;?>
        </td>
      </tr>
      <tr>
        <th>標題</th>
        <td>
          {<{<{ echo $event->title;?>
        </td>
      </tr>
      <tr>
        <th>資訊</th>
        <td>
          {<{<{ echo nl2br ($event->info);?>
        </td>
      </tr>
      <tr>
        <th>封面</th>
        <td>
          {<{<{ echo img ($event->cover->url ('100w'));?>
        </td>
      </tr>
      <tr>
        <th>標籤</th>
        <td>
    {<{<{ if ($event->tags) { ?>
            <div class='units'>
        {<{<{ foreach ($event->tags as $tag) { ?>
                <a class='unit' href='{<{<{ echo base_url (array ('tags', 'show', $tag->id));?>'>{<{<{ echo $tag->name;?></a>
        {<{<{ } ?>
            </div>
    {<{<{ } else { ?>
            沒任何標簽。
    {<{<{ } ?>
        </td>
      </tr>
      <tr>
        <th>參與者</th>
        <td>
    {<{<{ if ($event->attendees) { ?>
            <div class='units'>
        {<{<{ foreach ($event->attendees as $attendee) { ?>
                <div class='unit'>{<{<{ echo $attendee->name;?></div>
        {<{<{ } ?>
            </div>
    {<{<{ } else { ?>
            沒任何參與者。
    {<{<{ } ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>
