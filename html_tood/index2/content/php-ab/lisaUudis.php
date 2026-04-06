<?php
require('config.php');

app_require_basic_auth();
app_handle_ip_request_submission([
    'return_to' => 'lisaUudis.php',
    'reason' => 'Create news entry',
]);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nimi'])) {
    app_require_authorized_ip_for_action('Create news entry', 'lisaUudis.php');

    $nimi = $_POST['nimi'];
    $kirjeldus = $_POST['kirjeldus'];
    $kuupaev = $_POST['kuupaev'];
    $tuju = $_POST['tuju'];

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
<?=app_render_ip_access_panel([
    'return_to' => 'lisaUudis.php',
    'reason' => 'Create news entry',
]);?>

<div style="text-align:center;">
    <a href="index.php">
        <button>Tagasi</button>
    </a>
    <a href="../../ip-admin.php">
        <button>IP админка</button>
    </a>
</div>

<form action="" method="post">
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
