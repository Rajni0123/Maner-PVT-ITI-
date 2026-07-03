<div class="admin-page-header">
  <h1>Add Admission</h1>
  <div class="admin-page-actions">
    <a href="<?= site_url('admin/admissions') ?>" class="btn btn-outline btn-sm">Back to List</a>
  </div>
</div>

<form method="post" action="<?= site_url('admin/admissions/add') ?>" enctype="multipart/form-data" class="card" id="adminAdmissionForm">
  <?= csrf_field() ?>

  <h3>Personal Details</h3>
  <div class="form-grid">
    <div><label>Student Name *</label><input name="name" required></div>
    <div><label>Father's Name *</label><input name="father_name" required></div>
    <div><label>Mother's Name</label><input name="mother_name"></div>
    <div><label>Date of Birth</label><input type="date" name="dob"></div>
    <div>
      <label>Gender</label>
      <select name="gender">
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Other">Other</option>
      </select>
    </div>
    <div><label>Mobile *</label><input name="mobile" maxlength="10" pattern="\d{10}" required></div>
    <div><label>Email</label><input type="email" name="email"></div>
    <div><label>Aadhaar (UIDAI)</label><input name="uidai_number" id="uidai_number" maxlength="14" placeholder="XXXX XXXX XXXX"></div>
    <div>
      <label>Category</label>
      <select name="category" id="category" class="adm-select">
        <?php foreach (['General', 'OBC', 'SC', 'ST', 'EWS'] as $cat): ?>
        <option value="<?= $cat ?>"><?= $cat ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <?php
  $tradeOptions = [];
  foreach (($trades ?? []) as $t) {
      $name = trim((string) ($t['name'] ?? ''));
      if ($name !== '') {
          $tradeOptions[$name] = $name;
      }
  }
  if (!$tradeOptions) {
      $tradeOptions = ['Electrician' => 'Electrician', 'Fitter' => 'Fitter'];
  }
  $sessionOptions = [];
  foreach (($sessions ?? []) as $s) {
      $name = trim((string) ($s['session_name'] ?? ''));
      if ($name !== '') {
          $sessionOptions[$name] = $name;
      }
  }
  if (!$sessionOptions) {
      $sessionOptions = ['2026-28' => '2026-28', '2025-27' => '2025-27'];
  }
  ?>
  <style>
    .adm-select {
      width:100%; padding:10px 12px; border:1px solid #c6c6cd; border-radius:6px;
      background:#fff; color:#191c1e; font-size:14px; min-height:42px;
      appearance:auto; -webkit-appearance:menulist;
    }
    #bscc_details_box,
    #pwd_details_box,
    #category_docs_box,
    #pwd_docs_box {
      display:none; margin-top:16px; padding:16px; background:#f2f4f6;
      border:1px solid #c6c6cd; border-radius:8px;
    }
  </style>

  <h3 style="margin-top:1.5rem">Course Details</h3>
  <div class="form-grid">
    <div>
      <label>Trade *</label>
      <select name="trade" id="trade" class="adm-select" required>
        <option value="">Select trade</option>
        <?php foreach ($tradeOptions as $name): ?>
        <option value="<?= e($name) ?>"><?= e($name) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Session *</label>
      <select name="session" id="session" class="adm-select" required>
        <option value="">Select session</option>
        <?php foreach ($sessionOptions as $name): ?>
        <option value="<?= e($name) ?>"><?= e($name) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Shift</label>
      <input name="shift" type="text" placeholder="Optional">
    </div>
    <div>
      <label>Status</label>
      <select name="status" class="adm-select">
        <option value="Pending" selected>Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>
    <div>
      <label>BSCC (Student Credit Card)</label>
      <select name="student_credit_card" id="student_credit_card" class="adm-select">
        <option value="No" selected>No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>
    <div>
      <label>PWD Claim</label>
      <select name="pwd_claim" id="pwd_claim" class="adm-select">
        <option value="No" selected>No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>
  </div>

  <div id="pwd_details_box">
    <h4 style="margin:0 0 0.75rem">PWD Details</h4>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:#45464d">PWD Claim = Yes par yeh details bharna zaroori hai.</p>
    <div class="form-grid">
      <div>
        <label>PWD Category *</label>
        <select name="pwd_category" id="pwd_category" class="adm-select">
          <option value="">Select disability type</option>
          <option value="Orthopedic">Orthopedic</option>
          <option value="Visual">Visual</option>
          <option value="Hearing">Hearing</option>
          <option value="Speech">Speech</option>
          <option value="Mental Illness">Mental Illness</option>
          <option value="Multiple">Multiple</option>
          <option value="Other">Other</option>
        </select>
      </div>
      <div>
        <label>Disability Percentage (%)</label>
        <input name="pwd_percentage" id="pwd_percentage" type="number" min="0" max="100" placeholder="e.g. 40">
      </div>
    </div>
  </div>

  <div id="bscc_details_box">
    <h4 style="margin:0 0 0.75rem">BSCC Bank Account Details</h4>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:#45464d">BSCC = Yes select karne par yeh fields open hote hain.</p>
    <div class="form-grid">
      <div>
        <label>Bank Name *</label>
        <input name="student_credit_card_bank" id="student_credit_card_bank" placeholder="e.g. State Bank of India">
      </div>
      <div>
        <label>Account Holder Name</label>
        <input name="student_credit_card_holder" id="student_credit_card_holder" placeholder="As per bank passbook">
      </div>
      <div>
        <label>Account Number *</label>
        <input name="student_credit_card_account" id="student_credit_card_account" placeholder="Account number">
      </div>
      <div>
        <label>IFSC Code</label>
        <input name="student_credit_card_ifsc" id="student_credit_card_ifsc" placeholder="e.g. SBIN0001234">
      </div>
    </div>
  </div>

  <h3 style="margin-top:1.5rem">Address</h3>
  <div class="form-grid">
    <div><label>Village / Town / City</label><input name="village_town_city"></div>
    <div><label>Nearby Landmark</label><input name="nearby"></div>
    <div><label>Post Office</label><input name="post_office"></div>
    <div><label>Police Station</label><input name="police_station"></div>
    <div><label>Block</label><input name="block"></div>
    <div><label>District</label><input name="district"></div>
    <div><label>Pincode</label><input name="pincode" maxlength="6"></div>
    <div><label>State</label><input name="state" value="Bihar"></div>
  </div>

  <h3 style="margin-top:1.5rem">10th Education</h3>
  <div class="form-grid">
    <div><label>School Name</label><input name="class_10th_school"></div>
    <div><label>Marks Obtained</label><input name="class_10th_marks_obtained" id="class_10th_marks_obtained" type="number" min="0"></div>
    <div><label>Total Marks</label><input name="class_10th_total_marks" id="class_10th_total_marks" type="number" min="0"></div>
    <div><label>Percentage</label><input name="class_10th_percentage" id="class_10th_percentage" readonly placeholder="Auto calculated"></div>
  </div>

  <h3 style="margin-top:1.5rem">Documents (optional)</h3>
  <div class="form-grid">
    <div><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
    <div><label>Signature</label><input type="file" name="signature" accept="image/*"></div>
    <div><label>Aadhaar</label><input type="file" name="aadhaar" accept="image/*,.pdf"></div>
    <div><label>10th Marksheet</label><input type="file" name="marksheet" accept="image/*,.pdf"></div>
  </div>

  <div id="category_docs_box">
    <h4 style="margin:0 0 0.75rem">Category Documents (SC / ST / OBC / EWS)</h4>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:#45464d">Reserved category select karne par government documents upload karein.</p>
    <div class="form-grid">
      <div>
        <label>Caste Certificate (जाति प्रमाण पत्र)</label>
        <input type="file" name="caste_certificate" accept="image/*,.pdf">
      </div>
      <div>
        <label>Income Certificate (आय प्रमाण पत्र)</label>
        <input type="file" name="income_certificate" accept="image/*,.pdf">
      </div>
      <div>
        <label>Residential Certificate (आवासीय प्रमाण पत्र)</label>
        <input type="file" name="residential_certificate" accept="image/*,.pdf">
      </div>
    </div>
  </div>

  <div id="pwd_docs_box">
    <h4 style="margin:0 0 0.75rem">PWD Document</h4>
    <div class="form-grid">
      <div>
        <label>PWD Certificate (दिव्यांग प्रमाण पत्र)</label>
        <input type="file" name="pwd_certificate" accept="image/*,.pdf">
      </div>
    </div>
  </div>

  <button class="btn btn-primary" style="margin-top:1.5rem">Save Admission</button>
