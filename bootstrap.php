<?php

require_once __DIR__ . '/app/Core/helpers.php';
require_once __DIR__ . '/app/Core/Database.php';
require_once __DIR__ . '/app/Core/Auth.php';
require_once __DIR__ . '/app/Core/View.php';
require_once __DIR__ . '/app/Core/Upload.php';
require_once __DIR__ . '/app/Core/Router.php';

// Controllers (explicit load — reliable on all shared hosts)
require_once __DIR__ . '/app/Controllers/PublicController.php';
require_once __DIR__ . '/app/Controllers/AdmissionController.php';
require_once __DIR__ . '/app/Controllers/AdminAuthController.php';
require_once __DIR__ . '/app/Controllers/AdminDashboardController.php';
require_once __DIR__ . '/app/Controllers/AdminAdmissionController.php';
require_once __DIR__ . '/app/Controllers/AdminStudentController.php';
require_once __DIR__ . '/app/Controllers/AdminFeeController.php';
require_once __DIR__ . '/app/Controllers/AdminStaffSalaryController.php';
require_once __DIR__ . '/app/Controllers/AdminNotificationController.php';
require_once __DIR__ . '/app/Core/AdminNotifications.php';
require_once __DIR__ . '/app/Core/DatabaseRepair.php';
require_once __DIR__ . '/app/Controllers/AdminCmsController.php';
require_once __DIR__ . '/app/Controllers/AdminFinanceController.php';
require_once __DIR__ . '/app/Models/SiteData.php';

date_default_timezone_set(config('timezone', 'Asia/Kolkata'));

if (is_installed()) {
    \App\Core\DatabaseRepair::ensure();
}

session_name(config('session_name', 'maner_iti_session'));
if (session_status() === PHP_SESSION_NONE) {
    $isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => $isSecure,
        'httponly' => true,
        'samesite' => 'Strict',
    ]);
    session_start();

    if (!isset($_SESSION['_created'])) {
        $_SESSION['_created'] = time();
    } elseif (time() - $_SESSION['_created'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['_created'] = time();
    }
}

spl_autoload_register(function ($class) {
    if (str_starts_with($class, 'App\\')) {
        $relative = str_replace('\\', '/', substr($class, 4));
        $file = __DIR__ . '/app/' . $relative . '.php';
        if (is_file($file)) {
            require_once $file;
        }
    }
});

if (config('debug', false)) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', '0');
}

set_exception_handler(function (Throwable $e) {
    error_log($e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    http_response_code(500);
    if (config('debug', false)) {
        echo '<pre>' . e($e->getMessage()) . '</pre>';
    } elseif (!is_installed()) {
        echo '<h1>Setup Required</h1><p><a href="' . e(site_url('install.php')) . '">Run install.php</a></p>';
    } else {
        echo '<h1>Server Error</h1><p>Something went wrong. Please try again later.</p>';
    }
    exit;
});
