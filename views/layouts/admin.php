<!DOCTYPE html>
<html class="light" lang="en" data-admin-theme="light">
<head>
<?php
$pageTitle = ($title ?? 'Admin') . ' | Maner Private ITI Admin';
require base_path('views/partials/design-head.php');
?>
<link rel="stylesheet" href="<?= asset('css/admin.css') ?>">
<script>
(function () {
  var theme = localStorage.getItem('maner-admin-theme');
  if (theme === 'dark') {
    document.documentElement.setAttribute('data-admin-theme', 'dark');
    document.documentElement.classList.remove('light');
    document.documentElement.classList.add('dark');
  }
})();
</script>
</head>
<body class="admin-body bg-background text-on-surface font-body-md">
<?php require base_path('views/partials/admin-sidebar.php'); ?>

<main class="admin-main min-h-screen flex flex-col">
  <?php require base_path('views/partials/admin-topbar.php'); ?>

  <div class="admin-content flex-1 p-gutter lg:p-10 max-w-container-max mx-auto w-full pb-20 md:pb-10">
    <?php if ($msg = flash('success')): ?>
    <div class="admin-alert admin-alert-success"><?= e($msg) ?></div>
    <?php endif; ?>
    <?php if ($msg = flash('error')): ?>
    <div class="admin-alert admin-alert-error"><?= e($msg) ?></div>
    <?php endif; ?>
    <?= $content ?>
  </div>

  <footer class="md:ml-0 flex justify-between items-center w-full py-8 px-gutter border-t border-outline-variant mt-auto bg-surface-container-lowest">
    <p class="font-label-sm text-label-sm text-on-surface-variant">© <?= date('Y') ?> Maner Private ITI. All Rights Reserved.</p>
    <div class="flex gap-6">
      <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-all" href="<?= site_url('contact') ?>" target="_blank">Privacy Policy</a>
      <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-all" href="<?= site_url('contact') ?>" target="_blank">Support</a>
      <a class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-all" href="<?= site_url('admin') ?>">Portal Guide</a>
    </div>
  </footer>
</main>

<nav class="md:hidden fixed bottom-0 left-0 right-0 h-16 bg-surface-container-lowest border-t border-outline-variant flex items-center justify-around z-50">
  <a href="<?= site_url('admin') ?>" class="flex flex-col items-center text-primary">
    <span class="material-symbols-outlined">dashboard</span>
    <span class="text-[10px] font-bold font-label-sm">Home</span>
  </a>
  <a href="<?= site_url('admin/admissions') ?>" class="flex flex-col items-center text-on-surface-variant">
    <span class="material-symbols-outlined">person_add</span>
    <span class="text-[10px] font-label-sm">Admissions</span>
  </a>
  <a href="<?= site_url('admin/fees') ?>" class="flex flex-col items-center text-on-surface-variant">
    <span class="material-symbols-outlined">payments</span>
    <span class="text-[10px] font-label-sm">Fees</span>
  </a>
  <a href="<?= site_url('admin/settings') ?>" class="flex flex-col items-center text-on-surface-variant">
    <span class="material-symbols-outlined">menu</span>
    <span class="text-[10px] font-label-sm">Menu</span>
  </a>
</nav>

<script>
window.ADMIN_NOTIF_FEED_URL = <?= json_encode(site_url('admin/notifications/feed')) ?>;
</script>
<script src="<?= asset('js/admin.js') ?>"></script>
</body>
</html>
