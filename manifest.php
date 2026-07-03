<?php
require __DIR__ . '/bootstrap.php';

header('Content-Type: application/manifest+json; charset=utf-8');
header('Cache-Control: no-cache, must-revalidate');

$logoUrl = site_app_logo_url();
$logoFile = site_branding_file('app_logo') ?: site_branding_file('site_favicon');
$logoType = $logoFile !== '' ? branding_mime_type($logoFile) : 'image/svg+xml';

$header = [];
try {
    $header = \App\Models\SiteData::header();
} catch (\Throwable $e) {
}

$name = trim((string) ($header['logo_text'] ?? config('site_name', 'Maner Private ITI')));
if ($name === '' || $name === 'Maner Pvt ITI') {
    $name = 'Maner Private ITI';
}
$shortName = 'Maner ITI';

$manifest = [
    'name' => $name,
    'short_name' => $shortName,
    'description' => 'Official app of ' . $name . ' - Bihar\'s Leading Technical Institute. NCVT Affiliated.',
    'start_url' => site_url(),
    'scope' => rtrim(site_url(), '/') . '/',
    'display' => 'standalone',
    'orientation' => 'portrait',
    'background_color' => '#131b2e',
    'theme_color' => '#131b2e',
    'categories' => ['education'],
    'lang' => 'en',
    'icons' => [
        [
            'src' => $logoUrl,
            'sizes' => '192x192',
            'type' => $logoType,
            'purpose' => 'any',
        ],
        [
            'src' => $logoUrl,
            'sizes' => '512x512',
            'type' => $logoType,
            'purpose' => 'any maskable',
        ],
    ],
    'shortcuts' => [
        [
            'name' => 'Apply for Admission',
            'short_name' => 'Apply',
            'url' => site_url('apply-admission'),
            'icons' => [['src' => $logoUrl, 'sizes' => '96x96', 'type' => $logoType]],
        ],
        [
            'name' => 'Contact Us',
            'short_name' => 'Contact',
            'url' => site_url('contact'),
            'icons' => [['src' => $logoUrl, 'sizes' => '96x96', 'type' => $logoType]],
        ],
    ],
];

echo json_encode($manifest, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
