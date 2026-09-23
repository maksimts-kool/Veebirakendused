<?php
/**
 * Configuration file for PHP work showcase
 * Add or remove entries as needed
 */

$phpWorks = [
    [
        'title' => 'Jalgratta Eksami Veebileht',
        'description' => 'Veebileht jalgratta eksami info ja registreerimisega.',
        'date' => '2026-01-12',
        'category' => 'Veebileht',
        'link' => './content/jalgratta-eksam/index.php'
    ],
    [
        'title' => 'Andmebaasi loomine ja haldus',
        'description' => 'Lihtne andmebaasi haldamise rakendus PHP ja MySQL abil.',
        'date' => '2025-12-02',
        'category' => 'Andmebaasid',
        'link' => './content/php-ab/index.php'
    ],
    [
        'title' => 'Toidupood PHP ja MySQL',
        'description' => 'Veebipood toiduainete müügiks, kasutades PHP ja MySQLi.',
        'date' => '2025-12-04',
        'category' => 'Veebileht',
        'link' => './content/php-ab2/index.php'
    ],
    [
        'title' => 'Valimiste Süsteem',
        'description' => 'Valimiste haldamise ja hääletamise süsteem koos tulemuste visualiseerimisega.',
        'date' => '2025-12-09',
        'category' => 'Andmebaasid',
        'link' => './content/valimised/index.php'
    ],
    [
        'title' => 'XML ja PHP',
        'description' => 'XML andmete töötlemine PHP-ga. Autode andmete lugemine ja kuvamine.',
        'date' => '2026-01-08',
        'category' => 'Muu',
        'link' => './content/xmlphp/index.php'
    ],
    [
        'title' => 'Mobiilimall Nädalamenüü',
        'description' => 'Dünaamiline nädalamenüü mobiilimalli abil loodud veebileht.',
        'date' => '2025-11-27',
        'category' => 'Mobilimall',
        'link' => './content/mobiilimall/index.php'
    ],
    [
        'title' => 'Matemaatilised Funktsioonid',
        'description' => 'PHP matemaatiliste funktsioonide kasutamine ja demonstratsioon.',
        'date' => '2025-11-25',
        'category' => 'Funktsioonid',
        'link' => './content/matem-funk/index.php'
    ],
    [
        'title' => 'Pilditöötlus',
        'description' => 'Piltide üleslaadimine ja töötlemine PHP-ga.',
        'date' => '2025-11-25',
        'category' => 'Funktsioonid',
        'link' => './content/too-pilt/index.php'
    ],
    [
        'title' => 'Anekdoodid',
        'description' => 'Anekdootide veebileht dünaamilise sisuga ja failidest lugemisega.',
        'date' => '2025-11-27',
        'category' => 'Mobilimall',
        'link' => './content/anekdoot/index.php'
    ],    
    [
        'title' => 'Veebikalkulaator',
        'description' => 'Lihtne veebipõhine kalkulaator, mis on loodud HTML, CSS ja JavaScripti abil.',
        'date' => '2025-11-19',
        'category' => 'Veebileht',
        'link' => './content/post1/index.php'
    ],
    [
        'title' => 'Ajafunktsioonid PHP-s',
        'description' => 'PHP ajafunktsioonide õppimine.',
        'date' => '2025-11-19',
        'category' => 'Funktsioonid',
        'link' => './content/post2/index.php'
    ],
    [
        'title' => 'Ilus pilt ja aeg',
        'description' => 'Ilus pilt ja kellaaja kuvamine.',
        'date' => '2025-11-19',
        'category' => 'Funktsioonid',
        'link' => './content/post3/index.php'
    ],
    [
        'title' => 'Git Käsud',
        'description' => 'Kohustuslike Git käskude põhjalik juhend versioonihalduseks.',
        'date' => '2025-11-19',
        'category' => 'Muu',
        'link' => './content/post4/index.php'
    ],
    [
        'title' => 'Tekstifunktsioonid',
        'description' => 'Erinevate tekstimanipulatsiooni funktsioonide uurimine PHP-s.',
        'date' => '2025-11-20',
        'category' => 'Funktsioonid',
        'link' => './content/post5/index.php'
    ],
    [
        'title' => 'Mobiilimalli konspekt',
        'description' => 'Konspekt mobiilimalli loomisest ja rakendamisest.',
        'date' => '2025-11-27',
        'category' => 'Mobilimall',
        'link' => './content/mobillimalliKonspekt/index.php'
    ],
];

// Get all unique categories
function getCategories($works) {
    $categories = array_unique(array_column($works, 'category'));
    sort($categories);
    return $categories;
}

// Kuupäev eesti keeles, nt "12. jaanuar 2026"
function formatWorkDate($date) {
    $months = ['jaanuar', 'veebruar', 'märts', 'aprill', 'mai', 'juuni', 'juuli',
        'august', 'september', 'oktoober', 'november', 'detsember'];
    $time = strtotime($date);
    return date('j', $time) . '. ' . $months[(int)date('n', $time) - 1] . ' ' . date('Y', $time);
}

// Get works by category
function getWorksByCategory($works, $category) {
    return array_filter($works, function($work) use ($category) {
        return $work['category'] === $category;
    });
}

// Get latest works (limit)
function getLatestWorks($works, $limit = 3) {
    usort($works, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    return array_slice($works, 0, $limit);
}

// Sort works by date (newest first)
function sortWorksByDate($works) {
    usort($works, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    return $works;
}