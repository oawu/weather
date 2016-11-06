<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

    <title><?php echo $title = '製作說明' . ' - ' . TITLE;?></title>

    <meta name="robots" content="index,follow" />

    <meta name="keywords" content="<?php echo '製作說明, ' . KEYWORDS;?>" />
    <meta name="description" content="<?php echo mb_strimwidth ($description = '以 S3 為主 EC2 為輔，不用資料庫，而是將資料轉乘 Json 的格式，再用資料夾結構的放置，通通上傳至 S3 上！如此一來前端所有資源從原本的向 EC2 索取，變成改從 S3 上取得，大大地減低天氣的 Request 數量，API Request 不說，光圖片、靜態檔案就少了很多流量，同時資料庫也減輕一部份的負擔！唯一會需要後端的部分，就只剩下固定時間更新資料的後端處理部分，而這部分也只會耗費不到 30MB 的記憶體，所以負擔不算大。', 0, 150, '…','UTF-8');?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo URL_README;?>" />
    <meta property="og:title" content="<?php echo $title;?>" />
    <meta property="og:description" content="<?php echo mb_strimwidth (preg_replace ("/\s+/u", "", $description), 0, 300, '…','UTF-8');?>" />
    <meta property="fb:admins" content="<?php echo FB_ADMIN_ID;?>" />
    <meta property="fb:app_id" content="<?php echo FB_APP_ID;?>" />
    <meta property="og:locale" content="zh_TW" />
    <meta property="og:locale:alternate" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="article:author" content="<?php echo OA_FB_URL;?>" />
    <meta property="article:publisher" content="<?php echo OA_FB_URL;?>" />
    <meta property="article:modified_time" content="<?php echo date ('c');?>" />
    <meta property="article:published_time" content="<?php echo date ('c');?>" />
    <meta property="og:image" content="<?php echo URL_OG_README;?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/<?php echo pathinfo (URL_OG_README, PATHINFO_EXTENSION);?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <link rel="canonical" href="<?php echo URL_README;?>" />
    <link rel="alternate" href="<?php echo URL_README;?>" hreflang="zh-Hant" />

    <link href='https://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<?php foreach (Min::css ('/css/public' . CSS, '/css/readme' . CSS) as $path) { ?>
        <link href="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" rel="stylesheet" type="text/css" />
<?php } ?>

<?php foreach (Min::js ('/js/public' . JS, '/js/readme' . JS) as $path) { ?>
        <script src="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" language="javascript" type="text/javascript" ></script>
<?php }?>

    <script type="application/ld+json">
