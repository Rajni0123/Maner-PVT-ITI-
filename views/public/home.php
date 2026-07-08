<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$footerLinks = $footerLinks ?? \App\Models\SiteData::footerLinks();
$hero = $hero ?? [];
$trades = $trades ?? [];
$flashNews = $flashNews ?? [];

$defaultHeroBg = 'https://lh3.googleusercontent.com/aida-public/AB6AXuAVp-2HnFZhjpqs0CqblZSd3BhtzubTJ_qHOLS29sQvYv9tp2VAbF8VEimFVcD5v1GKZhhwYL4PQlbSy1VdtiduXlcZjRttJYXr80fkVicYot6BXfwtN937jl_GcV9oLo2K_jqc7c6Q-OEuv3n--u5gx-sHOGvWtdlkh9patBbrqa5fjQhL4TyxWjaAvMu9oktG7goCLFDa4EZ3qeATSr9vbLimTyGkl_ZoO6aShIcKcQ23ju2m5zpkmSjHaMqguxxJmbOD71CpVSo';
$heroBg = !empty($hero['background_image']) ? upload_url($hero['background_image']) : $defaultHeroBg;

$tradeImages = [
    'electrician' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBL8p05_ngajrWAwiKyCQCW1-JJTDojxjFflc0pYoa2pZc7ZtCPXIQDRoGddlCFOtPb6yjm0j39Ml0ZFVZ3m9yUt0zQ__9u2GzRDwCfGcEFR08l3NU1glO_HpVS86eZmR1bdnPB94fK6aEiDkmfWd_3gX0d4k4jZbG6LeHfM2cPVarc-6Li8PpmoMMuZQkjqhHU3thlJxGao9vdNNxgu3CSYMVzbaZlfdc-CrNUlQxUbOQH3YGchCLhSocSQ-_CMVIl36xGIYjH3jc',
    'fitter' => 'https://lh3.googleusercontent.com/aida-public/AB6AXuBLn8Ub-lO6m7PQmx2aSp1Icy2MdVqaj6NYHoo62snhJJg5J5_5oiLpKMOu8UJrUYcSmqjZ0VpLPXZLKAkWTI_DVUsFf0ZOtn-6azujayGqmzqRoFdkg2enaQq0CQ7TcXBp4PBHXwnl381-fo_EXHBWPdijDAQ-cudINhvI_Gowei19k7V_XUrVmDsbPUHvkymujzrVOUv5r_WecaqWrcC_gPj4_I4v4PqSbbJThXDlO6iMVPkLQxOCxHh1sHg1F1UBluos87vr2GM',
];

$tradeTags = [
    'electrician' => ['NCVT APPROVED', 'MODERN LABS'],
    'fitter' => ['NCVT APPROVED', 'JOB READY'],
];

$tradeDefaults = [
    'electrician' => 'Expert training in domestic and industrial wiring, transformer maintenance, and electrical power generation systems.',
    'fitter' => 'Precision machining, metal fitting, blueprint reading, and assembly techniques for industrial manufacturing roles.',
];

$tickerItems = [];
foreach ($flashNews as $f) {
    $tickerItems[] = trim($f['title'] . (!empty($f['content']) ? ': ' . $f['content'] : ''));
}
if (empty($tickerItems)) {
    $phone = $header['phone'] ?? '+91-9155401839';
    $tickerItems = [
        'Admissions Open for Session 2026-2028',
        'NCVT Affiliated Trades: Electrician & Fitter',
        'Bihar Student Credit Card (BSCC) Scheme Accepted',
        'Last date for initial registration approaching soon',
        'Contact for Counseling: ' . $phone,
    ];
}
$tickerText = implode(' • ', $tickerItems);

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$footerPhone = $footer['phone'] ?? $header['phone'] ?? '+91-9155401839';
$footerAddress = $footer['address'] ?? "Maner, Patna,\nBihar - 801108";
$footerAbout = $footer['about_text'] ?? "Empowering Bihar's youth through world-class vocational training and industry-recognized certifications.";
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. NCVT Affiliated Institution. All Rights Reserved.';

