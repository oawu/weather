<div id="header">
  <div>
    <div class='l'>
<?php if ($left_links) {
        foreach ($left_links as $link) { 
          if (!$link['is_login'] || ($link['is_login'] && identity ()->user ())) { ?>
            <a <?php echo $link['href'] == current_url () ? "class='active' " : '';?>href='<?php echo $link['href'];?>'<?php echo isset ($link['target']) && $link['target'] ? ' target="_blank"' : '';?>><?php echo $link['name'];?></a>
    <?php }
        }
      } ?>
    </div>
    <div class='r'>
<?php if ($right_links) {
        foreach ($right_links as $link) { 
          if (!$link['is_login'] || ($link['is_login'] && identity ()->user ())) { ?>
            <a <?php echo isset ($link['id']) && $link['id'] ? "id='" . $link['id'] . "' ": ''; ?><?php echo $link['href'] == current_url () ? "class='active' " : '';?>href='<?php echo $link['href'];?>'<?php echo isset ($link['target']) && $link['target'] ? ' target="_blank"' : '';?>><?php echo $link['name'];?></a>
    <?php }
        }
      } ?>
    </div>
  </div>
</div>

<div id='pop_up' class='hide'>
  <div class='paper'></div>
</div>