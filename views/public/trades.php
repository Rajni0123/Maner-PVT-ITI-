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
$footerAddress = $footer['address'] ?? "Near Police Station, Maner,\nPatna, Bihar - 801108";
$footerAbout = $footer['about_text'] ?? 'Affiliated to NCVT, Govt. of India. Providing excellence in technical education for over a decade.';
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. NCVT Affiliated Institution. All Rights Reserved.';

$tradeDesign = [
    'electrician' => [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuB_y_9EPerTEjze8Gb4HPXCcEJpmuaEu5lsu_n-wDOA81PmdhuCGzV93kUWsvi4X1YgzgrwJKFJPHy-_O2SmSxPmjjkIWJe5RYvxm6iJWLs01YAI_p0uSULHaUHuF4Zd1pQQ6tjTTFazdJgbRb4jtyHGsj0gtDshF0vJVXll3KWGkMvd9DTG2uOAk8NY2ZiJa73N4ceto8X4ikEnD0CS8Wth6hd-LQz5K1i3GNLIWuKjeJVlpu20cCmm6uI26COGDcgOjfxxgOx5Cs',
        'badge' => 'MOST POPULAR',
        'skills' => [
            ['icon' => 'bolt', 'text' => 'Industrial Power Systems & Distribution'],
            ['icon' => 'build', 'text' => 'Preventive Maintenance & Troubleshooting'],
            ['icon' => 'solar_power', 'text' => 'Solar Energy Installation & Maintenance'],
        ],
        'careers' => ['Indian Railways', 'BHEL', 'SAIL', 'Solar Sector'],
    ],
    'fitter' => [
        'image' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuDZbaLofkGoWHma_7dAkqk4YQRICM8Y-_2XFMmRqeXkG7Eq42kDvub8_gfwWCC9pMSofptl6tlCBpsNJGsxbr15t6mKJIQqtpiklzF_Rg0u3Y6Rer7yD7HtjXafNROAefQZsle0gaqBQMatfwc6xb7FPvTXqQBErHCKyHI0X3KZrkflJfVnmDjSslK49_v9vDLSsAgKUF0xd00-Q-WdeAJzYnsRvacuXJCBv-GyjE4yhk6WaF5Yqd_qetf7aYiM2tRPKWa0H_HDlXw',
        'badge' => null,
        'skills' => [
            ['icon' => 'precision_manufacturing', 'text' => 'Advanced Mechanical Assembly'],
            ['icon' => 'architecture', 'text' => 'Industrial Structural Frameworks'],
            ['icon' => 'construction', 'text' => 'Machine Installation & Calibration'],
        ],
        'careers' => ['Loco Sheds', 'BHEL', 'Heavy Engineering', 'Aerospace'],
    ],
];

$pageTitle = $title ?? 'Courses | Maner Private ITI - Empowering Technical Careers';
$extraCss = ['courses.css'];
$navActive = 'trades';
?>
<!DOCTYPE html>
<html class="light" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-surface text-on-surface font-body-md">

<?php require base_path('views/partials/design-nav-courses.php'); ?>

