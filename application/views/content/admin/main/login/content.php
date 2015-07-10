<?php echo render_cell ('admin_frame_cell', 'header');?>

<div id='container'>
  <form class='login' action='<?php echo base_url (array ('admin', 'main', 'signin'));?>' method='post'>
    <h2>請輸入帳號密碼登入</h2>

    <div class='row split-left'>
      <label class='l' for='account'>帳  號</label>
      <input type='text' class='r' name='account' id='account' value='<?php echo $account ? $account : '';?>' placeholder='輸入帳號..' pattern=".{1,10}" required title="輸入帳號.." autofocus />
    </div>

    <div class='row split-left'>
      <label class='l' for='password'>密  碼</label>
      <input type='password' class='r' name='password' id='password' value='' placeholder='輸入密碼..' pattern=".{3,}" required title="輸入密碼(3個英、數字元以上).."/>
    </div>

<?php
    if (isset ($message) && $message) { ?>
      <div class='row error'><?php echo $message;?></div>
<?php
    } ?>

    <div class='row split-right'>
      <div class='l'></div>
      <div class='r'>
        <button type='submit'>登入！</button>
      </div>
    </div>
  </form>
</div>
<?php echo render_cell ('admin_frame_cell', 'footer');?>
