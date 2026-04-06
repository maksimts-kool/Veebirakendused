<?php
require('config.php'); 

app_handle_ip_request_submission([
    'return_to' => 'index.php',
    'reason' => 'Delete news entry',
]);

if (isset($_REQUEST['kustuta'])) {
    app_require_basic_auth();
    app_require_authorized_ip_for_action('Delete news entry', 'index.php');

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
        <?=app_render_ip_access_panel([
            'return_to' => 'index.php',
            'reason' => 'Delete news entry',
        ]);?>
        <div style="text-align:center;">
    <a href="lisaUudis.php">
        <button>Lisa uus uudis</button>
    </a>
    <a href="../../ip-admin.php">
        <button>IP админка</button>
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
