<?php
/** Page registry: single source of truth for SEO meta, the sitemap and robots.txt. */
function seo_pages() {
    $name = APP_NAME;
    return [
        'home' => [
            'path' => '', 'file' => 'index.php', 'priority' => '1.0', 'changefreq' => 'weekly',
            'title' => $name . ' - Build something amazing, faster',
            'description' => 'A modern full-stack platform to power your next project. Simple, fast, and ready to scale.',
        ],
        'about' => [
            'path' => 'about', 'file' => 'about.php', 'priority' => '0.7', 'changefreq' => 'monthly',
            'title' => 'About us - ' . $name,
            'description' => 'Learn about our mission to help teams ship faster with authentication, an admin dashboard and an optimized foundation out of the box.',
        ],
        'services' => [
            'path' => 'services', 'file' => 'services.php', 'priority' => '0.8', 'changefreq' => 'monthly',
            'title' => 'Our services - ' . $name,
            'description' => 'Fast server-side rendering, secure-by-default access control, an admin dashboard and reliable data management for your project.',
        ],
        'contact' => [
            'path' => 'contact', 'file' => 'contact.php', 'priority' => '0.6', 'changefreq' => 'yearly',
            'title' => 'Contact us - ' . $name,
            'description' => 'Get in touch with the ' . $name . ' team. Send us a message and we will reply soon.',
        ],
    ];
}

/** Per-page SEO metadata. */
function get_seo_meta($pageKey) {
    $pages = seo_pages();
    $page = $pages[$pageKey] ?? null;
    $robots = $page ? 'index, follow, max-image-preview:large, max-snippet:-1' : 'noindex, follow';
    $page = $page ?? $pages['home'];

    $base = base_url();
    $canonical = $base . $page['path'];

    return [
        'title' => $page['title'],
        'description' => $page['description'],
        'robots' => $robots,
        'canonical' => $canonical,
        'site_name' => APP_NAME,
        'type' => $pageKey === 'home' ? 'website' : 'article',
        'jsonld' => seo_jsonld($pageKey, $page, $canonical, $base),
    ];
}

/** Structured data (schema.org JSON-LD) for the page. */
function seo_jsonld($pageKey, $page, $canonical, $base) {
    $graph = [
        [
            '@type' => 'Organization',
            '@id' => $base . '#organization',
            'name' => APP_NAME,
            'url' => $base,
            'logo' => $base . 'assets/images/logo.svg',
        ],
        [
            '@type' => 'WebSite',
            '@id' => $base . '#website',
            'url' => $base,
            'name' => APP_NAME,
            'publisher' => ['@id' => $base . '#organization'],
        ],
        [
            '@type' => 'WebPage',
            '@id' => $canonical . '#webpage',
            'url' => $canonical,
            'name' => $page['title'],
            'description' => $page['description'],
            'isPartOf' => ['@id' => $base . '#website'],
        ],
    ];
    if ($pageKey !== 'home') {
        $graph[] = [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $base],
                ['@type' => 'ListItem', 'position' => 2, 'name' => ucfirst($pageKey), 'item' => $canonical],
            ],
        ];
    }
    // JSON_HEX_TAG keeps a stray "</script>" in any value from breaking out of the tag.
    return json_encode(['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
}
