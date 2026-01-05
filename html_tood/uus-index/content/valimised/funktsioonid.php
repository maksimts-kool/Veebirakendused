<?php require('config.php'); 

if (isset($_REQUEST["lisa1punkt"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid = punktid + 1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    exit;
}

function naitaTabel() {
    global $yhendus;
    $paring = $yhendus->prepare("SELECT id, president, pilt, punktid, lisamisaeg, kommentaarid FROM valimised WHERE avalik = 1");
    $paring->bind_result($id, $president, $pilt, $punktid, $lisamisaeg, $kommentaarid);
    $paring->execute();

    while ($paring->fetch()) {
        echo "<tr>";
        echo "<td>{$president}</td>";
        echo "<td>{$punktid}</td>";
        echo "<td><a href='?lisa1punkt={$id}'>+</a></td>";
        echo "</tr>";
    }
}

?>