<?php
if (!isset($lehepealkiri)) {
  $lehepealkiri = "Jalgratta eksam";
}

if (!function_exists('turvTekst')) {
    require_once("funktsioonid.php");
}

if (!function_exists('onSissologitud')) {
    require_once("auth.php");
}
?>
<!doctype html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $lehepealkiri; ?> - Jalgratta Eksam</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header class="header">
        <div class="header-content">
            <div class="logo">
                <h1>Jalgratta Eksam</h1>
                <p class="tagline">Testi oma teadmisi ja oskusi</p>
            </div>
            <div style="text-align: right; color: white;">
                <?php if(onSissologitud()): ?>
                <p style="margin: 5px 0;">
                    👤 <strong><?php echo turvTekst(kasutajanimi()); ?></strong>
                    <?php if(onAdmin()): ?>
                    <span
                        style="background-color: #e74c3c; padding: 2px 8px; border-radius: 3px; font-size: 0.8em;">ADMIN</span>
                    <?php else: ?>
                    <span
                        style="background-color: #3498db; padding: 2px 8px; border-radius: 3px; font-size: 0.8em;">KASUTAJA</span>
                    <?php endif; ?>
                </p>
                <p style="margin: 5px 0;">
                    <a href="logout.php" style="color: #ffeb3b; text-decoration: none;">🚪 Logi välja</a>
                </p>
                <?php else: ?>
                <p style="margin: 5px 0;">
                    <a href="login.php" style="color: #ffeb3b; text-decoration: none;">🔐 Logi sisse</a> |
                    <a href="registreerimine.php" style="color: #ffeb3b; text-decoration: none;">📝 Registreeri</a>
                </p>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <nav class="navbar">
        <div class="nav-content">
            <ul class="nav-menu">
                <li><a href="index.php" class="nav-link">🏠 Avaleht</a></li>
                <?php if(onSissologitud()): ?>
                <li><a href="teooriaeksam.php" class="nav-link">📚 Teooria</a></li>
                <li><a href="slaalom.php" class="nav-link">🏁 Slaalom</a></li>
                <li><a href="ringtee.php" class="nav-link">🔄 Ringtee</a></li>
                <li><a href="t2navasoit.php" class="nav-link">🛣️ Tänavasõit</a></li>
                <li><a href="lubadeleht.php" class="nav-link">📜 Lubad</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>

    <main class="main-content">