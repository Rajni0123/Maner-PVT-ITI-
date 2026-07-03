<?php
$currentPath = request_path();
$navActive = static function (string $path) use ($currentPath): bool {
    if ($path === '/') return $currentPath === '/';
    return str_starts_with($currentPath, $path);
};
?>
<!-- Mobile Bottom Navigation -->
<nav class="bottom-nav" id="bottomNav">
  <div class="bottom-nav-items">
    <a href="<?= site_url() ?>" class="bottom-nav-item <?= $navActive('/') ? 'is-active' : '' ?>">
      <span class="material-symbols-outlined">home</span>
      <span>Home</span>
    </a>
    <a href="<?= site_url('trades') ?>" class="bottom-nav-item <?= $navActive('/trades') ? 'is-active' : '' ?>">
      <span class="material-symbols-outlined">engineering</span>
      <span>Courses</span>
    </a>
    <a href="<?= site_url('apply-admission') ?>" class="bottom-nav-item bottom-nav-apply">
      <span class="material-symbols-outlined">add</span>
      <span>Apply</span>
    </a>
    <a href="<?= site_url('notices') ?>" class="bottom-nav-item <?= $navActive('/notices') || $navActive('/results') ? 'is-active' : '' ?>">
      <span class="material-symbols-outlined">campaign</span>
      <span>News</span>
    </a>
    <a href="<?= site_url('contact') ?>" class="bottom-nav-item <?= $navActive('/contact') ? 'is-active' : '' ?>">
      <span class="material-symbols-outlined">call</span>
      <span>Contact</span>
    </a>
  </div>
</nav>

<!-- PWA Install Banner -->
<div class="pwa-install-banner" id="pwaInstallBanner">
  <div class="pwa-install-banner-icon">
    <span class="material-symbols-outlined" style="color:#131b2e;font-variation-settings:'FILL' 1">download</span>
  </div>
  <div class="pwa-install-banner-text">
    <strong>Install Maner ITI App</strong>
    <span>Add to home screen for quick access</span>
  </div>
  <button class="pwa-install-btn" id="pwaInstallBtn">Install</button>
  <button class="pwa-install-close" id="pwaInstallClose">&times;</button>
</div>
