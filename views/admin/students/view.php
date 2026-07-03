<?php
$s = $student;
$admission = $admission ?? null;
$admissionDocs = $admission ? json_decode_safe($admission['documents'] ?? '') : [];
$scc = $admission ? json_decode_safe($admission['student_credit_card_details'] ?? '') : [];
$photoFile = $s['photo'] ?? ($admissionDocs['photo'] ?? '');
$appId = !empty($s['admission_id']) ? app_id((int) $s['admission_id'], $admission['created_at'] ?? null, $admission['session'] ?? null) : null;
$trades = $trades ?? [];
$sessions = $sessions ?? [];
$bsccApplied = $admission['student_credit_card'] ?? 'No';
?>
<div class="admin-page-header">
  <h1><?= e($s['student_name']) ?><?php if ($appId): ?> <span style="font-size:0.55em;font-weight:600;color:var(--admin-on-surface-variant)"><?= e($appId) ?></span><?php endif; ?></h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/fees/collect?student_id=' . $s['id']) ?>" class="btn btn-secondary btn-sm">Collect Fee</a>
    <?php if (!empty($s['admission_id'])): ?>
    <a href="<?= site_url('admin/admissions/view/' . $s['admission_id']) ?>" class="btn btn-outline btn-sm">View Admission</a>
    <a href="<?= site_url('admin/admissions/print/' . $s['admission_id']) ?>" target="_blank" class="btn btn-primary btn-sm">Print Form</a>
    <?php endif; ?>
    <a href="<?= site_url('admin/students') ?>" class="btn btn-outline btn-sm">Back</a>
  </div>
</div>

<?php if ($photoFile && upload_exists($photoFile)): ?>
<div class="card" style="display:flex;align-items:center;gap:1.25rem;padding:1rem 1.25rem">
  <img src="<?= e(upload_url($photoFile)) ?>" alt="Student photo" style="width:100px;height:120px;object-fit:cover;border:2px solid #131b2e;background:#f3f4f6">
  <div>
    <p style="font-weight:700;font-size:1.1rem;margin:0"><?= e($s['student_name']) ?></p>
    <p style="color:var(--admin-on-surface-variant);margin:0.25rem 0 0"><?= e($s['trade']) ?> &nbsp;|&nbsp; Session <?= e($s['session'] ?? '—') ?> &nbsp;|&nbsp; <span class="badge badge-<?= strtolower($s['status']) ?>"><?= e($s['status']) ?></span></p>
    <?php if ($s['enrollment_number']): ?><p style="margin:0.35rem 0 0;font-size:0.9rem">Enrollment: <strong><?= e($s['enrollment_number']) ?></strong></p><?php endif; ?>
  </div>
</div>
<?php endif; ?>

