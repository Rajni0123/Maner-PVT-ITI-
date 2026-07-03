<?php

namespace App\Core;

use PDO;
use Throwable;

class DatabaseRepair
{
    private static bool $ensured = false;

    public static function ensure(): void
    {
        if (self::$ensured || !is_installed()) {
            return;
        }
        self::$ensured = true;

        try {
            Database::connect();
        } catch (Throwable $e) {
            return;
        }

        try {
            Database::fetch('SELECT 1 FROM site_settings LIMIT 1');
            ensure_contact_schema();
            ensure_newsletter_schema();
            ensure_important_links_menu();
            self::ensureStaffTablesQuiet();
            return;
        } catch (Throwable $e) {
            if (!self::isMissingTableError($e)) {
                return;
            }
        }

        try {
            self::runFullRepair();
        } catch (Throwable $e) {
            error_log('Database repair failed: ' . $e->getMessage());
        }
    }

    /** @return array{ok:bool,messages:string[]} */
    public static function runFullRepair(): array
    {
        $messages = [];
        $pdo = Database::connect();

        $schemaFile = base_path('database/schema.sql');
        if (!is_file($schemaFile)) {
            throw new \RuntimeException('database/schema.sql not found.');
        }

        $sql = file_get_contents($schemaFile);
        $pdo->exec($sql);
        $messages[] = 'Database schema applied (CREATE TABLE IF NOT EXISTS).';

        ensure_contact_schema();
        $messages[] = 'Contact table columns verified.';

        ensure_newsletter_schema();
        $messages[] = 'Newsletter subscribers table verified.';

        ensure_important_links_menu();
        $messages[] = 'Important Links menu verified.';

        $staffFile = base_path('database/migrate-staff-salary.sql');
        if (is_file($staffFile)) {
            $pdo->exec(file_get_contents($staffFile));
            $messages[] = 'Staff salary tables verified.';
        }

        $seeded = self::seedDefaultsIfEmpty($pdo);
        if ($seeded) {
            $messages[] = 'Default site data seeded (settings, menus, trades).';
        } else {
            $messages[] = 'Existing data kept — only missing tables were created.';
        }

        return ['ok' => true, 'messages' => $messages];
    }

    private static function ensureStaffTablesQuiet(): void
    {
        $row = Database::fetch(
            "SELECT COUNT(*) AS c FROM information_schema.tables WHERE table_schema = DATABASE() AND table_name = 'staff'"
        );
        if ((int) ($row['c'] ?? 0) === 0) {
            $staffFile = base_path('database/migrate-staff-salary.sql');
            if (is_file($staffFile)) {
                Database::connect()->exec(file_get_contents($staffFile));
            }
        }
    }

    private static function isMissingTableError(Throwable $e): bool
    {
        $msg = $e->getMessage();
        return str_contains($msg, '1146') || str_contains($msg, "doesn't exist");
    }

    private static function seedDefaultsIfEmpty(PDO $pdo): bool
    {
        $count = (int) $pdo->query('SELECT COUNT(*) FROM site_settings')->fetchColumn();
        if ($count > 0) {
            return false;
        }

        $logoText = 'Maner Private ITI';
        $phone = '+91-9155401839';
        $email = 'manerpvtiti@gmail.com';
        $tagline = 'Industrial Training Institute (ITI)';

        $menus = [
            ['Home', '/'], ['About', '/about'], ['Trades', '/trades'], ['Admission', '/admission-process'],
            ['Fee Structure', '/fee-structure'], ['Contact', '/contact'],
        ];
        $i = 1;
        foreach ($menus as [$title, $url]) {
            $pdo->prepare('INSERT IGNORE INTO menus (title, url, order_index, is_active) VALUES (?,?,?,1)')
                ->execute([$title, $url, $i++]);
        }

        $pdo->prepare('INSERT IGNORE INTO header_settings (phone, email, logo_text, tagline, student_portal_link, student_portal_text, ncvt_mis_link, ncvt_mis_text)
            VALUES (?,?,?,?,"#","Student Portal","https://ncvtmis.gov.in","NCVT MIS")')
            ->execute([$phone, $email, $logoText, $tagline]);

        $pdo->prepare('INSERT IGNORE INTO footer_settings (about_text, address, phone, email, copyright_text)
            VALUES (?,?,?,?,?)')
            ->execute([
                'Premier private ITI for vocational training.',
                'Maner Mahinawan, Near Vishwakarma Mandir, Maner, Patna - 801108',
                $phone,
                $email,
                '© ' . $logoText . '. All Rights Reserved.',
            ]);

        $settings = [
            ['header_text', 'Admission Open — Apply online for Electrician & Fitter trades.'],
            ['principal_name', 'Principal'],
            ['principal_message', 'Welcome to ' . $logoText . '.'],
            ['seo_title', $logoText . ' - Technical Education Patna'],
            ['seo_description', 'Official website of ' . $logoText . ', Patna.'],
            ['mis_code', 'PR10001156'],
        ];
        foreach ($settings as [$k, $v]) {
            $pdo->prepare('INSERT IGNORE INTO site_settings (setting_key, setting_value) VALUES (?,?)')->execute([$k, $v]);
        }

        $year = (int) date('Y');
        for ($j = 0; $j < 3; $j++) {
            $sy = $year + $j;
            $ey = $sy + 2;
            $sn = "{$sy}-" . substr((string) $ey, 2);
            $pdo->prepare('INSERT IGNORE INTO sessions (session_name, start_year, end_year, is_active) VALUES (?,?,?,1)')
                ->execute([$sn, $sy, $ey]);
        }

        $pdo->exec("INSERT IGNORE INTO trades (name, slug, category, description, duration, eligibility, seats, is_active) VALUES
            ('Electrician','electrician','Engineering','Industrial electrician training program.','2 Years','10th Pass','60',1),
            ('Fitter','fitter','Engineering','Fitter trade vocational training.','2 Years','10th Pass','60',1)");

        $pdo->prepare('INSERT IGNORE INTO hero_section (title, subtitle, description, cta_text, cta_link, cta2_text, cta2_link, is_active) VALUES (?,?,?,?,?,?,?,1)')
            ->execute([
                $logoText,
                "Bihar's Leading Technical Institute",
                "Join Bihar's premier NCVT-affiliated institution.",
                'Apply Now',
                '/apply-admission',
                'Fee Structure',
                '/fee-structure',
            ]);

        return true;
    }
}
