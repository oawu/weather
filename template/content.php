<!DOCTYPE html>
<html lang="tw">
  <head>
    <meta http-equiv="Content-Language" content="zh-tw" />
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui" />

    <title><?php echo $title = $town['cate']['name'] . $town['name'] . '目前的天氣概況' . ' - ' . TITLE;?></title>

    <meta name="robots" content="index,follow" />

    <meta name="keywords" content="<?php echo $town['cate']['name'] . $town['name'] . ',總覽全台灣, ' . KEYWORDS;?>" />
    <meta name="description" content="<?php echo mb_strimwidth ($description = $town['cate']['name'] . $town['name'] . '目前的天氣概況，目前' . $town['weather']['desc'] . '，氣溫：' . $town['weather']['temperature'] . '°c，濕度：' . $town['weather']['humidity'] . '%，降雨量：' . $town['weather']['rainfall'] . 'mm，' . ($town['specials'] ? '目前已經針對' . $town['cate']['name'] . $town['name'] . '發佈' . ($headers = implode (', ', column_array ($town['specials'], 'title'))) : '目前沒有任何特別預報') . '。' . ($town['infos']['地方簡介'] ? implode ('', $town['infos']['地方簡介']['descs']) : ''), 0, 150, '…','UTF-8');?>" />
    <meta property="og:site_name" content="<?php echo $title;?>" />
    <meta property="og:url" content="<?php echo $town['link'];?>" />
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
    <meta property="og:image" content="<?php echo $town['img'];?>" alt="<?php echo $title;?>" />
    <meta property="og:image:type" tag="larger" content="image/<?php echo ($pathinfo = pathinfo ($town['img'])) && $pathinfo['extension'] ? $pathinfo['extension'] : 'jpg';?>" />
    <meta property="og:image:width" content="<?php echo $town['img_dimension']['width'];?>" />
    <meta property="og:image:height" content="<?php echo $town['img_dimension']['height'];?>" />

    <link rel="canonical" href="<?php echo $town['link'];?>" />
    <link rel="alternate" href="<?php echo $town['link'];?>" hreflang="zh-Hant" />

    <link href='https://fonts.googleapis.com/css?family=Comfortaa:400,300,700' rel='stylesheet' type='text/css'>
<?php foreach (Min::css ('/css/public' . CSS, '/css/maps' . CSS, '/css/content' . CSS) as $path) { ?>
        <link href="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" rel="stylesheet" type="text/css" />
<?php } ?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDwwd8RtByLxQJcAnt8JMzznijiTPnelyA&v=3.exp&sensor=false&language=zh-TW" language="javascript" type="text/javascript" ></script>
<?php foreach (Min::js ('/js/public' . JS, '/asset/maps' . JS, '/js/content' . JS) as $path) { ?>
        <script src="<?php echo PROTOCOL . BUCKET . '/' . NAME . $path;?>" language="javascript" type="text/javascript" ></script>
<?php }?>
    <script type="application/ld+json">
