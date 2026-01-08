<?php 
if (isset($_POST['submit'])) {
    $xmlDoc = new DOMDocument('1.0', 'UTF-8');
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('autod.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_auto = $xmlDoc->createElement('auto');
    $xmlDoc->appendChild($xml_auto);
    $xml_root->appendChild($xml_auto);

    unset($_POST['submit']);
    foreach($_POST as $voti => $vaartus) {
        if ($voti === 'eesnimi' || $voti === 'perenimi' || $voti === 'vanus') {
            if (!isset($xml_omanik)) {
                $xml_omanik = $xmlDoc->createElement('omanik');
                $xml_auto->appendChild($xml_omanik);
            }
            $kirje = $xmlDoc->createElement($voti, $vaartus);
            $xml_omanik->appendChild($kirje);
        } else {
            $kirje = $xmlDoc->createElement($voti, $vaartus);
            $xml_auto->appendChild($kirje);
        }
    }

    $xmlDoc->save('autod.xml');
}

$autod = simplexml_load_file('autod.xml');
function autodeOtsing($otsing) {
    global $autod;
    $tulemused = [];

    foreach ($autod->auto as $auto) {
        $autoNumber = strtolower($auto->autoNumber);
        $mark = strtolower($auto->mark);
        $model = strtolower($auto->model);
        $eesnimi = strtolower($auto->omanik->eesnimi);
        $perenimi = strtolower($auto->omanik->perenimi);
        $otsingSisestus = strtolower($otsing);

        if (substr($autoNumber, 0, strlen($otsing)) == $otsingSisestus || 
            substr($mark, 0, strlen($otsing)) == $otsingSisestus ||
            substr($eesnimi, 0, strlen($otsing)) == $otsingSisestus ||
            substr($perenimi, 0, strlen($otsing)) == $otsingSisestus ||
            substr($model, 0, strlen($otsing)) == $otsingSisestus ) {
            array_push($tulemused, $auto);
        }
    }

    return $tulemused;
}
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto Lugemine</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <!-- lisamine -->
    <form method="post" action="">
        <table>
            <tr>
                <td><label for="autoNumber">Auto number:</label></td>
                <td><input type="text" id="autoNumber" name="autoNumber" minlength="6" maxlength="6"
                        pattern="[0-9]{3}[A-Z]{3}" required></td>
            </tr>
            <tr>
                <td><label for="mark">Mark:</label></td>
                <td><input type="text" id="mark" name="mark" minlength="2" required></td>
            </tr>
            <tr>
                <td><label for="model">Model:</label></td>
                <td><input type="text" id="model" name="model" minlength="2" required></td>
            </tr>
            <tr>
                <td><label for="eesnimi">Omanik eesnimi:</label></td>
                <td><input type="text" id="eesnimi" name="eesnimi" minlength="3" required></td>
            </tr>
            <tr>
                <td><label for="perenimi">Omanik perenimi:</label></td>
                <td><input type="text" id="perenimi" name="perenimi" minlength="3" required></td>
            </tr>
            <tr>
                <td><label for="vanus">Omanik vanus:</label></td>
                <td><input type="number" id="vanus" name="vanus" min="18" max="100" required></td>
            </tr>
            <tr>
                <td><input type="submit" name="submit" value="Lisa auto"></td>
                <td></td>
            </tr>
        </table>
    </form>

    <!-- otsing -->
    <form method="get" action="">
        <label for="otsing">Otsi autot:</label>
        <input type="text" id="otsing" name="otsing">
        <input type="submit" value="Otsi">
    </form>

    <table>
        <tr>
            <th>Auto number</th>
            <th>Mark</th>
            <th>Model</th>
            <th>Omanik</th>
        </tr>
        <?php
$tulemuseduus = [];

if (!empty($_GET['otsing'])) {
    $tulemused = autodeOtsing($_GET['otsing']);
    $tulemuseduus = $tulemused;
} else {
    $tulemuseduus = $autod->auto;
}

foreach ($tulemuseduus as $auto) {
    echo "<tr>";
    echo "<td>" . $auto->autoNumber . "</td>";
    echo "<td>" . $auto->mark . "</td>";
    echo "<td>" . $auto->model . "</td>";
    echo "<td>" . $auto->omanik->eesnimi . " " . $auto->omanik->perenimi . " (" . $auto->omanik->vanus . ")</td>";
    echo "</tr>";
}
?>
    </table>
</body>

</html>