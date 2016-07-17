<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

    <title><?php echo $title = TITLE;?></title>

    <meta name="robots" content="index,follow" />

    <meta name="keywords" content="<?php echo KEYWORDS;?>" />
    <meta name="description" content="<?php echo mb_strimwidth ($description = '想查詢每個地方的天氣嗎？藉由 Google Maps API 的地圖服務，以及中央氣象局的天氣預報，讓你快速輕鬆的查詢台灣 368 個鄉鎮的天氣概況！使用中央氣象局網站公告的氣象預報資訊作為資料來源，平均每 30 分鐘更新最新天氣概況，分別顯示溫濕度、日出落時間、特別預報..等功能。', 0, 150, '…','UTF-8');?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo URL_INDEX;?>" />
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
    <meta property="og:image" content="<?php echo URL_OG_INDEX;?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/<?php echo pathinfo (URL_OG_INDEX, PATHINFO_EXTENSION);?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <link rel="canonical" href="<?php echo URL_INDEX;?>" />
    <link rel="alternate" href="<?php echo URL_INDEX;?>" hreflang="zh-Hant" />

    <link href='https://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<?php foreach (Min::css  ('/css/public' . CSS, '/css/index' . CSS) as $path) { ?>
        <link href="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" rel="stylesheet" type="text/css" />
<?php } ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwwd8RtByLxQJcAnt8JMzznijiTPnelyA&v=3.exp&sensor=false&language=zh-TW" language="javascript" type="text/javascript" ></script>
<?php foreach (Min::js  ('/js/public' . JS, '/js/index' . JS) as $path) { ?>
        <script src="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" language="javascript" type="text/javascript" ></script>
<?php }?>
    <script type="application/ld+json">
<?php echo json_encode (array (
        '@context' => 'http://schema.org', '@type' => 'Article',
        'mainEntityOfPage' => array (
          '@type' => 'WebPage',
          '@id' => URL_INDEX),
        'headline' => $title,
        'image' => array ('@type' => 'ImageObject', 'url' => URL_OG_INDEX, 'height' => 630, 'width' => 1200),
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
      <a itemprop="url" href='<?php echo URL_INDEX;?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", '首頁');?></span></a>
    </div>

    <div id='container'>

      <div class='towns'>
  <?php foreach ($cards as $card) {
        $town = $card['town']; ?>
          <div>
            <a href='<?php echo $town['link'];?>' class='_i'>
              <img src='<?php echo $town['img'];?>' />
              <div><?php echo $card['title'] . ' - ' . $town['name'];?></div>
            </a>
            <a href='<?php echo $town['link'];?>'>
              <figure data-temperature='<?php echo $town['weather']['temperature'];?>'>
                <img src="<?php echo $town['weather']['img'];?>" />
                <figcaption><?php echo $town['name'];?></figcaption>
              </figure>
              <div>
                <span><?php echo $town['weather']['desc'];?></span>
                <div><span><p>濕度</p><p>：</p><p><?php echo $town['weather']['humidity'];?>%</p></span></div>
                <div><span><p>雨量</p><p>：</p><p><?php echo $town['weather']['rainfall'];?>mm</p></span></div>
                <time datetime='<?php echo $town['weather']['at'];?>'><?php echo $town['weather']['at'];?><time>
              </div>
            </a>
          </div>  
  <?php } ?>
        
        <div id='add'>
          <i></i><i></i><i></i><i></i>
          <span>新增關注追蹤地區！</span>
        </div>

      </div>

      <div class='questions'>
  <?php foreach ($questions as $question) { ?>
          <article>
            <header><?php echo $question['title'];?></header>
            <section>
              <p><?php echo $question['desc'];?></p>
            </section>
            <a href='<?php echo $question['town']['link'];?>'><?php echo $question['text'];?></a>
          </article>
  <?php }?>
      </div>

<?php if ($specials) { ?>
        <div class='specials<?php echo count ($specials) < 3 ? count ($specials) < 2 ? ' n1' : ' n2' : '';?>'>
    <?php foreach ($specials as $special) { ?>
            <article>
              <header><?php echo $special['special']['title'];?> - <?php echo $special['special']['status'];?></header>

              <section class='desc'>
                <p><span><img src='<?php echo $special['special']['img'];?>'></span><?php echo $special['special']['desc'];?></p>
                <p>以下是發佈大雨特報的縣市鄉鎮：</p>
              </section>

        <?php foreach ($special['cates'] as $cate) { ?>
                <section class='cate'>
                  <header><a href='<?php echo URL_ALL . '#' . url_encode ($cate['name']);?>'><?php echo $cate['name'];?></a></header>
            <?php foreach ($cate['towns'] as $town) { ?>
                    <a href='<?php echo $town['link'];?>'><?php echo $town['name'];?></a>
            <?php } ?>
                </section>
        <?php } ?>
            
              <time datetime='<?php echo $special['special']['at'];?>'><?php echo $special['special']['at'];?></time>

            </article>
    <?php } ?>
        </div>
<?php }?>
    </div>

    <?php echo $_aside;?>
    <?php echo $_footer;?>

    <div id='weathers'>
<?php foreach ($hiddens as $hidden) { ?>
        <a href='<?php echo $hidden['l'];?>' data-val='<?php echo json_encode ($hidden);?>' data-id='<?php echo $hidden['i'];?>' data-code='<?php echo $hidden['p'];?>' title='<?php echo $hidden['c'] . ' ' . $hidden['n'];?>'><?php echo $hidden['c'] . ' ' . $hidden['n'];?></a>
<?php } ?>
    </div>

    <div id='fb-root'></div>

  </body>
</html>
