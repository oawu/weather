<div class='InfoBubble'>
  <div>選擇放大級別：</div>
  <select class='zoom'>
    <?php
    for ($i = 0; $i < 22; $i++) { ?>
      <option value='<?php echo $i;?>'<?php echo $town->zoom == $i ? ' selected' : '';?>><?php echo $i;?></option>
    <?php
    } ?>
  </select>
</div>