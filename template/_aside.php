<aside>
  <header>
    <span>天氣地圖</span>
    <span>Weather Maps</span>
  </header>

  <span>主要功能</span>
  <ul>
    <li><a class='icon-o<?php echo $active == URL_INDEX ? ' a' : '';?>' href='<?php echo URL_INDEX;?>'>首頁</a></li>
    <li><a class='icon-p<?php echo $active == URL_MAPS ? ' a' : '';?>' href='<?php echo URL_MAPS;?>'>地圖模式</a></li>
    <li><a class='icon-h<?php echo $active == URL_SEARCH ? ' a' : '';?>' href='<?php echo URL_SEARCH;?>'>快速搜尋</a></li>
    <li><a class='icon-x<?php echo $active == URL_ALL ? ' a' : '';?>' href='<?php echo URL_ALL;?>'>總覽全台</a></li>
  </ul>

  <span>其他功能</span>
  <ul>
    <li><a class='icon-u' href='<?php echo OA_URL;?>' target='_blank'>開發人員</a></li>
    <li><a class='icon-d<?php echo $active == URL_README ? ' a' : '';?>' href='<?php echo URL_README;?>'>製作說明</a></li>
    <li><a class='icon-r' href='http://www.cwb.gov.tw/' target='_blank'>資料來源</a></li>
    <li><a class='icon-g' href='<?php echo GITHUB_URL;?>' target='_blank'>GitHub</a></li>
  </ul>
</aside>
<div class='cover'></div>
