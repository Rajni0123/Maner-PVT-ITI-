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

/** Short display label: 2026-28 → 26-28 */
function session_short_label(?string $session): string
{
    $session = trim((string) $session);
    if ($session === '') {
        return '';
    }
    if (preg_match('/^(\d{4})-(\d{2})$/', $session, $m)) {
        return substr($m[1], 2) . '-' . $m[2];
    }
    return $session;
}

/** Resolve academic session filter from query string or persisted admin choice. */
function admin_resolve_session_filter(): string
{
    if (array_key_exists('session', $_GET)) {
        $session = trim((string) $_GET['session']);
        if ($session !== '') {
            $_SESSION['admin_academic_session'] = $session;
        } else {
            unset($_SESSION['admin_academic_session']);
        }
        return $session;
    }
    return trim((string) ($_SESSION['admin_academic_session'] ?? ''));
}

/** @return list<string> */
function academic_session_options(): array
{
    $names = [];
    try {
        foreach (\App\Core\Database::fetchAll('SELECT session_name FROM sessions ORDER BY start_year DESC') as $row) {
            $name = trim((string) ($row['session_name'] ?? ''));
            if ($name !== '') {
                $names[$name] = $name;
            }
        }
    } catch (\Throwable $e) {
    }
    foreach (['students', 'admissions'] as $table) {
        try {
            foreach (\App\Core\Database::fetchAll(
                "SELECT DISTINCT session FROM {$table} WHERE session IS NOT NULL AND session != '' ORDER BY session DESC"
            ) as $row) {
                $name = trim((string) ($row['session'] ?? ''));
                if ($name !== '') {
                    $names[$name] = $name;
                }
            }
        } catch (\Throwable $e) {
        }
    }
    return array_values($names);
}

/** @return array<string, array{students:int, admissions:int, pending:int}> */
function academic_session_stats(): array
{
    $stats = [];
    foreach (academic_session_options() as $sessionName) {
        $stats[$sessionName] = ['students' => 0, 'admissions' => 0, 'pending' => 0];
    }
    try {
        foreach (\App\Core\Database::fetchAll(
            'SELECT session, COUNT(*) AS c FROM students WHERE session IS NOT NULL AND session != "" GROUP BY session'
        ) as $row) {
            $sessionName = trim((string) ($row['session'] ?? ''));
            if ($sessionName === '') {
                continue;
            }
            if (!isset($stats[$sessionName])) {
                $stats[$sessionName] = ['students' => 0, 'admissions' => 0, 'pending' => 0];
            }
            $stats[$sessionName]['students'] = (int) ($row['c'] ?? 0);
        }
        foreach (\App\Core\Database::fetchAll(
            'SELECT session, status, COUNT(*) AS c FROM admissions WHERE session IS NOT NULL AND session != "" GROUP BY session, status'
        ) as $row) {
            $sessionName = trim((string) ($row['session'] ?? ''));
            if ($sessionName === '') {
                continue;
            }
            if (!isset($stats[$sessionName])) {
                $stats[$sessionName] = ['students' => 0, 'admissions' => 0, 'pending' => 0];
            }
            $stats[$sessionName]['admissions'] += (int) ($row['c'] ?? 0);
            if (strtolower((string) ($row['status'] ?? '')) === 'pending') {
                $stats[$sessionName]['pending'] += (int) ($row['c'] ?? 0);
            }
        }
    } catch (\Throwable $e) {
    }
    return $stats;
}

/** Build query string preserving filters; pass empty string for session to clear filter. */
function admin_session_query(string $baseUrl, ?string $session = null, array $extra = []): string
{
    $params = $extra;
    if ($session !== null) {
        $params['session'] = $session;
    }
    $filtered = [];
    foreach ($params as $key => $value) {
        if ($key === 'session') {
            $filtered[$key] = $value;
            continue;
        }
        if ($value !== '' && $value !== null) {
            $filtered[$key] = $value;
        }
    }
    $qs = http_build_query($filtered);
    return site_url($baseUrl . ($qs !== '' ? '?' . $qs : ''));
}

/** @return list<array<string,mixed>> */
function academic_session_fees(string $session, int $limit = 0, string $order = 'name'): array
{
    $orderSql = $order === 'recent' ? 'created_at DESC' : 'student_name ASC, created_at DESC';
    $limitSql = $limit > 0 ? ' LIMIT ' . (int) $limit : '';
    return \App\Core\Database::fetchAll(
        'SELECT * FROM (
            SELECT f.*,
                COALESCE(
                    (SELECT s.session FROM students s
                     WHERE f.admission_id IS NOT NULL AND s.admission_id = f.admission_id
                     LIMIT 1),
                    (SELECT a.session FROM admissions a
                     WHERE a.id = f.admission_id
                     LIMIT 1),
                    (SELECT s2.session FROM students s2
                     WHERE s2.student_name = f.student_name
                       AND (f.mobile IS NULL OR f.mobile = "" OR s2.mobile = f.mobile)
                     LIMIT 1)
                ) AS fee_session
            FROM student_fees f
        ) t
        WHERE fee_session = ?
        ORDER BY ' . $orderSql . $limitSql,
        [$session]
    );
}

/** @return array{total:float, paid:float, cnt:int} */
function academic_session_fee_totals(string $session): array
{
    $total = 0.0;
    $paid = 0.0;
    $cnt = 0;
    foreach (academic_session_fees($session) as $fee) {
        $total += (float) ($fee['amount'] ?? 0);
        $paid += (float) ($fee['paid_amount'] ?? 0);
        $cnt++;
    }
    return ['total' => $total, 'paid' => $paid, 'cnt' => $cnt];
}
