<?php

namespace App\Core;

class Mail
{
    public static function send(string $to, string $subject, string $htmlBody, string $textBody = ''): bool
    {
        $to = trim($to);
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        $fromEmail = (string) (\App\Models\SiteData::setting('mail_from', '') ?: '');
        if ($fromEmail === '' || !filter_var($fromEmail, FILTER_VALIDATE_EMAIL)) {
            $header = \App\Models\SiteData::header();
            $fromEmail = (string) ($header['email'] ?? 'noreply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost'));
        }
        $fromName = (string) (\App\Models\SiteData::setting('mail_from_name', '') ?: config('site_name', 'Maner Private ITI'));

        if ($textBody === '') {
            $textBody = trim(strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $htmlBody)));
        }

        $boundary = 'bnd_' . bin2hex(random_bytes(8));
        $headers = [
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
            'From: ' . self::encodeAddress($fromName, $fromEmail),
            'Reply-To: ' . $fromEmail,
            'X-Mailer: Maner-ITI-CMS',
        ];

        $body = "--{$boundary}\r\n";
        $body .= "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $textBody . "\r\n\r\n";
        $body .= "--{$boundary}\r\n";
        $body .= "Content-Type: text/html; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $htmlBody . "\r\n\r\n";
        $body .= "--{$boundary}--";

        $encodedSubject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

        return @mail($to, $encodedSubject, $body, implode("\r\n", $headers));
    }

    private static function encodeAddress(string $name, string $email): string
    {
        $name = trim($name);
        if ($name === '') {
            return $email;
        }
        return '=?UTF-8?B?' . base64_encode($name) . '?= <' . $email . '>';
    }
}
