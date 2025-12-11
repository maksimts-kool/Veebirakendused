<?php
require("config.php");
session_start();

$selected_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$selected_product = null;

$query = "SELECT id, nimetus, pilt, hind, kirjeldus, kommentaarid FROM hinnakiri WHERE avalik = 1";
$paring = $yhendus->prepare($query);
$paring->execute();
$tulemus = $paring->get_result();

if ($selected_id) {
    $detail_query = "SELECT id, nimetus, pilt, hind, kirjeldus, kommentaarid FROM hinnakiri WHERE id=? AND avalik = 1";
    $detail_paring = $yhendus->prepare($detail_query);
    $detail_paring->bind_param("i", $selected_id);
    $detail_paring->execute();
    $detail_result = $detail_paring->get_result();
    $selected_product = $detail_result->fetch_assoc();
}

// Add comment
if (isset($_REQUEST["lisa_kommentaar"]) && isset($_REQUEST["kommentaar"])) {
    $kommentaar = $_REQUEST["kommentaar"];
    $id = $_REQUEST["lisa_kommentaar"];
    $paring = $yhendus->prepare("UPDATE hinnakiri SET kommentaarid = CONCAT(IFNULL(kommentaarid, ''), ?, '\n') WHERE id=?");
    $paring->bind_param("si", $kommentaar, $id);
    $paring->execute();
    header("Location: ".$_SERVER['PHP_SELF']."?id=".$id);
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="style.css">
    <title>Galerii - Toidupood</title>
</head>

<body>
    <nav>
        <div class="logo">Toidupood</div>
        <div>
            <a href="index.php">Avaleht</a>
            <a href="hinnakiri.php">Tooted</a>
            <a href="admin.php">Admin</a>
        </div>
    </nav>

    <section class="page-header">
        <h1>Galerii</h1>
    </section>

    <section class="featured">
        <?php if ($selected_product): ?>
        <div class="detail-container">
            <p><a href="galerii.php">Tagasi galeriisse</a></p>
            <div class="detail-content">
                <div class="detail-image">
                    <img src="<?php echo htmlspecialchars($selected_product['pilt']); ?>"
                        alt="<?php echo htmlspecialchars($selected_product['nimetus']); ?>">
                </div>
                <div class="detail-info">
                    <h2><?php echo htmlspecialchars($selected_product['nimetus']); ?></h2>
                    <p><strong>Hind:</strong> <?php echo $selected_product['hind']; ?> €</p>
                    <p><strong>Kirjeldus:</strong>
                        <?php echo nl2br(htmlspecialchars($selected_product['kirjeldus'])); ?></p>

                    <?php if (!empty($selected_product['kommentaarid'])): ?>
                    <div class="detail-comments">
                        <strong>Kommentaarid:</strong>
                        <div class="comments-box">
                            <?php echo nl2br(trim(htmlspecialchars($selected_product['kommentaarid']))); ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="detail-comment-form">
                        <form action="?" method="post">
                            <input type="text" name="kommentaar" placeholder="Lisa kommentaar..." required>
                            <input type="hidden" name="lisa_kommentaar" value="<?php echo $selected_product['id']; ?>">
                            <input type="submit" value="Saada">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="galerii">
            <?php
            $tulemus->data_seek(0);
            while ($rida = $tulemus->fetch_assoc()) {
                echo "<a href='?id={$rida['id']}' class='gallery-item'>";
                echo "<img src='{$rida['pilt']}' alt='{$rida['nimetus']}'>";
                echo "<div class='gallery-item-title'>{$rida['nimetus']}</div>";
                echo "</a>";
            }
            ?>
        </div>
        <?php endif; ?>
    </section>

    <footer>
        <p>&copy; 2024 Toidupood. Kõik õigused kaitstud.</p>
    </footer>

</body>

</html>