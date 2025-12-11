<?php
session_start();
require("config.php");

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
    $paring = $yhendus->prepare("UPDATE hinnakiri SET avalik = IF(avalik = 1, 0, 1) WHERE id=?");
    $paring->bind_param("i", $_REQUEST["muuda_avalik"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["kustuta"]) && isset($_SESSION["admin"])) {
    $paring = $yhendus->prepare("DELETE FROM hinnakiri WHERE id=?");
    $paring->bind_param("i", $_REQUEST["kustuta"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["nulli_hind"]) && isset($_SESSION["admin"])) {
    $paring = $yhendus->prepare("UPDATE hinnakiri SET hind = 0 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["nulli_hind"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["kustuta_kommentaar"]) && isset($_SESSION["admin"])) {
    $id = $_REQUEST["kustuta_kommentaar"];
    $paring = $yhendus->prepare("UPDATE hinnakiri SET kommentaarid = '' WHERE id=?");
    $paring->bind_param("i", $id);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["nimetus"]) && isset($_REQUEST["kirjeldus"]) && isset($_SESSION["admin"])) {
    $nimetus = $_REQUEST["nimetus"];
    $kirjeldus = $_REQUEST["kirjeldus"];
    $hind = isset($_REQUEST["hind"]) ? floatval($_REQUEST["hind"]) : 0;
    $avalik = isset($_REQUEST["avalik"]) ? 1 : 0;
    $pilt = isset($_REQUEST["pilt"]) ? $_REQUEST["pilt"] : '';
    $paring = $yhendus->prepare("INSERT INTO hinnakiri(nimetus, kirjeldus, hind, pilt, avalik) VALUES (?, ?, ?, ?, ?)");
    $paring->bind_param("ssdsi", $nimetus, $kirjeldus, $hind, $pilt, $avalik);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

$query = "SELECT id, nimetus, kirjeldus, hind, pilt, avalik, kommentaarid FROM hinnakiri";
$kask = $yhendus->prepare($query);
$kask->execute();
$tulemus = $kask->get_result();
$tooted = [];
while($rida = $tulemus->fetch_assoc()) $tooted[] = $rida;
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Tooted - Toidupood</title>
</head>

<body>
    <nav>
        <div class="logo">Toidupood</div>
        <div>
            <a href="index.php">Avaleht</a>
            <a href="hinnakiri.php">Tooted</a>
            <a href="galerii.php">Galerii</a>
            <?php if (isset($_SESSION["admin"])): ?>
            <a href="?logout=1">Logi välja</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="page-header">
        <h1>Admin - Toodete haldus</h1>
        <?php if (isset($_SESSION["admin"])): ?>
        <p class="logout-link"><a href="?logout=1" class="btn">Logi välja</a></p>
        <?php endif; ?>
    </section>

    <section class="menu-section">
        <?php if (isset($_SESSION["admin"])): ?>
        <table class="admin-table">
            <tr>
                <th>Nimetus</th>
                <th>Pilt</th>
                <th>Kirjeldus</th>
                <th>Hind</th>
                <th>Tegevus</th>
            </tr>
            <?php foreach($tooted as $t): 
$hiddenClass = (isset($t['avalik']) && $t['avalik'] == 0) ? ' class="hidden-row"' : '';
?>
            <tr<?=$hiddenClass?>>
                <td><?=htmlspecialchars($t['nimetus'])?></td>
                <td>
                    <img src="<?=$t['pilt'] ? $t['pilt'] : 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop'?>"
                        class="table-img">
                </td>
                <td><?=htmlspecialchars($t['kirjeldus'])?></td>
                <td><?=$t['hind']?></td>
                <td>
                    <?php 
$status = isset($t['avalik']) && $t['avalik'] ? 'Peida' : 'Näita';
?>
                    <a href="?muuda_avalik=<?=$t['id']?>" class="btn btn-small"><?=$status?></a>
                    <a href="?nulli_hind=<?=$t['id']?>" class="btn btn-small">Nulli hind</a>
                    <a href="?kustuta=<?=$t['id']?>" class="btn btn-small btn-delete"
                        onclick="return confirm('Kas oled kindel?')">Kustuta</a>
                </td>
                </tr>
                <?php if (!empty($t['kommentaarid'])): ?>
                <tr<?=$hiddenClass?> class="comment-row">
                    <td colspan="5">
                        <strong>Kommentaarid:</strong>
                        <div class="comments-box">
                            <?php echo nl2br(htmlspecialchars($t['kommentaarid'])); ?>
                        </div>
                        <a href="?kustuta_kommentaar=<?=$t['id']?>" class="btn btn-small btn-delete"
                            onclick="return confirm('Kustuta kõik kommentaarid?')">Kustuta kõik</a>
                    </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
        </table>

        <h2>Lisa uus toode</h2>
        <form action="?" class="admin-form-full">
            <div class="form-group">
                <label>Nimetus:</label>
                <input type="text" name="nimetus" required class="form-input">
            </div>
            <div class="form-group">
                <label>Kirjeldus:</label>
                <input type="text" name="kirjeldus" required class="form-input">
            </div>
            <div class="form-group">
                <label>Pildi URL:</label>
                <input type="text" name="pilt" class="form-input">
            </div>
            <div class="form-group">
                <label>Hind:</label>
                <input type="number" name="hind" value="0" min="0" step="0.01" class="form-input">
            </div>
            <div class="form-group">
                <label><input type="checkbox" name="avalik" checked> Avalik</label>
            </div>
            <div class="form-group">
                <input type="submit" value="Lisa toode" class="btn">
            </div>
        </form>
        <?php else: ?>
        <p style="text-align: center;">Palun logi sisse.</p>
        <?php endif; ?>
    </section>

    <?php if (!isset($_SESSION["admin"])): ?>
    <section class="menu-section">
        <h2>Admin Login</h2>
        <form action="?" method="post" class="login-form">
            <div class="form-group">
                <label>Parool:</label>
                <input type="password" name="parool" required class="form-input">
            </div>
            <div class="form-group">
                <input type="submit" name="login" value="Logi sisse" class="btn">
            </div>
        </form>
    </section>
    <?php endif; ?>

</body>

</html>