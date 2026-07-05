<?php
/**
 * @var string $notifyTab 'send'|'setup'
 */
$notifyTab = $notifyTab ?? 'send';
?>
<nav class="notify-tabs" aria-label="Notification sections">
  <a href="<?= site_url('admin/notifications') ?>" class="notify-tab<?= $notifyTab === 'send' ? ' is-active' : '' ?>">
    <span class="material-symbols-outlined">send</span>
    Send Notification
  </a>
  <a href="<?= site_url('admin/notifications?section=setup') ?>" class="notify-tab<?= $notifyTab === 'setup' ? ' is-active' : '' ?>">
    <span class="material-symbols-outlined">settings</span>
    Configuration
  </a>
</nav>
