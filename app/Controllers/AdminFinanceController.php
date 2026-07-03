<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;
use App\Models\SiteData;

class AdminFinanceController
{
    public static function reports(): void
    {
        Auth::require();

        $fromDate = $_GET['from'] ?? date('Y-m-01');
        $toDate = $_GET['to'] ?? date('Y-m-d');
        $reportType = $_GET['type'] ?? 'all';

        $data = self::buildReportData($fromDate, $toDate, $reportType);

        View::render('admin/finance/reports', [
            'title' => 'Financial Reports',
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportType' => $reportType,
            'income' => $data['income'],
            'expenses' => $data['expenses'],
            'summary' => $data['summary'],
            'feeBreakdown' => $data['feeBreakdown'],
            'salaryBreakdown' => $data['salaryBreakdown'],
            'monthlyTrend' => $data['monthlyTrend'],
            'tradeWise' => $data['tradeWise'],
        ], 'admin');
    }

    public static function printReport(): void
    {
        Auth::require();

        $fromDate = $_GET['from'] ?? date('Y-m-01');
        $toDate = $_GET['to'] ?? date('Y-m-d');
        $reportType = $_GET['type'] ?? 'all';

        $data = self::buildReportData($fromDate, $toDate, $reportType);

        View::render('admin/finance/print-report', [
            'title' => 'Financial Report',
            'fromDate' => $fromDate,
            'toDate' => $toDate,
            'reportType' => $reportType,
            'income' => $data['income'],
            'expenses' => $data['expenses'],
            'summary' => $data['summary'],
            'feeBreakdown' => $data['feeBreakdown'],
            'salaryBreakdown' => $data['salaryBreakdown'],
            'monthlyTrend' => $data['monthlyTrend'],
            'tradeWise' => $data['tradeWise'],
            'header' => SiteData::header(),
        ], 'print');
    }

    public static function exportCsv(): void
    {
        Auth::require();

        $fromDate = $_GET['from'] ?? date('Y-m-01');
        $toDate = $_GET['to'] ?? date('Y-m-d');
        $reportType = $_GET['type'] ?? 'all';

        $data = self::buildReportData($fromDate, $toDate, $reportType);

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename=financial_report_' . $fromDate . '_to_' . $toDate . '.csv');
        $out = fopen('php://output', 'w');

        fputcsv($out, ['FINANCIAL REPORT: ' . $fromDate . ' to ' . $toDate]);
        fputcsv($out, []);

        fputcsv($out, ['=== SUMMARY ===']);
        fputcsv($out, ['Total Income (Fees Collected)', $data['summary']['total_income']]);
        fputcsv($out, ['Total Expenses (Salaries Paid)', $data['summary']['total_expenses']]);
        fputcsv($out, ['Net Balance', $data['summary']['net_balance']]);
        fputcsv($out, ['Total Fees Billed', $data['summary']['total_billed']]);
        fputcsv($out, ['Outstanding Dues', $data['summary']['outstanding']]);
        fputcsv($out, []);

        if ($reportType === 'all' || $reportType === 'income') {
            fputcsv($out, ['=== FEE COLLECTIONS ===']);
            fputcsv($out, ['Date', 'Student', 'Trade', 'Fee Type', 'Amount', 'Paid', 'Method', 'Receipt']);
            foreach ($data['income'] as $row) {
                fputcsv($out, [
                    $row['payment_date'] ?? $row['created_at'],
                    $row['student_name'],
                    $row['trade'],
                    $row['fee_type'],
                    $row['amount'],
                    $row['paid_amount'],
                    $row['payment_method'] ?? 'N/A',
                    $row['receipt_number'] ?? '',
                ]);
            }
            fputcsv($out, []);
        }

        if ($reportType === 'all' || $reportType === 'expense') {
            fputcsv($out, ['=== SALARY DISBURSEMENTS ===']);
            fputcsv($out, ['Month/Year', 'Employee', 'Code', 'Gross', 'Deductions', 'Net Pay', 'Slip No']);
            foreach ($data['expenses'] as $row) {
                fputcsv($out, [
                    month_name($row['slip_month']) . ' ' . $row['slip_year'],
                    $row['staff_name'],
                    $row['employee_code'] ?? '',
                    $row['gross_pay'],
                    $row['total_deductions'],
                    $row['net_pay'],
                    $row['slip_number'] ?? '',
                ]);
            }
        }

        fclose($out);
        exit;
    }

