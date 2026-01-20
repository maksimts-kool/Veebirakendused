<?php require_once 'kuvarss.php'; ?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RSS Uudised</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php 
    fetchAndDisplayRSS('https://www.err.ee/rss/', 5, 'ERR Uudised');
    fetchAndDisplayRSS('https://www.postimees.ee/rss/', 5, 'Postimees Uudised'); 
    ?>
</body>

</html>