</form>

<script src="<?= asset('js/form-utils.js') ?>"></script>
<script>
(function () {
  var bsccSelect = document.getElementById('student_credit_card');
  var bsccBox = document.getElementById('bscc_details_box');
  var bankInput = document.getElementById('student_credit_card_bank');
  var accountInput = document.getElementById('student_credit_card_account');
  var pwdSelect = document.getElementById('pwd_claim');
  var pwdBox = document.getElementById('pwd_details_box');
  var pwdDocs = document.getElementById('pwd_docs_box');
  var pwdCategory = document.getElementById('pwd_category');
  var categorySelect = document.getElementById('category');
  var categoryDocs = document.getElementById('category_docs_box');

  function toggleBscc() {
    if (!bsccSelect || !bsccBox) return;
    var show = bsccSelect.value === 'Yes';
    bsccBox.style.display = show ? 'block' : 'none';
    if (bankInput) bankInput.required = show;
    if (accountInput) accountInput.required = show;
    if (!show) {
      ['student_credit_card_bank', 'student_credit_card_holder', 'student_credit_card_account', 'student_credit_card_ifsc'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
      });
    }
  }

  function togglePwd() {
    if (!pwdSelect) return;
    var show = pwdSelect.value === 'Yes';
    if (pwdBox) pwdBox.style.display = show ? 'block' : 'none';
    if (pwdDocs) pwdDocs.style.display = show ? 'block' : 'none';
    if (pwdCategory) {
      pwdCategory.required = show;
      if (!show) pwdCategory.value = '';
    }
    var pct = document.getElementById('pwd_percentage');
    if (pct && !show) pct.value = '';
  }

  function toggleCategoryDocs() {
    if (!categorySelect || !categoryDocs) return;
    var cat = (categorySelect.value || '').toUpperCase();
    var show = ['SC', 'ST', 'OBC', 'EWS'].indexOf(cat) !== -1;
    categoryDocs.style.display = show ? 'block' : 'none';
  }

  if (bsccBox) bsccBox.style.display = 'none';
  if (pwdBox) pwdBox.style.display = 'none';
  if (pwdDocs) pwdDocs.style.display = 'none';
  if (categoryDocs) categoryDocs.style.display = 'none';

  if (bsccSelect) {
    bsccSelect.addEventListener('change', toggleBscc);
    toggleBscc();
  }
  if (pwdSelect) {
    pwdSelect.addEventListener('change', togglePwd);
    togglePwd();
  }
  if (categorySelect) {
    categorySelect.addEventListener('change', toggleCategoryDocs);
    toggleCategoryDocs();
  }
})();
</script>
