<?php
// Minimal .env loader: KEY=value lines become constants.
$env = @file_get_contents(__DIR__ . '/../.env') ?: '';
foreach (explode("\n", $env) as $line) {
    if (preg_match('/^\s*([^#=\s]+)\s*=(.*)$/', $line, $m) && !defined(trim($m[1]))) {
        define(trim($m[1]), trim($m[2]));
    }
}
