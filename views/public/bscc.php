<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$trades = $trades ?? [];

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$footerPhone = $footer['phone'] ?? $header['phone'] ?? '+91-9155401839';
$footerEmail = $footer['email'] ?? $header['email'] ?? 'manerpvtiti@gmail.com';
$footerAddress = $footer['address'] ?? "NH-30, Maner, Patna\nBihar, 801108";
$footerAbout = $footer['about_text'] ?? "Empowering Bihar's youth through excellence in technical education and financial accessibility. Affiliated to NCVT, Govt. of India.";
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. NCVT Affiliated Institution. All Rights Reserved.';

$heroImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuCCKfGh1259a3gyOnMwjZYyyEuxs777aJWhfG7-eIo7u3Mu6H35uQ9IflRhNMcNsSrMk9xrJ35A_50tn8N8f-mFUsgHJwv3YEf1Q3WGpLVa0wt_9v1rIYWt4gJUrT9WlOEhX7IiDfrIJpTBtLk7R5dKltUQ4TYi4omBOOqyFoOQAJOkwJJ-6suacmNnIxv-5n4hYiMzoN0Fk7GvzIZpyWcv96g4M4jWuSgrS7oqjCN2xxNGLo_2joHkH4RLfVFfe3WNX384a-bDgpY';
$supportImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuA5io5H6g5Lq72p_494UuQ5uDkQs1MksLML2vFCm-8mSkbhyBaMtdmI8dYi_PkOJWFcPebLm84q1y5945NyX1wzaThtOnjN5moktCMm9YaUZTHaDKoZBX_fsYUnfx0_AnkqxyDwo0zARODz9ZiIO59OIh6b85UHJ1_IeBiUkc3yPmgQubcieyTDG6BuhtpqeUgfe1ZAsSMlo5YrB9eIbL5yCRDGP_qXdEWKm5RllDbZHN2kLRCldCjehPue0GohY3fTeLzmwAyxdiY';
$docsImage = 'https://lh3.googleusercontent.com/aida-public/AB6AXuB1C6Qdlnsam2mjrKR6n26UU0uhTgZUatKTPMHFwD4kRBYoMMgiOuDDvmR9TDxIyyJHLH6RpLzZdMkB4zic6ZxkSy0jN-06z462ubISdMMRP77oTfMew1Ig4w0cCapUYQqjUItOzgijYNtkSn3Y-H9gS-VgtWzc2p_YFHIVH0Tt2uzCoCbKWN4jWOA81fSz-8nJMK0hwFPaAa0YBxknMIUszkwJTNLGIslGhfhqrsouh-manGNiXBGbTxZNsUoF6UBdM36cQZbpsag';

$pageTitle = $title ?? 'BSCC - Maner Private ITI | Study with Zero Upfront Cost';
$extraCss = ['bscc.css'];
$navActive = 'student-zone';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-background font-body-md overflow-x-hidden">

<?php require base_path('views/partials/design-nav-bscc.php'); ?>

