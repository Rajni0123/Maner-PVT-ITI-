<?php
use App\Models\SiteData;

$header = $header ?? SiteData::header();
$footer = $footer ?? SiteData::footer();
$settings = $settings ?? SiteData::settings();
$trades = $trades ?? SiteData::activeTrades();
$navActive = $navActive ?? '';
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$pageTitle = $title ?? ($settings['seo_title'] ?? $logoText);
$seoDesc = $settings['seo_description'] ?? '';
$logoUrl = site_institute_logo_url();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($pageTitle) ?></title>
  <?php if ($seoDesc !== ''): ?>
  <meta name="description" content="<?= e($seoDesc) ?>">
  <?php endif; ?>
  <link rel="icon" href="<?= e($logoUrl) ?>">
  <link rel="stylesheet" href="<?= asset('css/patna-template.css') ?>">
</head>
<body class="pti-body">
<?php require base_path('views/partials/patna/nav.php'); ?>
<main>
  <?= $content ?>
</main>
<?php require base_path('views/partials/patna/footer.php'); ?>
<?php require base_path('views/partials/patna/enquiry.php'); ?>
<script>
(function () {
  var btn = document.getElementById('ptiNavToggle');
  var nav = document.getElementById('ptiNav');
  if (btn && nav) {
    btn.addEventListener('click', function () {
      nav.classList.toggle('is-open');
    });
  }
})();
</script>
</body>
</html>
