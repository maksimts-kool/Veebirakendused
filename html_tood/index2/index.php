<?php
require_once 'configidx.php';

$categories = getCategories($phpWorks);
$sortedWorks = sortWorksByDate($phpWorks);

// Kategooria filter
$selectedCategory = isset($_GET['category']) ? (string)$_GET['category'] : 'all';
if ($selectedCategory !== 'all' && !in_array($selectedCategory, $categories, true)) {
    $selectedCategory = 'all';
}
$filteredWorks = $selectedCategory === 'all'
    ? $sortedWorks
    : array_values(getWorksByCategory($sortedWorks, $selectedCategory));

// Lehekülgede jaotus
$perPage = 9;
$totalWorks = count($filteredWorks);
$totalPages = max(1, (int)ceil($totalWorks / $perPage));
$currentPage = isset($_GET['page']) ? min($totalPages, max(1, (int)$_GET['page'])) : 1;
$paginatedWorks = array_slice($filteredWorks, ($currentPage - 1) * $perPage, $perPage);

function e($value)
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function pageUrl($page, $category)
{
    $params = [];
    if ($category !== 'all') {
        $params['category'] = $category;
    }
    if ($page > 1) {
        $params['page'] = $page;
    }
    return '?' . http_build_query($params) . '#tood';
}
?>
<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP tööd – Maksim Tsikvasvili</title>
    <meta name="description" content="PHP ja MySQL tööd veebirakenduste kursuselt.">
    <!-- Ikoonid: Lucide (ISC litsents, https://lucide.dev/license) ja Simple Icons (CC0, https://simpleicons.org) -->
    <script>document.documentElement.classList.add("js");try{if(localStorage.getItem("hybridmagDarkMode")==="enabled")document.documentElement.classList.add("hm-dark")}catch(e){}</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Figtree:wght@400;500;600;700&amp;display=swap">
    <link rel="stylesheet" href="../../style.css">
    <script src="../../site.js" defer></script>
</head>

<body>
    <a class="skip-link screen-reader-text" href="#sisu">Liigu sisu juurde</a>

    <header class="site-header">
        <div class="container header-inner">
            <a class="brand" href="../../index.html" rel="home">
                <span class="brand-mark" aria-hidden="true">MT</span>
                <span class="brand-text"><strong>Maksim Tsikvasvili</strong><small>Veebirakendused</small></span>
            </a>

            <nav class="main-nav" id="peamenyy" aria-label="Peamenüü">
                <ul>
                    <li><a href="../../index.html">Kodu</a></li>
                    <li><a href="../../teemad_lingid.html">Teemad / Lingid</a></li>
                    <li><a href="../../tehtud_tood.html">Tehtud tööd</a></li>
                    <li><a href="../../html_tood/index2/index.php" aria-current="page">PHP tööd</a></li>
                    <li><a href="../../minust.html">Minust</a></li>
                    <li class="nav-blog"><a href="https://maksimtsikvasvili24.thkit.ee/wp/">Blogi <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M7 7h10v10" /><path d="M7 17 17 7" /></svg></a></li>
                </ul>
            </nav>

            <div class="header-actions">
                <button class="icon-button dark-toggle" type="button" aria-pressed="false" aria-label="Lülita tume režiim">
                    <svg class="icon icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M20.985 12.486a9 9 0 1 1-9.473-9.472c.405-.022.617.46.402.803a6 6 0 0 0 8.268 8.268c.344-.215.825-.004.803.401" /></svg><svg class="icon icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><circle cx="12" cy="12" r="4" /><path d="M12 2v2" /><path d="M12 20v2" /><path d="m4.93 4.93 1.41 1.41" /><path d="m17.66 17.66 1.41 1.41" /><path d="M2 12h2" /><path d="M20 12h2" /><path d="m6.34 17.66-1.41 1.41" /><path d="m19.07 4.93-1.41 1.41" /></svg>
                </button>
                <a class="button blog-link" href="https://maksimtsikvasvili24.thkit.ee/wp/">Blogi <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M7 7h10v10" /><path d="M7 17 17 7" /></svg></a>
                <button class="icon-button menu-toggle" type="button" aria-expanded="false" aria-controls="peamenyy" aria-label="Menüü"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M4 5h16" /><path d="M4 12h16" /><path d="M4 19h16" /></svg></button>
            </div>
        </div>
    </header>

    <main id="sisu">
    <section class="hero">
        <div class="container">
                    <p class="eyebrow">Serveripoolne programmeerimine</p>
                    <h1>PHP tööd</h1>
                    <p class="lead"><?= count($phpWorks) ?> tööd PHP ja MySQLiga: andmebaasid, vormid, failid, XML ja mallid. HTML-i ja JavaScripti tööd on lehel <a href="../../tehtud_tood.html">Tehtud tööd</a>.</p>
        </div>
    </section>

        <div class="container site-main">
            <ul class="filters" id="tood" aria-label="Kategooriad">
                <li><a class="filter" href="<?= e(pageUrl(1, 'all')) ?>"<?= $selectedCategory === 'all' ? ' aria-current="page"' : '' ?>>Kõik <span class="count"><?= count($phpWorks) ?></span></a></li>
                <?php foreach ($categories as $category): ?>
                <li><a class="filter" href="<?= e(pageUrl(1, $category)) ?>"<?= $selectedCategory === $category ? ' aria-current="page"' : '' ?>><?= e($category) ?> <span class="count"><?= count(getWorksByCategory($phpWorks, $category)) ?></span></a></li>
                <?php endforeach; ?>
            </ul>

            <?php if (count($paginatedWorks) > 0): ?>
            <div class="grid">
                <?php foreach ($paginatedWorks as $work): ?>
                <article class="card is-linked">
                    <ul class="tags"><li><a class="tag" href="<?= e(pageUrl(1, $work['category'])) ?>"><?= e($work['category']) ?></a></li></ul>
                    <h3><a href="<?= e($work['link']) ?>"><?= e($work['title']) ?></a></h3>
                    <p class="card-text"><?= e($work['description']) ?></p>
                    <div class="card-foot"><span class="meta"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M8 2v3" /><path d="M16 2v3" /><rect x="3" y="3" width="18" height="18" rx="2" /><path d="M3 9h18" /></svg><time datetime="<?= e($work['date']) ?>"><?= e(formatWorkDate($work['date'])) ?></time></span><span class="go">Ava <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg></span></div>
                </article>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <p class="card empty-state">Selles kategoorias töid ei leitud.</p>
            <?php endif; ?>

            <?php if ($totalPages > 1): ?>
            <nav class="pagination" aria-label="Lehed">
                <?php if ($currentPage > 1): ?>
                <a class="filter" href="<?= e(pageUrl($currentPage - 1, $selectedCategory)) ?>">Eelmine</a>
                <?php endif; ?>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a class="filter" href="<?= e(pageUrl($i, $selectedCategory)) ?>"<?= $i === $currentPage ? ' aria-current="page"' : '' ?>><?= $i ?></a>
                <?php endfor; ?>
                <?php if ($currentPage < $totalPages): ?>
                <a class="filter" href="<?= e(pageUrl($currentPage + 1, $selectedCategory)) ?>">Järgmine</a>
                <?php endif; ?>
            </nav>
            <?php endif; ?>

            <section class="section">
                <div class="section-head">
                    <h2 class="section-title">PHP blogis</h2>
                </div>
                <div class="grid">
                    <article class="card is-linked"><h3><a href="https://maksimtsikvasvili24.thkit.ee/wp/php/1972/">PHP Test</a></h3><p class="card-text">Blogipostitus PHP testist.</p></article>
                    <article class="card is-linked"><h3><a href="https://maksimtsikvasvili24.thkit.ee/wp/php/1963/">Jalgratta eksam</a></h3><p class="card-text">Jalgratta eksami rakenduse kirjeldus.</p></article>
                    <article class="card is-linked"><h3><a href="https://maksimtsikvasvili24.thkit.ee/wp/php/1947/">RSS paigaldamine</a></h3><p class="card-text">RSS-voo lisamine ja kuvamine.</p></article>
                </div>
            </section>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container footer-grid">
            <div class="footer-brand">
                <a class="brand" href="../../index.html" rel="home">
                <span class="brand-mark" aria-hidden="true">MT</span>
                <span class="brand-text"><strong>Maksim Tsikvasvili</strong><small>Veebirakendused</small></span>
            </a>
                <p>Veebirakenduste kursuse tööd Techno TLN-is. Konspektid ja kirjeldused on
                    <a href="https://maksimtsikvasvili24.thkit.ee/wp/">blogis</a>.</p>
            </div>
            <div>
                <h2>Lehed</h2>
                <ul><li><a href="../../index.html">Kodu</a></li><li><a href="../../teemad_lingid.html">Teemad / Lingid</a></li><li><a href="../../tehtud_tood.html">Tehtud tööd</a></li><li><a href="../../html_tood/index2/index.php">PHP tööd</a></li><li><a href="../../minust.html">Minust</a></li></ul>
            </div>
            <div>
                <h2>Kontakt</h2>
                <ul>
                    <li><a href="mailto:maksimtsitkool@gmail.com"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7" /><rect x="2" y="4" width="20" height="16" rx="2" /></svg> maksimtsitkool@gmail.com</a></li>
                    <li><a href="https://techno.ee/"><svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" focusable="false"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z" /><path d="M22 10v6" /><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5" /></svg> Techno TLN</a></li>
                    <li><a href="https://github.com/maksimts-kool"><svg class="icon" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false"><path d="M12 .297c-6.63 0-12 5.373-12 12 0 5.303 3.438 9.8 8.205 11.385.6.113.82-.258.82-.577 0-.285-.01-1.04-.015-2.04-3.338.724-4.042-1.61-4.042-1.61C4.422 18.07 3.633 17.7 3.633 17.7c-1.087-.744.084-.729.084-.729 1.205.084 1.838 1.236 1.838 1.236 1.07 1.835 2.809 1.305 3.495.998.108-.776.417-1.305.76-1.605-2.665-.3-5.466-1.332-5.466-5.93 0-1.31.465-2.38 1.235-3.22-.135-.303-.54-1.523.105-3.176 0 0 1.005-.322 3.3 1.23.96-.267 1.98-.399 3-.405 1.02.006 2.04.138 3 .405 2.28-1.552 3.285-1.23 3.285-1.23.645 1.653.24 2.873.12 3.176.765.84 1.23 1.91 1.23 3.22 0 4.61-2.805 5.625-5.475 5.92.42.36.81 1.096.81 2.22 0 1.606-.015 2.896-.015 3.286 0 .315.21.69.825.57C20.565 22.092 24 17.592 24 12.297c0-6.627-5.373-12-12-12"/></svg> GitHub</a></li>
                </ul>
            </div>
        </div>
        <div class="container footer-bottom">
            <span>© 2026 Maksim Tsikvasvili</span>
            <span>Õppekava: 3 aastat · TARpv24</span>
        </div>
    </footer>
</body>

</html>
