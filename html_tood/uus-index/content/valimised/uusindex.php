<?php
session_start();
require('funktsioonid.php');

// Logi sisse
if (isset($_REQUEST["login"])) {
    if (login_admin($_REQUEST["parool"])) {
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Logi välja
if (isset($_REQUEST["logout"])) {
    logout_admin();
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Muuda avalikuks/peidetud
if (isset($_REQUEST["muuda_avalik"]) && kontrolli_admin()) {
    muuda_avalik($_REQUEST["muuda_avalik"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Kustuta president
if (isset($_REQUEST["kustuta"]) && kontrolli_admin()) {
    kustuta_president($_REQUEST["kustuta"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Nulli punktid
if (isset($_REQUEST["nulli_punktid"]) && kontrolli_admin()) {
    nulli_punktid($_REQUEST["nulli_punktid"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lisa 1 punkt
if (isset($_REQUEST["lisa1punkt"])) {
    lisa1punkt($_REQUEST["lisa1punkt"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lahuta 1 punkt
if (isset($_REQUEST["lahuta1punkt"])) {
    lahuta1punkt($_REQUEST["lahuta1punkt"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lisa uus president
if (isset($_REQUEST["president"]) && isset($_REQUEST["pilt"])) {
    $punktid = kontrolli_admin() && isset($_REQUEST["punktid"])
               ? intval($_REQUEST["punktid"]) : 0;
    $avalik = kontrolli_admin() && isset($_REQUEST["avalik"]) ? 1 : 1;
    lisa_president($_REQUEST["president"], $_REQUEST["pilt"],
                   $punktid, $avalik);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lisa kommentaar
if (isset($_REQUEST["lisa_kommentaar"])
    && isset($_REQUEST["kommentaar"])) {
    lisa_kommentaar($_REQUEST["lisa_kommentaar"],
                    $_REQUEST["kommentaar"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Kustuta kommentaarid
if (isset($_REQUEST["kustuta_kommentaar"]) && kontrolli_admin()) {
    kustuta_kommentaarid($_REQUEST["kustuta_kommentaar"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Valimiste leht</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Eesti presidenti valimised</h1>
    <?php if (kontrolli_admin()): ?>
    <p style="text-align: center;">
        <a href="?logout=1">Logi välja</a>
    </p>
    <?php endif; ?>
    <p style="text-align: center;">
        <a href="galerii.php">Vaata galeriid</a>
    </p>

    <table>
        <tr>
            <th>Nimi</th>
            <th>Pilt</th>
            <th>Punktid</th>
            <th>Lisamisaeg</th>
            <th>Tegevus</th>
        </tr>
        <?php naitaTabel(); ?>
    </table>

    <h2>Lisa uus president</h2>
    <form action="?" method="post">
        <div>
            <label for="president">Presidenti nimi: </label>
            <input type="text" name="president" id="president" required>
        </div>
        <div>
            <label for="pilt">Pildi URL: </label>
            <input type="text" name="pilt" id="pilt" pattern=".*\.(jpg|jpeg|png|gif|webp)$" required>
        </div>
        <?php if (kontrolli_admin()): ?>
        <div>
            <label for="punktid">Punktid: </label>
            <input type="number" name="punktid" id="punktid" value="0" min="0">
        </div>
        <div>
            <label for="avalik">
                <input type="checkbox" name="avalik" id="avalik" checked> Avalik
            </label>
        </div>
        <?php endif; ?>
        <div>
            <input type="submit" value="Lisa president">
        </div>
    </form>

    <?php if (!kontrolli_admin()): ?>
    <h2>Admin Login</h2>
    <form action="?" method="post">
        <label for="parool">Parool: </label>
        <input type="password" name="parool" id="parool" required>
        <input type="submit" name="login" value="Logi sisse">
    </form>
    <?php endif; ?>
</body>

</html>