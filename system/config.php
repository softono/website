<?php
/** Loads src/<name>.php once. Call it only where the file is needed. */
function load($name) {
    require_once ROOT_PATH . '/src/' . $name . '.php';
}

/** Setting from .env (KEY=value lines) with built-in defaults, e.g. config('APP_NAME'). */
function config($key, $default = null) {
    static $values;
    if ($values === null) {
        $values = [
            'BASE_URL'              => '/',
            'APP_NAME'              => 'Website',
            'APP_DEBUG'             => '0',
            'CONTACT_EMAIL'         => '',
            'THEME'                 => 'claude',
            'THEME_COLOR'           => '#c96442',
            'BACKGROUND_COLOR'      => '#faf9f5',
            'BACKGROUND_COLOR_DARK' => '#2b2a27',
            'OG_IMAGE'              => 'assets/images/og-image.png',
            'SOCIAL_LINKS'          => '',
            'TWITTER_HANDLE'        => '',
            'MAIL_API_URL'          => '',
            'MAIL_API_KEY'          => '',
            'MAIL_TO'               => '',
            'MAIL_FROM_EMAIL'       => 'noreply@localhost',
            'MAIL_BCC'              => '',
        ];
        $env = @file_get_contents(ROOT_PATH . '/.env') ?: '';
        foreach (preg_split('/\R/', $env) as $line) {
            if (preg_match('/^\s*([^#=\s]+)\s*=(.*)$/', $line, $m)) {
                $values[trim($m[1])] = trim($m[2]);
            }
        }
    }
    return $values[$key] ?? $default;
}

/** Escapes a value for HTML output. */
function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES);
}

/** Asset path with a cache-busting version taken from the file's modification time. */
function asset($path) {
    $file = PUBLIC_PATH . '/' . $path;
    return $path . (is_file($file) ? '?v=' . filemtime($file) : '');
}

/** True when PJAX asks for only the <main> content. */
function is_partial() {
    return !empty($_GET['partial']) && ($_GET['layout'] ?? '') === 'main';
}

/** Active theme name; falls back to "claude" if the file does not exist. */
function theme_name() {
    $t = preg_replace('/[^a-z0-9-]/', '', strtolower(config('THEME')));
    return is_file(PUBLIC_PATH . "/assets/theme/theme-$t.css") ? $t : 'claude';
}

/** Absolute URL for a site-relative path (empty = home). */
function site_url($path = '') {
    return rtrim(config('BASE_URL'), '/') . '/' . ltrim($path, '/');
}
