<?php
require("config.php");
$k = $yhendus->prepare("SELECT nimetus, kirjeldus, hind, pilt FROM hinnakiri ORDER BY hind DESC LIMIT 3");
$k->bind_result($n, $kirj, $h, $pilt);
$k->execute();
$tooted = [];
while($k->fetch()) $tooted[] = ['n'=>$n, 'k'=>$kirj, 'h'=>$h, 'pilt'=>$pilt];

$g = $yhendus->prepare("SELECT failinimi FROM galerii LIMIT 3");
$g->bind_result($f);
$g->execute();
$pildid = [];
while($g->fetch()) $pildid[] = $f;


?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="style.css">
<title>Toidupood</title>
</head>
<body>
<nav>
<div class="logo">Toidupood</div>
<div>
<a href="hinnakiri.php">Tooted</a>
<a href="galerii.php">Galerii</a>
<a href="admin.php">Admin</a>
</div>
</nav>

<section class="hero">
<h1>Kes me oleme?</h1>
<p>Teie naabruskonna toidupood, mis pakub värskeid tooteid, suupisteid, jooke ja igapäevaseid tarbevahendeid. Oleme siin, et teenindada teid kvaliteetsete kaupade ja sõbraliku teenindusega.</p>
</section>

<section class="gallery-section">
<h2>Galerii</h2>
<div class="image-gallery">
<?php foreach($pildid as $pilt): ?>
<div class="gallery-item">
<img src="uploads/<?=$pilt?>" alt="Gallery image">
</div>
<?php endforeach; ?>
</div>

<div style="text-align: center; margin: 40px 0;">
<a href="galerii.php" class="btn">Vaata galeriid</a>
</div>
</section>

<section class="menu-section">
<h2>Tooted</h2>


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

<div style="text-align: center; margin: 40px 0;">
<a href="hinnakiri.php" class="btn">Vaata tooteid</a>
</div>
</section>

<section class="team">
<h2>Meie tiim</h2>
<div class="team-member">
<div class="team-icon">👨‍💼</div>
<h3>Maksim Tsikvasvili</h3>
<p>Poodi Juhataja</p>
</div>
</section>

<section class="events">
<h2>Teenused</h2>
<div class="services-list">
<div class="service-item">
<div class="service-title">🥬 Värske kaup</div>
<div class="service-desc">Iga päev värske</div>
</div>
<div class="service-item">
<div class="service-title">⚡ Kiire ostlemine</div>
<div class="service-desc">Kiire teenindus</div>
</div>
<div class="service-item">
<div class="service-title">🏠 Igapäevased asjad</div>
<div class="service-desc">Kõik vajalik</div>
</div>
<div class="service-item">
<div class="service-title">😊 Sõbralik teenindus</div>
<div class="service-desc">Alati abivalmis</div>
</div>
</div>
</section>

<footer>
<p>&copy; 2024 Toidupood. Kõik õigused kaitstud.</p>
</footer>

</body>
</html>