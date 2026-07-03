<?php

namespace App\Core;

class Sms
{
    public static function isConfigured(): bool
    {
        $settings = \App\Models\SiteData::settings();
        if (($settings['sms_enabled'] ?? '0') !== '1') {
            return false;
        }
        $provider = $settings['sms_provider'] ?? '';
        $apiKey = trim((string) ($settings['sms_api_key'] ?? ''));
        if ($provider === 'custom') {
            return trim((string) ($settings['sms_custom_url'] ?? '')) !== '';
        }
        return $provider !== '' && $apiKey !== '';
    }

    public static function statusLabel(): string
    {
        if (!self::isConfigured()) {
            return 'Not configured';
        }
        $provider = \App\Models\SiteData::setting('sms_provider', '');
        $labels = [
            'fast2sms' => 'Fast2SMS (active)',
            'msg91' => 'MSG91 (active)',
            'textlocal' => 'TextLocal (active)',
            'custom' => 'Custom API (active)',
        ];
        return $labels[$provider] ?? 'Active';
    }

    /**
     * Send SMS to one Indian mobile number.
     * @return array{ok:bool,error?:string}
     */
    public static function send(string $mobile, string $message): array
    {
        $mobile = self::normalizeMobile($mobile);
        $message = trim($message);
        if ($mobile === '') {
            return ['ok' => false, 'error' => 'Invalid mobile number'];
        }
        if ($message === '') {
            return ['ok' => false, 'error' => 'Empty message'];
        }
        if (!self::isConfigured()) {
            return ['ok' => false, 'error' => 'SMS gateway is not configured'];
        }

        $settings = \App\Models\SiteData::settings();
        $provider = $settings['sms_provider'] ?? '';

        return match ($provider) {
            'fast2sms' => self::sendFast2Sms($mobile, $message, $settings),
            'msg91' => self::sendMsg91($mobile, $message, $settings),
            'textlocal' => self::sendTextLocal($mobile, $message, $settings),
            'custom' => self::sendCustom($mobile, $message, $settings),
            default => ['ok' => false, 'error' => 'Unknown SMS provider'],
        };
    }

    public static function normalizeMobile(string $mobile): string
    {
        $digits = preg_replace('/\D+/', '', $mobile) ?? '';
        if (strlen($digits) > 10 && str_starts_with($digits, '91')) {
            $digits = substr($digits, -10);
        }
        if (strlen($digits) !== 10 || !preg_match('/^[6-9]/', $digits)) {
            return '';
        }
        return $digits;
    }

    public static function renderTemplate(string $template, array $vars): string
    {
        $map = [];
        foreach ($vars as $key => $value) {
            $map['{' . $key . '}'] = (string) $value;
        }
        return strtr($template, $map);
    }

    /** @param array<string,string> $settings */
    private static function sendFast2Sms(string $mobile, string $message, array $settings): array
    {
        $apiKey = trim((string) ($settings['sms_api_key'] ?? ''));
        $route = ($settings['sms_route'] ?? 'q') === 'dlt' ? 'dlt' : 'q';
        $senderId = trim((string) ($settings['sms_sender_id'] ?? ''));
        $templateId = trim((string) ($settings['sms_dlt_template_id'] ?? ''));

        $payload = [
            'route' => $route,
            'numbers' => $mobile,
            'flash' => 0,
        ];

        if ($route === 'dlt') {
            if ($templateId === '' || $senderId === '') {
                return ['ok' => false, 'error' => 'DLT Sender ID and Template ID required for Fast2SMS DLT route'];
            }
            $payload['sender_id'] = $senderId;
            $payload['message'] = $templateId;
            // Variables pipe-separated: name|due|trade|institute|phone
            $payload['variables_values'] = $message;
        } else {
            $payload['message'] = $message;
            $payload['language'] = 'english';
        }

        return self::httpJson(
            'https://www.fast2sms.com/dev/bulkV2',
            $payload,
            ['authorization: ' . $apiKey, 'Content-Type: application/json']
        );
    }

    /** @param array<string,string> $settings */
    private static function sendMsg91(string $mobile, string $message, array $settings): array
    {
        $apiKey = trim((string) ($settings['sms_api_key'] ?? ''));
        $senderId = trim((string) ($settings['sms_sender_id'] ?? 'MANERI'));
        $country = trim((string) ($settings['sms_country_code'] ?? '91'));
        $route = trim((string) ($settings['sms_route'] ?? '4'));

        $query = http_build_query([
            'authkey' => $apiKey,
            'mobiles' => $country . $mobile,
            'message' => $message,
            'sender' => $senderId !== '' ? $senderId : 'MANERI',
            'route' => $route !== '' ? $route : '4',
            'country' => $country,
        ]);

        return self::httpGet('https://api.msg91.com/api/sendhttp.php?' . $query);
    }

