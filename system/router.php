<?php
/**
 * Router: public/index.php sends every non-file request to dispatch().
 * Each route loads only the files it needs, so a sitemap or form request never
 * loads the page views, SEO or icon code (and vice versa).
 */

function redirect_to($path) {
    $qs = $_SERVER['QUERY_STRING'] ?? '';
    header('Location: ' . site_url($path) . ($qs !== '' ? '?' . $qs : ''), true, 301);
    exit;
}

/** URL path relative to BASE_URL, without leading slash ('' = home). */
function request_path() {
    $uri = rawurldecode(strtok($_SERVER['REQUEST_URI'] ?? '/', '?'));
    $base = rtrim((string) parse_url(config('BASE_URL'), PHP_URL_PATH), '/');
    $path = ($base !== '' && strpos($uri, $base) === 0) ? substr($uri, strlen($base)) : $uri;
    return ltrim($path, '/');
}

function dispatch() {
    $path = request_path();

    // One URL per page: /about.php, /about/ and /index all redirect to the clean URL.
    $clean = preg_replace('/\.php$/', '', $path);
    $clean = rtrim(preg_replace('#(^|/)index$#', '', $clean), '/');
    if ($clean !== $path) {
        redirect_to($clean);
    }

    load('routes');
    $route = routes()[$path] ?? null;

    // Feeds and the contact handler: load only their controller
    if (is_array($route)) {
        [$file, $handler] = $route;
        load($file);
        return $handler();
    }

    // Pages (and the 404) are rendered with views/
    require_once ROOT_PATH . '/system/view.php';
    if ($route !== null) {
        return render_page($route);
    }
    render_page('not-found', 'main', 404);
}
