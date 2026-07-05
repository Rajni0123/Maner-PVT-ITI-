<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Mail;
use App\Core\Sms;
use App\Core\View;
use App\Models\SiteData;

class AdminStudentNotificationController
{
    public static function index(): void
    {
        Auth::require();
        $session = trim($_GET['session'] ?? '');
        $students = self::loadStudents($session);
        $settings = SiteData::settings();
        $header = SiteData::header();

        View::render('admin/notifications/index', [
            'title' => 'Student Notifications',
            'students' => $students,
            'sessions' => academic_session_options(),
            'filterSession' => $session,
            'stats' => self::studentStats($students),
            'mailFrom' => trim((string) ($settings['mail_from'] ?? $header['email'] ?? '')),
            'mailFromName' => trim((string) ($settings['mail_from_name'] ?? $header['logo_text'] ?? 'Maner Private ITI')),
            'notifySubject' => trim((string) ($settings['student_notify_subject'] ?? '')),
            'notifyEmailBody' => trim((string) ($settings['student_notify_email_body'] ?? '')),
            'notifySmsBody' => trim((string) ($settings['student_notify_sms_body'] ?? '')),
            'smsSettings' => self::smsSettings($settings),
            'smsConfigured' => Sms::isConfigured(),
            'smsStatus' => Sms::statusLabel(),
        ], 'admin');
    }

    public static function setupSave(): void
    {
        Auth::require();
        verify_csrf();

        $pairs = [
            'mail_from' => trim($_POST['mail_from'] ?? ''),
            'mail_from_name' => trim($_POST['mail_from_name'] ?? ''),
            'student_notify_subject' => trim($_POST['student_notify_subject'] ?? ''),
            'student_notify_email_body' => trim($_POST['student_notify_email_body'] ?? ''),
            'student_notify_sms_body' => trim($_POST['student_notify_sms_body'] ?? ''),
            'sms_enabled' => (($_POST['sms_enabled'] ?? '0') === '1') ? '1' : '0',
            'sms_provider' => trim($_POST['sms_provider'] ?? ''),
            'sms_api_key' => trim($_POST['sms_api_key'] ?? ''),
            'sms_sender_id' => trim($_POST['sms_sender_id'] ?? ''),
            'sms_route' => trim($_POST['sms_route'] ?? ''),
            'sms_dlt_template_id' => trim($_POST['sms_dlt_template_id'] ?? ''),
            'sms_country_code' => trim($_POST['sms_country_code'] ?? '91'),
            'sms_custom_url' => trim($_POST['sms_custom_url'] ?? ''),
            'sms_custom_method' => strtoupper(trim($_POST['sms_custom_method'] ?? 'GET')),
        ];

        foreach ($pairs as $key => $value) {
            save_site_setting($key, $value);
        }

        flash('success', 'Notification setup saved.');
        redirect('admin/notifications');
    }

    public static function send(): void
    {
        Auth::require();
        verify_csrf();

        $channel = trim($_POST['notify_channel'] ?? 'email');
        $subject = trim($_POST['notify_subject'] ?? '');
        $message = trim($_POST['notify_message'] ?? '');
        $smsBody = trim($_POST['notify_sms_body'] ?? '');
        $studentIds = array_map('intval', $_POST['students'] ?? []);

        if (!in_array($channel, ['email', 'sms', 'both'], true)) {
            flash('error', 'Select a valid notification channel.');
            redirect('admin/notifications');
        }
        if ($message === '') {
            flash('error', 'Notification message is required.');
            redirect('admin/notifications');
        }
        if ($studentIds === []) {
            flash('error', 'Select at least one student.');
            redirect('admin/notifications');
        }
        if (($channel === 'email' || $channel === 'both') && $subject === '') {
            flash('error', 'Email subject is required.');
            redirect('admin/notifications');
        }
        if (($channel === 'sms' || $channel === 'both') && !Sms::isConfigured()) {
            flash('error', 'SMS gateway is not configured. Complete SMS setup first.');
            redirect('admin/notifications');
        }
        if (($channel === 'sms' || $channel === 'both') && $smsBody === '') {
            flash('error', 'SMS message template is required.');
            redirect('admin/notifications');
        }

        save_site_setting('student_notify_subject', $subject);
        save_site_setting('student_notify_email_body', trim($_POST['notify_email_body'] ?? ''));
        save_site_setting('student_notify_sms_body', $smsBody);

        $placeholders = implode(',', array_fill(0, count($studentIds), '?'));
        $rows = Database::fetchAll(
            "SELECT id, student_name, father_name, mobile, email, trade, session, enrollment_number
             FROM students WHERE id IN ({$placeholders}) AND status != 'Inactive'",
            $studentIds
        );

        $sentEmail = 0;
        $sentSms = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($rows as $student) {
            $result = self::notifyStudent($student, $channel, $subject, $message, $smsBody);
            $sentEmail += $result['email_sent'];
            $sentSms += $result['sms_sent'];
            $failed += $result['failed'];
            $skipped += $result['skipped'];
        }

        $summary = [];
        if ($channel === 'email' || $channel === 'both') {
            $summary[] = $sentEmail . ' email(s) sent';
        }
        if ($channel === 'sms' || $channel === 'both') {
            $summary[] = $sentSms . ' SMS sent';
        }
        if ($skipped > 0) {
            $summary[] = $skipped . ' skipped (missing contact)';
        }
        if ($failed > 0) {
            $summary[] = $failed . ' failed';
        }

        flash($failed > 0 && ($sentEmail + $sentSms) === 0 ? 'error' : 'success', 'Notifications: ' . implode(', ', $summary) . '.');
        redirect('admin/notifications');
    }

