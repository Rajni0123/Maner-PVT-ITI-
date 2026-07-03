<?php

namespace App\Core;

/**
 * Minimal Cloudflare R2 client (S3-compatible Put/Head/Delete).
 * No Composer SDK required — uses cURL + AWS Signature V4.
 */
class CloudflareR2
{
    public static function enabled(): bool
    {
        return storage_driver() === 'r2'
            && storage_config('r2_account_id') !== ''
            && storage_config('r2_access_key') !== ''
            && storage_config('r2_secret_key') !== ''
            && storage_config('r2_bucket') !== ''
            && storage_config('r2_public_url') !== '';
    }

    public static function publicUrl(string $filename): string
    {
        $base = rtrim(storage_config('r2_public_url'), '/');
        $key = self::objectKey($filename);
        return $base . '/' . ltrim($key, '/');
    }

    public static function objectKey(string $filename): string
    {
        $filename = basename(str_replace('\\', '/', $filename));
        $prefix = trim((string) storage_config('r2_prefix', 'uploads'), '/');
        return ($prefix !== '' ? $prefix . '/' : '') . $filename;
    }

    public static function putFile(string $localPath, string $filename, string $contentType = 'application/octet-stream'): bool
    {
        if (!is_file($localPath)) {
            return false;
        }
        $body = file_get_contents($localPath);
        if ($body === false) {
            return false;
        }
        return self::putObject(self::objectKey($filename), $body, $contentType);
    }

    public static function putObject(string $key, string $body, string $contentType = 'application/octet-stream'): bool
    {
        $key = ltrim($key, '/');
        $result = self::request('PUT', $key, $body, [
            'Content-Type' => $contentType,
        ]);
        return $result['ok'];
    }

    public static function exists(string $filename): bool
    {
        $result = self::request('HEAD', self::objectKey($filename));
        return $result['ok'];
    }

    public static function delete(string $filename): bool
    {
        $result = self::request('DELETE', self::objectKey($filename));
        return $result['ok'];
    }

    /** @return array{ok:bool,status:int,body:string} */
    private static function request(string $method, string $key, string $body = '', array $extraHeaders = []): array
    {
        $accountId = storage_config('r2_account_id');
        $accessKey = storage_config('r2_access_key');
        $secretKey = storage_config('r2_secret_key');
        $bucket = storage_config('r2_bucket');
        $region = storage_config('r2_region', 'auto') ?: 'auto';

        $key = ltrim(str_replace('\\', '/', $key), '/');
        $host = $accountId . '.r2.cloudflarestorage.com';

        // Path-style: /bucket/key/with/slashes
        $encodedKey = implode('/', array_map('rawurlencode', explode('/', $key)));
        $canonicalUri = '/' . rawurlencode($bucket) . '/' . $encodedKey;

        $now = gmdate('Ymd\THis\Z');
        $date = gmdate('Ymd');
        $payloadHash = hash('sha256', $body);

        $headers = array_merge([
            'host' => $host,
            'x-amz-content-sha256' => $payloadHash,
            'x-amz-date' => $now,
        ], array_change_key_case($extraHeaders, CASE_LOWER));

        if ($method === 'PUT' || $method === 'POST') {
            $headers['content-length'] = (string) strlen($body);
        }

        ksort($headers);
        $signedHeaderNames = [];
        $canonicalHeaders = '';
        foreach ($headers as $name => $value) {
            $signedHeaderNames[] = $name;
            $canonicalHeaders .= $name . ':' . trim(preg_replace('/\s+/', ' ', (string) $value)) . "\n";
        }
        $signedHeaders = implode(';', $signedHeaderNames);

        $canonicalRequest = $method . "\n"
            . $canonicalUri . "\n"
            . "\n"
            . $canonicalHeaders . "\n"
            . $signedHeaders . "\n"
            . $payloadHash;

        $credentialScope = $date . '/' . $region . '/s3/aws4_request';
        $stringToSign = "AWS4-HMAC-SHA256\n"
            . $now . "\n"
            . $credentialScope . "\n"
            . hash('sha256', $canonicalRequest);

        $signingKey = self::signingKey($secretKey, $date, $region, 's3');
        $signature = hash_hmac('sha256', $stringToSign, $signingKey);
        $authorization = 'AWS4-HMAC-SHA256 Credential=' . $accessKey . '/' . $credentialScope
            . ', SignedHeaders=' . $signedHeaders
            . ', Signature=' . $signature;

        $curlHeaders = ['Authorization: ' . $authorization];
        foreach ($headers as $name => $value) {
            if ($name === 'host') {
                continue;
            }
            $curlHeaders[] = $name . ': ' . $value;
        }

        $url = 'https://' . $host . $canonicalUri;
        $ch = curl_init($url);
        $opts = [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $curlHeaders,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER => true,
            CURLOPT_TIMEOUT => 60,
        ];
        if ($method === 'PUT' || $method === 'POST') {
            $opts[CURLOPT_POSTFIELDS] = $body;
        }
        if ($method === 'HEAD') {
            $opts[CURLOPT_NOBODY] = true;
        }
        curl_setopt_array($ch, $opts);

        $response = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $headerSize = (int) curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            error_log('R2 request failed: ' . $error);
            return ['ok' => false, 'status' => 0, 'body' => $error];
        }

        $respBody = substr((string) $response, $headerSize);
        $ok = $status >= 200 && $status < 300;
        if (!$ok) {
            error_log('R2 ' . $method . ' ' . $key . ' status=' . $status . ' body=' . substr((string) $respBody, 0, 300));
        }

        return ['ok' => $ok, 'status' => $status, 'body' => (string) $respBody];
    }

    private static function signingKey(string $secret, string $date, string $region, string $service): string
    {
        $kDate = hash_hmac('sha256', $date, 'AWS4' . $secret, true);
        $kRegion = hash_hmac('sha256', $region, $kDate, true);
        $kService = hash_hmac('sha256', $service, $kRegion, true);
        return hash_hmac('sha256', 'aws4_request', $kService, true);
    }
}
