<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

class AdminFeeController
{
    public static function index(): void
    {
        Auth::require();
        $fees = Database::fetchAll('SELECT * FROM student_fees ORDER BY created_at DESC LIMIT 200');
        $summary = Database::fetch(
            'SELECT SUM(amount) total, SUM(paid_amount) paid, COUNT(*) cnt FROM student_fees'
        );
        View::render('admin/fees/index', [
            'title' => 'Fee Management',
            'fees' => $fees,
            'summary' => $summary,
        ], 'admin');
    }

    public static function collectForm(): void
    {
        Auth::require();
        $prefill = self::collectPrefill();
        if (!empty($prefill['student_name'])) {
            $prefill['pending_due'] = self::studentPendingDue($prefill['student_name'], $prefill['mobile'] ?? '');
        }
        View::render('admin/fees/collect', [
            'title' => 'Collect Fee',
            'prefill' => $prefill,
        ], 'admin');
    }

    public static function searchStudents(): void
    {
        Auth::require();
        header('Content-Type: application/json');

        $q = trim($_GET['q'] ?? '');
        if (mb_strlen($q) < 2) {
            echo json_encode([]);
            return;
        }

        $like = '%' . $q . '%';
        $digits = preg_replace('/\D/', '', $q);

        $students = Database::fetchAll(
            'SELECT id, student_name, father_name, mobile, trade, session, enrollment_number, admission_id
             FROM students
             WHERE status != ?
               AND (student_name LIKE ? OR mobile LIKE ? OR enrollment_number LIKE ?)
             ORDER BY student_name
             LIMIT 12',
            ['Inactive', $like, $like, $like]
        );

        $admissions = Database::fetchAll(
            'SELECT id, name, father_name, mobile, trade, session
             FROM admissions
             WHERE status = ?
               AND (name LIKE ? OR mobile LIKE ? OR uidai_number LIKE ?)
             ORDER BY name
             LIMIT 8',
            ['Approved', $like, $like, $like]
        );

        $results = [];

        foreach ($students as $s) {
            $due = self::studentPendingDue($s['student_name'], $s['mobile'] ?? '');
            $results[] = [
                'key' => 'student-' . $s['id'],
                'type' => 'Student',
                'id' => (int) $s['id'],
                'name' => $s['student_name'],
                'father_name' => $s['father_name'] ?? '',
                'mobile' => $s['mobile'] ?? '',
                'trade' => $s['trade'] ?? '',
                'session' => $s['session'] ?? '',
                'enrollment' => $s['enrollment_number'] ?? '',
                'admission_id' => (int) ($s['admission_id'] ?? 0),
                'pending_due' => $due,
                'label' => trim(($s['enrollment_number'] ? $s['enrollment_number'] . ' · ' : '') . ($s['trade'] ?? '') . ($s['session'] ? ' · ' . $s['session'] : '')),
            ];
        }

        $studentAdmissionIds = array_filter(array_column($students, 'admission_id'));
        foreach ($admissions as $a) {
            if (in_array((int) $a['id'], array_map('intval', $studentAdmissionIds), true)) {
                continue;
            }
            $due = self::studentPendingDue($a['name'], $a['mobile'] ?? '');
            $results[] = [
                'key' => 'admission-' . $a['id'],
                'type' => 'Admission',
                'id' => (int) $a['id'],
                'name' => $a['name'],
                'father_name' => $a['father_name'] ?? '',
                'mobile' => $a['mobile'] ?? '',
                'trade' => $a['trade'] ?? '',
                'session' => $a['session'] ?? '',
                'enrollment' => '',
                'admission_id' => (int) $a['id'],
                'pending_due' => $due,
                'label' => trim(($a['trade'] ?? '') . ($a['session'] ? ' · ' . $a['session'] : '')),
            ];
        }

        if ($digits !== '' && strlen($digits) >= 4) {
            usort($results, static function (array $a, array $b) use ($digits): int {
                $aMobile = preg_replace('/\D/', '', $a['mobile'] ?? '');
                $bMobile = preg_replace('/\D/', '', $b['mobile'] ?? '');
                $aMatch = str_contains($aMobile, $digits) ? 0 : 1;
                $bMatch = str_contains($bMobile, $digits) ? 0 : 1;
                return $aMatch <=> $bMatch;
            });
        }

        echo json_encode(array_slice($results, 0, 15));
    }