    /** @param array<string,string> $settings */
    private static function sendTextLocal(string $mobile, string $message, array $settings): array
    {
        $apiKey = trim((string) ($settings['sms_api_key'] ?? ''));
        $senderId = trim((string) ($settings['sms_sender_id'] ?? 'TXTLCL'));
        $country = trim((string) ($settings['sms_country_code'] ?? '91'));

        return self::httpForm('https://api.textlocal.in/send/', [
            'apikey' => $apiKey,
            'numbers' => $country . $mobile,
            'message' => $message,
            'sender' => $senderId !== '' ? $senderId : 'TXTLCL',
        ]);
    }

    /** @param array<string,string> $settings */
    private static function sendCustom(string $mobile, string $message, array $settings): array
    {
        $url = trim((string) ($settings['sms_custom_url'] ?? ''));
        $method = strtoupper(trim((string) ($settings['sms_custom_method'] ?? 'GET')));
        $apiKey = trim((string) ($settings['sms_api_key'] ?? ''));
        $senderId = trim((string) ($settings['sms_sender_id'] ?? ''));
        $country = trim((string) ($settings['sms_country_code'] ?? '91'));

        $replacements = [
            '{mobile}' => $mobile,
            '{mobile91}' => $country . $mobile,
            '{message}' => $message,
            '{message_encoded}' => rawurlencode($message),
            '{api_key}' => $apiKey,
            '{sender_id}' => $senderId,
        ];
        $url = strtr($url, $replacements);

        if ($method === 'POST') {
            return self::httpForm($url, [
                'mobile' => $mobile,
                'mobile91' => $country . $mobile,
                'message' => $message,
                'api_key' => $apiKey,
                'sender_id' => $senderId,
            ]);
        }

        return self::httpGet($url);
    }

    /** @param array<string,mixed> $payload @param list<string> $headers */
    private static function httpJson(string $url, array $payload, array $headers = []): array
    {
        return self::request($url, json_encode($payload, JSON_UNESCAPED_UNICODE), array_merge([
            'Content-Type: application/json',
            'Accept: application/json',
        ], $headers));
    }

    /** @param array<string,string> $fields */
    private static function httpForm(string $url, array $fields): array
    {
        return self::request($url, http_build_query($fields), [
            'Content-Type: application/x-www-form-urlencoded',
            'Accept: application/json',
        ]);
    }

    private static function httpGet(string $url): array
    {
        return self::request($url, null, ['Accept: application/json']);
    }

    private static function request(string $url, ?string $body, array $headers): array
    {
        if (!function_exists('curl_init')) {
            return ['ok' => false, 'error' => 'PHP cURL extension is required for SMS'];
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_SSL_VERIFYPEER => true,
        ]);
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        }

        $response = curl_exec($ch);
        $errno = curl_errno($ch);
        $error = curl_error($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($errno) {
            return ['ok' => false, 'error' => 'SMS request failed: ' . $error];
        }

        $text = is_string($response) ? trim($response) : '';
        $json = json_decode($text, true);

        if (is_array($json)) {
            if (!empty($json['return']) || !empty($json['success']) || ($json['status'] ?? '') === 'success') {
                return ['ok' => true];
            }
            if (isset($json['message']) && is_string($json['message']) && stripos($json['message'], 'success') !== false) {
                return ['ok' => true];
            }
            $err = $json['message'] ?? $json['error'] ?? $json['description'] ?? null;
            if (is_array($err)) {
                $err = implode(', ', $err);
            }
            if ($status >= 200 && $status < 300 && $err === null) {
                return ['ok' => true];
            }
            return ['ok' => false, 'error' => (string) ($err ?: ('SMS API error (HTTP ' . $status . ')'))];
        }

        // MSG91 sendhttp often returns a request id string on success
        if ($status >= 200 && $status < 300 && $text !== '' && !preg_match('/error|fail|invalid/i', $text)) {
            return ['ok' => true];
        }

        return ['ok' => false, 'error' => $text !== '' ? $text : ('SMS API HTTP ' . $status)];
    }
}
