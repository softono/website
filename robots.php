<?php
// Served as /robots.txt (see .htaccess / nginx.conf) so the sitemap URL always matches BASE_URL.
include_once __DIR__ . '/src/config.php';

header('Content-Type: text/plain; charset=UTF-8');

echo "User-agent: *\n";
echo "Allow: /\n";
echo "Disallow: /contact-send\n";
echo "Disallow: /layout/\n";
echo "Disallow: /src/\n";
echo "Disallow: /*?partial=\n";
echo "\n";
echo 'Sitemap: ' . base_url() . "sitemap.xml\n";
