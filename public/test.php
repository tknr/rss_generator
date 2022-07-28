<?php

require_once __DIR__ . '/../vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Bhaktaraz\RSSGenerator\Item;
use Bhaktaraz\RSSGenerator\Feed;
use Bhaktaraz\RSSGenerator\Channel;


header('Content-Type: text/xml');

$client = new Client();

$url = 'https://www.comicbunch.com/manga/bunch/kodomowo/';
$response = $client->request('GET', $url);

$httpStatusCode = $response->getStatusCode();

if ($httpStatusCode !== 200) {
  echo "ステータスコードが正常ではありませんでした。\n";
  exit(1);
}

//var_dump($response->getBody()->getContents());

$htmlSource = $response->getBody()->getContents();
$crawler = new Crawler($htmlSource);

$feed = new Feed();
$channel = new Channel();


$channel
  ->title($crawler->filter('meta[property="og:title"]')->attr('content'))
  ->description($crawler->filter('meta[property="og:description"]')->attr('content'))
  ->url($url)
  ->copyright($crawler->filter('footer.push > div.inner > p')->text())
  ->updateFrequency(1)
  ->updatePeriod('hourly')
  ->ttl(60)
  ->appendTo($feed);



$list = $crawler->filter('div.read > div.inner > div.backnumber > ul.cf > li > a');
foreach($list as $obj){
  var_dump($obj);
  $href = $obj->attr('href');
  var_dump($href);
  $item  = new Item();
  $item
  ->title($obj->nodeValue)
  ->description($obj->textContent)
  ->url('https://example.com/?p=1')
//  ->pubDate(strtotime('Fri, 20 Nov 2020 03:08:42 +0100'))
//  ->content('<article><title>My first post</title><div id="content">Hello! I like sweets like Cupcake, Donut, Eclair, Froyo, Gingerbread, and so on...</div></atricle>')
  ->appendTo($channel);
}


echo $feed->render();
