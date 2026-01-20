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
    $paring = $yhendus->prepare("UPDATE valimised SET avalik = IF(avalik = 1, 0, 1) WHERE id=?"); // kui avalik on =1 siis tehakse 0 else 1
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

if (isset($_REQUEST["lahuta1punkt"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid = punktid - 1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lahuta1punkt"]);
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

if (isset($_REQUEST["lisa_kommentaar"]) && isset($_REQUEST["kommentaar"])) {
    $kommentaar = $_REQUEST["kommentaar"];
    $id = $_REQUEST["lisa_kommentaar"];
    $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid = CONCAT(IFNULL(kommentaarid, ''), ?, '\n') WHERE id=?");
    $paring->bind_param("si", $kommentaar, $id);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

if (isset($_REQUEST["kustuta_kommentaar"]) && isset($_SESSION["admin"])) {
    $id = $_REQUEST["kustuta_kommentaar"];
    $paring = $yhendus->prepare("UPDATE valimised SET kommentaarid = '' WHERE id=?");
    $paring->bind_param("i", $id);
    $paring->execute();
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
    <?php if (isset($_SESSION["admin"])): ?>
    <p style="text-align: center;"><a href="?logout=1">Logi välja</a></p>
    <?php endif; ?>
    <p style="text-align: center;"><a href="galerii.php">Vaata galeriid</a></p>
    <table>
        <tr>
            <th>Nimi</th>
            <th>Pilt</th>
            <th>Punktid</th>
            <th>Lisamisaeg</th>
            <th>Tegevus</th>
        </tr>
        <?php
        $query = isset($_SESSION["admin"]) ? "SELECT id, president, pilt, punktid, lisamisaeg, avalik, kommentaarid FROM valimised" : "SELECT id, president, pilt, punktid, lisamisaeg, kommentaarid FROM valimised WHERE avalik = 1";
        $paring = $yhendus->prepare($query);
        $paring->execute();
        $tulemus = $paring->get_result();
        while ($rida = $tulemus->fetch_assoc()) {
            $hiddenClass = (isset($rida['avalik']) && $rida['avalik'] == 0) ? ' class="hidden-row"' : '';
            echo "<tr$hiddenClass>";
            echo "<td>{$rida['president']}</td>";
            echo "<td><img src='{$rida['pilt']}' alt='{$rida['president']}'></td>";
            echo "<td>{$rida['punktid']}</td>";
            echo "<td>{$rida['lisamisaeg']}</td>";
            echo "<td>";
            if (isset($_SESSION["admin"])) {
                $status = isset($rida['avalik']) && $rida['avalik'] ? 'Peida' : 'Näita';
                echo "<a href='?muuda_avalik={$rida['id']}'>$status</a>";
                echo " | <a href='?nulli_punktid={$rida['id']}'>Nulli punktid</a>";
                echo " | <a href='?kustuta={$rida['id']}' onclick='return confirm(\"Kas oled kindel?\")'>Kustuta</a>";
            } else {
                echo "<a href='?lahuta1punkt={$rida['id']}'>−</a>";
                echo " <a href='?lisa1punkt={$rida['id']}'>+</a>";
            }
            echo "</td>";
            echo "</tr>";
        
            if (!empty($rida['kommentaarid'])) {
                echo "<tr$hiddenClass class='comment-row'>";
                echo "<td colspan='5'><strong>Kommentaarid:</strong><br>";
                echo nl2br(htmlspecialchars($rida['kommentaarid']));
                if (isset($_SESSION["admin"])) {
                    echo "<br> <a href='?kustuta_kommentaar={$rida['id']}' onclick='return confirm(\"Kustuta kõik kommentaarid?\")' class='delete-comment'>Kustuta kõik</a>";
                }
                echo "</td>";
                echo "</tr>";
            }
            
            if (!isset($_SESSION["admin"])) {
                echo "<tr$hiddenClass class='comment-form-row'>";
                echo "<td colspan='5'>";
                echo "<form action='?' method='post' class='comment-form'>";
                echo "<input type='text' name='kommentaar' placeholder='Lisa kommentaar...' required>";
                echo "<input type='hidden' name='lisa_kommentaar' value='{$rida['id']}'>";
                echo "<input type='submit' value='Saada'>";
                echo "</form>";
                echo "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
    <h2>Lisa uus president</h2>
    <form action="?">
        <div>
            <label for="president">Presidenti nimi: </label>
            <input type="text" name="president" id="president" required>
        </div>
        <div>
            <label for="pilt">Pildi URL: </label>
            <input type="text" name="pilt" id="pilt" pattern=".*\.(jpg|jpeg|png|gif|webp)$" required>
        </div>
        <?php if (isset($_SESSION["admin"])): ?>
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
    <?php if (!isset($_SESSION["admin"])): ?>
    <h2>Admin Login</h2>
    <form action="?" method="post">
        <label for="parool">Parool: </label>
        <input type="text" name="parool" id="parool" required>
        <input type="submit" name="login" value="Logi sisse">
    </form>
    <?php endif; ?>
</body>

</html>
<?php