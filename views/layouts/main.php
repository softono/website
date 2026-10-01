<?php
/**
 * Main layout. Variables from render_page(): $key (page key), $content (rendered view).
 * PJAX requests (?partial=1&layout=main) get only the <main> content.
 */
$seo = get_seo_meta($key);

$navPages = [];
foreach (site_pages() as $navKey => $p) {
    if (isset($p['nav'])) {
        $navPages[$navKey] = ['label' => $p['nav'], 'href' => page_href($navKey)];
    }
}

$mainContent = '<div id="main-content" data-title="' . e($seo['title'])
    . '" data-description="' . e($seo['description'])
    . '" data-canonical="' . e($seo['canonical'] ?? '') . '">' . $content . '</div>';

if (is_partial()) {
    // Keep fragments out of search indexes; point them at the real page.
    header('X-Robots-Tag: noindex, follow');
    if ($seo['canonical']) {
        header('Link: <' . $seo['canonical'] . '>; rel="canonical"');
    }
    echo $mainContent;
    return;
}
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= e($seo['title']); ?></title>
    <?= seo_head($key); ?>
    <meta name="color-scheme" content="light dark">
    <meta name="theme-color" content="<?= e(config('BACKGROUND_COLOR')); ?>" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="<?= e(config('BACKGROUND_COLOR_DARK')); ?>" media="(prefers-color-scheme: dark)">
    <link rel="manifest" href="manifest">
    <link rel="apple-touch-icon" href="assets/images/apple-touch-icon.png">
    <link rel="preconnect" href="https://cdn.tailwindcss.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <base href="<?= e(config('BASE_URL')); ?>">
    <link rel="icon" type="image/svg+xml" href="assets/images/logo.svg">
    <link rel="icon" type="image/png" sizes="192x192" href="assets/images/icon-192.png">
    <script>
        (function () {
            var pref = 'system';
            try { pref = localStorage.getItem('theme') || 'system'; } catch (e) {}
            var dark = pref === 'dark' || (pref === 'system' && matchMedia('(prefers-color-scheme: dark)').matches);
            document.documentElement.classList.toggle('dark', dark);
        })();
    </script>
    <link href="<?= asset('assets/theme/theme-' . theme_name() . '.css'); ?>" rel="stylesheet">
    <link href="<?= asset('assets/css/custom.css'); ?>" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = { darkMode: 'class' };
        var documentReadyFunctions = [];
        function documentReady(fn) { documentReadyFunctions.push(fn); }
    </script>
</head>
<body class="min-h-dvh t-page antialiased flex flex-col">
    <a href="<?= e(strtok($_SERVER['REQUEST_URI'], '?')); ?>#main-container" class="skip-link">Skip to main content</a>
    <?php component('header', ['key' => $key, 'navPages' => $navPages]); ?>
    <main id="main-container" class="flex-1" data-layout="main" tabindex="-1"><?= $mainContent; ?></main>
    <?php component('footer', ['navPages' => $navPages]); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="assets/js/pjax.min.js"></script>
    <script src="<?= asset('assets/js/app.js'); ?>"></script>
</body>
</html>
