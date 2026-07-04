<?php
$header = \App\Models\SiteData::header();
$footer = \App\Models\SiteData::footer();
$menus = \App\Models\SiteData::menus();
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<?php
$pageTitle = ($title ?? 'Maner Pvt ITI') . ' | Maner Private ITI';
require base_path('views/partials/design-head.php');
?>
<link rel="stylesheet" href="<?= asset('css/style.css') ?>">
</head>
<body>
  <div class="topbar">
    <div class="container">
      <span>📞 <?= e($header['phone'] ?? '') ?> | ✉ <?= e($header['email'] ?? '') ?></span>
      <span>
        <a href="<?= e($header['ncvt_mis_link'] ?? '#') ?>" target="_blank"><?= e($header['ncvt_mis_text'] ?? 'NCVT MIS') ?></a>
      </span>
    </div>
  </div>
  <nav class="navbar">
    <div class="container">
      <a href="<?= site_url() ?>" class="logo">
        <?= e($header['logo_text'] ?? 'Maner Pvt ITI') ?>
        <span><?= e($header['tagline'] ?? '') ?></span>
      </a>
      <button class="nav-toggle" onclick="document.querySelector('.nav-links').classList.toggle('open')">☰</button>
      <ul class="nav-links">
        <?php foreach ($menus as $m):
          $menuUrl = $m['url'] ?? '/';
          $href = (str_starts_with($menuUrl, 'http://') || str_starts_with($menuUrl, 'https://'))
            ? $menuUrl
            : site_url(ltrim($menuUrl, '/'));
          $active = request_path() === rtrim($menuUrl, '/') || request_path() === $menuUrl;
        ?>
          <li><a href="<?= e($href) ?>" class="<?= $active ? 'active' : '' ?>"><?= e($m['title']) ?></a></li>
        <?php endforeach; ?>
        <li><a href="<?= site_url('admin/login') ?>">Admin</a></li>
      </ul>
    </div>
  </nav>

  <?php if ($msg = flash('success')): ?><div class="container" style="margin-top:1rem"><div class="alert alert-success"><?= e($msg) ?></div></div><?php endif; ?>
  <?php if ($msg = flash('error')): ?><div class="container" style="margin-top:1rem"><div class="alert alert-error"><?= e($msg) ?></div></div><?php endif; ?>

  <main><?= $content ?></main>

  <?php require base_path('views/partials/design-footer.php'); ?>
  <script>window.APP_BASE = <?= json_encode(app_base_path()) ?>;</script>
  <script src="<?= asset('js/main.js') ?>"></script>
</body>
</html>
