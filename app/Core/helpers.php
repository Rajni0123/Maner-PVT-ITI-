<?php

function config(string $key, $default = null)
{
    static $cfg;
    if ($cfg === null) {
        $cfg = require dirname(__DIR__, 2) . '/config.php';
    }
    return $cfg[$key] ?? $default;
}

function base_path(string $path = ''): string
{
    $root = dirname(__DIR__, 2);
    return $path ? $root . '/' . ltrim($path, '/') : $root;
}

/** Subfolder path when site is not at domain root, e.g. /maner-iti-php */
function app_base_path(): string
{
    static $base = null;
    if ($base !== null) {
        return $base;
    }
    $configured = config('base_path', '');
    if ($configured !== '') {
        $base = '/' . trim($configured, '/');
        return $base === '/' ? '' : $base;
    }
    $script = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
    if ($script === '/' || $script === '.' || $script === '') {
        $base = '';
    } else {
        $base = rtrim($script, '/');
    }
    return $base;
}

/** Current request path without subfolder prefix */
function request_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $base = app_base_path();
    if ($base && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base)) ?: '/';
    }
    if (str_ends_with($uri, '/index.php')) {
        $uri = substr($uri, 0, -10) ?: '/';
    }
    $uri = '/' . trim($uri, '/');
    return $uri === '/' ? '/' : rtrim($uri, '/');
}

function site_url(string $path = ''): string
{
    $configured = config('site_url');
    if ($configured) {
        return rtrim($configured, '/') . '/' . ltrim($path, '/');
    }
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $base = app_base_path();
    $url = $scheme . '://' . $host . $base;
    if ($path !== '') {
        $url .= '/' . ltrim($path, '/');
    }
    return $url;
}

function asset(string $path): string
{
    return site_url('assets/' . ltrim($path, '/'));
}

function menu_url(string $url): string
{
    if ($url === '' || $url === '#') {
        return '#';
    }
    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }
    return site_url(ltrim($url, '/'));
}

