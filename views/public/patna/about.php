<?php
$navActive = 'about';
$page = $page ?? [];
$settings = $settings ?? [];
$header = $header ?? [];
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
$aboutHtml = $page['content'] ?? '';
$principal = $settings['principal_name'] ?? '';
$message = $settings['principal_message'] ?? '';
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>About Us</h1>
    <p>Learn more about <?= e($logoText) ?> — NCVT affiliated industrial training</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container pti-grid-2">
    <div class="pti-card">
      <h2 style="margin-top:0;color:var(--pti-navy)">Our Institute</h2>
      <?php if ($aboutHtml !== ''): ?>
        <div><?= $aboutHtml ?></div>
      <?php else: ?>
        <p>We are dedicated to providing exceptional vocational training and technical education. Our NCVT-affiliated programs prepare students for successful careers in industrial and technical sectors through modern workshops, experienced faculty, and placement support.</p>
        <p>Students gain hands-on practical knowledge alongside theoretical understanding, becoming job-ready professionals and skilled technicians.</p>
      <?php endif; ?>
    </div>
    <div class="pti-card">
      <h2 style="margin-top:0;color:var(--pti-navy)">Principal's Message</h2>
      <?php if ($principal): ?><p><strong><?= e($principal) ?></strong></p><?php endif; ?>
      <p><?= e($message ?: 'Welcome to our institute. We are committed to quality technical education and the holistic development of every trainee.') ?></p>
      <div style="margin-top:1.25rem">
        <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Apply Now</a>
        <a class="pti-btn pti-btn--outline" href="<?= site_url('contact') ?>">Contact</a>
      </div>
    </div>
  </div>
</section>

<section class="pti-section pti-section--alt">
  <div class="pti-container">
    <div class="pti-section__head">
      <h2>Mission, Vision &amp; Objectives</h2>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-mv">
      <div class="pti-mv__card">
        <h3>Mission</h3>
        <p>Deliver industry-relevant technical education and skill development that empowers youth for employment and entrepreneurship.</p>
      </div>
      <div class="pti-mv__card">
        <h3>Vision</h3>
        <p>Be a trusted center of excellence in vocational training with modern infrastructure and strong industry linkages.</p>
      </div>
      <div class="pti-mv__card">
        <h3>Objectives</h3>
        <ul>
          <li>Quality NCVT trade training</li>
          <li>Practical workshop mastery</li>
          <li>Placement assistance</li>
          <li>Holistic student development</li>
        </ul>
      </div>
    </div>
  </div>
</section>
