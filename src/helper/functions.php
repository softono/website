<?php
/** Page and feature lookups shared by the views, SEO and the sitemap. */

/** URL path of a page key ('' = home), or null when the page has no route (404). */
function page_path($key) {
    load('routes');
    $path = array_search($key, routes(), true);
    return $path === false ? null : (string) $path;
}

/** Link target for a page, relative to <base href>. */
function page_href($key) {
    $path = page_path($key);
    return ($path === null || $path === '') ? './' : $path;
}

/** Page registry (src/data/pages.php), loaded once. */
function site_pages() {
    static $pages;
    return $pages ?? ($pages = require ROOT_PATH . '/src/data/pages.php');
}

/** Feature cards (src/data/features.php), loaded once. */
function site_features() {
    static $features;
    return $features ?? ($features = require ROOT_PATH . '/src/data/features.php');
}
