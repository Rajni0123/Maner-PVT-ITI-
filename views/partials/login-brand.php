<?php
$header = $header ?? \App\Models\SiteData::header();
$logoText = trim((string) ($header['logo_text'] ?? 'Maner Private ITI'));
if ($logoText === '' || $logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$misCode = \App\Models\SiteData::setting('mis_code', 'PR10001156');
?>
<div class="login-panel-inner">
  <img
    src="<?= e(site_institute_logo_url()) ?>"
    alt="<?= e($logoText) ?>"
    class="login-brand-logo"
    width="96"
    height="96"
    loading="eager"
    decoding="async"
  >
  <p class="login-panel-kicker">Admin Portal</p>
  <h1 class="login-panel-title"><?= e($logoText) ?></h1>
  <p class="login-panel-tagline"><?= e(institute_tagline($header)) ?></p>
  <div class="login-panel-badges">
    <span class="login-badge">NCVT Affiliated</span>
    <span class="login-badge login-badge-muted">MIS <?= e($misCode) ?></span>
  </div>
</div>
