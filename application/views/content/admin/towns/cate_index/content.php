<?php echo render_cell ('admin_frame_cell', 'header'); ?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
<?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>
  <form action='<?php echo base_url ('admin', 'towns', 'cate_index');?>' method='get'<?php echo $has_search ? ' class="show"' : '';?>>
    <div class='l'>
        <input type='text' name='id' value='<?php echo isset ($columns['id']) ? $columns['id'] : '';?>' placeholder='請輸入ID..' />
        <input type='text' name='name' value='<?php echo isset ($columns['name']) ? $columns['name'] : '';?>' placeholder='請輸入名稱..' />
    </div>
    <button type='submit' class='submit'>尋找</button>
      <a class='new' href='<?php echo base_url ('admin', 'towns', 'cate_add');?>'>新增</a>
  </form>
  <button type='button' onClick="if (!$(this).prev ().is (':visible')) $(this).attr ('class', 'search_feature icon-circle-up').prev ().addClass ('show'); else $(this).attr ('class', 'search_feature icon-circle-down').prev ().removeClass ('show');" class='search_feature icon-circle-<?php echo $has_search ? 'up' : 'down';?>'></button>

  <table class='table-list-rwd'>
    <tbody>
<?php if ($town_categories) {
        foreach ($town_categories as $town_category) { ?>
          <tr>
            <td data-title='ID' width='60'><?php echo $town_category->id;?></td>
            <td data-title='名稱' width='200'><?php echo $town_category->name;?></td>
            <td data-title='鄉鎮' class='left'>
              <?php echo $town_category->towns ? implode ('', array_map (function ($town) {
                return '<div class="town">
                <div class="title">' . $town->name . '</div>
                  <div class="features">
                    <a href="' . base_url ('admin', 'towns', 'cate_edit_town', $town->id) . '" class="icon-pencil2"></a>
                    <a class="icon-eye2 fancybox_town" data-id="' . $town->id . '"></a>
                    <a href="' . base_url ('admin', 'towns', 'cate_destroy_town', $town->id) . '" class="icon-bin"></a>
                  </div>
                </div>';}, $town_category->towns)) : '(無鄉鎮)';?>
            </td>
            <td data-title='編輯' width='150' class='middle'>
              <a href='<?php echo base_url ('admin', 'towns', 'cate_add_town', $town_category->id);?>' class='icon-location'></a>
              /
              <a href='<?php echo base_url ('admin', 'towns', 'cate_edit', $town_category->id);?>' class='icon-pencil2'></a>
              /
              <a href='<?php echo base_url ('admin', 'towns', 'cate_destroy', $town_category->id);?>' class='icon-bin'></a>
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
