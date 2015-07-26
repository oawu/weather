<?php echo render_cell ('admin_frame_cell', 'header'); ?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
  <?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>

  <form action='<?php echo base_url ('admin', 'crontab_logs');?>' method='get'<?php echo $has_search ? ' class="show"' : '';?>>
    <div class='l'>
      <input type='text' name='id' value='<?php echo isset ($columns['id']) ? $columns['id'] : '';?>' placeholder='請輸入ID..' />
      <input type='text' name='type' value='<?php echo isset ($columns['type']) ? $columns['type'] : '';?>' placeholder='請輸入狀態..' />
      <input type='text' name='message' value='<?php echo isset ($columns['message']) ? $columns['message'] : '';?>' placeholder='請輸入訊息..' />
      <input type='text' name='run_time' value='<?php echo isset ($columns['run_time']) ? $columns['run_time'] : '';?>' placeholder='請輸入執行時間..' />
    </div>
    <button type='submit' class='submit'>尋找</button>
    <!-- <a class='new' href='<?php echo base_url ('admin', 'crontab_logs', 'add');?>'>新增</a> -->
  </form>
  <button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'search_feature icon-circle-up').prev ().addClass ('show'); else $(this).attr ('class', 'search_feature icon-circle-down').prev ().removeClass ('show');" class='search_feature icon-circle-<?php echo $has_search ? 'up' : 'down';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($crontab_logs) {
        foreach ($crontab_logs as $crontab_log) { ?>
          <tr>
            <td data-title='ID' width='120'><?php echo $crontab_log->id;?></td>
            <td data-title='狀態' width='180'<?php echo $crontab_log->type != '完成' ? ' class="error"' : '';?>><?php echo $crontab_log->type;?></td>
            <td data-title='訊息'><?php echo $crontab_log->message;?></td>
            <td data-title='執行時間' width='160'><?php echo $crontab_log->run_time;?> 秒</td>
            <td data-title='開始時間' width='160' data-time='<?php echo $crontab_log->created_at;?>' class='created_at'><?php echo $crontab_log->created_at;?></td>
            <td data-title='編輯' width='60'>
              <a href='<?php echo base_url ('admin', 'crontab_logs', 'destroy', $crontab_log->id);?>' class='icon-bin'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan>目前沒有任何資料。</td></tr>
<?php }?>
    </tbody>
  </table>
  <?php echo render_cell ('admin_frame_cell', 'pagination', $pagination);?>
</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
