<?php require('config.php'); 
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Uudised SQL andmebaasist</title>
        <link rel="stylesheet" type="text/css" href="style.css">
    </head>
    <body>
        <h1>Uudiste loend</h1>
        <div style="text-align:center;">
            <a href="../../lisaUudis.php">
                <button>Tagasi</button>
            </a>
        </div>
        <div id="menyy">
            <ul>
            <?php
        global $connect;
        $paring = $connect->prepare("SELECT uudisId, pealkiri FROM uudised");
        $paring->bind_result($id, $pealkiri);
        $paring->execute();
        while ($paring->fetch()) {
            echo "<li><a href='?uudis=$id'>$pealkiri</a></li>";
        }
        $paring->close();
        ?>
        </ul>
        </div>
        <div id="sisu">
            <?php
        if (isset($_REQUEST['uudis'])) {
            $id = $_REQUEST['uudis'];
            $paring = $connect->prepare("SELECT uudisId, pealkiri, kirjeldus, kuupaev, tuju FROM uudised WHERE uudisId = ?");
            $paring->bind_result($id, $pealkiri, $kirjeldus, $kuupaev, $tuju);
            $paring->bind_param("i", $id);
            $paring->execute();
            if ($paring->fetch()) {
                echo "<h2>".$pealkiri."</h2>";
                echo "<div>".$kuupaev."</div>";
                echo "<img src='$kirjeldus' alt='Tuju pilt'>";
            } else {
                echo "<p>Uudist ei leitud.</p>";
            }
            $paring->close();
        } else {
            echo "<p>Valige menüüst uudis, et näha selle sisu.</p>";
        }
        ?>
        </div>
    </body>
</html>