<form method="post" action="<?= site_url('admin/students/save/' . $s['id']) ?>" enctype="multipart/form-data" class="card" id="adminStudentForm">
  <h3>Edit Student Record</h3>
  <?= csrf_field() ?>

  <h4 style="margin-top:1.25rem;margin-bottom:0.75rem">Personal Details</h4>
  <div class="form-grid">
    <div><label>Student Name *</label><input name="student_name" value="<?= e($s['student_name']) ?>" required></div>
    <div><label>Father Name</label><input name="father_name" value="<?= e($s['father_name']) ?>"></div>
    <div><label>Mother Name</label><input name="mother_name" value="<?= e($s['mother_name']) ?>"></div>
    <div>
      <label>Mobile *</label>
      <input name="mobile" id="mobile" type="tel" inputmode="numeric" value="<?= e($s['mobile']) ?>" maxlength="10" pattern="\d{10}" required placeholder="10-digit mobile">
      <small id="mobile-msg" class="field-msg" style="display:block;margin-top:4px;font-size:12px"></small>
    </div>
    <div><label>Email</label><input type="email" name="email" value="<?= e($s['email']) ?>"></div>
    <div><label>DOB</label><input type="date" name="dob" value="<?= e($s['dob']) ?>"></div>
    <div><label>Gender</label><select name="gender"><option value="">—</option><?php foreach (['Male', 'Female', 'Other'] as $g): ?><option value="<?= $g ?>" <?= ($s['gender'] ?? '') === $g ? 'selected' : '' ?>><?= $g ?></option><?php endforeach; ?></select></div>
    <div>
      <label>Aadhaar (UIDAI)</label>
      <input name="uidai_number" id="uidai_number" type="text" inputmode="numeric" maxlength="14" value="<?= e(format_uidai($s['uidai_number'] ?? '')) ?>" placeholder="XXXX XXXX XXXX">
      <small id="uidai_number-msg" class="field-msg" style="display:block;margin-top:4px;font-size:12px"></small>
    </div>
    <div><label>Category</label><select name="category"><?php foreach (['General', 'OBC', 'SC', 'ST', 'EWS'] as $cat): ?><option value="<?= $cat ?>" <?= ($s['category'] ?? '') === $cat ? 'selected' : '' ?>><?= $cat ?></option><?php endforeach; ?></select></div>
    <div><label>PWD Claim</label><select name="pwd_claim"><option value="No" <?= ($s['pwd_claim'] ?? 'No') === 'No' ? 'selected' : '' ?>>No</option><option value="Yes" <?= ($s['pwd_claim'] ?? '') === 'Yes' ? 'selected' : '' ?>>Yes</option></select></div>
    <div><label>PWD Category</label><input name="pwd_category" value="<?= e($s['pwd_category']) ?>"></div>
  </div>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Course &amp; Enrollment</h4>
  <div class="form-grid">
    <div><label>Trade *</label>
      <select name="trade" required>
        <option value="">Select trade</option>
        <?php if ($trades): foreach ($trades as $t): ?>
        <option value="<?= e($t['name']) ?>" <?= ($s['trade'] ?? '') === $t['name'] ? 'selected' : '' ?>><?= e($t['name']) ?></option>
        <?php endforeach; else: ?>
        <option value="<?= e($s['trade']) ?>" selected><?= e($s['trade']) ?></option>
        <?php endif; ?>
      </select>
    </div>
    <div><label>Session</label>
      <select name="session">
        <option value="">—</option>
        <?php if ($sessions): foreach ($sessions as $sess): ?>
        <option value="<?= e($sess['session_name']) ?>" <?= ($s['session'] ?? '') === $sess['session_name'] ? 'selected' : '' ?>><?= e($sess['session_name']) ?></option>
        <?php endforeach; else: ?>
        <option value="<?= e($s['session']) ?>" selected><?= e($s['session']) ?></option>
        <?php endif; ?>
      </select>
    </div>
    <div><label>Shift</label><input name="shift" type="text" value="<?= e($s['shift'] ?? '') ?>" placeholder="Optional"></div>
    <div><label>Enrollment No</label><input name="enrollment_number" value="<?= e($s['enrollment_number']) ?>"></div>
    <div><label>Admission Date</label><input type="date" name="admission_date" value="<?= e($s['admission_date']) ?>"></div>
    <div><label>Academic Year</label><input name="academic_year" value="<?= e($s['academic_year']) ?>" placeholder="2026-2027"></div>
    <div><label>Qualification</label><input name="qualification" value="<?= e($s['qualification']) ?>"></div>
    <div><label>Status</label><select name="status"><?php foreach (['Active', 'Inactive', 'Graduated'] as $st): ?><option value="<?= $st ?>" <?= ($s['status'] ?? '') === $st ? 'selected' : '' ?>><?= $st ?></option><?php endforeach; ?></select></div>
    <div><label>MIS / ITI Code</label><input name="mis_iti_code" value="<?= e($s['mis_iti_code'] ?? 'PR10001156') ?>"></div>
  </div>

  <?php if ($admission): ?>
  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">BSCC Details</h4>
  <div class="form-grid">
    <div><label>BSCC Applied</label>
      <select name="student_credit_card" id="student_credit_card">
        <option value="No" <?= $bsccApplied === 'No' ? 'selected' : '' ?>>No</option>
        <option value="Yes" <?= $bsccApplied === 'Yes' ? 'selected' : '' ?>>Yes</option>
      </select>
    </div>
    <div id="bscc_bank_field"><label>BSCC Bank Name</label><input name="student_credit_card_bank" value="<?= e($scc['bank_name'] ?? '') ?>"></div>
    <div id="bscc_account_field"><label>BSCC Account Number</label><input name="student_credit_card_account" value="<?= e($scc['account_number'] ?? '') ?>"></div>
    <div><label>Registration Type</label><input name="registration_type" value="<?= e($admission['registration_type'] ?? '') ?>"></div>
  </div>
  <?php endif; ?>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Address</h4>
  <div class="form-grid">
    <div><label>Village / Town / City</label><input name="village_town_city" value="<?= e($s['village_town_city']) ?>"></div>
    <div><label>Nearby / Landmark</label><input name="nearby" value="<?= e($s['nearby']) ?>"></div>
    <div><label>Post Office</label><input name="post_office" value="<?= e($s['post_office']) ?>"></div>
    <div><label>Police Station</label><input name="police_station" value="<?= e($s['police_station']) ?>"></div>
    <div><label>Block</label><input name="block" value="<?= e($s['block']) ?>"></div>
    <div><label>District</label><input name="district" value="<?= e($s['district']) ?>"></div>
    <div><label>Pincode</label><input name="pincode" value="<?= e($s['pincode']) ?>" maxlength="6"></div>
    <div><label>State</label><input name="state" value="<?= e($s['state'] ?? 'Bihar') ?>"></div>
  </div>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">10th Education</h4>
  <div class="form-grid">
    <div><label>10th Board / School</label><input name="class_10th_school" value="<?= e($s['class_10th_school']) ?>"></div>
    <div><label>Marks Obtained</label><input name="class_10th_marks_obtained" id="class_10th_marks_obtained" value="<?= e($s['class_10th_marks_obtained']) ?>"></div>
    <div><label>Total Marks</label><input name="class_10th_total_marks" id="class_10th_total_marks" value="<?= e($s['class_10th_total_marks']) ?>"></div>
    <div><label>Percentage</label><input name="class_10th_percentage" id="class_10th_percentage" readonly value="<?= e($s['class_10th_percentage']) ?>"></div>
  </div>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">12th Education (if applicable)</h4>
  <div class="form-grid">
    <div><label>12th Board / School</label><input name="class_12th_school" value="<?= e($s['class_12th_school']) ?>"></div>
    <div><label>12th Subject</label><input name="class_12th_subject" value="<?= e($s['class_12th_subject']) ?>"></div>
    <div><label>Marks Obtained</label><input name="class_12th_marks_obtained" id="class_12th_marks_obtained" value="<?= e($s['class_12th_marks_obtained']) ?>"></div>
    <div><label>Total Marks</label><input name="class_12th_total_marks" id="class_12th_total_marks" value="<?= e($s['class_12th_total_marks']) ?>"></div>
    <div><label>Percentage</label><input name="class_12th_percentage" id="class_12th_percentage" readonly value="<?= e($s['class_12th_percentage']) ?>"></div>
  </div>

  <h4 style="margin-top:1.5rem;margin-bottom:0.75rem">Photo</h4>
  <div class="form-grid">
    <div><label>Update Photo</label><input type="file" name="photo" accept="image/*"></div>
  </div>

  <button type="submit" class="btn btn-primary" style="margin-top:1.5rem">Save Changes</button>
