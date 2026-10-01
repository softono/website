<?php
// Minimal .env loader: KEY=value lines become constants.
$env = @file_get_contents(__DIR__ . '/../.env') ?: '';
foreach (explode("\n", $env) as $line) {
    if (preg_match('/^\s*([^#=\s]+)\s*=(.*)$/', $line, $m) && !defined(trim($m[1]))) {
        define(trim($m[1]), trim($m[2]));
    }
}

// Safe defaults so a missing .env key never causes a fatal "undefined constant".
foreach (['BASE_URL' => '/', 'ASSET_VERSION' => '1', 'APP_NAME' => 'Website', 'CONTACT_EMAIL' => ''] as $k => $v) {
    if (!defined($k)) define($k, $v);
}

/** HTML-escape for output. */
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** BASE_URL guaranteed to end with a single trailing slash. */
function base_url() {
    return rtrim(BASE_URL, '/') . '/';
}

/** True when the site is configured to be served over HTTPS. */
function site_is_https() {
    return stripos(BASE_URL, 'https://') === 0;
}
