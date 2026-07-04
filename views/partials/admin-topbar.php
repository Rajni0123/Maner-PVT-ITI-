<?php
use App\Core\Auth;
use App\Core\AdminNotifications;

$adminUser = Auth::user();
$userName = $adminUser['name'] ?? 'Admin User';
$userRole = ucfirst($adminUser['role'] ?? 'Registrar');
$notifCount = AdminNotifications::count();
$notifItems = AdminNotifications::items(10);
$initials = '';
foreach (preg_split('/\s+/', trim($userName)) as $part) {
    if ($part !== '') {
        $initials .= strtoupper($part[0]);
    }
}
if ($initials === '') {
    $initials = 'AD';
}
?>
<header class="admin-topbar w-full h-16 sticky top-0 z-40 bg-surface-container-lowest border-b border-outline-variant flex justify-between items-center px-gutter">
  <div class="flex items-center flex-1 max-w-xl">
    <div class="relative w-full">
      <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
      <input class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant focus:border-primary outline-none transition-all text-sm" placeholder="Search students, trade records, or fees..." type="search"/>
    </div>
  </div>
  <div class="flex items-center gap-6 ml-gutter">
    <div class="flex items-center gap-4">
      <div class="admin-notifications-wrap relative">
        <button type="button" id="adminNotifBtn" class="relative p-2 text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer active:opacity-80" aria-label="Notifications" aria-expanded="false">
          <span class="material-symbols-outlined">notifications</span>
          <span id="adminNotifBadge" class="admin-notif-badge<?= $notifCount > 0 ? '' : ' hidden' ?>"><?= $notifCount > 99 ? '99+' : (int) $notifCount ?></span>
        </button>
        <div id="adminNotifPanel" class="admin-notif-panel hidden" role="menu">
          <div class="admin-notif-header">
            <strong>Notifications</strong>
            <span id="adminNotifCountLabel"><?= (int) $notifCount ?> new</span>
          </div>
          <div id="adminNotifList" class="admin-notif-list">
            <?php if (!$notifItems): ?>
            <p class="admin-notif-empty">No new messages or form submissions.</p>
            <?php else: foreach ($notifItems as $n): ?>
            <a href="<?= e($n['url']) ?>" class="admin-notif-item" data-type="<?= e($n['type']) ?>" data-id="<?= (int) $n['id'] ?>">
              <span class="material-symbols-outlined admin-notif-icon"><?= $n['type'] === 'admission' ? 'person_add' : 'mail' ?></span>
              <span class="admin-notif-body">
                <span class="admin-notif-title"><?= e($n['title']) ?></span>
                <span class="admin-notif-text"><?= e($n['text']) ?></span>
                <span class="admin-notif-meta"><?= e($n['meta']) ?> · <?= e($n['time_label']) ?></span>
              </span>
            </a>
            <?php endforeach; endif; ?>
          </div>
          <div class="admin-notif-footer">
            <a href="<?= site_url('admin/contacts') ?>">All Inquiries</a>
            <a href="<?= site_url('admin/admissions') ?>">All Admissions</a>
          </div>
        </div>
      </div>
      <button type="button" id="adminThemeToggle" class="admin-theme-toggle p-2 text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer active:opacity-80" aria-label="Toggle dark mode" title="Toggle dark / light mode">
        <span class="material-symbols-outlined admin-theme-icon-light">dark_mode</span>
        <span class="material-symbols-outlined admin-theme-icon-dark">light_mode</span>
      </button>
      <a href="<?= site_url() ?>" target="_blank" class="p-2 text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer active:opacity-80" title="View Website">
        <span class="material-symbols-outlined">help</span>
      </a>
      <a href="<?= site_url('admin/settings') ?>" class="p-2 text-on-surface-variant hover:bg-surface-container transition-colors cursor-pointer active:opacity-80">
        <span class="material-symbols-outlined">settings</span>
      </a>
    </div>
    <a href="<?= site_url('admin/profile') ?>" class="admin-profile-link flex items-center gap-3 pl-6 border-l border-outline-variant h-8 hover:opacity-90 transition-opacity" title="Update profile">
      <div class="text-right">
        <p class="font-label-sm text-label-sm text-primary font-bold"><?= e($userName) ?></p>
        <p class="text-[10px] text-on-surface-variant uppercase tracking-widest"><?= e($userRole) ?></p>
      </div>
      <?php
      $avatarFile = $adminUser['avatar'] ?? '';
      if ($avatarFile && upload_exists($avatarFile)):
      ?>
      <img src="<?= e(upload_url($avatarFile)) ?>" alt="" class="admin-topbar-avatar-img">
      <?php else: ?>
      <div class="admin-topbar-avatar"><?= e($initials) ?></div>
      <?php endif; ?>
    </a>
  </div>
</header>
