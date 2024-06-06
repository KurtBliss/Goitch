<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

$client = new Client();
$response = $client->get("https://itch.io/game-assets/free");

$contents = $response->getBody()->getContents();

$crawler = new Crawler($contents);

$div = $crawler->filter('.grid_outer');

if ($div->count()) {
    echo $div->html();
} else {
    echo "Element with class '.grid_outer' not found.";
}
?>
