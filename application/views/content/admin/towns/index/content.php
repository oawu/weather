<?php echo render_cell ('admin_frame_cell', 'header'); ?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
  <?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>
  <form action='<?php echo base_url ('admin', 'towns');?>' method='get'<?php echo $has_search ? ' class="show"' : '';?>>
    <div class='l'>
      <input type='text' name='id' value='<?php echo isset ($columns['id']) ? $columns['id'] : '';?>' placeholder='請輸入ID..' />
      <input type='text' name='name' value='<?php echo isset ($columns['name']) ? $columns['name'] : '';?>' placeholder='請輸入名稱..' />
      <input type='text' name='postal_code' value='<?php echo isset ($columns['postal_code']) ? $columns['postal_code'] : '';?>' placeholder='請輸入郵遞區號..' />
    </div>
    <button type='submit' class='submit'>尋找</button>
      <a class='new' href='<?php echo base_url ('admin', 'towns', 'add');?>'>新增</a>
  </form>
  <button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'search_feature icon-circle-up').prev ().addClass ('show'); else $(this).attr ('class', 'search_feature icon-circle-down').prev ().removeClass ('show');" class='search_feature icon-circle-<?php echo $has_search ? 'up' : 'down';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($towns) {
        foreach ($towns as $town) {
          $weather = $town->weather_array ();
          $special = $weather && $weather['special'] ? $weather['special'] : array (); ?>
          <tr>
            <td data-title='ID' width='60'><?php echo $town->id;?></td>
            <td data-title='名稱'><?php echo $town->name;?></td>
            <td data-title='郵遞區號' width='100'><?php echo $town->postal_code;?></td>
            <td data-title='縣市' width='100'><?php echo $town->category->name;?></td>
            <td data-title='地點' width='70'>
              <img src='<?php echo $town->pic->url ('50x50c');?>' data-id='<?php echo $town->id;?>' class='fancybox_town pic'/>
            </td>
            <td data-title='街景' width='70'>
              <?php
              if ($town->view) { ?>
                <img src='<?php echo $town->view->pic->url ('50x50c');?>' data-id='<?php echo $town->id;?>' class='fancybox_view pic'/>
              <?php
              } else { ?>
                <a data-id='<?php echo $town->id;?>' class='fancybox_view'>未設定街景</a>
              <?php
              }
              ?>
            </td>
            <td data-title='天氣圖示' width='70'>
        <?php if ($weather) { ?>
                <img src='<?php echo $weather['icon'];?>' data-fancybox-group='group_icon' title='<?php echo $town->name;?>' href='<?php echo $weather['icon'];?>' class='pic'/>
        <?php } else { ?>
                (尚未有更天氣資料)
        <?php } ?>
            <td data-title='天氣概況' width='150' class='left'>
              描述：<?php echo $weather ? $weather['describe'] : '尚未有天氣資料';?>
              <br/>
              攝氏：<?php echo $weather ? $weather['temperature'] . '°c' : '尚未有天氣資料';?>
              <br/>
              濕度：<?php echo $weather ? $weather['humidity'] . '％' : '尚未有天氣資料';?>
              <br/>
              雨量：<?php echo $weather ? $weather['rainfall'] . 'mm' : '尚未有天氣資料';?>
            </td>
            <td data-title='特報圖示' width='70'><?php echo $special ? img ($special['icon'], false, 'style="background-color: rgba(255, 255, 255, 1);" data-fancybox-group="group_special_icon" title="' . $special['status'] . '" href="' . $special['icon'] . '" class="pic"') : '(尚未有更天氣資料)';?></td>
            <td data-title='特報內容' width='180' class='left'>
              狀態：<?php echo $special ? $special['status'] : '尚未有天氣資料';?>
              <hr/>
              描述：<?php echo $special ? $special['describe'] . '°c' : '尚未有天氣資料';?>
              <hr/>
              時間：<?php echo $special ? $special['at'] . '％' : '尚未有天氣資料';?>
            </td>
            <td data-title='編輯' width='120' class='middle'>
              <a class='icon-refresh' data-id='<?php echo $town->id;?>'></a>
              /
              <a href='<?php echo base_url ('admin', 'towns', 'view', $town->id);?>' class='icon-street-view'></a>
              <hr/>
              <a href='<?php echo base_url ('admin', 'towns', 'edit', $town->id);?>' class='icon-pencil2'></a>
              /
              <a href='<?php echo base_url ('admin', 'towns', 'destroy', $town->id);?>' class='icon-bin'></a>
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
