<?php
$d = $admission;
$docs = $d['documents_parsed'] ?? [];
$scc = json_decode_safe($d['student_credit_card_details'] ?? '');
$bsccApplied = trim((string) ($d['student_credit_card'] ?? 'No'));
?>
<div class="admin-page-header">
  <h1><?= e($appId) ?> — <?= e($d['name']) ?></h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees/collect?admission_id=' . $d['id']) ?>" class="btn btn-secondary btn-sm">Collect Fee</a>
    <a href="<?= site_url('admin/admissions/print/' . $d['id']) ?>" target="_blank" class="btn btn-primary btn-sm">Print Form</a>
    <a href="<?= site_url('admin/admissions') ?>" class="btn btn-outline btn-sm">Back</a>
  </div>
</div>

<div class="card" id="approve">
  <?php if (strtolower((string) $d['status']) === 'pending'): ?>
  <h3>Approve Admission</h3>
  <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">Total admission amount required. Advance payment (if any) will be recorded automatically.</p>
  <form method="post" action="<?= site_url('admin/admissions/status/' . $d['id']) ?>">
    <?= csrf_field() ?>
    <input type="hidden" name="status" value="Approved">
    <div class="form-grid">
      <div>
        <label>Total Admission Amount (₹) *</label>
        <input type="number" step="0.01" min="0.01" name="total_admission_amount" id="totalAdmissionAmount" required placeholder="e.g. 52000">
      </div>
      <div>
        <label>Advance Paid (₹)</label>
        <input type="number" step="0.01" min="0" name="advance_paid" id="advancePaid" value="0" placeholder="0">
      </div>
      <div>
        <label>Advance Payment Method</label>
        <select name="advance_payment_method">
          <option value="Cash">Cash</option>
          <option value="UPI">UPI</option>
          <option value="Bank Transfer">Bank Transfer</option>
          <option value="Cheque">Cheque</option>
        </select>
      </div>
      <div>
        <label>Balance After Advance</label>
        <input type="text" id="balanceAfterAdvance" readonly value="₹ 0.00" style="background:var(--admin-surface-container-low)">
      </div>
    </div>
    <div class="action-btns" style="margin-top:1rem">
      <button type="submit" class="btn btn-success btn-sm">✓ Approve &amp; Create Student</button>
    </div>
  </form>
  <form method="post" action="<?= site_url('admin/admissions/status/' . $d['id']) ?>" style="display:inline;margin-top:0.5rem" data-confirm="Reject this application?">
    <?= csrf_field() ?>
    <input type="hidden" name="status" value="Rejected">
    <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
  </form>
  <?php elseif (strtolower((string) $d['status']) === 'approved'): ?>
  <?php $fp = $feeProfile ?? student_admission_fee_profile((int) $d['id']); ?>
  <h3>Fee Summary</h3>
  <div class="detail-grid">
    <div class="detail-item"><label>Total Admission Amount</label><p><strong><?= format_inr($fp['total_admission_amount'] ?? $d['total_admission_amount'] ?? 0) ?></strong></p></div>
    <div class="detail-item"><label>Advance Paid</label><p><strong><?= format_inr($fp['advance_paid'] ?? $d['advance_paid'] ?? 0) ?></strong></p></div>
    <div class="detail-item"><label>Total Collected</label><p><strong><?= format_inr($fp['total_paid'] ?? 0) ?></strong></p></div>
    <div class="detail-item"><label>Balance Due</label><p><strong><?= format_inr($fp['balance_due'] ?? 0) ?></strong></p></div>
  </div>
  <?php if (($fp['balance_due'] ?? 0) > 0): ?>
  <a href="<?= site_url('admin/fees/collect?admission_id=' . $d['id']) ?>" class="btn btn-secondary btn-sm" style="margin-top:0.5rem">Collect Next Installment</a>
  <?php endif; ?>
  <?php else: ?>
  <form method="post" action="<?= site_url('admin/admissions/status/' . $d['id']) ?>" class="form-row-inline">
    <?= csrf_field() ?>
    <div>
      <label>Update Status</label>
      <select name="status">
        <?php foreach (['Pending', 'Rejected'] as $s): ?>
        <option value="<?= $s ?>" <?= strcasecmp($d['status'], $s) === 0 ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-primary btn-sm">Save Status</button>
  </form>
  <?php endif; ?>
</div>

<script>
(function () {
  var total = document.getElementById('totalAdmissionAmount');
  var advance = document.getElementById('advancePaid');
  var balance = document.getElementById('balanceAfterAdvance');
  if (!total || !advance || !balance) return;
  function update() {
    var t = parseFloat(total.value) || 0;
    var a = parseFloat(advance.value) || 0;
    if (a > t) a = t;
    var b = Math.max(0, t - a);
    balance.value = '₹ ' + b.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }
  total.addEventListener('input', update);
  advance.addEventListener('input', update);
  update();
})();
</script>

