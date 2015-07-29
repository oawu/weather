<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container' class='<?php echo !$frame_sides ? 'no_sides': '';?>'>
<?php
  if (isset ($message) && $message) { ?>
    <div class='error'><?php echo $message;?></div>
<?php
  } ?>

  <form action='<?php echo base_url (array ('admin', 'towns', 'update_view', $town->id));?>' method='post' enctype='multipart/form-data'>
    <table class='table-form'>
      <tbody>

        <tr>
          <th>緯度</th>
          <td>
            <input type='text' id='latitude' name='latitude' value='<?php echo !$latitude ? $view ? $view->latitude : $town->latitude : $latitude;?>' placeholder='請輸入緯度..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' readonly />
          </td>
        </tr>

        <tr>
          <th>經度</th>
          <td>
            <input type='text' id='longitude' name='longitude' value='<?php echo !$longitude ? $view ? $view->longitude : $town->longitude : $longitude;?>' placeholder='請輸入經度..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' readonly />
          </td>
        </tr>

        <tr>
          <th>水平角度</th>
          <td>
            <input type='text' id='heading' name='heading' value='<?php echo !$heading ? $view ? $view->heading : '0' : $heading;?>' placeholder='請輸入水平角度..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' readonly />
          </td>
        </tr>

        <tr>
          <th>垂直角度</th>
          <td>
            <input type='text' id='pitch' name='pitch' value='<?php echo !$pitch ? $view ? $view->pitch : '0' : $pitch;?>' placeholder='請輸入垂直角度..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' readonly />
          </td>
        </tr>

        <tr>
          <th>放大度</th>
          <td>
            <input type='text' id='zoom' name='zoom' value='<?php echo !$zoom ? $view ? $view->zoom : '0' : $zoom;?>' placeholder='請輸入放大度..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' readonly />
          </td>
        </tr>

        <tr>
          <th>地圖</th>
          <td>
            <div class='google'>
              <div class='map'>
                <i></i><i></i><i></i><i></i>
                <div id='map'></div>
                <div class='loading_data'>資料讀取中...</div>
                <div id='name'></div>
                <div id='postal_code'></div>
              </div>
              <div class='view'>
                <i></i><i></i><i></i><i></i>
                <div id='view'></div>
                <div class='loading_data'>沒有街景影像...</div>
              </div>
            </div>
          </td>
        </tr>

        <tr>
          <td colspan='2'>
            <a href='<?php echo base_url ('admin', 'towns');?>'>回列表</a>
            <button type='reset' class='button'>重填</button>
            <button type='submit' class='button'>確定</button>
          </td>
        </tr>
      </tbody>
    </table>
  </form>
</div>

<?php echo render_cell ('admin_frame_cell', 'footer');?>
