<div class='_scope' itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
  <a itemprop="url" href='<?php echo URL;?>'><span itemprop="title"><?php echo TITLE;?></span></a>
</div>
<header class='n'>
  <div>
    <a class='icon-m'></a>
    <a class='icon-o<?php echo $active == URL_INDEX ? ' a': '';?>' href='<?php echo URL_INDEX;?>'>首頁</a>
    <a class='icon-p<?php echo $active == URL_MAPS ? ' a': '';?>' href='<?php echo URL_MAPS;?>'>地圖</a>
    <a class='icon-h<?php echo $active == URL_SEARCH ? ' a': '';?>' href='<?php echo URL_SEARCH;?>'>搜尋</a>
    <a class='icon-x<?php echo $active == URL_ALL ? ' a': '';?>' href='<?php echo URL_ALL;?>'>總覽</a>
    <a class='icon-d<?php echo $active == URL_README ? ' a': '';?>' href='<?php echo URL_README;?>'>說明</a>
    <span><?php echo isset ($title) && $title ? $title : '';?></span>
    <span>
      <a class='icon-s' id='share' title='分享至 Facebook'></a>
      <a href='<?php echo OA_URL;?>' class='icon-u' title='網站作者' target='_blank'></a>
    </span>
  </div>
</header>