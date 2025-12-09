<?php
$posts = [
    ["id" => "post1", "title" => "Veebikalkulaator", "desc" => "Lihtne veebipõhine kalkulaator, mis on loodud HTML, CSS ja JavaScripti abil.", "date" => "2025-11-19", "category" => "Määratlemata"],
    ["id" => "post2", "title" => "Ajafunktsioonid PHP-s", "desc" => "PHP ajafunktsioonide õppimine.", "date" => "2025-11-19", "category" => "Funktsioonid"],
    ["id" => "post3", "title" => "Ilus pilt ja aeg", "desc" => "Ilus pilt ja kellaaja kuvamine.", "date" => "2025-11-19", "category" => "Määratlemata"],
    ["id" => "post4", "title" => "Git Käsud", "desc" => "Kohustuslike Git käskude põhjalik juhend versioonihalduseks.", "date" => "2025-11-19", "category" => "Määratlemata"],
    ["id" => "post5", "title" => "Tekstifunktsioonid", "desc" => "Erinevate tekstimanipulatsiooni funktsioonide uurimine PHP-s.", "date" => "2025-11-20", "category" => "Funktsioonid"],
    ["id" => "matem-funk", "title" => "Matemaatilised funktsioonid", "desc" => "Erinevate matemaatiliste funktsioonide uurimine PHP-s.", "date" => "2025-11-25", "category" => "Funktsioonid"],
    ["id" => "too-pilt", "title" => "Töö pildifailidega", "desc" => "Pildifailide töötlemine ja manipuleerimine PHP-s.", "date" => "2025-11-25", "category" => "Funktsioonid"],
    ["id" => "mobiilimall", "title" => "Mobiilseadmete mall", "desc" => "Responsiivse veebimalli loomine mobiilseadmete jaoks.", "date" => "2025-11-27", "category" => "Mobillimall"],
    ["id" => "anekdoot", "title" => "Anekdootide kogu", "desc" => "Kogumik naljakaid anekdoote erinevatel teemadel.", "date" => "2025-11-27", "category" => "Mobillimall"],
    ["id" => "mobillimalliKonspekt", "title" => "Mobiilimalli konspekt", "desc" => "Konspekt mobiilimalli loomisest ja rakendamisest.", "date" => "2025-11-27", "category" => "Mobillimall"],
    ["id" => "php-ab", "title" => "XAMPP Andmebaasi loomine PHPs", "desc" => "", "date" => "2025-12-02", "category" => "XAMPP"],
    ["id" => "php-ab2", "title" => "Toidupood", "desc" => "Toidupood kasutades andmebaasi", "date" => "2025-12-04", "category" => "XAMPP"],
    ["id" => "valimised", "title" => "Presidenti valimised", "desc" => "Lisa, Kustuta, lisa punkte, näita ja peida presiednte", "date" => "2025-12-04", "category" => "XAMPP"],
    
];
usort($posts, function($a, $b) {
    return strtotime($b["date"]) - strtotime($a["date"]);
});
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Maksim Tsikavsvili</title>
    <link rel="stylesheet" href="style.css">
    <script defer src="script.js"></script>
</head>

<body>

    <header class="topbar">
        <div class="site-name">Maksim Tsikavsvili</div>
        <nav>
            <a href="index.php">Home</a>

            <span class="nav-split">|</span>

            <div class="dropdown">
                <span>Kategooriad</span>
                <div class="dropdown-content">
                    <button onclick="filterPosts('Kõik')">Kõik</button>
                    <button onclick="filterPosts('XAMPP')">XAMPP</button>
                    <button onclick="filterPosts('Funktsioonid')">Funktsioonid</button>
                    <button onclick="filterPosts('Mobillimall')">Mobillimall</button>
                    <button onclick="filterPosts('Määratlemata')">Määratlemata</button>
                </div>
            </div>

            <span class="nav-split">|</span>

            <a href="https://maksimtsikvasvili24.thkit.ee">Vana Index</a>
        </nav>
    </header>

    <div class="logo-area">
        <img id="mainLogo" src="logo.png" class="main-logo" alt="Logo">
    </div>

    <div class="container">

        <div class="section-header">
            <h2>Tööd</h2>
        </div>

        <div class="grid">
            <?php foreach ($posts as $post) { ?>

            <?php if ($post['id'] === 'php-ab' || $post['id'] === 'php-ab2') { ?>
            <!-- php-ab and php-ab2 open in new tab -->
            <div class="card post" data-category="<?php echo $post['category']; ?>">
                <a href="content/<?php echo $post['id']; ?>/index.php" target="_blank" class="post-link">
                    <h3><?php echo $post['title']; ?></h3>
                    <p><?php echo $post['desc']; ?></p>
                    <small class="post-category"><?php echo $post['category']; ?></small><br>
                    <small><?php echo $post['date']; ?></small>
                </a>
            </div>

            <?php } else { ?>
            <!-- all other posts open inside index -->
            <div class="card post" data-category="<?php echo $post['category']; ?>"
                onclick="loadPost('<?php echo $post['id']; ?>')">
                <h3><?php echo $post['title']; ?></h3>
                <p><?php echo $post['desc']; ?></p>
                <small class="post-category"><?php echo $post['category']; ?></small><br>
                <small><?php echo $post['date']; ?></small>
            </div>

            <?php } ?>

            <?php } ?>
        </div>

        <div id="content-area"></div>

    </div>

    <footer class="footer">
        © Maksim Tsikavsvili 2025
    </footer>

</body>

</html>