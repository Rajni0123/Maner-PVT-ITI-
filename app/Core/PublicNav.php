<?php

namespace App\Core;

use App\Models\SiteData;

class PublicNav
{
    /** @return array<int,array<string,mixed>> */
    public static function menu(): array
    {
        $header = SiteData::header();
        $cmsMenus = SiteData::menus();
        $trades = SiteData::activeTrades();
        $tradeSlugs = array_map(static fn(array $t): string => (string) ($t['slug'] ?? ''), $trades);

        $fromCms = static function (array $needles, string $fallback) use ($cmsMenus): string {
            foreach ($cmsMenus as $menu) {
                $title = strtolower((string) ($menu['title'] ?? ''));
                foreach ($needles as $needle) {
                    if ($needle !== '' && str_contains($title, strtolower($needle))) {
                        return menu_url((string) ($menu['url'] ?? '/'));
                    }
                }
            }
            if ($fallback === '' || $fallback === '#') {
                return '#';
            }
            if (preg_match('#^https?://#i', $fallback)) {
                return $fallback;
            }
            return menu_url($fallback);
        };

        $tradeLink = static function (string $slug, string $title, string $icon) use ($tradeSlugs): array {
            $url = in_array($slug, $tradeSlugs, true)
                ? site_url('trades/' . $slug)
                : site_url('trades');
            return [
                'key' => $slug,
                'title' => $title,
                'url' => $url,
                'icon' => $icon,
            ];
        };

        $ncvt = trim((string) ($header['ncvt_mis_link'] ?? 'https://ncvtmis.gov.in'));
        $studentPortal = trim((string) ($header['student_portal_link'] ?? '#'));
        if ($studentPortal === '') {
            $studentPortal = '#';
        }
        $detHunnarUrl = $fromCms(['det hunnar', 'hunnar'], '#');

        return [
            [
                'type' => 'link',
                'key' => 'home',
                'title' => 'Home',
                'url' => site_url(),
                'icon' => 'home',
            ],
            [
                'type' => 'link',
                'key' => 'about',
                'title' => 'About',
                'url' => site_url('about'),
                'icon' => 'info',
            ],
            [
                'type' => 'link',
                'key' => 'admission',
                'title' => 'Admission',
                'url' => site_url('admission-process'),
                'icon' => 'how_to_reg',
            ],
            [
                'type' => 'dropdown',
                'key' => 'trades',
                'title' => 'Trades',
                'icon' => 'construction',
                'items' => [
                    $tradeLink('electrician', 'Electrician', 'bolt'),
                    $tradeLink('fitter', 'Fitter', 'build'),
                    $tradeLink('welder', 'Welder', 'flare'),
                    $tradeLink('copa', 'COPA', 'computer'),
                    $tradeLink('mechanic-diesel', 'Mechanic Diesel', 'local_gas_station'),
                    [
                        'key' => 'all-trades',
                        'title' => 'All Trades',
                        'url' => site_url('trades'),
                        'icon' => 'apps',
                    ],
                ],
            ],
            [
                'type' => 'dropdown',
                'key' => 'student-zone',
                'title' => 'Student Zone',
                'icon' => 'groups',
                'items' => [
                    [
                        'key' => 'ncvt-mis',
                        'title' => (string) ($header['ncvt_mis_text'] ?? 'NCVT MIS'),
                        'url' => $ncvt !== '' ? $ncvt : 'https://ncvtmis.gov.in',
                        'icon' => 'verified',
                        'external' => true,
                    ],
                    [
                        'key' => 'bharat-skill',
                        'title' => 'Bharat Skill',
                        'url' => $fromCms(['bharat skill', 'bharatskill'], 'https://www.bharatskills.gov.in'),
                        'icon' => 'workspace_premium',
                        'external' => true,
                    ],
                    [
                        'key' => 'det-hunnar',
                        'title' => 'DET Hunnar',
                        'url' => $detHunnarUrl,
                        'icon' => 'school',
                        'external' => str_starts_with($detHunnarUrl, 'http'),
                    ],
                    [
                        'key' => 'student-login',
                        'title' => (string) ($header['student_portal_text'] ?? 'Student Login'),
                        'url' => $studentPortal,
                        'icon' => 'login',
                        'external' => preg_match('#^https?://#i', $studentPortal) === 1,
                    ],
                    [
                        'key' => 'downloads',
                        'title' => 'Downloads',
                        'url' => site_url('notices'),
                        'icon' => 'download',
                    ],
                ],
            ],
            [
                'type' => 'dropdown',
                'key' => 'examination',
                'title' => 'Examination',
                'icon' => 'assignment',
                'items' => [
                    [
                        'key' => 'higher-level',
                        'title' => 'ITI Higher Level Exam',
                        'url' => $fromCms(['higher level', 'iti higher'], site_url('notices')),
                        'icon' => 'military_tech',
                    ],
                    [
                        'key' => 'practical-exam',
                        'title' => 'Practical Exam',
                        'url' => $fromCms(['practical exam'], site_url('notices')),
                        'icon' => 'handyman',
                    ],
                    [
                        'key' => 'admit-card',
                        'title' => 'Admit Card',
                        'url' => $fromCms(['admit card'], site_url('notices')),
                        'icon' => 'badge',
                    ],
                    [
                        'key' => 'result',
                        'title' => 'Result',
                        'url' => site_url('results'),
                        'icon' => 'emoji_events',
                    ],
                    [
                        'key' => 'time-table',
                        'title' => 'Time Table',
                        'url' => $fromCms(['time table', 'timetable'], site_url('notices')),
                        'icon' => 'calendar_month',
                    ],
                ],
            ],
            [
                'type' => 'dropdown',
                'key' => 'resources',
                'title' => 'Resources',
                'icon' => 'folder_open',
                'items' => [
                    [
                        'key' => 'fee-structure',
                        'title' => 'Fee Structure',
                        'url' => site_url('fee-structure'),
                        'icon' => 'payments',
                    ],
                    [
                        'key' => 'prospectus',
                        'title' => 'Prospectus',
                        'url' => site_url('fee-structure'),
                        'icon' => 'menu_book',
                    ],
                    [
                        'key' => 'gallery',
                        'title' => 'Gallery',
                        'url' => site_url('infrastructure'),
                        'icon' => 'photo_library',
                    ],
                    [
                        'key' => 'notices',
                        'title' => 'Notices',
                        'url' => site_url('notices'),
                        'icon' => 'campaign',
                    ],
                    [
                        'key' => 'faq',
                        'title' => 'FAQ',
                        'url' => site_url('contact'),
                        'icon' => 'help',
                    ],
                ],
            ],
            [
                'type' => 'link',
                'key' => 'contact',
                'title' => 'Contact',
                'url' => site_url('contact'),
                'icon' => 'call',
            ],
        ];
    }

