<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 *
 * Need to run init.php
 *
 */

define ('PHP', '.php');
define ('PATH', implode (DIRECTORY_SEPARATOR, explode (DIRECTORY_SEPARATOR, dirname (str_replace (pathinfo (__FILE__, PATHINFO_BASENAME), '', __FILE__)))) . '/');
define ('PATH_CMD', PATH . 'cmd' . DIRECTORY_SEPARATOR);
define ('PATH_CMD_LIBS', PATH_CMD . 'libs' . DIRECTORY_SEPARATOR);
define ('PATH_CMD_LIBS_INFOS', PATH_CMD_LIBS . 'town_infos' . DIRECTORY_SEPARATOR);

include_once PATH_CMD_LIBS . 'defines' . PHP;
include_once PATH_CMD_LIBS . 'functions' . PHP;
include_once PATH_CMD_LIBS . 'Step' . PHP;
include_once PATH_CMD_LIBS . 'Icon' . PHP;
include_once PATH_CMD_LIBS . 'Minify' . DIRECTORY_SEPARATOR . 'Min' . PHP;
include_once PATH_CMD_LIBS . 'phpquery' . PHP;
include_once PATH_CMD_LIBS . 'Weather' . PHP;
include_once PATH_CMD_LIBS . 'Sitemap' . PHP;

Step::start ();

$file = array_shift ($argv);
$argv = Step::params ($argv, array (array ('-b', '-bucket'), array ('-a', '-access'), array ('-s', '-secret'), array ('-u', '-upload'), array ('-m', '-minify'), array ('-d', '-update')));
if (!(isset ($argv['-b'][0]) && ($bucket = trim ($argv['-b'][0], '/')) && isset ($argv['-a'][0]) && ($access = $argv['-a'][0]) && isset ($argv['-s'][0]) && ($secret = $argv['-s'][0]))) {
  echo str_repeat ('=', 80) . "\n";
  echo ' ' . Step::color ('◎', 'R') . ' ' . Step::color ('錯誤囉！', 'r') . Step::color ('請確認參數是否正確，分別需要', 'p') . ' ' . Step::color ('-b', 'W') . '、' . Step::color ('-a', 'W') . '、' . Step::color ('-s', 'W') . Step::color (' 的參數！', 'p') . ' ' . Step::color ('◎', 'R');
  echo "\n" . str_repeat ('=', 80) . "\n\n";
  exit ();
}

define ('BUCKET', $bucket);
define ('ACCESS', $access);
define ('SECRET', $secret);
define ('UPLOAD', isset ($argv['-u'][0]) && is_numeric ($tmp = $argv['-u'][0]) ? $tmp ? true : false : true);
define ('MINIFY', isset ($argv['-m'][0]) && is_numeric ($tmp = $argv['-m'][0]) ? $tmp ? true : false : true);
define ('UPDATE', isset ($argv['-d'][0]) && is_numeric ($tmp = $argv['-d'][0]) ? $tmp ? true : false : true);

include_once PATH_CMD_LIBS . 'define_urls' . PHP;

// 開始執行
Step::init ();
Step::writeUrlApi ();
Step::writeAllApi ();
Step::writeCatesApi ();
Step::writeTownsApi ();
Step::writeWeathersApi ();
// ---------------

Step::datas ();
// ---------------

Step::cleanAsset ();
Step::writeMapsJs ();
// ---------------

Step::deleteHtmlAndTxt ();
Step::writeIndexHtml ();
Step::writeAllHtml ();
Step::writeMapsHtml ();
Step::writeSearchHtml ();
Step::writeReadmeHtml ();
// ---------------

Step::cleanTowns ();
Step::writeTownsHtml ();
// ---------------

Step::cleanSitemap ();
Step::writeSitemap ();
Step::writeRobotsTxt ();
// ---------------

if (!UPLOAD) {
  Step::usage ();
  Step::end ();
  Step::showUrl ();
  echo "\n";
  exit ();
}
// ---------------

Step::setUploadDirs (array (
    'asset' => array ('js', 'css'),
    'font' => array ('eot', 'svg', 'ttf', 'woff'),
    'img' => array ('png', 'jpg', 'jpeg', 'gif', 'svg'),
    '' => array ('html', 'txt'),
    'towns' => array ('html'),
    'sitemap' => array ('xml'),
    'api' => array ('json', 'js', 'css', 'png')
  ));
// ---------------
include_once PATH_CMD_LIBS . 'S3' . PHP;

Step::initS3 (ACCESS, SECRET);
Step::listS3Files ();
Step::listLocalFiles ();
// ---------------

$files = Step::filterLocalFiles ();
Step::uploadLocalFiles ($files);
$files = Step::filterS3Files ();
Step::deletwS3Files ($files);
// ---------------

Step::usage ();
Step::end ();
Step::showUrl ();
echo "\n";
exit ();
