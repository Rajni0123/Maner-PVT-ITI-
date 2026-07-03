<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$trades = $trades ?? [];

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$footerAbout = $footer['about_text'] ?? 'Empowering the youth of Bihar with technical expertise and industrial precision since 2014.';
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. NCVT Affiliated Institution. All Rights Reserved.';

$documents = [
    ['icon' => 'school', 'title' => '10th Marksheet/Certificate', 'desc' => 'Original documents along with 3 sets of photocopies.'],
    ['icon' => 'fingerprint', 'title' => 'Aadhaar Card', 'desc' => 'Must be linked with an active mobile number for OTP verification.'],
    ['icon' => 'add_a_photo', 'title' => '6 Passport Size Photos', 'desc' => 'Recent photographs with a clean white background.'],
    ['icon' => 'home_pin', 'title' => 'Residential Certificate', 'desc' => 'Valid Bihar Domicile certificate issued by Circle Officer.'],
    ['icon' => 'account_balance', 'title' => 'Bank Passbook', 'desc' => 'Required for BSCC/Scholarship fund transfers.'],
];

$tradeCareers = [
    'electrician' => [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuAje_Lm8LoR4Va8GS7KI_ji6UXwEea4gyFrNxXdYIOY8MbxxHkBbrUffa5xvb2ZQ7EMqyh0qrOeHErFvY_kLGbxX75c6XaHaJTlBBNlWkLjHVGzU_h87f7CrZuR_syagaOUgSCT88OFPBEHXmlKzXV62ESqv2ThYHnC1SovMLn07q8fUk0O-EEm3HvoEq8OHnv7xYr-w6ddHq-Zt8Gcj6eot69AxXnEdIal56FBOyXHzRiawWTi-0yxANLg2GezRUNzVjxXL6Ribzw',
        'benefits' => [
            ['icon' => 'bolt', 'text' => 'High Demand in Green Energy'],
            ['icon' => 'work_outline', 'text' => 'Self-Employment Potential'],
        ],
        'government' => ['Indian Railways (ALP/Technician)', 'PGCIL (Power Grid)', 'State Boards (BSPHCL)', 'ISRO Research Centers'],
        'private' => ['Tata Power', 'Samsung Electronics', 'Solar Energy Firms', 'Industrial Maintenance'],
    ],
    'fitter' => [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB01f_2gyrAyeniMiDmt7PLUJrDLZSdBJBKaiMc7Og-hl8Hlgh6czujR7v1BXL_r0TnKIEHMYherCNraXcvl8_UfPmui-DepQIASM1hUEx6xKvVZ8yZrw-rruO08UYug_O0Gc5XElgqUSyh_a1tZly5zQVPPh894rw_MUG7peDhjhqQNkKCUOVP_AcfcuWUZQX5VfOEyPOUX7VmKha6Wyf4Zk0lx3xXqzey8yy_rv2RHeS7yUrwzrx2bPcA4VE_Lxn3m2U5upBXl8I',
        'benefits' => [
            ['icon' => 'precision_manufacturing', 'text' => 'Precision Engineering Skills'],
            ['icon' => 'public', 'text' => 'Global Manufacturing Demand'],
        ],
        'government' => ['Indian Railways', 'DRDO Laboratories', 'SAIL & BHEL Plants', 'Ordnance Factories'],
        'private' => ['Maruti Suzuki / Honda', 'L&T Construction', 'Aerospace Manufacturing', 'Heavy Machinery Units'],
    ],
];

$pageTitle = $title ?? 'Requirements & Career Pathways | Maner Private ITI';
$pageDescription = 'Explore admission requirements and career opportunities for Electrician and Fitter trades at Maner Private ITI.';
$extraCss = ['career-pathways.css'];
$navActive = 'admission';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-background font-body-md">

<?php require base_path('views/partials/design-nav-courses.php'); ?>

