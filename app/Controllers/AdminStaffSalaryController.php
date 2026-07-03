<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use App\Models\SiteData;

class AdminStaffSalaryController
{
    public static function ensureSchema(): void
    {
        static $checked = false;
        if ($checked) {
            return;
        }
        $checked = true;

        $row = Database::fetch(
            "SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'staff'"
        );
        if ((int) ($row['c'] ?? 0) > 0) {
            return;
        }

        $sql = file_get_contents(base_path('database/migrate-staff-salary.sql'));
        if ($sql) {
            Database::connect()->exec($sql);
        }
    }

    public static function index(): void
    {
        Auth::require();
        self::ensureSchema();

        View::render('admin/staff/index', [
            'title' => 'Staff Management',
            'staff' => Database::fetchAll('SELECT * FROM staff ORDER BY name'),
        ], 'admin');
    }

    public static function save(): void
    {
        Auth::require();
        verify_csrf();
        self::ensureSchema();

        $id = (int) ($_POST['id'] ?? 0);
        $data = self::staffPayload();

        if ($data['name'] === '' || $data['designation'] === '') {
            flash('error', 'Staff name and designation are required.');
            redirect('admin/staff');
        }

        if ($data['employee_code'] === '') {
            $data['employee_code'] = self::nextEmployeeCode();
        }

        if ($id) {
            Database::update('staff', $data, 'id = ?', [$id]);
            flash('success', 'Staff updated.');
        } else {
            Database::insert('staff', $data);
            flash('success', 'Staff added.');
        }

        redirect('admin/staff');
    }

    public static function delete(int $id): void
    {
        Auth::require();
        verify_csrf();
        self::ensureSchema();
        Database::delete('staff', 'id = ?', [$id]);
        flash('success', 'Staff removed.');
        redirect('admin/staff');
    }

    public static function salaryForm(): void
    {
        Auth::require();
        self::ensureSchema();

        $slips = Database::fetchAll(
            'SELECT s.*, st.name AS staff_name, st.employee_code
             FROM staff_salary_slips s
             JOIN staff st ON st.id = s.staff_id
             ORDER BY s.generated_at DESC
             LIMIT 50'
        );

        View::render('admin/staff/salary', [
            'title' => 'Staff Salary Slip',
            'staff' => Database::fetchAll('SELECT * FROM staff WHERE is_active = 1 ORDER BY name'),
            'slips' => $slips,
            'currentMonth' => (int) date('n'),
            'currentYear' => (int) date('Y'),
        ], 'admin');
    }

    public static function salaryGenerate(): void
    {
        Auth::require();
        verify_csrf();
        self::ensureSchema();

        $staffId = (int) ($_POST['staff_id'] ?? 0);
        $month = (int) ($_POST['slip_month'] ?? 0);
        $year = (int) ($_POST['slip_year'] ?? 0);

        $staff = Database::fetch('SELECT * FROM staff WHERE id = ?', [$staffId]);
        if (!$staff) {
            flash('error', 'Select a valid staff member.');
            redirect('admin/staff/salary');
        }
        if ($month < 1 || $month > 12 || $year < 2000) {
            flash('error', 'Select a valid salary month and year.');
            redirect('admin/staff/salary');
        }

        $payload = self::salaryPayload();
        $gross = salary_amount($payload['basic_salary'] + $payload['hra'] + $payload['da'] + $payload['other_allowances']);
        $deductions = salary_amount(
            $payload['pf_deduction'] + $payload['esi_deduction'] + $payload['tax_deduction'] + $payload['other_deductions']
        );
        $net = salary_amount($gross - $deductions);

        $existing = Database::fetch(
            'SELECT id FROM staff_salary_slips WHERE staff_id = ? AND slip_month = ? AND slip_year = ?',
            [$staffId, $month, $year]
        );

        $row = array_merge($payload, [
            'staff_id' => $staffId,
            'slip_month' => $month,
            'slip_year' => $year,
            'gross_pay' => $gross,
            'total_deductions' => $deductions,
            'net_pay' => $net,
            'notes' => trim($_POST['notes'] ?? '') ?: null,
        ]);

        if ($existing) {
            unset($row['slip_number']);
            Database::update('staff_salary_slips', $row, 'id = ?', [(int) $existing['id']]);
            $slipId = (int) $existing['id'];
            flash('success', 'Salary slip updated.');
        } else {
            $row['slip_number'] = salary_slip_no();
            $slipId = Database::insert('staff_salary_slips', $row);
            flash('success', 'Salary slip generated.');
        }

        redirect('admin/staff/salary/print/' . $slipId);
    }

