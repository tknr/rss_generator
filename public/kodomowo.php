<?php

require_once __DIR__ . '/../vendor/autoload.php';

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

use Bhaktaraz\RSSGenerator\Item;
use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\Channel;


header('Content-Type: text/xml');

$options = new Options();
$options->setEnforceEncoding('utf8');

$url = 'https://www.comicbunch.com/manga/bunch/kodomowo/';
$dom = new Dom();
$dom->loadFromUrl($url, $options);


$feed = new Feed();

$channel = new Channel();
$channel
->title($dom->find('meta[property="og:title"]')->getAttribute('content'))
->description($dom->find('meta[property="og:description"]')->getAttribute('content'))
  ->url($url)
  ->copyright('SHINCHOSHA')
  ->updateFrequency(1)
  ->updatePeriod('daily')
  ->ttl(120)
  ->appendTo($feed);

$list = $dom->find('div.read div.inner div.backnumber ul.cf li');
foreach($list as $content){

  $item  = new Item();
  $item
  ->title($content->find('a')->innerHtml)
  ->description($content->find('a')->innerHtml)
  ->url($url.$content->find('a')->getAttribute('href'))
//  ->pubDate(strtotime('Fri, 20 Nov 2020 03:08:42 +0100'))
//  ->content('<article><title>My first post</title><div id="content">Hello! I like sweets like Cupcake, Donut, Eclair, Froyo, Gingerbread, and so on...</div></atricle>')
  ->appendTo($channel);
  
}


 echo $feed->render();
