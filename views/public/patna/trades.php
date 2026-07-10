<?php
$navActive = 'courses';
$trades = $trades ?? [];
$heroBg = 'https://images.unsplash.com/photo-1581092918056-0c4c3acd3789?auto=format&fit=crop&w=800&q=80';
$tradeImages = [
    'electrician' => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?auto=format&fit=crop&w=600&q=80',
    'fitter' => 'https://images.unsplash.com/photo-1504328345606-18bbc8c9d7d1?auto=format&fit=crop&w=600&q=80',
];
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Our Courses</h1>
    <p>Explore NCVT certified courses with practical training and placement assistance</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-courses">
      <?php if (empty($trades)): ?>
      <div class="pti-card"><p>No active courses found. Add trades from the admin panel.</p></div>
      <?php endif; ?>
      <?php foreach ($trades as $t):
        $slug = $t['slug'] ?? '';
        $img = !empty($t['image']) ? upload_url($t['image']) : ($tradeImages[$slug] ?? $heroBg);
      ?>
      <article class="pti-course">
        <div class="pti-course__img" style="background-image:url('<?= e($img) ?>')"></div>
        <div class="pti-course__body">
          <span class="pti-badge"><?= e(($t['duration'] ?? '2 Years') . ' · ' . ($t['eligibility'] ?? '10th Pass')) ?></span>
          <h3><?= e($t['name']) ?></h3>
          <p><?= e($t['description'] ?: 'Industry-aligned vocational training with hands-on workshop practice.') ?></p>
          <a class="pti-btn pti-btn--primary" href="<?= site_url('trades/' . $slug) ?>">View Details</a>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <p style="text-align:center;margin-top:2rem">
      <a class="pti-btn pti-btn--outline" href="<?= site_url('fee-structure') ?>">View Fee Structure</a>
      <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Apply Now</a>
    </p>
  </div>
</section>
