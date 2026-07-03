<?php

namespace App\Controllers;

use App\Core\Auth;
use App\Core\Database;
use App\Core\Upload;
use App\Core\View;
use App\Models\SiteData;

class AdminSiteController
{
    // --- Hero ---
    public static function hero(): void
    {
        Auth::require();
        $hero = Database::fetch('SELECT * FROM hero_section ORDER BY id LIMIT 1') ?: [];
        View::render('admin/cms/hero', ['title' => 'Homepage Hero', 'hero' => $hero], 'admin');
    }

    public static function heroSave(): void
    {
        Auth::require();
        verify_csrf();
        $bg = Upload::save($_FILES['background_image'] ?? [], 'hero');
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'subtitle' => trim($_POST['subtitle'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'cta_text' => trim($_POST['cta_text'] ?? ''),
            'cta_link' => trim($_POST['cta_link'] ?? ''),
            'cta2_text' => trim($_POST['cta2_text'] ?? ''),
            'cta2_link' => trim($_POST['cta2_link'] ?? ''),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ];
        if ($bg) {
            $data['background_image'] = $bg;
        }
        $row = Database::fetch('SELECT id FROM hero_section ORDER BY id LIMIT 1');
        if ($row) {
            Database::update('hero_section', $data, 'id = ?', [$row['id']]);
        } else {
            Database::insert('hero_section', $data);
        }
        flash('success', 'Hero section updated.');
        redirect('admin/hero');
    }

    // --- Flash news ---
    public static function flashNews(): void
    {
        Auth::require();
        View::render('admin/cms/flash-news', [
            'title' => 'News Ticker',
            'items' => Database::fetchAll('SELECT * FROM flash_news ORDER BY order_index, id DESC'),
        ], 'admin');
    }

    public static function flashNewsSave(): void
    {
        Auth::require();
        verify_csrf();
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'link' => trim($_POST['link'] ?? ''),
            'order_index' => (int) ($_POST['order_index'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ];
        if (!empty($_POST['id'])) {
            Database::update('flash_news', $data, 'id = ?', [(int) $_POST['id']]);
        } else {
            Database::insert('flash_news', $data);
        }
        flash('success', 'News item saved.');
        redirect('admin/flash-news');
    }

    public static function flashNewsDelete(int $id): void
    {
        Auth::require();
        verify_csrf();
        Database::delete('flash_news', 'id = ?', [$id]);
        flash('success', 'Deleted.');
        redirect('admin/flash-news');
    }

    // --- Trades ---
    public static function trades(): void
    {
        Auth::require();
        View::render('admin/cms/trades', [
            'title' => 'Trade Courses',
            'items' => Database::fetchAll('SELECT * FROM trades ORDER BY name'),
        ], 'admin');
    }

    public static function tradeEdit(?int $id = null): void
    {
        Auth::require();
        $trade = $id ? Database::fetch('SELECT * FROM trades WHERE id = ?', [$id]) : null;
        View::render('admin/cms/trade-edit', [
            'title' => $trade ? 'Edit Trade' : 'Add Trade',
            'trade' => $trade,
        ], 'admin');
    }

    public static function tradeSave(): void
    {
        Auth::require();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $slug = trim($_POST['slug'] ?? '');
        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', trim($_POST['name'] ?? '')));
        }
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'slug' => $slug,
            'category' => trim($_POST['category'] ?? 'Engineering'),
            'description' => trim($_POST['description'] ?? ''),
            'duration' => trim($_POST['duration'] ?? '2 Years'),
            'eligibility' => trim($_POST['eligibility'] ?? '10th Pass'),
            'seats' => trim($_POST['seats'] ?? '60'),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
            'syllabus_json' => trim($_POST['syllabus_json'] ?? '') ?: null,
            'careers_json' => trim($_POST['careers_json'] ?? '') ?: null,
        ];
        $img = Upload::save($_FILES['image'] ?? [], 'trade');
        if ($img) {
            $data['image'] = $img;
        }
        $syllabusPdf = Upload::save($_FILES['syllabus_pdf'] ?? [], 'syllabus');
        if ($syllabusPdf) {
            $data['syllabus_pdf'] = $syllabusPdf;
        }
        $prospectus = Upload::save($_FILES['prospectus_pdf'] ?? [], 'prospectus');
        if ($prospectus) {
            $data['prospectus_pdf'] = $prospectus;
        }
        if ($id) {
            Database::update('trades', $data, 'id = ?', [$id]);
        } else {
            Database::insert('trades', $data);
        }
        flash('success', 'Trade saved.');
        redirect('admin/trades');
    }

