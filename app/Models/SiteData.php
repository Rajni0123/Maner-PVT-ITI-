<?php

namespace App\Models;

use App\Core\Database;

class SiteData
{
    public static function header(): array
    {
        return Database::fetch('SELECT * FROM header_settings ORDER BY id LIMIT 1') ?: [
            'phone' => '+91-9155401839',
            'email' => 'manerpvtiti@gmail.com',
            'logo_text' => 'Maner Pvt ITI',
            'tagline' => 'Industrial Training Institute (ITI)',
            'student_portal_link' => '#',
            'student_portal_text' => 'Student Portal',
            'ncvt_mis_link' => 'https://ncvtmis.gov.in',
            'ncvt_mis_text' => 'NCVT MIS',
        ];
    }

    public static function footer(): array
    {
        return Database::fetch('SELECT * FROM footer_settings ORDER BY id LIMIT 1') ?: [];
    }

    public static function footerLinks(string $category = 'quick_links'): array
    {
        return Database::fetchAll(
            'SELECT * FROM footer_links WHERE category = ? AND is_active = 1 ORDER BY order_index',
            [$category]
        );
    }

    public static function menus(): array
    {
        return Database::fetchAll(
            'SELECT * FROM menus WHERE is_active = 1 ORDER BY order_index'
        );
    }

    public static function setting(string $key, $default = ''): string
    {
        $row = Database::fetch('SELECT setting_value FROM site_settings WHERE setting_key = ?', [$key]);
        return $row['setting_value'] ?? $default;
    }

    public static function settings(): array
    {
        $rows = Database::fetchAll('SELECT setting_key, setting_value FROM site_settings');
        $out = [];
        foreach ($rows as $r) {
            $out[$r['setting_key']] = $r['setting_value'];
        }
        return $out;
    }

    public static function hero(): ?array
    {
        return Database::fetch('SELECT * FROM hero_section WHERE is_active = 1 ORDER BY id DESC LIMIT 1');
    }

    public static function flashNews(): array
    {
        return Database::fetchAll(
            'SELECT * FROM flash_news WHERE is_active = 1 ORDER BY order_index, id DESC'
        );
    }

    public static function activeSessions(): array
    {
        return Database::fetchAll('SELECT * FROM sessions WHERE is_active = 1 ORDER BY start_year DESC');
    }

    public static function activeTrades(): array
    {
        return Database::fetchAll('SELECT * FROM trades WHERE is_active = 1 ORDER BY name');
    }
}
