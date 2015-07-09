<div id="header">
  <div>
    <div class='l'>
      <a href='<?php echo base_url ();?>'>首頁</a>
<?php if (identity ()->get_session ('is_login')) {
        foreach ($links as $link) { ?>
          <a <?php echo $link['href'] == current_url () ? "class='active' " : '';?>href='<?php echo $link['href'];?>'><?php echo $link['name'];?></a>
  <?php }
      } ?>
    </div>
    <div class='r'>
<?php if (identity ()->get_session ('is_login')) { ?>
        <a href='<?php echo base_url ('admin', 'main', 'logout');?>'>登出</a>
<?php } else { ?>
        <a href='<?php echo base_url ('admin', 'main', 'login');?>'>登入</a>
<?php } ?>
    </div>
  </div>
</div>
