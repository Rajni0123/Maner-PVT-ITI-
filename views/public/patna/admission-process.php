<?php
$navActive = 'admission';
$trades = $trades ?? [];
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Admission Process</h1>
    <p>Requirements, pathways, and how to join our NCVT trades</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-explore">
      <div class="pti-explore__card">
        <h3>1. Check Eligibility</h3>
        <p>Most trades require 10th pass from a recognized board. Confirm seats and session dates.</p>
      </div>
      <div class="pti-explore__card">
        <h3>2. Apply Online</h3>
        <p>Fill the admission form with personal, academic, and document details.</p>
      </div>
      <div class="pti-explore__card">
        <h3>3. Verification</h3>
        <p>Visit campus with originals for document verification and counseling.</p>
      </div>
      <div class="pti-explore__card">
        <h3>4. Fee &amp; Enrollment</h3>
        <p>Pay the applicable fee and receive enrollment confirmation.</p>
      </div>
    </div>

    <div class="pti-card" style="margin-top:2rem">
      <h2 style="margin-top:0;color:var(--pti-navy)">Available Trades</h2>
      <ul>
        <?php foreach ($trades as $t): ?>
        <li style="margin-bottom:.5rem">
          <a href="<?= site_url('trades/' . ($t['slug'] ?? '')) ?>"><strong><?= e($t['name']) ?></strong></a>
          — <?= e($t['duration'] ?? '') ?> · <?= e($t['eligibility'] ?? '10th Pass') ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <p style="margin-top:1.25rem">
        <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Apply Online</a>
        <a class="pti-btn pti-btn--outline" href="<?= site_url('bscc-info') ?>">BSCC Info</a>
      </p>
    </div>
  </div>
</section>
