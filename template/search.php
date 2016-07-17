<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />
    
    <title><?php echo $title = '快速搜尋地方天氣' . ' - ' . TITLE;?></title>

    <meta name="robots" content="index,follow" />

    <meta name="keywords" content="<?php echo '搜尋天氣, ' . KEYWORDS;?>" />
    <meta name="description" content="<?php echo mb_strimwidth ($description = '使用關鍵快速查詢台灣的天氣，可以藉由地名、住址、郵遞區號..等方式，讓您馬上的可以找到準確的位置，並且以 Google Maps 的方式將地方天氣概況呈現出來，同時也可以知道鄰近鄉鎮的天氣。', 0, 150, '…','UTF-8');?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo URL_SEARCH;?>" />
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
    <meta property="og:image" content="<?php echo URL_OG_SEARCH;?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/<?php echo pathinfo (URL_OG_SEARCH, PATHINFO_EXTENSION);?>" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="630" />

    <link rel="canonical" href="<?php echo URL_SEARCH;?>" />
    <link rel="alternate" href="<?php echo URL_SEARCH;?>" hreflang="zh-Hant" />

    <link href='https://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<?php foreach (Min::css ('/css/public' . CSS, '/css/maps' . CSS, '/css/search' . CSS) as $path) { ?>
        <link href="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" rel="stylesheet" type="text/css" />
<?php } ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwwd8RtByLxQJcAnt8JMzznijiTPnelyA&v=3.exp&sensor=false&language=zh-TW" language="javascript" type="text/javascript" ></script>
<?php foreach (Min::js ('/js/public' . JS, '/asset/maps' . JS, '/js/search' . JS) as $path) { ?>
        <script src="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" language="javascript" type="text/javascript" ></script>
<?php }?>
    <script type="application/ld+json">
<?php echo json_encode (array (
        '@context' => 'http://schema.org', '@type' => 'Article',
        'mainEntityOfPage' => array (
          '@type' => 'WebPage',
          '@id' => URL_SEARCH),
        'headline' => $title,
        'image' => array ('@type' => 'ImageObject', 'url' => URL_OG_SEARCH, 'height' => 630, 'width' => 1200),
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
      <a itemprop="url" href='<?php echo URL_SEARCH;?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", '快速搜尋');?></span></a>
    </div>

    <div id='container'>
      
      <article>
        <header>
          <h1>
            <a href='<?php echo URL_SEARCH;?>'>
              <span>Weather Maps</span>
              <span>天氣地圖 • 快速搜尋 • v2.0</span>
            </a>
          </h1>
          <span><input type='text' id='search' value='' placeholder='輸入關鍵字搜尋地方天氣吧！' autofocus/><i class='icon-h'></i></span>
          <div id='weather' class='weather'></div>
        </header>

        <section id='towns'>
    <?php foreach ($suggests as $suggest) { ?>
            <a href='<?php echo $suggest['l'];?>'><?php echo $suggest['n'];?></a>
    <?php } ?>
        </section>

        <div id='maps' data-position='<?php echo json_encode (null);?>'></div>
      </article>
    </div>

    <?php echo $_aside;?>
    <?php echo $_footer;?>

    <div id='fb-root'></div>

  </body>
</html>
