<?php

namespace App\Core;

class AdminNotifications
{
    public static function count(): int
    {
        ensure_contact_schema();
        $contacts = (int) (Database::fetch('SELECT COUNT(*) AS c FROM contact WHERE is_read = 0')['c'] ?? 0);
        $admissions = (int) (Database::fetch("SELECT COUNT(*) AS c FROM admissions WHERE status = 'Pending'")['c'] ?? 0);

        return $contacts + $admissions;
    }

    public static function items(int $limit = 15): array
    {
        ensure_contact_schema();
        $limit = max(1, min(30, $limit));
        $items = [];

        $contacts = Database::fetchAll(
            'SELECT id, name, trade_interest, message, created_at, inquiry_type
             FROM contact WHERE is_read = 0 ORDER BY created_at DESC LIMIT ' . $limit
        );
        foreach ($contacts as $row) {
            $items[] = [
                'type' => 'inquiry',
                'id' => (int) $row['id'],
                'title' => $row['name'],
                'text' => str_limit($row['message'], 90),
                'meta' => $row['trade_interest'] ?: ($row['inquiry_type'] ?? 'Admission Inquiry'),
                'url' => site_url('admin/contacts'),
                'time' => $row['created_at'],
                'time_label' => format_date($row['created_at']),
            ];
        }

        $admissions = Database::fetchAll(
            "SELECT id, name, trade, mobile, created_at FROM admissions WHERE status = 'Pending' ORDER BY created_at DESC LIMIT " . $limit
        );
        foreach ($admissions as $row) {
            $items[] = [
                'type' => 'admission',
                'id' => (int) $row['id'],
                'title' => $row['name'],
                'text' => 'New admission form submitted' . ($row['trade'] ? ' — ' . $row['trade'] : ''),
                'meta' => format_mobile($row['mobile'] ?? ''),
                'url' => site_url('admin/admissions/view/' . $row['id']),
                'time' => $row['created_at'],
                'time_label' => format_date($row['created_at']),
            ];
        }

        usort($items, static function (array $a, array $b): int {
            return strtotime($b['time'] ?? '') <=> strtotime($a['time'] ?? '');
        });

        return array_slice($items, 0, $limit);
    }

    public static function markContactsRead(): void
    {
        ensure_contact_schema();
        Database::query('UPDATE contact SET is_read = 1 WHERE is_read = 0');
    }

    public static function markContactRead(int $id): void
    {
        ensure_contact_schema();
        Database::update('contact', ['is_read' => 1], 'id = ?', [$id]);
    }
}
