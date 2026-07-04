<?php
$header = \App\Models\SiteData::header();
$logoText = $header['logo_text'] ?? 'MANER PRIVATE ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoText = strtoupper($logoText);
$pending = max(0, (float) ($summary['total'] ?? 0) - (float) ($summary['paid'] ?? 0));
?>
<div class="page report-page">
  <div class="brand-top-line"></div>
  <header class="form-header">
    <div class="institute-name"><?= e($logoText) ?></div>
    <div class="institute-tagline"><?= e(institute_tagline($header)) ?></div>
    <div class="form-title">Session Fee Report</div>
  </header>

  <div class="meta-bar">
    <div class="meta-cell">
      <span class="lbl">Session</span>
      <span class="val"><?= e($filterSession) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Students</span>
      <span class="val"><?= (int) ($summary['students'] ?? 0) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Total / Collected</span>
      <span class="val">₹<?= number_format((float) ($summary['total'] ?? 0), 2) ?> / ₹<?= number_format((float) ($summary['paid'] ?? 0), 2) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Pending</span>
      <span class="val">₹<?= number_format($pending, 2) ?></span>
    </div>
  </div>

  <?php if (!empty($byTrade)): ?>
  <div class="section">
    <div class="section-title">Trade-wise Summary</div>
    <table class="print-table">
      <thead>
        <tr><th>Trade</th><th>Records</th><th>Total</th><th>Collected</th><th>Pending</th></tr>
      </thead>
      <tbody>
      <?php foreach ($byTrade as $t): ?>
      <?php $tdue = max(0, (float) $t['total'] - (float) $t['paid']); ?>
      <tr>
        <td><?= e($t['trade']) ?></td>
        <td><?= (int) $t['cnt'] ?></td>
        <td>₹<?= number_format((float) $t['total'], 2) ?></td>
        <td>₹<?= number_format((float) $t['paid'], 2) ?></td>
        <td>₹<?= number_format($tdue, 2) ?></td>
      </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <?php endif; ?>

  <div class="section">
    <div class="section-title">Fee Records</div>
    <table class="print-table">
      <thead>
        <tr>
          <th>#</th>
          <th>Student</th>
          <th>Mobile</th>
          <th>Trade</th>
          <th>Type</th>
          <th>Amount</th>
          <th>Paid</th>
          <th>Due</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
      <?php if (empty($fees)): ?>
        <tr><td colspan="9" style="text-align:center">No fee records found.</td></tr>
      <?php else: ?>
        <?php foreach ($fees as $i => $f): ?>
        <?php $due = max(0, (float) $f['amount'] - (float) $f['paid_amount']); ?>
        <tr>
          <td><?= $i + 1 ?></td>
          <td><?= e($f['student_name']) ?></td>
          <td><?= e(format_mobile($f['mobile'] ?? '')) ?></td>
          <td><?= e($f['trade'] ?? '—') ?></td>
          <td><?= e($f['fee_type'] ?? '—') ?></td>
          <td>₹<?= number_format((float) $f['amount'], 2) ?></td>
          <td>₹<?= number_format((float) $f['paid_amount'], 2) ?></td>
          <td>₹<?= number_format($due, 2) ?></td>
          <td><?= e($f['status'] ?? '—') ?></td>
        </tr>
        <?php endforeach; ?>
      <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
