<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$sessions = $sessions ?? [];
$trades = $trades ?? [];

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$helpline = $header['phone'] ?? '+91-9155401839';
$footerAddress = $footer['address'] ?? "Station Road, Near Petrol Pump,\nManer, Patna,\nBihar - 801108";
$footerAbout = $footer['about_text'] ?? "Empowering Bihar's youth with technical skills and industrial proficiency. An NCVT affiliated institution committed to excellence since 2012.";
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. NCVT Affiliated Institution. All Rights Reserved.';

$tradeIcons = [
    'electrician' => 'bolt',
    'fitter' => 'settings',
    'copa' => 'computer',
    'mechanic-diesel' => 'precision_manufacturing',
];

$pageTitle = $title ?? 'Online Admission Portal 2026 | Maner Private ITI';
$extraCss = ['admission.css'];
$navActive = 'admission';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
<script>window.APP_BASE = <?= json_encode(app_base_path()) ?>;</script>
</head>
<body class="bg-surface text-on-surface font-body-md selection:bg-secondary-fixed selection:text-on-secondary-fixed">

<?php require base_path('views/partials/design-nav-admission.php'); ?>

<main class="max-w-container-max mx-auto px-gutter py-12 md:py-section-gap">
  <div class="grid grid-cols-1 lg:grid-cols-12 gap-12">
    <aside class="lg:col-span-4 space-y-8">
      <div class="bg-white border border-outline-variant p-8 shadow-sm">
        <h3 class="font-headline-md text-headline-md mb-6">Application Progress</h3>
        <div class="space-y-6 relative">
          <div class="absolute left-4 top-2 bottom-2 w-0.5 bg-outline-variant z-0"></div>
          <div class="flex items-center gap-4 relative z-10" id="step1-indicator">
            <div class="w-8 h-8 rounded-full bg-primary text-white flex items-center justify-center font-bold text-sm">1</div>
            <div>
              <p class="font-bold text-primary">Basic Details</p>
              <p class="text-xs text-outline">Personal Information</p>
            </div>
          </div>
          <div class="flex items-center gap-4 relative z-10 opacity-50" id="step2-indicator">
            <div class="w-8 h-8 rounded-full bg-surface-container-highest border border-outline text-outline flex items-center justify-center font-bold text-sm">2</div>
            <div>
              <p class="font-bold text-outline">Academic Info</p>
              <p class="text-xs text-outline">Address &amp; 10th Grade</p>
            </div>
          </div>
          <div class="flex items-center gap-4 relative z-10 opacity-50" id="step3-indicator">
            <div class="w-8 h-8 rounded-full bg-surface-container-highest border border-outline text-outline flex items-center justify-center font-bold text-sm">3</div>
            <div>
              <p class="font-bold text-outline">Trade Selection</p>
              <p class="text-xs text-outline">Preferences &amp; Documents</p>
            </div>
          </div>
        </div>
      </div>

      <div class="bg-primary-container p-8 border-l-4 border-secondary-container">
        <div class="flex items-center gap-3 mb-4">
          <span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1;">support_agent</span>
          <h4 class="font-headline-md text-headline-md text-white">Admission Support</h4>
        </div>
        <p class="text-on-primary-container text-body-md mb-6">Need assistance with the 2026 application cycle? Our counselors are here to help Bihar students with specific documentation.</p>
        <ul class="space-y-4 mb-8">
          <li class="flex items-start gap-3">
            <span class="material-symbols-outlined text-secondary-container text-sm mt-1">check_circle</span>
            <span class="text-white font-medium">BSCC Documentation Assistance</span>
          </li>
          <li class="flex items-start gap-3">
            <span class="material-symbols-outlined text-secondary-container text-sm mt-1">check_circle</span>
            <span class="text-white font-medium">Bonafide Certificate Issuance</span>
          </li>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-secondary-container text-sm mt-1">check_circle</span>
              <span class="text-white font-medium">Fee Structure Transparency</span>
            </li>
          </ul>
          <a href="<?= site_url('fee-structure') ?>" class="inline-block mb-6 text-secondary-fixed font-bold text-sm hover:underline">View detailed fee breakdown →</a>
          <div class="p-4 bg-primary rounded-lg border border-outline/20">
          <p class="text-xs font-label-sm text-on-primary-container mb-1">HELPLINE (10 AM - 5 PM)</p>
          <p class="text-headline-md font-bold text-white"><?= e($helpline) ?></p>
        </div>
      </div>

      <div class="border-2 border-on-tertiary-container p-6 bg-surface-container-lowest">
        <div class="flex items-center gap-4 mb-4">
          <div class="w-12 h-12 bg-on-tertiary-container flex items-center justify-center rounded">
            <span class="material-symbols-outlined text-white">account_balance</span>
          </div>
          <h5 class="font-bold text-primary">Student Credit Card Scheme</h5>
        </div>
        <p class="text-body-md mb-4 text-on-surface-variant">Eligible for Bihar Government's Zero-Interest Loan for ITI education. We provide all necessary paperwork for your application.</p>
        <div class="flex flex-col gap-2">
          <a class="text-on-tertiary-container font-bold flex items-center gap-2 hover:underline" href="<?= site_url('bscc-info') ?>">
            Learn about BSCC
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </a>
          <a class="text-primary font-bold flex items-center gap-2 hover:underline text-sm" href="<?= site_url('fee-structure') ?>">
            View fee structure
            <span class="material-symbols-outlined text-sm">payments</span>
          </a>
        </div>
      </div>
    </aside>

    <section class="lg:col-span-8">
      <div class="bg-white border border-outline-variant p-8 md:p-12 shadow-sm min-h-[600px] flex flex-col">
        <div class="mb-10">
          <h1 class="font-headline-lg text-headline-lg mb-2">2026 Admission Portal</h1>
          <p class="text-on-surface-variant">Complete your application for the upcoming technical session in three simple steps.</p>
        </div>

        <?php if ($msg = flash('error')): ?>
        <div class="mb-6 p-4 bg-error-container text-on-error-container border border-error text-sm"><?= e($msg) ?></div>
        <?php endif; ?>

        <form method="post" action="<?= site_url('apply-admission') ?>" enctype="multipart/form-data" class="flex-grow" id="admissionForm" novalidate>
          <?= csrf_field() ?>

          <!-- Step 1: Personal Information -->
          <div class="step-form-section block space-y-8" id="step1-content">
            <h2 class="font-headline-md text-headline-md flex items-center gap-3">
              <span class="bg-surface-container-high w-8 h-8 rounded-full flex items-center justify-center text-sm font-label-sm">01</span>
              Personal Information
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="font-bold text-sm block">Full Name *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="name" value="<?= e(old('name')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Father's Name *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="father_name" value="<?= e(old('father_name')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Mother's Name *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="mother_name" value="<?= e(old('mother_name')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Date of Birth *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" type="date" name="dob" value="<?= e(old('dob')) ?>" required/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Gender *</label>
                <select class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="gender" required>
                  <option value="">Select</option>
                  <?php foreach (['Male', 'Female', 'Other'] as $g): ?>
                  <option value="<?= e($g) ?>" <?= old('gender') === $g ? 'selected' : '' ?>><?= e($g) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Category *</label>
                <select class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="category" required>
                  <option value="">Select</option>
                  <?php foreach (['General', 'OBC', 'SC', 'ST', 'EWS'] as $c): ?>
                  <option value="<?= e($c) ?>" <?= old('category') === $c ? 'selected' : '' ?>><?= e($c) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">UIDAI / Aadhaar *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="uidai_number" id="uidai_number" value="<?= e(old('uidai_number')) ?>" maxlength="14" placeholder="XXXX XXXX XXXX" required type="text"/>
                <small id="uidai-msg" class="text-xs"></small>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Mobile Number *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="mobile" value="<?= e(old('mobile')) ?>" pattern="\d{10}" required type="tel" placeholder="10-digit mobile"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Email Address</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" type="email" name="email" value="<?= e(old('email')) ?>" placeholder="example@email.com"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">PWD Claim</label>
                <select class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="pwd_claim">
                  <option value="No" <?= old('pwd_claim', 'No') === 'No' ? 'selected' : '' ?>>No</option>
                  <option value="Yes" <?= old('pwd_claim') === 'Yes' ? 'selected' : '' ?>>Yes</option>
                </select>
              </div>
              <div class="space-y-2 md:col-span-2">
                <label class="font-bold text-sm block">PWD Category</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="pwd_category" value="<?= e(old('pwd_category')) ?>" type="text"/>
              </div>
            </div>
          </div>

          <!-- Step 2: Address & Education -->
          <div class="step-form-section hidden space-y-8" id="step2-content">
            <h2 class="font-headline-md text-headline-md flex items-center gap-3">
              <span class="bg-surface-container-high w-8 h-8 rounded-full flex items-center justify-center text-sm font-label-sm">02</span>
              Address &amp; Academic Info (10th Pass)
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="font-bold text-sm block">Village/Town/City *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="village_town_city" value="<?= e(old('village_town_city')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Nearby</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="nearby" value="<?= e(old('nearby')) ?>" type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Police Station *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="police_station" value="<?= e(old('police_station')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Post Office *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="post_office" value="<?= e(old('post_office')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Block *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="block" value="<?= e(old('block')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">District *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="district" value="<?= e(old('district')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">State *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="state" value="<?= e(old('state', 'Bihar')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Pincode *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="pincode" value="<?= e(old('pincode')) ?>" pattern="\d{6}" required type="text"/>
              </div>
              <div class="md:col-span-2 border-t border-outline-variant pt-8 mt-2">
                <h3 class="font-bold mb-4">Class 10th Details</h3>
              </div>
              <div class="space-y-2 md:col-span-2">
                <label class="font-bold text-sm block">School *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="class_10th_school" value="<?= e(old('class_10th_school')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Marks Obtained *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="class_10th_marks_obtained" id="class_10th_marks_obtained" value="<?= e(old('class_10th_marks_obtained')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Total Marks *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="class_10th_total_marks" id="class_10th_total_marks" value="<?= e(old('class_10th_total_marks')) ?>" required type="text"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Percentage</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all bg-surface-container-low" name="class_10th_percentage" id="class_10th_percentage" value="<?= e(old('class_10th_percentage')) ?>" readonly type="text"/>
              </div>
            </div>
          </div>

          <!-- Step 3: Trade, Session & Documents -->
          <div class="step-form-section hidden space-y-8" id="step3-content">
            <h2 class="font-headline-md text-headline-md flex items-center gap-3">
              <span class="bg-surface-container-high w-8 h-8 rounded-full flex items-center justify-center text-sm font-label-sm">03</span>
              Trade Selection &amp; Documents
            </h2>
            <p class="text-on-surface-variant -mt-4">Choose your desired technical field. Note: Admission depends on merit and availability.</p>

            <?php
            $tradeList = $trades ?? [];
            if (!$tradeList) {
                $tradeList = [
                    ['name' => 'Electrician', 'slug' => 'electrician', 'duration' => '2 Years', 'description' => ''],
                    ['name' => 'Fitter', 'slug' => 'fitter', 'duration' => '2 Years', 'description' => ''],
                ];
            }
            ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="tradeCardGrid">
              <?php foreach ($tradeList as $t):
                $slug = $t['slug'] ?? '';
                $icon = $tradeIcons[$slug] ?? 'precision_manufacturing';
                $duration = $t['duration'] ?? '2 Years';
                $selected = old('trade') === $t['name'];
              ?>
              <label class="trade-card relative flex flex-col p-6 border-2 <?= $selected ? 'border-primary bg-surface-container-low' : 'border-outline-variant' ?> hover:border-primary cursor-pointer group transition-all">
                <input class="sr-only" name="trade" type="radio" value="<?= e($t['name']) ?>" <?= $selected ? 'checked' : '' ?>/>
                <span class="material-symbols-outlined text-3xl mb-2 text-on-surface-variant group-hover:text-primary trade-check <?= $selected ? 'text-primary' : '' ?>"><?= e($icon) ?></span>
                <span class="font-headline-md font-bold block mb-1"><?= e($t['name']) ?></span>
                <span class="text-xs font-label-sm uppercase text-outline"><?= e($duration) ?> Duration</span>
                <?php if (!empty($t['description'])): ?>
                <p class="text-sm mt-3 text-on-surface-variant"><?= e(mb_strimwidth($t['description'], 0, 120, '...')) ?></p>
                <?php endif; ?>
                <span class="trade-selected-badge mt-4 text-xs font-bold text-primary <?= $selected ? '' : 'hidden' ?>">✓ Selected</span>
              </label>
              <?php endforeach; ?>
            </div>
            <p id="tradeSelectedText" class="text-sm font-bold text-primary"><?= old('trade') ? 'Selected trade: ' . e(old('trade')) : 'Please select a trade above.' ?></p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4">
              <div class="space-y-2">
                <label class="font-bold text-sm block">Session *</label>
                <select class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="session" required>
                  <option value="">Select</option>
                  <?php foreach ($sessions as $s): ?>
                  <option value="<?= e($s['session_name']) ?>" <?= old('session') === $s['session_name'] ? 'selected' : '' ?>><?= e($s['session_name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Shift</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="shift" value="<?= e(old('shift')) ?>" type="text" placeholder="Optional"/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">BSCC (Student Credit Card)</label>
                <select class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="student_credit_card" id="student_credit_card">
                  <option value="No" <?= old('student_credit_card', 'No') === 'No' ? 'selected' : '' ?>>No</option>
                  <option value="Yes" <?= old('student_credit_card') === 'Yes' ? 'selected' : '' ?>>Yes</option>
                </select>
              </div>
            </div>

            <div id="bscc_details_box" class="border border-outline-variant bg-surface-container-low p-6 space-y-4 <?= old('student_credit_card') === 'Yes' ? '' : 'hidden' ?>">
              <h3 class="font-headline-md text-primary">BSCC Bank Account Details</h3>
              <p class="text-sm text-on-surface-variant">BSCC Yes select karne par bank details bharna zaroori hai.</p>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                  <label class="font-bold text-sm block">Bank Name *</label>
                  <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="student_credit_card_bank" id="student_credit_card_bank" value="<?= e(old('student_credit_card_bank')) ?>" type="text"/>
                </div>
                <div class="space-y-2">
                  <label class="font-bold text-sm block">Account Holder Name</label>
                  <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="student_credit_card_holder" value="<?= e(old('student_credit_card_holder')) ?>" type="text"/>
                </div>
                <div class="space-y-2">
                  <label class="font-bold text-sm block">Account Number *</label>
                  <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="student_credit_card_account" id="student_credit_card_account" value="<?= e(old('student_credit_card_account')) ?>" type="text"/>
                </div>
                <div class="space-y-2">
                  <label class="font-bold text-sm block">IFSC Code</label>
                  <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all" name="student_credit_card_ifsc" value="<?= e(old('student_credit_card_ifsc')) ?>" type="text"/>
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-4 border-t border-outline-variant">
              <div class="space-y-2">
                <label class="font-bold text-sm block">Passport Photo *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all text-sm" type="file" name="photo" accept="image/*" required/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Aadhaar Card *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all text-sm" type="file" name="aadhaar" accept="image/*,application/pdf" required/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">10th Marksheet *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all text-sm" type="file" name="marksheet" accept="image/*,application/pdf" required/>
              </div>
              <div class="space-y-2">
                <label class="font-bold text-sm block">Applicant Signature *</label>
                <input class="w-full border border-outline-variant p-3 form-input-focus rounded-none transition-all text-sm" type="file" name="signature" accept="image/*" required/>
                <p class="text-xs text-on-surface-variant">Sign on white paper, scan or photo — JPG/PNG only</p>
              </div>
            </div>

            <div class="pt-4">
              <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" name="declaration" value="1" class="mt-1" required <?= old('declaration') ? 'checked' : '' ?>/>
                <span class="text-sm text-on-surface-variant">I declare that all information is true and correct.</span>
              </label>
            </div>
          </div>

          <div class="mt-12 flex justify-between pt-8 border-t border-outline-variant">
            <button class="invisible bg-surface-container-highest text-on-surface font-bold px-8 py-3 hover:bg-surface-variant transition-colors flex items-center gap-2" id="prevBtn" type="button">
              <span class="material-symbols-outlined">arrow_back</span>
              Previous
            </button>
            <button class="bg-primary text-white font-bold px-10 py-3 hover:bg-black transition-colors flex items-center gap-2" id="nextBtn" type="button">
              Continue
              <span class="material-symbols-outlined">arrow_forward</span>
            </button>
            <button class="hidden bg-secondary-container text-on-secondary-container font-bold px-10 py-3 hover:opacity-90 transition-all flex items-center gap-2 shadow-lg" id="submitBtn" type="submit">
              Final Submission
              <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">send</span>
            </button>
          </div>
        </form>
      </div>

      <div class="mt-8 relative overflow-hidden h-40 border border-outline-variant bg-surface-container-low flex items-center px-12 group">
        <div class="absolute inset-0 opacity-5 pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 20px 20px;"></div>
        <div class="z-10">
          <p class="font-label-sm text-outline uppercase tracking-widest mb-1">Workshop Transparency</p>
          <h4 class="font-headline-md text-headline-md">Visit our Campus in Maner</h4>
          <p class="text-on-surface-variant">See the infrastructure before you join. Weekly campus tours every Saturday.</p>
        </div>
        <div class="ml-auto z-10">
          <a href="<?= site_url('contact') ?>" class="border-2 border-primary text-primary px-6 py-2 font-bold hover:bg-primary hover:text-white transition-all inline-block">Schedule Visit</a>
        </div>
      </div>
    </section>
  </div>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<script src="<?= asset('js/form-utils.js') ?>"></script>
<script src="<?= asset('js/admission.js') ?>"></script>
</body>
</html>