function nav_key_from_menu_url(string $url): string
{
    $path = ltrim(parse_url($url, PHP_URL_PATH) ?: $url, '/');
    $base = trim(parse_url(site_url(), PHP_URL_PATH) ?: '', '/');
    if ($base !== '' && str_starts_with($path, $base)) {
        $path = ltrim(substr($path, strlen($base)), '/');
    }
    if ($path === '' || $path === '/') {
        return 'home';
    }
    if (str_starts_with($path, 'trades')) {
        return 'courses';
    }
    if (str_contains($path, 'admission')) {
        return 'admission';
    }
    if (str_contains($path, 'bscc')) {
        return 'bscc';
    }
    if (str_starts_with($path, 'contact')) {
        return 'contact';
    }
    if (str_starts_with($path, 'about')) {
        return 'about';
    }
    if (str_starts_with($path, 'notices') || str_contains($path, 'news')) {
        return 'news';
    }
    return '';
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function uppercase_admission_value(?string $value): string
{
    return mb_strtoupper(trim((string) $value), 'UTF-8');
}

function admission_uppercase_fields(): array
{
    return [
        'name', 'father_name', 'mother_name', 'email', 'gender', 'category',
        'village_town_city', 'nearby', 'police_station', 'post_office', 'district',
        'block', 'state', 'pwd_category', 'pwd_claim', 'trade', 'session', 'shift',
        'class_10th_school', 'class_10th_subject', 'class_12th_school', 'class_12th_subject',
        'student_credit_card', 'student_credit_card_bank', 'registration_type', 'qualification',
    ];
}

function normalize_admission_fields(array $data): array
{
    foreach (admission_uppercase_fields() as $field) {
        if (!isset($data[$field]) || !is_string($data[$field])) {
            continue;
        }
        if (trim($data[$field]) === '') {
            continue;
        }
        $data[$field] = uppercase_admission_value($data[$field]);
    }
    return $data;
}

function admission_display_value(?string $value): string
{
    $display = trim((string) ($value ?? ''));
    if ($display === '') {
        return '—';
    }
    return uppercase_admission_value($display);
}

function admission_display_value_preserve(?string $value): string
{
    $display = trim((string) ($value ?? ''));
    return $display === '' ? '—' : $display;
}

function institute_tagline(?array $header = null): string
{
    $header = $header ?? \App\Models\SiteData::header();
    $tagline = trim((string) ($header['tagline'] ?? ''));
    $legacyTaglines = [
        'Skill India | Digital India',
        'Premier Vocational Training Institute',
        'Premier Vocational Institute',
    ];
    if ($tagline === '' || in_array($tagline, $legacyTaglines, true)) {
        return 'Industrial Training Institute (ITI)';
    }
    return $tagline;
}

function upload_dir_path(): string
{
    $dir = (string) config('upload_dir', '');
    if ($dir !== '' && is_dir($dir)) {
        return rtrim(str_replace('\\', '/', $dir), '/');
    }
    return base_path('uploads');
}

function upload_legacy_dirs(): array
{
    $dirs = [];
    $configured = (string) config('upload_legacy_dir', '');
    if ($configured !== '' && is_dir($configured)) {
        $dirs[] = rtrim(str_replace('\\', '/', $configured), '/');
    }
    return $dirs;
}

function upload_resolve_path(?string $filename): ?string
{
    if (!$filename) {
        return null;
    }
    $filename = basename(str_replace('\\', '/', $filename));
    $primary = upload_dir_path() . '/' . $filename;
    if (is_file($primary)) {
        return $primary;
    }
    foreach (upload_legacy_dirs() as $legacy) {
        $path = $legacy . '/' . $filename;
        if (is_file($path)) {
            return $path;
        }
    }
    return null;
}

function upload_sync_file(?string $filename): bool
{
    $resolved = upload_resolve_path($filename);
    if (!$resolved) {
        return false;
    }
    $filename = basename(str_replace('\\', '/', (string) $filename));
    $dest = upload_dir_path() . '/' . $filename;
    if (is_file($dest)) {
        return true;
    }
    $dir = upload_dir_path();
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    return copy($resolved, $dest);
}

function upload_url(?string $filename): string
{
    if (!$filename) {
        return '';
    }
    upload_sync_file($filename);
    return site_url('uploads/' . basename(str_replace('\\', '/', $filename)));
}

function upload_exists(?string $filename): bool
{
    return upload_resolve_path($filename) !== null;
}

function normalize_uidai(?string $value): string
{
    return preg_replace('/\D/', '', (string) $value);
}

function format_uidai(?string $value): string
{
    $digits = normalize_uidai($value);
    if (strlen($digits) !== 12) {
        return trim((string) $value);
    }
    return substr($digits, 0, 4) . ' ' . substr($digits, 4, 4) . ' ' . substr($digits, 8, 4);
}

function format_mobile(?string $value): string
{
    $digits = preg_replace('/\D/', '', (string) $value);
    if (strlen($digits) === 12 && str_starts_with($digits, '91')) {
        $digits = substr($digits, 2);
    }
    if (strlen($digits) === 11 && $digits[0] === '0') {
        $digits = substr($digits, 1);
    }
    if (strlen($digits) === 10) {
        return '+91 ' . $digits;
    }
    $trimmed = trim((string) $value);
    return $trimmed;
}

function education_percentage(?string $obtained, ?string $total, ?string $stored = null): string
{
    if ($stored !== null && $stored !== '') {
        $pct = (float) $stored;
        return rtrim(rtrim(number_format($pct, 2, '.', ''), '0'), '.') . '%';
    }
    $o = (float) $obtained;
    $t = (float) $total;
    if ($o > 0 && $t > 0) {
        return rtrim(rtrim(number_format(($o / $t) * 100, 2, '.', ''), '0'), '.') . '%';
    }
    return '—';
}

function calc_percentage_value(?string $obtained, ?string $total): ?string
{
    $o = (float) $obtained;
    $t = (float) $total;
    if ($o > 0 && $t > 0) {
        return rtrim(rtrim(number_format(($o / $t) * 100, 2, '.', ''), '0'), '.');
    }
    return null;
}

function format_dob(?string $value): string
{
    if (!$value) {
        return '—';
    }
    $ts = strtotime($value);
    return $ts ? date('d-m-Y', $ts) : e($value);
}

function installed_lock_path(): string
{
    $lock = (string) config('installed_lock', '');
    return $lock !== '' ? $lock : base_path('storage/installed.lock');
}

function redirect(string $path): void
{
    header('Location: ' . site_url($path));
    exit;
}

function old(string $key, $default = '')
{
    return $_SESSION['_old'][$key] ?? $default;
}

function flash(string $key, $value = null)
{
    if ($value !== null) {
        $_SESSION['_flash'][$key] = $value;
        return null;
    }
    $v = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);
    return $v;
}