<?php echo json_encode (array (
        '@context' => 'http://schema.org', '@type' => 'Article',
        'mainEntityOfPage' => array (
          '@type' => 'WebPage',
          '@id' => URL_README),
        'headline' => $title,
        'image' => array ('@type' => 'ImageObject', 'url' => URL_OG_README, 'height' => 630, 'width' => 1200),
        'datePublished' => date ('c'),
        'dateModified' => date ('c'),
        'author' => array (
            '@type' => 'Person', 'name' => OA, 'url' => OA_URL,
            'image' => array ('@type' => 'ImageObject', 'url' => avatar_url (OA_FB_UID, 300, 300), 'height' => 300, 'width' => 300)
          ),
        'publisher' => array (
            '@type' => 'Organization', 'name' => TITLE,
            'logo' => array ('@type' => 'ImageObject', 'url' => URL_IMG . 'amp_title.png', 'width' => 600, 'height' => 60)
          ),
        'description' => mb_strimwidth ($description, 0, 150, '…','UTF-8')
      ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
    </script>
  </head>
  <body lang="zh-tw">
    
    <?php echo $_header;?>

    <div class='_scope' itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
      <a itemprop="url" href='<?php echo URL_README;?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", '製作說明');?></span></a>
    </div>

    <div id='container'>
      <article>
        <header>
          <h1><a href='URL_README'>Weather Maps</a>
            <div class='fb-like' data-href='<?php echo URL_README;?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
          </h1>
          <span><p>全台灣 368 個鄉鎮 • 天氣概況</p></span>
        </header>
        <section>
          <p>想查詢每個地方的天氣嗎？藉由 Google Maps API 的地圖服務，以及<b>中央氣象局</b>的天氣預報，讓你快速輕鬆的查詢台灣 368 個鄉鎮的天氣概況！使用中央氣象局網站公告的氣象預報資訊作為資料來源，平均每 30 分鐘更新最新天氣概況，分別顯示溫濕度、日出落時間、特別預報..等功能。</p>
        </section>
      </article>
      
      <article>
        <header>
          <h2>作品分享</h2>
          <span>取之於網路 • 分享於網路</span>
        </header>
        <section>
          <ul>
            <li>GitHub：<a href='https://github.com/comdan66/weather' target='_blank'>https://github.com/comdan66/weather</a></li>
            <li>Demo：<a href='https://works.ioa.tw/weather/index.html' target='_blank'>https://works.ioa.tw/weather/index.html</a></li>
            <li>更多作品：<a href='<?php echo OA_URL;?>' target='_blank'><?php echo OA_URL;?></a></li>
            <li>API 文件：<a href='<?php echo URL_API_DOC;?>' target='_blank'><?php echo URL_API_DOC;?></a></li>
          </ul>
        </section>
      </article>
      
      <article>
        <header>
          <h2>作品聲明</h2>
          <span>授權 • 聲明</span>
        </header>
        <section>
          <ul>
            <li>本作品授權採用<b>姓名標示-非商業性 2.0 台灣 (CC BY-NC 2.0 TW) 授權</b>，詳見 <a href='https://creativecommons.org/licenses/by-nc/2.0/tw/' target='_blank'>https://creativecommons.org/licenses/by-nc/2.0/tw/</a></li>
            <li>網頁內容資料主要參考<a href='http://www.cwb.gov.tw/' target='_blank'>中央氣象局網站</a>所公佈之內容建置，其內容預報僅供參考，更多詳細氣像概況可至中央氣象局查詢，一切天氣資訊以氣象局為準喔！</li>
          </ul>
        </section>
      </article>
      

      <article>
        <header>
          <h2>實作說明</h2>
          <span>流程解說 • 部署 S3 • API 分享</span>
        </header>
        <section>
          <p>天氣地圖 Weather Maps 其實約莫在去年這時候第一、二版完成，然後一直放在 <a href='https://aws.amazon.com/tw/ec2/' target='_blank'>Amazon EC2</a> 讓它獨立的運作，直到這幾週才有空再將他修改一下，這次修改不僅對版型做翻新，也對系統面做修改！</p>
          <p>上一版架構基本上就是典型的後端伺服器吐出 API 處理架構，藉由一台 EC 定時地去取得最新天氣資訊，在存入資料庫然後提供 API。運行一年下來，其實無論是流量、記憶體亦或者資料庫都會佔走 EC2 一項固定的資源（因為 <a href='https://aws.amazon.com/tw/rds/' target='_blank'>Amazon RDS</a> 對我來說有點貴..，所以我的資料庫是安裝在 EC2 上的），而這項卻是固定要做的事，尤其是圖片檔案與 API 流量！為了減低 EC2 的負擔以及加強 <a href='https://zh.wikipedia.org/zh-tw/搜尋引擎最佳化' target='_blank'>Search Engine Optimization(SEO)</a>，於是我開始進行對天氣地圖作品的修改。</p>
          <p>有鑒於我近期與 <a href='https://aws.amazon.com/tw/s3/' target='_blank'>Amazon S3</a> 變得比較熟了，所以把腦筋動到了 S3 上，因為看 S3 有<b>安全</b>、<b>耐久</b>、<b>可高度擴展</b>的<a href='https://aws.amazon.com/tw/what-is-cloud-storage/' target='_blank'>雲端儲存</a>特性，索性就以 S3 為主 EC2 為輔，不用資料庫，而是將資料轉乘 Json 的格式，再用資料夾結構的放置，通通上傳至 S3 上！如此一來前端所有資源從原本的向 EC2 索取，變成改從 S3 上取得，大大地減低天氣的 Request 數量，API Request 不說，光圖片、靜態檔案就少了很多流量，同時資料庫也減輕一部份的負擔！唯一會需要後端的部分，就只剩下固定時間更新資料的後端處理部分，而這部分也只會耗費不到 15MB 的記憶體，所以負擔不算大。</p>

          <div class='pics'>
            <div class='pictures n2'>
              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ($i = 1) . '&id=0';?>'>
                <img alt="上一版天氣地圖的架構 - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/01.png';?>" />
                <figcaption data-description='上一版的架構會造成大量的 Request 都往伺服器索取，所以會對伺服器造成一部份的負擔。'>上一版天氣地圖的架構</figcaption>
              </figure>
              
              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ++$i . '&id=0';?>'>
                <img alt="目前天氣地圖的架構 - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/02.png';?>" />
                <figcaption data-description='因為 Amazon S3 安全、耐久、可高度擴展的雲端儲存特性，於是將架構調整成以 S3 為主 EC2 為輔的架構，以減緩 EC2 伺服器的負擔。'>目前天氣地圖的架構</figcaption>
              </figure>

            </div>
          </div>
          <br/>
          <br/>
          <br/>
          <p>後端處理的關鍵程式是 <b>/cmd/put.php</b> 這隻，基本上流程是 定義基本常數、取得最新的天氣資訊、寫入 API Json 檔案、產生相關的 HTML 檔案、列出 S3 上檔案、比對新舊檔案、刪除、上傳 S3、完成。其中在產生 HTML、JavaScript 時，會進行 minify 與 uglify，css 則是使用了 Compass 編譯 Scss，最後再依個別 HTML Merge css、JavaScript，並以 <a href='https://zh.wikipedia.org/zh-tw/MD5' target='_blank'>MD5</a> 內容後為檔名（以 md5 檔名取代版本號，當內容有更新時會有不同新檔名，以防止前端瀏覽器快取問題，細節可參考<a href='http://www.infoq.com/cn/articles/front-end-engineering-and-performance-optimization-part1' target='_blank'>此篇</a>），如此一來便可以加強前端傳輸效能與壓縮檔案大小！</p>
          <p>前端部分因為會隨著天氣更新而產生新的 HTML 檔案，這部份剛好可以針對此架構加強 SEO 的調整優化，當然大家都知道的 sitemap、robots.txt 都有一定會有，但這次還加入部分的 <a href='https://schema.org/' target='_blank'>schema</a>、<a href='https://www.ampproject.org/' target='_blank'>Google AMP</a>、<a href='http://json-ld.org/' target='_blank'>JSON-LD</a> 的結構，這些步驟都是去餵搜尋引擎想吃的菜，好盡可能達到所有可能的曝光與分享！原本上一版有使用 <a href='https://developer.mozilla.org/zh-TW/docs/Using_geolocation' target='_blank'>navigator.geolocation</a> 物件取得前端 GPS 位置以增加更多的趣味性，但因為隨著 Chrome 的更新，要使用 navigator.geolocation 則必須使用 <a href='https://zh.wikipedia.org/wiki/超文本传输安全协议' target='_blank'>https</a> 的協定（詳情可看<a href='https://developers.google.com/web/fundamentals/native-hardware/user-location/obtain-location' target='_blank'>此篇</a>），所以這功能在 Chrome 上就無法使用，不過其他瀏覽器應該還可以用！</p>
          <p>前端功能中有使用到 <a href='https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage' target='_blank'>LocalStorage</a> 作為暫存的機制，利用這項前端瀏覽器的功能，我將它拿來記錄使用者瀏覽過的鄉鎮，同時也可以拿來做為收藏鄉鎮的功能。在地圖上更可以藉由讀取 LocalStorage 來實作記錄上次的地圖位置，而上一段所提到的 navigator.geolocation 取得客戶端 GPS 位置，也可以利用 LocalStorage 來實作 Cache 的機制，而這些功能程式碼我都放置在 <b>/weather/js/public.js</b> 內，各位若有興趣歡迎<a href='https://github.com/comdan66/weather/blob/master/js/public.js' target='_blank'>點開</a>來看喔！</p>

          <div class='pics'>
            <div class='pictures n2'>
              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ++$i . '&id=0';?>'>
                <img alt="主程式架構 - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/10.png';?>" />
                <figcaption data-description='藉由 Step 物件，分步驟地將天氣更新，寫入 API，產生 HTML，上傳 S3，達成部署更新天氣的步驟！'>主程式架構</figcaption>
              </figure>
              
              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ++$i . '&id=0';?>'>
                <img alt="整體流程，編譯 API、HTML，上傳 S3 - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/12.png';?>" />
                <figcaption data-description='基本上流程是 定義基本常數、取得最新的天氣資訊、寫入 API Json 檔案、產生相關的 HTML 檔案、列出 S3 上檔案、比對新舊檔案、刪除、上傳 S3、完成。'>整體流程，編譯 API、HTML，上傳 S3</figcaption>
              </figure>

            </div>
          </div>
          <br/>
          <br/>
          <br/>


          <p>此新版的天氣地圖除了使用中央氣象局的資訊外，此次也使用 <a href='https://zh.wikipedia.org/wiki/维基百科' target='_blank'>維基百科(wikipedia)</a> 的 <a href='https://www.mediawiki.org/wiki/API:Main_page' target='_blank'>API</a> 將各個鄉鎮地方的簡介、歷史資訊下載下來，並且整理分類，再產生 HTML 的鄉鎮內頁時一併加入簡介，讓整體網站功能更加多元，在瀏覽全台各地時，可以更加的對各個地方有所認識！而各個鄉鎮的照片，則是利用 Google Maps 提供的街景服務，取得該鄉鎮的隨機街景截圖，若是取得失敗則使用 Google Maps 的截圖。</p>
          <p>程式裡面會使用到上傳 S3 的功能，所以在執行時特別設計成 Cli 執行，配合下參數的方式，所以可以上傳至不同的 S3 Bucket。部署至 S3 的方式是進入專案 <b>/cmd/</b> 目錄內，下指令 <span class='path'>php put.php -b {Bucket Name} -a {Access Key} -s {Secret Key}</span> 即可按照步驟做更新、上傳的流程，-b 代表要上傳的 S3 Bucket 名稱，-a 是 Access Key，-s 是 Secret Key，若是不想上傳的話則有 代表 upload 的 -u 參數可決定是否上傳，若是不想上傳則加入 <span class='path'>-u 0</span> 的參數即可，而 -m 則代表 minify，-d 為是否更新天氣；-u、-m、-d 的預設值皆為 1。</p>
          <p>另外在 cmd 目錄內還有兩隻 php 檔案，分別是 init.php、clean.php，因為專案內有很多資料夾或者檔案是被 Git Ignore 的，所以需要 init.php 來幫你建立這些檔案、目錄，一樣進入專案 <b>/cmd/</b> 目錄內，下指令 <span class='path'>php init.php</span> 後即可產生所需的目錄與檔案！而 clean.php 是用來清除被 Git Ignore 的目錄與檔案，執行方式與 init.php 相同喔！</p>

          <div class='pics'>
            <div class='pictures n3'>

              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ++$i . '&id=0';?>'>
                <img alt="使用 LocalStorage 實作前端功能 - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/07.png';?>" />
                <figcaption data-description='搭配 LocalStorage 實作追蹤天氣、紀錄瀏覽、記錄上次地圖位置.. 等功能。'>使用 LocalStorage 實作前端功能</figcaption>
              </figure>

              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ++$i . '&id=0';?>'>
                <img alt="編譯完後的各個鄉鎮內頁 HTML - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/05.png';?>" />
                <figcaption data-description='編譯完後的各個鄉鎮內頁 HTML'>編譯完後的各個鄉鎮內頁 HTML</figcaption>
              </figure>
              
              <figure href='<?php echo URL_README . '#&gid=1&pid=' . ++$i . '&id=0';?>'>
                <img alt="將整理過後的 Json 一起上傳 S3，變成公開的 API - <?php echo ALT;?>" src="<?php echo URL . 'img/readme/06.png';?>" />
                <figcaption data-description='將整理過後的 Json 一起上傳 S3，變成公開的 API，同時也提供 apiDoc，如此一來大家就可以一起使用我的資源，取之於網路 分享於網路，所以在我還沒有關閉我的服務之前，大家都可以使用喔！'>將整理過後的 Json 一起上傳 S3，變成公開的 API</figcaption>
              </figure>

            </div>
          </div>
          <br/>
          <br/>
          <br/>
          <p>最後！既然做成 S3 架構也有了 API 服務，既然取之於網路，那就一樣分享於網路，所幸我就連 <a href='<?php echo URL_API_DOC;?>' target='_blank'>apiDoc</a> 一起建立，如此一來大家就可以一起使用我的資源，在我還沒有關閉我的服務之前，大家都可以使用喔！以上就是我的天氣地圖 Weather Maps 的架構改版心得，若大家想看更多有關於我的作品，歡迎至<a href='<?php echo OA_URL;?>' target='_blank'>我的個人網頁</a>逛逛！然後各位若喜歡此專案的話，可以到我的 <a href='https://github.com/comdan66/weather' class='github' target='_blank'><i class='icon-g'></i> GitHub</a> 上幫我按個喜歡喔！</p>
        </section>
      </article>

      <article>
        <header>
          <h2>重點整理</h2>
          <span>懶人簡介 • 關鍵字</span>
        </header>
        <section>
          <ul>
            <li>藉由 <a href='https://developers.google.com/maps/documentation/javascript/' target='_blank'>Google Maps JavaScript API v3</a> 的地圖服務，以及中央氣象局網站的天氣預報所實作的天氣地圖！</li>
            <li>基本上是利用 Google Maps API 的 <a href='https://developers.google.com/maps/documentation/javascript/tutorial' target='_blank'>Maps</a> 以及 <a href='https://developers.google.com/maps/documentation/javascript/markers' target='_blank'>Marker</a> 設計！</li>
            <li>附加使用 <a href='http://google-maps-utility-library-v3.googlecode.com/svn/tags/markerwithlabel/1.1.8/docs/examples.html' target='_blank'>MarkerWithLabel</a> 在地圖上顯示各區域的天氣圖示，以加強 Google Maps 上的圖像表現。</li>
            <li>參考<a href='http://www.cwb.gov.tw/m/' target='_blank'>中央氣象局手機版本網頁</a>所提供的資料建置。</li>
            <li>全網站使用<a href='http://www.ibest.tw/page01.php' target='_blank'>響應式網站設計(RWD)</a>，所以手機也可以正常瀏覽。</li>
            <li>搭配 <a href='https://developer.mozilla.org/en-US/docs/Web/API/Window/localStorage' target='_blank'>LocalStorage</a> 實作<a href='URL_INDEX' target='_blank'>追蹤天氣</a>、紀錄瀏覽、<a href='URL_MAPS' target='_blank'>記錄上次地圖位置</a>.. 等功能。</li>
            <li>網站內容使用 <a href='https://developer.mozilla.org/zh-TW/docs/Using_geolocation' target='_blank'>navigator.geolocation</a> 物件取得前端 GPS 位置，新版 Chrome 必須是 Https 下才可使用。</li>
            <li><a href='URL_INDEX' target='_blank'>搜尋功能</a>則使用 <a href='https://developers.google.com/maps/documentation/geocoding/intro' target='_blank'>Google Maps Geocoding API</a> 將住址搜尋更加準確化。</li>
            <li>使用 <a href='https://developers.google.com/maps/documentation/staticmaps/intro' target='_blank'>Static Maps API</a> 以及 <a href='https://developers.google.com/maps/documentation/streetview/intro' target='_blank'>Street View Image API</a> 所提供的服務，擷取地點的地圖、街景截圖。</li>
            <li>感謝 <a href='http://www.zeusdesign.com.tw/' target='_blank'>宙思設計</a> 提供的可愛天氣小圖示，感謝 <a href='https://www.flickr.com/photos/lifegoseon' target='_blank'>Monkeyy78</a> 提供景點照片。</li>
            <li>前端開發工具主要使用 <a href='http://gulpjs.com/' target='_blank'>Gulp</a>、<a href='http://compass-style.org/' target='_blank'>Compass</a> 加強開發效率，並使用 <a href='https://zh.wikipedia.org/zh-tw/JavaScript' target='_blank'>JavaScript</a>、<a href='https://jquery.com/' target='_blank'>jQuery</a> 實作前端功能效果。</li>
            <li>專案整體主要框架使用 <a href='https://github.com/comdan66/oaf2e' target='_blank'>OAF2E</a> <a href='https://github.com/comdan66/oaf2e/tree/version/3.3' target='_blank'>v3.3</a>。</li>
            <li>後端語言為 <a href='http://php.net/' target='_blank'>php</a>，關鍵程式碼在 <span class='path'>/cmd/put.php</span>，GitHub 位置在<a href='https://github.com/comdan66/weather/blob/master/cmd/put.php' target='_blank'>這裡</a>。</li>
            <li>系統使用 <a href='https://aws.amazon.com/tw/s3/' target='_blank'>Amazon S3</a> 服務，利用 php 執行完更新後，在將相關網頁檔案部署至 S3 服務。</li>
            <li>JavaScript uglify 使用 <a href='https://github.com/rgrove/jsmin-php/' target='_blank'>jsmin-php</a>，css、HTML minify 則是使用 <a href='http://php.net/manual/en/function.preg-replace.php' target='_blank'>preg_replace</a> 函式。</li>
            <li>天氣概況一併整理成公開 <a href='https://zh.wikipedia.org/wiki/JSON' target='_blank'>json</a> 格式的 <a href='<?php echo URL_API_DOC;?>' target='_blank'>API</a> 分享，並且使用 <a href='http://apidocjs.com/' target='_blank'>apiDoc</a> 說明使用方式。</li>
            <li>加入部分的 <a href='https://schema.org/' target='_blank'>schema</a>、<a href='https://www.ampproject.org/docs/get_started/about-amp.html' target='_blank'>Google AMP</a>、<a href='http://json-ld.org/' target='_blank'>JSON-LD</a> 的結構，並且加強 <a href='https://zh.wikipedia.org/zh-tw/%E6%90%9C%E5%B0%8B%E5%BC%95%E6%93%8E%E6%9C%80%E4%BD%B3%E5%8C%96' target='_blank'>SEO</a> 的優化。</li>
            <li>使用<a href='https://zh.wikipedia.org/wiki/维基百科' target='_blank'>維基百科(wikipedia)</a> 的 <a href='https://www.mediawiki.org/wiki/API:Main_page' target='_blank'>API</a> 將各個鄉鎮地方的簡介，讓整體網站功能更加多元。</li>
          </ul>
        </section>
      </article>
    </div>

    <?php echo $_aside;?>
    <?php echo $_footer;?>
    <div id='fb-root'></div>

  </body>
</html>