    public static function salaryPrint(int $id): void
    {
        Auth::require();
        self::ensureSchema();

        $slip = Database::fetch(
            'SELECT s.*, st.name, st.designation, st.department, st.employee_code,
                    st.bank_name, st.account_number, st.pan_number, st.pf_number,
                    st.mobile, st.email, st.date_of_joining, st.address
             FROM staff_salary_slips s
             JOIN staff st ON st.id = s.staff_id
             WHERE s.id = ?',
            [$id]
        );
        if (!$slip) {
            redirect('admin/staff/salary');
        }

        View::render('print/salary-slip', [
            'title' => 'Salary Slip',
            'slip' => $slip,
            'header' => SiteData::header(),
        ], 'print');
    }

    private static function staffPayload(): array
    {
        return [
            'employee_code' => strtoupper(trim($_POST['employee_code'] ?? '')) ?: null,
            'name' => strtoupper(trim($_POST['name'] ?? '')),
            'designation' => strtoupper(trim($_POST['designation'] ?? '')),
            'department' => strtoupper(trim($_POST['department'] ?? '')) ?: null,
            'date_of_joining' => trim($_POST['date_of_joining'] ?? '') ?: null,
            'bank_name' => strtoupper(trim($_POST['bank_name'] ?? '')) ?: null,
            'account_number' => trim($_POST['account_number'] ?? '') ?: null,
            'pan_number' => strtoupper(trim($_POST['pan_number'] ?? '')) ?: null,
            'pf_number' => trim($_POST['pf_number'] ?? '') ?: null,
            'mobile' => trim($_POST['mobile'] ?? '') ?: null,
            'email' => strtolower(trim($_POST['email'] ?? '')) ?: null,
            'address' => strtoupper(trim($_POST['address'] ?? '')) ?: null,
            'basic_salary' => salary_amount((float) ($_POST['basic_salary'] ?? 0)),
            'hra' => salary_amount((float) ($_POST['hra'] ?? 0)),
            'da' => salary_amount((float) ($_POST['da'] ?? 0)),
            'other_allowances' => salary_amount((float) ($_POST['other_allowances'] ?? 0)),
            'pf_deduction' => salary_amount((float) ($_POST['pf_deduction'] ?? 0)),
            'esi_deduction' => salary_amount((float) ($_POST['esi_deduction'] ?? 0)),
            'tax_deduction' => salary_amount((float) ($_POST['tax_deduction'] ?? 0)),
            'other_deductions' => salary_amount((float) ($_POST['other_deductions'] ?? 0)),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ];
    }

    private static function salaryPayload(): array
    {
        return [
            'working_days' => max(1, (int) ($_POST['working_days'] ?? 30)),
            'paid_days' => max(1, (int) ($_POST['paid_days'] ?? 30)),
            'basic_salary' => salary_amount((float) ($_POST['basic_salary'] ?? 0)),
            'hra' => salary_amount((float) ($_POST['hra'] ?? 0)),
            'da' => salary_amount((float) ($_POST['da'] ?? 0)),
            'other_allowances' => salary_amount((float) ($_POST['other_allowances'] ?? 0)),
            'pf_deduction' => salary_amount((float) ($_POST['pf_deduction'] ?? 0)),
            'esi_deduction' => salary_amount((float) ($_POST['esi_deduction'] ?? 0)),
            'tax_deduction' => salary_amount((float) ($_POST['tax_deduction'] ?? 0)),
            'other_deductions' => salary_amount((float) ($_POST['other_deductions'] ?? 0)),
        ];
    }

    private static function nextEmployeeCode(): string
    {
        $row = Database::fetch('SELECT COUNT(*) AS c FROM staff');
        $num = (int) ($row['c'] ?? 0) + 1;
        return 'STF-' . str_pad((string) $num, 4, '0', STR_PAD_LEFT);
    }
}