function set_old(array $data): void
{
    $_SESSION['_old'] = $data;
}

function clear_old(): void
{
    unset($_SESSION['_old']);
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf'])) {
        $_SESSION['_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['_csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_csrf" value="' . e(csrf_token()) . '">';
}

function verify_csrf(): void
{
    $token = $_POST['_csrf'] ?? '';
    if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
        http_response_code(403);
        die('Invalid security token. Please go back and try again.');
    }
}

function app_id(int $id, ?string $createdAt = null, ?string $session = null): string
{
    $year = (int) date('Y');

    if ($session && preg_match('/^(\d{4})/', trim($session), $m)) {
        $year = (int) $m[1];
    } elseif ($createdAt) {
        $ts = strtotime($createdAt);
        if ($ts) {
            $year = (int) date('Y', $ts);
        }
    }

    return 'ITI-' . $year . '-' . str_pad((string) $id, 4, '0', STR_PAD_LEFT);
}

function parse_app_id(string $code): ?int
{
    if (preg_match('/ITI-\d{4}-(\d+)/', $code, $m)) {
        return (int) $m[1];
    }
    return is_numeric($code) ? (int) $code : null;
}

function format_date(?string $date): string
{
    if (!$date) {
        return '—';
    }
    $ts = strtotime($date);
    return $ts ? date('d M Y', $ts) : e($date);
}

function receipt_no(string $prefix = 'RCP'): string
{
    return $prefix . date('ym') . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
}

function salary_slip_no(): string
{
    return 'SAL-' . date('Ym') . '-' . str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
}

function format_inr($amount): string
{
    return '₹ ' . number_format((float) $amount, 2);
}

function month_name(int $month): string
{
    static $names = [
        1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
        5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
        9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December',
    ];
    return $names[$month] ?? (string) $month;
}

function salary_amount(float $value): float
{
    return round(max(0, $value), 2);
}

function str_limit(?string $value, int $limit = 80, string $suffix = '...'): string
{
    $text = (string) ($value ?? '');
    if ($text === '') {
        return '';
    }
    if (function_exists('mb_strimwidth')) {
        return mb_strimwidth($text, 0, $limit, $suffix);
    }
    return strlen($text) > $limit ? substr($text, 0, $limit) . $suffix : $text;
}

