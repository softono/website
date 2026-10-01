<?php
/**
 * Security headers, sent from PHP so they apply on both Apache and Nginx.
 * Include before any output.
 */
include_once __DIR__ . '/config.php';

if (!headers_sent()) {
    header_remove('X-Powered-By');
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=(), payment=(), usb=()');
    header('Cross-Origin-Opener-Policy: same-origin');

    // Tailwind's CDN build and the inline bootstrap scripts need 'unsafe-inline'; everything
    // else is locked to self + the specific CDNs this site uses.
    header("Content-Security-Policy: default-src 'self'; "
        . "script-src 'self' 'unsafe-inline' https://cdnjs.cloudflare.com; "
        . "style-src 'self' 'unsafe-inline'; "
        . "img-src 'self' data:; font-src 'self' data:; connect-src 'self'; "
        . "object-src 'none'; base-uri 'self'; form-action 'self'; frame-ancestors 'self'"
        . (site_is_https() ? '; upgrade-insecure-requests' : ''));

    if (site_is_https()) {
        header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
    }
}