    public static function tradeDelete(int $id): void
    {
        Auth::require();
        verify_csrf();
        Database::delete('trades', 'id = ?', [$id]);
        flash('success', 'Trade deleted.');
        redirect('admin/trades');
    }

    // --- Menus ---
    public static function menus(): void
    {
        Auth::require();
        ensure_important_links_menu();
        View::render('admin/cms/menus', [
            'title' => 'Navigation Menus',
            'items' => Database::fetchAll(
                'SELECT * FROM menus ORDER BY COALESCE(parent_id, 0), order_index, id'
            ),
        ], 'admin');
    }

    public static function menuSave(): void
    {
        Auth::require();
        verify_csrf();
        $id = (int) ($_POST['id'] ?? 0);
        $parentId = trim($_POST['parent_id'] ?? '');
        $parentId = $parentId === '' ? null : (int) $parentId;
        if ($parentId && $id && $parentId === $id) {
            flash('error', 'A menu cannot be its own parent.');
            redirect('admin/menus');
        }
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'url' => trim($_POST['url'] ?? '/'),
            'parent_id' => $parentId,
            'order_index' => (int) ($_POST['order_index'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ];
        if ($id) {
            Database::update('menus', $data, 'id = ?', [$id]);
        } else {
            Database::insert('menus', $data);
        }
        flash('success', 'Menu saved.');
        redirect('admin/menus');
    }

    public static function menuDelete(int $id): void
    {
        Auth::require();
        verify_csrf();
        Database::delete('menus', 'parent_id = ?', [$id]);
        Database::delete('menus', 'id = ?', [$id]);
        flash('success', 'Menu deleted.');
        redirect('admin/menus');
    }

    // --- Footer links ---
    public static function footerLinks(): void
    {
        Auth::require();
        View::render('admin/cms/footer-links', [
            'title' => 'Footer Links',
            'items' => Database::fetchAll('SELECT * FROM footer_links ORDER BY order_index, id'),
        ], 'admin');
    }

    public static function footerLinkSave(): void
    {
        Auth::require();
        verify_csrf();
        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'url' => trim($_POST['url'] ?? '/'),
            'category' => trim($_POST['category'] ?? 'quick_links'),
            'order_index' => (int) ($_POST['order_index'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
        ];
        if (!empty($_POST['id'])) {
            Database::update('footer_links', $data, 'id = ?', [(int) $_POST['id']]);
        } else {
            Database::insert('footer_links', $data);
        }
        flash('success', 'Footer link saved.');
        redirect('admin/footer-links');
    }

    public static function footerLinkDelete(int $id): void
    {
        Auth::require();
        verify_csrf();
        Database::delete('footer_links', 'id = ?', [$id]);
        redirect('admin/footer-links');
    }

    // --- Faculty ---
    public static function faculty(): void
    {
        Auth::require();
        View::render('admin/cms/faculty', [
            'title' => 'Faculty',
            'items' => Database::fetchAll('SELECT * FROM faculty ORDER BY display_order, id'),
        ], 'admin');
    }

    public static function facultySave(): void
    {
        Auth::require();
        verify_csrf();
        $data = [
            'name' => trim($_POST['name'] ?? ''),
            'designation' => trim($_POST['designation'] ?? ''),
            'department' => trim($_POST['department'] ?? ''),
            'qualification' => trim($_POST['qualification'] ?? ''),
            'experience' => trim($_POST['experience'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'bio' => trim($_POST['bio'] ?? ''),
            'display_order' => (int) ($_POST['display_order'] ?? 0),
            'is_active' => !empty($_POST['is_active']) ? 1 : 0,
            'is_principal' => !empty($_POST['is_principal']) ? 1 : 0,
        ];
        $img = Upload::save($_FILES['image'] ?? [], 'faculty');
        if ($img) {
            $data['image'] = $img;
        }
        if (!empty($_POST['id'])) {
            Database::update('faculty', $data, 'id = ?', [(int) $_POST['id']]);
        } else {
            Database::insert('faculty', $data);
        }
        flash('success', 'Faculty saved.');
        redirect('admin/faculty');
    }

    public static function facultyDelete(int $id): void
    {
        Auth::require();
        verify_csrf();
        Database::delete('faculty', 'id = ?', [$id]);
        redirect('admin/faculty');
    }
}