<main>
  <!-- Hero Section -->
  <section class="relative bg-primary-container text-on-primary py-24 overflow-hidden">
    <div class="absolute inset-0 industrial-pattern"></div>
    <div class="max-w-container-max mx-auto px-gutter relative z-10">
      <div class="max-w-2xl">
        <span class="font-label-sm text-label-sm text-secondary-container mb-4 block uppercase tracking-widest">Industry-Ready Training</span>
        <h1 class="font-display text-display mb-6">Expertise That Powers Nations</h1>
        <p class="font-body-lg text-body-lg text-on-primary-container/90 mb-8">
          Our NCVT-affiliated trades are designed for precision, durability, and global standards. Step into a world-class workshop environment and secure your future in India's leading industrial sectors.
        </p>
      </div>
    </div>
    <div class="hidden lg:block absolute right-0 top-1/2 -translate-y-1/2 w-1/3 h-[80%] opacity-20">
      <span class="material-symbols-outlined scale-[10] text-on-primary-container">settings_suggest</span>
    </div>
  </section>

  <!-- Course Trade Grid -->
  <section class="py-section-gap bg-surface">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
          <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Technical Trades</h2>
          <p class="text-on-surface-variant">Structured 2-year programs with hands-on workshop immersion.</p>
        </div>
        <div class="flex gap-4">
          <span class="flex items-center gap-2 text-label-sm font-label-sm border border-outline px-3 py-1 bg-surface-container-low">
            <span class="material-symbols-outlined text-[16px]">verified</span> NCVT AFFILIATED
          </span>
          <span class="flex items-center gap-2 text-label-sm font-label-sm border border-outline px-3 py-1 bg-surface-container-low">
            <span class="material-symbols-outlined text-[16px]">schedule</span> 2-YEAR DURATION
          </span>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <?php foreach ($trades as $t):
          $slug = $t['slug'] ?? '';
          $design = $tradeDesign[$slug] ?? [
            'image' => '',
            'badge' => null,
            'skills' => [],
            'careers' => [],
          ];
          if (!empty($t['image'])) {
              $design['image'] = upload_url($t['image']);
          }
          $duration = $t['duration'] ?? '2 Years';
        ?>
        <div class="group course-card-hover bg-white border border-outline-variant flex flex-col h-full">
          <div class="aspect-video relative overflow-hidden">
            <?php if (!empty($design['image'])): ?>
            <div class="w-full h-full bg-cover bg-center group-hover:scale-105 transition-transform duration-500" style="background-image: url('<?= e($design['image']) ?>')"></div>
            <?php endif; ?>
            <?php if (!empty($design['badge'])): ?>
            <div class="absolute top-4 right-4 bg-secondary-container text-on-secondary-container px-3 py-1 font-bold text-label-sm"><?= e($design['badge']) ?></div>
            <?php endif; ?>
          </div>
          <div class="p-8 flex-grow">
            <h3 class="font-headline-md text-headline-md text-primary mb-4"><?= e($t['name']) ?></h3>
            <div class="flex items-center gap-4 mb-6">
              <div class="flex items-center gap-2 text-label-sm font-label-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">history</span> <?= e($duration) ?>
              </div>
              <div class="w-px h-4 bg-outline-variant"></div>
              <div class="flex items-center gap-2 text-label-sm font-label-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-[18px]">workspace_premium</span> NCVT
              </div>
            </div>
            <?php if (!empty($design['skills'])): ?>
            <div class="mb-6">
              <h4 class="font-label-sm text-label-sm text-primary mb-3 uppercase tracking-wider">Key Skills</h4>
              <ul class="space-y-2">
                <?php foreach ($design['skills'] as $skill): ?>
                <li class="flex items-start gap-2 text-body-md text-on-surface-variant">
                  <span class="material-symbols-outlined text-secondary text-[20px]"><?= e($skill['icon']) ?></span>
                  <?= e($skill['text']) ?>
                </li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php endif; ?>
            <?php if (!empty($design['careers'])): ?>
            <div class="mb-8">
              <h4 class="font-label-sm text-label-sm text-primary mb-3 uppercase tracking-wider">Career Paths</h4>
              <div class="flex flex-wrap gap-2">
                <?php foreach ($design['careers'] as $career): ?>
                <span class="bg-surface-container text-on-surface px-3 py-1 text-label-sm border border-outline-variant"><?= e($career) ?></span>
                <?php endforeach; ?>
              </div>
            </div>
            <?php endif; ?>
            <a href="<?= site_url('trades/' . $slug) ?>" class="w-full border-2 border-primary text-primary py-3 font-bold hover:bg-primary hover:text-white transition-colors flex justify-center items-center gap-2">
              Download Syllabus <span class="material-symbols-outlined text-[18px]">download</span>
            </a>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- The NTC Advantage -->
  <section class="py-section-gap bg-tertiary-container text-on-tertiary">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-12 relative overflow-hidden">
        <div class="absolute -right-20 -bottom-20 opacity-5">
          <span class="material-symbols-outlined text-[400px]">public</span>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center relative z-10">
          <div>
            <span class="bg-secondary text-white px-4 py-1 font-label-sm text-label-sm mb-6 inline-block">GLOBAL CREDENTIAL</span>
            <h2 class="font-display text-display mb-6 leading-tight">The National Trade Certificate Advantage</h2>
            <p class="font-body-lg text-body-lg text-tertiary-fixed-dim mb-8">
              Your National Trade Certificate (NTC) is not just a diploma; it's a <strong class="text-secondary-fixed">digital passport</strong>. Recognized by the Government of India and accepted globally, it bridges the gap between rural talent and international industrial demand.
            </p>
            <div class="space-y-6">
              <div class="flex gap-4">
                <div class="bg-on-tertiary-fixed-variant p-3 h-fit">
                  <span class="material-symbols-outlined text-white">flight_takeoff</span>
                </div>
                <div>
                  <h4 class="font-bold text-headline-md mb-1">Global Employment</h4>
                  <p class="text-on-surface-variant/80">Valid for jobs in the Middle East, Europe, and Southeast Asia's manufacturing hubs.</p>
                </div>
              </div>
              <div class="flex gap-4">
                <div class="bg-on-tertiary-fixed-variant p-3 h-fit">
                  <span class="material-symbols-outlined text-white">account_balance</span>
                </div>
                <div>
                  <h4 class="font-bold text-headline-md mb-1">Government Validity</h4>
                  <p class="text-on-surface-variant/80">Mandatory requirement for Technicians and Grade-III posts in all Central and State PSUs.</p>
                </div>
              </div>
            </div>
          </div>
          <div class="relative">
            <div class="bg-white text-primary p-8 border-l-8 border-secondary-container shadow-2xl">
              <div class="flex justify-between items-start mb-8">
                <div class="w-16 h-16 bg-surface-container flex items-center justify-center">
                  <span class="material-symbols-outlined text-[40px] text-primary">verified_user</span>
                </div>
                <div class="text-right">
                  <div class="font-label-sm text-label-sm text-on-surface-variant uppercase">Certificate Type</div>
                  <div class="font-bold">NCVT - NTC</div>
                </div>
              </div>
              <div class="space-y-4 mb-8">
                <div class="h-4 bg-surface-container-high w-3/4"></div>
                <div class="h-4 bg-surface-container-high w-1/2"></div>
                <div class="h-4 bg-surface-container-high w-5/6"></div>
              </div>
              <div class="flex justify-between items-center pt-6 border-t border-outline-variant">
                <div class="flex items-center gap-2">
                  <span class="material-symbols-outlined text-secondary">qr_code_2</span>
                  <span class="text-label-sm">Digitally Verified</span>
                </div>
                <span class="font-display text-primary font-bold">MINISTRY OF SKILL DEV.</span>
              </div>
            </div>
            <div class="absolute -bottom-6 -left-6 bg-secondary text-primary p-6 shadow-xl hidden md:block">
              <div class="text-display leading-none mb-1">100%</div>
              <div class="text-label-sm font-bold uppercase">Industry Authenticity</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action -->
  <section class="py-section-gap bg-surface">
    <div class="max-w-container-max mx-auto px-gutter text-center">
      <h2 class="font-headline-lg text-headline-lg mb-4">Ready to Build Your Industrial Legacy?</h2>
      <p class="text-body-lg mb-10 text-on-surface-variant max-w-2xl mx-auto">Admission for the 2024-2026 session is now open. Seats are limited for both Electrician and Fitter trades.</p>
      <div class="flex flex-col sm:flex-row justify-center gap-4">
        <a href="<?= site_url('contact') ?>" class="bg-primary text-white px-10 py-4 font-bold text-body-lg hover:opacity-90 transition-all">Enquire Now</a>
        <a href="<?= site_url('contact') ?>" class="border-2 border-primary text-primary px-10 py-4 font-bold text-body-lg hover:bg-primary/5 transition-all">Visit Campus</a>
      </div>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<script src="<?= asset('js/courses.js') ?>"></script>
</body>
</html>
