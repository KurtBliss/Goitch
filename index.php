<?php
require 'vendor/autoload.php';
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
$client = new Client();
$response = $client->get("https://itch.io/game-assets/free?page=1&format=json");
$contents = json_decode($response->getBody()->getContents())->content;
$crawler = new Crawler($contents);
$div = $crawler->filter("div");

if ($div->count()) {
    $data = [];
    $current_entry = 0;
    foreach ($div as $element) {
        $check_for_new_entry = $element->getAttribute("data-game_id");
        if ($check_for_new_entry != "") {
            $current_entry = $check_for_new_entry;
            $data[$current_entry] = array();
        } else {
            $class = $element->getAttribute("class");
            if (str_contains($class, "game_thumb")) {
                $attrs = $element->firstChild->firstChild->attributes;
                foreach($attrs as $attr) {
                    if ($attr->name == "data-lazy_src") {
                        $data[$current_entry]["thumb"] = $attr->value;
                    }
                }
            } elseif (str_contains($class, "title game_link")) {
                $data[$current_entry]["title"] = $element->textContent;
                $data[$current_entry]["link"] =$element->firstElementChild->getAttribute("href");
            } elseif (str_contains($class, "game_text")) {
                $data[$current_entry]["description"] = $element->textContent;
            } elseif (str_contains($class, "game_author")) {
                $data[$current_entry]["author"] = $element->textContent;
                $data[$current_entry]["authorlink"] = $element->firstElementChild->getAttribute("href");
            }
        }
    }
    echo "<div><pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre></div>";
    
} else {
    echo "Element with class '.grid_outer' not found. <br>" . $contents;
}
