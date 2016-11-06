<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

mb_regex_encoding ("UTF-8");
mb_internal_encoding ('UTF-8');

date_default_timezone_set ('Asia/Taipei');

define ('TIME', date ('Ymd_His'));
define ('IMG_TMEME', 'zeusdesign');
define ('PROTOCOL', "https://");

define ('JS', '.js');
define ('CSS', '.css');
define ('JSON', '.json');
define ('HTML', '.html');
define ('TXT', '.txt');
define ('XML', '.xml');

define ('PATH_TOWNS', PATH . 'towns' . DIRECTORY_SEPARATOR);
define ('PATH_ASSET', PATH . 'asset' . DIRECTORY_SEPARATOR);
define ('PATH_SITEMAP', PATH . 'sitemap' . DIRECTORY_SEPARATOR);
define ('PATH_API', PATH . 'api' . DIRECTORY_SEPARATOR);
define ('PATH_API_CATES', PATH_API . 'cates' . DIRECTORY_SEPARATOR);
define ('PATH_API_TOWNS', PATH_API . 'towns' . DIRECTORY_SEPARATOR);
define ('PATH_API_WEATHERS', PATH_API . 'weathers' . DIRECTORY_SEPARATOR);
define ('PATH_IMG', PATH . 'img' . DIRECTORY_SEPARATOR);
define ('PATH_IMG_WEATHERS', PATH_IMG . 'weathers' . DIRECTORY_SEPARATOR . IMG_TMEME . DIRECTORY_SEPARATOR);
define ('PATH_IMG_SPECIALS', PATH_IMG . 'specials' . DIRECTORY_SEPARATOR . IMG_TMEME . DIRECTORY_SEPARATOR);
define ('PATH_IMG_TOWNS', PATH_IMG . 'towns' . DIRECTORY_SEPARATOR);

define ('PATH_TEMPLATE', PATH . 'template' . DIRECTORY_SEPARATOR);

define ('NAME', ($temps = array_filter (explode (DIRECTORY_SEPARATOR, PATH))) ? end ($temps) : '');

define ('OA', '吳政賢');
define ('OA_URL', 'http://www.ioa.tw/');
define ('OA_FB_URL', 'https://www.facebook.com/comdan66/');
define ('OA_FB_UID', '100000100541088');
define ('FB_APP_ID', '199589883770118');
define ('FB_ADMIN_ID', OA_FB_UID);

define ('GITHUB_URL', 'https://github.com/comdan66/weather/');

define ('TITLE', '天氣地圖 Weather Maps');
define ('KEYWORDS', '天氣地圖 Weather Maps, 台灣天氣, Taiwan Weather, 在地天氣, 即時天氣, 台灣, Taiwan, Weather');
define ('ALT', '天氣地圖 Weather Maps');