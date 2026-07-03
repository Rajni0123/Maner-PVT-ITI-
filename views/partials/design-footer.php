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
$privacyUrl = safe_href($footer['privacy_link'] ?? site_url('contact'));
$termsUrl = safe_href($footer['terms_link'] ?? site_url('contact'));
$newsletterEnabled = newsletter_enabled();
$newsletterTitle = trim($settings['newsletter_title'] ?? '') ?: 'Join Our Newsletter';
$newsletterPlaceholder = trim($settings['newsletter_placeholder'] ?? '') ?: 'Email Address';

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
<a class="w-10 h-10 rounded-full border border-on-primary-container flex items-center justify-center hover:bg-secondary-container hover:text-primary-container hover:border-secondary-container transition-all duration-300" href="<?= e(safe_href($socialWeb ?: '#')) ?>" target="_blank" rel="noopener">
<span class="material-symbols-outlined text-[20px]">public</span>
</a>
<a class="w-10 h-10 rounded-full border border-on-primary-container flex items-center justify-center hover:bg-secondary-container hover:text-primary-container hover:border-secondary-container transition-all duration-300" href="<?= e(safe_href($socialVideo ?: '#')) ?>" target="_blank" rel="noopener">
<span class="material-symbols-outlined text-[20px]">video_library</span>
</a>
<a class="w-10 h-10 rounded-full border border-on-primary-container flex items-center justify-center hover:bg-secondary-container hover:text-primary-container hover:border-secondary-container transition-all duration-300" href="<?= e(safe_href($socialGroups ?: '#')) ?>" target="_blank" rel="noopener">
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
<a class="font-body-md text-body-md text-secondary-container hover:text-on-primary transition-colors duration-200 flex items-center gap-2 font-bold" href="https://dethunar-bih.com/" target="_blank" rel="noopener">
<span class="material-symbols-outlined text-[16px]">school</span> National Scholarship Portal (DET Bihar)
</a>
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
<?php if ($newsletterEnabled): ?>
<div class="mt-8">
<h5 class="font-label-sm text-label-sm text-on-primary mb-3 uppercase tracking-wider"><?= e($newsletterTitle) ?></h5>
<form class="flex flex-col gap-2" id="footerNewsletterForm" action="<?= site_url('newsletter/subscribe') ?>" method="post" novalidate>
<input type="hidden" name="_csrf" value="<?= e(csrf_token()) ?>">
<div class="flex">
<input class="bg-primary/20 border border-outline-variant text-on-primary px-4 py-2 w-full focus:outline-none focus:border-secondary-container rounded-l-lg font-body-md" placeholder="<?= e($newsletterPlaceholder) ?>" type="email" name="email" required autocomplete="email"/>
<button type="submit" class="bg-secondary-container text-primary-container px-4 py-2 font-bold hover:bg-secondary transition-colors rounded-r-lg flex items-center" id="footerNewsletterBtn">
<span class="material-symbols-outlined">send</span>
</button>
</div>
<p id="footerNewsletterMsg" class="text-[11px] hidden"></p>
</form>
</div>
<script>
(function () {
  const form = document.getElementById('footerNewsletterForm');
  if (!form) return;
  const msg = document.getElementById('footerNewsletterMsg');
  const btn = document.getElementById('footerNewsletterBtn');
  const originalBtnHtml = btn.innerHTML;

  form.addEventListener('submit', async function (e) {
    e.preventDefault();
    msg.classList.add('hidden');
    btn.disabled = true;
    btn.innerHTML = '<span class="material-symbols-outlined animate-spin text-[20px]">refresh</span>';

    try {
      const res = await fetch(form.action, {
        method: 'POST',
        body: new FormData(form),
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      msg.textContent = data.message || (data.ok ? 'Subscribed successfully.' : 'Subscription failed.');
      msg.className = 'text-[11px] ' + (data.ok ? 'text-green-300' : 'text-red-300');
      if (data.ok) {
        form.querySelector('input[name="email"]').value = '';
      }
    } catch (err) {
      msg.textContent = 'Something went wrong. Please try again.';
      msg.className = 'text-[11px] text-red-300';
    }

    msg.classList.remove('hidden');
    btn.disabled = false;
    btn.innerHTML = originalBtnHtml;
  });
})();
</script>
<?php endif; ?>
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
<div class="flex gap-4 flex-wrap justify-center">
<a class="font-label-sm text-label-sm text-on-primary-container hover:text-on-primary" href="<?= e($privacyUrl) ?>">Privacy Policy</a>
<a class="font-label-sm text-label-sm text-on-primary-container hover:text-on-primary" href="<?= e($termsUrl) ?>">Terms of Service</a>
<a class="font-label-sm text-label-sm text-secondary-container hover:text-on-primary font-bold" href="https://dethunar-bih.com/" target="_blank" rel="noopener">DET Bihar (National Scholarship Portal)</a>
</div>
</div>
</div>
</div>
</footer>

<?php
// Shared mobile app chrome (bottom nav + FABs) — all public pages
if (empty($skipMobileChrome)):
  $callPhone = preg_replace('/\D/', '', $header['phone'] ?? '9155401839');
  $whatsappPhone = '91' . ltrim($callPhone, '91');
  $whatsappMsg = urlencode('Hi, I am interested in admission at Maner Private ITI. Please provide details.');
?>
<div class="floating-cta" id="floatingCTA">
  <a href="https://wa.me/<?= e($whatsappPhone) ?>?text=<?= e($whatsappMsg) ?>" target="_blank" rel="noopener" class="fab-btn fab-btn-whatsapp" title="WhatsApp" aria-label="WhatsApp">
    <span class="fab-pulse"></span>
    <span class="fab-tooltip">WhatsApp Us</span>
    <svg width="24" height="24" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
  </a>
  <a href="tel:+<?= e($callPhone) ?>" class="fab-btn fab-btn-call" title="Call Now" aria-label="Call Now">
    <span class="fab-pulse"></span>
    <span class="fab-tooltip">Call Now</span>
    <span class="material-symbols-outlined" style="font-variation-settings:'FILL' 1;font-size:28px">call</span>
  </a>
</div>

<?php require base_path('views/partials/mobile-bottom-nav.php'); ?>

<script>window.APP_BASE = <?= json_encode(app_base_path()) ?>;</script>
<script src="<?= asset('js/pwa.js') ?>"></script>
<script>
(function () {
  var f = document.getElementById('floatingCTA');
  if (f) setTimeout(function () { f.classList.add('fab-visible'); }, 800);
  if (window.innerWidth <= 768) document.body.classList.add('has-bottom-nav');
})();
</script>
<?php endif; ?>
