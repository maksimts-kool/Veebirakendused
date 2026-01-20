<?php 
require('config.php');

$selected_id = isset($_GET['id']) ? intval($_GET['id']) : null;
$selected_president = null;

$query = isset($_SESSION["admin"]) ? "SELECT id, president, pilt, punktid, lisamisaeg, avalik, kommentaarid FROM valimised" : "SELECT id, president, pilt, punktid, lisamisaeg, kommentaarid FROM valimised WHERE avalik = 1";
$paring = $yhendus->prepare($query);
$paring->execute();
$tulemus = $paring->get_result();

if ($selected_id) {
    $detail_query = isset($_SESSION["admin"]) ? "SELECT id, president, pilt, punktid, lisamisaeg, avalik, kommentaarid FROM valimised WHERE id=?" : "SELECT id, president, pilt, punktid, lisamisaeg, kommentaarid FROM valimised WHERE id=? AND avalik = 1";
    $detail_paring = $yhendus->prepare($detail_query);
    $detail_paring->bind_param("i", $selected_id);
    $detail_paring->execute();
    $detail_result = $detail_paring->get_result();
    $selected_president = $detail_result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Eesti presidenti valimise galerii</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>Eesti presidenti valimise galerii</h1>
    <p style="text-align: center;"><a href="index.php">Tagasi tabelile</a></p>

    <?php if ($selected_president): ?>
    <div class="detail-container">
        <p><a href="galerii.php">Tagasi galeriisse</a></p>
        <div class="detail-content">
            <div class="detail-image">
                <img src="<?php echo htmlspecialchars($selected_president['pilt']); ?>"
                    alt="<?php echo htmlspecialchars($selected_president['president']); ?>">
            </div>
            <div class="detail-info">
                <h2><?php echo htmlspecialchars($selected_president['president']); ?></h2>
                <p><strong>Punktid:</strong> <?php echo $selected_president['punktid']; ?></p>
                <p><strong>Lisamisaeg:</strong> <?php echo $selected_president['lisamisaeg']; ?></p>
                <?php if (!empty($selected_president['kommentaarid'])): ?>
                <div class="detail-comments">
                    <strong>Kommentaarid:</strong>
                    <div class="comments-box">
                        <?php echo nl2br(trim(htmlspecialchars($selected_president['kommentaarid']))); ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="gallery-grid">
        <?php
        $tulemus->data_seek(0);
        while ($rida = $tulemus->fetch_assoc()) {
            $hiddenClass = (isset($rida['avalik']) && $rida['avalik'] == 0) ? ' hidden-item' : '';
            echo "<a href='?id={$rida['id']}' class='gallery-item$hiddenClass'>";
            echo "<img src='{$rida['pilt']}' alt='{$rida['president']}'>";
            echo "<div class='gallery-item-title'>{$rida['president']}</div>";
            echo "</a>";
        }
        ?>
    </div>
    <?php endif; ?>
</body>

</html>