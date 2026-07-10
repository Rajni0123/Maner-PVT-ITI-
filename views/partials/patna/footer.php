<?php
use App\Models\SiteData;

$header = $header ?? SiteData::header();
$footer = $footer ?? SiteData::footer();
$trades = $trades ?? SiteData::activeTrades();
$settings = $settings ?? SiteData::settings();

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$about = $footer['about_text'] ?? 'A premier NCVT-affiliated industrial training institute providing quality vocational education and placement support.';
$address = trim(str_replace("\n", ', ', $footer['address'] ?? 'Maner, Patna, Bihar'));
$phone = $footer['phone'] ?? $header['phone'] ?? '';
$email = $footer['email'] ?? $header['email'] ?? '';
$copyright = $footer['copyright_text'] ?? ('© ' . date('Y') . ' ' . $logoText . '. All Rights Reserved.');
?>
<footer class="pti-footer">
  <div class="pti-container pti-footer__grid">
    <div class="pti-footer__about">
      <div class="pti-footer__brand"><?= e($logoText) ?></div>
      <p><?= e($about) ?></p>
      <p style="margin-top:1rem">
        <a href="<?= site_url('apply-admission') ?>">Apply Online</a> ·
        <a href="<?= site_url('contact') ?>">Student Query</a>
      </p>
    </div>
    <div>
      <h4>Quick Links</h4>
      <ul>
        <li><a href="<?= site_url() ?>">Home</a></li>
        <li><a href="<?= site_url('about') ?>">About Us</a></li>
        <li><a href="<?= site_url('trades') ?>">Courses</a></li>
        <li><a href="<?= site_url('admission-process') ?>">Admission</a></li>
        <li><a href="<?= site_url('notices') ?>">News &amp; Notice</a></li>
        <li><a href="<?= site_url('contact') ?>">Contact Us</a></li>
      </ul>
    </div>
    <div>
      <h4>Important Links</h4>
      <ul>
        <li><a href="<?= site_url('fee-structure') ?>">Course with Fee List</a></li>
        <li><a href="<?= site_url('bscc-info') ?>">BSCC Scheme</a></li>
        <li><a href="<?= site_url('apply-admission') ?>">Admission Form</a></li>
        <li><a href="<?= site_url('infrastructure') ?>">Facilities / Gallery</a></li>
        <li><a href="<?= site_url('faculty') ?>">Faculty</a></li>
        <li><a href="<?= site_url('results') ?>">Results</a></li>
      </ul>
    </div>
    <div>
      <h4>Contact Info</h4>
      <ul>
        <li><?= e($address) ?></li>
        <?php if ($email): ?><li><a href="mailto:<?= e($email) ?>"><?= e($email) ?></a></li><?php endif; ?>
        <?php if ($phone): ?><li><a href="tel:<?= e(preg_replace('/\s+/', '', $phone)) ?>"><?= e($phone) ?></a></li><?php endif; ?>
      </ul>
      <?php if (!empty($trades)): ?>
      <h4 style="margin-top:1.25rem">Trades</h4>
      <ul>
        <?php foreach ($trades as $t): ?>
        <li><a href="<?= site_url('trades/' . ($t['slug'] ?? '')) ?>"><?= e($t['name']) ?></a></li>
        <?php endforeach; ?>
      </ul>
      <?php endif; ?>
    </div>
  </div>
  <div class="pti-footer__bottom">
    <div class="pti-container">
      <?= e($copyright) ?><br>
      Affiliated to National Council for Vocational Training (NCVT) | Ministry of Skill Development and Entrepreneurship
    </div>
  </div>
</footer>
