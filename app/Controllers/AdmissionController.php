<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Upload;
use App\Core\View;
use App\Models\SiteData;

class AdmissionController
{
    public static function form(): void
    {
        View::render('public/apply-admission', [
            'title' => 'Online Admission Portal 2026 | Maner Private ITI',
            'sessions' => SiteData::activeSessions(),
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function checkUidai(): void
    {
        header('Content-Type: application/json');
        $uidai = preg_replace('/\D/', '', $_GET['uidai'] ?? '');
        if (strlen($uidai) !== 12) {
            echo json_encode(['available' => false, 'message' => 'Invalid UIDAI number']);
            return;
        }
        $inAdmissions = Database::fetch('SELECT id FROM admissions WHERE uidai_number = ? LIMIT 1', [$uidai]);
        $inStudents = Database::fetch('SELECT id FROM students WHERE uidai_number = ? LIMIT 1', [$uidai]);
        $exists = $inAdmissions || $inStudents;
        echo json_encode([
            'available' => !$exists,
            'message' => $exists ? 'This Aadhaar is already registered.' : 'Available',
        ]);
    }

    public static function submit(): void
    {
        verify_csrf();
        $data = self::collectPost();
        $errors = self::validate($data);

        if ($errors) {
            set_old($data);
            flash('error', implode(' ', $errors));
            redirect('apply-admission');
        }

        try {
            $photo = Upload::save($_FILES['photo'] ?? [], 'photo');
            $aadhaar = Upload::save($_FILES['aadhaar'] ?? [], 'aadhaar');
            $marksheet = Upload::save($_FILES['marksheet'] ?? [], 'marksheet');
            $signature = Upload::save($_FILES['signature'] ?? [], 'signature');
            $sccDoc = Upload::save($_FILES['student_credit_card_doc'] ?? [], 'scc');

            if (!$photo || !$aadhaar || !$marksheet || !$signature) {
                throw new \RuntimeException('Photo, Aadhaar, 10th marksheet and signature are required.');
            }

            $documents = json_encode([
                'photo' => $photo,
                'aadhaar' => $aadhaar,
                'marksheet' => $marksheet,
                'signature' => $signature,
                'student_credit_card_doc' => $sccDoc,
            ]);

            $qualification = $data['class_10th_school']
                ? '10TH FROM ' . $data['class_10th_school']
                : '10TH PASS';

            $sccDetails = null;
            if (($data['student_credit_card'] ?? '') === 'Yes') {
                $sccDetails = json_encode([
                    'bank_name' => $data['student_credit_card_bank'] ?? '',
                    'account_holder' => $data['student_credit_card_holder'] ?? '',
                    'account_number' => $data['student_credit_card_account'] ?? '',
                    'ifsc' => strtoupper($data['student_credit_card_ifsc'] ?? ''),
                    'branch' => $data['student_credit_card_branch'] ?? '',
                ]);
            }

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
                'status' => 'Pending',
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
                'pwd_claim' => $data['pwd_claim'] ?? 'No',
                'class_10th_school' => $data['class_10th_school'] ?: null,
                'class_10th_marks_obtained' => $data['class_10th_marks_obtained'] ?: null,
                'class_10th_total_marks' => $data['class_10th_total_marks'] ?: null,
                'class_10th_percentage' => $data['class_10th_percentage'] ?: null,
                'class_10th_subject' => $data['class_10th_subject'] ?: null,
                'class_12th_school' => $data['class_12th_school'] ?: null,
                'class_12th_marks_obtained' => $data['class_12th_marks_obtained'] ?: null,
                'class_12th_total_marks' => $data['class_12th_total_marks'] ?: null,
                'class_12th_percentage' => $data['class_12th_percentage'] ?: null,
                'class_12th_subject' => $data['class_12th_subject'] ?: null,
                'session' => $data['session'] ?: null,
                'shift' => $data['shift'] ?: null,
                'dob' => $data['dob'] ?: null,
                'gender' => $data['gender'] ?: null,
                'student_credit_card' => $data['student_credit_card'] ?? 'No',
                'student_credit_card_details' => $sccDetails,
                'registration_type' => ($data['student_credit_card'] ?? '') === 'Yes' ? 'Student Credit Card' : 'Regular',
            ]);

            $_SESSION['last_application_id'] = $id;
            clear_old();
            redirect('apply-admission/success');
        } catch (\Throwable $e) {
            set_old($data);
            flash('error', $e->getMessage());
            redirect('apply-admission');
        }
    }

    public static function success(): void
    {
        $id = $_SESSION['last_application_id'] ?? null;
        if (!$id) {
            redirect('apply-admission');
        }
        $admission = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$id]);
        View::render('public/admission-success', [
            'title' => 'Application Submitted',
            'admission' => $admission,
            'appId' => app_id($id, $admission['created_at'] ?? null, $admission['session'] ?? null),
        ]);
    }

    public static function print(int $id): void
    {
        $admission = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$id]);
        if (!$admission) {
            http_response_code(404);
            die('Application not found');
        }
        $docs = json_decode_safe($admission['documents']);
        $header = SiteData::header();
        View::render('print/admission-form', [
            'admission' => $admission,
            'docs' => $docs,
            'appId' => app_id($id, $admission['created_at'] ?? null, $admission['session'] ?? null),
            'header' => $header,
        ], 'print');
    }

    private static function collectPost(): array
    {
        $fields = [
            'name', 'father_name', 'mother_name', 'mobile', 'email', 'dob', 'gender', 'category',
            'uidai_number', 'village_town_city', 'nearby', 'police_station', 'post_office', 'district',
            'pincode', 'block', 'state', 'pwd_category', 'pwd_claim', 'trade', 'session', 'shift',
            'class_10th_school', 'class_10th_marks_obtained', 'class_10th_total_marks',
            'class_10th_percentage', 'class_10th_subject', 'class_12th_school', 'class_12th_marks_obtained',
            'class_12th_total_marks', 'class_12th_percentage', 'class_12th_subject',
            'student_credit_card', 'student_credit_card_bank', 'student_credit_card_account',
            'student_credit_card_holder', 'student_credit_card_ifsc', 'student_credit_card_branch',
        ];
        $out = [];
        foreach ($fields as $f) {
            $out[$f] = trim($_POST[$f] ?? '');
        }
        $out['uidai_number'] = normalize_uidai($out['uidai_number']);
        if ($out['class_10th_percentage'] === '') {
            $calc = calc_percentage_value($out['class_10th_marks_obtained'], $out['class_10th_total_marks']);
            if ($calc !== null) {
                $out['class_10th_percentage'] = $calc;
            }
        }
        if ($out['class_12th_percentage'] === '') {
            $calc12 = calc_percentage_value($out['class_12th_marks_obtained'], $out['class_12th_total_marks']);
            if ($calc12 !== null) {
                $out['class_12th_percentage'] = $calc12;
            }
        }
        $out = normalize_admission_fields($out);
        $out['student_credit_card'] = strcasecmp($out['student_credit_card'] ?? 'No', 'Yes') === 0 ? 'Yes' : 'No';
        return $out;
    }

    private static function validate(array $d): array
    {
        $errors = [];
        if (!$d['name']) $errors[] = 'Name required.';
        if (!$d['father_name']) $errors[] = 'Father name required.';
        if (!preg_match('/^\d{10}$/', $d['mobile'])) $errors[] = 'Valid 10-digit mobile required.';
        if (!$d['trade']) $errors[] = 'Trade required.';
        if (!$d['session']) $errors[] = 'Session required.';
        if (!$d['village_town_city'] || !$d['district'] || !$d['pincode']) $errors[] = 'Complete address required.';
        if (!empty($d['uidai_number']) && strlen(preg_replace('/\D/', '', $d['uidai_number'])) !== 12) {
            $errors[] = 'UIDAI must be 12 digits.';
        }
        if (($d['student_credit_card'] ?? 'No') === 'Yes') {
            if (($d['student_credit_card_bank'] ?? '') === '' || ($d['student_credit_card_account'] ?? '') === '') {
                $errors[] = 'BSCC bank name and account number are required.';
            }
        }
        if (empty($_POST['declaration'])) $errors[] = 'Accept declaration.';
        return $errors;
    }
}
