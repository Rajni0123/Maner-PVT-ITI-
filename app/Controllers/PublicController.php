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
        View::render('public/notices', ['title' => 'Notice Board', 'notices' => $notices]);
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

    public static function enquirySubmit(): void
    {
        $isAjax = (!empty($_POST['ajax']) && $_POST['ajax'] === '1')
            || (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
                && strtolower((string) $_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        $respond = static function (bool $ok, string $message, int $code = 200) use ($isAjax): void {
            if ($isAjax) {
                http_response_code($code);
                header('Content-Type: application/json; charset=UTF-8');
                echo json_encode(['ok' => $ok, 'message' => $message]);
                exit;
            }
            flash($ok ? 'success' : 'error', $message);
            redirect($ok ? '' : 'contact');
        };

        $token = $_POST['_csrf'] ?? '';
        if (!$token || !hash_equals($_SESSION['_csrf'] ?? '', $token)) {
            $respond(false, 'Invalid security token. Please refresh and try again.', 403);
        }

        ensure_contact_schema();

        $name = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $course = trim($_POST['course'] ?? '');
        $subject = trim($_POST['subject'] ?? $_POST['trade_interest'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($name === '' || $phone === '' || $course === '' || $subject === '') {
            $respond(false, 'Please fill all fields.', 422);
        }

        $digits = preg_replace('/\D/', '', $phone);
        if (strlen($digits) < 10) {
            $respond(false, 'Enter a valid 10-digit mobile number.', 422);
        }

        if ($email === '') {
            $email = 'enquiry+' . $digits . '@visitor.local';
        }

        $message = "Admission enquiry via website popup.\n"
            . "Course: {$course}\n"
            . "Subject / Trade: {$subject}";

        Database::insert('contact', [
            'name' => mb_strtoupper($name, 'UTF-8'),
            'email' => $email,
            'phone' => $phone,
            'message' => $message,
            'inquiry_type' => 'Admission Enquiry Popup',
            'trade_interest' => $subject,
        ]);

        $respond(true, 'Thank you! Your admission enquiry has been submitted. We will contact you shortly.');
    }
}
