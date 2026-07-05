<?php
$p = $prefill ?? [];
$hasPrefill = !empty($p['student_name']);
$hasFeePlan = !empty($p['has_fee_plan']);
?>
<div class="admin-page-header">
  <h1>Collect Fee</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees') ?>" class="btn btn-outline btn-sm">Back to Fees</a>
  </div>
</div>

<div class="card fee-search-card">
  <h3>Search Student</h3>
  <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">Name, mobile number, ya enrollment se search karein</p>
  <div class="fee-student-search">
    <span class="material-symbols-outlined fee-search-icon">search</span>
    <input type="search" id="studentSearch" placeholder="Search student..." autocomplete="off" value="<?= e($hasPrefill ? ($p['student_name'] ?? '') : '') ?>">
    <div id="studentSearchResults" class="fee-search-results hidden"></div>
  </div>
  <div id="selectedStudent" class="fee-selected-student<?= $hasPrefill ? '' : ' hidden' ?>">
    <div>
      <strong id="selectedStudentName"><?= e($p['student_name'] ?? '') ?></strong>
      <p id="selectedStudentMeta" style="margin:0.25rem 0 0;font-size:0.85rem;color:var(--admin-on-surface-variant)">
        <?php if ($hasPrefill): ?>
        <?= e($p['trade'] ?? '') ?><?= !empty($p['session']) ? ' · ' . e($p['session']) : '' ?><?= !empty($p['mobile']) ? ' · ' . e(format_mobile($p['mobile'])) : '' ?>
        <?php endif; ?>
      </p>
      <p id="selectedStudentDue" class="fee-pending-due hidden"></p>
    </div>
    <button type="button" class="btn btn-outline btn-sm" id="clearStudent">Change</button>
  </div>
</div>

<form method="post" action="<?= site_url('admin/fees/collect') ?>" class="card<?= $hasPrefill ? '' : ' hidden' ?>" id="collectFeeForm">
  <?= csrf_field() ?>
  <input type="hidden" name="admission_id" id="admissionId" value="<?= e((string) ($p['admission_id'] ?? '')) ?>">
  <input type="hidden" name="student_source" id="studentSource" value="<?= e($p['source'] ?? '') ?>">
  <input type="hidden" name="amount" id="feeAmount" value="">

  <h3>Installment Collection</h3>

  <div id="feePlanSummary" class="fee-plan-summary hidden">
    <div class="fee-plan-stat"><span>Total Admission</span><strong id="feePlanTotal">—</strong></div>
    <div class="fee-plan-stat"><span>Advance Paid</span><strong id="feePlanAdvance">—</strong></div>
    <div class="fee-plan-stat"><span>Collected</span><strong id="feePlanPaid">—</strong></div>
    <div class="fee-plan-stat fee-plan-stat-due"><span>Balance Due</span><strong id="feePlanBalance">—</strong></div>
  </div>

  <div class="form-grid">
    <div><label>Student Name *</label><input name="student_name" id="studentName" value="<?= e($p['student_name'] ?? '') ?>" required readonly></div>
    <div><label>Father Name</label><input name="father_name" id="fatherName" value="<?= e($p['father_name'] ?? '') ?>" readonly></div>
    <div><label>Mobile</label><input name="mobile" id="mobile" value="<?= e($p['mobile'] ?? '') ?>" readonly></div>
    <div><label>Trade *</label><input name="trade" id="trade" value="<?= e($p['trade'] ?? '') ?>" required readonly></div>
    <div>
      <label>Installment *</label>
      <select name="fee_type" id="feeTypeSelect" required>
        <option value="">Select student first</option>
      </select>
    </div>
    <div>
      <label>Collect Amount (₹) *</label>
      <input type="number" step="0.01" min="0.01" name="paid_amount" id="paidAmount" required placeholder="Enter installment amount">
      <p id="amountHint" class="fee-amount-hint hidden"></p>
    </div>
    <div>
      <label>Payment Method *</label>
      <select name="payment_method" required>
        <option value="Cash">Cash</option>
        <option value="UPI">UPI</option>
        <option value="Bank Transfer">Bank Transfer</option>
        <option value="Cheque">Cheque</option>
      </select>
    </div>
    <div><label>Academic Year</label><input name="academic_year" value="<?= e(date('Y') . '-' . (date('Y') + 1)) ?>"></div>
  </div>
  <div style="margin-top:1rem"><label>Notes</label><textarea name="notes" rows="2" placeholder="Optional remarks"></textarea></div>
  <button class="btn btn-primary" style="margin-top:1rem" id="collectSubmitBtn">
    <span class="material-symbols-outlined" style="font-size:18px">receipt_long</span>
    Collect Installment &amp; Generate Receipt
  </button>
</form>

<script>
window.FEE_SEARCH_URL = <?= json_encode(site_url('admin/fees/search')) ?>;
window.FEE_PREFILL = <?= json_encode($hasPrefill ? array_merge([
    'key' => $p['source'] ?? '',
    'name' => $p['student_name'] ?? '',
    'father_name' => $p['father_name'] ?? '',
    'mobile' => $p['mobile'] ?? '',
    'trade' => $p['trade'] ?? '',
    'session' => $p['session'] ?? '',
    'enrollment' => $p['enrollment'] ?? '',
    'admission_id' => (int) ($p['admission_id'] ?? 0),
    'label' => trim(($p['trade'] ?? '') . (!empty($p['session']) ? ' · ' . $p['session'] : '')),
    'pending_due' => (float) ($p['balance_due'] ?? $p['pending_due'] ?? 0),
    'total_admission_amount' => (float) ($p['total_admission_amount'] ?? 0),
    'advance_paid' => (float) ($p['advance_paid'] ?? 0),
    'total_paid' => (float) ($p['total_paid'] ?? 0),
    'balance_due' => (float) ($p['balance_due'] ?? 0),
    'has_fee_plan' => !empty($p['has_fee_plan']),
    'installment_options' => $p['installment_options'] ?? [],
    'next_installment' => $p['next_installment'] ?? 'Installment 1',
], []) : null) ?>;
</script>
<script src="<?= asset('js/fee-collect.js') ?>?v=<?= (int) @filemtime(base_path('assets/js/fee-collect.js')) ?>"></script>