    private static function studentPendingDue(string $name, string $mobile): float
    {
        $row = Database::fetch(
            'SELECT COALESCE(SUM(amount - paid_amount), 0) AS due
             FROM student_fees
             WHERE student_name = ? AND (mobile = ? OR mobile IS NULL OR mobile = ?)
               AND paid_amount < amount',
            [$name, $mobile, '']
        );

        return max(0, (float) ($row['due'] ?? 0));
    }

    public static function collectSave(): void
    {
        Auth::require();
        verify_csrf();
        self::storeFee(true);
    }

    public static function create(): void
    {
        Auth::require();
        verify_csrf();
        self::storeFee(false);
    }

    private static function collectPrefill(): array
    {
        $admissionId = (int) ($_GET['admission_id'] ?? 0);
        $studentId = (int) ($_GET['student_id'] ?? 0);
        if ($studentId) {
            $row = Database::fetch('SELECT * FROM students WHERE id = ?', [$studentId]);
            if ($row) {
                return [
                    'source' => 'student-' . $studentId,
                    'student_id' => $studentId,
                    'admission_id' => $row['admission_id'] ?? '',
                    'student_name' => $row['student_name'] ?? '',
                    'father_name' => $row['father_name'] ?? '',
                    'mobile' => $row['mobile'] ?? '',
                    'trade' => $row['trade'] ?? '',
                    'session' => $row['session'] ?? '',
                    'enrollment' => $row['enrollment_number'] ?? '',
                ];
            }
        }
        if ($admissionId) {
            $row = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$admissionId]);
            if ($row) {
                return [
                    'source' => 'admission-' . $admissionId,
                    'admission_id' => $row['id'],
                    'student_name' => $row['name'] ?? '',
                    'father_name' => $row['father_name'] ?? '',
                    'mobile' => $row['mobile'] ?? '',
                    'trade' => $row['trade'] ?? '',
                ];
            }
        }
        return [];
    }

    private static function storeFee(bool $fromCollect): void
    {
        $amount = (float) ($_POST['amount'] ?? 0);
        $paid = (float) ($_POST['paid_amount'] ?? 0);
        if ($amount <= 0) {
            flash('error', 'Fee amount must be greater than zero.');
            redirect($fromCollect ? 'admin/fees/collect' : 'admin/fees');
        }
        if ($paid > $amount) {
            flash('error', 'Paid amount cannot exceed total fee.');
            redirect($fromCollect ? 'admin/fees/collect' : 'admin/fees');
        }
        if ($paid <= 0) {
            flash('error', 'Enter the amount collected now.');
            redirect($fromCollect ? 'admin/fees/collect' : 'admin/fees');
        }
        if (trim($_POST['student_name'] ?? '') === '' || trim($_POST['trade'] ?? '') === '') {
            flash('error', 'Student name and trade are required.');
            redirect($fromCollect ? 'admin/fees/collect' : 'admin/fees');
        }
        $status = $paid >= $amount ? 'Paid' : 'Partially Paid';

        $feeId = Database::insert('student_fees', [
            'admission_id' => $_POST['admission_id'] ?: null,
            'student_name' => trim($_POST['student_name'] ?? ''),
            'father_name' => trim($_POST['father_name'] ?? ''),
            'mobile' => trim($_POST['mobile'] ?? ''),
            'trade' => trim($_POST['trade'] ?? ''),
            'fee_type' => trim($_POST['fee_type'] ?? 'Tuition Fee'),
            'total_amount' => $amount,
            'amount' => $amount,
            'paid_amount' => $paid,
            'due_date' => $_POST['due_date'] ?: null,
            'status' => $status,
            'payment_date' => $paid > 0 ? date('Y-m-d') : null,
            'payment_method' => $_POST['payment_method'] ?? null,
            'receipt_number' => $paid > 0 ? receipt_no() : null,
            'academic_year' => trim($_POST['academic_year'] ?? date('Y') . '-' . (date('Y') + 1)),
            'notes' => trim($_POST['notes'] ?? ''),
        ]);

        flash('success', $fromCollect ? 'Fee collected successfully.' : 'Fee record created.');
        if ($paid > 0) {
            redirect('admin/fees/receipt/' . $feeId);
        }
        redirect('admin/fees');
    }

    public static function pay(int $id): void
    {
        Auth::require();
        verify_csrf();
        $fee = Database::fetch('SELECT * FROM student_fees WHERE id = ?', [$id]);
        if (!$fee) {
            redirect('admin/fees');
        }
        $pay = (float) ($_POST['pay_amount'] ?? 0);
        if ($pay <= 0) {
            flash('error', 'Enter a valid payment amount.');
            redirect('admin/fees');
        }
        $newPaid = (float) $fee['paid_amount'] + $pay;
        $amount = (float) $fee['amount'];
        $status = $newPaid >= $amount ? 'Paid' : ($newPaid > 0 ? 'Partially Paid' : 'Pending');

        Database::update('student_fees', [
            'paid_amount' => $newPaid,
            'status' => $status,
            'payment_date' => date('Y-m-d'),
            'payment_method' => $_POST['payment_method'] ?? 'Cash',
            'receipt_number' => receipt_no(),
        ], 'id = ?', [$id]);

        redirect('admin/fees/receipt/' . $id);
    }

    public static function receipt(int $id): void
    {
        Auth::require();
        $fee = Database::fetch('SELECT * FROM student_fees WHERE id = ?', [$id]);
        if (!$fee) {
            redirect('admin/fees');
        }
        View::render('print/fee-receipt', ['fee' => $fee, 'title' => 'Fee Receipt'], 'print');
    }

    public static function dueNotifyForm(): void
    {
        Auth::require();
        $students = self::allDueStudents();
        $withEmail = 0;
        $withMobile = 0;
        $totalDue = 0.0;
        foreach ($students as $s) {
            $totalDue += (float) $s['total_due'];
            if (($s['email'] ?? '') !== '') {
                $withEmail++;
            }
            if (\App\Core\Sms::normalizeMobile((string) ($s['mobile'] ?? '')) !== '') {
                $withMobile++;
            }
        }

        $settings = \App\Models\SiteData::settings();
        $header = \App\Models\SiteData::header();
        $defaultSms = 'Dear {name}, fee due {due} for {trade} at {institute}. Please pay soon. Call {phone}';

        View::render('admin/fees/due-notify', [
            'title' => 'Fee Reminder Panel',
            'students' => $students,
            'stats' => [
                'total_students' => count($students),
                'with_email' => $withEmail,
                'with_mobile' => $withMobile,
                'total_due' => $totalDue,
            ],
            'mailFrom' => $settings['mail_from'] ?? ($header['email'] ?? ''),
            'mailFromName' => $settings['mail_from_name'] ?? ($header['logo_text'] ?? 'Maner Private ITI'),
            'mailSubject' => $settings['fee_reminder_subject'] ?? '',
            'mailMessage' => $settings['fee_reminder_message'] ?? '',
            'smsMessage' => $settings['fee_reminder_sms_message'] ?? $defaultSms,
            'smsConfigured' => \App\Core\Sms::isConfigured(),
            'smsStatus' => \App\Core\Sms::statusLabel(),
            'header' => $header,
        ], 'admin');
    }

    public static function dueNotifySend(): void
    {
        Auth::require();
        verify_csrf();

        $channel = strtolower(trim((string) ($_POST['notify_channel'] ?? 'email')));
        if (!in_array($channel, ['email', 'sms', 'both'], true)) {
            $channel = 'email';
        }

        // Save setup for next time
        foreach ([
            'mail_from' => trim($_POST['mail_from'] ?? ''),
            'mail_from_name' => trim($_POST['mail_from_name'] ?? ''),
            'fee_reminder_subject' => trim($_POST['mail_subject'] ?? ''),
            'fee_reminder_message' => trim($_POST['mail_message'] ?? ''),
            'fee_reminder_sms_message' => trim($_POST['sms_message'] ?? ''),
        ] as $key => $value) {
            if ($value === '') {
                continue;
            }
            $exists = Database::fetch('SELECT id FROM site_settings WHERE setting_key = ?', [$key]);
            if ($exists) {
                Database::update('site_settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
            } else {
                Database::insert('site_settings', ['setting_key' => $key, 'setting_value' => $value]);
            }
        }

        $keys = $_POST['students'] ?? [];
        if (!is_array($keys) || $keys === []) {
            flash('error', 'Select at least one student with fee due.');
            redirect('admin/fee-reminders');
        }

        if (($channel === 'sms' || $channel === 'both') && !\App\Core\Sms::isConfigured()) {
            flash('error', 'SMS gateway configured nahi hai. Settings → SMS Notification mein API key enable karein.');
            redirect('admin/fee-reminders');
        }

        $all = self::allDueStudents();
        $byKey = [];
        foreach ($all as $row) {
            $byKey[$row['key']] = $row;
        }

        $emailSent = 0;
        $emailFailed = 0;
        $smsSent = 0;
        $smsFailed = 0;
        $skipped = 0;
        $lastSmsError = '';

        $institute = \App\Models\SiteData::header();
        $instituteName = trim($_POST['mail_from_name'] ?? '') ?: ($institute['logo_text'] ?? config('site_name', 'Maner Private ITI'));
        if ($instituteName === 'Maner Pvt ITI') {
            $instituteName = 'Maner Private ITI';
        }
        $phone = $institute['phone'] ?? '';
        $officeEmail = trim($_POST['mail_from'] ?? '') ?: ($institute['email'] ?? '');
        $customSubject = trim($_POST['mail_subject'] ?? '');
        $customMessage = trim($_POST['mail_message'] ?? '');
        $smsTemplate = trim($_POST['sms_message'] ?? '');
        if ($smsTemplate === '') {
            $smsTemplate = 'Dear {name}, fee due {due} for {trade} at {institute}. Please pay soon. Call {phone}';
        }

        $settings = \App\Models\SiteData::settings();
        $useDltVars = ($settings['sms_provider'] ?? '') === 'fast2sms' && ($settings['sms_route'] ?? '') === 'dlt';

        foreach ($keys as $key) {
            $key = (string) $key;
            if (!isset($byKey[$key])) {
                continue;
            }
            $row = $byKey[$key];
            $didSomething = false;

            if ($channel === 'email' || $channel === 'both') {
                if (($row['email'] ?? '') !== '') {
                    $didSomething = true;
                    if (self::sendDueEmail($row, $instituteName, $phone, $officeEmail, $customSubject, $customMessage)) {
                        $emailSent++;
                    } else {
                        $emailFailed++;
                    }
                } elseif ($channel === 'email') {
                    $skipped++;
                }
            }

            if ($channel === 'sms' || $channel === 'both') {
                $mobile = \App\Core\Sms::normalizeMobile((string) ($row['mobile'] ?? ''));
                if ($mobile !== '') {
                    $didSomething = true;
                    $vars = [
                        'name' => $row['student_name'] ?: 'Student',
                        'due' => format_inr($row['total_due'] ?? 0),
                        'trade' => $row['trade'] ?: 'Trade',
                        'institute' => $instituteName,
                        'phone' => format_mobile($phone) ?: $phone,
                        'mobile' => format_mobile($mobile),
                    ];
                    $payload = $useDltVars
                        ? implode('|', [
                            $vars['name'],
                            preg_replace('/[^\d.]/', '', (string) ($row['total_due'] ?? 0)) ?: '0',
                            $vars['trade'],
                            $vars['institute'],
                            preg_replace('/\D+/', '', (string) $phone) ?: '',
                        ])
                        : \App\Core\Sms::renderTemplate($smsTemplate, $vars);

                    $result = \App\Core\Sms::send($mobile, $payload);
                    if (!empty($result['ok'])) {
                        $smsSent++;
                    } else {
                        $smsFailed++;
                        $lastSmsError = (string) ($result['error'] ?? 'SMS failed');
                    }
                } elseif ($channel === 'sms') {
                    $skipped++;
                }
            }

            if (!$didSomething && $channel === 'both') {
                $skipped++;
            }
        }

        $parts = [];
        if ($channel === 'email' || $channel === 'both') {
            $parts[] = "Email sent: {$emailSent}" . ($emailFailed ? ", failed: {$emailFailed}" : '');
        }
        if ($channel === 'sms' || $channel === 'both') {
            $parts[] = "SMS sent: {$smsSent}" . ($smsFailed ? ", failed: {$smsFailed}" : '');
        }
        if ($skipped > 0) {
            $parts[] = "Skipped (no contact): {$skipped}";
        }

        if ($emailSent + $smsSent > 0) {
            $msg = 'Fee reminder notifications sent. ' . implode('. ', $parts) . '.';
            if ($lastSmsError !== '' && $smsFailed > 0) {
                $msg .= ' Last SMS error: ' . $lastSmsError;
            }
            flash('success', $msg);
        } else {
            $err = 'No notifications sent. ' . implode('. ', $parts) . '.';
            if ($lastSmsError !== '') {
                $err .= ' SMS error: ' . $lastSmsError;
            }
            flash('error', $err);
        }
        redirect('admin/fee-reminders');
    }

    /** @return list<array<string,mixed>> */
    private static function allDueStudents(): array
    {
        $fees = Database::fetchAll(
            'SELECT * FROM student_fees WHERE paid_amount < amount ORDER BY student_name, created_at DESC'
        );

        $grouped = [];
        foreach ($fees as $fee) {
            $due = max(0, (float) $fee['amount'] - (float) $fee['paid_amount']);
            if ($due <= 0) {
                continue;
            }

            $email = self::resolveStudentEmail($fee);
            $mobile = trim((string) ($fee['mobile'] ?? ''));
            $key = md5(strtolower($email ?: 'none') . '|' . strtolower(trim($fee['student_name'] ?? '')) . '|' . preg_replace('/\D/', '', $mobile));
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'key' => $key,
                    'email' => $email,
                    'student_name' => $fee['student_name'] ?? '',
                    'mobile' => $mobile,
                    'trade' => $fee['trade'] ?? '',
                    'total_due' => 0.0,
                    'fee_count' => 0,
                    'items' => [],
                ];
            }

            $grouped[$key]['total_due'] += $due;
            $grouped[$key]['fee_count']++;
            $grouped[$key]['items'][] = [
                'fee_type' => $fee['fee_type'] ?? 'Fee',
                'due' => $due,
                'due_date' => $fee['due_date'] ?? null,
            ];
            if (($grouped[$key]['trade'] ?? '') === '' && !empty($fee['trade'])) {
                $grouped[$key]['trade'] = $fee['trade'];
            }
            if (($grouped[$key]['mobile'] ?? '') === '' && $mobile !== '') {
                $grouped[$key]['mobile'] = $mobile;
            }
        }

        $list = array_values($grouped);
        usort($list, static fn($a, $b) => strcasecmp($a['student_name'], $b['student_name']));
        return $list;
    }

    private static function resolveStudentEmail(array $fee): string
    {
        $admissionId = (int) ($fee['admission_id'] ?? 0);
        if ($admissionId > 0) {
            $row = Database::fetch('SELECT email FROM admissions WHERE id = ?', [$admissionId]);
            $email = strtolower(trim((string) ($row['email'] ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
            $student = Database::fetch('SELECT email FROM students WHERE admission_id = ?', [$admissionId]);
            $email = strtolower(trim((string) ($student['email'] ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        $name = trim((string) ($fee['student_name'] ?? ''));
        $mobile = preg_replace('/\D/', '', (string) ($fee['mobile'] ?? ''));

        if ($mobile !== '') {
            $student = Database::fetch(
                'SELECT email FROM students WHERE REPLACE(REPLACE(REPLACE(mobile, " ", ""), "-", ""), "+", "") LIKE ? ORDER BY id DESC LIMIT 1',
                ['%' . $mobile]
            );
            $email = strtolower(trim((string) ($student['email'] ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }

            $admission = Database::fetch(
                'SELECT email FROM admissions WHERE REPLACE(REPLACE(REPLACE(mobile, " ", ""), "-", ""), "+", "") LIKE ? ORDER BY id DESC LIMIT 1',
                ['%' . $mobile]
            );
            $email = strtolower(trim((string) ($admission['email'] ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        if ($name !== '') {
            $student = Database::fetch(
                'SELECT email FROM students WHERE LOWER(student_name) = LOWER(?) AND email IS NOT NULL AND email != "" ORDER BY id DESC LIMIT 1',
                [$name]
            );
            $email = strtolower(trim((string) ($student['email'] ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }

            $admission = Database::fetch(
                'SELECT email FROM admissions WHERE LOWER(name) = LOWER(?) AND email IS NOT NULL AND email != "" ORDER BY id DESC LIMIT 1',
                [$name]
            );
            $email = strtolower(trim((string) ($admission['email'] ?? '')));
            if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                return $email;
            }
        }

        return '';
    }

    private static function sendDueEmail(
        array $row,
        string $instituteName,
        string $phone,
        string $officeEmail,
        string $customSubject = '',
        string $customMessage = ''
    ): bool {
        $name = $row['student_name'] ?: 'Student';
        $due = format_inr($row['total_due'] ?? 0);
        $trade = $row['trade'] ?: '—';
        $mobile = format_mobile($row['mobile'] ?? '');
        $today = date('d M Y');

        $rowsHtml = '';
        $rowsText = '';
        foreach ($row['items'] as $item) {
            $dueDate = !empty($item['due_date']) ? format_date($item['due_date']) : 'As applicable';
            $amount = format_inr($item['due']);
            $type = $item['fee_type'] ?? 'Fee';
            $rowsHtml .= '<tr>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;font-size:14px">' . e($type) . '</td>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;font-size:14px;text-align:right;font-weight:700;color:#b45309">' . e($amount) . '</td>'
                . '<td style="padding:10px 12px;border-bottom:1px solid #e5e7eb;font-size:13px;color:#64748b">' . e($dueDate) . '</td>'
                . '</tr>';
            $rowsText .= "- {$type}: {$amount} (Due: {$dueDate})\n";
        }

        $extraNote = $customMessage !== ''
            ? '<p style="margin:16px 0;padding:12px 14px;background:#fff7ed;border-left:4px solid #fea619;color:#7c2d12;font-size:14px;line-height:1.5">' . nl2br(e($customMessage)) . '</p>'
            : '';

        $subject = $customSubject !== ''
            ? $customSubject
            : 'Fee Payment Reminder / शुल्क भुगतान अनुस्मारक — ' . $instituteName;

        $html = '<!DOCTYPE html><html><body style="margin:0;padding:0;background:#f1f5f9">'
            . '<div style="font-family:Segoe UI,Arial,sans-serif;max-width:640px;margin:0 auto;background:#ffffff">'
            . '<div style="background:linear-gradient(135deg,#131b2e 0%,#1e293b 100%);padding:28px 24px;text-align:center">'
            . '<div style="display:inline-block;background:#fea619;color:#131b2e;font-size:11px;font-weight:800;letter-spacing:0.08em;padding:4px 10px;border-radius:4px;margin-bottom:12px">FEE REMINDER</div>'
            . '<h1 style="margin:0;color:#ffffff;font-size:22px;font-weight:800">' . e($instituteName) . '</h1>'
            . '<p style="margin:8px 0 0;color:#94a3b8;font-size:13px">Official Fee Payment Reminder</p>'
            . '</div>'
            . '<div style="padding:28px 24px">'
            . '<p style="margin:0 0 8px;font-size:15px;color:#334155">Dear <strong style="color:#0f172a">' . e($name) . '</strong>,</p>'
            . '<p style="margin:0 0 16px;font-size:14px;line-height:1.6;color:#475569">'
            . 'This is a gentle reminder from <strong>' . e($instituteName) . '</strong> regarding your pending course fee. '
            . 'Kindly clear the outstanding amount at the earliest to avoid any inconvenience in your training / admission process.'
            . '</p>'
            . '<p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#475569">'
            . 'यह <strong>' . e($instituteName) . '</strong> की ओर से आपके बकाया शुल्क (Fee Due) के संबंध में एक आधिकारिक अनुस्मारक है। '
            . 'कृपया अपना बकाया राशि यथाशीघ्र जमा करें, ताकि आपके प्रशिक्षण / प्रवेश प्रक्रिया में कोई बाधा न आए।'
            . '</p>'
            . '<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:14px 16px;margin-bottom:18px">'
            . '<table style="width:100%;border-collapse:collapse;font-size:14px">'
            . '<tr><td style="padding:4px 0;color:#64748b">Student Name</td><td style="padding:4px 0;text-align:right;font-weight:700;color:#0f172a">' . e($name) . '</td></tr>'
            . '<tr><td style="padding:4px 0;color:#64748b">Trade / ट्रेड</td><td style="padding:4px 0;text-align:right;font-weight:700;color:#0f172a">' . e($trade) . '</td></tr>'
            . ($mobile ? '<tr><td style="padding:4px 0;color:#64748b">Mobile</td><td style="padding:4px 0;text-align:right;font-weight:700;color:#0f172a">' . e($mobile) . '</td></tr>' : '')
            . '<tr><td style="padding:4px 0;color:#64748b">Date</td><td style="padding:4px 0;text-align:right;font-weight:700;color:#0f172a">' . e($today) . '</td></tr>'
            . '</table></div>'
            . '<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:16px;text-align:center;margin-bottom:18px">'
            . '<div style="font-size:12px;font-weight:700;letter-spacing:0.06em;color:#991b1b;text-transform:uppercase">Total Outstanding / कुल बकाया</div>'
            . '<div style="font-size:28px;font-weight:800;color:#b91c1c;margin-top:4px">' . e($due) . '</div>'
            . '</div>'
            . '<table style="width:100%;border-collapse:collapse;margin-bottom:8px">'
            . '<thead><tr style="background:#131b2e;color:#fff">'
            . '<th style="padding:10px 12px;text-align:left;font-size:12px">Fee Type / शुल्क प्रकार</th>'
            . '<th style="padding:10px 12px;text-align:right;font-size:12px">Amount / राशि</th>'
            . '<th style="padding:10px 12px;text-align:left;font-size:12px">Due Date</th>'
            . '</tr></thead><tbody>' . $rowsHtml . '</tbody></table>'
            . $extraNote
            . '<p style="margin:18px 0 8px;font-size:14px;line-height:1.6;color:#475569">'
            . 'Please visit the institute office or contact us to complete the payment. Keep this email for your reference.'
            . '</p>'
            . '<p style="margin:0 0 18px;font-size:14px;line-height:1.7;color:#475569">'
            . 'भुगतान हेतु संस्थान कार्यालय से संपर्क करें। यह ईमेल आपके रिकॉर्ड के लिए सुरक्षित रखें।'
            . '</p>'
            . '<div style="background:#f1f5f9;border-radius:8px;padding:14px 16px;font-size:13px;color:#334155;line-height:1.6">'
            . '<strong>Contact / संपर्क:</strong><br>'
            . ($phone ? '📞 ' . e(format_mobile($phone)) . '<br>' : '')
            . ($officeEmail ? '✉ ' . e($officeEmail) . '<br>' : '')
            . '🏛 ' . e($instituteName)
            . '</div>'
            . '<p style="margin:22px 0 0;font-size:14px;color:#0f172a">Warm regards / सादर,<br><strong>' . e($instituteName) . '</strong><br>'
            . '<span style="color:#64748b;font-size:12px">Accounts / Admission Office</span></p>'
            . '</div>'
            . '<div style="background:#0f172a;color:#94a3b8;padding:14px 20px;text-align:center;font-size:11px;line-height:1.5">'
            . 'This is an official automated reminder from ' . e($instituteName) . '.<br>'
            . 'Please do not reply to this email if it is a no-reply address. Contact the office for assistance.'
            . '</div></div></body></html>';

        $text = "Fee Payment Reminder / शुल्क भुगतान अनुस्मारक\n"
            . "{$instituteName}\n\n"
            . "Dear {$name},\n\n"
            . "This is a reminder regarding your pending fee dues.\n"
            . "Trade: {$trade}\n"
            . "Total Due: {$due}\n\n"
            . "Details:\n{$rowsText}\n"
            . ($customMessage !== '' ? $customMessage . "\n\n" : '')
            . "Please clear your dues at the earliest.\n"
            . ($phone ? "Phone: {$phone}\n" : '')
            . ($officeEmail ? "Email: {$officeEmail}\n" : '')
            . "\nRegards,\n{$instituteName}\n";

        return \App\Core\Mail::send($row['email'], $subject, $html, $text);
    }
}
