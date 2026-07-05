<?php
$header = \App\Models\SiteData::header();
$logoText = $header['logo_text'] ?? 'MANER PRIVATE ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoText = strtoupper($logoText);
$misCode = \App\Models\SiteData::setting('mis_code', 'PR10001156');
$due = max(0, (float) $fee['amount'] - (float) $fee['paid_amount']);
$status = trim((string) ($fee['status'] ?? 'Paid'));
$statusClass = strtolower($status) === 'paid' ? 'paid' : (strtolower($status) === 'pending' ? 'pending' : 'partial');
$sessionLabel = session_short_label($fee['session'] ?? '');
?>
<div class="page fee-receipt-page">
  <div class="brand-top-line"></div>

  <header class="form-header fee-receipt-header">
    <div class="institute-name"><?= e($logoText) ?></div>
    <div class="institute-tagline"><?= e(institute_tagline($header)) ?></div>
    <div class="institute-meta">
      Phone: <?= e(format_mobile($header['phone'] ?? '')) ?>
      <?php if (!empty($header['email'])): ?>
      &nbsp;|&nbsp; Email: <?= e($header['email']) ?>
      <?php endif; ?>
    </div>
    <div class="form-title">Fee Receipt</div>
    <div class="affiliation-badge">★ NCVT Affiliated &nbsp;|&nbsp; MIS Code: <?= e($misCode) ?> ★</div>
  </header>

  <div class="meta-bar fee-receipt-meta">
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
      <span class="lbl">Payment Mode</span>
      <span class="val"><?= e($fee['payment_method'] ?? 'Cash') ?></span>
    </div>
  </div>

  <div class="sections-wrap fee-receipt-body">
    <div class="section">
      <div class="section-title">Student Details</div>
      <div class="section-body">
        <div class="field-row">
          <div class="field"><span class="label">Student Name</span><span class="value"><?= e($fee['student_name']) ?></span></div>
          <div class="field"><span class="label">Father Name</span><span class="value"><?= e($fee['father_name'] ?: '—') ?></span></div>
        </div>
        <div class="field-row">
          <div class="field"><span class="label">Trade</span><span class="value"><?= e($fee['trade']) ?></span></div>
          <div class="field"><span class="label">Session</span><span class="value"><?= e($sessionLabel ?: '—') ?></span></div>
        </div>
        <div class="field-row">
          <div class="field"><span class="label">Mobile</span><span class="value mono"><?= e(format_mobile($fee['mobile'] ?? '')) ?></span></div>
          <div class="field"><span class="label">Academic Year</span><span class="value"><?= e($fee['academic_year'] ?? '—') ?></span></div>
        </div>
      </div>
    </div>

    <div class="fee-payment-panel">
      <div class="fee-amount-hero">
        <div class="fee-amount-hero-label">
          <span>Amount Received</span>
          <small>via <?= e($fee['payment_method'] ?? 'Cash') ?></small>
        </div>
        <div class="fee-amount-hero-value"><?= format_inr($fee['paid_amount']) ?></div>
      </div>

      <table class="fee-breakdown-table">
        <tbody>
          <tr>
            <td>Total Fee (This Record)</td>
            <td><?= format_inr($fee['amount']) ?></td>
          </tr>
          <tr>
            <td>Paid on This Receipt</td>
            <td><?= format_inr($fee['paid_amount']) ?></td>
          </tr>
          <tr>
            <td>Balance Due</td>
            <td class="<?= $due > 0 ? 'fee-due-highlight' : '' ?>"><?= format_inr($due) ?></td>
          </tr>
          <tr class="fee-breakdown-status">
            <td>Payment Status</td>
            <td><span class="fee-status-badge fee-status-<?= e($statusClass) ?>"><?= e($status) ?></span></td>
          </tr>
        </tbody>
      </table>
    </div>

    <?php if (!empty($fee['notes'])): ?>
    <div class="fee-receipt-notes">
      <strong>Remarks</strong>
      <p><?= e($fee['notes']) ?></p>
    </div>
    <?php endif; ?>

    <div class="form-bottom fee-receipt-signatures">
      <div class="sign-block">
        <div class="sign-box"></div>
        <div class="sign-label">Received By (Student / Guardian)</div>
      </div>
      <div class="sign-block">
        <div class="sign-box"></div>
        <div class="sign-label">Authorized Signatory</div>
      </div>
    </div>
  </div>

  <div class="form-footer fee-receipt-footer">
    <p>This is a computer-generated receipt and does not require a physical signature unless stamped by the institute.</p>
    <p><?= e($logoText) ?> · Receipt <?= e($fee['receipt_number'] ?? '') ?> · <?= format_date($fee['payment_date'] ?? date('Y-m-d')) ?></p>
  </div>
</div>
