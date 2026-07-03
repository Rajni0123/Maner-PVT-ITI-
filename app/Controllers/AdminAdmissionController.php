<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

class AdminAdmissionController
{
    public static function index(): void
    {
        Auth::require();
        $status = $_GET['status'] ?? '';
        $trade = $_GET['trade'] ?? '';
        $sql = 'SELECT * FROM admissions WHERE 1=1';
        $params = [];
        if ($status) {
            $sql .= ' AND status = ?';
            $params[] = ucfirst(strtolower($status));
        }
        if ($trade) {
            $sql .= ' AND trade = ?';
            $params[] = $trade;
        }
        $sql .= ' ORDER BY created_at DESC LIMIT 200';
        $rows = Database::fetchAll($sql, $params);
        View::render('admin/admissions/index', [
            'title' => 'Admissions',
            'admissions' => $rows,
            'filterStatus' => $status,
            'filterTrade' => $trade,
        ], 'admin');
    }

    public static function view(int $id): void
    {
        Auth::require();
        $row = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$id]);
        if (!$row) {
            redirect('admin/admissions');
        }
        $row['documents_parsed'] = json_decode_safe($row['documents']);
        View::render('admin/admissions/view', [
            'title' => 'Admission ' . app_id($id, $row['created_at'] ?? null, $row['session'] ?? null),
            'admission' => $row,
            'appId' => app_id($id, $row['created_at'] ?? null, $row['session'] ?? null),
        ], 'admin');
    }

    public static function documentsSave(int $id): void
    {
        Auth::require();
        verify_csrf();
        $row = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$id]);
        if (!$row) {
            redirect('admin/admissions');
        }
        $docs = json_decode_safe($row['documents']);
        try {
            $photo = \App\Core\Upload::save($_FILES['photo'] ?? [], 'photo');
            $aadhaar = \App\Core\Upload::save($_FILES['aadhaar'] ?? [], 'aadhaar');
            $marksheet = \App\Core\Upload::save($_FILES['marksheet'] ?? [], 'marksheet');
            $signature = \App\Core\Upload::save($_FILES['signature'] ?? [], 'signature');
            $sccDoc = \App\Core\Upload::save($_FILES['student_credit_card_doc'] ?? [], 'scc');
            $pwdCert = \App\Core\Upload::save($_FILES['pwd_certificate'] ?? [], 'pwd');
            $casteCert = \App\Core\Upload::save($_FILES['caste_certificate'] ?? [], 'caste');
            $incomeCert = \App\Core\Upload::save($_FILES['income_certificate'] ?? [], 'income');
            $residentialCert = \App\Core\Upload::save($_FILES['residential_certificate'] ?? [], 'residential');
            $uploaded = false;
            foreach ([
                'photo' => $photo,
                'aadhaar' => $aadhaar,
                'marksheet' => $marksheet,
                'signature' => $signature,
                'student_credit_card_doc' => $sccDoc,
                'pwd_certificate' => $pwdCert,
                'caste_certificate' => $casteCert,
                'income_certificate' => $incomeCert,
                'residential_certificate' => $residentialCert,
            ] as $key => $file) {
                if ($file) {
                    $docs[$key] = $file;
                    $uploaded = true;
                }
            }
            if (!$uploaded) {
                flash('error', 'Select at least one file to upload.');
                redirect('admin/admissions/view/' . $id);
            }
            Database::update('admissions', ['documents' => json_encode($docs)], 'id = ?', [$id]);
            flash('success', 'Documents updated.');
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
        }
        redirect('admin/admissions/view/' . $id);
    }

    public static function updateStatus(int $id): void
    {
        Auth::require();
        verify_csrf();
        $status = ucfirst(strtolower(trim($_POST['status'] ?? '')));
        if (!in_array($status, ['Pending', 'Approved', 'Rejected'], true)) {
            flash('error', 'Invalid status');
            redirect('admin/admissions/view/' . $id);
        }

        Database::update('admissions', ['status' => $status], 'id = ?', [$id]);

        if ($status === 'Approved') {
            self::createStudentFromAdmission($id);
        }

        flash('success', 'Status updated to ' . $status);
        if (($_POST['return'] ?? '') === 'list') {
            redirect('admin/admissions');
        }
        redirect('admin/admissions/view/' . $id);
    }

    public static function exportCsv(): void
    {
        Auth::require();
        $rows = Database::fetchAll('SELECT * FROM admissions ORDER BY created_at DESC');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=admissions_' . date('Y-m-d') . '.csv');
        $out = fopen('php://output', 'w');
        fputcsv($out, ['ID', 'Name', 'Father', 'Mobile', 'Trade', 'Session', 'Status', 'Date']);
        foreach ($rows as $r) {
            fputcsv($out, [app_id($r['id'], $r['created_at'] ?? null, $r['session'] ?? null), $r['name'], $r['father_name'], $r['mobile'], $r['trade'], $r['session'], $r['status'], $r['created_at']]);
        }
        fclose($out);
        exit;
    }

    public static function createForm(): void
    {
        Auth::require();
        View::render('admin/admissions/create', [
            'title' => 'Add Admission',
            'trades' => \App\Models\SiteData::activeTrades(),
            'sessions' => \App\Models\SiteData::activeSessions(),
        ], 'admin');
    }

    public static function save(): void
    {
        Auth::require();
        verify_csrf();

        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'father_name' => trim($_POST['father_name'] ?? ''),
            'mother_name' => trim($_POST['mother_name'] ?? ''),
            'mobile' => trim($_POST['mobile'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'dob' => trim($_POST['dob'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'category' => trim($_POST['category'] ?? 'General'),
            'uidai_number' => trim($_POST['uidai_number'] ?? ''),
            'trade' => trim($_POST['trade'] ?? ''),
            'session' => trim($_POST['session'] ?? ''),
            'shift' => trim($_POST['shift'] ?? ''),
            'village_town_city' => trim($_POST['village_town_city'] ?? ''),
            'nearby' => trim($_POST['nearby'] ?? ''),
            'police_station' => trim($_POST['police_station'] ?? ''),
            'post_office' => trim($_POST['post_office'] ?? ''),
            'district' => trim($_POST['district'] ?? ''),
            'pincode' => trim($_POST['pincode'] ?? ''),
            'block' => trim($_POST['block'] ?? ''),
            'state' => trim($_POST['state'] ?? 'Bihar'),
            'pwd_claim' => trim($_POST['pwd_claim'] ?? 'No'),
            'pwd_category' => trim($_POST['pwd_category'] ?? ''),
            'pwd_percentage' => trim($_POST['pwd_percentage'] ?? ''),
            'class_10th_school' => trim($_POST['class_10th_school'] ?? ''),
            'class_10th_marks_obtained' => trim($_POST['class_10th_marks_obtained'] ?? ''),
            'class_10th_total_marks' => trim($_POST['class_10th_total_marks'] ?? ''),
            'class_10th_percentage' => trim($_POST['class_10th_percentage'] ?? ''),
            'class_10th_subject' => trim($_POST['class_10th_subject'] ?? ''),
            'student_credit_card' => trim($_POST['student_credit_card'] ?? 'No'),
            'student_credit_card_bank' => trim($_POST['student_credit_card_bank'] ?? ''),
            'student_credit_card_account' => trim($_POST['student_credit_card_account'] ?? ''),
            'student_credit_card_holder' => trim($_POST['student_credit_card_holder'] ?? ''),
            'student_credit_card_ifsc' => trim($_POST['student_credit_card_ifsc'] ?? ''),
            'student_credit_card_branch' => trim($_POST['student_credit_card_branch'] ?? ''),
        ];
        $status = ucfirst(strtolower(trim($_POST['status'] ?? 'Pending')));
        if (!in_array($status, ['Pending', 'Approved', 'Rejected'], true)) {
            $status = 'Pending';
        }

        $errors = [];
        if ($data['name'] === '') {
            $errors[] = 'Student name is required.';
        }
        if ($data['father_name'] === '') {
            $errors[] = 'Father name is required.';
        }
        if (!preg_match('/^\d{10}$/', $data['mobile'])) {
            $errors[] = 'Valid 10-digit mobile is required.';
        }
        if ($data['trade'] === '') {
            $errors[] = 'Trade is required.';
        }
        if ($data['session'] === '') {
            $errors[] = 'Session is required.';
        }
        $isBscc = strcasecmp($data['student_credit_card'], 'Yes') === 0;
        if ($isBscc) {
            if ($data['student_credit_card_bank'] === '' || $data['student_credit_card_account'] === '') {
                $errors[] = 'BSCC bank name and account number are required when BSCC is Yes.';
            }
        }

        if ($errors) {
            flash('error', implode(' ', $errors));
            redirect('admin/admissions/add');
        }

        $data['uidai_number'] = normalize_uidai($data['uidai_number']);
        if ($data['class_10th_percentage'] === '') {
            $calc = calc_percentage_value($data['class_10th_marks_obtained'], $data['class_10th_total_marks']);
            if ($calc !== null) {
                $data['class_10th_percentage'] = $calc;
            }
        }

        $data = normalize_admission_fields($data);
        $data['student_credit_card'] = $isBscc ? 'Yes' : 'No';
        $data['pwd_claim'] = strcasecmp($data['pwd_claim'] ?? 'No', 'Yes') === 0 ? 'Yes' : 'No';
        if ($data['pwd_claim'] === 'Yes' && $data['pwd_percentage'] !== '') {
            $data['pwd_category'] = trim($data['pwd_category'] . ' (' . $data['pwd_percentage'] . '%)');
        }
        if ($data['pwd_claim'] !== 'Yes') {
            $data['pwd_category'] = '';
        }

        try {
            $photo = \App\Core\Upload::save($_FILES['photo'] ?? [], 'photo');
            $aadhaar = \App\Core\Upload::save($_FILES['aadhaar'] ?? [], 'aadhaar');
            $marksheet = \App\Core\Upload::save($_FILES['marksheet'] ?? [], 'marksheet');
            $signature = \App\Core\Upload::save($_FILES['signature'] ?? [], 'signature');
            $sccDoc = \App\Core\Upload::save($_FILES['student_credit_card_doc'] ?? [], 'scc');
            $pwdCert = \App\Core\Upload::save($_FILES['pwd_certificate'] ?? [], 'pwd');
            $casteCert = \App\Core\Upload::save($_FILES['caste_certificate'] ?? [], 'caste');
            $incomeCert = \App\Core\Upload::save($_FILES['income_certificate'] ?? [], 'income');
            $residentialCert = \App\Core\Upload::save($_FILES['residential_certificate'] ?? [], 'residential');
            $documents = json_encode([
                'photo' => $photo,
                'aadhaar' => $aadhaar,
                'marksheet' => $marksheet,
                'signature' => $signature,
                'student_credit_card_doc' => $sccDoc,
                'pwd_certificate' => $pwdCert,
                'caste_certificate' => $casteCert,
                'income_certificate' => $incomeCert,
                'residential_certificate' => $residentialCert,
            ]);

            $sccDetails = null;
            if ($isBscc) {
                $sccDetails = json_encode([
                    'bank_name' => $data['student_credit_card_bank'],
                    'account_holder' => $data['student_credit_card_holder'],
                    'account_number' => $data['student_credit_card_account'],
                    'ifsc' => strtoupper($data['student_credit_card_ifsc']),
                ]);
            }

            $qualification = $data['class_10th_school']
                ? '10TH FROM ' . $data['class_10th_school']
                : '10TH PASS';

            $id = Database::insert('admissions', [
                'name' => $data['name'],
                'father_name' => $data['father_name'],
                'mother_name' => $data['mother_name'] ?: null,
                'mobile' => $data['mobile'],
                'email' => $data['email'] ?: null,
                'trade' => $data['trade'],
                'qualification' => $qualification,
                'category' => $data['category'],
                'documents' => $documents,
                'status' => $status,
                'uidai_number' => $data['uidai_number'] ?: null,
                'village_town_city' => $data['village_town_city'] ?: null,
                'nearby' => $data['nearby'] ?: null,
                'police_station' => $data['police_station'] ?: null,
                'post_office' => $data['post_office'] ?: null,
                'district' => $data['district'] ?: null,
                'pincode' => $data['pincode'] ?: null,
                'block' => $data['block'] ?: null,
                'state' => $data['state'] ?: null,
                'pwd_category' => $data['pwd_category'] ?: null,
                'pwd_claim' => $data['pwd_claim'],
                'class_10th_school' => $data['class_10th_school'] ?: null,
                'class_10th_marks_obtained' => $data['class_10th_marks_obtained'] ?: null,
                'class_10th_total_marks' => $data['class_10th_total_marks'] ?: null,
                'class_10th_percentage' => $data['class_10th_percentage'] ?: null,
                'class_10th_subject' => $data['class_10th_subject'] ?: null,
                'session' => $data['session'],
                'shift' => $data['shift'] ?: null,
                'dob' => $data['dob'] ?: null,
                'gender' => $data['gender'] ?: null,
                'student_credit_card' => $data['student_credit_card'],
                'student_credit_card_details' => $sccDetails,
                'registration_type' => $data['student_credit_card'] === 'Yes' ? 'Student Credit Card' : 'Regular',
            ]);

            if ($status === 'Approved') {
                self::createStudentFromAdmission($id);
            }

            flash('success', 'Admission added successfully.');
            redirect('admin/admissions/view/' . $id);
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            redirect('admin/admissions/add');
        }
    }

    private static function createStudentFromAdmission(int $id): void
    {
        $exists = Database::fetch('SELECT id FROM students WHERE admission_id = ?', [$id]);
        if ($exists) {
            return;
        }
        $a = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$id]);
        if (!$a) {
            return;
        }
        $docs = json_decode_safe($a['documents']);
        $year = date('Y');
        Database::insert('students', [
            'admission_id' => $id,
            'student_name' => $a['name'],
            'father_name' => $a['father_name'],
            'mother_name' => $a['mother_name'],
            'mobile' => $a['mobile'],
            'email' => $a['email'],
            'trade' => $a['trade'],
            'admission_date' => date('Y-m-d'),
            'qualification' => $a['qualification'],
            'category' => $a['category'],
            'photo' => $docs['photo'] ?? null,
            'status' => 'Active',
            'academic_year' => $year . '-' . ($year + 1),
            'uidai_number' => $a['uidai_number'],
            'village_town_city' => $a['village_town_city'],
            'nearby' => $a['nearby'],
            'police_station' => $a['police_station'],
            'post_office' => $a['post_office'],
            'district' => $a['district'],
            'pincode' => $a['pincode'],
            'block' => $a['block'],
            'state' => $a['state'],
            'pwd_category' => $a['pwd_category'],
            'pwd_claim' => $a['pwd_claim'],
            'class_10th_school' => $a['class_10th_school'],
            'class_10th_marks_obtained' => $a['class_10th_marks_obtained'],
            'class_10th_total_marks' => $a['class_10th_total_marks'],
            'class_10th_percentage' => $a['class_10th_percentage'],
            'class_10th_subject' => $a['class_10th_subject'],
            'class_12th_school' => $a['class_12th_school'],
            'class_12th_marks_obtained' => $a['class_12th_marks_obtained'],
            'class_12th_total_marks' => $a['class_12th_total_marks'],
            'class_12th_percentage' => $a['class_12th_percentage'],
            'class_12th_subject' => $a['class_12th_subject'],
            'session' => $a['session'],
            'shift' => $a['shift'],
            'dob' => $a['dob'],
            'gender' => $a['gender'],
            'declaration_agreed' => 1,
        ]);
    }
}
