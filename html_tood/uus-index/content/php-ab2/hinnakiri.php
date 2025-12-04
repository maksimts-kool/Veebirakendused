<?php
require("config.php");
$kask = $yhendus->prepare("SELECT nimetus, kirjeldus, hind, pilt FROM hinnakiri");
$kask->bind_result($n, $k, $h, $pilt);
$kask->execute();
$tooted = [];
while($kask->fetch()) $tooted[] = ['n'=>$n, 'k'=>$k, 'h'=>$h, 'pilt'=>$pilt];
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Tooted - Toidupood</title>
</head>
<body>
<nav>
<div class="logo">Toidupood</div>
<div>
<a href="index.php">Avaleht</a>
<a href="galerii.php">Galerii</a>
<a href="admin.php">Admin</a>
</div>
</nav>

<section class="page-header">
<h1>Tooted</h1>
</section>

<section class="menu-section">
<div class="products">
<?php foreach($tooted as $t): ?>
<div class="product-card">
<div class="product-image">
<img src="<?=$t['pilt'] ? 'uploads/'.$t['pilt'] : 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=300&h=300&fit=crop'?>" alt="<?=htmlspecialchars($t['n'])?>">
</div>
<h3><?=htmlspecialchars($t['n'])?></h3>
<p><?=htmlspecialchars($t['k'])?></p>
<span class="price">€<?=$t['h']?></span>
</div>
<?php endforeach; ?>
</div>
</section>
<script src="script.js"></script>
<footer>
<p>&copy; 2024 Toidupood. Kõik õigused kaitstud.</p>
</footer>

</body>
</html>