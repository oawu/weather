<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container'>

<?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>
  <form action='<?php echo base_url ('admin', 'call_logs');?>' method='get'>
    <div class='conditions'>
      <div class='l'>
        <input type='text' name='latitude' value='<?php echo isset ($columns['latitude']) ? $columns['latitude'] : '';?>' placeholder='請輸入緯度(latitude)..' />
        <input type='text' name='longitude' value='<?php echo isset ($columns['longitude']) ? $columns['longitude'] : '';?>' placeholder='請輸入經度(longitude)..' />
        <input type='text' name='temperature' value='<?php echo isset ($columns['temperature']) ? $columns['temperature'] : '';?>' placeholder='請輸入溫度(絕對)..' />
        <button type='submit'>尋找</button>
      </div>
      <div class='r'>
        <a class='new' href='<?php echo base_url ('admin', 'call_logs', 'add');?>'>新增</a>
      </div>
    </div>
  </form>

  <table class='table-list'>
    <thead>
      <tr>
        <th width='60'>ID</th>
        <th >天氣 ID</th>
        <th >天氣 title</th>
        <th width='130'>呼叫時間</th>
      </tr>
    </thead>
    <tbody>
  <?php
      if ($call_logs) {
        foreach ($call_logs as $call_log) { ?>
          <tr>
            <td><?php echo $call_log->id;?></td>
            <td><?php echo $call_log->weather_id;?></td>
            <td><?php echo $call_log->weather ? $call_log->weather->title : '不存在';?></td>
            <td class='timeago' data-time='<?php echo $call_log->created_at;?>'><?php echo $call_log->created_at;?></td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan='4'>目前沒有任何資料。</td></tr>
  <?php
      } ?>
    <tbody>
  </table>

<?php echo $pagination;?>

</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
