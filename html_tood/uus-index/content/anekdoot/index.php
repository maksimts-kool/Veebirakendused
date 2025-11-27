<div id="anekdoot">
<?php include("pais.php"); ?>

<section>
    <?php
    $teade = file_get_contents("teade.txt");
    echo "<p>" . htmlspecialchars($teade) . "</p>";
    ?>
</section>

<?php include("jalus.php"); ?>
</div>