<?php
/**
 * Router for PHP built-in server: php -S localhost:8080 router.php
 */
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$file = __DIR__ . $path;
if ($path !== '/' && is_file($file) && !str_ends_with($path, '.php')) {
    return false;
}
require __DIR__ . '/index.php';
