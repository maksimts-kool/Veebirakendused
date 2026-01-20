<?php
function fetchAndDisplayRSS($url, $kogus, $sourceName) {
    echo "<h2>{$sourceName} RSS:</h2>";

    libxml_use_internal_errors(true);
    $feed = simplexml_load_file($url);
    if ($feed === false) {
        echo "<p>Viga RSS kanali laadimisel.</p>";
        libxml_clear_errors();
        return;
    }
    libxml_clear_errors();

    $loendur = 0;
    echo "<ul>";
    foreach ($feed->channel->item as $item) {
        if ($loendur >= $kogus) {
            break;
        }
        $loendur++;

        $imageUrl = (isset($item->enclosure) && isset($item->enclosure['url']))
            ? $item->enclosure['url']
            : '';
        echo "<li>";
        echo "<img src='{$imageUrl}'>";
        echo "<a href='{$item->link}' target='_blank' rel='noopener noreferrer'>{$item->title}</a>";
        echo "<br>";
        echo $item->description;
        echo "<br>";
        if ($item->pubDate !== '') {
            echo date("d.m.Y H:i", strtotime($item->pubDate));
        }
        echo "</li>";
    }
    echo "</ul>";
}