    /** @return list<array<string,mixed>> */
    private static function loadStudents(string $session): array
    {
        $sql = "SELECT id, student_name, father_name, mobile, email, trade, session, enrollment_number, status
                FROM students WHERE status != 'Inactive'";
        $params = [];
        if ($session !== '') {
            $sql .= ' AND session = ?';
            $params[] = $session;
        }
        $sql .= ' ORDER BY student_name ASC, id DESC';
        return Database::fetchAll($sql, $params);
    }

    /** @param list<array<string,mixed>> $students @return array{total:int,with_email:int,with_mobile:int} */
    private static function studentStats(array $students): array
    {
        $withEmail = 0;
        $withMobile = 0;
        foreach ($students as $student) {
            if (trim((string) ($student['email'] ?? '')) !== '' && filter_var($student['email'], FILTER_VALIDATE_EMAIL)) {
                $withEmail++;
            }
            if (Sms::normalizeMobile((string) ($student['mobile'] ?? '')) !== '') {
                $withMobile++;
            }
        }
        return [
            'total' => count($students),
            'with_email' => $withEmail,
            'with_mobile' => $withMobile,
        ];
    }

    /** @param array<string,string> $settings @return array<string,string> */
    private static function smsSettings(array $settings): array
    {
        return [
            'sms_enabled' => (string) ($settings['sms_enabled'] ?? '0'),
            'sms_provider' => (string) ($settings['sms_provider'] ?? ''),
            'sms_api_key' => (string) ($settings['sms_api_key'] ?? ''),
            'sms_sender_id' => (string) ($settings['sms_sender_id'] ?? ''),
            'sms_route' => (string) ($settings['sms_route'] ?? 'q'),
            'sms_dlt_template_id' => (string) ($settings['sms_dlt_template_id'] ?? ''),
            'sms_country_code' => (string) ($settings['sms_country_code'] ?? '91'),
            'sms_custom_url' => (string) ($settings['sms_custom_url'] ?? ''),
            'sms_custom_method' => (string) ($settings['sms_custom_method'] ?? 'GET'),
        ];
    }

    /**
     * @param array<string,mixed> $student
     * @return array{email_sent:int,sms_sent:int,failed:int,skipped:int}
     */
    private static function notifyStudent(array $student, string $channel, string $subject, string $message, string $smsBody): array
    {
        $header = SiteData::header();
        $institute = trim((string) (SiteData::setting('mail_from_name') ?: $header['logo_text'] ?? 'Maner Private ITI'));
        if ($institute === 'Maner Pvt ITI') {
            $institute = 'Maner Private ITI';
        }

        $vars = [
            'name' => (string) ($student['student_name'] ?? ''),
            'father_name' => (string) ($student['father_name'] ?? ''),
            'trade' => (string) ($student['trade'] ?? ''),
            'session' => session_short_label((string) ($student['session'] ?? '')),
            'enrollment' => (string) ($student['enrollment_number'] ?? ''),
            'mobile' => format_mobile((string) ($student['mobile'] ?? '')),
            'institute' => $institute,
            'phone' => format_mobile((string) ($header['phone'] ?? '')),
            'message' => $message,
        ];

        $result = ['email_sent' => 0, 'sms_sent' => 0, 'failed' => 0, 'skipped' => 0];

        if ($channel === 'email' || $channel === 'both') {
            $email = trim((string) ($student['email'] ?? ''));
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $result['skipped']++;
            } else {
                $emailSubject = Sms::renderTemplate($subject, $vars);
                $bodyText = Sms::renderTemplate(trim($_POST['notify_email_body'] ?? '') ?: $message, $vars);
                $html = '<div style="font-family:Inter,Arial,sans-serif;line-height:1.6;color:#191c1e">'
                    . '<p>Dear ' . htmlspecialchars($vars['name'], ENT_QUOTES, 'UTF-8') . ',</p>'
                    . '<p>' . nl2br(htmlspecialchars($bodyText, ENT_QUOTES, 'UTF-8')) . '</p>'
                    . '<p style="color:#45464d;font-size:14px">— ' . htmlspecialchars($institute, ENT_QUOTES, 'UTF-8') . '</p>'
                    . '</div>';
                if (Mail::send($email, $emailSubject, $html)) {
                    $result['email_sent']++;
                } else {
                    $result['failed']++;
                }
            }
        }

        if ($channel === 'sms' || $channel === 'both') {
            $mobile = Sms::normalizeMobile((string) ($student['mobile'] ?? ''));
            if ($mobile === '') {
                if ($channel === 'sms') {
                    $result['skipped']++;
                }
            } else {
                $smsText = Sms::renderTemplate($smsBody, $vars);
                $smsResult = Sms::send($mobile, $smsText);
                if (!empty($smsResult['ok'])) {
                    $result['sms_sent']++;
                } else {
                    $result['failed']++;
                }
            }
        }

        return $result;
    }
}
