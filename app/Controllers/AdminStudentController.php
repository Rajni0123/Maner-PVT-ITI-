<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

class AdminStudentController
{
    public static function index(): void
    {
        Auth::require();
        $q = trim($_GET['q'] ?? '');
        $session = admin_resolve_session_filter();
        $status = trim($_GET['status'] ?? '');

        View::render('admin/students/index', [
            'title' => 'Students',
            'students' => self::queryStudents($session, $q, $status),
            'sessions' => academic_session_options(),
            'q' => $q,
            'filterSession' => $session,
            'filterStatus' => $status,
            'totalCount' => self::countStudents($session, $q, $status),
            'sessionStats' => academic_session_stats(),
        ], 'admin');
    }

    public static function exportExcel(): void
    {
        Auth::require();
        $session = trim($_GET['session'] ?? '');
        $q = trim($_GET['q'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $rows = self::queryStudents($session, $q, $status, 5000);

        $label = $session !== '' ? preg_replace('/[^a-zA-Z0-9_-]/', '_', $session) : 'all';
        header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
        header('Content-Disposition: attachment; filename=students_' . $label . '_' . date('Y-m-d') . '.xls');
        header('Pragma: no-cache');
        header('Expires: 0');

        echo "\xEF\xBB\xBF";
        $out = fopen('php://output', 'w');
        fputcsv($out, [
            'S.No', 'Name', 'Father Name', 'Mother Name', 'Mobile', 'Email',
            'Enrollment', 'Trade', 'Session', 'Shift', 'Status', 'Gender',
            'Category', 'DOB', 'Admission Date', 'District', 'State',
        ], "\t");

        $i = 1;
        foreach ($rows as $r) {
            fputcsv($out, [
                $i++,
                $r['student_name'] ?? '',
                $r['father_name'] ?? '',
                $r['mother_name'] ?? '',
                $r['mobile'] ?? '',
                $r['email'] ?? '',
                $r['enrollment_number'] ?? '',
                $r['trade'] ?? '',
                $r['session'] ?? '',
                $r['shift'] ?? '',
                $r['status'] ?? '',
                $r['gender'] ?? '',
                $r['category'] ?? '',
                $r['dob'] ?? '',
                $r['admission_date'] ?? '',
                $r['district'] ?? '',
                $r['state'] ?? '',
            ], "\t");
        }
        fclose($out);
        exit;
    }

    public static function exportPdf(): void
    {
        Auth::require();
        $session = trim($_GET['session'] ?? '');
        $q = trim($_GET['q'] ?? '');
        $status = trim($_GET['status'] ?? '');
        $students = self::queryStudents($session, $q, $status, 5000);

        $title = 'Students List';
        if ($session !== '') {
            $title .= ' — Session ' . $session;
        }

        View::render('print/students-list', [
            'title' => $title,
            'students' => $students,
            'filterSession' => $session,
            'filterStatus' => $status,
            'filterQ' => $q,
        ], 'print');
    }

    /** @return list<array<string,mixed>> */
    private static function queryStudents(string $session, string $q, string $status, int $limit = 500): array
    {
        [$sql, $params] = self::studentFilterSql($session, $q, $status);
        $sql .= ' ORDER BY student_name ASC, id DESC LIMIT ' . (int) $limit;
        return Database::fetchAll($sql, $params);
    }

    private static function countStudents(string $session, string $q, string $status): int
    {
        [$sql, $params] = self::studentFilterSql($session, $q, $status, true);
        $row = Database::fetch($sql, $params);
        return (int) ($row['c'] ?? 0);
    }

    /** @return array{0:string,1:list<mixed>} */
    private static function studentFilterSql(string $session, string $q, string $status, bool $count = false): array
    {
        $sql = $count
            ? 'SELECT COUNT(*) AS c FROM students WHERE 1=1'
            : 'SELECT * FROM students WHERE 1=1';
        $params = [];

        if ($session !== '') {
            $sql .= ' AND session = ?';
            $params[] = $session;
        }
        if ($status !== '') {
            $sql .= ' AND status = ?';
            $params[] = $status;
        }
        if ($q !== '') {
            $sql .= ' AND (student_name LIKE ? OR mobile LIKE ? OR enrollment_number LIKE ? OR father_name LIKE ?)';
            $like = '%' . $q . '%';
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
            $params[] = $like;
        }

        return [$sql, $params];
    }

    public static function view(int $id): void
    {
        Auth::require();
        $student = Database::fetch('SELECT * FROM students WHERE id = ?', [$id]);
        if (!$student) {
            redirect('admin/students');
        }
        $admission = null;
        if (!empty($student['admission_id'])) {
            $admission = Database::fetch('SELECT * FROM admissions WHERE id = ?', [$student['admission_id']]);
        }
        View::render('admin/students/view', [
            'title' => $student['student_name'],
            'student' => $student,
            'admission' => $admission,
            'trades' => \App\Models\SiteData::activeTrades(),
            'sessions' => \App\Models\SiteData::activeSessions(),
        ], 'admin');
    }

    public static function save(int $id = 0): void
    {
        Auth::require();
        verify_csrf();

        $data = [
            'student_name' => trim($_POST['student_name'] ?? ''),
            'father_name' => trim($_POST['father_name'] ?? ''),
            'mother_name' => trim($_POST['mother_name'] ?? ''),
            'mobile' => trim($_POST['mobile'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'dob' => trim($_POST['dob'] ?? ''),
            'gender' => trim($_POST['gender'] ?? ''),
            'uidai_number' => normalize_uidai($_POST['uidai_number'] ?? ''),
            'trade' => trim($_POST['trade'] ?? ''),
            'enrollment_number' => trim($_POST['enrollment_number'] ?? '') ?: null,
            'session' => trim($_POST['session'] ?? ''),
            'shift' => trim($_POST['shift'] ?? ''),
            'status' => trim($_POST['status'] ?? 'Active'),
            'category' => trim($_POST['category'] ?? ''),
            'pwd_claim' => trim($_POST['pwd_claim'] ?? 'No'),
            'pwd_category' => trim($_POST['pwd_category'] ?? ''),
            'village_town_city' => trim($_POST['village_town_city'] ?? ''),
            'nearby' => trim($_POST['nearby'] ?? ''),
            'post_office' => trim($_POST['post_office'] ?? ''),
            'police_station' => trim($_POST['police_station'] ?? ''),
            'district' => trim($_POST['district'] ?? ''),
            'pincode' => trim($_POST['pincode'] ?? ''),
            'block' => trim($_POST['block'] ?? ''),
            'state' => trim($_POST['state'] ?? ''),
            'qualification' => trim($_POST['qualification'] ?? ''),
            'admission_date' => trim($_POST['admission_date'] ?? '') ?: null,
            'academic_year' => trim($_POST['academic_year'] ?? ''),
            'mis_iti_code' => trim($_POST['mis_iti_code'] ?? 'PR10001156'),
            'class_10th_school' => trim($_POST['class_10th_school'] ?? ''),
            'class_10th_subject' => trim($_POST['class_10th_subject'] ?? ''),
            'class_10th_marks_obtained' => trim($_POST['class_10th_marks_obtained'] ?? ''),
            'class_10th_total_marks' => trim($_POST['class_10th_total_marks'] ?? ''),
            'class_10th_percentage' => trim($_POST['class_10th_percentage'] ?? ''),
            'class_12th_school' => trim($_POST['class_12th_school'] ?? ''),
            'class_12th_subject' => trim($_POST['class_12th_subject'] ?? ''),
            'class_12th_marks_obtained' => trim($_POST['class_12th_marks_obtained'] ?? ''),
            'class_12th_total_marks' => trim($_POST['class_12th_total_marks'] ?? ''),
            'class_12th_percentage' => trim($_POST['class_12th_percentage'] ?? ''),
        ];

        if ($data['class_10th_percentage'] === '') {
            $calc = calc_percentage_value($data['class_10th_marks_obtained'], $data['class_10th_total_marks']);
            if ($calc !== null) {
                $data['class_10th_percentage'] = $calc;
            }
        }
        if ($data['class_12th_percentage'] === '') {
            $calc12 = calc_percentage_value($data['class_12th_marks_obtained'], $data['class_12th_total_marks']);
            if ($calc12 !== null) {
                $data['class_12th_percentage'] = $calc12;
            }
        }

        if ($data['qualification'] === '' && $data['class_10th_school'] !== '') {
            $data['qualification'] = '10TH FROM ' . strtoupper($data['class_10th_school']);
        }

        $normalized = normalize_admission_fields([
            'name' => $data['student_name'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'category' => $data['category'],
            'pwd_claim' => $data['pwd_claim'],
            'pwd_category' => $data['pwd_category'],
            'village_town_city' => $data['village_town_city'],
            'nearby' => $data['nearby'],
            'post_office' => $data['post_office'],
            'police_station' => $data['police_station'],
            'district' => $data['district'],
            'block' => $data['block'],
            'state' => $data['state'],
            'qualification' => $data['qualification'],
            'trade' => $data['trade'],
            'session' => $data['session'],
            'shift' => $data['shift'],
            'class_10th_school' => $data['class_10th_school'],
            'class_10th_subject' => $data['class_10th_subject'],
            'class_12th_school' => $data['class_12th_school'],
            'class_12th_subject' => $data['class_12th_subject'],
        ]);
        $data['student_name'] = $normalized['name'];
        $data['father_name'] = $normalized['father_name'];
        $data['mother_name'] = $normalized['mother_name'];
        $data['email'] = $normalized['email'];
        $data['gender'] = $normalized['gender'];
        $data['category'] = $normalized['category'];
        $data['pwd_claim'] = $normalized['pwd_claim'];
        $data['pwd_category'] = $normalized['pwd_category'] ?: null;
        $data['village_town_city'] = $normalized['village_town_city'] ?: null;
        $data['nearby'] = $normalized['nearby'] ?: null;
        $data['post_office'] = $normalized['post_office'] ?: null;
        $data['police_station'] = $normalized['police_station'] ?: null;
        $data['district'] = $normalized['district'] ?: null;
        $data['block'] = $normalized['block'] ?: null;
        $data['state'] = $normalized['state'] ?: null;
        $data['qualification'] = $normalized['qualification'] ?: null;
        $data['trade'] = $normalized['trade'];
        $data['session'] = $normalized['session'] ?: null;
        $data['shift'] = $normalized['shift'] ?: null;
        $data['class_10th_school'] = $normalized['class_10th_school'] ?: null;
        $data['class_10th_subject'] = $normalized['class_10th_subject'] ?: null;
        $data['class_12th_school'] = $normalized['class_12th_school'] ?: null;
        $data['class_12th_subject'] = $normalized['class_12th_subject'] ?: null;

        try {
            $photo = \App\Core\Upload::save($_FILES['photo'] ?? [], 'photo');
            if ($photo) {
                $data['photo'] = $photo;
            }
        } catch (\Throwable $e) {
            flash('error', $e->getMessage());
            redirect($id ? 'admin/students/view/' . $id : 'admin/students');
        }

        if ($id) {
            $student = Database::fetch('SELECT admission_id FROM students WHERE id = ?', [$id]);
            Database::update('students', $data, 'id = ?', [$id]);

            if (!empty($student['admission_id'])) {
                self::syncAdmission((int) $student['admission_id'], $data);
            }

            flash('success', 'Student updated.');
            redirect('admin/students/view/' . $id);
        }

        $data['admission_date'] = date('Y-m-d');
        $data['academic_year'] = date('Y') . '-' . (date('Y') + 1);
        $newId = Database::insert('students', $data);
        flash('success', 'Student created.');
        redirect('admin/students/view/' . $newId);
    }

    private static function syncAdmission(int $admissionId, array $data): void
    {
        $admissionUpdate = [
            'name' => $data['student_name'],
            'father_name' => $data['father_name'],
            'mother_name' => $data['mother_name'],
            'mobile' => $data['mobile'],
            'email' => $data['email'],
            'dob' => $data['dob'],
            'gender' => $data['gender'],
            'uidai_number' => $data['uidai_number'],
            'category' => $data['category'],
            'pwd_claim' => $data['pwd_claim'],
            'pwd_category' => $data['pwd_category'],
            'trade' => $data['trade'],
            'session' => $data['session'],
            'shift' => $data['shift'],
            'qualification' => $data['qualification'],
            'village_town_city' => $data['village_town_city'],
            'nearby' => $data['nearby'],
            'post_office' => $data['post_office'],
            'police_station' => $data['police_station'],
            'district' => $data['district'],
            'pincode' => $data['pincode'],
            'block' => $data['block'],
            'state' => $data['state'],
            'class_10th_school' => $data['class_10th_school'],
            'class_10th_subject' => $data['class_10th_subject'],
            'class_10th_marks_obtained' => $data['class_10th_marks_obtained'],
            'class_10th_total_marks' => $data['class_10th_total_marks'],
            'class_10th_percentage' => $data['class_10th_percentage'],
            'class_12th_school' => $data['class_12th_school'],
            'class_12th_subject' => $data['class_12th_subject'],
            'class_12th_marks_obtained' => $data['class_12th_marks_obtained'],
            'class_12th_total_marks' => $data['class_12th_total_marks'],
            'class_12th_percentage' => $data['class_12th_percentage'],
        ];

        if (isset($_POST['student_credit_card'])) {
            $bscc = trim($_POST['student_credit_card']);
            $admissionUpdate['student_credit_card'] = $bscc;
            $admissionUpdate['registration_type'] = $bscc === 'Yes' ? 'Student Credit Card' : 'Regular';
            if ($bscc === 'Yes') {
                $admissionUpdate['student_credit_card_details'] = json_encode([
                    'bank_name' => trim($_POST['student_credit_card_bank'] ?? ''),
                    'account_number' => trim($_POST['student_credit_card_account'] ?? ''),
                ]);
            } else {
                $admissionUpdate['student_credit_card_details'] = null;
            }
        }

        if (!empty($_POST['registration_type'])) {
            $admissionUpdate['registration_type'] = trim($_POST['registration_type']);
        }

        if (!empty($data['photo'])) {
            $row = Database::fetch('SELECT documents FROM admissions WHERE id = ?', [$admissionId]);
            $docs = json_decode_safe($row['documents'] ?? '');
            $docs['photo'] = $data['photo'];
            $admissionUpdate['documents'] = json_encode($docs);
        }

        Database::update('admissions', $admissionUpdate, 'id = ?', [$admissionId]);
    }
}
