<?php
require('funktsioonid.php'); 

if (isset($_REQUEST["lisa1punkt"])) {
    lisa1punkt($_REQUEST["lisa1punkt"]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
if (isset($_REQUEST["president"]) && isset($_REQUEST["pilt"])) {
    lisa_president($_REQUEST["president"], $_REQUEST["pilt"]);
    header("Location: ".$_SERVER['PHP_SELF']);
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