<?php echo render_cell ('site_frame_cell', 'header');?>

<div id='container'>
  <div class="map">
    <i></i><i></i><i></i><i></i>
    <div id='map'></div>
    <div class='hint'>
      <img src='/resource/image/spotlight-poi-blue.png' /> <span>表示已存在景點</span>
    </div>
    <div class='error<?php echo isset ($message) && $message ? ' show':''; ?>'>
<?php if (isset ($message) && $message) { ?>
        <?php echo $message;?>
<?php } ?>
    </div>
    <form id='fm' action='<?php echo base_url ('proposes', 'create');?>' method='post' enctype='multipart/form-data'>
      <div class='input_bar'>
        <div class='l'>
          <input type='text' name='title' value='<?php echo $title;?>' placeholder='請輸入標題..' maxlength='200' pattern='.{1,200}' required title='輸入 1~200 個字元!' />
        </div>
        <div class='r'>
          <button type='submit' class='button'>確定</button>
        </div>
      </div>
    </form>
    <div class='latlng_bar' id='latlng_bar'>
      <div class='l' id='lat' data-val='<?php echo $latitude;?>'></div>
      <div class='r' id='lng' data-val='<?php echo $longitude;?>'></div>
    </div>
  </div>
</div>

<?php echo render_cell ('site_frame_cell', 'footer');?>