$heroEyebrow = $hero['subtitle'] ?? "Bihar's Leading Technical Institute";
$heroTitleHtml = "Master Your Trade,<br/>Shape Your Future";
if (!empty($hero['title']) && $hero['title'] !== 'Maner Pvt ITI') {
    $heroTitleHtml = e($hero['title']);
}
$heroDesc = $hero['description'] ?? "Join Bihar's premier NCVT-affiliated institution. We combine industrial rigor with modern technical proficiency to prepare you for a high-impact engineering career.";
$cta1Text = $hero['cta_text'] ?? 'Fast-Track Your Career';
$cta1Link = site_url(ltrim($hero['cta_link'] ?? 'apply-admission', '/'));
$cta2Text = $hero['cta2_text'] ?? 'Download Prospectus';
$cta2Link = site_url(ltrim($hero['cta2_link'] ?? 'fee-structure', '/'));

$tradeCount = max(count($trades), 2);
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
  <meta charset="utf-8"/>
  <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
  <title><?= e($title ?? 'Maner Private ITI - Master Your Trade') ?></title>
  <meta name="description" content="<?= e($settings['seo_description'] ?? 'Official website of Maner Private ITI, Patna.') ?>">
  <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@400;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
  <link href="https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@100..900&family=Inter:wght@100..900&family=JetBrains+Mono:wght@100..900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="<?= asset('css/design.css') ?>">
  <script id="tailwind-config">
    tailwind.config = {
      darkMode: "class",
      theme: {
        extend: {
          colors: {
            "on-tertiary-fixed-variant": "#004b73",
            "secondary-fixed-dim": "#ffb95f",
            "secondary-fixed": "#ffddb8",
            "on-background": "#191c1e",
            "surface-bright": "#f7f9fb",
            "inverse-primary": "#bec6e0",
            "on-primary": "#ffffff",
            "on-primary-fixed": "#131b2e",
            "on-surface-variant": "#45464d",
            "on-tertiary": "#ffffff",
            "tertiary": "#000000",
            "on-tertiary-fixed": "#001d31",
            "on-error-container": "#93000a",
            "on-secondary-container": "#684000",
            "secondary-container": "#fea619",
            "on-primary-container": "#7c839b",
            "surface-tint": "#565e74",
            "tertiary-fixed-dim": "#93ccff",
            "primary-fixed-dim": "#bec6e0",
            "error": "#ba1a1a",
            "on-primary-fixed-variant": "#3f465c",
            "inverse-surface": "#2d3133",
            "secondary": "#855300",
            "on-tertiary-container": "#188ace",
            "surface-container-high": "#e6e8ea",
            "surface": "#f7f9fb",
            "on-secondary-fixed": "#2a1700",
            "background": "#f7f9fb",
            "on-error": "#ffffff",
            "on-secondary": "#ffffff",
            "surface-dim": "#d8dadc",
            "primary": "#000000",
            "primary-container": "#131b2e",
            "tertiary-container": "#001d31",
            "outline-variant": "#c6c6cd",
            "on-surface": "#191c1e",
            "surface-container": "#eceef0",
            "inverse-on-surface": "#eff1f3",
            "surface-variant": "#e0e3e5",
            "surface-container-lowest": "#ffffff",
            "surface-container-low": "#f2f4f6",
            "outline": "#76777d",
            "on-secondary-fixed-variant": "#653e00",
            "error-container": "#ffdad6",
            "surface-container-highest": "#e0e3e5",
            "tertiary-fixed": "#cce5ff",
            "primary-fixed": "#dae2fd"
          },
          borderRadius: {
            DEFAULT: "0.125rem",
            lg: "0.25rem",
            xl: "0.5rem",
            full: "0.75rem"
          },
          spacing: {
            "margin-mobile": "16px",
            base: "8px",
            "section-gap": "80px",
            "container-max": "1280px",
            gutter: "24px"
          },
          fontFamily: {
            display: ["Hanken Grotesk"],
            "body-lg": ["Inter"],
            "headline-lg": ["Hanken Grotesk"],
            "label-sm": ["JetBrains Mono"],
            "headline-md": ["Hanken Grotesk"],
            "headline-lg-mobile": ["Hanken Grotesk"],
            "body-md": ["Inter"]
          },
          fontSize: {
            display: ["48px", { lineHeight: "56px", letterSpacing: "-0.02em", fontWeight: "800" }],
            "body-lg": ["18px", { lineHeight: "28px", fontWeight: "400" }],
            "headline-lg": ["32px", { lineHeight: "40px", fontWeight: "700" }],
            "label-sm": ["12px", { lineHeight: "16px", letterSpacing: "0.05em", fontWeight: "500" }],
            "headline-md": ["24px", { lineHeight: "32px", fontWeight: "600" }],
            "headline-lg-mobile": ["28px", { lineHeight: "36px", fontWeight: "700" }],
            "body-md": ["16px", { lineHeight: "24px", fontWeight: "400" }]
          }
        }
      }
    };
  </script>