</form>

<?php if (!empty($s['admission_id'])): ?>
<form method="post" action="<?= site_url('admin/admissions/documents/' . $s['admission_id']) ?>" enctype="multipart/form-data" class="card">
  <h3>Upload / Replace Documents</h3>
  <?= csrf_field() ?>
  <div class="form-grid">
    <div><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
    <div><label>Signature</label><input type="file" name="signature" accept="image/*"></div>
    <div><label>Aadhaar</label><input type="file" name="aadhaar" accept="image/*,.pdf"></div>
    <div><label>10th Marksheet</label><input type="file" name="marksheet" accept="image/*,.pdf"></div>
    <div><label>BSCC Document</label><input type="file" name="student_credit_card_doc" accept="image/*,.pdf"></div>
  </div>
  <?php foreach (['photo' => 'Photo', 'signature' => 'Signature', 'aadhaar' => 'Aadhaar', 'marksheet' => '10th Marksheet', 'student_credit_card_doc' => 'BSCC Document'] as $k => $label): ?>
  <p style="margin-top:0.5rem"><?= e($label) ?>: <?php if (!empty($admissionDocs[$k]) && upload_exists($admissionDocs[$k])): ?><a href="<?= e(upload_url($admissionDocs[$k])) ?>" target="_blank">View / Download</a><?php elseif (!empty($admissionDocs[$k])): ?>File missing<?php else: ?>—<?php endif; ?></p>
  <?php endforeach; ?>
  <button class="btn btn-primary btn-sm" style="margin-top:1rem">Save Documents</button>
</form>
<?php endif; ?>

<script src="<?= asset('js/form-utils.js') ?>"></script>
<script>
(function () {
  var bscc = document.getElementById('student_credit_card');
  var bank = document.getElementById('bscc_bank_field');
  var account = document.getElementById('bscc_account_field');
  function toggleBscc() {
    if (!bscc) return;
    var show = bscc.value === 'Yes';
    if (bank) bank.style.display = show ? '' : 'none';
    if (account) account.style.display = show ? '' : 'none';
  }
  if (bscc) {
    bscc.addEventListener('change', toggleBscc);
    toggleBscc();
  }
})();
</script>
