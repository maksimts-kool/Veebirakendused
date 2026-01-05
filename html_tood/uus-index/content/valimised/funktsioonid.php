<?php require('config.php'); 

if (isset($_REQUEST["lisa1punkt"])) {
    $paring = $yhendus->prepare("UPDATE valimised SET punktid = punktid + 1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1punkt"]);
    $paring->execute();
    exit;
}

?>