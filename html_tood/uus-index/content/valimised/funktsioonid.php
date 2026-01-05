<?php
require('config.php');

// Autentimise funktsioonid
function kontrolli_admin() {
    return isset($_SESSION["admin"]);
}

function login_admin($parool) {
    if ($parool === "lolavalik") {
        $_SESSION["admin"] = true;
        return true;
    }
    return false;
}

function logout_admin() {
    session_destroy();
}

// Presidendi funktsioonid
function lisa_president($president, $pilt, $punktid = 0, $avalik = 1) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "INSERT INTO valimised(president, pilt, punktid, avalik, lisamisaeg)
         VALUES (?, ?, ?, ?, NOW())"
    );
    $paring->bind_param("ssii", $president, $pilt, $punktid, $avalik);
    $paring->execute();
}

function lisa1punkt($id) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET punktid = punktid + 1 WHERE id=?"
    );
    $paring->bind_param("i", $id);
    $paring->execute();
}

function lahuta1punkt($id) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET punktid = punktid - 1 WHERE id=?"
    );
    $paring->bind_param("i", $id);
    $paring->execute();
}

function nulli_punktid($id) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET punktid = 0 WHERE id=?"
    );
    $paring->bind_param("i", $id);
    $paring->execute();
}

function kustuta_president($id) {
    global $yhendus;
    $paring = $yhendus->prepare("DELETE FROM valimised WHERE id=?");
    $paring->bind_param("i", $id);
    $paring->execute();
}

function muuda_avalik($id) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET avalik = IF(avalik = 1, 0, 1) WHERE id=?"
    );
    $paring->bind_param("i", $id);
    $paring->execute();
}

// Kommentaari funktsioonid
function lisa_kommentaar($id, $kommentaar) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET kommentaarid =
         CONCAT(IFNULL(kommentaarid, ''), ?, '\n') WHERE id=?"
    );
    $paring->bind_param("si", $kommentaar, $id);
    $paring->execute();
}

function kustuta_kommentaarid($id) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET kommentaarid = '' WHERE id=?"
    );
    $paring->bind_param("i", $id);
    $paring->execute();
}

// Kuva funktsioonid
function naitaTabel() {
    global $yhendus;
    if (kontrolli_admin()) {
        $query = "SELECT id, president, pilt, punktid, lisamisaeg,
                  avalik, kommentaarid FROM valimised";
    } else {
        $query = "SELECT id, president, pilt, punktid, lisamisaeg,
                  kommentaarid FROM valimised WHERE avalik = 1";
    }

    $paring = $yhendus->prepare($query);
    $paring->execute();
    $tulemus = $paring->get_result();

    while ($rida = $tulemus->fetch_assoc()) {
        $hiddenClass = (isset($rida['avalik']) && $rida['avalik'] == 0)
                       ? ' class="hidden-row"' : '';
        echo "<tr$hiddenClass>";
        echo "<td>{$rida['president']}</td>";
        echo "<td><img src='{$rida['pilt']}'
             alt='{$rida['president']}'></td>";
        echo "<td>{$rida['punktid']}</td>";
        echo "<td>{$rida['lisamisaeg']}</td>";
        echo "<td>";

        if (kontrolli_admin()) {
            $status = isset($rida['avalik']) && $rida['avalik']
                      ? 'Peida' : 'Näita';
            echo "<a href='?muuda_avalik={$rida['id']}'>$status</a>";
            echo " | <a href='?nulli_punktid={$rida['id']}'>
                  Nulli punktid</a>";
            echo " | <a href='?kustuta={$rida['id']}'
                  onclick='return confirm(\"Kas oled kindel?\")'>
                  Kustuta</a>";
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
            if (kontrolli_admin()) {
                echo "<br> <a href='?kustuta_kommentaar={$rida['id']}'
                     onclick='return confirm(\"Kustuta kõik?\")' 
                     class='delete-comment'>Kustuta kõik</a>";
            }
            echo "</td>";
            echo "</tr>";
        }

        if (!kontrolli_admin()) {
            echo "<tr$hiddenClass class='comment-form-row'>";
            echo "<td colspan='5'>";
            echo "<form action='?' method='post'
                  class='comment-form'>";
            echo "<input type='text' name='kommentaar'
                  placeholder='Lisa kommentaar...' required>";
            echo "<input type='hidden' name='lisa_kommentaar'
                  value='{$rida['id']}'>";
            echo "<input type='submit' value='Saada'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
    }
}
?>