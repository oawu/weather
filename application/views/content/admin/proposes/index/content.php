<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container'>

<?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>
  <form action='<?php echo base_url ('admin', 'proposes');?>' method='get'>
    <div class='conditions'>
      <div class='l'>
        <input type='text' name='latitude' value='<?php echo isset ($columns['latitude']) ? $columns['latitude'] : '';?>' placeholder='請輸入緯度(latitude)..' />
        <input type='text' name='longitude' value='<?php echo isset ($columns['longitude']) ? $columns['longitude'] : '';?>' placeholder='請輸入經度(longitude)..' />
        <button type='submit'>尋找</button>
      </div>
      <div class='r'>
        <a class='new' href='<?php echo base_url ('admin', 'proposes', 'add');?>'>新增</a>
      </div>
    </div>
  </form>

  <table class='table-list'>
    <thead>
      <tr>
        <th width='60'>ID</th>
        <th >標題</th>
        <th width='140'>IP</th>
        <th width='100'>地點</th>
        <th width='100'>緯度</th>
        <th width='100'>經度</th>
        <th width='100'>編輯</th>
      </tr>
    </thead>
    <tbody>
  <?php
      if ($proposes) {
        foreach ($proposes as $propose) { ?>
          <tr>
            <td><?php echo $propose->id;?></td>
            <td><?php echo $propose->title;?></td>
            <td><?php echo $propose->ip;?></td>
            <td><?php echo img ($propose->picture (), false, 'class="fanc" data-id="' . $propose->id . '"');?></td>
            <td><?php echo $propose->latitude;?></td>
            <td><?php echo $propose->longitude;?></td>
            <td class='edit'>
              <a href='<?php echo base_url ('admin', 'proposes', 'check', $propose->id);?>'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="26" height="28" viewBox="0 0 26 28"><path fill="#444444" d="M22 14.531v4.969q0 1.859-1.32 3.18t-3.18 1.32h-13q-1.859 0-3.18-1.32t-1.32-3.18v-13q0-1.859 1.32-3.18t3.18-1.32h13q0.984 0 1.828 0.391 0.234 0.109 0.281 0.359 0.047 0.266-0.141 0.453l-0.766 0.766q-0.156 0.156-0.359 0.156-0.047 0-0.141-0.031-0.359-0.094-0.703-0.094h-13q-1.031 0-1.766 0.734t-0.734 1.766v13q0 1.031 0.734 1.766t1.766 0.734h13q1.031 0 1.766-0.734t0.734-1.766v-3.969q0-0.203 0.141-0.344l1-1q0.156-0.156 0.359-0.156 0.094 0 0.187 0.047 0.313 0.125 0.313 0.453zM25.609 6.891l-12.719 12.719q-0.375 0.375-0.891 0.375t-0.891-0.375l-6.719-6.719q-0.375-0.375-0.375-0.891t0.375-0.891l1.719-1.719q0.375-0.375 0.891-0.375t0.891 0.375l4.109 4.109 10.109-10.109q0.375-0.375 0.891-0.375t0.891 0.375l1.719 1.719q0.375 0.375 0.375 0.891t-0.375 0.891z"></path></svg></a>
              /
              <a href='<?php echo base_url ('admin', 'proposes', 'destroy', $propose->id);?>'><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32" height="32" viewBox="0 0 32 32"><path fill="#444444" d="M4 10v20c0 1.1 0.9 2 2 2h18c1.1 0 2-0.9 2-2v-20h-22zM10 28h-2v-14h2v14zM14 28h-2v-14h2v14zM18 28h-2v-14h2v14zM22 28h-2v-14h2v14z"></path><path fill="#444444" d="M26.5 4h-6.5v-2.5c0-0.825-0.675-1.5-1.5-1.5h-7c-0.825 0-1.5 0.675-1.5 1.5v2.5h-6.5c-0.825 0-1.5 0.675-1.5 1.5v2.5h26v-2.5c0-0.825-0.675-1.5-1.5-1.5zM18 4h-6v-1.975h6v1.975z"></path></svg></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan='7'>目前沒有任何資料。</td></tr>
  <?php
      } ?>
    <tbody>
  </table>

<?php echo $pagination;?>

</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
