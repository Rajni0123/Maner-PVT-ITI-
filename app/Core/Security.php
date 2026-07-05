<?php

namespace App\Core;

class Security
{
    /** Idle timeout: logout after 30 minutes of inactivity */
    public const SESSION_IDLE_SECONDS = 1800;

    /** Absolute session lifetime: force re-login after 8 hours */
    public const SESSION_MAX_SECONDS = 28800;

    public static function sendHeaders(): void
    {
        if (headers_sent()) {
            return;
        }

        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
        header('Cross-Origin-Opener-Policy: same-origin');
        header_remove('X-Powered-By');

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['SERVER_PORT'] ?? '') === '443')
            || (strtolower((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https');

        if ($isHttps) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
        }

        // CSP: allow required CDNs used by the site, block inline-eval
        header(
            "Content-Security-Policy: default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' https://cdn.tailwindcss.com; "
            . "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; "
            . "font-src 'self' https://fonts.gstatic.com data:; "
            . "img-src 'self' data: https: blob:; "
            . "connect-src 'self'; "
            . "frame-ancestors 'self'; "
            . "base-uri 'self'; "
            . "form-action 'self'; "
            . "object-src 'none'"
        );
    }

    public static function clientIp(): string
    {
        $ip = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        // Only trust direct remote addr (shared hosts often set spoofable X-Forwarded-For)
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }
        return '0.0.0.0';
    }

    /**
     * File-based rate limiter (works across sessions / cookie clears).
     * @return array{allowed:bool,retry_after:int}
     */
    public static function rateLimit(string $bucket, int $maxAttempts, int $windowSeconds, bool $hit = false): array
    {
        $dir = base_path('storage/rate_limits');
        if (!is_dir($dir)) {
            @mkdir($dir, 0750, true);
        }

        $ip = self::clientIp();
        $key = hash('sha256', $bucket . '|' . $ip);
        $file = $dir . '/' . $key . '.json';
        $now = time();
        $data = ['count' => 0, 'start' => $now];

        if (is_file($file)) {
            $raw = @file_get_contents($file);
            $parsed = is_string($raw) ? json_decode($raw, true) : null;
            if (is_array($parsed) && isset($parsed['count'], $parsed['start'])) {
                $data = [
                    'count' => (int) $parsed['count'],
                    'start' => (int) $parsed['start'],
                ];
            }
        }

        if (($now - $data['start']) >= $windowSeconds) {
            $data = ['count' => 0, 'start' => $now];
        }

        if ($data['count'] >= $maxAttempts) {
            $retry = max(1, $windowSeconds - ($now - $data['start']));
            return ['allowed' => false, 'retry_after' => $retry];
        }

        if ($hit) {
            $data['count']++;
            @file_put_contents($file, json_encode($data), LOCK_EX);
        }

        return ['allowed' => true, 'retry_after' => 0];
    }

    /** Record a failed attempt against the rate limit bucket. */
    public static function rateLimitHit(string $bucket, int $maxAttempts, int $windowSeconds): array
    {
        return self::rateLimit($bucket, $maxAttempts, $windowSeconds, true);
    }

    public static function clearRateLimit(string $bucket): void
    {
        $dir = base_path('storage/rate_limits');
        $key = hash('sha256', $bucket . '|' . self::clientIp());
        $file = $dir . '/' . $key . '.json';
        if (is_file($file)) {
            @unlink($file);
        }
    }

    public static function enforceSessionTimeout(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $now = time();
        if (!isset($_SESSION['_auth_started'])) {
            $_SESSION['_auth_started'] = $now;
        }
        if (!isset($_SESSION['_last_activity'])) {
            $_SESSION['_last_activity'] = $now;
        }

        $idle = $now - (int) $_SESSION['_last_activity'];
        $absolute = $now - (int) $_SESSION['_auth_started'];

        if ($idle > self::SESSION_IDLE_SECONDS || $absolute > self::SESSION_MAX_SECONDS) {
            $_SESSION = [];
            if (ini_get('session.use_cookies')) {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool) $params['secure'], (bool) $params['httponly']);
            }
            session_destroy();
            session_start();
            $_SESSION['_auth_started'] = time();
            $_SESSION['_last_activity'] = time();
            return;
        }

        $_SESSION['_last_activity'] = $now;
    }

    public static function isSafeIdentifier(string $name): bool
    {
        return (bool) preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $name);
    }

    public static function assertSafeIdentifier(string $name, string $label = 'identifier'): void
    {
        if (!self::isSafeIdentifier($name)) {
            throw new \InvalidArgumentException('Invalid ' . $label);
        }
    }

    public static function validatePasswordStrength(string $password): ?string
    {
        if (strlen($password) < 8) {
            return 'Password must be at least 8 characters.';
        }
        if (!preg_match('/[A-Za-z]/', $password) || !preg_match('/[0-9]/', $password)) {
            return 'Password must include letters and numbers.';
        }
        return null;
    }

    /** @return list<string> */
    public static function allowedSettingKeys(): array
    {
        return [
            'seo_title', 'seo_description', 'header_text', 'principal_name', 'principal_message', 'mis_code',
            'newsletter_enabled', 'newsletter_title', 'newsletter_placeholder',
            'storage_driver', 'r2_account_id', 'r2_access_key', 'r2_secret_key', 'r2_bucket',
            'r2_public_url', 'r2_prefix', 'r2_delete_local',
            'site_favicon', 'app_logo',
            'fee_structure_pdf', 'fee_structure_json',
            'fee_bank_name', 'fee_bank_address', 'fee_bank_holder', 'fee_bank_account', 'fee_bank_ifsc',
            'mail_from', 'mail_from_name',
            'fee_reminder_subject', 'fee_reminder_message', 'fee_reminder_sms_message',
            'student_notify_subject', 'student_notify_email_body', 'student_notify_sms_body',
            'sms_enabled', 'sms_provider', 'sms_api_key', 'sms_sender_id', 'sms_route',
            'sms_dlt_template_id', 'sms_country_code', 'sms_custom_url', 'sms_custom_method',
        ];
    }

    public static function isAllowedSettingKey(string $key): bool
    {
        return in_array($key, self::allowedSettingKeys(), true) && self::isSafeIdentifier($key);
    }
}