<div class="card detail-grid">
  <?php
  $fields = [
    'Student Name' => $d['name'],
    "Father's Name" => $d['father_name'],
    "Mother's Name" => $d['mother_name'],
    'DOB' => format_dob($d['dob'] ?? null),
    'Gender' => $d['gender'],
    'Category' => $d['category'],
    'UIDAI' => format_uidai($d['uidai_number'] ?? ''),
    'Mobile' => format_mobile($d['mobile'] ?? ''),
    'Email' => $d['email'],
    'Village' => $d['village_town_city'],
    'District' => $d['district'],
    'Pincode' => $d['pincode'],
    'Police Stn' => $d['police_station'],
    '10th School' => $d['class_10th_school'],
    '10th %' => education_percentage($d['class_10th_marks_obtained'] ?? null, $d['class_10th_total_marks'] ?? null, $d['class_10th_percentage'] ?? null),
    'Session' => $d['session'],
    'Trade' => $d['trade'],
    'Shift' => $d['shift'],
    'PWD Claim' => $d['pwd_claim'],
    'PWD Category' => $d['pwd_category'],
    'Registration' => $d['registration_type'],
  ];
  $wideFields = ['Email', 'Village', '10th School'];
  foreach ($fields as $label => $val): ?>
  <div class="detail-item<?= in_array($label, $wideFields, true) ? ' detail-item-wide' : '' ?>">
    <label><?= e($label) ?></label>
    <p><?php if ($label === 'Email' && $val): ?><a href="mailto:<?= e($val) ?>"><?= e($val) ?></a><?php else: ?><?= e($val ?: '—') ?><?php endif; ?></p>
  </div>
  <?php endforeach; ?>
</div>

<div class="card">
  <h3>BSCC — Bihar Student Credit Card</h3>
  <div class="detail-grid">
    <div class="detail-item">
      <label>BSCC Applied</label>
      <p><span class="badge badge-<?= strtolower($bsccApplied) === 'yes' ? 'approved' : 'pending' ?>"><?= e($bsccApplied ?: 'No') ?></span></p>
    </div>
    <div class="detail-item">
      <label>Registration Type</label>
      <p><?= e($d['registration_type'] ?: ($bsccApplied === 'Yes' ? 'Student Credit Card' : 'Regular')) ?></p>
    </div>
    <?php if ($bsccApplied === 'Yes'): ?>
    <div class="detail-item">
      <label>Bank Name</label>
      <p><?= e($scc['bank_name'] ?? '—') ?></p>
    </div>
    <div class="detail-item">
      <label>Account Number</label>
      <p class="mono"><?= e($scc['account_number'] ?? '—') ?></p>
    </div>
    <?php endif; ?>
  </div>
  <?php if ($bsccApplied === 'Yes'): ?>
  <p style="margin-top:1rem">
    BSCC Document:
    <?php if (!empty($docs['student_credit_card_doc']) && upload_exists($docs['student_credit_card_doc'])): ?>
    <a href="<?= e(upload_url($docs['student_credit_card_doc'])) ?>" target="_blank" class="text-on-tertiary-container hover:underline">View / Download</a>
    <?php elseif (!empty($docs['student_credit_card_doc'])): ?>
    File missing on server
    <?php else: ?>
    —
    <?php endif; ?>
  </p>
  <?php endif; ?>
</div>

<div class="card">
  <h3>Student Photo &amp; Documents</h3>
  <?php if (!empty($docs['photo']) && upload_exists($docs['photo'])): ?>
  <div style="margin-bottom:1rem">
    <img src="<?= e(upload_url($docs['photo'])) ?>" alt="Student photo" style="width:120px;height:145px;object-fit:cover;border:2px solid #131b2e;background:#f3f4f6">
  </div>
  <?php elseif (!empty($docs['photo'])): ?>
  <p style="color:#b45309;margin-bottom:1rem">Photo file missing on server. Please re-upload.</p>
  <?php endif; ?>
  <?php foreach (['photo' => 'Photo', 'signature' => 'Signature', 'aadhaar' => 'Aadhaar', 'marksheet' => '10th Marksheet', 'student_credit_card_doc' => 'BSCC Document'] as $k => $label): ?>
  <p><?= e($label) ?>: <?php if (!empty($docs[$k]) && upload_exists($docs[$k])): ?><a href="<?= e(upload_url($docs[$k])) ?>" target="_blank" class="text-on-tertiary-container hover:underline">View / Download</a><?php elseif (!empty($docs[$k])): ?>File missing<?php else: ?>—<?php endif; ?></p>
  <?php endforeach; ?>
</div>

<form method="post" action="<?= site_url('admin/admissions/documents/' . $d['id']) ?>" enctype="multipart/form-data" class="card">
  <h3>Upload / Replace Documents</h3>
  <?= csrf_field() ?>
  <div class="form-grid">
    <div><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
    <div><label>Signature</label><input type="file" name="signature" accept="image/*"></div>
    <div><label>Aadhaar</label><input type="file" name="aadhaar" accept="image/*,.pdf"></div>
    <div><label>10th Marksheet</label><input type="file" name="marksheet" accept="image/*,.pdf"></div>
    <div><label>BSCC Document</label><input type="file" name="student_credit_card_doc" accept="image/*,.pdf"></div>
  </div>
  <button class="btn btn-primary btn-sm" style="margin-top:1rem">Save Documents</button>
</form>
