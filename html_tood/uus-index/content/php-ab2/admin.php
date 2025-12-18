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

if (isset($_REQUEST["edit_id"]) && isset($_REQUEST["nimetus"]) && isset($_REQUEST["kirjeldus"]) && isset($_SESSION["admin"])) {
    $id = intval($_REQUEST["edit_id"]);
    $nimetus = $_REQUEST["nimetus"];
    $kirjeldus = $_REQUEST["kirjeldus"];
    $hind = isset($_REQUEST["hind"]) ? floatval($_REQUEST["hind"]) : 0;
    if (isset($_REQUEST["avalik"])) {
        $avalik = 1;
    } else {
        $avalik = 0;
    }
    $pilt = isset($_REQUEST["pilt"]) ? $_REQUEST["pilt"] : '';
    $paring = $yhendus->prepare("UPDATE hinnakiri SET nimetus=?, kirjeldus=?, hind=?, pilt=?, avalik=? WHERE id=?");
    $paring->bind_param("ssdsii", $nimetus, $kirjeldus, $hind, $pilt, $avalik, $id);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["nimetus"]) && isset($_REQUEST["kirjeldus"]) && isset($_SESSION["admin"]) && !isset($_REQUEST["edit_id"])) {
    $nimetus = $_REQUEST["nimetus"];
    $kirjeldus = $_REQUEST["kirjeldus"];
    $hind = isset($_REQUEST["hind"]) ? floatval($_REQUEST["hind"]) : 0;
    if (isset($_REQUEST["avalik"])) {
        $avalik = 1;
    } else {
        $avalik = 0;
    }
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

$edit_product = null;
if (isset($_GET["edit"]) && isset($_SESSION["admin"])) {
    $edit_id = intval($_GET["edit"]);
    foreach ($tooted as $t) {
        if ($t["id"] === $edit_id) {
            $edit_product = $t;
            break;
        }
    }
}
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
$hiddenClass = '';
if (isset($t['avalik']) && $t['avalik'] == 0) {
    $hiddenClass = ' class="hidden-row"';
}
?>
            <tr<?=$hiddenClass?>>
                <td><?=htmlspecialchars($t['nimetus'])?></td>
                <td>
                    <?php 
                    if ($t['pilt']) {
                        $piltURL = $t['pilt'];
                    } else {
                        $piltURL = 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop';
                    }
                    ?>
                    <img src="<?=htmlspecialchars($piltURL)?>" class="table-img"
                        alt="<?=htmlspecialchars($t['nimetus'])?>">
                </td>
                <td><?=htmlspecialchars($t['kirjeldus'])?></td>
                <td>€<?=htmlspecialchars($t['hind'])?></td>
                <td>
                    <?php 
                    if (isset($t['avalik']) && $t['avalik']) {
                        $status = 'Peida';
                    } else {
                        $status = 'Näita';
                    }
                    ?>
                    <a href="?muuda_avalik=<?=$t['id']?>" class="btn btn-small"><?=$status?></a>
                    <a href="?edit=<?=$t['id']?>" class="btn btn-small" style="background-color: #2196F3;">Muuda</a>
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

        <h2>
            <?php 
            if ($edit_product) {
                echo 'Muuda toodet';
            } else {
                echo 'Lisa uus toode';
            }
            ?>
        </h2>
        <form action="?" method="post" class="admin-form-full">
            <?php if ($edit_product): ?>
            <input type="hidden" name="edit_id" value="<?=$edit_product['id']?>">
            <a href="?" class="btn" style="margin-bottom: 15px; display: inline-block;">Loobu muudatusest</a>
            <?php endif; ?>
            <div class="form-group">
                <label>Nimetus:</label>
                <?php 
                if ($edit_product) {
                    $nimetuseVaartus = htmlspecialchars($edit_product['nimetus']);
                } else {
                    $nimetuseVaartus = '';
                }
                ?>
                <input type="text" name="nimetus" required class="form-input" value="<?=$nimetuseVaartus?>">
            </div>
            <div class="form-group">
                <label>Kirjeldus:</label>
                <?php 
                if ($edit_product) {
                    $kirjeldusVaartus = htmlspecialchars($edit_product['kirjeldus']);
                } else {
                    $kirjeldusVaartus = '';
                }
                ?>
                <input type="text" name="kirjeldus" required class="form-input" value="<?=$kirjeldusVaartus?>">
            </div>
            <div class="form-group">
                <label>Pildi URL:</label>
                <?php 
                if ($edit_product) {
                    $piltVaartus = htmlspecialchars($edit_product['pilt']);
                } else {
                    $piltVaartus = '';
                }
                ?>
                <input type="text" name="pilt" class="form-input" value="<?=$piltVaartus?>">
            </div>
            <div class="form-group">
                <label>Hind:</label>
                <?php 
                if ($edit_product) {
                    $hindVaartus = $edit_product['hind'];
                } else {
                    $hindVaartus = '0';
                }
                ?>
                <input type="number" name="hind" value="<?=$hindVaartus?>" min="0" step="0.01" class="form-input">
            </div>
            <div class="form-group">
                <?php 
                if ($edit_product && $edit_product['avalik']) {
                    $checked = 'checked';
                } elseif ($edit_product) {
                    $checked = '';
                } else {
                    $checked = 'checked';
                }
                ?>
                <label><input type="checkbox" name="avalik" <?=$checked?>> Avalik</label>
            </div>
            <div class="form-group">
                <?php 
                if ($edit_product) {
                    $submitText = 'Salvesta muudatused';
                } else {
                    $submitText = 'Lisa toode';
                }
                ?>
                <input type="submit" value="<?=$submitText?>" class="btn">
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