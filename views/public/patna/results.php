<?php
$navActive = 'notices';
$results = $results ?? [];
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Results</h1>
    <p>Published examination and trade results</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <?php if (empty($results)): ?>
    <div class="pti-card"><p>No results published yet.</p></div>
    <?php endif; ?>
    <?php foreach ($results as $r): ?>
    <div class="pti-card" style="margin-bottom:1rem">
      <h3 style="margin:0 0 .35rem;color:var(--pti-navy)"><?= e($r['title'] ?? 'Result') ?></h3>
      <small style="color:var(--pti-muted)"><?= e(format_date($r['created_at'] ?? '')) ?></small>
      <?php if (!empty($r['description'])): ?><p><?= nl2br(e($r['description'])) ?></p><?php endif; ?>
      <?php if (!empty($r['pdf'])): ?>
      <p><a class="pti-btn pti-btn--outline" href="<?= e(upload_url($r['pdf'])) ?>" target="_blank" rel="noopener">Download</a></p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</section>
