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

function site_branding_file(string $key): string
{
    $file = trim((string) (\App\Models\SiteData::setting($key, '') ?: ''));
    if ($file !== '' && upload_exists($file)) {
        return $file;
    }
    return '';
}

function site_institute_logo_url(): string
{
    $file = site_branding_file('app_logo') ?: site_branding_file('site_favicon');
    if ($file !== '') {
        return upload_url($file);
    }
    return asset('icons/icon.svg');
}

function site_app_logo_url(): string
{
    return site_institute_logo_url();
}

function save_site_setting(string $key, string $value): void
{
    if (!\App\Core\Security::isAllowedSettingKey($key)) {
        return;
    }
    $exists = \App\Core\Database::fetch('SELECT id FROM site_settings WHERE setting_key = ?', [$key]);
    if ($exists) {
        \App\Core\Database::update('site_settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
    } else {
        \App\Core\Database::insert('site_settings', ['setting_key' => $key, 'setting_value' => $value]);
    }
}

function branding_mime_type(string $filename): string
{
    $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return match ($ext) {
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'webp' => 'image/webp',
        'ico' => 'image/x-icon',
        default => 'application/octet-stream',
    };
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

function ensure_password_reset_schema(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    try {
        \App\Core\Database::fetch('SELECT 1 FROM users LIMIT 1');
    } catch (\Throwable $e) {
        return;
    }

    if (!\App\Core\Database::fetch("SHOW COLUMNS FROM users LIKE 'password_reset_token'")) {
        \App\Core\Database::connect()->exec(
            'ALTER TABLE users
             ADD COLUMN password_reset_token VARCHAR(64) NULL AFTER is_active,
             ADD COLUMN password_reset_expires DATETIME NULL AFTER password_reset_token'
        );
    }
}

function ensure_admission_fee_schema(): void
{
    static $checked = false;
    if ($checked) {
        return;
    }
    $checked = true;

    foreach (['admissions', 'students'] as $table) {
        try {
            \App\Core\Database::fetch("SELECT 1 FROM {$table} LIMIT 1");
        } catch (\Throwable $e) {
            continue;
        }
        if (!\App\Core\Database::fetch("SHOW COLUMNS FROM {$table} LIKE 'total_admission_amount'")) {
            \App\Core\Database::connect()->exec(
                "ALTER TABLE {$table}
                 ADD COLUMN total_admission_amount DECIMAL(12,2) NULL AFTER shift,
                 ADD COLUMN advance_paid DECIMAL(12,2) DEFAULT 0 AFTER total_admission_amount"
            );
        }
    }
}

/** @return array{total_admission_amount:float,advance_paid:float,total_paid:float,balance_due:float,has_fee_plan:bool,installment_options:list<string>,next_installment:string} */
function student_admission_fee_profile(?int $admissionId, string $studentName = '', string $mobile = ''): array
{
    ensure_admission_fee_schema();
    $admission = null;
    if ($admissionId > 0) {
        $admission = \App\Core\Database::fetch(
            'SELECT id, name, father_name, mobile, trade, total_admission_amount, advance_paid FROM admissions WHERE id = ?',
            [$admissionId]
        );
    }
    if (!$admission && $studentName !== '') {
        $admission = \App\Core\Database::fetch(
            'SELECT a.id, a.name, a.father_name, a.mobile, a.trade, a.total_admission_amount, a.advance_paid
             FROM students s
             JOIN admissions a ON a.id = s.admission_id
             WHERE s.student_name = ? AND (s.mobile = ? OR ? = "" OR s.mobile IS NULL)
             LIMIT 1',
            [$studentName, $mobile, $mobile]
        );
        if ($admission) {
            $admissionId = (int) $admission['id'];
        }
    }

    $total = (float) ($admission['total_admission_amount'] ?? 0);
    $advance = (float) ($admission['advance_paid'] ?? 0);
    if ($total <= 0 && $admissionId > 0) {
        $studentRow = \App\Core\Database::fetch(
            'SELECT total_admission_amount, advance_paid FROM students WHERE admission_id = ? LIMIT 1',
            [$admissionId]
        );
        if ($studentRow) {
            $studentTotal = (float) ($studentRow['total_admission_amount'] ?? 0);
            if ($studentTotal > 0) {
                $total = $studentTotal;
                if ($advance <= 0) {
                    $advance = (float) ($studentRow['advance_paid'] ?? 0);
                }
            }
        }
    }
    $paid = 0.0;
    if ($admissionId > 0) {
        $paidRow = \App\Core\Database::fetch(
            'SELECT COALESCE(SUM(paid_amount), 0) AS paid FROM student_fees WHERE admission_id = ?',
            [$admissionId]
        );
        $paid = (float) ($paidRow['paid'] ?? 0);
    }
    $balance = max(0, round($total - $paid, 2));

    $installmentCount = 0;
    if ($admissionId > 0) {
        $installmentCount = (int) (\App\Core\Database::fetch(
            "SELECT COUNT(*) AS c FROM student_fees WHERE admission_id = ? AND fee_type LIKE 'Installment %'",
            [$admissionId]
        )['c'] ?? 0);
    }
    $nextNum = $installmentCount + 1;
    $nextInstallment = 'Installment ' . $nextNum;

    $options = [];
    if ($total > 0 && $balance > 0) {
        $options[] = $nextInstallment;
    } elseif ($total <= 0 && $admissionId > 0) {
        $options[] = $nextInstallment;
    }

    return [
        'total_admission_amount' => $total,
        'advance_paid' => $advance,
        'total_paid' => $paid,
        'balance_due' => $balance,
        'has_fee_plan' => $total > 0,
        'installment_options' => $options,
        'next_installment' => $nextInstallment,
        'admission_id' => $admissionId,
    ];
}

function validate_admission_approval_amounts(float $total, float $advance): ?string
{
    if ($total <= 0) {
        return 'Total admission amount is required to approve.';
    }
    if ($advance < 0) {
        return 'Advance paid cannot be negative.';
    }
    if ($advance > $total) {
        return 'Advance paid cannot exceed total admission amount.';
    }
    return null;
}

function record_admission_advance_fee(array $admission, int $admissionId): void
{
    $advance = round((float) ($admission['advance_paid'] ?? 0), 2);
    if ($advance <= 0) {
        return;
    }
    $exists = \App\Core\Database::fetch(
        "SELECT id FROM student_fees WHERE admission_id = ? AND fee_type = 'Advance Payment' LIMIT 1",
        [$admissionId]
    );
    if ($exists) {
        return;
    }
    $total = round((float) ($admission['total_admission_amount'] ?? 0), 2);
    \App\Core\Database::insert('student_fees', [
        'admission_id' => $admissionId,
        'student_name' => $admission['name'] ?? '',
        'father_name' => $admission['father_name'] ?? null,
        'mobile' => $admission['mobile'] ?? null,
        'trade' => $admission['trade'] ?? '',
        'fee_type' => 'Advance Payment',
        'total_amount' => $total,
        'amount' => $advance,
        'paid_amount' => $advance,
        'status' => 'Paid',
        'payment_date' => date('Y-m-d'),
        'payment_method' => $_POST['advance_payment_method'] ?? 'Cash',
        'receipt_number' => receipt_no(),
        'academic_year' => date('Y') . '-' . (date('Y') + 1),
        'notes' => 'Advance paid at admission approval',
    ]);
}