    public static function activeGroup(?string $navActive = null): string
    {
        if ($navActive !== null && $navActive !== '') {
            return self::normalizeGroupKey($navActive);
        }

        $path = request_path();
        if ($path === '/' || $path === '') {
            return 'home';
        }
        if (str_starts_with($path, '/about')) {
            return 'about';
        }
        if (str_contains($path, 'admission') || str_contains($path, 'apply-admission')) {
            return 'admission';
        }
        if (str_starts_with($path, '/trades')) {
            return 'trades';
        }
        if (str_starts_with($path, '/bscc')) {
            return 'student-zone';
        }
        if (str_starts_with($path, '/results')) {
            return 'examination';
        }
        if (str_starts_with($path, '/fee-structure')
            || str_starts_with($path, '/notices')
            || str_starts_with($path, '/infrastructure')
            || str_starts_with($path, '/faculty')) {
            return 'resources';
        }
        if (str_starts_with($path, '/contact')) {
            return 'contact';
        }

        return '';
    }

    public static function normalizeGroupKey(string $key): string
    {
        $map = [
            'courses' => 'trades',
            'bscc' => 'student-zone',
        ];
        return $map[$key] ?? $key;
    }

    /** @param array<string,mixed> $item */
    public static function isItemActive(array $item, string $activeGroup): bool
    {
        $key = (string) ($item['key'] ?? '');
        if ($key === $activeGroup) {
            return true;
        }
        if (($item['type'] ?? '') === 'dropdown') {
            foreach ($item['items'] ?? [] as $child) {
                if (self::isChildActive($child, $activeGroup)) {
                    return true;
                }
            }
        }
        return false;
    }

    /** @param array<string,mixed> $child */
    public static function isChildActive(array $child, string $activeGroup): bool
    {
        $path = request_path();
        $url = (string) ($child['url'] ?? '');
        if ($url === '' || $url === '#') {
            return false;
        }

        $childPath = parse_url($url, PHP_URL_PATH) ?: '';
        $childPath = '/' . trim($childPath, '/');
        if ($childPath !== '/' && str_starts_with($path, rtrim($childPath, '/'))) {
            return true;
        }

        $key = (string) ($child['key'] ?? '');
        if ($activeGroup === 'trades' && in_array($key, ['electrician', 'fitter', 'welder', 'copa', 'mechanic-diesel', 'all-trades'], true)) {
            return str_starts_with($path, '/trades');
        }
        if ($activeGroup === 'examination' && $key === 'result' && str_starts_with($path, '/results')) {
            return true;
        }
        if ($activeGroup === 'resources') {
            if ($key === 'fee-structure' && str_starts_with($path, '/fee-structure')) {
                return true;
            }
            if ($key === 'notices' && str_starts_with($path, '/notices')) {
                return true;
            }
            if ($key === 'gallery' && str_starts_with($path, '/infrastructure')) {
                return true;
            }
            if ($key === 'faq' && str_starts_with($path, '/contact')) {
                return true;
            }
        }
        if ($activeGroup === 'student-zone' && $key === 'downloads' && str_starts_with($path, '/notices')) {
            return true;
        }

        return false;
    }
}
