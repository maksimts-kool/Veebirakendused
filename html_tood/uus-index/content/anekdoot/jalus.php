</main>
<footer>
    <?php
    $tegija = file_get_contents("tegija.txt");
    echo htmlspecialchars($tegija);
    ?>
</footer>
</body>
</html>