<?php
/**
 * Error handling. With APP_DEBUG=1 PHP shows errors; otherwise visitors get
 * views/500.php (real 500 status) and the details go to the PHP error log.
 */

function show_error_page() {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: text/html; charset=utf-8');
        header('X-Robots-Tag: noindex');
    }
    require VIEWS_PATH . '/500.php';
}

function register_error_handling() {
    error_reporting(E_ALL);
    if (config('APP_DEBUG') === '1') {
        ini_set('display_errors', '1');
        return;
    }
    ini_set('display_errors', '0');
    ini_set('log_errors', '1');

    set_exception_handler(function ($ex) {
        error_log('Uncaught ' . get_class($ex) . ': ' . $ex->getMessage() . ' in ' . $ex->getFile() . ':' . $ex->getLine());
        show_error_page();
    });
    register_shutdown_function(function () {
        $err = error_get_last();
        if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
            show_error_page();
        }
    });
}
