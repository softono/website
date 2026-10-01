<?php
/** Per-page SEO metadata, read from the page registry. */
function get_seo_meta($pageKey) {
    $pages = site_pages();
    $key = isset($pages[$pageKey]) ? $pageKey : 'home';
    $page = $pages[$key];
    $hidden = !empty($page['hidden']);
    return [
        'key' => $key,
        'name' => $page['name'],
        'title' => $page['title'],
        'description' => $page['description'],
        'schema' => $page['schema'],
        'robots' => $hidden ? 'noindex, follow' : 'index, follow, max-image-preview:large, max-snippet:-1',
        'canonical' => $hidden ? null : site_url(page_path($key)),
        'image' => site_url(config('OG_IMAGE')),
    ];
}

function seo_social_links() {
    return array_values(array_filter(array_map('trim', explode(',', config('SOCIAL_LINKS')))));
}

/** schema.org graph: Organization, WebSite, WebPage and BreadcrumbList. */
function seo_jsonld($pageKey) {
    $m = get_seo_meta($pageKey);
    $home = site_url();
    $org = ['@type' => 'Organization', '@id' => $home . '#organization', 'name' => config('APP_NAME'), 'url' => $home,
        'logo' => ['@type' => 'ImageObject', 'url' => site_url('assets/images/icon-512.png'), 'width' => 512, 'height' => 512]];
    if (config('CONTACT_EMAIL') !== '') {
        $org['email'] = config('CONTACT_EMAIL');
    }
    if ($links = seo_social_links()) {
        $org['sameAs'] = $links;
    }
    $graph = [
        $org,
        ['@type' => 'WebSite', '@id' => $home . '#website', 'url' => $home, 'name' => config('APP_NAME'),
            'inLanguage' => 'en', 'publisher' => ['@id' => $home . '#organization']],
    ];
    if ($m['canonical']) {
        $url = $m['canonical'];
        $crumbs = [['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => $home]];
        if ($m['key'] !== 'home') {
            $crumbs[] = ['@type' => 'ListItem', 'position' => 2, 'name' => $m['name'], 'item' => $url];
        }
        $graph[] = ['@type' => $m['schema'], '@id' => $url . '#webpage', 'url' => $url, 'name' => $m['title'],
            'description' => $m['description'], 'inLanguage' => 'en',
            'isPartOf' => ['@id' => $home . '#website'], 'about' => ['@id' => $home . '#organization'],
            'primaryImageOfPage' => ['@type' => 'ImageObject', 'url' => $m['image']],
            'breadcrumb' => ['@id' => $url . '#breadcrumb']];
        $graph[] = ['@type' => 'BreadcrumbList', '@id' => $url . '#breadcrumb', 'itemListElement' => $crumbs];
    }
    return json_encode(['@context' => 'https://schema.org', '@graph' => $graph],
        JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP);
}

/** All SEO tags for <head>, except <title>. */
function seo_head($pageKey) {
    $m = get_seo_meta($pageKey);
    $o = '<meta name="description" content="' . e($m['description']) . '">' . "\n";
    $o .= '    <meta name="robots" content="' . e($m['robots']) . '">' . "\n";
    if ($m['canonical']) {
        $o .= '    <link rel="canonical" href="' . e($m['canonical']) . '">' . "\n";
    }
    $og = [
        'og:type' => $m['key'] === 'home' ? 'website' : 'article',
        'og:site_name' => config('APP_NAME'),
        'og:locale' => 'en_US',
        'og:title' => $m['title'],
        'og:description' => $m['description'],
        'og:image' => $m['image'],
        'og:image:width' => '1200',
        'og:image:height' => '630',
        'og:image:alt' => config('APP_NAME'),
    ];
    if ($m['canonical']) {
        $og['og:url'] = $m['canonical'];
    }
    foreach ($og as $k => $v) {
        $o .= '    <meta property="' . $k . '" content="' . e($v) . '">' . "\n";
    }
    $tw = ['twitter:card' => 'summary_large_image', 'twitter:title' => $m['title'],
        'twitter:description' => $m['description'], 'twitter:image' => $m['image']];
    if (config('TWITTER_HANDLE') !== '') {
        $tw['twitter:site'] = '@' . ltrim(config('TWITTER_HANDLE'), '@');
    }
    foreach ($tw as $k => $v) {
        $o .= '    <meta name="' . $k . '" content="' . e($v) . '">' . "\n";
    }
    $o .= '    <script type="application/ld+json">' . seo_jsonld($pageKey) . '</script>' . "\n";
    return $o;
}
