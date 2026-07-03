<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\View;
use App\Models\SiteData;

class PublicController
{
    public static function home(): void
    {
        $settings = SiteData::settings();
        View::render('public/home', [
            'title' => $settings['seo_title'] ?? 'Maner Private ITI - Master Your Trade',
            'hero' => SiteData::hero(),
            'flashNews' => SiteData::flashNews(),
            'notices' => Database::fetchAll('SELECT * FROM notices ORDER BY created_at DESC LIMIT 5'),
            'settings' => $settings,
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'footerLinks' => SiteData::footerLinks(),
        ], '');
    }

    public static function about(): void
    {
        $page = Database::fetch('SELECT * FROM about_page ORDER BY id LIMIT 1') ?: [];
        $settings = SiteData::settings();
        $gallery = Database::fetchAll(
            'SELECT * FROM gallery ORDER BY created_at DESC LIMIT 4'
        );
        View::render('public/about', [
            'title' => 'About Us | Maner Private ITI',
            'page' => $page,
            'settings' => $settings,
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'trades' => SiteData::activeTrades(),
            'gallery' => $gallery,
        ], '');
    }

    public static function trades(): void
    {
        View::render('public/trades', [
            'title' => 'Courses | Maner Private ITI - Empowering Technical Careers',
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function bsccInfo(): void
    {
        View::render('public/bscc', [
            'title' => 'BSCC - Maner Private ITI | Study with Zero Upfront Cost',
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function tradeDetail(string $slug): void
    {
        $trade = Database::fetch('SELECT * FROM trades WHERE slug = ? AND is_active = 1', [$slug]);
        if (!$trade) {
            http_response_code(404);
            View::render('public/404', ['title' => 'Not Found'], '');
            return;
        }
        if ($slug === 'fitter') {
            View::render('public/fitter-syllabus', [
                'title' => 'Fitter Trade Syllabus | Maner Private ITI',
                'syllabusPdf' => !empty($trade['syllabus_pdf']) ? upload_url($trade['syllabus_pdf']) : '',
            ], '');
            return;
        }
        View::render('public/trade-detail', [
            'title' => $trade['name'] . ' Trade Syllabus | Maner Private ITI',
            'trade' => $trade,
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function admissionProcess(): void
    {
        View::render('public/admission-process', [
            'title' => 'Requirements & Career Pathways | Maner Private ITI',
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function feeStructure(): void
    {
        $settings = SiteData::settings();
        $feeJson = [];
        if (!empty($settings['fee_structure_json'])) {
            $decoded = json_decode($settings['fee_structure_json'], true);
            if (is_array($decoded)) {
                $feeJson = $decoded;
            }
        }
        View::render('public/fee-structure', [
            'title' => 'Fee Structure | Maner Private ITI',
            'pdf' => SiteData::setting('fee_structure_pdf'),
            'feeData' => $feeJson,
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => $settings,
        ], '');
    }

    public static function feeStructurePdf(): void
    {
        \App\Core\FeeStructurePdf::download();
    }

    public static function faculty(): void
    {
        $faculty = Database::fetchAll('SELECT * FROM faculty WHERE is_active = 1 ORDER BY is_principal DESC, display_order');
        View::render('public/faculty', ['title' => 'Faculty', 'faculty' => $faculty]);
    }

    public static function infrastructure(): void
    {
        $images = Database::fetchAll('SELECT * FROM gallery ORDER BY created_at DESC');
        View::render('public/infrastructure', ['title' => 'Infrastructure', 'images' => $images]);
    }

    public static function notices(): void
    {
        $notices = Database::fetchAll('SELECT * FROM notices ORDER BY created_at DESC');
        View::render('public/notices', [
            'title' => 'Latest News & Updates',
            'notices' => $notices,
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function results(): void
    {
        $results = Database::fetchAll('SELECT * FROM results ORDER BY created_at DESC');
        View::render('public/results', ['title' => 'Results', 'results' => $results]);
    }

    public static function contact(): void
    {
        View::render('public/contact', [
            'title' => 'Contact Us - Maner Private ITI',
            'trades' => SiteData::activeTrades(),
            'header' => SiteData::header(),
            'footer' => SiteData::footer(),
            'settings' => SiteData::settings(),
        ], '');
    }

    public static function contactSubmit(): void
    {
        verify_csrf();
        ensure_contact_schema();

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $trade = trim($_POST['trade_interest'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (!$name || !$email || !$phone || !$message) {
            set_old($_POST);
            flash('error', 'Please fill all required fields.');
            redirect('contact');
        }

        if ($trade !== '' && stripos($message, 'Interested Trade:') === false) {
            $message = 'Interested Trade: ' . $trade . "\n\n" . $message;
        }

        Database::insert('contact', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'inquiry_type' => 'Admission Inquiry',
            'trade_interest' => $trade ?: null,
        ]);
        clear_old();
        flash('success', 'Thank you! Your admission inquiry has been sent. We will contact you shortly.');
        redirect('contact');
    }

    public static function newsletterSubscribe(): void
    {
        header('Content-Type: application/json');

        if (!newsletter_enabled()) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'message' => 'Newsletter subscriptions are currently disabled.']);
            exit;
        }

        $token = $_POST['_csrf'] ?? '';
        if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
            http_response_code(403);
            echo json_encode(['ok' => false, 'message' => 'Session expired. Please refresh the page and try again.']);
            exit;
        }

        ensure_newsletter_schema();

        $email = strtolower(trim($_POST['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(422);
            echo json_encode(['ok' => false, 'message' => 'Please enter a valid email address.']);
            exit;
        }

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $existing = Database::fetch(
            'SELECT * FROM newsletter_subscribers WHERE LOWER(email) = ? LIMIT 1',
            [$email]
        );

        if ($existing) {
            if ((int) ($existing['is_active'] ?? 0) === 1) {
                echo json_encode(['ok' => true, 'message' => 'You are already subscribed to our newsletter.']);
                exit;
            }

            Database::update('newsletter_subscribers', [
                'is_active' => 1,
                'ip_address' => $ip,
                'source' => 'footer',
                'subscribed_at' => date('Y-m-d H:i:s'),
                'unsubscribed_at' => null,
            ], 'id = ?', [(int) $existing['id']]);

            echo json_encode(['ok' => true, 'message' => 'Welcome back! You have been re-subscribed to our newsletter.']);
            exit;
        }

        Database::insert('newsletter_subscribers', [
            'email' => $email,
            'is_active' => 1,
            'ip_address' => $ip,
            'source' => 'footer',
        ]);

        echo json_encode(['ok' => true, 'message' => 'Thank you for subscribing to our newsletter!']);
        exit;
    }
}
