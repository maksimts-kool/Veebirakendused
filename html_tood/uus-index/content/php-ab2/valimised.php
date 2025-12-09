<?php 
session_start();
require('config.php');

if (isset($_REQUEST["login"])) {
    if ($_REQUEST["parool"] === "lolavalik") {
        $_SESSION["admin"] = true;
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}

if (isset($_REQUEST["logout"])) {
    session_destroy();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["muuda_avalik"]) && isset($_SESSION["admin"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET avalik = IF(avalik = 1, 0, 1) WHERE id=?");
    $paring->bind_param("i", $_REQUEST["muuda_avalik"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["kustuta"]) && isset($_SESSION["admin"])) {
    $paring = $yhendus->prepare("DELETE FROM valimised WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["nulli_punktid"]) && isset($_SESSION["admin"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid = 0 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["nulli_punktid"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["lisa1punkt"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid = punktid + 1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["president"]) && isset($_REQUEST["pilt"])) {
    $president = $_REQUEST["president"];
    $pilt = $_REQUEST["pilt"];
    $punktid = isset($_REQUEST["punktid"]) ? intval($_REQUEST["punktid"]) : 0;
    $avalik = isset($_REQUEST["avalik"]) ? 1 : 0;
    $paring = $yhendus->prepare("INSERT INTO valimised(president, pilt, punktid, avalik, lisamisaeg) VALUES (?, ?, ?, ?, NOW())");
    $paring->bind_param("ssii", $president, $pilt, $punktid, $avalik);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Valimised - Toidupood</title>
</head>
<body>
<nav>
<div class="logo">Toidupood</div>
<div>
<a href="index.php">Avaleht</a>
<a href="hinnakiri.php">Tooted</a>
<a href="galerii.php">Galerii</a>
<a href="admin.php">Admin</a>
<a href="valimised.php">Valimised</a>
</div>
</nav>

<section class="hero">
<h1>Eesti presidenti valimised</h1>
<?php if (isset($_SESSION["admin"])): ?>
<p style="text-align: center;"><a href="?logout=1" class="btn">Logi välja</a></p>
<?php endif; ?>
</section>

<section class="menu-section">
<table style="width: 100%; background: white; border-collapse: collapse;">
<tr style="background: #4CAF50; color: white;">
<th style="padding: 12px; text-align: left;">Nimi</th>
<th style="padding: 12px; text-align: left;">Pilt</th>
<th style="padding: 12px; text-align: left;">Punktid</th>
<th style="padding: 12px; text-align: left;">Lisamisaeg</th>
<th style="padding: 12px; text-align: left;">Tegevus</th>
</tr>
<?php
$query = isset($_SESSION["admin"]) ? "SELECT id, president, pilt, punktid, lisamisaeg, avalik FROM valimised" : "SELECT id, president, pilt, punktid, lisamisaeg FROM valimised WHERE avalik = 1";
$paring = $yhendus->prepare($query);
$paring->execute();
$tulemus = $paring->get_result();
while ($rida = $tulemus->fetch_assoc()) {
    $hiddenStyle = (isset($rida['avalik']) && $rida['avalik'] == 0) ? ' style="background-color: #ffcccc;"' : '';
    echo "<tr$hiddenStyle>";
    echo "<td style='padding: 12px; border-bottom: 1px solid #ddd;'>{$rida['president']}</td>";
    echo "<td style='padding: 12px; border-bottom: 1px solid #ddd;'><img src='{$rida['pilt']}' alt='{$rida['president']}' style='width: 100px; border-radius: 10px;'></td>";
    echo "<td style='padding: 12px; border-bottom: 1px solid #ddd;'>{$rida['punktid']}</td>";
    echo "<td style='padding: 12px; border-bottom: 1px solid #ddd;'>{$rida['lisamisaeg']}</td>";
    echo "<td style='padding: 12px; border-bottom: 1px solid #ddd;'>";
    if (isset($_SESSION["admin"])) {
        $status = isset($rida['avalik']) && $rida['avalik'] ? 'Peida' : 'Näita';
        echo "<a href='?muuda_avalik={$rida['id']}' class='btn' style='padding: 5px 10px; margin: 2px;'>$status</a>";
        echo "<a href='?nulli_punktid={$rida['id']}' class='btn' style='padding: 5px 10px; margin: 2px;'>Nulli punktid</a>";
        echo "<a href='?kustuta={$rida['id']}' class='btn' style='padding: 5px 10px; margin: 2px; background: #f44336;' onclick='return confirm(\"Kas oled kindel?\")'>Kustuta</a>";
    } else {
        echo "<a href='?lisa1punkt={$rida['id']}' class='btn' style='padding: 5px 10px;'>Lisa 1 punkt</a>";
    }
    echo "</td>";
    echo "</tr>";
}
?>
</table>
</section>

<section class="menu-section">
<h2>Lisa uus president</h2>
<form action="?" style="max-width: 600px; margin: 0 auto;">
<div style="margin: 10px 0;">
<label for="president">Presidenti nimi: </label>
<input type="text" name="president" id="president" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
</div>
<div style="margin: 10px 0;">
<label for="pilt">Pildi URL: </label>
<input type="text" name="pilt" id="pilt" pattern=".*\.(jpg|jpeg|png|gif|webp)$" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
</div>
<?php if (isset($_SESSION["admin"])): ?>
<div style="margin: 10px 0;">
<label for="punktid">Punktid: </label>
<input type="number" name="punktid" id="punktid" value="0" min="0" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
</div>
<div style="margin: 10px 0;">
<label for="avalik">
<input type="checkbox" name="avalik" id="avalik" checked> Avalik
</label>
</div>
<?php endif; ?>
<div style="margin: 10px 0;">
<input type="submit" value="Lisa president" class="btn">
</div>
</form>
</section>

<?php if (!isset($_SESSION["admin"])): ?>
<section class="menu-section">
<h2>Admin Login</h2>
<form action="?" method="post" style="max-width: 400px; margin: 0 auto;">
<div style="margin: 10px 0;">
<label for="parool">Parool: </label>
<input type="password" name="parool" id="parool" required style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px;">
</div>
<div style="margin: 10px 0;">
<input type="submit" name="login" value="Logi sisse" class="btn">
</div>
</form>
</section>
<?php endif; ?>

<footer>
<p>&copy; 2024 Toidupood. Kõik õigused kaitstud.</p>
</footer>

</body>
</html>
