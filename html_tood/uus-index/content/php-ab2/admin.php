<?php
// admin.php – toodete haldus
require("config.php");

if(isset($_REQUEST["lisa"])){
    $pilt = "";
    if(isset($_FILES["pilt"]) && $_FILES["pilt"]["name"] != ""){
        $pilt = $_FILES["pilt"]["name"];
        move_uploaded_file($_FILES["pilt"]["tmp_name"], "uploads/" . $pilt);
    }
    $k = $yhendus->prepare(
        "INSERT INTO hinnakiri (nimetus, kirjeldus, hind, pilt) VALUES (?, ?, ?, ?)"
    );
    $k->bind_param("ssds", $_REQUEST["nimetus"], $_REQUEST["kirjeldus"], $_REQUEST["hind"], $pilt);
    $k->execute();
}

if(isset($_REQUEST["kustuta"])){
    $k = $yhendus->prepare("DELETE FROM hinnakiri WHERE id=?");
    $k->bind_param("i", $_REQUEST["kustuta"]);
    $k->execute();
}

if(isset($_REQUEST["muuda_id"])){
    $pilt_update = "";
    if(isset($_FILES["pilt"]) && $_FILES["pilt"]["name"] != ""){
        $pilt_update = $_FILES["pilt"]["name"];
        move_uploaded_file($_FILES["pilt"]["tmp_name"], "uploads/" . $pilt_update);
        $k = $yhendus->prepare("UPDATE hinnakiri SET nimetus=?, kirjeldus=?, hind=?, pilt=? WHERE id=?");
        $k->bind_param("ssdsi", $_REQUEST["nimetus"], $_REQUEST["kirjeldus"], $_REQUEST["hind"], $pilt_update, $_REQUEST["muuda_id"]);
    } else {
        $k = $yhendus->prepare("UPDATE hinnakiri SET nimetus=?, kirjeldus=?, hind=? WHERE id=?");
        $k->bind_param("ssdi", $_REQUEST["nimetus"], $_REQUEST["kirjeldus"], $_REQUEST["hind"], $_REQUEST["muuda_id"]);
    }
    $k->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Admin Tooted - Toidupood</title>
</head>
<body>
<nav>
<div class="logo">Toidupood</div>
<div>
<a href="index.php">Avaleht</a>
<a href="hinnakiri.php">Tooted</a>
<a href="galerii.php">Galerii</a>
<a href="admin_galerii.php">Admin Galerii</a>
</div>
</nav>

<section class="page-header">
<h1>Toodete haldus</h1>
</section>

<div class="admin-form">
<h3>Lisa toode</h3>
<form method="post" enctype="multipart/form-data">
<input type="hidden" name="lisa" value="1">
Nimetus: <input type="text" name="nimetus"><br>
Kirjeldus: <input type="text" name="kirjeldus"><br>
Hind: <input type="text" name="hind"><br>
Pilt: <input type="file" name="pilt"><br>
<input type="submit" value="Lisa toode">
</form>
</div>

<div class="admin-form">
<h3>Olemasolevad tooted</h3>
<?php
$k = $yhendus->prepare("SELECT id, nimetus, kirjeldus, hind, pilt FROM hinnakiri");
$k->bind_result($id, $n, $kirj, $h, $pilt);
$k->execute();

while($k->fetch()){
    echo "<div class='product-admin-item'>
            <div class='product-info'>
                <h4>$n – €$h</h4>
                <p>".htmlspecialchars($kirj)."</p>
            </div>
            <div class='product-actions'>
                <a href='?kustuta=$id' class='btn-delete'>Kustuta</a>
                <a href='?muutmine=$id' class='btn-edit'>Muuda</a>
            </div>
          </div>";
}
?>
</div>

<?php
if(isset($_REQUEST["muutmine"])){
    $k = $yhendus->prepare("SELECT nimetus, kirjeldus, hind, pilt FROM hinnakiri WHERE id=?");
    $k->bind_param("i", $_REQUEST["muutmine"]);
    $k->bind_result($n, $kirj, $h, $pilt);
    $k->execute();
    if($k->fetch()){
        echo "
        <div class='admin-form'>
        <h3>Muuda toodet</h3>
        <form method='post' enctype='multipart/form-data'>
            <input type='hidden' name='muuda_id' value='".$_REQUEST["muutmine"]."'>
            Nimetus: <input type='text' name='nimetus' value='".htmlspecialchars($n)."'><br>
            Kirjeldus: <input type='text' name='kirjeldus' value='".htmlspecialchars($kirj)."'><br>
            Hind: <input type='text' name='hind' value='$h'><br>
            Pilt: <input type='file' name='pilt'><br>
            <input type='submit' value='Muuda toodet'>
        </form>
        </div>
        ";
    }
}
?>

</body>
</html>