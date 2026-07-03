<?php
function pf(string $label, ?string $value, bool $mono = false, string $fieldClass = '', bool $preserveCase = false): void
{
    $display = $preserveCase ? admission_display_value_preserve($value) : admission_display_value($value);
    $valueClass = 'value' . ($mono ? ' mono' : '');
    echo '<div class="field' . ($fieldClass ? ' ' . e($fieldClass) : '') . '"><span class="label">' . e($label) . '</span><span class="' . $valueClass . '">' . e($display) . '</span></div>';
}

function pf_row(array $fields, string $rowClass = ''): void
{
    echo '<div class="field-row' . ($rowClass ? ' ' . $rowClass : '') . '">';
    foreach ($fields as $field) {
        pf($field[0], $field[1], $field[2] ?? false, $field[3] ?? '');
    }
    echo '</div>';
}

function pf_marks(?string $obtained, ?string $total): ?string
{
    $obt = trim((string) ($obtained ?? ''));
    $tot = trim((string) ($total ?? ''));
    if ($obt === '' && $tot === '') {
        return null;
    }
    return ($obt ?: '—') . ' / ' . ($tot ?: '—');
}

$photo = (!empty($docs['photo']) && upload_exists($docs['photo'])) ? upload_url($docs['photo']) : '';
$signature = (!empty($docs['signature']) && upload_exists($docs['signature'])) ? upload_url($docs['signature']) : '';
$scc = json_decode_safe($admission['student_credit_card_details'] ?? '');
$pct10 = education_percentage(
    $admission['class_10th_marks_obtained'] ?? null,
    $admission['class_10th_total_marks'] ?? null,
    $admission['class_10th_percentage'] ?? null
);
$bankName = trim((string) ($scc['bank_name'] ?? ''));
$logoText = $header['logo_text'] ?? 'MANER PRIVATE ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoText = strtoupper($logoText);
$misCode = \App\Models\SiteData::setting('mis_code', 'PR10001156');
?>
<div class="page">
  <div class="brand-top-line"></div>

  <header class="form-header">
    <div class="institute-name"><?= e($logoText) ?></div>
    <div class="institute-tagline"><?= e(institute_tagline($header)) ?></div>
    <div class="institute-meta">Phone: <?= e(format_mobile($header['phone'] ?? '')) ?> &nbsp;|&nbsp; Email: <?= e($header['email'] ?? '') ?></div>
    <div class="form-title">Admission Application Form</div>
    <div class="affiliation-badge">★ NCVT Affiliated &nbsp;|&nbsp; MIS Code: <?= e($misCode) ?> &nbsp;|&nbsp; Govt. of India ★</div>
  </header>

  <div class="meta-bar">
    <div class="meta-cell">
      <span class="lbl">Application ID</span>
      <span class="val mono"><?= e($appId) ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Session</span>
      <span class="val"><?= e($admission['session'] ?: '—') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Trade Applied</span>
      <span class="val"><?= e($admission['trade'] ?: '—') ?></span>
    </div>
    <div class="meta-cell">
      <span class="lbl">Date of Application</span>
      <span class="val"><?= e(format_date($admission['created_at'] ?? date('Y-m-d'))) ?></span>
    </div>
  </div>

  <div class="sections-wrap">
    <div class="section">
      <div class="section-title">Personal Information</div>
      <div class="section-body personal-with-photo">
        <div class="personal-fields">
          <?php pf_row([
              ['Full Name', $admission['name']],
              ["Father's Name", $admission['father_name']],
          ]); ?>
          <?php pf_row([
              ["Mother's Name", $admission['mother_name']],
              ['Date of Birth', format_dob($admission['dob'] ?? null)],
          ]); ?>
          <?php pf_row([
              ['Gender', $admission['gender']],
              ['Category', $admission['category']],
          ]); ?>
          <?php pf_row([
              ['Mobile No.', format_mobile($admission['mobile'] ?? ''), true],
              ['Aadhaar No.', format_uidai($admission['uidai_number'] ?? ''), true],
          ]); ?>
          <?php pf_row([
              ['Email ID', $admission['email'], false, 'span-2 field-email'],
          ]); ?>
          <?php pf_row([
              ['PWD Claim', $admission['pwd_claim']],
              ['PWD Category', $admission['pwd_category']],
          ]); ?>
        </div>
        <div class="personal-photo-wrap">
          <?php if ($photo): ?>
          <img src="<?= e($photo) ?>" alt="Photo" width="100" height="128">
          <?php else: ?>
          <div class="photo-placeholder">Affix Passport Size Photograph</div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="section">
      <div class="section-title">Correspondence Address</div>
      <div class="section-body">
        <?php pf_row([
            ['Village / Town', $admission['village_town_city']],
            ['Nearby / Landmark', $admission['nearby']],
        ]); ?>
        <?php pf_row([
            ['Police Station', $admission['police_station']],
            ['Post Office', $admission['post_office']],
        ]); ?>
        <?php pf_row([
            ['Block', $admission['block']],
            ['District', $admission['district']],
        ]); ?>
        <?php pf_row([
            ['State', $admission['state']],
            ['Pincode', $admission['pincode'], true],
        ]); ?>
      </div>
    </div>

    <div class="section">
      <div class="section-title">Educational Qualification</div>
      <div class="section-body">
        <?php pf_row([
            ['Qualification', $admission['qualification']],
            ['10th Board / School', $admission['class_10th_school']],
        ]); ?>
        <?php pf_row([
            ['10th Marks Obtained / Total', pf_marks($admission['class_10th_marks_obtained'] ?? null, $admission['class_10th_total_marks'] ?? null)],
            ['10th Percentage', $pct10],
        ]); ?>
      </div>
    </div>

    <div class="section">
      <div class="section-title">Admission &amp; Registration Details</div>
      <div class="section-body">
        <?php pf_row([
            ['Registration Type', $admission['registration_type']],
            ['BSCC Applied', $admission['student_credit_card']],
        ]); ?>
        <?php pf_row([
            ['Shift', $admission['shift']],
            ['Trade', $admission['trade']],
        ]); ?>
        <?php if (($admission['student_credit_card'] ?? '') === 'Yes' && $bankName): ?>
        <?php pf_row([
            ['Bank Name', $bankName, false, 'span-2'],
        ]); ?>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="declaration-block">
    <div class="declaration-title">Declaration by Applicant</div>
    <p class="declaration-text">
      I hereby declare that the information provided above is true and correct to the best of my knowledge and belief.
      I understand that any false information or concealment of facts may lead to cancellation of my admission at any stage.
      I agree to abide by all rules, regulations, and discipline of <?= e($logoText) ?> and the guidelines of NCVT / competent authority.
      I also undertake to pay all fees and dues as prescribed by the institute within the stipulated time.
    </p>
  </div>

  <div class="form-bottom">
    <div class="sign-block">
      <div class="sign-box">
        <?php if ($signature): ?>
        <img src="<?= e($signature) ?>" alt="Applicant signature" class="sign-image">
        <?php endif; ?>
      </div>
      <div class="sign-label">Signature of Applicant</div>
    </div>
    <div class="sign-block">
      <div class="sign-box"></div>
      <div class="sign-label">Principal / Authorized Signatory</div>
    </div>
  </div>

  <div class="form-footer">
    <?= e($logoText) ?> &nbsp;|&nbsp; <?= e($appId) ?> &nbsp;|&nbsp; Computer Generated — Valid with Official Seal
  </div>
</div>
