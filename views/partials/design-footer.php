<?php
use App\Models\SiteData;

$footer = $footer ?? SiteData::footer();
$header = $header ?? SiteData::header();
$trades = $trades ?? SiteData::activeTrades();
$settings = $settings ?? SiteData::settings();

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}

$footerAbout = $footer['about_text'] ?? 'A premier industrial training institute dedicated to providing top-tier technical education and hands-on workshop experience to the youth of Bihar.';
$footerAddress = trim(str_replace("\n", ', ', $footer['address'] ?? 'Maner, Patna, Bihar - 801108'));
$footerPhone = $footer['phone'] ?? $header['phone'] ?? '+91 91554 01839';
$footerEmail = $footer['email'] ?? $header['email'] ?? 'info@maneriti.com';
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. All Rights Reserved.';
$misCode = $settings['mis_code'] ?? 'PR10001156';
$privacyUrl = $footer['privacy_link'] ?? site_url('contact');
$termsUrl = $footer['terms_link'] ?? site_url('contact');

$socialWeb = $footer['facebook_link'] ?? site_url();
$socialVideo = $footer['youtube_link'] ?? '#';
$socialGroups = $footer['linkedin_link'] ?? '#';

$quickLinks = [];
$dbLinks = SiteData::footerLinks('quick_links');
foreach ($dbLinks as $link) {
    $quickLinks[] = ['label' => $link['title'], 'url' => menu_url($link['url'])];
}
if (empty($quickLinks)) {
    $quickLinks = [
        ['label' => 'Home', 'url' => site_url()],
        ['label' => 'Courses', 'url' => site_url('trades')],
        ['label' => 'Admission', 'url' => site_url('admission-process')],
        ['label' => 'Syllabus', 'url' => site_url('trades')],
        ['label' => 'Contact', 'url' => site_url('contact')],
    ];
}
?>
<footer class="site-footer w-full bg-primary-container text-surface-container-highest footer-industrial-pattern relative overflow-hidden border-t-4 border-secondary-container">
<div class="absolute top-0 right-0 w-64 h-64 opacity-5 pointer-events-none transform translate-x-32 -translate-y-32">
<span class="material-symbols-outlined text-[200px]" style="font-variation-settings: 'FILL' 1;">settings</span>
</div>
<div class="max-w-container-max mx-auto px-gutter py-section-gap grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 relative z-10">
<div class="space-y-6">
<div class="flex flex-col gap-2">
<span class="font-headline-md text-headline-md font-bold text-on-primary tracking-tight"><?= e($logoText) ?></span>
<span class="font-label-sm text-label-sm text-secondary-container uppercase tracking-widest"><?= e(institute_tagline($header)) ?></span>
</div>
<p class="font-body-md text-body-md text-on-primary-container leading-relaxed"><?= e($footerAbout) ?></p>
<div class="flex gap-4">
<a class="w-10 h-10 rounded-full border border-on-primary-container flex items-center justify-center hover:bg-secondary-container hover:text-primary-container hover:border-secondary-container transition-all duration-300" href="<?= e($socialWeb ?: '#') ?>" target="_blank" rel="noopener">
<span class="material-symbols-outlined text-[20px]">public</span>
</a>
<a class="w-10 h-10 rounded-full border border-on-primary-container flex items-center justify-center hover:bg-secondary-container hover:text-primary-container hover:border-secondary-container transition-all duration-300" href="<?= e($socialVideo ?: '#') ?>" target="_blank" rel="noopener">
<span class="material-symbols-outlined text-[20px]">video_library</span>
</a>
<a class="w-10 h-10 rounded-full border border-on-primary-container flex items-center justify-center hover:bg-secondary-container hover:text-primary-container hover:border-secondary-container transition-all duration-300" href="<?= e($socialGroups ?: '#') ?>" target="_blank" rel="noopener">
<span class="material-symbols-outlined text-[20px]">groups</span>
</a>
</div>
</div>
<div class="space-y-6">
<h4 class="font-headline-md text-headline-md text-on-primary border-l-4 border-secondary-container pl-4">Quick Links</h4>
<nav class="flex flex-col gap-3">
<?php foreach ($quickLinks as $link): ?>
<a class="font-body-md text-body-md text-on-primary-container hover:text-secondary-container transition-colors duration-200 flex items-center gap-2" href="<?= e($link['url']) ?>">
<span class="material-symbols-outlined text-[16px]">chevron_right</span> <?= e($link['label']) ?>
</a>
<?php endforeach; ?>
</nav>
</div>
<div class="space-y-6">
<h4 class="font-headline-md text-headline-md text-on-primary border-l-4 border-secondary-container pl-4">Trade Courses</h4>
<div class="flex flex-col gap-3">
<?php foreach ($trades as $t): ?>
<a href="<?= site_url('trades/' . ($t['slug'] ?? '')) ?>" class="group cursor-pointer">
<p class="font-label-sm text-label-sm text-secondary-container mb-1"><?= e(strtoupper($t['category'] ?? 'ENGINEERING')) ?></p>
<p class="font-body-md text-body-md text-on-primary group-hover:text-secondary-container transition-colors"><?= e($t['name']) ?> Trade (<?= e($t['duration'] ?? '2 Years') ?>)</p>
</a>
<?php endforeach; ?>
<a href="<?= site_url('trades') ?>" class="group cursor-pointer">
<p class="font-label-sm text-label-sm text-secondary-container mb-1">VOCATIONAL</p>
<p class="font-body-md text-body-md text-on-primary group-hover:text-secondary-container transition-colors">Skill Development Cell</p>
</a>
</div>
</div>
<div class="space-y-6">
<h4 class="font-headline-md text-headline-md text-on-primary border-l-4 border-secondary-container pl-4">Contact Us</h4>
<div class="flex flex-col gap-4">
<div class="flex items-start gap-3">
<span class="material-symbols-outlined text-secondary-container">location_on</span>
<p class="font-body-md text-body-md text-on-primary-container"><?= e($footerAddress) ?></p>
</div>
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary-container">call</span>
<a href="tel:<?= e(preg_replace('/\D/', '', format_mobile($footerPhone))) ?>" class="font-body-md text-body-md text-on-primary-container hover:text-secondary-container transition-colors"><?= e(format_mobile($footerPhone)) ?></a>
</div>
<div class="flex items-center gap-3">
<span class="material-symbols-outlined text-secondary-container">mail</span>
<a href="mailto:<?= e($footerEmail) ?>" class="font-body-md text-body-md text-on-primary-container hover:text-secondary-container transition-colors"><?= e($footerEmail) ?></a>
</div>
</div>
<div class="mt-8">
<h5 class="font-label-sm text-label-sm text-on-primary mb-3 uppercase tracking-wider">Join Our Newsletter</h5>
<form class="flex" id="footerNewsletterForm" onsubmit="return false;">
<input class="bg-primary/20 border border-outline-variant text-on-primary px-4 py-2 w-full focus:outline-none focus:border-secondary-container rounded-l-lg font-body-md" placeholder="Email Address" type="email" name="email"/>
<button type="button" class="bg-secondary-container text-primary-container px-4 py-2 font-bold hover:bg-secondary transition-colors rounded-r-lg flex items-center" onclick="this.closest('form').querySelector('input').value='';">
<span class="material-symbols-outlined">send</span>
</button>
</form>
</div>
</div>
</div>
<div class="w-full bg-tertiary-container/50 border-t border-outline-variant/10">
<div class="max-w-container-max mx-auto px-gutter py-6 flex flex-col md:flex-row justify-between items-center gap-4">
<div class="flex flex-col md:flex-row items-center gap-4">
<p class="font-label-sm text-label-sm text-on-primary-container"><?= e($copyright) ?></p>
<span class="hidden md:block text-on-primary-container opacity-30">|</span>
<p class="font-label-sm text-label-sm text-on-primary-container flex items-center gap-2">
<span class="material-symbols-outlined text-[14px]">verified</span> Affiliated to NCVT, Government of India
</p>
</div>
<div class="flex flex-col sm:flex-row items-center gap-6">
<span class="font-label-sm text-label-sm text-secondary-container bg-secondary-container/10 px-3 py-1 border border-secondary-container/20">MIS CODE: <?= e($misCode) ?></span>
<div class="flex gap-4">
<a class="font-label-sm text-label-sm text-on-primary-container hover:text-on-primary" href="<?= e($privacyUrl) ?>">Privacy Policy</a>
<a class="font-label-sm text-label-sm text-on-primary-container hover:text-on-primary" href="<?= e($termsUrl) ?>">Terms of Service</a>
</div>
</div>
</div>
</div>
</footer>

<?php require base_path('views/partials/admission-enquiry-popup.php'); ?>
