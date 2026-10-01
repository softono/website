<?php
// Served as /sitemap.xml (see .htaccess / nginx.conf). Built from BASE_URL and the page registry.
include_once __DIR__ . '/../src/config.php';
include_once __DIR__ . '/../src/seo.php';

header('Content-Type: application/xml; charset=UTF-8');
header('X-Robots-Tag: noindex');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
foreach (seo_pages() as $page) {
    $file = __DIR__ . '/' . $page['file'];
    echo "  <url>\n";
    echo '    <loc>' . e(base_url() . $page['path']) . "</loc>\n";
    if (is_file($file)) echo '    <lastmod>' . gmdate('Y-m-d', filemtime($file)) . "</lastmod>\n";
    echo '    <changefreq>' . $page['changefreq'] . "</changefreq>\n";
    echo '    <priority>' . $page['priority'] . "</priority>\n";
    echo "  </url>\n";
}
echo "</urlset>\n";
