<?php
define('ROOT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('VIEWS_PATH', ROOT_PATH . '/views');

// Only the essentials are loaded on every request. Everything else is loaded
// on demand with load('name') by the route that needs it (see system/router.php).
require __DIR__ . '/config.php';
require __DIR__ . '/errors.php';
require __DIR__ . '/router.php';

register_error_handling();