<main>
  <!-- Hero Section -->
  <section class="relative bg-primary-container text-white py-24 overflow-hidden">
    <div class="absolute inset-0 opacity-20 pointer-events-none"></div>
    <div class="relative max-w-container-max mx-auto px-gutter grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
      <div>
        <span class="inline-block py-1 px-3 bg-secondary-container text-on-secondary-container font-label-sm text-label-sm mb-6">GOVERNMENT OF BIHAR SCHEME</span>
        <h1 class="font-display text-display mb-6">Study Now, Pay After You Land a Job</h1>
        <p class="font-body-lg text-body-lg text-on-primary-container mb-8 max-w-xl">
          Unlock up to <span class="text-secondary-fixed font-bold">₹4 Lakhs</span> through the Bihar Student Credit Card (BSCC) scheme. Maner ITI provides dedicated documentation assistance for Bihar residents to ensure zero upfront costs.
        </p>
        <div class="flex flex-wrap gap-4">
          <a class="bg-secondary text-on-secondary px-8 py-4 font-bold flex items-center gap-2 hover:opacity-90 transition-all" href="#process">
            View Step-by-Step Guide
            <span class="material-symbols-outlined">arrow_downward</span>
          </a>
          <a class="border border-outline-variant px-8 py-4 font-bold hover:bg-surface/10 transition-all" href="<?= site_url('fee-structure') ?>">
            Download Brochure
          </a>
        </div>
      </div>
      <div class="relative group">
        <div class="aspect-video bg-surface overflow-hidden border border-outline-variant shadow-xl">
          <img class="w-full h-full object-cover grayscale-0 group-hover:scale-105 transition-transform duration-500" alt="Students working in a modern vocational training workshop" src="<?= e($heroImage) ?>"/>
        </div>
        <div class="absolute -bottom-6 -left-6 bg-white p-6 border border-outline-variant shadow-lg hidden md:block">
          <p class="text-primary font-display text-headline-md font-extrabold">₹4,00,000</p>
          <p class="text-on-surface-variant font-label-sm text-label-sm">MAXIMUM CREDIT LIMIT</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Core Benefits Bento Grid -->
  <section class="py-section-gap max-w-container-max mx-auto px-gutter">
    <div class="text-center mb-16">
      <h2 class="font-headline-lg text-headline-lg text-primary mb-4">Why Choose BSCC at Maner ITI?</h2>
      <div class="w-20 h-1 bg-secondary mx-auto"></div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bento-card bg-surface p-8 border border-outline-variant flex flex-col justify-between">
        <div>
          <span class="material-symbols-outlined text-secondary text-4xl mb-6">payments</span>
          <h3 class="font-headline-md text-headline-md mb-4">Zero Initial Fees</h3>
          <p class="text-on-surface-variant">Enroll in our NCVT courses without worrying about admission or semester fees. The credit card covers your entire education costs.</p>
        </div>
      </div>
      <div class="bento-card bg-primary-container text-white p-8 border border-outline-variant md:col-span-2 flex flex-row items-center gap-8">
        <div class="flex-1">
          <span class="material-symbols-outlined text-secondary-fixed text-4xl mb-6">description</span>
          <h3 class="font-headline-md text-headline-md mb-4 text-white">Dedicated Support Cell</h3>
          <p class="text-on-primary-container">Our campus features a dedicated BSCC Assistance Desk. We handle the complex documentation and verification process at the DRCC office for you.</p>
        </div>
        <div class="hidden lg:block w-48 h-48 rounded-full overflow-hidden border-4 border-secondary-fixed/20 shrink-0">
          <img class="w-full h-full object-cover" alt="BSCC assistance counselor" src="<?= e($supportImage) ?>"/>
        </div>
      </div>
      <div class="bento-card bg-surface-container-low p-8 border border-outline-variant md:col-span-2 flex flex-col justify-between">
        <div class="grid md:grid-cols-2 gap-8">
          <div>
            <span class="material-symbols-outlined text-secondary text-4xl mb-6">verified_user</span>
            <h3 class="font-headline-md text-headline-md mb-4">Official Accreditation</h3>
            <p class="text-on-surface-variant">Maner ITI is a fully NCVT-affiliated institution, making all our courses eligible for the state-funded credit scheme.</p>
          </div>
          <div class="bg-white p-4 border border-outline-variant">
            <ul class="space-y-3 font-label-sm">
              <?php foreach ($trades as $t): ?>
              <li class="flex items-center gap-2">
                <span class="material-symbols-outlined text-success text-sm">check_circle</span>
                <?= e(strtoupper($t['name'])) ?> TRADE
              </li>
              <?php endforeach; ?>
              <?php if (empty($trades)): ?>
              <li class="flex items-center gap-2"><span class="material-symbols-outlined text-success text-sm">check_circle</span> ELECTRICIAN TRADE</li>
              <li class="flex items-center gap-2"><span class="material-symbols-outlined text-success text-sm">check_circle</span> FITTER TRADE</li>
              <?php endif; ?>
            </ul>
          </div>
        </div>
      </div>
      <div class="bento-card bg-secondary-container p-8 border border-outline-variant flex flex-col justify-between">
        <div>
          <span class="material-symbols-outlined text-on-secondary-container text-4xl mb-6">handshake</span>
          <h3 class="font-headline-md text-headline-md mb-4 text-on-secondary-container">Bihar Resident Eligibility</h3>
          <p class="text-on-secondary-container/80">Specifically designed for residents of Bihar. Students from rural and urban areas can apply regardless of background.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Application Roadmap -->
  <section class="py-section-gap bg-surface-container-highest" id="process">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="flex flex-col md:flex-row justify-between items-end mb-16 gap-4">
        <div class="max-w-2xl">
          <h2 class="font-headline-lg text-headline-lg text-primary mb-4">Application Roadmap</h2>
          <p class="text-on-surface-variant text-body-lg">We've simplified the journey from a prospective student to a funded professional. Here is how it works at Maner ITI.</p>
        </div>
        <div class="bg-primary text-white p-4 font-label-sm">
          TOTAL ESTIMATED TIME: 15-30 DAYS
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-12 relative">
        <div class="relative z-10">
          <div class="w-16 h-16 bg-primary text-white flex items-center justify-center font-display text-2xl mb-6 outline outline-8 outline-white">01</div>
          <h4 class="font-bold mb-3">Admission at Maner ITI</h4>
          <p class="text-on-surface-variant text-sm">Submit your documents and secure your seat in your preferred trade.</p>
          <div class="hidden md:block absolute top-8 left-16 w-full h-px border-t-2 border-dashed border-outline-variant -z-10"></div>
        </div>
        <div class="relative z-10">
          <div class="w-16 h-16 bg-primary text-white flex items-center justify-center font-display text-2xl mb-6 outline outline-8 outline-white">02</div>
          <h4 class="font-bold mb-3">Bonafide Certificate</h4>
          <p class="text-on-surface-variant text-sm">We provide the official bonafide and fee structure required for the DRCC office.</p>
          <div class="hidden md:block absolute top-8 left-16 w-full h-px border-t-2 border-dashed border-outline-variant -z-10"></div>
        </div>
        <div class="relative z-10">
          <div class="w-16 h-16 bg-primary text-white flex items-center justify-center font-display text-2xl mb-6 outline outline-8 outline-white">03</div>
          <h4 class="font-bold mb-3">DRCC Verification</h4>
          <p class="text-on-surface-variant text-sm">Apply online and visit your district's DRCC center for biometric and document verification.</p>
          <div class="hidden md:block absolute top-8 left-16 w-full h-px border-t-2 border-dashed border-outline-variant -z-10"></div>
        </div>
        <div class="relative z-10">
          <div class="w-16 h-16 bg-secondary text-on-secondary flex items-center justify-center font-display text-2xl mb-6 outline outline-8 outline-white">04</div>
          <h4 class="font-bold mb-3">Approval &amp; Funding</h4>
          <p class="text-on-surface-variant text-sm">Once approved, the funds are directly disbursed for your education and expenses.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Document Checklist -->
  <section class="py-section-gap max-w-container-max mx-auto px-gutter grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-5 order-2 lg:order-1">
      <div class="relative aspect-square">
        <div class="absolute inset-0 border-2 border-secondary -translate-x-4 translate-y-4"></div>
        <img class="w-full h-full object-cover relative z-10" alt="Official documents for BSCC application" src="<?= e($docsImage) ?>"/>
      </div>
    </div>
    <div class="lg:col-span-7 order-1 lg:order-2">
      <h2 class="font-headline-lg text-headline-lg mb-8">Mandatory Documentation</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary">check_box</span>
          <div>
            <p class="font-bold">10th/12th Marksheet</p>
            <p class="text-xs text-on-surface-variant">Original and photocopies required</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary">check_box</span>
          <div>
            <p class="font-bold">Residential Certificate</p>
            <p class="text-xs text-on-surface-variant">Proof of Bihar domicile</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary">check_box</span>
          <div>
            <p class="font-bold">Aadhaar Card</p>
            <p class="text-xs text-on-surface-variant">Linked with active mobile number</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary">check_box</span>
          <div>
            <p class="font-bold">Bank Passbook</p>
            <p class="text-xs text-on-surface-variant">Nationalized bank account preferred</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary">check_box</span>
          <div>
            <p class="font-bold">Pan Card</p>
            <p class="text-xs text-on-surface-variant">For student and co-applicant</p>
          </div>
        </div>
        <div class="flex items-start gap-3">
          <span class="material-symbols-outlined text-secondary">check_box</span>
          <div>
            <p class="font-bold">Passport Photos</p>
            <p class="text-xs text-on-surface-variant">6 copies with white background</p>
          </div>
        </div>
      </div>
      <div class="mt-12 p-6 bg-surface-container border-l-4 border-primary">
        <p class="italic text-on-surface-variant">"Don't have these ready? Visit our campus and our counselors will help you obtain these documents through the right government channels."</p>
      </div>
    </div>
  </section>

  <!-- Eligibility CTA -->
  <section class="bg-primary py-20 text-center">
    <div class="max-w-3xl mx-auto px-gutter">
      <h2 class="font-headline-lg text-headline-lg text-white mb-6">Are You Eligible?</h2>
      <div class="flex flex-col md:flex-row justify-center gap-8 mb-12">
        <div class="bg-white/10 p-4 border border-white/20">
          <p class="text-secondary-fixed-dim font-label-sm mb-2">AGE LIMIT</p>
          <p class="text-white text-xl font-bold">Up to 25 Years</p>
        </div>
        <div class="bg-white/10 p-4 border border-white/20">
          <p class="text-secondary-fixed-dim font-label-sm mb-2">RESIDENCY</p>
          <p class="text-white text-xl font-bold">Bihar Permanent Resident</p>
        </div>
        <div class="bg-white/10 p-4 border border-white/20">
          <p class="text-secondary-fixed-dim font-label-sm mb-2">EDUCATION</p>
          <p class="text-white text-xl font-bold">Passed 10th or 12th</p>
        </div>
      </div>
      <a href="<?= site_url('apply-admission') ?>" class="inline-block bg-secondary text-on-secondary px-10 py-5 font-display text-lg font-extrabold uppercase tracking-widest hover:scale-105 transition-transform">
        Start My Application Today
      </a>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<script src="<?= asset('js/bscc.js') ?>"></script>
</body>
</html>
