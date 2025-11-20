<?php
$posts = [
    ["id" => "post1", "title" => "Veebikalkulaator", "desc" => "A simple web-based calculator built with HTML, CSS, and JavaScript.", "date" => "2025-11-19"],
    ["id" => "post2", "title" => "Ajafunktsioonid PHP-s", "desc" => "Learn about date and time functions in PHP.", "date" => "2025-11-19"],
    ["id" => "post3", "title" => "", "desc" => "", "date" => "2025-11-19"],
    ["id" => "post4", "title" => "Git Käsud", "desc" => "A comprehensive guide to essential Git commands for version control.", "date" => "2025-11-19"],
    ["id" => "post5", "title" => "Tekstifunktsioonid", "desc" => "", "date" => "2025-11-20"],
];
usort($posts, function($a, $b) {
    return strtotime($b["date"]) - strtotime($a["date"]);
});
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Maksim Tsikavsvili</title>
  <link rel="stylesheet" href="style.css">
  <script defer src="script.js"></script>
</head>

<body>

<header class="topbar">
  <div class="site-name">Maksim Tsikavsvili</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="index.php">Vana Index</a>
  </nav>
</header>

<div class="logo-area">
  <img id="mainLogo" src="logo.png" class="main-logo" alt="Logo">
</div>

<div class="container">

  <div class="section-header">
    <h2>Tööd</h2>
  </div>

  <div class="grid">
    <?php foreach ($posts as $post) { ?>
      <div class="card post" onclick="loadPost('<?php echo $post['id']; ?>')">
    <h3><?php echo $post['title']; ?></h3>
    <p><?php echo $post['desc']; ?></p>
    <small><?php echo $post['date']; ?></small>
</div>
    <?php } ?>
  </div>

  <div id="content-area"></div>

</div>

<footer class="footer">
  © Maksim Tsikavsvili 2025
</footer>

</body>
</html>