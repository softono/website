<?php
include_once __DIR__ . '/config.php';
include_once __DIR__ . '/seo.php';
include_once __DIR__ . '/icons.php';
$baseUrl = BASE_URL;
$version = ASSET_VERSION;

$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
$currentPageKey = ($currentPage === 'index' || $currentPage === '') ? 'home' : $currentPage;
$seo = get_seo_meta($currentPageKey);

$navItems = ['about' => 'About', 'services' => 'Services', 'contact' => 'Contact'];

// PJAX requests fetch only the <main> content
$partial = !empty($_GET['partial']) && ($_GET['layout'] ?? '') === 'main';
if (!$partial) {
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($seo['title']); ?></title>
    <meta name="description" content="<?= htmlspecialchars($seo['description']); ?>">
    <meta name="robots" content="<?= htmlspecialchars($seo['robots']); ?>">
    <link rel="canonical" href="<?= htmlspecialchars($seo['canonical']); ?>">
    <base href="<?= $baseUrl; ?>">
    <link rel="icon" type="image/svg+xml" href="assets/images/logo.svg">
    <script>
        (function () {
            var pref = 'system';
            try { pref = localStorage.getItem('theme') || 'system'; } catch (e) {}
            var dark = pref === 'dark' || (pref === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    <link href="assets/css/custom.css?v=<?= $version; ?>" rel="stylesheet">
    <link href="assets/theme/theme-claude.css?v=<?= $version; ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com?v=<?= $version; ?>"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { colors: { indigo: { 50:'#f0fdf4',100:'#dcfce7',400:'#4cc274',500:'#2e9e4f',600:'#1f8a3e',700:'#17702f',900:'#0f4420' } } } },
        };
        var documentReadyFunctions = [];
        function documentReady(fn) { documentReadyFunctions.push(fn); }
    </script>
</head>
<body class="min-h-dvh t-page antialiased">
    <header class="sticky top-0 z-40 t-header t-border border-b backdrop-blur">
        <div class="mx-auto flex h-16 max-w-6xl items-center justify-between px-4 sm:px-6">
            <a href="./" class="pjax flex items-center" aria-label="Home">
                <span class="t-brand" aria-hidden="true"><svg viewBox="0 0 40 40" class="h-9 w-9"><rect x="2" y="2" width="36" height="36" rx="9" fill="currentColor"/><path d="M13 29V11h7a5.5 5.5 0 0 1 0 11h-7" fill="none" stroke="var(--primary-foreground)" stroke-width="3.2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                <span class="ml-2.5 text-lg font-bold tracking-tight t-heading">Brand</span>
            </a>
            <nav class="flex items-center gap-1" aria-label="Primary">
                <?php foreach ($navItems as $key => $label): ?>
                    <a href="<?= $key; ?>" class="nav-link pjax<?= $currentPageKey === $key ? ' active' : ''; ?>"><?= $label; ?></a>
                <?php endforeach; ?>
            </nav>
        </div>
    </header>
    <main id="main-container" data-layout="main" tabindex="-1">
<?php } ?>
