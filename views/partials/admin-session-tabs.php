<?php
/**
 * Clickable academic session tabs for admin lists.
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
<div class="session-tabs-wrap">
  <p class="session-tabs-label">Select Session</p>
  <div class="session-tabs" role="tablist" aria-label="Academic sessions">
    <a href="<?= e($allUrl) ?>"
       class="session-tab<?= $filterSession === '' ? ' is-active' : '' ?>"
       role="tab"
       aria-selected="<?= $filterSession === '' ? 'true' : 'false' ?>">
      <span class="session-tab-name">All</span>
    </a>
    <?php foreach ($sessions as $sn): ?>
    <?php
      $tabUrl = admin_session_query($baseUrl, $sn, $extraQuery);
      $count = (int) ($stats[$sn]['students'] ?? 0);
      $isActive = $filterSession === $sn;
    ?>
    <a href="<?= e($tabUrl) ?>"
       class="session-tab<?= $isActive ? ' is-active' : '' ?>"
       role="tab"
       aria-selected="<?= $isActive ? 'true' : 'false' ?>"
       title="Session <?= e($sn) ?> — <?= $count ?> student<?= $count === 1 ? '' : 's' ?>">
      <span class="session-tab-name"><?= e(session_short_label($sn)) ?></span>
      <?php if ($count > 0): ?>
      <span class="session-tab-count"><?= $count ?></span>
      <?php endif; ?>
    </a>
    <?php endforeach; ?>
  </div>
  <?php if ($filterSession !== ''): ?>
  <p class="session-tabs-hint">
    Showing data for session <strong><?= e(session_short_label($filterSession)) ?></strong>
    <span class="text-muted">(<?= e($filterSession) ?>)</span>
  </p>
  <?php endif; ?>
</div>
