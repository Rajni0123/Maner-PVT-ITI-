<?php
$header = \App\Models\SiteData::header();
$logoText = $header['logo_text'] ?? 'MANER PRIVATE ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoText = strtoupper($logoText);
$due = max(0, (float) $fee['amount'] - (float) $fee['paid_amount']);
?>
<div class="page fee-receipt-page">
  <div class="brand-top-line"></div>
  <header class="form-header">
    <div class="institute-name"><?= e($logoText) ?></div>
    <div class="institute-tagline"><?= e(institute_tagline($header)) ?></div>
    <div class="form-title">Fee Receipt</div>
  </header>

  <div class="meta-bar">
    <div class="meta-cell">
      <span class="lbl">Receipt No.</span>
      <span class="val mono"><?= e($fee['receipt_number'] ?? '—') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Date</span>
      <span class="val"><?= format_date($fee['payment_date'] ?? date('Y-m-d')) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Fee Type</span>
      <span class="val"><?= e($fee['fee_type'] ?? '—') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Payment</span>
      <span class="val"><?= e($fee['payment_method'] ?? 'Cash') ?></span>
    </div>
  </div>

  <div class="section">
    <div class="section-title">Student Details</div>
    <div class="section-body">
      <div class="field-row">
        <div class="field"><span class="label">Student Name</span><span class="value"><?= e($fee['student_name']) ?></span></div>
        <div class="field"><span class="label">Father Name</span><span class="value"><?= e($fee['father_name'] ?: '—') ?></span></div>
      </div>
      <div class="field-row">
        <div class="field"><span class="label">Trade</span><span class="value"><?= e($fee['trade']) ?></span></div>
        <div class="field"><span class="label">Mobile</span><span class="value mono"><?= e(format_mobile($fee['mobile'] ?? '')) ?></span></div>
      </div>
      <?php if (!empty($fee['academic_year'])): ?>
      <div class="field-row">
        <div class="field span-2"><span class="label">Academic Year</span><span class="value"><?= e($fee['academic_year']) ?></span></div>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <div class="net-pay-bar fee-receipt-total">
    <span>Amount Received</span>
    <strong><?= format_inr($fee['paid_amount']) ?></strong>
  </div>

  <div class="fee-receipt-summary">
    <p><span>Total Fee</span><strong><?= format_inr($fee['amount']) ?></strong></p>
    <p><span>Balance Due</span><strong><?= format_inr($due) ?></strong></p>
    <p><span>Status</span><strong><?= e($fee['status'] ?? 'Paid') ?></strong></p>
  </div>

  <?php if (!empty($fee['notes'])): ?>
  <div class="salary-notes"><strong>Notes:</strong> <?= e($fee['notes']) ?></div>
  <?php endif; ?>

  <div class="form-footer fee-receipt-footer">
    Computer generated receipt · <?= e($logoText) ?> · <?= e($fee['receipt_number'] ?? '') ?>
  </div>
</div>
