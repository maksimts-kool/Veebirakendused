<?php
require("config.php");

if (isset($_REQUEST["lisa1euro"])) {
    $paring = $yhendus->prepare("UPDATE hinnakiri SET hind = hind + 1 WHERE id=?");
    $paring->bind_param("i", $_REQUEST["lisa1euro"]);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

$kask = $yhendus->prepare("SELECT id, nimetus, kirjeldus, hind, pilt FROM hinnakiri WHERE avalik = 1");
$kask->execute();
$tulemus = $kask->get_result();
$tooted = [];
while($rida = $tulemus->fetch_assoc()) $tooted[] = $rida;
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
        <table class="admin-table">
            <tr>
                <th>Nimetus</th>
                <th>Pilt</th>
                <th>Kirjeldus</th>
                <th>Hind</th>
                <th>Tegevus</th>
            </tr>
            <?php foreach($tooted as $t): ?>
            <tr>
                <td><?=htmlspecialchars($t['nimetus'])?></td>
                <td>
                    <img src="<?=$t['pilt'] ? $t['pilt'] : 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop'?>"
                        class="table-img">
                </td>
                <td><?=htmlspecialchars($t['kirjeldus'])?></td>
                <td>€<?=$t['hind']?></td>
                <td>
                    <a href="?lisa1euro=<?=$t['id']?>" class="btn btn-small">Lisa 1€</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </section>
    <script src="script.js"></script>
    <footer>
        <p>&copy; 2024 Toidupood. Kõik õigused kaitstud.</p>
    </footer>

</body>

</html>