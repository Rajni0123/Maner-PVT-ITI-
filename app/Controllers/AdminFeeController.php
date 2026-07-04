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

    public static function report(): void
    {
        Auth::require();
        $session = trim($_GET['session'] ?? '');
        $sessions = self::sessionOptions();

        $fees = [];
        $summary = ['total' => 0, 'paid' => 0, 'cnt' => 0, 'students' => 0];
        $byTrade = [];

        if ($session !== '') {
            $fees = self::feesForSession($session);
            $summary = self::feeSummary($fees);
            $byTrade = self::feeByTrade($fees);
        }

        View::render('admin/fees/report', [
            'title' => 'Session Fee Report',
            'sessions' => $sessions,
            'filterSession' => $session,
            'fees' => $fees,
            'summary' => $summary,
            'byTrade' => $byTrade,
        ], 'admin');
    }

    public static function reportExcel(): void
    {
        Auth::require();
        $session = trim($_GET['session'] ?? '');
        if ($session === '') {
            flash('error', 'Select a session to export fee report.');
            redirect('admin/fees/report');
        }

        $fees = self::feesForSession($session);
        $label = preg_replace('/[^a-zA-Z0-9_-]/', '_', $session);
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename=fee_report_' . $label . '_' . date('Y-m-d') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "\xEF\xBB\xBF";
        $out = fopen('php://output', 'w');
        fputcsv($out, [
            'S.No', 'Student', 'Father Name', 'Mobile', 'Trade', 'Session',
            'Fee Type', 'Amount', 'Paid', 'Due', 'Status', 'Receipt', 'Payment Date', 'Method',
        ], "\t");

        $i = 1;
        foreach ($fees as $f) {
            $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']);
            fputcsv($out, [
                $i++,
                $f['student_name'] ?? '',
                $f['father_name'] ?? '',
                $f['mobile'] ?? '',
                $f['trade'] ?? '',
                $f['fee_session'] ?? $session,
                $f['fee_type'] ?? '',
                $f['amount'] ?? 0,
                $f['paid_amount'] ?? 0,
                $due,
                $f['status'] ?? '',
                $f['receipt_number'] ?? '',
                $f['payment_date'] ?? '',
                $f['payment_method'] ?? '',
            ], "\t");
        }
        fclose($out);
        exit;
    }

    public static function reportPdf(): void
    {
        Auth::require();
        $session = trim($_GET['session'] ?? '');
        if ($session === '') {
            flash('error', 'Select a session to print fee report.');
            redirect('admin/fees/report');
        }

        $fees = self::feesForSession($session);
        View::render('print/fee-report', [
            'title' => 'Fee Report — Session ' . $session,
            'filterSession' => $session,
            'fees' => $fees,
            'summary' => self::feeSummary($fees),
            'byTrade' => self::feeByTrade($fees),
        ], 'print');
    }

    /** @return list<array<string,mixed>> */
    private static function feesForSession(string $session): array
    {
        return Database::fetchAll(
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
            ORDER BY student_name ASC, created_at DESC',
            [$session]
        );
    }

    /** @param list<array<string,mixed>> $fees */
    private static function feeSummary(array $fees): array
    {
        $total = 0.0;
        $paid = 0.0;
        $students = [];
        foreach ($fees as $f) {
            $total += (float) ($f['amount'] ?? 0);
            $paid += (float) ($f['paid_amount'] ?? 0);
            $key = strtolower(trim(($f['student_name'] ?? '') . '|' . ($f['mobile'] ?? '')));
            if ($key !== '|') {
                $students[$key] = true;
            }
        }
        return [
            'total' => $total,
            'paid' => $paid,
            'cnt' => count($fees),
            'students' => count($students),
        ];
    }

    /** @param list<array<string,mixed>> $fees @return list<array<string,mixed>> */
    private static function feeByTrade(array $fees): array
    {
        $map = [];
        foreach ($fees as $f) {
            $trade = trim((string) ($f['trade'] ?? '')) ?: 'Other';
            if (!isset($map[$trade])) {
                $map[$trade] = ['trade' => $trade, 'total' => 0.0, 'paid' => 0.0, 'cnt' => 0];
            }
            $map[$trade]['total'] += (float) ($f['amount'] ?? 0);
            $map[$trade]['paid'] += (float) ($f['paid_amount'] ?? 0);
            $map[$trade]['cnt']++;
        }
        ksort($map);
        return array_values($map);
    }

    /** @return list<string> */
    private static function sessionOptions(): array
    {
        $names = [];
        foreach (Database::fetchAll('SELECT session_name FROM sessions ORDER BY start_year DESC') as $row) {
            $name = trim((string) ($row['session_name'] ?? ''));
            if ($name !== '') {
                $names[$name] = $name;
            }
        }
        foreach (Database::fetchAll(
            'SELECT DISTINCT session FROM students WHERE session IS NOT NULL AND session != ""
             UNION
             SELECT DISTINCT session FROM admissions WHERE session IS NOT NULL AND session != ""'
        ) as $row) {
            $name = trim((string) ($row['session'] ?? ''));
            if ($name !== '') {
                $names[$name] = $name;
            }
        }
        return array_values($names);
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
}
