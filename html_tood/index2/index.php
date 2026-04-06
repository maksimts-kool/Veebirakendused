<?php
require_once 'configidx.php';

$latestWorks = getLatestWorks($phpWorks, 3);
$categories = getCategories($phpWorks);

// Category filter
$selectedCategory = isset($_GET['category']) ? $_GET['category'] : 'all';

// Get works based on category
if ($selectedCategory === 'all') {
    $filteredWorks = sortWorksByDate($phpWorks);
} elseif (in_array($selectedCategory, $categories)) {
    $filteredWorks = sortWorksByDate(getWorksByCategory($phpWorks, $selectedCategory));
} else {
    $filteredWorks = sortWorksByDate($phpWorks);
    $selectedCategory = 'all';
}

// Pagination settings
$perPage = 8;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$totalWorks = count($filteredWorks);
$totalPages = ceil($totalWorks / $perPage);
$offset = ($currentPage - 1) * $perPage;
$paginatedWorks = array_slice($filteredWorks, $offset, $perPage);
?>
<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minu PHP Tööd - Portfoolio</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="header-content">
                <img src="logo.png" alt="Logo" class="logo">
                <h1>Minu PHP Tööd</h1>
                <p class="subtitle">Veebirakenduste arendamise portfoolio</p>
            </div>
            <div class="header-nav">
                <a href="../../index.html" class="btn-back">← Tagasi pealehele</a>
                <a href="ip-admin.php" class="btn-back">IP Admin</a>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Latest Posts Section -->
        <section class="latest-posts">
            <h2>Viimased Tööd</h2>
            <div class="posts-grid">
                <?php foreach ($latestWorks as $work): ?>
                <?php $theme = getCategoryTheme($work['category'], $categoryColors); ?>
                <article class="post-card featured" data-category="<?= htmlspecialchars($work['category']) ?>"
                    style="--cat-color: <?= htmlspecialchars($theme['color']) ?>; --cat-bg: <?= htmlspecialchars($theme['bg']) ?>; --cat-border: <?= htmlspecialchars($theme['border']) ?>;">
                    <div class="post-header">
                        <span class="category-badge"
                            data-category="<?= htmlspecialchars($work['category']) ?>"><?= htmlspecialchars($work['category']) ?></span>
                        <span class="post-date"><?= date('d.m.Y', strtotime($work['date'])) ?></span>
                    </div>
                    <h3><?= htmlspecialchars($work['title']) ?></h3>
                    <p class="post-description"><?= htmlspecialchars($work['description']) ?></p>
                    <a href="<?= htmlspecialchars($work['link']) ?>" class="btn-view">Vaata tööd →</a>
                </article>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- All Posts Section -->
        <section class="all-posts" id="koik-tood">
            <h2>Kõik Tööd</h2>

            <!-- Category Filter -->
            <div class="category-filter">
                <a href="?category=all#koik-tood"
                    class="filter-btn <?= $selectedCategory === 'all' ? 'active' : '' ?>">Kõik</a>
                <?php foreach ($categories as $category): ?>
                <?php $theme = getCategoryTheme($category, $categoryColors); ?>
                <a href="?category=<?= urlencode($category) ?>#koik-tood"
                    data-category="<?= htmlspecialchars($category) ?>"
                    class="filter-btn <?= $selectedCategory === $category ? 'active' : '' ?>"
                    style="--cat-color: <?= htmlspecialchars($theme['color']) ?>; --cat-bg: <?= htmlspecialchars($theme['bg']) ?>; --cat-border: <?= htmlspecialchars($theme['border']) ?>;">
                    <?= htmlspecialchars($category) ?>
                </a>
                <?php endforeach; ?>
            </div>

            <!-- Posts List -->
            <div class="posts-list">
                <?php if (count($paginatedWorks) > 0): ?>
                <?php foreach ($paginatedWorks as $work): ?>
                <?php $theme = getCategoryTheme($work['category'], $categoryColors); ?>
                <article class="post-item" data-category="<?= htmlspecialchars($work['category']) ?>"
                    style="--cat-color: <?= htmlspecialchars($theme['color']) ?>; --cat-bg: <?= htmlspecialchars($theme['bg']) ?>; --cat-border: <?= htmlspecialchars($theme['border']) ?>;">
                    <div class="post-item-header">
                        <div>
                            <h3><?= htmlspecialchars($work['title']) ?></h3>
                            <span class="category-badge"
                                data-category="<?= htmlspecialchars($work['category']) ?>"><?= htmlspecialchars($work['category']) ?></span>
                        </div>
                        <span class="post-date"><?= date('d.m.Y', strtotime($work['date'])) ?></span>
                    </div>
                    <p class="post-description"><?= htmlspecialchars($work['description']) ?></p>
                    <a href="<?= htmlspecialchars($work['link']) ?>" class="btn-view">Vaata tööd →</a>
                </article>
                <?php endforeach; ?>
                <?php else: ?>
                <p style="text-align: center; color: var(--text-muted); padding: 2rem;">Selles kategoorias töid ei
                    leitud.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <div class="pagination">
                <?php 
                $categoryParam = $selectedCategory !== 'all' ? '&category=' . urlencode($selectedCategory) : '';
                ?>
                <?php if ($currentPage > 1): ?>
                <a href="?page=<?= $currentPage - 1 ?><?= $categoryParam ?>#koik-tood" class="pagination-btn">←
                    Eelmine</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="?page=<?= $i ?><?= $categoryParam ?>#koik-tood"
                    class="pagination-btn <?= $i === $currentPage ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                <a href="?page=<?= $currentPage + 1 ?><?= $categoryParam ?>#koik-tood" class="pagination-btn">Järgmine
                    →</a>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </section>
    </main>

    <footer>
        <div class="container">
            <p>&copy; <?= date('Y') ?> Minu PHP Tööd. Kõik õigused kaitstud.</p>
        </div>
    </footer>

    <script>
    // No client-side filtering needed - using server-side filtering
    // Category selection handled by URL parameters
    </script>
</body>

</html>
