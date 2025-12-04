<?php
// admin_galerii.php – piltide lisamine
require("config.php");

if(isset($_POST["lae_yles"])){
    $f = $_FILES["pilt"]["name"];
    $siht = "uploads/" . basename($f);
    move_uploaded_file($_FILES["pilt"]["tmp_name"], $siht);

    $kirj = $_POST["kirjeldus"];
    $k = $yhendus->prepare(
        "INSERT INTO galerii (failinimi, kirjeldus, kuupaev)
         VALUES (?, ?, CURDATE())"
    );
    $k->bind_param("ss", $f, $kirj);
    $k->execute();
}

if(isset($_GET["kustuta"])){
    $k = $yhendus->prepare("DELETE FROM galerii WHERE id=?");
    $k->bind_param("i", $_GET["kustuta"]);
    $k->execute();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Admin Galerii - Toidupood</title>
</head>
<body>
<nav>
<div class="logo">Toidupood</div>
<div>
<a href="index.php">Avaleht</a>
<a href="hinnakiri.php">Tooted</a>
<a href="galerii.php">Galerii</a>
<a href="admin.php">Admin Tooted</a>
</div>
</nav>

<section class="page-header">
<h1>Galerii haldus</h1>
</section>

<div class="admin-form">
<h3>Lae pilt üles</h3>
<form method="post" enctype="multipart/form-data">
Pilt: <input type="file" name="pilt" required><br>
Kirjeldus: <input type="text" name="kirjeldus"><br>
<input type="submit" name="lae_yles" value="Lae üles">
</form>
</div>

<hr>

<?php
$k = $yhendus->prepare("SELECT id, failinimi, kirjeldus FROM galerii");
$k->bind_result($id, $f, $kirj);
$k->execute();

echo "<div class='galerii'>";
while($k->fetch()){
    echo "<div class='pilt'>
            <img src='uploads/$f'>
            <p>".htmlspecialchars($kirj)."</p>
            <a href='?kustuta=$id'>Kustuta</a>
          </div>";
}
echo "</div>";
?>

</body>
</html>