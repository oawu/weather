<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

    <title><?php echo $title = '總覽全台灣' . ' - ' . TITLE;?></title>

    <meta name="robots" content="index,follow" />

    <meta name="keywords" content="<?php echo '總覽全台灣, ' . KEYWORDS;?>" />
    <meta name="description" content="<?php echo mb_strimwidth ($description = '總覽全台灣最新天氣概況，藉由 Google Maps API 的地圖服務，以及中央氣象局的天氣預報，讓你快速輕鬆的查詢台灣 368 個鄉鎮的天氣概況！使用中央氣象局網站公告的氣象預報資訊作為資料來源，平均每 30 分鐘更新最新天氣概況，分別顯示溫濕度、日出落時間、特別預報..等功能。', 0, 150, '…','UTF-8');?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo URL_ALL;?>" />
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
    <meta property="og:image" content="<?php echo URL_OG_ALL;?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/<?php echo pathinfo (URL_OG_ALL, PATHINFO_EXTENSION);?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <link rel="canonical" href="<?php echo URL_ALL;?>" />
    <link rel="alternate" href="<?php echo URL_ALL;?>" hreflang="zh-Hant" />

    <script type="application/ld+json">
<?php $items = array (); foreach ($cates as $cate) foreach ($cate['towns'] as $i => $town) array_push ($items, array ('@type' => 'ListItem', 'position' => $i + 1, 'item' => array ('@id' => $town['link'], 'url' => $town['link'], 'name' => $town['cate']['name'] . $town['name'])));
echo json_encode (array (
        '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => $items
      ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
    </script>

    <link href='https://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<?php foreach (Min::css ('/css/public' . CSS, '/css/all' . CSS) as $path) { ?>
        <link href="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" rel="stylesheet" type="text/css" />
<?php } ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwwd8RtByLxQJcAnt8JMzznijiTPnelyA&v=3.exp&sensor=false&language=zh-TW" language="javascript" type="text/javascript" ></script>
<?php foreach (Min::js ('/js/public' . JS, '/js/all' . JS) as $path) { ?>
        <script src="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" language="javascript" type="text/javascript" ></script>
<?php }?>

  </head>
  <body lang="zh-tw">
    
    <?php echo $_header;?>

    <div class='_scope' itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
      <a itemprop="url" href='<?php echo URL_ALL;?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", '總覽全台');?></span></a>
    </div>

    <div id='container'>
<?php foreach ($cates as $cate) { ?>
        <article>
          <header title='<?php echo $cate['name'];?>'><a href='<?php echo URL_ALL . '#' . url_encode ($cate['name']);?>'><?php echo $cate['name'];?></a> (<?php echo count ($cate['towns']);?>)</header>
          <section class='n<?php echo count ($cate['towns']);?>'>
      <?php foreach ($cate['towns'] as $town) { ?>
              <a href='<?php echo $town['link'];?>'>
                <figure class='_i' data-id='<?php echo $town['id'];?>' data-temperature='<?php echo $town['weather']['temperature'];?>°c' data-describe='<?php echo $town['weather']['desc'];?>' data-humidity='<?php echo $town['weather']['humidity'];?>%' data-rainfall='<?php echo $town['weather']['rainfall'];?>mm'>
                  <img src="<?php echo $town['img'];?>" />
                  <figcaption class='<?php echo $town['icons'][0];?>'><?php echo $town['name'];?></figcaption>
                </figure>
              </a>
      <?php } ?>
          </section>
        </article>
<?php } ?>
    </div>

    <div id='top'></div>

    <?php echo $_aside;?>
    <?php echo $_footer;?>

    <div id='fb-root'></div>

  </body>
</html>