<?php echo json_encode (array (
        '@context' => 'http://schema.org', '@type' => 'Article',
        'mainEntityOfPage' => array (
          '@type' => 'WebPage',
          '@id' => $town['link']),
        'headline' => $town['cate']['name'] . $town['name'],
        'image' => array ('@type' => 'ImageObject', 'url' => $town['img'], 'height' => $town['img_dimension']['height'] < 800 ? 800 : $town['img_dimension']['height'], 'width' => $town['img_dimension']['width'] < 800 ? 800 : $town['img_dimension']['width']),
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
      <a itemprop="url" href='<?php echo URL_ALL . '#' . url_encode ($town['cate']['name']);?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", $town['cate']['name']);?></span></a>
    </div>

    <div class='_scope' itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
      <a itemprop="url" href='<?php echo $town['link'];?>'><span itemprop="title"><?php echo preg_replace ("/\.+/", "", $town['name']);?></span></a>
    </div>

    <div id='container'>
      <div class='top'>
        <div>
          <div>
            <button id='change'></button>
            <div id='maps' data-position='<?php echo json_encode ($position);?>'></div>
            <div id='view'></div>
          </div>
          <div>
            <article>
              <header class='weather'>
                <h1><?php echo $town['name'];?></h1>
                <figure data-temperature="<?php echo $town['weather']['temperature'];?>°c">
                  <img src="<?php echo $town['weather']['img'];?>" />
                  <figcaption><?php echo $town['name'];?></figcaption>
                </figure>
              </header>

              <section>
                <header class='like'>
                  <h2><?php echo $town['weather']['desc'];?></h2>
                  <div class='fb-like' data-href='<?php echo $town['link'];?>' data-send='false' data-layout='button_count' data-action='like' data-show-faces='false' data-share='true'></div>
                </header>
                
                <div>
                  <span><b>溫度：</b><?php echo $town['weather']['temperature'];?>°c</span>
                  <span><b>濕度：</b><?php echo $town['weather']['humidity'];?>%</span>
                  <span><b>雨量：</b><?php echo $town['weather']['rainfall'];?>mm</span>
                  <span><b>日出時間：</b><?php echo $town['weather']['sunrise'];?></span>
                  <span><b>日落時間：</b><?php echo $town['weather']['sunset'];?></span>
                  <span><b>更新：</b><time datetime="<?php echo $town['weather']['at'];?>"><?php echo $town['weather']['at'];?></time></span>
                </div>

              </section>

              <section<?php echo $town['specials'] ? '' : ' class="nh"';?>>
          <?php if ($town['specials'] && $headers) { ?>
                  <header><?php echo $headers;?></header>
          <?php } ?>
                <span>
            <?php if ($imgs = column_array ($town['specials'], 'img')) {
                    foreach ($imgs as $img) { ?>
                      <img src='<?php echo $img;?>'>
              <?php }
                  } ?>
                  <p><?php echo $town['specials'] ? implode (', ', array_filter (column_array ($town['specials'], 'desc'))) : '目前沒有任何特別預報。';?></p>
                </span>
                <?php if ($town['specials']) { ?>
                  <time datetime='<?php echo $town['specials'][0]['at'];?>'><?php echo $town['specials'][0]['at'];?></time>
                <?php } ?>
              </section>

            </article>
          </div>
        </div>
      </div>
      <article class='line_chart n<?php echo count($town['histories']);?>'>
        <header>溫度、濕度、雨量變化</header>
        <div>
    <?php foreach ($town['histories'] as $history) { ?>
            <div>
              <div data-percent='<?php echo $history['temperature']['percent'];?>' title='<?php echo $history['temperature']['value'];?>°c'></div>
              <div data-percent='<?php echo $history['humidity']['percent'];?>' title='<?php echo $history['humidity']['value'];?>%'></div>
              <div data-percent='<?php echo $history['rainfall']['percent'];?>' title='<?php echo $history['rainfall']['value'];?>mm'></div>
            </div>
    <?php } ?>
        </div>
        <div>
    <?php foreach ($town['histories'] as $history) { ?>
            <div title="<?php echo $history['at'];?>"><?php echo date ('H:i', strtotime ($history['at']));?></div>
    <?php } ?>
        </div>
        <span>
          <div>溫度</div>
          <div>濕度</div>
          <div>雨量</div>
        </span>
      </article>
      
<?php foreach ($town['infos'] as $title => $info) { ?>
        <article>
          <header><?php echo $title;?></header>
          <section>
      <?php foreach ($info['descs'] as $desc) { ?>
              <p><?php echo enable_link ($desc);?></p>
      <?php } ?>
          </section>
    <?php if ($info['sources']) { ?>
            <ul>
        <?php foreach ($info['sources'] as $title => $source) { ?>
                <li><a href="<?php echo $source;?>" target='_blank'><?php echo $title . ($title == '維基百科' ? ' ' . rawurldecode (pathinfo ($source, PATHINFO_FILENAME)) : '');?></a><span><a href="<?php echo $source;?>" target='_blank'><?php echo rawurldecode ($source);?></a></span></li>
        <?php } ?>
            </ul>
    <?php } ?>
        </article>
<?php } ?>

      <article class='more'>
        <header>更多地方天氣</header>
        <div>
    <?php foreach ($mores as $more) { ?>
            <a href='<?php echo $more['link'];?>'>
              <figure class='_i' data-id='<?php echo $more['id'];?>' data-temperature='<?php echo $more['weather']['temperature'];?>°c' data-describe='<?php echo $more['weather']['desc'];?>' data-humidity='<?php echo $more['weather']['humidity'];?>%' data-rainfall='<?php echo $more['weather']['rainfall'];?>mm'>
                <img src="<?php echo $more['img'];?>" />
                <figcaption class='<?php echo $more['icons'][0];?>'><?php echo $more['name'];?></figcaption>
              </figure>
            </a>
    <?php } ?>
        </div>
      </article>
    </div>
    <div id='button' data-id='<?php echo $town['id'];?>'></div>

    <?php echo $_aside;?>
    <?php echo $_footer;?>
    <div id='fb-root'></div>
    
  </body>
</html>
