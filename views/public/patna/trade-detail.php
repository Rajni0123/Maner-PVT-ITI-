<?php
$navActive = 'courses';
$trade = $trade ?? [];
$slug = $trade['slug'] ?? '';
$img = !empty($trade['image']) ? upload_url($trade['image']) : 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=1200&q=80';
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1><?= e($trade['name'] ?? 'Trade Details') ?></h1>
    <p><?= e(($trade['duration'] ?? '') . (!empty($trade['eligibility']) ? ' · Eligibility: ' . $trade['eligibility'] : '')) ?></p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container pti-grid-2">
    <div>
      <div class="pti-intro__media" style="background-image:url('<?= e($img) ?>');min-height:280px;margin-bottom:1rem"></div>
      <div class="pti-card">
        <h2 style="margin-top:0;color:var(--pti-navy)">Course Overview</h2>
        <p><?= e($trade['description'] ?: 'This NCVT trade program combines classroom theory with intensive workshop practice to prepare students for industrial roles.') ?></p>
        <?php if (!empty($trade['syllabus_pdf'])): ?>
        <p><a class="pti-btn pti-btn--outline" href="<?= e(upload_url($trade['syllabus_pdf'])) ?>" target="_blank" rel="noopener">Download Syllabus PDF</a></p>
        <?php endif; ?>
      </div>
    </div>
    <div class="pti-card">
      <h2 style="margin-top:0;color:var(--pti-navy)">Quick Facts</h2>
      <ul>
        <li><strong>Duration:</strong> <?= e($trade['duration'] ?? '—') ?></li>
        <li><strong>Eligibility:</strong> <?= e($trade['eligibility'] ?? '10th Pass') ?></li>
        <li><strong>Category:</strong> <?= e($trade['category'] ?? 'Engineering') ?></li>
        <?php if (!empty($trade['seats'])): ?>
        <li><strong>Seats:</strong> <?= e($trade['seats']) ?></li>
        <?php endif; ?>
      </ul>
      <p style="margin-top:1.25rem">
        <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Apply for this Course</a>
        <a class="pti-btn pti-btn--outline" href="<?= site_url('fee-structure') ?>">Fee List</a>
      </p>
    </div>
  </div>
</section>
