<?php
/** Generated site files: /sitemap.xml, /robots.txt and /manifest. */

load('helper/functions');

function render_sitemap() {
    header('Content-Type: application/xml; charset=utf-8');
    echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
    echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    foreach (site_pages() as $key => $page) {
        if (!empty($page['hidden'])) {
            continue;
        }
        $mtime = @filemtime(VIEWS_PATH . '/' . $page['view'] . '.php');
        echo '  <url><loc>' . htmlspecialchars(site_url(page_path($key)), ENT_XML1) . '</loc>'
            . ($mtime ? '<lastmod>' . date('Y-m-d', $mtime) . '</lastmod>' : '')
            . '</url>' . "\n";
    }
    echo '</urlset>' . "\n";
}

function render_robots() {
    header('Content-Type: text/plain; charset=utf-8');
    echo "User-agent: *\n";
    echo "Allow: /\n";
    echo "Disallow: /contact-send\n";
    echo "Disallow: /*?partial=\n";
    echo "\n";
    echo 'Sitemap: ' . site_url('sitemap.xml') . "\n";
}

function render_manifest() {
    header('Content-Type: application/manifest+json; charset=utf-8');
    echo json_encode([
        'name' => config('APP_NAME'),
        'short_name' => config('APP_NAME'),
        'description' => site_pages()['home']['description'],
        'start_url' => site_url(),
        'scope' => site_url(),
        'display' => 'standalone',
        'background_color' => config('BACKGROUND_COLOR'),
        'theme_color' => config('THEME_COLOR'),
        'icons' => [
            ['src' => site_url('assets/images/icon-192.png'), 'sizes' => '192x192', 'type' => 'image/png'],
            ['src' => site_url('assets/images/icon-512.png'), 'sizes' => '512x512', 'type' => 'image/png'],
            ['src' => site_url('assets/images/icon-512.png'), 'sizes' => '512x512', 'type' => 'image/png', 'purpose' => 'maskable'],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
}
