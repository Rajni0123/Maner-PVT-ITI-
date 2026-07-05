<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Upload;
use App\Core\View;
use App\Models\SiteData;

class AdminCmsController
{
    // --- Notices ---
    public static function notices(): void
    {
        Auth::require();
        View::render('admin/cms/notices', [
            'title' => 'Notices',
            'items' => Database::fetchAll('SELECT * FROM notices ORDER BY created_at DESC'),
        ], 'admin');
    }

    public static function noticeSave(): void
    {
        Auth::require();
        verify_csrf();
        $pdf = Upload::save($_FILES['pdf'] ?? [], 'notice');
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];
        if ($pdf) {
            $data['pdf'] = $pdf;
        }
        if (!empty($_POST['id'])) {
            Database::update('notices', $data, 'id = ?', [(int) $_POST['id']]);
        } else {
            Database::insert('notices', $data);
        }
        flash('success', 'Notice saved.');
        redirect('admin/notices');
    }

    public static function noticeDelete(int $id): void
    {
        Auth::require();
        Database::delete('notices', 'id = ?', [$id]);
        flash('success', 'Deleted.');
        redirect('admin/notices');
    }

    // --- Gallery ---
    public static function gallery(): void
    {
        Auth::require();
        View::render('admin/cms/gallery', [
            'title' => 'Gallery',
            'items' => Database::fetchAll('SELECT * FROM gallery ORDER BY created_at DESC'),
        ], 'admin');
    }

    public static function gallerySave(): void
    {
        Auth::require();
        verify_csrf();
        $img = Upload::save($_FILES['image'] ?? [], 'gallery');
        if (!$img) {
            flash('error', 'Image required.');
            redirect('admin/gallery');
        }
        Database::insert('gallery', [
            'image' => $img,
            'category' => trim($_POST['category'] ?? 'General'),
        ]);
        flash('success', 'Image uploaded.');
        redirect('admin/gallery');
    }

    public static function galleryDelete(int $id): void
    {
        Auth::require();
        Database::delete('gallery', 'id = ?', [$id]);
        redirect('admin/gallery');
    }

    // --- Results ---
    public static function results(): void
    {
        Auth::require();
        View::render('admin/cms/results', [
            'title' => 'Results',
            'items' => Database::fetchAll('SELECT * FROM results ORDER BY created_at DESC'),
        ], 'admin');
    }

    public static function resultSave(): void
    {
        Auth::require();
        verify_csrf();
        $pdf = Upload::save($_FILES['pdf'] ?? [], 'result');
        if (!$pdf && empty($_POST['id'])) {
            flash('error', 'PDF required.');
            redirect('admin/results');
        }
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'trade' => trim($_POST['trade'] ?? ''),
            'year' => trim($_POST['year'] ?? ''),
        ];
        if ($pdf) {
            $data['pdf'] = $pdf;
        }
        if (!empty($_POST['id'])) {
            if (empty($data['pdf'])) {
                unset($data['pdf']);
            }
            Database::update('results', $data, 'id = ?', [(int) $_POST['id']]);
        } else {
            $data['pdf'] = $pdf;
            Database::insert('results', $data);
        }
        flash('success', 'Result saved.');
        redirect('admin/results');
    }

    public static function resultDelete(int $id): void
    {
        Auth::require();
        Database::delete('results', 'id = ?', [$id]);
        redirect('admin/results');
    }

    // --- Sessions ---
    public static function sessions(): void
    {
        Auth::require();
        $items = Database::fetchAll('SELECT * FROM sessions ORDER BY start_year DESC');
        View::render('admin/cms/sessions', [
            'title' => 'Sessions',
            'items' => $items,
            'sessionStats' => academic_session_stats(),
        ], 'admin');
    }

    public static function sessionSave(): void
    {
        Auth::require();
        verify_csrf();
        $data = [
            'session_name' => trim($_POST['session_name'] ?? ''),
            'start_year' => (int) ($_POST['start_year'] ?? 0),
            'end_year' => (int) ($_POST['end_year'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ];
        if (!empty($_POST['id'])) {
            Database::update('sessions', $data, 'id = ?', [(int) $_POST['id']]);
        } else {
            Database::insert('sessions', $data);
        }
        redirect('admin/sessions');
    }

    public static function sessionDelete(int $id): void
    {
        Auth::require();
        verify_csrf();
        Database::delete('sessions', 'id = ?', [$id]);
        flash('success', 'Session deleted.');
        redirect('admin/sessions');
    }

    // --- Settings ---
    public static function settings(): void
    {
        Auth::require();
        View::render('admin/cms/settings', [
            'title' => 'Site Settings',
            'settings' => SiteData::settings(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
        ], 'admin');
    }

    public static function settingsSave(): void
    {
        Auth::require();
        verify_csrf();
        foreach ($_POST['settings'] ?? [] as $key => $value) {
            $exists = Database::fetch('SELECT id FROM site_settings WHERE setting_key = ?', [$key]);
            if ($exists) {
                Database::update('site_settings', ['setting_value' => $value], 'setting_key = ?', [$key]);
            } else {
                Database::insert('site_settings', ['setting_key' => $key, 'setting_value' => $value]);
            }
        }
        $feePdf = Upload::save($_FILES['fee_structure_pdf'] ?? [], 'fee');
        if ($feePdf) {
            $exists = Database::fetch('SELECT id FROM site_settings WHERE setting_key = ?', ['fee_structure_pdf']);
            if ($exists) {
                Database::update('site_settings', ['setting_value' => $feePdf], 'setting_key = ?', ['fee_structure_pdf']);
            } else {
                Database::insert('site_settings', ['setting_key' => 'fee_structure_pdf', 'setting_value' => $feePdf]);
            }
        }
        $header = [
            'phone' => $_POST['phone'] ?? '',
            'email' => $_POST['header_email'] ?? '',
            'logo_text' => $_POST['logo_text'] ?? '',
            'tagline' => $_POST['tagline'] ?? '',
            'student_portal_link' => $_POST['student_portal_link'] ?? '',
            'student_portal_text' => $_POST['student_portal_text'] ?? '',
            'ncvt_mis_link' => $_POST['ncvt_mis_link'] ?? '',
            'ncvt_mis_text' => $_POST['ncvt_mis_text'] ?? '',
        ];
        $h = Database::fetch('SELECT id FROM header_settings LIMIT 1');
        if ($h) {
            Database::update('header_settings', $header, 'id = ?', [$h['id']]);
        } else {
            Database::insert('header_settings', $header);
        }
        $footer = [
            'about_text' => $_POST['about_text'] ?? '',
            'address' => $_POST['address'] ?? '',
            'phone' => $_POST['footer_phone'] ?? '',
            'email' => $_POST['footer_email'] ?? '',
            'copyright_text' => $_POST['copyright_text'] ?? '',
            'privacy_link' => $_POST['privacy_link'] ?? '',
            'terms_link' => $_POST['terms_link'] ?? '',
            'facebook_link' => $_POST['facebook_link'] ?? '',
            'youtube_link' => $_POST['youtube_link'] ?? '',
            'linkedin_link' => $_POST['linkedin_link'] ?? '',
        ];
        $f = Database::fetch('SELECT id FROM footer_settings LIMIT 1');
        if ($f) {
            Database::update('footer_settings', $footer, 'id = ?', [$f['id']]);
        } else {
            Database::insert('footer_settings', $footer);
        }
        flash('success', 'Settings saved.');
        redirect('admin/settings');
    }

    // --- Contact messages ---
    public static function contacts(): void
    {
        Auth::require();
        ensure_contact_schema();
        \App\Core\AdminNotifications::markContactsRead();
        View::render('admin/cms/contacts', [
            'title' => 'Admission Inquiries',
            'items' => Database::fetchAll('SELECT * FROM contact ORDER BY created_at DESC'),
        ], 'admin');
    }
}
