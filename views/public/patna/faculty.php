<?php
$navActive = 'about';
$faculty = $faculty ?? [];
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Faculty</h1>
    <p>Meet our teaching and administrative team</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-team">
      <?php if (empty($faculty)): ?>
      <div class="pti-card"><p>Faculty profiles will appear here once added in admin.</p></div>
      <?php endif; ?>
      <?php foreach ($faculty as $f):
        $photo = !empty($f['photo']) ? upload_url($f['photo']) : '';
      ?>
      <div class="pti-team__card">
        <div class="pti-team__avatar"<?= $photo ? ' style="background-image:url(\'' . e($photo) . '\')"' : '' ?>></div>
        <h3><?= e($f['name']) ?><?= !empty($f['is_principal']) ? ' ★' : '' ?></h3>
        <div class="role"><?= e($f['designation'] ?? 'Faculty') ?></div>
        <?php if (!empty($f['department'])): ?><div class="phone"><?= e($f['department']) ?></div><?php endif; ?>
        <?php if (!empty($f['qualification'])): ?><div class="phone"><?= e($f['qualification']) ?></div><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
