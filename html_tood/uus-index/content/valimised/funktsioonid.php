<?php require('config.php'); 

function lisa1punkt($id) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "UPDATE valimised SET punktid = punktid + 1 WHERE id=?"
    );
    $paring->bind_param("i", $id);
    $paring->execute();
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

function lisa_president($president, $pilt, $punktid = 0, $avalik = 1) {
    global $yhendus;
    $paring = $yhendus->prepare(
        "INSERT INTO valimised(president, pilt, punktid, avalik, lisamisaeg)
         VALUES (?, ?, ?, ?, NOW())"
    );
    $paring->bind_param("ssii", $president, $pilt, $punktid, $avalik);
    $paring->execute();
}

?>