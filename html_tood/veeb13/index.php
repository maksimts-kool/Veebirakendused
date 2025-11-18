<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <title>Maksim veebirakenduste koduleht</title>
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/ruhmastyle.css">
    <script src="js/aeg.js"></script>
    <script src="js/script.js"></script>
</head>

<body>
    <?php
    include("header.php");
    ?>
    <?php
    include("nav.php");
    ?>
    <div class="flex-container">
        <div>
            <?php
            if (isset($_GET['link'])) {
                $allowed_pages = ['phpkas.php', 'avaleht.php', 'too1.php', 'ruhmaleht.php', "ajafunk.php"];
                $file = $_GET['link'] ?? '';
                if (in_array($file, $allowed_pages)) {
                    include('content/' . $file);
                } else {
                    echo "Invalid page.";
                }
            }
            ?>
        </div>
        <div>
            <img src="images/pilt.png" alt="">
            <h2>Aeg</h2>
            <button onclick="naitaKuupaevaJaKellaega()">TÄNA ON</button>
            <p id="kuupaev"></p>
            <p id="kellaaeg"></p>
            <p id="kokku"></p>
            <button onclick="arvutaSynnipaevani()">Minu sünnipäevani</button>
            <p id="teade"></p>
            <p id="vahe"></p>
        </div>
    </div>
    <?php
    include("footer.php");
    ?>
</body>

</html>