<?php
/**
 * Configuration file for PHP work showcase
 * Add or remove entries as needed
 */

// Category colors configuration
$categoryColors = [
    'Andmebaasid' => '#7c3aed',
    'Mobilimall' => '#059669',
    'Funktsioonid' => '#0ea5e9',
    'Veebileht' => '#f59e0b',
    'Muu' => '#ef4444',
];

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

// Get category color
function getCategoryColor($category, $categoryColors) {
    return isset($categoryColors[$category]) ? $categoryColors[$category] : '#2563eb';
}

function normalizeHexColor($hex, $fallback = '#2563eb') {
    if (!is_string($hex)) return $fallback;
    $hex = trim($hex);

    if (preg_match('/^#([0-9a-fA-F]{3})$/', $hex, $m)) {
        $r = str_repeat($m[1][0], 2);
        $g = str_repeat($m[1][1], 2);
        $b = str_repeat($m[1][2], 2);
        return '#' . strtolower($r . $g . $b);
    }

    if (preg_match('/^#([0-9a-fA-F]{6})$/', $hex, $m)) {
        return '#' . strtolower($m[1]);
    }

    return $fallback;
}

function hexToRgb($hex) {
    $hex = normalizeHexColor($hex);
    $hex = ltrim($hex, '#');

    return [
        hexdec(substr($hex, 0, 2)),
        hexdec(substr($hex, 2, 2)),
        hexdec(substr($hex, 4, 2)),
    ];
}

function rgbToHex($r, $g, $b) {
    $r = max(0, min(255, (int)$r));
    $g = max(0, min(255, (int)$g));
    $b = max(0, min(255, (int)$b));
    return sprintf('#%02x%02x%02x', $r, $g, $b);
}

function mixHexColors($hexA, $hexB, $weightB) {
    $weightB = max(0, min(1, (float)$weightB));
    $weightA = 1 - $weightB;

    [$ar, $ag, $ab] = hexToRgb($hexA);
    [$br, $bg, $bb] = hexToRgb($hexB);

    return rgbToHex(
        round($ar * $weightA + $br * $weightB),
        round($ag * $weightA + $bg * $weightB),
        round($ab * $weightA + $bb * $weightB)
    );
}

// Get category theme colors for CSS variables.
// Returns: ['color' => '#rrggbb', 'bg' => '#rrggbb', 'border' => '#rrggbb']
function getCategoryTheme($category, $categoryColors) {
    $base = normalizeHexColor(getCategoryColor($category, $categoryColors));

    // Pastel background + border derived from the base color.
    $bg = mixHexColors($base, '#ffffff', 0.92);
    $border = mixHexColors($base, '#ffffff', 0.75);

    return [
        'color' => $base,
        'bg' => $bg,
        'border' => $border,
    ];
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