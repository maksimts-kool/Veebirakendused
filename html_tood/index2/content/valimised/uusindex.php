<?php
require('funktsioonid.php');

app_handle_ip_request_submission([
    'return_to' => 'uusindex.php',
    'reason' => 'Modify election data',
]);

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
    app_require_authorized_ip_for_action('Change candidate visibility', 'uusindex.php');
    muuda_avalik($_REQUEST["muuda_avalik"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Kustuta president
if (isset($_REQUEST["kustuta"]) && kontrolli_admin()) {
    app_require_authorized_ip_for_action('Delete candidate', 'uusindex.php');
    kustuta_president($_REQUEST["kustuta"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Nulli punktid
if (isset($_REQUEST["nulli_punktid"]) && kontrolli_admin()) {
    app_require_authorized_ip_for_action('Reset candidate points', 'uusindex.php');
    nulli_punktid($_REQUEST["nulli_punktid"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lisa 1 punkt
if (isset($_REQUEST["lisa1punkt"])) {
    app_require_authorized_ip_for_action('Increase candidate points', 'uusindex.php');
    lisa1punkt($_REQUEST["lisa1punkt"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lahuta 1 punkt
if (isset($_REQUEST["lahuta1punkt"])) {
    app_require_authorized_ip_for_action('Decrease candidate points', 'uusindex.php');
    lahuta1punkt($_REQUEST["lahuta1punkt"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lisa uus president
if (isset($_REQUEST["president"]) && isset($_REQUEST["pilt"])) {
    app_require_authorized_ip_for_action('Create candidate', 'uusindex.php');
    $punktid = kontrolli_admin() && isset($_REQUEST["punktid"])
               ? intval($_REQUEST["punktid"]) : 0;
    $avalik = kontrolli_admin() && isset($_REQUEST["avalik"]) ? 1 : 0;
    lisa_president($_REQUEST["president"], $_REQUEST["pilt"],
                   $punktid, $avalik);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Lisa kommentaar
if (isset($_REQUEST["lisa_kommentaar"])
    && isset($_REQUEST["kommentaar"])) {
    app_require_authorized_ip_for_action('Add election comment', 'uusindex.php');
    lisa_kommentaar($_REQUEST["lisa_kommentaar"],
                    $_REQUEST["kommentaar"]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Kustuta kommentaarid
if (isset($_REQUEST["kustuta_kommentaar"]) && kontrolli_admin()) {
    app_require_authorized_ip_for_action('Delete election comments', 'uusindex.php');
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
    <?=app_render_ip_access_panel([
        'return_to' => 'uusindex.php',
        'reason' => 'Modify election data',
    ]);?>
    <?php if (kontrolli_admin()): ?>
    <p style="text-align: center;">
        <a href="?logout=1">Logi välja</a>
    </p>
    <p style="text-align: center;"><a href="../../ip-admin.php">IP admin</a></p>
    <?php endif; ?>

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
