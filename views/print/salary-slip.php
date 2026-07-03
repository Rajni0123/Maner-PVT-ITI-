<?php
$s = $slip;
$logoText = $header['logo_text'] ?? 'MANER PRIVATE ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoText = strtoupper($logoText);
$misCode = \App\Models\SiteData::setting('mis_code', 'PR10001156');
$period = month_name((int) $s['slip_month']) . ' ' . (int) $s['slip_year'];

$earnings = [
    'Basic Salary' => (float) $s['basic_salary'],
    'HRA' => (float) $s['hra'],
    'DA' => (float) $s['da'],
    'Other Allowances' => (float) $s['other_allowances'],
];
$deductions = [
    'Provident Fund (PF)' => (float) $s['pf_deduction'],
    'ESI' => (float) $s['esi_deduction'],
    'Income Tax (TDS)' => (float) $s['tax_deduction'],
    'Other Deductions' => (float) $s['other_deductions'],
];
?>
<div class="page salary-slip-page">
  <div class="brand-top-line"></div>

  <header class="form-header">
    <div class="institute-name"><?= e($logoText) ?></div>
    <div class="institute-tagline"><?= e(institute_tagline($header)) ?></div>
    <div class="institute-meta">Phone: <?= e(format_mobile($header['phone'] ?? '')) ?> &nbsp;|&nbsp; Email: <?= e($header['email'] ?? '') ?></div>
    <div class="form-title">Salary Slip</div>
    <div class="affiliation-badge">★ NCVT Affiliated &nbsp;|&nbsp; MIS Code: <?= e($misCode) ?> ★</div>
  </header>

  <div class="meta-bar salary-meta-bar">
    <div class="meta-cell">
      <span class="lbl">Slip Number</span>
      <span class="val mono"><?= e($s['slip_number'] ?: '—') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Pay Period</span>
      <span class="val"><?= e($period) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Paid Days</span>
      <span class="val"><?= (int) $s['paid_days'] ?> / <?= (int) $s['working_days'] ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Generated On</span>
      <span class="val"><?= e(format_date($s['generated_at'])) ?></span>
    </div>
  </div>

  <div class="section">
    <div class="section-title">Employee Details</div>
    <div class="section-body">
      <div class="field-row">
        <div class="field"><span class="label">Employee Name</span><span class="value"><?= e($s['name']) ?></span></div>
        <div class="field"><span class="label">Employee Code</span><span class="value mono"><?= e($s['employee_code'] ?: '—') ?></span></div>
      </div>
      <div class="field-row">
        <div class="field"><span class="label">Designation</span><span class="value"><?= e($s['designation']) ?></span></div>
        <div class="field"><span class="label">Department</span><span class="value"><?= e($s['department'] ?: '—') ?></span></div>
      </div>
      <div class="field-row">
        <div class="field"><span class="label">Date of Joining</span><span class="value"><?= e(format_date($s['date_of_joining'])) ?></span></div>
        <div class="field"><span class="label">Mobile</span><span class="value mono"><?= e(format_mobile($s['mobile'] ?? '')) ?></span></div>
      </div>
      <div class="field-row">
        <div class="field field-email span-2"><span class="label">Email</span><span class="value"><?= e($s['email'] ?: '—') ?></span></div>
      </div>
      <div class="field-row">
        <div class="field"><span class="label">Bank Name</span><span class="value"><?= e($s['bank_name'] ?: '—') ?></span></div>
        <div class="field"><span class="label">Account No.</span><span class="value mono"><?= e($s['account_number'] ?: '—') ?></span></div>
      </div>
      <div class="field-row">
        <div class="field"><span class="label">PAN</span><span class="value mono"><?= e($s['pan_number'] ?: '—') ?></span></div>
        <div class="field"><span class="label">PF No.</span><span class="value mono"><?= e($s['pf_number'] ?: '—') ?></span></div>
      </div>
    </div>
  </div>

  <div class="salary-tables">
    <div class="section salary-table-section">
      <div class="section-title">Earnings</div>
      <div class="section-body salary-table-body">
        <table class="salary-table">
          <thead><tr><th>Particulars</th><th class="amt">Amount (₹)</th></tr></thead>
          <tbody>
          <?php foreach ($earnings as $label => $amt): if ($amt <= 0) continue; ?>
          <tr><td><?= e($label) ?></td><td class="amt"><?= number_format($amt, 2) ?></td></tr>
          <?php endforeach; ?>
          <tr class="total-row"><td><strong>Gross Pay</strong></td><td class="amt"><strong><?= number_format((float) $s['gross_pay'], 2) ?></strong></td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="section salary-table-section">
      <div class="section-title">Deductions</div>
      <div class="section-body salary-table-body">
        <table class="salary-table">
          <thead><tr><th>Particulars</th><th class="amt">Amount (₹)</th></tr></thead>
          <tbody>
          <?php
          $hasDed = false;
          foreach ($deductions as $label => $amt):
              if ($amt <= 0) continue;
              $hasDed = true;
          ?>
          <tr><td><?= e($label) ?></td><td class="amt"><?= number_format($amt, 2) ?></td></tr>
          <?php endforeach; ?>
          <?php if (!$hasDed): ?>
          <tr><td colspan="2" class="empty-ded">No deductions</td></tr>
          <?php endif; ?>
          <tr class="total-row"><td><strong>Total Deductions</strong></td><td class="amt"><strong><?= number_format((float) $s['total_deductions'], 2) ?></strong></td></tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="net-pay-bar">
    <span>Net Pay (In Hand)</span>
    <strong><?= format_inr($s['net_pay']) ?></strong>
  </div>

  <?php if (!empty($s['notes'])): ?>
  <div class="salary-notes"><strong>Notes:</strong> <?= e($s['notes']) ?></div>
  <?php endif; ?>

  <div class="form-bottom salary-signatures">
    <div class="sign-block">
      <div class="sign-box"></div>
      <div class="sign-label">Employee Signature</div>
    </div>
    <div class="sign-block">
      <div class="sign-box"></div>
      <div class="sign-label">Authorized Signatory</div>
    </div>
    <div class="form-footer">
      This is a computer-generated salary slip for internal records.<br>
      <?= e($logoText) ?> &nbsp;|&nbsp; <?= e($s['slip_number'] ?: '') ?>
    </div>
  </div>
</div>