    private static function buildReportData(string $fromDate, string $toDate, string $reportType): array
    {
        $from = $fromDate . ' 00:00:00';
        $to = $toDate . ' 23:59:59';

        $income = [];
        $expenses = [];
        $feeBreakdown = [];
        $salaryBreakdown = [];
        $tradeWise = [];

        if ($reportType === 'all' || $reportType === 'income') {
            $income = Database::fetchAll(
                'SELECT * FROM student_fees
                 WHERE paid_amount > 0
                   AND (payment_date BETWEEN ? AND ? OR (payment_date IS NULL AND created_at BETWEEN ? AND ?))
                 ORDER BY COALESCE(payment_date, created_at) DESC',
                [$fromDate, $toDate, $from, $to]
            );

            $feeBreakdown = Database::fetchAll(
                "SELECT fee_type,
                        COUNT(*) AS count,
                        SUM(amount) AS total_billed,
                        SUM(paid_amount) AS total_collected,
                        SUM(amount - paid_amount) AS total_due
                 FROM student_fees
                 WHERE (payment_date BETWEEN ? AND ? OR (payment_date IS NULL AND created_at BETWEEN ? AND ?))
                 GROUP BY fee_type
                 ORDER BY total_collected DESC",
                [$fromDate, $toDate, $from, $to]
            );

            $tradeWise = Database::fetchAll(
                "SELECT trade,
                        COUNT(*) AS count,
                        SUM(paid_amount) AS total_collected,
                        SUM(amount - paid_amount) AS total_due
                 FROM student_fees
                 WHERE paid_amount > 0
                   AND (payment_date BETWEEN ? AND ? OR (payment_date IS NULL AND created_at BETWEEN ? AND ?))
                 GROUP BY trade
                 ORDER BY total_collected DESC",
                [$fromDate, $toDate, $from, $to]
            );
        }

        if ($reportType === 'all' || $reportType === 'expense') {
            $fromMonth = (int) date('n', strtotime($fromDate));
            $fromYear = (int) date('Y', strtotime($fromDate));
            $toMonth = (int) date('n', strtotime($toDate));
            $toYear = (int) date('Y', strtotime($toDate));

            try {
                $expenses = Database::fetchAll(
                    "SELECT s.*, st.name AS staff_name, st.employee_code, st.designation
                     FROM staff_salary_slips s
                     JOIN staff st ON st.id = s.staff_id
                     WHERE (s.slip_year > ? OR (s.slip_year = ? AND s.slip_month >= ?))
                       AND (s.slip_year < ? OR (s.slip_year = ? AND s.slip_month <= ?))
                     ORDER BY s.slip_year DESC, s.slip_month DESC",
                    [$fromYear, $fromYear, $fromMonth, $toYear, $toYear, $toMonth]
                );

                $salaryBreakdown = Database::fetchAll(
                    "SELECT st.designation,
                            COUNT(*) AS slip_count,
                            SUM(s.gross_pay) AS total_gross,
                            SUM(s.total_deductions) AS total_deductions,
                            SUM(s.net_pay) AS total_net
                     FROM staff_salary_slips s
                     JOIN staff st ON st.id = s.staff_id
                     WHERE (s.slip_year > ? OR (s.slip_year = ? AND s.slip_month >= ?))
                       AND (s.slip_year < ? OR (s.slip_year = ? AND s.slip_month <= ?))
                     GROUP BY st.designation
                     ORDER BY total_net DESC",
                    [$fromYear, $fromYear, $fromMonth, $toYear, $toYear, $toMonth]
                );
            } catch (\Throwable $e) {
                $expenses = [];
                $salaryBreakdown = [];
            }
        }

        $totalIncome = 0.0;
        foreach ($income as $row) {
            $totalIncome += (float) ($row['paid_amount'] ?? 0);
        }

        $totalExpenses = 0.0;
        foreach ($expenses as $row) {
            $totalExpenses += (float) ($row['net_pay'] ?? 0);
        }

        $totalBilled = 0.0;
        foreach ($income as $row) {
            $totalBilled += (float) ($row['amount'] ?? 0);
        }

        $monthlyTrend = self::buildMonthlyTrend($fromDate, $toDate);

        return [
            'income' => $income,
            'expenses' => $expenses,
            'feeBreakdown' => $feeBreakdown,
            'salaryBreakdown' => $salaryBreakdown,
            'tradeWise' => $tradeWise,
            'monthlyTrend' => $monthlyTrend,
            'summary' => [
                'total_income' => $totalIncome,
                'total_expenses' => $totalExpenses,
                'net_balance' => $totalIncome - $totalExpenses,
                'total_billed' => $totalBilled,
                'outstanding' => $totalBilled - $totalIncome,
                'fee_count' => count($income),
                'salary_count' => count($expenses),
            ],
        ];
    }

    private static function buildMonthlyTrend(string $fromDate, string $toDate): array
    {
        $trend = [];
        $start = strtotime(date('Y-m-01', strtotime($fromDate)));
        $end = strtotime(date('Y-m-01', strtotime($toDate)));

        while ($start <= $end) {
            $monthStart = date('Y-m-01', $start);
            $monthEnd = date('Y-m-t', $start);
            $label = date('M Y', $start);
            $month = (int) date('n', $start);
            $year = (int) date('Y', $start);

            $incomeRow = Database::fetch(
                'SELECT COALESCE(SUM(paid_amount), 0) AS total
                 FROM student_fees
                 WHERE paid_amount > 0
                   AND (payment_date BETWEEN ? AND ? OR (payment_date IS NULL AND created_at BETWEEN ? AND ?))',
                [$monthStart, $monthEnd, $monthStart . ' 00:00:00', $monthEnd . ' 23:59:59']
            );

            $expenseTotal = 0.0;
            try {
                $expenseRow = Database::fetch(
                    'SELECT COALESCE(SUM(net_pay), 0) AS total
                     FROM staff_salary_slips
                     WHERE slip_month = ? AND slip_year = ?',
                    [$month, $year]
                );
                $expenseTotal = (float) ($expenseRow['total'] ?? 0);
            } catch (\Throwable $e) {
            }

            $trend[] = [
                'label' => $label,
                'income' => (float) ($incomeRow['total'] ?? 0),
                'expense' => $expenseTotal,
            ];

            $start = strtotime('+1 month', $start);
        }

        return $trend;
    }
}
