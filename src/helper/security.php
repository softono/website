<?php
/** Form protection: a double-submit CSRF token and a simple per-IP rate limit. */

/** Token stored in a cookie and echoed in the form; a POST is valid only if both match. */
function csrf_token() {
    $token = $_COOKIE['csrf'] ?? '';
    if (!preg_match('/^[a-f0-9]{64}$/', $token)) {
        $token = bin2hex(random_bytes(32));
        setcookie('csrf', $token, [
            'expires'  => 0,
            'path'     => rtrim((string) parse_url(config('BASE_URL'), PHP_URL_PATH), '/') . '/',
            'secure'   => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }
    return $token;
}

function csrf_field() {
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_valid($submitted) {
    $cookie = $_COOKIE['csrf'] ?? '';
    return is_string($submitted) && $cookie !== '' && hash_equals($cookie, $submitted);
}

/** True when this visitor already made $max requests for $name in the last $seconds. Records this one otherwise. */
function rate_limited($name, $max, $seconds) {
    $file = sys_get_temp_dir() . '/rl-' . md5($name . ($_SERVER['REMOTE_ADDR'] ?? '')) . '.json';
    $now = time();
    $hits = is_file($file) ? (json_decode((string) @file_get_contents($file), true) ?: []) : [];
    $hits = array_values(array_filter($hits, function ($t) use ($now, $seconds) { return $t > $now - $seconds; }));
    if (count($hits) >= $max) {
        return true;
    }
    $hits[] = $now;
    @file_put_contents($file, json_encode($hits), LOCK_EX);
    return false;
}
