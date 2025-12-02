<?php
require('config.php');

if (isset($_REQUEST['nimi'])) {
    $nimi = $_REQUEST['nimi'];
    $kirjeldus = $_REQUEST['kirjeldus'];
    $kuupaev = $_REQUEST['kuupaev'];
    $tuju = $_REQUEST['tuju'];

    $paring = $connect->prepare(
        "INSERT INTO uudised (pealkiri, kirjeldus, kuupaev, tuju) VALUES (?, ?, ?, ?)"
    );
    $paring->bind_param("ssss", $nimi, $kirjeldus, $kuupaev, $tuju);
    $paring->execute();
    $paring->close();

    header("Location: index.php");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Lisa uudis</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<h1>Lisa uus uudis</h1>

<div style="text-align:center;">
    <a href="../../index.php">
        <button>Tagasi</button>
    </a>
</div>

<form action="">
    <label for="nimi">Pealkiri</label><br>
    <input type="text" name="nimi" id="nimi" required><br><br>

    <label for="kirjeldus">Kirjeldus</label><br>
    <textarea name="kirjeldus" id="kirjeldus" rows="4" cols="40" required></textarea><br><br>

    <label for="kuupaev">Kuupäev</label><br>
    <input type="date" name="kuupaev" id="kuupaev" required><br><br>

    <label for="tuju">Värv</label><br>
    <select name="tuju" id="tuju">
        <option value="green">Roheline</option>
        <option value="red">Punane</option>
        <option value="yellow">Kollane</option>
        <option value="blue">Sinine</option>
    </select><br><br>

    <input type="submit" value="Salvesta">
</form>

</body>
</html>