<?php
require('config.php'); 

if (isset($_REQUEST['kustuta'])) {

    $correct_user = "admin";
$correct_pass = "phpantihacker";

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Access denied";
    exit;
}

if ($_SERVER['PHP_AUTH_USER'] !== $correct_user ||
    $_SERVER['PHP_AUTH_PW'] !== $correct_pass) {
    header('WWW-Authenticate: Basic realm="Admin Area"');
    header('HTTP/1.0 401 Unauthorized');
    echo "Wrong username or password";
    exit;
}

    $id = $_REQUEST['kustuta'];
    $paring = $connect->prepare("DELETE FROM uudised WHERE uudisId = ?");
    $paring->bind_param("i", $id);
    $paring->execute();
    $paring->close();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Uudised SQL andmebaasist</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h1>Uudiste tabeli sisu</h1>
        <div style="text-align:center;">
    <a href="lisaUudis.php">
        <button>Lisa uus uudis</button>
    </a>
    <a href="indexcopy.php">
        <button>Vaata teine tabel</button>
    </a>
</div>
        <table>
            <tr>
                <th>JRK</th>
                <th>Pealkiri</th>
                <th>Kirjeldus</th>
                <th>Kuupäev</th>
                <th>Admin</th>
            </tr>
        <?php
        global $connect;
        $paring = $connect->prepare("SELECT uudisId, pealkiri, kirjeldus, kuupaev, tuju FROM uudised");
        $paring->bind_result($id, $pealkiri, $kirjeldus, $kuupaev, $tuju);
        $paring->execute();
        while ($paring->fetch()) {

    $class = '';
    if ($tuju === 'green') $class = 'row-green';
    elseif ($tuju === 'red') $class = 'row-red';
    elseif ($tuju === 'yellow') $class = 'row-yellow';
    elseif ($tuju === 'blue') $class = 'row-blue';

    echo "<tr class='$class'>";
    echo "<td>$id</td>";
    echo "<td>$pealkiri</td>";
    echo "<td><img src='$kirjeldus'></td>";
    echo "<td>$kuupaev</td>";
    echo "<td><a href='?kustuta=$id'>Kustuta</a></td>";
    echo "</tr>";
}
        $paring->close();
        ?>
        </table>
    </body>
</html>