<?php
/**
 * Academic session filter — segmented pill control.
 *
 * @var string $filterSession
 * @var string $baseUrl e.g. admin/students
 * @var array<string,string> $extraQuery
 * @var list<string>|null $sessions
 */
$baseUrl = $baseUrl ?? 'admin/students';
$filterSession = $filterSession ?? '';
$extraQuery = $extraQuery ?? [];
$sessions = $sessions ?? academic_session_options();
$stats = academic_session_stats();
$allUrl = admin_session_query($baseUrl, '', $extraQuery);
?>
<div class="admin-session-filter">
  <div class="admin-session-filter-inner">
    <div class="admin-session-filter-title">
      <span class="material-symbols-outlined" aria-hidden="true">calendar_month</span>
      <span>Academic Session</span>
    </div>
    <nav class="admin-session-pills" role="tablist" aria-label="Academic sessions">
      <a href="<?= e($allUrl) ?>"
         class="admin-session-pill<?= $filterSession === '' ? ' is-active' : '' ?>"
         role="tab"
         aria-selected="<?= $filterSession === '' ? 'true' : 'false' ?>">
        All
      </a>
      <?php foreach ($sessions as $sn): ?>
      <?php
        $tabUrl = admin_session_query($baseUrl, $sn, $extraQuery);
        $count = (int) ($stats[$sn]['students'] ?? 0);
        $isActive = $filterSession === $sn;
      ?>
      <a href="<?= e($tabUrl) ?>"
         class="admin-session-pill<?= $isActive ? ' is-active' : '' ?>"
         role="tab"
         aria-selected="<?= $isActive ? 'true' : 'false' ?>"
         title="<?= e($sn) ?>">
        <?= e(session_short_label($sn)) ?>
        <?php if ($count > 0): ?>
        <span class="admin-session-pill-badge"><?= $count ?></span>
        <?php endif; ?>
      </a>
      <?php endforeach; ?>
    </nav>
  </div>
</div>
