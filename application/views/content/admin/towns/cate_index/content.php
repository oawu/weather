<?php echo render_cell ('admin_frame_cell', 'header'); ?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
<?php
  if (isset ($message) && $message) { ?>
    <div class='info'><?php echo $message;?></div>
<?php
  } ?>
  <form action='<?php echo base_url ('admin', 'towns', 'cate_index');?>' method='get'>
    <div class='conditions'>
      <div class='l'>
        <input type='text' name='id' value='<?php echo isset ($columns['id']) ? $columns['id'] : '';?>' placeholder='請輸入ID..' />
        <input type='text' name='name' value='<?php echo isset ($columns['name']) ? $columns['name'] : '';?>' placeholder='請輸入名稱..' />
        <button type='submit'>尋找</button>
      </div>
      <div class='r'>
        <a class='new' href='<?php echo base_url ('admin', 'towns', 'cate_add');?>'>新增</a>
      </div>
    </div>
  </form>

  <table class='table-list'>
    <thead>
      <tr>
        <th width='60'>ID</th>
        <th width='200'>名稱</th>
        <th >鄉鎮</th>
        <th width='150'>編輯</th>
      </tr>
    </thead>
    <tbody>
  <?php
      if ($town_categories) {
        foreach ($town_categories as $town_category) { ?>
          <tr>
            <td><?php echo $town_category->id;?></td>
            <td><?php echo $town_category->name;?></td>
            <td class='left'><?php echo $town_category->towns ? implode ('', array_map (function ($town) {
              return '<div class="town">
              <div class="title">' . $town->name . '</div>
                <div class="features">
                  <a href="' . base_url ('admin', 'towns', 'cate_edit_town', $town->id) . '" class="icon-pencil2"></a>
                  <a class="icon-eye2 fancybox_town" data-id="' . $town->id . '"></a>
                  <a href="' . base_url ('admin', 'towns', 'cate_destroy_town', $town->id) . '" class="icon-bin"></a>
                </div>
              </div>';}, $town_category->towns)) : '(無鄉鎮)';?></td>
            <td class='edit'>
              <a href='<?php echo base_url ('admin', 'towns', 'cate_add_town', $town_category->id);?>' class='icon-location'></a>
              /
              <a href='<?php echo base_url ('admin', 'towns', 'cate_edit', $town_category->id);?>' class='icon-pencil2'></a>
              /
              <a href='<?php echo base_url ('admin', 'towns', 'cate_destroy', $town_category->id);?>' class='icon-bin'></a>
            </td>
          </tr>
  <?php }
      } else { ?>
        <tr><td colspan='3'>目前沒有任何資料。</td></tr>
  <?php
      } ?>
    <tbody>
  </table>

<?php echo $pagination;?>

</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
