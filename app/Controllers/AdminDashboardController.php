<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\View;

class AdminDashboardController
{
    public static function index(): void
    {
        Auth::require();
        ensure_contact_schema();

        $session = admin_resolve_session_filter();
        $sessionWhere = '';
        $sessionParams = [];
        if ($session !== '') {
            $sessionWhere = ' AND session = ?';
            $sessionParams = [$session];
        }

        $totalSeats = 0;
        foreach (Database::fetchAll('SELECT seats FROM trades WHERE is_active = 1') as $trade) {
            $totalSeats += (int) preg_replace('/\D/', '', (string) ($trade['seats'] ?? '0'));
        }
        if ($totalSeats <= 0) {
            $totalSeats = 60;
        }

        $approved = (int) (Database::fetch(
            'SELECT COUNT(*) c FROM admissions WHERE status = ?' . $sessionWhere,
            array_merge(['Approved'], $sessionParams)
        )['c'] ?? 0);
        $pending = (int) (Database::fetch(
            'SELECT COUNT(*) c FROM admissions WHERE status = ?' . $sessionWhere,
            array_merge(['Pending'], $sessionParams)
        )['c'] ?? 0);
        $totalApplications = (int) (Database::fetch(
            'SELECT COUNT(*) c FROM admissions WHERE 1=1' . $sessionWhere,
            $sessionParams
        )['c'] ?? 0);
        $students = (int) (Database::fetch(
            'SELECT COUNT(*) c FROM students WHERE status = ?' . $sessionWhere,
            array_merge(['Active'], $sessionParams)
        )['c'] ?? 0);

        if ($session !== '') {
            $feeTotals = academic_session_fee_totals($session);
            $feesPaid = $feeTotals['paid'];
            $feesTotal = $feeTotals['total'];
        } else {
            $feeRow = Database::fetch('SELECT COALESCE(SUM(amount), 0) total, COALESCE(SUM(paid_amount), 0) paid FROM student_fees');
            $feesPaid = (float) ($feeRow['paid'] ?? 0);
            $feesTotal = (float) ($feeRow['total'] ?? 0);
        }

        $unreadInquiries = (int) (Database::fetch('SELECT COUNT(*) c FROM contact WHERE is_read = 0')['c'] ?? 0);
        $inquiriesToday = (int) (Database::fetch('SELECT COUNT(*) c FROM contact WHERE DATE(created_at) = CURDATE()')['c'] ?? 0);
        $inquiriesYesterday = (int) (Database::fetch('SELECT COUNT(*) c FROM contact WHERE DATE(created_at) = DATE_SUB(CURDATE(), INTERVAL 1 DAY)')['c'] ?? 0);

        $pipeline = [];
        $pipelineMax = 1;
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = date('Y-m-01 00:00:00', strtotime("-{$i} months"));
            $monthEnd = date('Y-m-t 23:59:59', strtotime("-{$i} months"));
            $count = (int) (Database::fetch(
                'SELECT COUNT(*) c FROM admissions WHERE created_at >= ? AND created_at <= ?' . $sessionWhere,
                array_merge([$monthStart, $monthEnd], $sessionParams)
            )['c'] ?? 0);
            $pipeline[] = [
                'month_label' => date('M', strtotime($monthStart)),
                'total' => $count,
            ];
            $pipelineMax = max($pipelineMax, $count);
        }

        $stats = [
            'approved' => $approved,
            'pending' => $pending,
            'total_applications' => $totalApplications,
            'students' => $students,
            'total_seats' => $totalSeats,
            'unread_inquiries' => $unreadInquiries,
            'inquiries_today' => $inquiriesToday,
            'inquiries_delta' => max(0, $inquiriesToday - $inquiriesYesterday),
            'fees_paid' => $feesPaid,
            'fees_total' => $feesTotal,
            'admission_fill_pct' => $totalSeats > 0 ? min(100, round(($approved / $totalSeats) * 100, 1)) : 0,
            'fees_pct' => $feesTotal > 0 ? min(100, round(($feesPaid / $feesTotal) * 100)) : 0,
        ];

        $recentContacts = Database::fetchAll(
            'SELECT id, name, email, phone, trade_interest, message, created_at, is_read
             FROM contact ORDER BY created_at DESC LIMIT 5'
        );

        if ($session !== '') {
            $recent = Database::fetchAll(
                'SELECT id, name, trade, mobile, status, session, created_at
                 FROM admissions WHERE session = ? ORDER BY created_at DESC LIMIT 6',
                [$session]
            );
        } else {
            $recent = Database::fetchAll(
                'SELECT id, name, trade, mobile, status, session, created_at FROM admissions ORDER BY created_at DESC LIMIT 6'
            );
        }

        View::render('admin/dashboard', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recent' => $recent,
            'recentContacts' => $recentContacts,
            'pipeline' => $pipeline,
            'pipelineMax' => $pipelineMax,
            'filterSession' => $session,
            'sessions' => academic_session_options(),
        ], 'admin');
    }
}
