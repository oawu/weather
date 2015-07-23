<?php echo render_cell ('admin_frame_cell', 'header'); ?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
  <?php
    if (isset ($message) && $message) { ?>
      <div class='info'><?php echo $message;?></div>
  <?php
    } ?>
    <form action='<?php echo base_url ('admin', 'towns');?>' method='get'>
      <div class='conditions'>
        <div class='l'>
          <input type='text' name='id' value='<?php echo isset ($columns['id']) ? $columns['id'] : '';?>' placeholder='請輸入ID..' />
          <input type='text' name='name' value='<?php echo isset ($columns['name']) ? $columns['name'] : '';?>' placeholder='請輸入名稱..' />
          <input type='text' name='postal_code' value='<?php echo isset ($columns['postal_code']) ? $columns['postal_code'] : '';?>' placeholder='請輸入郵遞區號..' />
          <button type='submit'>尋找</button>
        </div>
        <div class='r'>
          <a class='new' href='<?php echo base_url ('admin', 'towns', 'add');?>'>新增</a>
        </div>
      </div>
    </form>

    <table class='table-list'>
      <thead>
        <tr>
          <th width='60'>ID</th>
          <th >名稱</th>
          <th width='100'>郵遞區號</th>
          <th width='100'>縣市</th>
          <th width='70'>地點</th>
          <th width='70'>天氣圖示</th>
          <th width='150'>天氣概況</th>
          <th width='70'>特報圖示</th>
          <th width='180'>特報內容</th>
          <th width='120'>編輯</th>
        </tr>
      </thead>
      <tbody>
    <?php
        if ($towns) {
          foreach ($towns as $town) {
            $weather = $town->weather_array ();
            $special = $weather && $weather['special'] ? $weather['special'] : array (); ?>
            <tr>
              <td><?php echo $town->id;?></td>
              <td><?php echo $town->name;?></td>
              <td><?php echo $town->postal_code;?></td>
              <td><?php echo $town->category->name;?></td>
              <td class='map'><?php echo img ($town->pic->url ('50x50c'), false, "data-id='" . $town->id . "' class='fancybox_town'");?></td>
              <td class='map'><?php echo $weather ? img ($weather['icon'], false, 'style="background-color: rgba(255, 255, 255, 1);" data-fancybox-group="group_icon" title="' . $town->name . '" href="' . $weather['icon'] . '" class="pic"') : '(尚未有更天氣資料)';?></td>
              <td class='left'>
                描述：<?php echo $weather ? $weather['describe'] : '尚未有天氣資料';?>
                <br/>
                攝氏：<?php echo $weather ? $weather['temperature'] . '°c' : '尚未有天氣資料';?>
                <br/>
                濕度：<?php echo $weather ? $weather['humidity'] . '％' : '尚未有天氣資料';?>
                <br/>
                雨量：<?php echo $weather ? $weather['rainfall'] . 'mm' : '尚未有天氣資料';?>
              </td>
              <td class='map'><?php echo $special ? img ($special['icon'], false, 'style="background-color: rgba(255, 255, 255, 1);" data-fancybox-group="group_special_icon" title="' . $special['status'] . '" href="' . $special['icon'] . '" class="pic"') : '(尚未有更天氣資料)';?></td>
              <td class='left'>
                狀態：<?php echo $special ? $special['status'] : '尚未有天氣資料';?>
                <hr/>
                描述：<?php echo $special ? $special['describe'] . '°c' : '尚未有天氣資料';?>
                <hr/>
                時間：<?php echo $special ? $special['at'] . '％' : '尚未有天氣資料';?>
              </td>
              <td class='edit'>
                <a class='icon-refresh' data-id='<?php echo $town->id;?>'></a>
                /
                <a href='<?php echo base_url ('admin', 'towns', 'edit', $town->id);?>' class='icon-pencil2'></a>
                /
                <a href='<?php echo base_url ('admin', 'towns', 'destroy', $town->id);?>' class='icon-bin'></a>
              </td>
            </tr>
    <?php }
        } else { ?>
          <tr><td colspan='10'>目前沒有任何資料。</td></tr>
    <?php
        } ?>
      <tbody>
    </table>

  <?php echo $pagination;?>

</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
