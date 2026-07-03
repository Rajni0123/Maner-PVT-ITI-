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
      <select name="category">
        <?php foreach (['General', 'OBC', 'SC', 'ST', 'EWS'] as $cat): ?>
        <option value="<?= $cat ?>"><?= $cat ?></option>
        <?php endforeach; ?>
      </select>
    </div>
  </div>

  <?php
  $tradeOptions = $trades ?? [];
  if (!$tradeOptions) {
      $tradeOptions = [
          ['name' => 'Electrician'],
          ['name' => 'Fitter'],
      ];
  }
  $sessionOptions = $sessions ?? [];
  if (!$sessionOptions) {
      $sessionOptions = [
          ['session_name' => '2026-28'],
          ['session_name' => '2025-27'],
      ];
  }
  ?>
  <h3 style="margin-top:1.5rem">Course Details</h3>
  <div class="form-grid">
    <div>
      <label>Trade *</label>
      <select name="trade" id="trade" required>
        <option value="">Select trade</option>
        <?php foreach ($tradeOptions as $t): ?>
        <option value="<?= e($t['name']) ?>"><?= e($t['name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Session *</label>
      <select name="session" id="session" required>
        <option value="">Select session</option>
        <?php foreach ($sessionOptions as $s): ?>
        <option value="<?= e($s['session_name']) ?>"><?= e($s['session_name']) ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div>
      <label>Shift</label>
      <input name="shift" type="text" placeholder="Optional">
    </div>
    <div>
      <label>Status</label>
      <select name="status">
        <option value="Pending">Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>
    <div>
      <label>BSCC (Student Credit Card)</label>
      <select name="student_credit_card" id="student_credit_card">
        <option value="No">No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>
    <div>
      <label>PWD Claim</label>
      <select name="pwd_claim">
        <option value="No">No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>
  </div>

  <div id="bscc_details_box" class="card" style="display:none;margin-top:1rem;background:var(--admin-surface-container-low);border:1px solid var(--admin-outline-variant)">
    <h4 style="margin:0 0 0.75rem">BSCC Bank Account Details</h4>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">Student Credit Card select karne par bank details bharna zaroori hai.</p>
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
      <div>
        <label>Branch Name</label>
        <input name="student_credit_card_branch" id="student_credit_card_branch" placeholder="Branch">
      </div>
      <div id="bscc_doc_field">
        <label>BSCC Document</label>
        <input type="file" name="student_credit_card_doc" accept="image/*,.pdf">
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
    <div><label>Subject</label><input name="class_10th_subject"></div>
  </div>

  <h3 style="margin-top:1.5rem">Documents (optional)</h3>
  <div class="form-grid">
    <div><label>Photo</label><input type="file" name="photo" accept="image/*"></div>
    <div><label>Signature</label><input type="file" name="signature" accept="image/*"></div>
    <div><label>Aadhaar</label><input type="file" name="aadhaar" accept="image/*,.pdf"></div>
    <div><label>10th Marksheet</label><input type="file" name="marksheet" accept="image/*,.pdf"></div>
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

  function toggleBscc() {
    if (!bsccSelect || !bsccBox) return;
    var show = bsccSelect.value === 'Yes';
    bsccBox.style.display = show ? 'block' : 'none';
    if (bankInput) bankInput.required = show;
    if (accountInput) accountInput.required = show;
    if (!show) {
      if (bankInput) bankInput.value = '';
      if (accountInput) accountInput.value = '';
      var holder = document.getElementById('student_credit_card_holder');
      var ifsc = document.getElementById('student_credit_card_ifsc');
      var branch = document.getElementById('student_credit_card_branch');
      if (holder) holder.value = '';
      if (ifsc) ifsc.value = '';
      if (branch) branch.value = '';
    }
  }

  if (bsccSelect) {
    bsccSelect.addEventListener('change', toggleBscc);
    toggleBscc();
  }
})();
</script>
