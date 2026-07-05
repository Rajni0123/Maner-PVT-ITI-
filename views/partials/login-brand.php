<?php
$header = $header ?? \App\Models\SiteData::header();
$logoText = trim((string) ($header['logo_text'] ?? 'Maner Private ITI'));
if ($logoText === '' || $logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$brandTitle = $brandTitle ?? $logoText;
?>
<div class="login-brand">
  <img
    src="<?= e(site_institute_logo_url()) ?>"
    alt="<?= e($logoText) ?>"
    class="login-brand-logo"
    width="64"
    height="64"
    loading="eager"
    decoding="async"
  >
  <h1><?= e($brandTitle) ?></h1>
  <p class="login-sub">Admin Portal</p>
</div>
