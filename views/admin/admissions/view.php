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

<div class="card">
  <?php if (strtolower((string) $d['status']) === 'pending'): ?>
  <div class="action-btns" style="margin-bottom:1rem">
    <form method="post" action="<?= site_url('admin/admissions/status/' . $d['id']) ?>" style="display:inline">
      <?= csrf_field() ?>
      <input type="hidden" name="status" value="Approved">
      <button type="submit" class="btn btn-success btn-sm">✓ Approve Application</button>
    </form>
    <form method="post" action="<?= site_url('admin/admissions/status/' . $d['id']) ?>" style="display:inline" data-confirm="Reject this application?">
      <?= csrf_field() ?>
      <input type="hidden" name="status" value="Rejected">
      <button type="submit" class="btn btn-danger btn-sm">✕ Reject</button>
    </form>
  </div>
  <?php endif; ?>
  <form method="post" action="<?= site_url('admin/admissions/status/' . $d['id']) ?>" class="form-row-inline">
    <?= csrf_field() ?>
    <div>
      <label>Update Status</label>
      <select name="status">
        <?php foreach (['Pending', 'Approved', 'Rejected'] as $s): ?>
        <option value="<?= $s ?>" <?= strcasecmp($d['status'], $s) === 0 ? 'selected' : '' ?>><?= $s ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <button class="btn btn-primary btn-sm">Save Status</button>
  </form>
</div>

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
