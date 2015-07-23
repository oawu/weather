<div id="header_right_slide" class="close">
  <div class="right_slide_container">
<?php foreach ($left_links as $link) {
        if ($link['show']) {?>
        <a class='sub<?php echo $link['href'] == current_url () ? " active" : '';?>' href="<?php echo $link['href'];?>"<?php echo isset ($link['target']) && $link['target'] ? ' target="_blank"' : '';?>><?php echo $link['name'];?></a>
  <?php }
      }?>
<?php foreach (array_reverse ($right_links) as $link) {
        if ($link['show']) {?>
        <a class='sub<?php echo $link['href'] == current_url () ? " active" : '';?>' href="<?php echo $link['href'];?>"<?php echo isset ($link['target']) && $link['target'] ? ' target="_blank"' : '';?>><?php echo $link['name'];?></a>
  <?php }
      }?>
  </div>
</div>

<div id="header_slide_cover"></div>

<div id="header">
  <div class='header_container'>
    <div class="l">
      <a class='home icon-home' href='<?php echo base_url ();?>'></a>
<?php foreach ($left_links as $link) {
        if ($link['show']) {?>
        <a <?php echo $link['href'] == current_url () ? "class='active' " : '';?>href="<?php echo $link['href'];?>"<?php echo isset ($link['target']) && $link['target'] ? ' target="_blank"' : '';?>><?php echo $link['name'];?></a>
  <?php }
      }?>
    </div>
    <div class="c">Weather Maps</div>
    <div class="r">
<?php foreach ($right_links as $link) {
        if ($link['show']) {?>
          <a <?php echo $link['href'] == current_url () ? "class='active' " : '';?>href="<?php echo $link['href'];?>"<?php echo isset ($link['target']) && $link['target'] ? ' target="_blank"' : '';?>><?php echo $link['name'];?></a>
  <?php }
      }?>
      <a class='option icon-th-menu'></a>
    </div>
  </div>
</div>