<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS Uudised</title>
</head>

<body>
    <h1>RSS (Rich Site Summary või Really Simple Syndication)</h1>
    <p>on XML-il põhinev andmevorming Internetis kasutamiseks,
        peamiselt veebilehtede sisukorra või uudiste kokkuvõtete tegemiseks.</p>
    <?php
        echo "<p>ERR RSS kanali uudised:</p>";
        $feed = simplexml_load_file('https://www.err.ee/rss');
        //echo $feed->channel[0]->item[1]->title;
        echo "<ul>";
        foreach ($feed->channel->item as $item) {
            echo "<li>";
            echo "<a href='" . $item->link . "' target='_blank'>" . $item->title . "</a>";
            echo "<br>";
            echo $item->description;
            echo "<br>";
            echo date("d.m.Y H:i", strtotime($item->pubDate));
            echo "</li>";
            
        }
        echo "</ul>";
        ?>
</body>

</html>