<?php
use App\Models\SiteData;

$header = $header ?? SiteData::header();
$flashNews = $flashNews ?? SiteData::flashNews();
$navActive = $navActive ?? '';
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$phone = $header['phone'] ?? '+91-9155401839';
$email = $header['email'] ?? 'info@maneriti.com';
$tagline = $header['tagline'] ?? 'NCVT Affiliated Industrial Training Institute';
$logoUrl = site_institute_logo_url();
$ncvtText = $header['ncvt_mis_text'] ?? 'NCVT';
$ncvtLink = $header['ncvt_mis_link'] ?? 'https://ncvtmis.gov.in';

$tickerItems = [];
foreach ($flashNews as $f) {
    $tickerItems[] = trim(($f['title'] ?? '') . (!empty($f['content']) ? ': ' . $f['content'] : ''));
}
if (empty($tickerItems)) {
    $tickerItems = [
        'Admission Open — New Batch',
        'Limited Seats! Apply Now',
        'NCVT Affiliated Courses',
        'BSCC Scheme Accepted',
        'Contact: ' . $phone,
    ];
}
$tickerText = implode('   |   ', $tickerItems);
$tickerLoop = $tickerText . '   |   ' . $tickerText;

$isActive = static function (string $key) use ($navActive): string {
    return $navActive === $key ? 'is-active' : '';
};
?>
<div class="pti-topbar">
  <div class="pti-container pti-topbar__inner">
    <div>
      <a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a>
      &nbsp;|&nbsp;
      <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
    </div>
    <div class="pti-topbar__links">
      <a href="<?= e($ncvtLink) ?>" target="_blank" rel="noopener"><?= e($ncvtText) ?></a>
      <a href="<?= site_url('apply-admission') ?>">Apply Online</a>
      <a href="<?= site_url('notices') ?>">News &amp; Notice</a>
    </div>
  </div>
</div>

<div class="pti-ticker" aria-label="Latest updates">
  <div class="pti-ticker__track"><?= e($tickerLoop) ?></div>
</div>

<header class="pti-header">
  <div class="pti-container pti-header__row">
    <a class="pti-brand" href="<?= site_url() ?>">
      <img class="pti-brand__logo" src="<?= e($logoUrl) ?>" alt="<?= e($logoText) ?>">
      <span>
        <span class="pti-brand__name"><?= e($logoText) ?></span>
        <span class="pti-brand__tag"><?= e($tagline) ?></span>
      </span>
    </a>
    <div class="pti-header__actions">
      <div class="pti-header__contact">
        <strong><?= e($phone) ?></strong>
        <?= e($email) ?>
      </div>
      <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Apply</a>
      <a class="pti-btn pti-btn--outline" href="<?= site_url('notices') ?>">Notice</a>
    </div>
  </div>
</header>

<nav class="pti-nav" id="ptiNav">
  <div class="pti-container">
    <button type="button" class="pti-nav__toggle" id="ptiNavToggle" aria-expanded="false">Menu ☰</button>
    <ul class="pti-nav__list">
      <li><a class="<?= $isActive('home') ?>" href="<?= site_url() ?>">Home</a></li>
      <li><a class="<?= $isActive('about') ?>" href="<?= site_url('about') ?>">About</a></li>
      <li><a class="<?= $isActive('courses') ?>" href="<?= site_url('trades') ?>">Courses</a></li>
      <li><a class="<?= $isActive('admission') ?>" href="<?= site_url('admission-process') ?>">Admission</a></li>
      <li><a class="<?= $isActive('fees') ?>" href="<?= site_url('fee-structure') ?>">Fee List</a></li>
      <li><a class="<?= $isActive('bscc') ?>" href="<?= site_url('bscc-info') ?>">BSCC</a></li>
      <li><a class="<?= $isActive('notices') ?>" href="<?= site_url('notices') ?>">News &amp; Notice</a></li>
      <li><a class="<?= $isActive('gallery') ?>" href="<?= site_url('infrastructure') ?>">Gallery</a></li>
      <li><a class="<?= $isActive('apply') ?>" href="<?= site_url('apply-admission') ?>">Admission Form</a></li>
      <li><a class="<?= $isActive('contact') ?>" href="<?= site_url('contact') ?>">Contact</a></li>
    </ul>
  </div>
</nav>
