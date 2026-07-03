<?php

require __DIR__ . '/bootstrap.php';

use App\Core\Router;
use App\Controllers\PublicController;
use App\Controllers\AdmissionController;
use App\Controllers\AdminAuthController;
use App\Controllers\AdminDashboardController;
use App\Controllers\AdminAdmissionController;
use App\Controllers\AdminStudentController;
use App\Controllers\AdminFeeController;
use App\Controllers\AdminCmsController;
use App\Controllers\AdminSiteController;
use App\Controllers\AdminStaffSalaryController;
use App\Controllers\AdminFinanceController;
use App\Controllers\AdminNotificationController;

// Allow install.php, reset-admin.php, repair-database.php without lock file
if (!is_installed() && strpos($_SERVER['SCRIPT_NAME'] ?? '', 'install.php') === false
    && strpos($_SERVER['SCRIPT_NAME'] ?? '', 'reset-admin.php') === false
    && strpos($_SERVER['SCRIPT_NAME'] ?? '', 'repair-database.php') === false) {
    header('Location: ' . site_url('install.php'));
    exit;
}

$uri = request_path();
$method = $_SERVER['REQUEST_METHOD'];

// Dynamic routes first
if (preg_match('#^/trades/([a-z0-9-]+)$#', $uri, $m) && $method === 'GET') {
    PublicController::tradeDetail($m[1]);
    exit;
}
if (preg_match('#^/apply-admission/print/(\d+)$#', $uri, $m) && $method === 'GET') {
    AdmissionController::print((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/admissions/view/(\d+)$#', $uri, $m) && $method === 'GET') {
    AdminAdmissionController::view((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/admissions/status/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminAdmissionController::updateStatus((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/admissions/documents/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminAdmissionController::documentsSave((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/admissions/print/(\d+)$#', $uri, $m) && $method === 'GET') {
    AdmissionController::print((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/students/view/(\d+)$#', $uri, $m) && $method === 'GET') {
    AdminStudentController::view((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/students/save(?:/(\d+))?$#', $uri, $m) && $method === 'POST') {
    AdminStudentController::save((int) ($m[1] ?? 0));
    exit;
}
if (preg_match('#^/admin/fees/receipt/(\d+)$#', $uri, $m) && $method === 'GET') {
    AdminFeeController::receipt((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/fees/pay/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminFeeController::pay((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/notices/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminCmsController::noticeDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/gallery/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminCmsController::galleryDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/results/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminCmsController::resultDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/sessions/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminCmsController::sessionDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/trades/edit(?:/(\d+))?$#', $uri, $m) && $method === 'GET') {
    AdminSiteController::tradeEdit(isset($m[1]) ? (int) $m[1] : null);
    exit;
}
if (preg_match('#^/admin/trades/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminSiteController::tradeDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/flash-news/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminSiteController::flashNewsDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/menus/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminSiteController::menuDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/footer-links/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminSiteController::footerLinkDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/newsletter/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminCmsController::newsletterDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/faculty/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminSiteController::facultyDelete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/staff/delete/(\d+)$#', $uri, $m) && $method === 'POST') {
    AdminStaffSalaryController::delete((int) $m[1]);
    exit;
}
if (preg_match('#^/admin/staff/salary/print/(\d+)$#', $uri, $m) && $method === 'GET') {
    AdminStaffSalaryController::salaryPrint((int) $m[1]);
    exit;
}

$router = new Router();

// Public GET
$router->get('/', fn() => PublicController::home());
$router->get('/about', fn() => PublicController::about());
$router->get('/trades', fn() => PublicController::trades());
$router->get('/bscc-info', fn() => PublicController::bsccInfo());
$router->get('/admission-process', fn() => PublicController::admissionProcess());
$router->get('/apply-admission', fn() => AdmissionController::form());
$router->get('/apply-admission/success', fn() => AdmissionController::success());
$router->get('/fee-structure', fn() => PublicController::feeStructure());
$router->get('/faculty', fn() => PublicController::faculty());
$router->get('/infrastructure', fn() => PublicController::infrastructure());
$router->get('/notices', fn() => PublicController::notices());
$router->get('/results', fn() => PublicController::results());
$router->get('/contact', fn() => PublicController::contact());
$router->get('/api/check-uidai', fn() => AdmissionController::checkUidai());
$router->get('/health', function () {
    header('Content-Type: application/json');
    echo json_encode(['ok' => true, 'app' => 'maner-iti-php', 'path' => request_path()]);
});

// Public POST
$router->post('/contact', fn() => PublicController::contactSubmit());
$router->post('/newsletter/subscribe', fn() => PublicController::newsletterSubscribe());
$router->post('/apply-admission', fn() => AdmissionController::submit());

// Admin auth
$router->get('/admin/login', fn() => AdminAuthController::loginForm());
$router->post('/admin/login', fn() => AdminAuthController::login());
$router->get('/admin/logout', fn() => AdminAuthController::logout());
$router->get('/admin/profile', fn() => AdminAuthController::profileForm());
$router->post('/admin/profile', fn() => AdminAuthController::profileSave());
$router->get('/admin/notifications/feed', fn() => AdminNotificationController::feed());

// Admin panel
$router->get('/admin', fn() => AdminDashboardController::index());
$router->get('/admin/admissions', fn() => AdminAdmissionController::index());
$router->get('/admin/admissions/add', fn() => AdminAdmissionController::createForm());
$router->post('/admin/admissions/add', fn() => AdminAdmissionController::save());
$router->get('/admin/admissions/export', fn() => AdminAdmissionController::exportCsv());
$router->get('/admin/students', fn() => AdminStudentController::index());
$router->get('/admin/fees', fn() => AdminFeeController::index());
$router->get('/admin/fees/collect', fn() => AdminFeeController::collectForm());
$router->get('/admin/fees/search', fn() => AdminFeeController::searchStudents());
$router->get('/admin/fees/due-notify', fn() => AdminFeeController::dueNotifyForm());
$router->post('/admin/fees/due-notify', fn() => AdminFeeController::dueNotifySend());
$router->get('/admin/fee-reminders', fn() => AdminFeeController::dueNotifyForm());
$router->post('/admin/fee-reminders', fn() => AdminFeeController::dueNotifySend());
$router->post('/admin/fees/collect', fn() => AdminFeeController::collectSave());
$router->post('/admin/fees', fn() => AdminFeeController::create());
$router->get('/admin/notices', fn() => AdminCmsController::notices());
$router->post('/admin/notices', fn() => AdminCmsController::noticeSave());
$router->get('/admin/gallery', fn() => AdminCmsController::gallery());
$router->post('/admin/gallery', fn() => AdminCmsController::gallerySave());
$router->get('/admin/results', fn() => AdminCmsController::results());
$router->post('/admin/results', fn() => AdminCmsController::resultSave());
$router->get('/admin/sessions', fn() => AdminCmsController::sessions());
$router->post('/admin/sessions', fn() => AdminCmsController::sessionSave());
$router->get('/admin/settings', fn() => AdminCmsController::settings());
$router->post('/admin/settings', fn() => AdminCmsController::settingsSave());
$router->get('/admin/contacts', fn() => AdminCmsController::contacts());
$router->get('/admin/newsletter', fn() => AdminCmsController::newsletter());
$router->get('/admin/newsletter/export', fn() => AdminCmsController::newsletterExport());
$router->get('/admin/hero', fn() => AdminSiteController::hero());
$router->post('/admin/hero', fn() => AdminSiteController::heroSave());
$router->get('/admin/flash-news', fn() => AdminSiteController::flashNews());
$router->post('/admin/flash-news', fn() => AdminSiteController::flashNewsSave());
$router->get('/admin/trades', fn() => AdminSiteController::trades());
$router->post('/admin/trades', fn() => AdminSiteController::tradeSave());
$router->get('/admin/menus', fn() => AdminSiteController::menus());
$router->post('/admin/menus', fn() => AdminSiteController::menuSave());
$router->get('/admin/footer-links', fn() => AdminSiteController::footerLinks());
$router->post('/admin/footer-links', fn() => AdminSiteController::footerLinkSave());
$router->get('/admin/faculty', fn() => AdminSiteController::faculty());
$router->post('/admin/faculty', fn() => AdminSiteController::facultySave());
$router->get('/admin/staff', fn() => AdminStaffSalaryController::index());
$router->post('/admin/staff', fn() => AdminStaffSalaryController::save());
$router->get('/admin/staff/salary', fn() => AdminStaffSalaryController::salaryForm());
$router->post('/admin/staff/salary/generate', fn() => AdminStaffSalaryController::salaryGenerate());

$router->get('/admin/finance', fn() => AdminFinanceController::reports());
$router->get('/admin/finance/print', fn() => AdminFinanceController::printReport());
$router->get('/admin/finance/export', fn() => AdminFinanceController::exportCsv());

$router->dispatch($method, $uri);
