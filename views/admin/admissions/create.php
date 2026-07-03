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
  <h3 style="margin-top:1.5rem">Course Details</h3>
  <div class="form-grid">
    <div>
      <label>Trade *</label>
      <input type="hidden" name="trade" id="trade" value="">
      <div class="choice-picker" id="tradePicker">
        <?php foreach ($tradeOptions as $name): ?>
        <button type="button" data-value="<?= e($name) ?>"><?= e($name) ?></button>
        <?php endforeach; ?>
      </div>
      <p id="tradeSelectedLabel" style="margin:0.4rem 0 0;font-size:0.85rem;color:var(--admin-on-surface-variant)">Selected: —</p>
    </div>
    <div>
      <label>Session *</label>
      <select name="session" id="session" required>
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
      <select name="status">
        <option value="Pending" selected>Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>
    <div>
      <label>BSCC (Student Credit Card)</label>
      <input type="hidden" name="student_credit_card" id="student_credit_card" value="No">
      <div class="choice-picker" id="bsccPicker">
        <button type="button" data-value="No" class="is-active">No</button>
        <button type="button" data-value="Yes">Yes</button>
      </div>
      <p id="bsccSelectedLabel" style="margin:0.4rem 0 0;font-size:0.85rem;color:var(--admin-on-surface-variant)">Selected: No</p>
    </div>
    <div>
      <label>PWD Claim</label>
      <select name="pwd_claim">
        <option value="No" selected>No</option>
        <option value="Yes">Yes</option>
      </select>
    </div>
  </div>

  <div id="bscc_details_box" class="bscc-details-box">
    <h4 style="margin:0 0 0.75rem">BSCC Bank Account Details</h4>
    <p style="margin:0 0 1rem;font-size:0.85rem;color:var(--admin-on-surface-variant)">BSCC = Yes select karne par yeh fields open hote hain.</p>
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
      <div>
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
  function bindPicker(pickerId, inputId, labelId, onChange) {
    var picker = document.getElementById(pickerId);
    var input = document.getElementById(inputId);
    var label = document.getElementById(labelId);
    if (!picker || !input) return;

    picker.addEventListener('click', function (e) {
      var btn = e.target.closest('button[data-value]');
      if (!btn || !picker.contains(btn)) return;
      e.preventDefault();
      var value = btn.getAttribute('data-value') || '';
      input.value = value;
      picker.querySelectorAll('button').forEach(function (b) {
        b.classList.toggle('is-active', b === btn);
      });
      if (label) label.textContent = 'Selected: ' + (value || '—');
      if (typeof onChange === 'function') onChange(value);
    });
  }

  var bsccBox = document.getElementById('bscc_details_box');
  var bankInput = document.getElementById('student_credit_card_bank');
  var accountInput = document.getElementById('student_credit_card_account');

  function toggleBscc(value) {
    var show = value === 'Yes';
    if (bsccBox) bsccBox.classList.toggle('is-open', show);
    if (bankInput) bankInput.required = show;
    if (accountInput) accountInput.required = show;
    if (!show) {
      ['student_credit_card_bank', 'student_credit_card_holder', 'student_credit_card_account', 'student_credit_card_ifsc', 'student_credit_card_branch'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
      });
    }
  }

  bindPicker('tradePicker', 'trade', 'tradeSelectedLabel');
  bindPicker('bsccPicker', 'student_credit_card', 'bsccSelectedLabel', toggleBscc);
  toggleBscc(document.getElementById('student_credit_card').value || 'No');

  var form = document.getElementById('adminAdmissionForm');
  if (form) {
    form.addEventListener('submit', function (e) {
      var trade = document.getElementById('trade');
      if (!trade || !trade.value) {
        e.preventDefault();
        alert('Please select a Trade (Electrician or Fitter).');
        return false;
      }
    });
  }
})();
</script>
