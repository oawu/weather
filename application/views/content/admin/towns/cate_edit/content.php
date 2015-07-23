<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
<?php
  if (TownCategory::count ()) {
    if (isset ($message) && $message) { ?>
      <div class='error'><?php echo $message;?></div>
<?php
    } ?>

    <form action='<?php echo base_url (array ('admin', 'towns', 'cate_create'));?>' method='post' enctype='multipart/form-data'>
      <table class='table-form'>
        <tbody>
          <tr>
            <th>名稱</th>
            <td>
              <input type='text' id='name' name='name' value='<?php echo $name ? $name : $town_category->name;?>' placeholder='請輸入名稱..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
            </td>
          </tr>
          <tr>
            <td colspan='2'>
              <a href='<?php echo base_url ('admin', 'cate_towns');?>'>回列表</a>
              <button type='reset' class='button'>重填</button>
              <button type='submit' class='button'>確定</button>
            </td>
          </tr>
        </tbody>
      </table>
    </form>
<?php
  } else { ?>
    <a href='<?php echo base_url ('admin', 'towns', 'cate_add');?>' class='create_cate'>請先新稱縣市分類！</a>
<?php
  } ?>
</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
