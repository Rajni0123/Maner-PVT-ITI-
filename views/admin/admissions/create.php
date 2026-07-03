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
  <style>
    .adm-choice-row { display:flex; flex-wrap:wrap; gap:10px; margin-top:6px; }
    .adm-choice-btn {
      display:inline-block; min-width:120px; padding:10px 16px; border:2px solid #c6c6cd;
      background:#fff; color:#191c1e; font-weight:700; font-size:14px; cursor:pointer;
      border-radius:6px; text-align:center;
    }
    .adm-choice-btn:hover { border-color:#131b2e; }
    .adm-choice-btn.is-active { background:#131b2e; border-color:#131b2e; color:#fff; }
    .adm-selected-label { margin:8px 0 0; font-size:13px; color:#45464d; font-weight:600; }
    #bscc_details_box {
      display:none; margin-top:16px; padding:16px; background:#f2f4f6;
      border:1px solid #c6c6cd; border-radius:8px;
    }
    #bscc_details_box.is-open { display:block !important; }
  </style>

  <h3 style="margin-top:1.5rem">Course Details</h3>
  <div class="form-grid">
    <div>
      <label>Trade *</label>
      <input type="hidden" name="trade" id="trade" value="">
      <div class="adm-choice-row" id="tradePicker">
        <?php foreach ($tradeOptions as $name): ?>
        <button type="button" class="adm-choice-btn" data-value="<?= e($name) ?>"><?= e($name) ?></button>
        <?php endforeach; ?>
      </div>
      <p class="adm-selected-label" id="tradeSelectedLabel">Selected: —</p>
    </div>
    <div>
      <label>Session *</label>
      <select name="session" id="session" required style="width:100%;padding:10px;border:1px solid #c6c6cd;border-radius:6px;background:#fff;color:#191c1e">
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
      <select name="status" style="width:100%;padding:10px;border:1px solid #c6c6cd;border-radius:6px;background:#fff;color:#191c1e">
        <option value="Pending" selected>Pending</option>
        <option value="Approved">Approved</option>
        <option value="Rejected">Rejected</option>
      </select>
    </div>
    <div>
      <label>BSCC (Student Credit Card)</label>
      <input type="hidden" name="student_credit_card" id="student_credit_card" value="No">
      <div class="adm-choice-row" id="bsccPicker">
        <button type="button" class="adm-choice-btn is-active" data-value="No">No</button>
        <button type="button" class="adm-choice-btn" data-value="Yes">Yes</button>
      </div>
      <p class="adm-selected-label" id="bsccSelectedLabel">Selected: No</p>
    </div>
    <div>
      <label>PWD Claim</label>
      <select name="pwd_claim" style="width:100%;padding:10px;border:1px solid #c6c6cd;border-radius:6px;background:#fff;color:#191c1e">
        <option value="No" selected>No</option>
        <option value="Yes">Yes</option>
      </select>
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

    picker.querySelectorAll('button[data-value]').forEach(function (btn) {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var value = btn.getAttribute('data-value') || '';
        input.value = value;
        picker.querySelectorAll('button[data-value]').forEach(function (b) {
          b.classList.remove('is-active');
          b.style.background = '#fff';
          b.style.color = '#191c1e';
          b.style.borderColor = '#c6c6cd';
        });
        btn.classList.add('is-active');
        btn.style.background = '#131b2e';
        btn.style.color = '#fff';
        btn.style.borderColor = '#131b2e';
        if (label) label.textContent = 'Selected: ' + (value || '—');
        if (typeof onChange === 'function') onChange(value);
      });
    });
  }

  var bsccBox = document.getElementById('bscc_details_box');
  var bankInput = document.getElementById('student_credit_card_bank');
  var accountInput = document.getElementById('student_credit_card_account');

  function toggleBscc(value) {
    var show = value === 'Yes';
    if (bsccBox) {
      bsccBox.style.display = show ? 'block' : 'none';
      bsccBox.classList.toggle('is-open', show);
    }
    if (bankInput) bankInput.required = show;
    if (accountInput) accountInput.required = show;
    if (!show) {
      ['student_credit_card_bank', 'student_credit_card_holder', 'student_credit_card_account', 'student_credit_card_ifsc', 'student_credit_card_branch'].forEach(function (id) {
        var el = document.getElementById(id);
        if (el) el.value = '';
      });
    }
  }

  // Default: hide bank box
  if (bsccBox) bsccBox.style.display = 'none';

  // Default active style for BSCC No
  var defaultBscc = document.querySelector('#bsccPicker button[data-value="No"]');
  if (defaultBscc) {
    defaultBscc.style.background = '#131b2e';
    defaultBscc.style.color = '#fff';
    defaultBscc.style.borderColor = '#131b2e';
  }

  bindPicker('tradePicker', 'trade', 'tradeSelectedLabel');
  bindPicker('bsccPicker', 'student_credit_card', 'bsccSelectedLabel', toggleBscc);

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
