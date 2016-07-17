<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Step {
  public static $cates = array ();
  public static $towns = array ();
  public static $datas = array ();
  public static $mapsData = array ();
  public static $uploadDirs = array ();
  public static $s3Files = array ();
  public static $localFiles = array ();

  public static $startTime;
  public static $nowSize;
  public static $size;
  public static $progress = array ();
  public static $maxs = array (
        'temperature' => array ('title' => '目前最高溫度', 'val' => 0, 'town' => array ()),
        'humidity'    => array ('title' => '目前最高濕度', 'val' => 0, 'town' => array ()),
        'rainfall'    => array ('title' => '目前最多雨量', 'val' => 0, 'town' => array ()),
      );
  public static $mins = array (
        'temperature' => array ('title' => '目前最低溫度', 'val' => 99999, 'town' => array ()),
        'humidity'    => array ('title' => '目前最低濕度', 'val' => 99999, 'town' => array ()),
        'rainfall'    => array ('title' => '目前最少雨量', 'val' => 99999, 'town' => array ()),
      );
  public static $specials = array ();
  public static $questions = array (
      '地名猜一猜' => array (),
      '台灣知多少' => array (),
      '野史傳說' => array (),
      '地方特色' => array (),
    );

  public static function progress ($str, $c = 0) {
    $isStr = !is_numeric ($c);
    if (!isset (Step::$progress[$str])) Step::$progress[$str] = array ('c' => is_numeric ($c) && $c ? $c : 1, 'i' => 0);
    else Step::$progress[$str]['i'] += 1;

    if (is_numeric ($c) && $c) Step::$progress[$str]['c'] = $c;
    Step::$progress[$str]['i'] = Step::$progress[$str]['i'] >= Step::$progress[$str]['c'] || $isStr ? Step::$progress[$str]['c'] : Step::$progress[$str]['i'];
    
    preg_match_all('/(?P<c>[\x{4e00}-\x{9fa5}])/u', $str . ($isStr ? $c : ''), $matches);
    Step::$size = memory_get_usage () > Step::$size ? memory_get_usage () : Step::$size;
    $size = Step::memoryUnit (Step::$size - Step::$nowSize);
    $show = sprintf (' ' . Step::color ('➜', 'W') . ' ' . Step::color ($str . '(' . Step::$progress[$str]['i'] . '/' . Step::$progress[$str]['c'] . ')', 'g') . " - % 3d%% " . ($isStr ? '- ' . Step::color ('完成！', 'C') : ''), Step::$progress[$str]['c'] ? ceil ((Step::$progress[$str]['i'] * 100) / Step::$progress[$str]['c']) : 100);
    echo sprintf ("\r% -" . (91 + count ($matches['c']) + ($isStr ? 12 : 0)) . "s" .  Step::color (sprintf ('% 7s', $size[0]), 'W') . ' ' . $size[1] . " " . ($isStr ? "\n" : ''), $show, 10);
  }
  public static function start () {
    Step::$startTime = microtime (true);
    echo "\n" . str_repeat ('=', 80) . "\n";
    echo ' ' . Step::color ('◎ 執行開始 ◎', 'P') . str_repeat (' ', 48) . '[' . Step::color ('OA S3 Tools v1.0', 'y') . "]\n";
  }
  public static function end () {
    echo str_repeat ('=', 80) . "\n";
    echo ' ' . Step::color ('◎ 執行結束 ◎', 'P') . "\n";
    echo str_repeat ('=', 80) . "\n";
  }
  public static function showUrl () {
    echo "\n";
    echo " " . Step::color ('➜', 'R') . " " . Step::color ('您的網址是', 'G') . "：" . Step::color (PROTOCOL . BUCKET . '/' . NAME . '/', 'W') . "\n\n";
    echo str_repeat ('=', 80) . "\n";
  }
  public static function memoryUnit ($size) {
    $units = array ('B','KB','MB','GB','TB','PB');
    return array (@round ($size / pow (1024, ($i = floor (log ($size, 1024)))), 2), $units[$i]);
  }
  public static function usage () {
    echo str_repeat ('=', 80) . "\n";
    $size = Step::memoryUnit (memory_get_usage ());
    echo ' ' . Step::color ('➜', 'W') . ' ' . Step::color ('使用記憶體：', 'R') . '' . Step::color ($size[0], 'W') . ' ' . $size[1] . "\n";
    echo str_repeat ('-', 80) . "\n";

    echo ' ' . Step::color ('➜', 'W') . ' ' . Step::color ('執行時間：', 'R') . '' . Step::color (round (microtime (true) - Step::$startTime, 4), 'W') . ' 秒' . "\n";
  }
  public static function setUploadDirs ($args = array ()) {
    Step::$uploadDirs = $args;
  }
  public static function newLine ($char, $str = '', $c = 0) {
    echo str_repeat ($char, 80) . "\n";
    Step::$nowSize = Step::$size = memory_get_usage ();
    if ($str) Step::progress ($str, $c);
  }
  public static function error ($str, $errors = array ()) {
    echo "\n" . str_repeat ('=', 80) . "\n";
    echo " " . Step::color ('➜', 'W') . ' ' . Step::color ('有發生錯誤！', 'r') . "\n";
    echo $errors ? str_repeat ('-', 80) . "\n" . implode ("\n" . str_repeat ('-', 80) . "\n", $errors) . "\n" : "";
    echo str_repeat ('=', 80) . "\n";
    exit ();
  }
  public static function init () {
    $cates = include_once (PATH_CMD_LIBS . 'towns' . PHP);
    Step::$cates = array_combine (column_array ($cates, 'id'), $cates);

    $towns = array ();
    foreach ($cates as $cate) foreach ($cate['towns'] as $town) array_push ($towns, array_merge ($town, array ('cate' => array ('id' => $cate['id'], 'name' => $cate['name']))));
    Step::$towns = array_combine (column_array ($towns, 'id'), $towns);

    $paths = array (PATH, PATH_TOWNS, PATH_ASSET, PATH_API, PATH_API_CATES, PATH_API_TOWNS, PATH_API_WEATHERS, PATH_IMG, PATH_IMG_WEATHERS, PATH_IMG_SPECIALS);

    Step::newLine ('-', '初始化環境與變數', count ($paths));

    if ($errors = array_filter (array_map (function ($path) {
        if (!file_exists ($path)) Step::mkdir777 ($path);
        Step::progress ('初始化環境與變數');
        return !(is_dir ($path) && is_writable ($path)) ? ' 目錄：' . $path : '';
      }, $paths))) Step::error ($errors);

    Step::progress ('初始化環境與變數', '完成！');
  }
  public static function writeUrlApi () {
    Step::newLine ('-', '更新 url API');

    if (!write_file (PATH_API . 'url' . JSON, json_encode (array ('img' => URL . 'img/weathers/' . IMG_TMEME . '/')))) Step::error ();

    Step::progress ('更新 url API', '完成！');
  }
  public static function writeAllApi () {
    Step::newLine ('-', '更新 all API');

    $all = array_values (array_map (function ($cate) { return array ('id' => $cate['id'], 'name' => $cate['name'], 'towns' => array_map (function ($town) { return array ('id' => $town['id'], 'name' => $town['name']); }, $cate['towns'])); }, Step::$cates));

    if (!write_file (PATH_API . 'all' . JSON, json_encode ($all))) Step::error ();

    Step::progress ('更新 all API', '完成！');
  }
  public static function writeCatesApi () {
    Step::newLine ('-', '更新 Cates API', count (Step::$cates));
    
    if ($errors = array_filter (array_map (function ($cate) {
      Step::progress ('更新 Cates API');
      return !write_file (PATH_API_CATES . $cate['id'] . JSON, json_encode ($cate)) ? ' 縣市：' . $cate['name'] : '';
    }, Step::$cates))) Step::error ($errors);

    Step::progress ('更新 Cates API', '完成！');
  }
  public static function writeTownsApi () {
    Step::newLine ('-', '更新 Towns API', count (Step::$towns));
    
    if ($errors = array_filter (array_map (function ($town) {
      unset ($town['cwb_id']);
      $town['img'] = town_img_info ($town)['img'];
      Step::progress ('更新 Towns API');
      return !write_file (PATH_API_TOWNS . $town['id'] . JSON, json_encode ($town)) ? ' 鄉鎮：' . $town['cate']['name'] . $town['name'] : '';
    }, Step::$towns))) Step::error ($errors);
    
    Step::progress ('更新 Towns API', '完成！');
  }
  public static function writeWeathersApi () {
    Step::newLine ('-', '更新全部鄉鎮天氣', count (Step::$towns));

    if ($errors = array_filter (array_map (function ($town) {
      Step::progress ('更新全部鄉鎮天氣');
      if (!UPDATE) return ($msg = '') ? ' 鄉鎮：' . $town['cate']['name'] . $town['name'] . ' 原因：' . $msg : '';
      return ($msg = Step::writeWeather ($town)) ? ' 鄉鎮：' . $town['cate']['name'] . $town['name'] . ' 原因：' . $msg : '';
    }, Step::$towns))) Step::error ($errors);

    Step::progress ('更新全部鄉鎮天氣', '完成！');
  }
  public static function cleanAsset () {
    $lasts = array ();
    Step::mergeArrayRecursive (Step::directoryMap (PATH_ASSET), $lasts, rtrim (PATH_ASSET, DIRECTORY_SEPARATOR));
    Step::newLine ('-', '清除 asset 內的檔案', count ($lasts));
    
    if ($errors = array_filter (array_map (function ($last) {
      Step::progress ('清除 asset 內的檔案');
      return !@unlink ($last) ? ' 檔案：' . pathinfo ($last, PATHINFO_BASENAME) : '';
    }, $lasts))) Step::error ($errors);

    Step::progress ('清除 asset 內的檔案', '完成！');
  }
  public static function writeMapsJs () {
    Step::newLine ('-', '更新 Maps JavaScript');

    if (!write_file (PATH_ASSET . 'maps' . JS, load_view (PATH_TEMPLATE . '_js_maps' . PHP, array ('weathers' => Step::mapsData ())))) Step::error ();

    Step::progress ('更新 Maps JavaScript', '完成！');
  }
  public static function deleteHtmlAndTxt () {
    Step::newLine ('-', '清除檔案', count ($files = array_filter (array_map (function ($file) { return PATH . $file; }, Step::directoryList (PATH)), function ($file) { return in_array (pathinfo ($file, PATHINFO_EXTENSION), array ('html', 'txt')); })));

    if ($errors = array_filter (array_map (function ($file) {
        Step::progress ('清除檔案');
        return !unlink ($file) ? ' 檔案：' . $file : '';
      }, $files))) Step::error ($errors);

    Step::progress ('清除檔案', '完成！');
  }
  public static function writeIndexHtml () {
    Step::newLine ('-', '更新 Index HTML');

    Step::$questions = Step::array2dTo1d (array_filter (array_map (function ($question) { shuffle ($question); return array_splice ($question, 0, 6); }, Step::$questions), function ($question) { return $question; }));
    shuffle (Step::$questions);

    if (($town = Step::datas ()[215]) && (Step::$questions = array_splice (Step::$questions, 0, 8))) array_push (Step::$questions, array ('title' => '地方特色', 'desc' => '你知道<b>' . $town['cate']['name'] . $town['name'] .'</b>的迎媽祖很有名嗎？', 'text' => '活動在這裡', 'town' => $town));
    else Step::$questions = array_splice (Step::$questions, 0, 9);
    shuffle (Step::$questions);

    $cards = array_filter (array_map (function ($card) { return array_merge ($card, array ('town' => isset ($card['town']) && isset (self::datas ()[$card['town']]) ? self::datas ()[$card['town']] : array ())); }, array_filter (array_merge (array_values (Step::$maxs), array_values (Step::$mins)), function ($val) { return isset ($val['town']) && $val['town']; })));
    $hiddens = array_values (array_map (function ($town) { return array ('i' => $town['id'], 'g' => $town['img'], 'n' => $town['name'], 'c' => $town['cate']['name'], 'p' => $town['postal'], 'm' => $town['weather']['img'], 'd' => $town['weather']['desc'], 't' => $town['weather']['temperature'], 'h' => $town['weather']['humidity'], 'r' => $town['weather']['rainfall'], 'l' => $town['link'], 'e' => $town['weather']['at'],); }, Step::datas ()));

    Step::$specials = array_values (Step::$specials);
    uasort (Step::$specials, function ($a, $b) { return count ($a['towns']) < count ($b['towns']); });

    Step::$specials = array_map (function ($special) {
      $cates = array ();
      foreach ($special['towns'] as $town)
        if (!isset ($cates[$town['cate']['id']])) $cates[$town['cate']['id']] = array_merge ($town['cate'], array ('towns' => array ($town)));
        else array_push ($cates[$town['cate']['id']]['towns'], $town);
      $special['cates'] = array_values ($cates);
      unset ($special['towns']);

      $special['cates'] = array_values (array_filter (array_map (function ($cate) {
        $cate['towns'] = array_values (array_filter (array_map (function ($town) {
            return isset (Step::datas ()[$town['id']]) ? Step::datas ()[$town['id']] : array ();
          }, $cate['towns'])));
        return $cate['towns'] ? $cate : array ();
      }, $special['cates'])));
      return $special;
    }, Step::$specials);

    if (!write_file (PATH . 'index' . HTML, HTMLMin::minify (load_view (PATH_TEMPLATE . 'index' . PHP, array (
          'cards' => $cards,
          'hiddens' => $hiddens,
          'specials' => Step::$specials,
          'questions' => Step::$questions,
          '_header' => load_view (PATH_TEMPLATE . '_header' . PHP, array ('active' => URL_INDEX)),
          '_aside' => load_view (PATH_TEMPLATE . '_aside' . PHP, array ('active' => URL_INDEX)),
          '_footer' => load_view (PATH_TEMPLATE . '_footer' . PHP),
        ))))) Step::error ();

    Step::progress ('更新 Index HTML', '完成！');
  }
  public static function writeAllHtml () {
    Step::newLine ('-', '更新 All HTML');
    
    $cates = array_values (array_filter (array_map (function ($cate) {
      $cate['towns'] = array_values (array_filter (array_map (function ($town) { return isset (Step::datas ()[$town['id']]) ? Step::datas ()[$town['id']] : array (); }, $cate['towns'])));
      return $cate['towns'] ? $cate : array ();
    }, Step::$cates)));
    
    if (!write_file (PATH . 'all' . HTML, HTMLMin::minify (load_view (PATH_TEMPLATE . 'all' . PHP, array (
          'cates' => $cates,
          '_header' => load_view (PATH_TEMPLATE . '_header' . PHP, array ('active' => URL_ALL)),
          '_aside' => load_view (PATH_TEMPLATE . '_aside' . PHP, array ('active' => URL_ALL)),
          '_footer' => load_view (PATH_TEMPLATE . '_footer' . PHP),
        ))))) Step::error ();

    Step::progress ('更新 All HTML', '完成！');
  }
  public static function writeMapsHtml () {
    Step::newLine ('-', '更新 Maps HTML');

    if (!write_file (PATH . 'maps' . HTML, HTMLMin::minify (load_view (PATH_TEMPLATE . 'maps' . PHP, array (
          '_header' => load_view (PATH_TEMPLATE . '_header' . PHP, array ('active' => URL_MAPS)),
          '_aside' => load_view (PATH_TEMPLATE . '_aside' . PHP, array ('active' => URL_MAPS)),
          '_footer' => load_view (PATH_TEMPLATE . '_footer' . PHP),
          'weathers' => Step::mapsData (),
        ))))) Step::error ();

    Step::progress ('更新 Maps HTML', '完成！');
  }
  public static function writeSearchHtml () {
    Step::newLine ('-', '更新 Search HTML');

    if (!write_file (PATH . 'search' . HTML, HTMLMin::minify (load_view (PATH_TEMPLATE . 'search' . PHP, array (
          '_header' => load_view (PATH_TEMPLATE . '_header' . PHP, array ('active' => URL_SEARCH)),
          '_aside' => load_view (PATH_TEMPLATE . '_aside' . PHP, array ('active' => URL_SEARCH)),
          '_footer' => load_view (PATH_TEMPLATE . '_footer' . PHP),
          'suggests' => array_map (function ($weather) {return array ('n' => $weather['n'], 'l' => URL_SEARCH . '#' . url_encode ($weather['n']));}, Step::mapsData ()),
        ))))) Step::error ();

    Step::progress ('更新 Search HTML', '完成！');
  }
  public static function writeReadmeHtml () {
    Step::newLine ('-', '更新 Readme HTML');

    if (!write_file (PATH . 'readme' . HTML, HTMLMin::minify (load_view (PATH_TEMPLATE . 'readme' . PHP, array (
          '_header' => load_view (PATH_TEMPLATE . '_header' . PHP, array ('active' => URL_README)),
          '_aside' => load_view (PATH_TEMPLATE . '_aside' . PHP, array ('active' => URL_README)),
          '_footer' => load_view (PATH_TEMPLATE . '_footer' . PHP),
        ))))) Step::error ();

    Step::progress ('更新 Readme HTML', '完成！');
  }
  public static function cleanTowns () {
    $lasts = array ();
    Step::mergeArrayRecursive (Step::directoryMap (PATH_TOWNS), $lasts, rtrim (PATH_TOWNS, DIRECTORY_SEPARATOR));
    Step::newLine ('-', '清除 towns 內的檔案', count ($lasts));
    
    if ($errors = array_filter (array_map (function ($last) {
      Step::progress ('清除 towns 內的檔案');
      return !@unlink ($last) ? ' 檔案：' . pathinfo ($last, PATHINFO_BASENAME) : '';
    }, $lasts))) Step::error ($errors);

    Step::progress ('清除 towns 內的檔案', '完成！');
  }
  public static function writeTownsHtml () {
    Step::newLine ('-', '更新各鄉鎮天氣 HTML', count (Step::datas ()));

    if ($errors = array_filter (array_map (function ($town) {
        Step::progress ('更新各鄉鎮天氣 HTML');

        $limit = 10;
        if (count ($mores = Step::$cates[$town['cate']['id']]['towns']) < ($limit + 1)) $mores = array_merge ($mores, Step::$cates[$town['cate']['id'] + 1]['towns']);

        $mores = array_filter ($mores, function ($t) use ($town) { return $town['id'] != $t['id']; });
        shuffle ($mores);
        $mores = array_map (function ($more) { return Step::datas ()[$more['id']]; }, array_values (array_splice ($mores, 0, $limit)));

        return !write_file (PATH_TOWNS . $town['cate']['name'] . '-' . $town['name'] . HTML, HTMLMin::minify (load_view (PATH_TEMPLATE . 'content' . PHP, array (
            'town' => $town,
            'mores' => $mores,
            'position' => array ('i' => $town['id'], 'z' => 14, 'a' => $town['position']['lat'], 'g' => $town['position']['lng']),
            '_header' => load_view (PATH_TEMPLATE . '_header' . PHP, array ('active' => URL_TOWNS, 'title' => $town['cate']['name'] . $town['name'])),
            '_aside' => load_view (PATH_TEMPLATE . '_aside' . PHP, array ('active' => URL_TOWNS)),
            '_footer' => load_view (PATH_TEMPLATE . '_footer' . PHP),
          )))) ? ' 鄉鎮：' . $town['cate']['name'] . $town['name'] : '';
      }, Step::datas ()))) Step::error ($errors);

    Step::progress ('更新各鄉鎮天氣 HTML', '完成！');
  }
  public static function cleanSitemap () {
    Step::newLine ('-', '清除 Sitemap', count ($forders = array (PATH_SITEMAP)));
    
    if ($errors = array_filter (array_map (function ($forder) {
        Step::progress ('清除 Sitemap');
        return !Step::directoryDelete ($forder, false) ? ' 目錄：' . $forder : '';
      }, $forders))) Step::error ($errors);
    
    Step::progress ('清除 Sitemap', '完成！');
  }
  public static function writeSitemap () {
    $files = array_filter (array_map (function ($file) { return $file; }, Step::directoryList (PATH)), function ($file) { return in_array (pathinfo ($file, PATHINFO_EXTENSION), array ('html')); });
    Step::newLine ('-', '更新 Sitemap', count ($files) + count (Step::datas ()));

    $sit_map = new Sitemap ($domain = rtrim (URL, '/'));
    $sit_map->setPath (PATH_SITEMAP);
    $sit_map->setDomain ($domain);

    foreach ($files as $file) {
      if ($file == 'readme.html') $sit_map->addItem ('/' . $file, '0.3', 'weekly', date ('c'));
      else if (in_array ($file, array ('search.html', 'maps.html'))) $sit_map->addItem ('/' . $file, '0.5', 'daily', date ('c'));
      else $sit_map->addItem ('/' . $file, '0.7', 'hourly', date ('c'));
      Step::progress ('更新 Sitemap');
    }

    foreach (Step::datas () as $town) {
      $sit_map->addItem ('/towns/' . pathinfo ($town['link'], PATHINFO_BASENAME), '1', 'hourly', date ('c'));
      Step::progress ('更新 Sitemap');
    }
    $sit_map->createSitemapIndex ($domain . '/sitemap/', date ('c'));

    Step::progress ('更新 Sitemap', '完成！');
  }
  public static function writeRobotsTxt () {
    Step::newLine ('-', '更新 Robots TXT');
    
    if (!write_file (PATH . 'robots' . TXT, "Sitemap: " . URL_SITEMAP . "sitemap_index" . XML . "\n\nUser-agent: *")) Step::error ();

    Step::progress ('更新 Robots TXT', '完成！');
  }
  public static function initS3 ($access, $secret) {
    Step::newLine ('-', '初始化 S3 工具');
    
    try {
      if (!S3::init ($access, $secret)) throw new Exception ('初始化失敗！');
    } catch (Exception $e) { Step::error (array (' ' . $e->getMessage ())); }

    Step::progress ('初始化 S3 工具', '完成！');
  }
  public static function listS3Files () {
    try {
      Step::newLine ('-', '列出 S3 上所有檔案', count ($list = S3::getBucket (BUCKET, NAME)));
      Step::$s3Files = array_filter ($list, function ($file) {
        Step::progress ('列出 S3 上所有檔案');
        return preg_match ('/^' . NAME . '\//', $file['name']);
      });
    } catch (Exception $e) { Step::error (array (' ' . $e->getMessage ())); }

    Step::progress ('列出 S3 上所有檔案', '完成！');
  }
  public static function listLocalFiles () {
    Step::newLine ('-', '列出即將上傳所有檔案');
    $uploadDirs = array (); foreach (Step::$uploadDirs as $key => $value) array_push ($uploadDirs, array ('path' => PATH . $key, 'formats' => $value));

    Step::$localFiles = Step::array2dTo1d (array_map (function ($uploadDir) {
        $files = array ();
        Step::mergeArrayRecursive (Step::directoryMap ($uploadDir['path']), $files, $uploadDir['path']);
        $files = array_filter ($files, function ($file) use ($uploadDir) { return in_array (pathinfo ($file, PATHINFO_EXTENSION), $uploadDir['formats']); });
        Step::progress ('列出即將上傳所有檔案');
        return array_map (function ($file) { return array ('path' => $file, 'md5' => md5_file ($file), 'uri' => preg_replace ('/^(' . preg_replace ('/\//', '\/', PATH) . ')/', '', $file)); }, $files);
      }, $uploadDirs));

    Step::progress ('列出即將上傳所有檔案', '完成！');
  }
  public static function filterLocalFiles () {
    Step::newLine ('-', '過濾需要上傳檔案');

    $files = array_filter (Step::$localFiles, function ($file) {
      foreach (Step::$s3Files as $s3File)
        if (($s3File['name'] == (NAME . DIRECTORY_SEPARATOR . $file['uri'])) && ($s3File['hash'] == $file['md5']))
          return false;
      Step::progress ('過濾需要上傳檔案');
      return $file;
    });

    Step::progress ('過濾需要上傳檔案', '完成！');

    return $files;
  }
  public static function uploadLocalFiles ($files) {
    Step::newLine ('-', '上傳檔案', count ($files));
    
    if ($errors = array_filter (array_map (function ($file) {
        try {
          Step::progress ('上傳檔案');
          return !S3::putFile ($file['path'], BUCKET, NAME . DIRECTORY_SEPARATOR . $file['uri']) ? ' 檔案：' . $file['path'] : '';
        } catch (Exception $e) { return ' 檔案：' . $file['path']; }
      }, $files))) Step::error ($errors);

    Step::progress ('上傳檔案', '完成！');
  }
  public static function filterS3Files () {
    Step::newLine ('-', '過濾需要刪除檔案');

    $files = array_filter (Step::$s3Files, function ($s3File) {
      foreach (Step::$localFiles as $localFile) if ($s3File['name'] == (NAME . DIRECTORY_SEPARATOR . $localFile['uri'])) return false;
      Step::progress ('過濾需要刪除檔案');
      return true;
    });

    Step::progress ('過濾需要刪除檔案', '完成！');
    return $files;
  }
  public static function deletwS3Files ($files) {
    Step::newLine ('-', '刪除 S3 上需要刪除的檔案', count ($files));

    if ($errors = array_filter (array_map (function ($file) {
        try {
          Step::progress ('刪除 S3 上需要刪除的檔案');
          return !S3::deleteObject (BUCKET, $file['name']) ? ' 檔案：' . $file['name'] : '';
        } catch (Exception $e) { return ' 檔案：' . $file['name']; }
      }, $files))) Step::error ($errors);

    Step::progress ('刪除 S3 上需要刪除的檔案', '完成！');
  }
  public static function initDirs () {
    Step::newLine ('-', '建立所需目錄', count ($forders = array (PATH_API, PATH_ASSET, PATH_TOWNS, PATH_SITEMAP)));

    if ($errors = array_filter (array_map (function ($forder) {
        Step::progress ('建立所需目錄');
        return !((file_exists ($forder) && is_dir ($forder) && is_writable ($forder)) || Step::mkdir777 ($forder)) ? ' 目錄：' . $forder : '';
      }, $forders))) Step::error ($errors);

    Step::progress ('建立所需目錄', '完成！');
  }
  public static function chmodDir () {
    Step::newLine ('-', '變更目錄權限', count ($forders = array (PATH_IMG_TOWNS, PATH_IMG_WEATHERS, PATH_IMG_SPECIALS)));

    if ($errors = array_filter (array_map (function ($forder) {
        Step::progress ('變更目錄權限');
        return !((file_exists ($forder) && is_dir ($forder) && is_writable ($forder)) || Step::mkdir777 ($forder)) ? ' 目錄：' . $forder : '';
      }, $forders))) Step::error ($errors);

    Step::progress ('變更目錄權限', '完成！');
  }
  public static function cleanDirs () {
    Step::newLine ('-', '清除目錄', count ($forders = array (PATH_API, PATH_ASSET, PATH_TOWNS, PATH_SITEMAP)));
    
    if ($errors = array_filter (array_map (function ($forder) {
        Step::progress ('清除目錄');
        return !Step::directoryDelete ($forder) ? ' 目錄：' . $forder : '';
      }, $forders))) Step::error ($errors);

    Step::progress ('清除目錄', '完成！');
  }

  public static function writeWeather ($town) {
    if (!is_array ($weather = Weather::getWeather ($town))) return $weather;

    if (!write_file (PATH_API_WEATHERS . $town['id'] . JSON, json_encode ($weather)))
      return '寫入天氣失敗';
    
    Step::$datas[$town['id']] = Step::buildDatas ($town, $weather);
    return '';
  }
  public static function buildDatas ($town, $weather) {
    $specials = array_map (function ($special) { return array_merge ($special, array ('img' => URL_IMG_SPECIALS . $special['img'])); }, $weather['specials']);
    $histories = $weather['histories'];
    $weather = end ($weather['histories']);
    $weather['img'] = URL_IMG_WEATHERS . $weather['img'];
    
    $fTemperature = func (max ($t = column_array ($histories, 'temperature')), min ($t));
    $fHumidity = func (max ($t = column_array ($histories, 'humidity')), min ($t));
    $fRainfall = func (max ($t = column_array ($histories, 'rainfall')), min ($t));

    $histories = array_map (function ($history) use ($fTemperature, $fHumidity, $fRainfall) {
      return array (
          'at' => $history['at'],
          'temperature' => array (
              'percent' => ($t = ceil ($history['temperature'] * $fTemperature['x'] + $fTemperature['y'])) ? $t : 50,
              'value' => $history['temperature']
            ),
          'humidity' => array (
              'percent' => ($t = ceil ($history['humidity'] * $fHumidity['x'] + $fHumidity['y'])) ? $t : 50,
              'value' => $history['humidity']
            ),
          'rainfall' => array (
              'percent' => ceil ($history['rainfall'] * $fRainfall['x'] + $fRainfall['y']),
              'value' => $history['rainfall']
            )
        );
      return $history;
    }, $histories);

    if (($weather['temperature'] > Step::$maxs['temperature']['val']) || ($weather['temperature'] == Step::$maxs['temperature']['val'] && rand (0, 1))) Step::$maxs['temperature'] = array_merge (Step::$maxs['temperature'], array ('val' => $weather['temperature'], 'town' => $town['id']));
    if (($weather['humidity'] > Step::$maxs['humidity']['val']) || ($weather['humidity'] == Step::$maxs['humidity']['val'] && rand (0, 1))) Step::$maxs['humidity'] = array_merge (Step::$maxs['humidity'], array ('val' => $weather['humidity'], 'town' => $town['id']));
    if (($weather['rainfall'] > Step::$maxs['rainfall']['val']) || ($weather['rainfall'] == Step::$maxs['rainfall']['val'] && rand (0, 1))) Step::$maxs['rainfall'] = array_merge (Step::$maxs['rainfall'], array ('val' => $weather['rainfall'], 'town' => $town['id']));
    
    if ($weather['temperature'] < Step::$mins['temperature']['val'] || ($weather['temperature'] == Step::$mins['temperature']['val'] && rand (0, 1))) Step::$mins['temperature'] = array_merge (Step::$mins['temperature'], array ('val' => $weather['temperature'], 'town' => $town['id']));
    if ($weather['humidity'] > 0 && ($weather['humidity'] < Step::$mins['humidity']['val'] || ($weather['humidity'] == Step::$mins['humidity']['val'] && rand (0, 1)))) Step::$mins['humidity'] = array_merge (Step::$mins['humidity'], array ('val' => $weather['humidity'], 'town' => $town['id']));
    if ($weather['rainfall'] > 0 && ($weather['rainfall'] < Step::$mins['rainfall']['val'] || ($weather['rainfall'] == Step::$mins['rainfall']['val'] && rand (0, 1)))) Step::$mins['rainfall'] = array_merge (Step::$mins['rainfall'], array ('val' => $weather['rainfall'], 'town' => $town['id']));

    foreach ($specials as $special)
      if (!isset (Step::$specials[$special['title'] . $special['desc']])) Step::$specials[$special['title'] . $special['desc']] = array ('special' => $special, 'towns' => array ($town));
      else array_push (Step::$specials[$special['title'] . $special['desc']]['towns'], $town);

    $infos = include (PATH_CMD_LIBS_INFOS . $town['id'] . PHP);
    $town_img_info = town_img_info ($town);
    $town = array_merge (
        $town, array (
        'infos' => $infos,
        'link' => URL_TOWNS . url_encode ($town['cate']['name'] . '-' . $town['name']) . HTML,
        'specials' => $specials,
        'histories' => $histories,
        'weather' => $weather,
        'img' => $town_img_info['img'],
        'img_dimension' => $town_img_info['dimension'],
        'icons' => Icon::gets ($weather['img']),
      ));
    
    if (isset ($infos['地名緣由'])) array_push (Step::$questions[$k = '地名猜一猜'], array ('title' => $k, 'desc' => '你知道<b>' . $town['cate']['name'] . $town['name'] .'</b>的地名由來嗎？', 'text' => '答案看這裡', 'town' => $town));
    if (isset ($infos['野史傳說'])) array_push (Step::$questions[$k = '野史傳說'], array ('title' => $k, 'desc' => '你知道<b>' . $town['cate']['name'] . $town['name'] .'</b>傳奇的野史傳說嗎？', 'text' => '故事在這裡', 'town' => $town));
    if ($town['position']['zoom'] >=13) array_push (Step::$questions[$k = '台灣知多少'], array ('title' => $k, 'desc' => '你知道<b>' . $town['name'] .'</b>在台灣哪裡嗎？', 'text' => '答案看這裡', 'town' => $town));
    return $town;
  }
  public static function datas () {
    if (Step::$datas) return Step::$datas;

    Step::$datas = array_values (array_filter (array_map (function ($weather) {
        if (!isset (Step::$towns[$id = pathinfo ($weather, PATHINFO_FILENAME)]))
          return array ();
        $weather = json_decode (read_file (PATH_API_WEATHERS . $weather), true);
        
        return Step::buildDatas (Step::$towns[$id], $weather);
      }, Step::directoryList (PATH_API_WEATHERS))));

    return Step::$datas = array_combine (column_array (Step::$datas, 'id'), Step::$datas);
  }
  public static function mapsData () {
    if (Step::$mapsData) return Step::$mapsData;

    Step::$mapsData = array_values (array_map (function ($data) {

      $special = $data['specials'] ? array (
          'imgs' => column_array ($data['specials'], 'img'),
          'desc' => mb_strimwidth (implode (', ', array_filter (column_array ($data['specials'], 'title'))) . ' ' . implode ('; ', column_array ($data['specials'], 'desc')), 0, 126, '…','UTF-8')
        ) : null;

      return array (
          'i' => $data['id'],
          'c' => $data['cate']['name'],
          'p' => $data['postal'],
          'n' => $data['name'],
          'a' => $data['position']['lat'],
          'g' => $data['position']['lng'],
          'z' => $data['position']['zoom'],
          'm' => $data['weather']['img'],
          'd' => $data['weather']['desc'],
          't' => $data['weather']['temperature'],
          'h' => $data['weather']['humidity'],
          'r' => $data['weather']['rainfall'],
          's' => $special,
          'l' => $data['link'],
        );
    }, Step::datas ()));
    
    return Step::$mapsData;
  }
  public static function params ($params, $keys) {
    $ks = $return = $result = array ();

    if (!$params) return $return;
    if (!$keys) return $return;

    foreach ($keys as $key)
      if (is_array ($key)) foreach ($key as $k) array_push ($ks, $k);
      else  array_push ($ks, $key);

    $key = null;

    foreach ($params as $param)
      if (in_array ($param, $ks)) if (!isset ($result[$key = $param])) $result[$key] = array (); else ;
      else if (isset ($result[$key])) array_push ($result[$key], $param); else ;

    foreach ($keys as $key)
      if (is_array ($key))  foreach ($key as $k) if (isset ($result[$k])) $return[$key[0]] = isset ($return[$key[0]]) ? array_merge ($return[$key[0]], $result[$k]) : $result[$k]; else;
      else if (isset ($result[$key])) $return[$key] = isset ($return[$key]) ? array_merge ($return[$key], $result[$key]) : $result[$key]; else;

    return $return;
  }

  public static function directoryList ($sourceDir, $hidden = false) {
    if ($fp = @opendir ($sourceDir = rtrim ($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR)) {
      $filedata = array ();

      while (false !== ($file = readdir ($fp)))
        if (!(!trim ($file, '.') || (($hidden == false) && ($file[0] == '.'))))
          array_push ($filedata, $file);

      closedir ($fp);
      return $filedata;
    }
    return array ();
  }
  public static function directoryMap ($sourceDir, $directoryDepth = 0, $hidden = false) {
    if ($fp = @opendir ($sourceDir)) {
      $filedata = array ();
      $new_depth  = $directoryDepth - 1;
      $sourceDir = rtrim ($sourceDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

      while (false !== ($file = readdir ($fp))) {
        if (!trim ($file, '.') || (($hidden == false) && ($file[0] == '.')) || is_link ($file) || ($file == 'cmd') || ($sourceDir == PATH && $file == 'towns'))
          continue;

        if ((($directoryDepth < 1) || ($new_depth > 0)) && @is_dir ($sourceDir . $file))
          $filedata[$file] = Step::directoryMap ($sourceDir . $file . DIRECTORY_SEPARATOR, $new_depth, $hidden);
        else
          array_push ($filedata, $file);
      }

      closedir ($fp);
      return $filedata;
    }

    return false;
  }
  public static function mergeArrayRecursive ($files, &$a, $k = null) {
    if (!($files && is_array ($files))) return false;
    foreach ($files as $key => $file)
      if (is_array ($file)) $key . Step::mergeArrayRecursive ($file, $a, ($k ? rtrim ($k, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $key);
      else array_push ($a, ($k ? rtrim ($k, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR : '') . $file);
  }
  public static function color ($string, $fColor = null, $background_color = null, $is_print = false) {
    if (!strlen ($string)) return "";
    $sColor = "";
    $keys = array ('n' => '30', 'w' => '37', 'b' => '34', 'g' => '32', 'c' => '36', 'r' => '31', 'p' => '35', 'y' => '33');
    if ($fColor && in_array (strtolower ($fColor), array_map ('strtolower', array_keys ($keys)))) {
      $fColor = !in_array (ord ($fColor[0]), array_map ('ord', array_keys ($keys))) ? in_array (ord ($fColor[0]) | 0x20, array_map ('ord', array_keys ($keys))) ? '1;' . $keys[strtolower ($fColor[0])] : null : $keys[$fColor[0]];
      $sColor .= $fColor ? "\033[" . $fColor . "m" : "";
    }
    $sColor .= $background_color && in_array (strtolower ($background_color), array_map ('strtolower', array_keys ($keys))) ? "\033[" . ($keys[strtolower ($background_color[0])] + 10) . "m" : "";

    if (substr ($string, -1) == "\n") { $string = substr ($string, 0, -1); $has_new_line = true; } else { $has_new_line = false; }
    $sColor .=  $string . "\033[0m";
    $sColor = $sColor . ($has_new_line ? "\n" : "");
    if ($is_print) printf ($sColor);
    return $sColor;
  }
  public static function array2dTo1d ($array) {
    $messages = array ();
    foreach ($array as $key => $value)
      if (is_array ($value)) $messages = array_merge ($messages, $value);
      else array_push ($messages, $value);
    return $messages;
  }
  public static function directoryDelete ($dir, $is_root = true) {
    if (!file_exists ($dir)) return true;
    
    $dir = rtrim ($dir, DIRECTORY_SEPARATOR);
    if (!$currentDir = @opendir ($dir))
      return false;

    while (false !== ($filename = @readdir ($currentDir)))
      if (($filename != '.') && ($filename != '..'))
        if (is_dir ($dir . DIRECTORY_SEPARATOR . $filename)) if (substr ($filename, 0, 1) != '.') Step::directoryDelete ($dir . DIRECTORY_SEPARATOR . $filename); else;
        else unlink ($dir . DIRECTORY_SEPARATOR . $filename);

    @closedir ($currentDir);

    return $is_root ? @rmdir ($dir) : true;
  }
  
  public static function mkdir777 ($path) {
    $oldmask = umask (0);
    @mkdir ($path, 0777, true);
    umask ($oldmask);
    return true;
  }
}
