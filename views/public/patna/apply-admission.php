<?php
$navActive = 'apply';
$sessions = $sessions ?? [];
$trades = $trades ?? [];
$old = old();
$hideEnquiryPopup = true;
?>
<section class="pti-page-hero">
  <div class="pti-container">
    <h1>Online Admission Form</h1>
    <p>Fill the form carefully. Photo, Aadhaar, 10th marksheet and signature are mandatory.</p>
  </div>
</section>

<section class="pti-section">
  <div class="pti-container">
    <div class="pti-card">
      <?php if ($msg = flash('error')): ?><div class="pti-flash pti-flash--error"><?= e($msg) ?></div><?php endif; ?>
      <?php if ($msg = flash('success')): ?><div class="pti-flash pti-flash--success"><?= e($msg) ?></div><?php endif; ?>

      <form class="pti-form" method="post" action="<?= site_url('apply-admission') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <h3 style="color:var(--pti-navy);margin-top:0">Basic Details</h3>
        <div class="pti-grid-2">
          <div>
            <label>Full Name *</label>
            <input name="name" required value="<?= e($old['name'] ?? '') ?>">
          </div>
          <div>
            <label>Father's Name *</label>
            <input name="father_name" required value="<?= e($old['father_name'] ?? '') ?>">
          </div>
          <div>
            <label>Mother's Name</label>
            <input name="mother_name" value="<?= e($old['mother_name'] ?? '') ?>">
          </div>
          <div>
            <label>Mobile (10 digit) *</label>
            <input name="mobile" required maxlength="10" value="<?= e($old['mobile'] ?? '') ?>">
          </div>
          <div>
            <label>Email</label>
            <input type="email" name="email" value="<?= e($old['email'] ?? '') ?>">
          </div>
          <div>
            <label>Date of Birth</label>
            <input type="date" name="dob" value="<?= e($old['dob'] ?? '') ?>">
          </div>
          <div>
            <label>Gender</label>
            <select name="gender">
              <?php foreach (['', 'Male', 'Female', 'Other'] as $g): ?>
              <option value="<?= e($g) ?>" <?= (($old['gender'] ?? '') === $g) ? 'selected' : '' ?>><?= $g === '' ? '-- Select --' : e($g) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Category</label>
            <select name="category">
              <?php foreach (['General', 'OBC', 'SC', 'ST', 'EWS'] as $c): ?>
              <option value="<?= e($c) ?>" <?= (($old['category'] ?? 'General') === $c) ? 'selected' : '' ?>><?= e($c) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Aadhaar / UIDAI</label>
            <input name="uidai_number" maxlength="12" value="<?= e($old['uidai_number'] ?? '') ?>">
          </div>
        </div>

        <h3 style="color:var(--pti-navy)">Address</h3>
        <div class="pti-grid-2">
          <div>
            <label>Village / Town / City *</label>
            <input name="village_town_city" required value="<?= e($old['village_town_city'] ?? '') ?>">
          </div>
          <div>
            <label>District *</label>
            <input name="district" required value="<?= e($old['district'] ?? '') ?>">
          </div>
          <div>
            <label>Pincode *</label>
            <input name="pincode" required value="<?= e($old['pincode'] ?? '') ?>">
          </div>
          <div>
            <label>State</label>
            <input name="state" value="<?= e($old['state'] ?? 'Bihar') ?>">
          </div>
          <div>
            <label>Block</label>
            <input name="block" value="<?= e($old['block'] ?? '') ?>">
          </div>
          <div>
            <label>Police Station</label>
            <input name="police_station" value="<?= e($old['police_station'] ?? '') ?>">
          </div>
          <div>
            <label>Post Office</label>
            <input name="post_office" value="<?= e($old['post_office'] ?? '') ?>">
          </div>
          <div>
            <label>Nearby Landmark</label>
            <input name="nearby" value="<?= e($old['nearby'] ?? '') ?>">
          </div>
        </div>

        <h3 style="color:var(--pti-navy)">Academic &amp; Trade</h3>
        <div class="pti-grid-2">
          <div>
            <label>Session *</label>
            <select name="session" required>
              <option value="">-- Select Session --</option>
              <?php foreach ($sessions as $s): ?>
              <option value="<?= e($s['session_name']) ?>" <?= (($old['session'] ?? '') === $s['session_name']) ? 'selected' : '' ?>><?= e($s['session_name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Trade *</label>
            <select name="trade" required>
              <option value="">-- Select Trade --</option>
              <?php foreach ($trades as $t): ?>
              <option value="<?= e($t['name']) ?>" <?= (($old['trade'] ?? '') === $t['name']) ? 'selected' : '' ?>><?= e($t['name']) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>Shift</label>
            <select name="shift">
              <?php foreach (['', '1st', '2nd'] as $sh): ?>
              <option value="<?= e($sh) ?>" <?= (($old['shift'] ?? '') === $sh) ? 'selected' : '' ?>><?= $sh === '' ? '-- Select --' : e($sh) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div>
            <label>10th School</label>
            <input name="class_10th_school" value="<?= e($old['class_10th_school'] ?? '') ?>">
          </div>
          <div>
            <label>10th Marks Obtained</label>
            <input name="class_10th_marks_obtained" value="<?= e($old['class_10th_marks_obtained'] ?? '') ?>">
          </div>
          <div>
            <label>10th Total Marks</label>
            <input name="class_10th_total_marks" value="<?= e($old['class_10th_total_marks'] ?? '') ?>">
          </div>
          <div>
            <label>10th Percentage</label>
            <input name="class_10th_percentage" value="<?= e($old['class_10th_percentage'] ?? '') ?>">
          </div>
          <div>
            <label>Student Credit Card (BSCC)?</label>
            <select name="student_credit_card">
              <option value="No" <?= (($old['student_credit_card'] ?? 'No') === 'No') ? 'selected' : '' ?>>No</option>
              <option value="Yes" <?= (($old['student_credit_card'] ?? '') === 'Yes') ? 'selected' : '' ?>>Yes</option>
            </select>
          </div>
        </div>

        <h3 style="color:var(--pti-navy)">Documents *</h3>
        <div class="pti-grid-2">
          <div>
            <label>Photo *</label>
            <input type="file" name="photo" accept="image/*" required>
          </div>
          <div>
            <label>Aadhaar *</label>
            <input type="file" name="aadhaar" accept="image/*,.pdf" required>
          </div>
          <div>
            <label>10th Marksheet *</label>
            <input type="file" name="marksheet" accept="image/*,.pdf" required>
          </div>
          <div>
            <label>Signature *</label>
            <input type="file" name="signature" accept="image/*" required>
          </div>
          <div>
            <label>BSCC Document (if any)</label>
            <input type="file" name="student_credit_card_doc" accept="image/*,.pdf">
          </div>
        </div>

        <input type="hidden" name="pwd_claim" value="No">
        <button class="pti-btn pti-btn--primary pti-btn--lg" type="submit">Submit Application</button>
      </form>
    </div>
  </div>
</section>
