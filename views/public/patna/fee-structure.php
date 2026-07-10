<?php
$navActive = 'fees';
$trades = $trades ?? [];
$feeData = $feeData ?? [];
$pdf = $pdf ?? '';
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Course &amp; Fee Structure</h1>
    <p>Detailed information about courses, duration, eligibility, and fees</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-card">
      <div class="pti-table-wrap">
        <table class="pti-table">
          <thead>
            <tr>
              <th>S.No</th>
              <th>Course Name</th>
              <th>Duration</th>
              <th>Eligibility</th>
              <th>Fee / Notes</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($trades)): ?>
            <tr><td colspan="6">No courses available.</td></tr>
            <?php else: ?>
            <?php foreach ($trades as $i => $t):
              $slug = $t['slug'] ?? '';
              $feeNote = '';
              if (!empty($feeData[$slug])) {
                $feeNote = is_array($feeData[$slug])
                  ? ($feeData[$slug]['total'] ?? json_encode($feeData[$slug]))
                  : (string) $feeData[$slug];
              } elseif (!empty($t['fee'])) {
                $feeNote = $t['fee'];
              } else {
                $feeNote = 'See prospectus / contact office';
              }
            ?>
            <tr>
              <td><?= $i + 1 ?></td>
              <td><?= e($t['name']) ?></td>
              <td><?= e($t['duration'] ?? '—') ?></td>
              <td><?= e($t['eligibility'] ?? '10th Pass') ?></td>
              <td><?= e($feeNote) ?></td>
              <td><a href="<?= site_url('trades/' . $slug) ?>">View Details</a></td>
            </tr>
            <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <?php if ($pdf): ?>
      <p style="margin-top:1rem"><a class="pti-btn pti-btn--outline" href="<?= e(upload_url($pdf)) ?>" target="_blank" rel="noopener">Download Fee PDF</a></p>
      <?php endif; ?>
    </div>

    <div class="pti-section__head" style="margin-top:2.5rem">
      <h2>Admission Process</h2>
      <div class="pti-section__rule"></div>
    </div>
    <div class="pti-explore">
      <div class="pti-explore__card"><h3>1. Fill Application</h3><p>Complete the online form or visit campus.</p></div>
      <div class="pti-explore__card"><h3>2. Document Verification</h3><p>Submit required documents for verification.</p></div>
      <div class="pti-explore__card"><h3>3. Fee Payment</h3><p>Confirm admission by paying the course fee.</p></div>
      <div class="pti-explore__card"><h3>4. Start Training</h3><p>Join workshops and begin your trade journey.</p></div>
    </div>

    <p style="text-align:center;margin-top:2rem">
      <a class="pti-btn pti-btn--primary" href="<?= site_url('apply-admission') ?>">Apply for Admission</a>
      <a class="pti-btn pti-btn--outline" href="<?= site_url('contact') ?>">Contact Us</a>
    </p>
  </div>
</section>