<main>
  <section class="hero-gradient text-white py-20 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20 technical-mesh"></div>
    <div class="max-w-container-max mx-auto px-gutter relative z-10">
      <h1 class="font-display text-display mb-4">Launch Your <span class="text-secondary-container">Technical Career</span></h1>
        <p class="font-body-lg text-body-lg max-w-2xl opacity-90">Step-by-step guidance on admission requirements and high-growth career pathways in modern industrial sectors.</p>
        <div class="mt-8 flex flex-wrap gap-4">
          <a href="<?= site_url('fee-structure') ?>" class="bg-secondary-container text-on-secondary-container px-6 py-3 font-bold hover:opacity-90 transition-all inline-flex items-center gap-2">
            <span class="material-symbols-outlined text-sm">payments</span>
            View Fee Structure
          </a>
          <a href="<?= site_url('apply-admission') ?>" class="border border-white/30 bg-white/10 hover:bg-white/20 px-6 py-3 font-bold transition-all inline-flex items-center gap-2">
            Apply Online
            <span class="material-symbols-outlined text-sm">arrow_forward</span>
          </a>
        </div>
    </div>
  </section>

  <section class="py-section-gap bg-surface-bright">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="flex flex-col md:flex-row gap-12 items-start">
        <div class="md:w-1/3">
          <div class="sticky top-24">
            <h2 class="font-headline-lg text-headline-lg mb-6 flex items-center gap-3">
              <span class="material-symbols-outlined text-secondary text-4xl">inventory_2</span>
              Admission Checklist
            </h2>
            <p class="text-on-surface-variant mb-8">Ensure you have the following documents ready for a smooth admission process. Original documents will be returned after verification.</p>
            <div class="p-6 bg-secondary-container/10 border-l-4 border-secondary-container">
              <p class="font-bold text-on-secondary-container">Note for BSCC Applicants:</p>
              <p class="text-on-secondary-container/80 text-sm mt-2">The Bank Passbook and Residential Certificate are mandatory for Bihar Student Credit Card (BSCC) and other government scholarship schemes.</p>
              <a href="<?= site_url('fee-structure') ?>" class="inline-flex items-center gap-1 text-on-secondary-container font-bold text-sm mt-4 hover:underline">
                See full fee breakdown <span class="material-symbols-outlined text-sm">arrow_forward</span>
              </a>
            </div>
          </div>
        </div>
        <div class="md:w-2/3 grid grid-cols-1 gap-4">
          <?php foreach ($documents as $doc): ?>
          <div class="doc-check-card bg-white p-6 border border-outline-variant hover:border-primary transition-all flex items-center gap-6 group cursor-pointer">
            <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center group-hover:bg-primary group-hover:text-white transition-colors">
              <span class="material-symbols-outlined"><?= e($doc['icon']) ?></span>
            </div>
            <div>
              <h3 class="font-bold text-lg"><?= e($doc['title']) ?></h3>
              <p class="text-on-surface-variant"><?= e($doc['desc']) ?></p>
            </div>
            <span class="ml-auto material-symbols-outlined text-outline-variant doc-check-icon">check_circle</span>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>

  <section class="py-section-gap bg-white overflow-hidden">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="text-center mb-16">
        <h2 class="font-display text-headline-lg mb-4">Future-Proof Your Career</h2>
        <p class="text-on-surface-variant max-w-2xl mx-auto">Discover the immense potential and diverse opportunities waiting for you after completing your ITI certification.</p>
      </div>
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <?php foreach ($trades as $t):
          $slug = $t['slug'] ?? '';
          $career = $tradeCareers[$slug] ?? null;
          if (!$career) continue;
        ?>
        <div class="group">
          <div class="relative h-64 overflow-hidden mb-8">
            <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500" style="background-image: url('<?= e($career['image']) ?>')"></div>
            <div class="absolute inset-0 bg-primary/40 flex items-end p-8">
              <h3 class="text-white font-headline-md"><?= e($t['name']) ?> Trade</h3>
            </div>
          </div>
          <div class="space-y-8">
            <div>
              <span class="font-label-sm text-label-sm text-secondary bg-secondary-container/10 px-3 py-1 uppercase tracking-wider mb-3 inline-block">Key Benefits</span>
              <div class="grid grid-cols-2 gap-4">
                <?php foreach ($career['benefits'] as $benefit): ?>
                <div class="flex items-start gap-2">
                  <span class="material-symbols-outlined text-secondary text-xl"><?= e($benefit['icon']) ?></span>
                  <span class="font-medium"><?= e($benefit['text']) ?></span>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 bg-surface-container-low border-l-4 border-primary">
              <div>
                <h4 class="font-bold text-primary mb-4 flex items-center gap-2">
                  <span class="material-symbols-outlined text-sm">account_balance</span>
                  Government
                </h4>
                <ul class="space-y-2 text-sm text-on-surface-variant">
                  <?php foreach ($career['government'] as $item): ?>
                  <li>• <?= e($item) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
              <div>
                <h4 class="font-bold text-primary mb-4 flex items-center gap-2">
                  <span class="material-symbols-outlined text-sm">factory</span>
                  Private Sector
                </h4>
                <ul class="space-y-2 text-sm text-on-surface-variant">
                  <?php foreach ($career['private'] as $item): ?>
                  <li>• <?= e($item) ?></li>
                  <?php endforeach; ?>
                </ul>
              </div>
            </div>
            <a href="<?= site_url('trades/' . $slug) ?>" class="inline-flex items-center gap-2 text-primary font-bold hover:underline">
              View <?= e($t['name']) ?> Syllabus <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="py-20 bg-primary text-white relative">
    <div class="max-w-container-max mx-auto px-gutter text-center relative z-10">
      <h2 class="font-headline-lg text-headline-lg mb-6">Ready to Start Your Journey?</h2>
      <p class="mb-10 max-w-xl mx-auto opacity-80">Our counselors are available to help you choose the right trade and guide you through the admission process.</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="<?= site_url('fee-structure') ?>" class="bg-secondary text-on-secondary px-8 py-4 font-bold text-lg hover:scale-105 transition-transform flex items-center justify-center gap-2">
          Download Prospectus
          <span class="material-symbols-outlined">download</span>
        </a>
        <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-8 py-4 font-bold text-lg hover:opacity-90 transition-all flex items-center justify-center gap-2">
          Apply Online
          <span class="material-symbols-outlined">arrow_forward</span>
        </a>
        <a href="<?= site_url('contact') ?>" class="border border-white/30 bg-white/5 hover:bg-white/10 px-8 py-4 font-bold text-lg transition-colors">
          Visit Campus
        </a>
      </div>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<script src="<?= asset('js/career-pathways.js') ?>"></script>
</body>
</html>
