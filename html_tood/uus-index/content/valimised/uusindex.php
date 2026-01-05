<?php
require('funktsioonid.php'); 

if (isset($_REQUEST["lisa1punkt"])) {
    lisa1punkt($_REQUEST["lisa1punkt"]);
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}
if (isset($_REQUEST["president"]) && isset($_REQUEST["pilt"])) {
    lisa_president($_REQUEST["president"], $_REQUEST["pilt"]);
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
    <table>
        <tr>
            <th>Nimi</th>
            <th>Punktid</th>
            <th>+1 Punkt</th>
        </tr>
        <?php naitaTabel(); ?>
    </table>
</body>

</html>