<?php
require("config.php");
$k = $yhendus->prepare("SELECT failinimi, kirjeldus FROM galerii");
$k->bind_result($f, $kirj);
$k->execute();
$pildid = [];
while($k->fetch()) $pildid[] = ['f'=>$f, 'k'=>$kirj];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Pildigalerii</title>
</head>
<body>
<nav>
<div class="logo">Toidupood</div>
<div><a href="index.php">Avaleht</a><a href="hinnakiri.php">Tooted</a><a href="admin.php">Admin</a></div>
</nav>

<section class="page-header">
<h1>Galerii</h1>
</section>

<section class="featured">
<div class="galerii">
<?php foreach($pildid as $p): ?>
<div class="pilt">
<img src="uploads/<?=$p['f']?>">
<p><?=htmlspecialchars($p['k'])?></p>
</div>
<?php endforeach; ?>
</div>
</section>
<footer>
<p>&copy; 2024 Toidupood. Kõik õigused kaitstud.</p>
</footer>

</body>
</html>