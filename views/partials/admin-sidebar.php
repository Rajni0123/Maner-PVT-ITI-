<?php
use App\Core\Auth;

$adminUser = Auth::user();
$path = trim(parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$basePath = trim(parse_url(site_url(), PHP_URL_PATH) ?: '', '/');
if ($basePath !== '' && str_starts_with($path, $basePath)) {
    $path = trim(substr($path, strlen($basePath)), '/');
}

$navItems = [
    ['url' => 'admin', 'icon' => 'dashboard', 'label' => 'Dashboard', 'exact' => true],
    ['url' => 'admin/admissions', 'icon' => 'person_add', 'label' => 'Admissions'],
    ['url' => 'admin/students', 'icon' => 'school', 'label' => 'Students'],
    ['url' => 'admin/notifications', 'icon' => 'notifications_active', 'label' => 'Notifications', 'match' => 'admin/notifications'],
    ['url' => 'admin/contacts', 'icon' => 'contact_support', 'label' => 'Inquiries'],
    ['url' => 'admin/fees', 'icon' => 'payments', 'label' => 'Fee Tracker', 'match' => 'admin/fees', 'except' => 'admin/fees/report'],
    ['url' => 'admin/fees/report', 'icon' => 'assessment', 'label' => 'Fee Report'],
    ['url' => 'admin/staff/salary', 'icon' => 'receipt_long', 'label' => 'Staff Salary', 'match' => 'admin/staff'],
];

$cmsItems = [
    ['url' => 'admin/hero', 'icon' => 'home', 'label' => 'Homepage Hero'],
    ['url' => 'admin/flash-news', 'icon' => 'campaign', 'label' => 'News Ticker'],
    ['url' => 'admin/trades', 'icon' => 'engineering', 'label' => 'Trades'],
    ['url' => 'admin/menus', 'icon' => 'menu', 'label' => 'Navigation'],
    ['url' => 'admin/footer-links', 'icon' => 'link', 'label' => 'Footer Links'],
    ['url' => 'admin/faculty', 'icon' => 'groups', 'label' => 'Faculty'],
    ['url' => 'admin/notices', 'icon' => 'menu_book', 'label' => 'Notices'],
    ['url' => 'admin/results', 'icon' => 'fact_check', 'label' => 'Results'],
    ['url' => 'admin/gallery', 'icon' => 'photo_library', 'label' => 'Gallery'],
    ['url' => 'admin/sessions', 'icon' => 'calendar_month', 'label' => 'Sessions'],
];

$isActive = static function (array $item) use ($path): bool {
    if (!empty($item['except'])) {
        $except = rtrim($item['except'], '/');
        if ($path === $except || str_starts_with($path, $except . '/')) {
            return false;
        }
    }
    if (!empty($item['match'])) {
        $prefix = rtrim($item['match'], '/');
        return $path === $prefix || str_starts_with($path, $prefix . '/');
    }
    if (!empty($item['exact'])) {
        return $path === $item['url'];
    }
    return $path === $item['url'] || str_starts_with($path, $item['url'] . '/');
};

$footerItems = [
    ['url' => 'admin/settings', 'icon' => 'settings', 'label' => 'Settings', 'match' => 'admin/settings'],
    ['url' => 'admin/logout', 'icon' => 'logout', 'label' => 'Logout', 'exact' => true],
];
?>
<aside id="admin-sidebar" class="admin-sidebar hidden md:flex flex-col bg-surface-container border-r border-outline-variant py-base">
  <div class="admin-sidebar-brand px-gutter mb-6 mt-4 shrink-0">
    <h2 class="font-headline-md text-headline-md font-bold text-primary">Maner ITI</h2>
    <p class="font-label-sm text-label-sm text-on-surface-variant">Admin Portal</p>
  </div>
  <nav class="admin-sidebar-nav flex-1 space-y-1 min-h-0">
    <?php foreach ($navItems as $item): ?>
    <?php $active = $isActive($item); ?>
    <a class="admin-sidebar-link<?= $active ? ' is-active' : '' ?>" href="<?= site_url($item['url']) ?>">
      <span class="material-symbols-outlined mr-3"><?= e($item['icon']) ?></span>
      <span class="font-label-sm text-label-sm"><?= e($item['label']) ?></span>
    </a>
    <?php endforeach; ?>
    <p class="px-gutter pt-4 pb-1 font-label-sm text-label-sm text-on-surface-variant uppercase tracking-wider">Website CMS</p>
    <?php foreach ($cmsItems as $item): ?>
    <?php $active = $isActive($item); ?>
    <a class="admin-sidebar-link<?= $active ? ' is-active' : '' ?>" href="<?= site_url($item['url']) ?>">
      <span class="material-symbols-outlined mr-3"><?= e($item['icon']) ?></span>
      <span class="font-label-sm text-label-sm"><?= e($item['label']) ?></span>
    </a>
    <?php endforeach; ?>
  </nav>
  <div class="admin-sidebar-footer shrink-0 px-gutter py-6">
    <a href="<?= site_url('apply-admission') ?>" target="_blank" class="w-full bg-secondary-container text-on-secondary-container py-3 px-4 font-label-sm text-label-sm flex items-center justify-center gap-2 hover:opacity-90 transition-opacity active:scale-95">
      <span class="material-symbols-outlined">campaign</span>
      Admission Open
    </a>
  </div>
  <div class="admin-sidebar-actions shrink-0 border-t border-outline-variant mt-4 pt-4 pb-4">
    <?php foreach ($footerItems as $item): ?>
    <?php if (($item['url'] ?? '') === 'admin/logout'): ?>
    <a class="admin-sidebar-link admin-sidebar-link-footer" href="<?= site_url('admin/logout') ?>">
      <span class="material-symbols-outlined mr-3 text-[20px]">logout</span>
      <span class="font-label-sm text-label-sm">Logout</span>
    </a>
    <?php else: ?>
    <?php $active = $isActive($item); ?>
    <a class="admin-sidebar-link admin-sidebar-link-footer<?= $active ? ' is-active' : '' ?>" href="<?= site_url($item['url']) ?>">
      <span class="material-symbols-outlined mr-3 text-[20px]"><?= e($item['icon']) ?></span>
      <span class="font-label-sm text-label-sm"><?= e($item['label']) ?></span>
    </a>
    <?php endif; ?>
    <?php endforeach; ?>
  </div>
</aside>
