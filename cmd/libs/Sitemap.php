<?php

/**
 * @author      OA Wu <comdan66@gmail.com>
 * @copyright   Copyright (c) 2016 OA Wu Design
 */

class Sitemap {
  private $writer;
  private $domain;
  private $path;
  private $filename = 'sitemap';
  private $current_item = 0;
  private $current_sitemap = 0;

  const EXT = '.xml';
  const SCHEMA = 'http://www.sitemaps.org/schemas/sitemap/0.9';
  const DEFAULT_PRIORITY = 0.5;
  const ITEM_PER_SITEMAP = 200;
  const SEPERATOR = '_';
  const INDEX_SUFFIX = 'index';

  public function __construct() {
  }

  public function setDomain($domain) {
    $this->domain = $domain;
    return $this;
  }

  private function getDomain() {
    return $this->domain;
  }

  private function getWriter() {
    return $this->writer;
  }

  private function setWriter(XMLWriter $writer) {
    $this->writer = $writer;
  }

  private function getPath() {
    return $this->path;
  }

  public function setPath($path) {
    $this->path = $path;
    return $this;
  }

  private function getFilename() {
    return $this->filename;
  }

  public function setFilename($filename) {
    $this->filename = $filename;
    return $this;
  }

  private function getCurrentItem() {
    return $this->current_item;
  }

  private function incCurrentItem() {
    $this->current_item = $this->current_item + 1;
  }

  private function getCurrentSitemap() {
    return $this->current_sitemap;
  }

  private function incCurrentSitemap() {
    $this->current_sitemap = $this->current_sitemap + 1;
  }

  private function startSitemap() {
    $this->setWriter(new XMLWriter());
    $this->getWriter()->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . $this->getCurrentSitemap() . self::EXT);
    $this->getWriter()->startDocument('1.0', 'UTF-8');
    $this->getWriter()->setIndent(true);
    $this->getWriter()->startElement('urlset');
    $this->getWriter()->writeAttribute('xmlns', self::SCHEMA);
  }

  public function addItem($loc, $priority = self::DEFAULT_PRIORITY, $changefreq = NULL, $lastmod = NULL) {
    if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
      if ($this->getWriter() instanceof XMLWriter) {
        $this->endSitemap();
      }
      $this->startSitemap();
      $this->incCurrentSitemap();
    }
    $this->incCurrentItem();
    $this->getWriter()->startElement('url');
    $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
    $this->getWriter()->writeElement('priority', $priority);
    if ($changefreq)
      $this->getWriter()->writeElement('changefreq', $changefreq);
    if ($lastmod)
      $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
    $this->getWriter()->endElement();
    return $this;
  }

  //增加 image sitemap 
  //$loc 網頁網址  , $image_loc 圖片網址
  //$param['caption'] 圖片的說明。
  //$param['geo_location'] 圖片所顯示的地理位置。例如，<image:geo_location>Limerick, Ireland</image:geo_location>。
  //$param['title'] 圖片的標題。
  //$param['license'] 圖片授權的網址。
  //詳細參考  http://support.google.com/webmasters/bin/answer.py?hl=zh-Hant&answer=178636  
  // 2013/02/27 By Rich 
  public function addImage($loc , $image_loc , $param = array()){
    if (($this->getCurrentItem() % self::ITEM_PER_SITEMAP) == 0) {
      if ($this->getWriter() instanceof XMLWriter) {
        $this->endSitemap();
      }
      $this->startSitemap();
      $this->incCurrentSitemap();
    }
    $this->incCurrentItem();
    $this->getWriter()->startElement('url');
    $this->getWriter()->writeElement('loc', $this->getDomain() . $loc);
    $this->getWriter()->startElement('image:image');
      $this->getWriter()->writeElement('image:loc', $image_loc);
      $attrs = array("caption" , "geo_location" , "title" , "license");    
      if (is_array($param)) {
        foreach ($attrs as $attr) {
          if (isset($param[$attr]))
            $this->getWriter()->writeElement('image:'.$attr , $param[$attr]);
        }
      }
    $this->getWriter()->endElement(); 


    // if ($changefreq)
    //   $this->getWriter()->writeElement('changefreq', $changefreq);
    // if ($lastmod)
    //   $this->getWriter()->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
    $this->getWriter()->endElement();
    return $this;
  }

  private function getLastModifiedDate($date) {
    return $date;
    if (ctype_digit($date)) {
      return date('Y-m-d', $date);
    } else {
      $date = strtotime($date);
      return date('Y-m-d', $date);
    }
  }

  private function endSitemap() {
    $this->getWriter()->endElement();
    $this->getWriter()->endDocument();
  }

  public function createSitemapIndex($loc, $lastmod = 'Today') {
    $this->endSitemap();
    $indexwriter = new XMLWriter();
    $indexwriter->openURI($this->getPath() . $this->getFilename() . self::SEPERATOR . self::INDEX_SUFFIX . self::EXT);
    $indexwriter->startDocument('1.0', 'UTF-8');
    $indexwriter->setIndent(true);
    $indexwriter->startElement('sitemapindex');
    $indexwriter->writeAttribute('xmlns', self::SCHEMA);
    for ($index = 0; $index < $this->getCurrentSitemap(); $index++) {
      $indexwriter->startElement('sitemap');
      $indexwriter->writeElement('loc', $loc . $this->getFilename() . self::SEPERATOR . $index . self::EXT);
      $indexwriter->writeElement('lastmod', $this->getLastModifiedDate($lastmod));
      $indexwriter->endElement();
    }
    $indexwriter->endElement();
    $indexwriter->endDocument();
  }
}