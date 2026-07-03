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
}
