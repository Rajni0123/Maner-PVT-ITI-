<?php
$header = $header ?? \App\Models\SiteData::header();
$logoText = trim((string) ($header['logo_text'] ?? 'Maner Private ITI'));
if ($logoText === '' || $logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$brandTitle = $brandTitle ?? $logoText;
$brandSubtitle = $brandSubtitle ?? 'Admin Portal';
?>
<div class="login-brand">
  <img
    src="<?= e(site_institute_logo_url()) ?>"
    alt="<?= e($logoText) ?>"
    class="login-brand-logo"
    width="80"
    height="80"
    loading="eager"
    decoding="async"
  >
  <h2><?= e($brandTitle) ?></h2>
  <p class="login-sub"><?= e($brandSubtitle) ?></p>
</div>
