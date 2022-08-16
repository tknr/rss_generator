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

$url = 'https://jav-torrent.org/new';
$dom = new Dom();
$dom->loadFromUrl($url, $options);

$feed = new Feed();

$channel = new Channel();
$channel
  ->title($dom->find('meta[property="og:title"]')->getAttribute('content'))
  ->description($dom->find('meta[property="og:description"]')->getAttribute('content'))
  ->url($url)
  ->copyright('JAV-TORRENT.ORG')
  ->updateFrequency(1)
  ->updatePeriod('daily')
  ->ttl(120)
  ->appendTo($feed);

$list = $dom->find('div.content-main div.card div.container div.columns');
foreach ($list as $content) {

  $image = $content->find('div.column img.image')->getAttribute('data-src');
  $title = $content->find('div.is-5 div.card-content a')->innerHtml;
  $url = $content->find('div.is-5 div.card-content a')->getAttribute('href');
  $description = $content->find('div.is-5 div.card-content span.is-size-6')->innerHtml;
  $contents = $content->find('div.is-5 div.card-content')->innerHtml;

  $item  = new Item();
  $item
    ->title($title)
    ->description($description)
    ->url($url)
    ->enclosure($image,0,'image/jpeg')
    //->pubDate($pubDate)
    ->content($contents)
    ->appendTo($channel);
}


echo $feed->render();