function ensure_contact_schema(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    try {
        \App\Core\Database::fetch('SELECT 1 FROM contact LIMIT 1');
    } catch (\Throwable $e) {
        \App\Core\Database::connect()->exec(
            "CREATE TABLE IF NOT EXISTS contact (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(191) NOT NULL,
                email VARCHAR(191) NOT NULL,
                phone VARCHAR(50) NOT NULL,
                message TEXT NOT NULL,
                inquiry_type VARCHAR(100) DEFAULT 'Admission Inquiry',
                trade_interest VARCHAR(100) NULL,
                is_read TINYINT(1) DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
        return;
    }

    $hasType = \App\Core\Database::fetch("SHOW COLUMNS FROM contact LIKE 'inquiry_type'");
    if (!$hasType) {
        \App\Core\Database::connect()->exec(
            "ALTER TABLE contact
             ADD COLUMN inquiry_type VARCHAR(100) DEFAULT 'Admission Inquiry' AFTER message,
             ADD COLUMN trade_interest VARCHAR(100) NULL AFTER inquiry_type"
        );
    }

    $hasRead = \App\Core\Database::fetch("SHOW COLUMNS FROM contact LIKE 'is_read'");
    if (!$hasRead) {
        \App\Core\Database::connect()->exec(
            'ALTER TABLE contact ADD COLUMN is_read TINYINT(1) DEFAULT 0 AFTER trade_interest'
        );
    }
}

function json_decode_safe(?string $json, $default = [])
{
    if (!$json) {
        return $default;
    }
    $data = json_decode($json, true);
    return is_array($data) ? $data : $default;
}

function is_installed(): bool
{
    return is_file(installed_lock_path());
}

function ensure_newsletter_schema(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    try {
        \App\Core\Database::fetch('SELECT 1 FROM newsletter_subscribers LIMIT 1');
    } catch (\Throwable $e) {
        \App\Core\Database::connect()->exec(
            "CREATE TABLE IF NOT EXISTS newsletter_subscribers (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(191) NOT NULL,
                is_active TINYINT(1) DEFAULT 1,
                ip_address VARCHAR(45) NULL,
                source VARCHAR(50) DEFAULT 'footer',
                subscribed_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                unsubscribed_at DATETIME NULL,
                UNIQUE KEY uniq_newsletter_email (email)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"
        );
    }
}

function newsletter_enabled(): bool
{
    return (\App\Models\SiteData::setting('newsletter_enabled', '1')) === '1';
}

function ensure_news_menu_removed(): void
{
    static $checked = false;
    if ($checked || !is_installed()) {
        return;
    }
    $checked = true;

    try {
        $rows = \App\Core\Database::fetchAll(
            "SELECT id FROM menus WHERE (LOWER(title) LIKE '%latest news%' OR LOWER(url) IN ('notices', '/notices')) AND (parent_id IS NULL OR parent_id = 0)"
        );
        foreach ($rows as $row) {
            \App\Core\Database::update('menus', ['is_active' => 0], 'id = ?', [(int) $row['id']]);
        }
    } catch (\Throwable $e) {
        // menus table may not exist yet
    }
}

function ensure_important_links_menu(): void
{
    static $checked = false;
    if ($checked || !is_installed()) {
        return;
    }
    $checked = true;

    ensure_news_menu_removed();

    try {
        $existing = \App\Core\Database::fetch(
            "SELECT id FROM menus WHERE LOWER(title) = 'important links' AND (parent_id IS NULL OR parent_id = 0) LIMIT 1"
        );

        if ($existing) {
            $parentId = (int) $existing['id'];
        } else {
            $parentId = (int) \App\Core\Database::insert('menus', [
                'title' => 'Important Links',
                'url' => '#',
                'parent_id' => null,
                'order_index' => 50,
                'is_active' => 1,
            ]);
        }

        $children = [
            ['NCVT MIS', 'https://ncvtmis.gov.in', 1],
            ['Bharat Skill', 'https://bharatskills.gov.in', 2],
            ['DET Hunnar', 'https://dethunar-bih.com/', 3],
            ['ITI Higher Level Exam', 'https://ncvtmis.gov.in', 4],
            ['Post Matric Scholarship', 'https://scholarships.gov.in', 5],
            ['National Scholarship Portal', 'https://scholarships.gov.in', 6],
        ];

        foreach ($children as [$title, $url, $order]) {
            $found = \App\Core\Database::fetch(
                'SELECT id FROM menus WHERE parent_id = ? AND LOWER(title) = LOWER(?) LIMIT 1',
                [$parentId, $title]
            );
            if ($found) {
                \App\Core\Database::update('menus', [
                    'url' => $url,
                    'order_index' => $order,
                    'is_active' => 1,
                ], 'id = ?', [(int) $found['id']]);
            } else {
                \App\Core\Database::insert('menus', [
                    'title' => $title,
                    'url' => $url,
                    'parent_id' => $parentId,
                    'order_index' => $order,
                    'is_active' => 1,
                ]);
            }
        }
    } catch (\Throwable $e) {
        // menus table may not exist yet during install
    }
}
