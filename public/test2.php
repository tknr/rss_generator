<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

use Bhaktaraz\RSSGenerator\Item;
use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\Channel;

// FIXME 2021年 PHP 8 でスクレイピングするなら php-html-parser - 猫でもわかるWebプログラミングと副業 https://www.utakata.work/entry/php/webscraping-with-php-html-parser

header('Content-Type: text/xml');

$options = new Options();
$options->setEnforceEncoding('utf8');

$url = 'https://13dl.me/home/';
$dom = new Dom();
$dom->loadFromUrl($url, $options);
/*
$html = $dom->outerHtml;
echo $html;
exit();
*/
/*
$element = $dom->find('div.copyrights > span.footer-logo')->innerHtml;
echo $element;
exit();
*/



$feed = new Feed();

$channel = new Channel();
$channel
  ->title($dom->find('meta[property="og:title"]')->getAttribute('content'))
  ->description($dom->find('meta[property="og:description"]')->getAttribute('content'))
  ->url($url)
  ->copyright($dom->find('div.copyrights > span.footer-logo')->innerHtml)
  ->updateFrequency(1)
  ->updatePeriod('hourly')
  ->ttl(60)
  ->appendTo($feed);

/*
  echo $feed->render();
  exit();
  */

$list = $dom->find('div.recommendationList div.container div.__homel div.__item');
foreach ($list as $content) {

  /*
  $html = $content->innerHtml;
  echo $html."\n";
  */

  /*
  $html = $content->find('div.__l a')->getAttribute('href');
  echo $html."\n";
  */


  $item  = new Item();
  $item
  ->title($content->find('div.__l a')->getAttribute('title'))
  ->description($content->find('div.__l a')->getAttribute('title'))
  ->url($content->find('div.__l a')->getAttribute('href'))
  ->enclosure($content->find('div.__l img')->getAttribute('src'))
//  ->pubDate(strtotime('Fri, 20 Nov 2020 03:08:42 +0100'))
//  ->content('<article><title>My first post</title><div id="content">Hello! I like sweets like Cupcake, Donut, Eclair, Froyo, Gingerbread, and so on...</div></atricle>')
  ->appendTo($channel);
  
}


echo $feed->render();
