<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */


define ('URL', PROTOCOL . BUCKET . '/' . NAME . '/');
define ('URL_INDEX', URL . 'index' . HTML);
define ('URL_MAPS', URL . 'maps' . HTML);
define ('URL_SEARCH', URL . 'search' . HTML);
define ('URL_README', URL . 'readme' . HTML);
define ('URL_ALL', URL . 'all' . HTML);
define ('URL_TOWNS', URL . 'towns/');
define ('URL_SITEMAP', URL . 'sitemap/');

define ('URL_API_DOC', URL . 'api/doc/index.html');

define ('URL_IMG', URL . 'img/');
define ('URL_IMG_WEATHERS', URL_IMG . 'weathers/' . IMG_TMEME . '/');
define ('URL_IMG_SPECIALS', URL_IMG . 'specials/' . IMG_TMEME . '/');
define ('URL_IMG_TOWNS', URL_IMG . 'towns/');

define ('URL_OG', URL_IMG . 'og/');
define ('URL_OG_INDEX', URL_OG . 'index.jpg');
define ('URL_OG_MAPS', URL_OG . 'maps.jpg');
define ('URL_OG_SEARCH', URL_OG . 'search.jpg');
define ('URL_OG_README', URL_OG . 'readme.jpg');
define ('URL_OG_ALL', URL_OG . 'all.jpg');