</head>
<body class="bg-background text-on-background font-body-md">

<!-- News Ticker -->
<div class="bg-primary-container text-white py-2 overflow-hidden whitespace-nowrap z-50 relative">
  <div class="ticker-scroll inline-block font-label-sm uppercase tracking-wider">
    <?= e($tickerText) ?>
  </div>
</div>

<!-- TopNavBar -->
<?php $navActive = 'home'; require base_path('views/partials/design-nav.php'); ?>

<main>
  <!-- Hero Section -->
  <section class="relative h-[640px] flex items-center overflow-hidden">
    <div class="absolute inset-0 z-0 bg-cover bg-center" style="background-image: url('<?= e($heroBg) ?>')"></div>
    <div class="absolute inset-0 z-10 industrial-overlay"></div>
    <div class="relative z-20 px-gutter max-w-container-max mx-auto w-full">
      <div class="max-w-2xl">
        <span class="font-label-sm text-secondary-fixed-dim tracking-[0.2em] uppercase mb-4 block"><?= e($heroEyebrow) ?></span>
        <h1 class="font-display text-display text-white mb-6">
          <?= $heroTitleHtml ?>
        </h1>
        <p class="font-body-lg text-body-lg text-white/80 mb-10 leading-relaxed">
          <?= e($heroDesc) ?>
        </p>
        <div class="flex flex-col sm:flex-row gap-4">
          <a href="<?= e($cta1Link) ?>" class="bg-secondary-container text-on-secondary-container px-8 py-4 font-bold rounded-lg flex items-center justify-center gap-2 group hover:shadow-lg transition-all">
            <?= e($cta1Text) ?>
            <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
          </a>
          <a href="<?= e($cta2Link) ?>" class="border border-white/30 text-white bg-white/10 backdrop-blur-sm px-8 py-4 font-bold rounded-lg hover:bg-white/20 transition-all text-center">
            <?= e($cta2Text) ?>
          </a>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Bento Bar -->
  <section class="relative z-30 -mt-12 px-gutter max-w-container-max mx-auto">
    <div class="grid grid-cols-2 md:grid-cols-4 gap-1 bg-surface-container shadow-xl border border-outline-variant">
      <div class="bg-surface p-8 text-center border-r border-outline-variant">
        <div class="font-display text-headline-lg text-primary">100%</div>
        <div class="font-label-sm text-outline uppercase">Placement Support</div>
      </div>
      <div class="bg-surface p-8 text-center border-r border-outline-variant">
        <div class="font-display text-headline-lg text-primary"><?= (int) $tradeCount ?>+</div>
        <div class="font-label-sm text-outline uppercase">Core Trades</div>
      </div>
      <div class="bg-surface p-8 text-center border-r border-outline-variant">
        <div class="font-display text-headline-lg text-primary">NCVT</div>
        <div class="font-label-sm text-outline uppercase">Govt. Affiliated</div>
      </div>
      <div class="bg-surface p-8 text-center">
        <div class="font-display text-headline-lg text-primary">500+</div>
        <div class="font-label-sm text-outline uppercase">Alumni Network</div>
      </div>
    </div>
  </section>

  <!-- Our Engineering Trades -->
  <section class="py-section-gap px-gutter max-w-container-max mx-auto">
    <div class="flex justify-between items-end mb-12">
      <div>
        <h2 class="font-display text-headline-lg text-primary mb-2">Our 2-Year Engineering Trades</h2>
        <p class="text-on-surface-variant max-w-xl">Intensive hands-on training designed to meet global industrial standards and NCVT requirements.</p>
      </div>
      <a class="hidden md:flex items-center gap-2 text-primary font-bold hover:underline" href="<?= site_url('trades') ?>">
        View All Details <span class="material-symbols-outlined">open_in_new</span>
      </a>
    </div>
    <div class="grid md:grid-cols-2 gap-8">
      <?php foreach ($trades as $t):
        $slug = $t['slug'] ?? '';
        $img = !empty($t['image']) ? upload_url($t['image']) : ($tradeImages[$slug] ?? $heroBg);
        $tags = $tradeTags[$slug] ?? ['NCVT APPROVED'];
        $desc = $t['description'] ?: ($tradeDefaults[$slug] ?? '');
        $duration = $t['duration'] ?? '2 Years';
        $durationLabel = strtoupper(preg_replace('/\s+/', '-', $duration)) . ' COURSE';
      ?>
      <div class="group border border-outline-variant overflow-hidden hover:shadow-xl transition-all duration-300">
        <div class="h-64 relative overflow-hidden">
          <div class="absolute inset-0 bg-cover bg-center group-hover:scale-105 transition-transform duration-500" style="background-image: url('<?= e($img) ?>')"></div>
          <div class="absolute top-4 left-4 bg-secondary-container text-on-secondary-container px-3 py-1 font-label-sm"><?= e($durationLabel) ?></div>
        </div>
        <div class="p-8">
          <h3 class="font-display text-headline-md mb-4 text-primary"><?= e($t['name']) ?></h3>
          <p class="text-on-surface-variant mb-6"><?= e($desc) ?></p>
          <div class="flex flex-wrap gap-3 mb-8">
            <?php foreach ($tags as $tag): ?>
            <span class="bg-surface-container px-3 py-1 text-xs font-label-sm border border-outline-variant"><?= e($tag) ?></span>
            <?php endforeach; ?>
          </div>
          <a href="<?= site_url('trades/' . $slug) ?>" class="block w-full text-center border-2 border-primary text-primary py-3 font-bold hover:bg-primary hover:text-white transition-all">Syllabus &amp; Details</a>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- BSCC Banner Section -->
  <section id="bscc" class="bg-primary-container py-section-gap overflow-hidden relative">
    <div class="absolute right-0 top-0 w-1/3 h-full opacity-10 pointer-events-none">
      <span class="material-symbols-outlined text-[300px]">account_balance</span>
    </div>
    <div class="px-gutter max-w-container-max mx-auto relative z-10">
      <div class="bscc-gradient p-12 md:p-20 border border-on-tertiary-container shadow-2xl flex flex-col md:flex-row items-center gap-12">
        <div class="flex-1">
          <div class="inline-flex items-center gap-2 bg-secondary-container text-on-secondary-container px-4 py-1 font-label-sm mb-6">
            <span class="material-symbols-outlined text-sm">verified</span>
            OFFICIAL BIHAR GOVT. SCHEME
          </div>
          <h2 class="font-display text-display text-white mb-6">Bihar Student Credit Card (BSCC) Accepted</h2>
          <p class="text-tertiary-fixed text-body-lg mb-8 max-w-2xl">
            Unlock your future without financial barriers. Get up to <strong class="text-secondary-fixed">₹4 Lakh credit limit</strong> with zero interest for girls and 4% for boys under the MNSSBY scheme.
          </p>
          <ul class="space-y-4 mb-10">
            <li class="flex items-center gap-3 text-white">
              <span class="material-symbols-outlined text-secondary-fixed">check_circle</span>
              Easy online documentation support
            </li>
            <li class="flex items-center gap-3 text-white">
              <span class="material-symbols-outlined text-secondary-fixed">check_circle</span>
              Zero processing fees for all applicants
            </li>
            <li class="flex items-center gap-3 text-white">
              <span class="material-symbols-outlined text-secondary-fixed">check_circle</span>
              Covers Tuition, Books, and Hostel fees
            </li>
          </ul>
          <a href="<?= site_url('bscc-info') ?>" class="inline-block bg-white text-primary px-10 py-4 font-bold rounded-lg hover:bg-secondary-fixed transition-colors">
            Check Eligibility
          </a>
        </div>
        <div class="w-full md:w-80 bg-white/5 backdrop-blur-md p-8 border border-white/20 rounded-xl text-center">
          <div class="mb-4">
            <span class="material-symbols-outlined text-6xl text-secondary-fixed">payments</span>
          </div>
          <div class="font-label-sm text-tertiary-fixed mb-1 uppercase">Max Credit Limit</div>
          <div class="font-display text-display text-white mb-4">₹4.0L</div>
          <p class="text-white/60 text-sm">Valid for the academic year 2026-2028 admissions.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Why Choose Us -->
  <section class="py-section-gap px-gutter max-w-container-max mx-auto">
    <div class="text-center mb-16">
      <h2 class="font-display text-headline-lg text-primary mb-4">Why Choose Maner Private ITI?</h2>
      <div class="w-20 h-1.5 bg-secondary-container mx-auto"></div>
    </div>
    <div class="grid md:grid-cols-3 gap-0 border border-outline-variant">
      <div class="p-12 border-b md:border-b-0 md:border-r border-outline-variant hover:bg-surface-container-low transition-colors group">
        <div class="w-16 h-16 bg-primary text-white flex items-center justify-center mb-8 group-hover:rotate-12 transition-transform">
          <span class="material-symbols-outlined text-3xl">workspace_premium</span>
        </div>
        <h3 class="font-display text-headline-md mb-4 text-primary">NCVT Affiliated</h3>
        <p class="text-on-surface-variant">Globally recognized certification from the National Council for Vocational Training (NCVT), Ministry of Skill Development.</p>
      </div>
      <div class="p-12 border-b md:border-b-0 md:border-r border-outline-variant hover:bg-surface-container-low transition-colors group">
        <div class="w-16 h-16 bg-primary text-white flex items-center justify-center mb-8 group-hover:rotate-12 transition-transform">
          <span class="material-symbols-outlined text-3xl">precision_manufacturing</span>
        </div>
        <h3 class="font-display text-headline-md mb-4 text-primary">Modern Labs</h3>
        <p class="text-on-surface-variant">High-density information layouts and precision-focused workshop environments designed for actual technical mastery.</p>
      </div>
      <div class="p-12 hover:bg-surface-container-low transition-colors group">
        <div class="w-16 h-16 bg-primary text-white flex items-center justify-center mb-8 group-hover:rotate-12 transition-transform">
          <span class="material-symbols-outlined text-3xl">handshake</span>
        </div>
        <h3 class="font-display text-headline-md mb-4 text-primary">Placement Support</h3>
        <p class="text-on-surface-variant">Dedicated cell connecting students with top industrial companies for apprenticeships and permanent placements.</p>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="bg-surface-container-highest border-t border-outline-variant py-20 px-gutter">
    <div class="max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center gap-12">
      <div class="text-center md:text-left">
        <h2 class="font-display text-headline-lg text-primary mb-2">Ready to Start Your Journey?</h2>
        <p class="text-on-surface-variant">Admissions are now open for the 2026 academic session. Book your campus tour today.</p>
      </div>
      <div class="flex flex-col sm:flex-row gap-4 w-full md:w-auto">
        <a href="<?= site_url('apply-admission') ?>" class="text-center bg-primary text-white px-10 py-4 font-bold rounded-lg hover:opacity-90 active:scale-95 transition-all">Apply Online</a>
        <a href="<?= site_url('contact') ?>" class="text-center bg-white border-2 border-primary text-primary px-10 py-4 font-bold rounded-lg hover:bg-primary hover:text-white transition-all">Visit Campus</a>
      </div>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<script src="<?= asset('js/home.js') ?>"></script>
</body>
</html>
