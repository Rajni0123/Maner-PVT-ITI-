<?php
$navActive = 'notices';
$notices = $notices ?? [];
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>News &amp; Notice</h1>
    <p>Latest announcements and holiday notices from the institute</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <?php if (empty($notices)): ?>
    <div class="pti-card"><p>No notices published yet.</p></div>
    <?php endif; ?>
    <?php foreach ($notices as $n): ?>
    <div class="pti-card" style="margin-bottom:1rem">
      <h3 style="margin:0 0 .35rem;color:var(--pti-navy)"><?= e($n['title']) ?></h3>
      <small style="color:var(--pti-accent);font-weight:700"><?= e(format_date($n['created_at'] ?? '')) ?></small>
      <p><?= nl2br(e($n['description'] ?? '')) ?></p>
      <?php if (!empty($n['pdf'])): ?>
      <p><a class="pti-btn pti-btn--outline" href="<?= e(upload_url($n['pdf'])) ?>" target="_blank" rel="noopener">Download PDF</a></p>
      <?php endif; ?>
    </div>
    <?php endforeach; ?>
  </div>
</section>
