<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

    <title><?php echo $title = '地圖模式' . ' - ' . TITLE;?></title>

    <meta name="robots" content="index,follow" />

    <meta name="keywords" content="<?php echo '地圖天氣, ' . KEYWORDS;?>" />
    <meta name="description" content="<?php echo mb_strimwidth ($description = '藉由 Google Maps API 的地圖服務，再加上中央氣象局的天氣預報，讓你快速輕鬆的在台灣地圖上查詢全台 368 個鄉鎮的天氣概況！', 0, 150, '…','UTF-8');?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo URL_MAPS;?>" />
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
    <meta property="og:image" content="<?php echo URL_OG_MAPS;?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/<?php echo pathinfo (URL_OG_MAPS, PATHINFO_EXTENSION);?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <link rel="canonical" href="<?php echo URL_MAPS;?>" />
    <link rel="alternate" href="<?php echo URL_MAPS;?>" hreflang="zh-Hant" />

    <link href='https://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<?php foreach (Min::css ('/css/public' . CSS, '/css/maps' . CSS) as $path) { ?>
        <link href="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" rel="stylesheet" type="text/css" />
<?php } ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwwd8RtByLxQJcAnt8JMzznijiTPnelyA&v=3.exp&sensor=false&language=zh-TW" language="javascript" type="text/javascript" ></script>
<?php foreach (Min::js ('/js/public' . JS, '/asset/maps' . JS) as $path) { ?>
        <script src="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" language="javascript" type="text/javascript" ></script>
<?php }?>
    
    <script type="application/ld+json">
<?php $items = array (); foreach ($weathers as $i => $weather) array_push ($items, array ('@type' => 'ListItem', 'position' => $i + 1, 'item' => array ('@id' => $weather['l'], 'url' => $weather['l'], 'name' => $weather['c'] . $weather['n'])));
echo json_encode (array (
        '@context' => 'http://schema.org', '@type' => 'BreadcrumbList',
        'itemListElement' => $items
      ), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);?>
    </script>

  </head>
  <body lang="zh-tw">
    
    <?php echo $_header;?>

    <div class='_scope' itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
      <a itemprop="url" href='<?php echo URL_MAPS;?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", '地圖模式');?></span></a>
    </div>
    
    <div id='maps' data-position='<?php echo json_encode (null);?>'>
<?php foreach ($weathers as $weather) { ?>
        <a href='<?php echo $weather['l'];?>'><?php echo $weather['c'];?> <?php echo $weather['n'];?></a>
<?php } ?>
    </div>

    <?php echo $_aside;?>
    <div id='fb-root'></div>

  </body>
</html>
