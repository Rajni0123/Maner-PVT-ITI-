<?php
require_once base_path('app/Core/trade_syllabus_data.php');

$trade = $trade ?? [];
$trades = $trades ?? [];
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();

$slug = $trade['slug'] ?? '';
$design = trade_syllabus_design($slug, $trade ?? null);
if (!$design) {
    $design = [
        'ncvt_code' => 'NCVT AFFILIATED',
        'nsqf' => 'ITI TRADE',
        'hero_image' => '',
        'hero_desc' => $trade['description'] ?? '',
        'semesters' => [],
        'career_main' => null,
        'career_grid' => [],
    ];
}

$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$footerAddress = $footer['address'] ?? 'NH-30, Maner, Patna, Bihar - 801108';
$footerPhone = $footer['phone'] ?? $header['phone'] ?? '';
$footerEmail = $footer['email'] ?? $header['email'] ?? '';
$footerAbout = $footer['about_text'] ?? 'Dedicated to providing world-class vocational training and technical education in Bihar.';
$copyright = $footer['copyright_text'] ?? '© 2024 Maner Private ITI. All Rights Reserved. NCVT Affiliation: DGT-Bihar-6/2018.';

$duration = $trade['duration'] ?? '2 Years';
$durationLabel = strtoupper(preg_replace('/\s+/', '-', $duration));
$syllabusPdf = !empty($trade['syllabus_pdf']) ? upload_url($trade['syllabus_pdf']) : '';

$relatedTrade = null;
foreach ($trades as $t) {
    if (($t['slug'] ?? '') !== $slug) {
        $relatedTrade = $t;
        break;
    }
}

$layout = $design['layout'] ?? 'classic';

$pageTitle = ($trade['name'] ?? 'Trade') . ' Trade Syllabus | Maner Private ITI';
$pageDescription = 'Explore the ' . ($trade['name'] ?? '') . ' trade syllabus, semester breakdown, and career pathways at Maner Private ITI.';
$extraCss = ['trade-syllabus.css'];
$navActive = 'trades';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
</head>
<body class="bg-background text-on-surface selection:bg-secondary-container selection:text-on-secondary-container">

<?php require base_path('views/partials/design-nav-trade.php'); ?>

<?php if ($layout === 'industrial'): ?>
<?php require base_path('views/partials/trade-syllabus-industrial.php'); ?>
<?php require base_path('views/partials/design-footer.php'); ?>
<?php else: ?>

<main class="pt-20">
  <section class="relative bg-primary-container text-on-primary py-24 overflow-hidden">
    <div class="max-w-container-max mx-auto px-gutter relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
      <div class="max-w-2xl">
        <span class="inline-block bg-secondary-container text-on-secondary-fixed px-3 py-1 font-label-sm text-label-sm mb-4">NCVT AFFILIATED: <?= e($design['ncvt_code']) ?></span>
        <h1 class="font-display text-display text-white mb-6"><?= e($trade['name']) ?> Trade Syllabus</h1>
        <p class="font-body-lg text-body-lg text-on-primary-container/90 mb-8 max-w-lg">
          <?= e($design['hero_desc']) ?>
        </p>
        <div class="flex flex-wrap gap-4">
          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
            <span class="material-symbols-outlined text-secondary-container">schedule</span>
            <span class="font-label-sm text-label-sm text-white"><?= e($durationLabel) ?> DURATION</span>
          </div>
          <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md px-4 py-2 border border-white/20">
            <span class="material-symbols-outlined text-secondary-container">workspace_premium</span>
            <span class="font-label-sm text-label-sm text-white"><?= e($design['nsqf']) ?></span>
          </div>
        </div>
      </div>
      <?php if (!empty($design['hero_image'])): ?>
      <div class="relative w-full md:w-[450px] aspect-square group">
        <div class="absolute inset-0 border-2 border-secondary-container translate-x-4 translate-y-4 group-hover:translate-x-0 group-hover:translate-y-0 transition-transform duration-500"></div>
        <div class="h-full w-full bg-cover bg-center" style="background-image: url('<?= e($design['hero_image']) ?>')"></div>
      </div>
      <?php endif; ?>
    </div>
  </section>

  <?php if (!empty($design['semesters'])): ?>
  <section class="py-section-gap bg-white industrial-pattern">
    <div class="max-w-container-max mx-auto px-gutter syllabus-animate">
      <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-12 gap-6">
        <div>
          <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Semester-wise Breakdown</h2>
          <div class="w-24 h-1.5 bg-secondary-container"></div>
        </div>
        <?php if ($syllabusPdf): ?>
        <a href="<?= e($syllabusPdf) ?>" target="_blank" class="flex items-center gap-2 bg-primary text-white px-6 py-3 font-semibold hover:bg-secondary-container hover:text-primary transition-all">
          <span class="material-symbols-outlined">download</span>
          Download PDF Syllabus
        </a>
        <?php endif; ?>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php foreach ($design['semesters'] as $sem): ?>
        <div class="bg-surface-container-lowest border border-outline-variant p-6 hover:shadow-xl transition-all group">
          <div class="flex justify-between items-start mb-6">
            <span class="font-label-sm text-label-sm text-on-surface-variant"><?= e($sem['year']) ?></span>
            <span class="bg-primary/10 text-primary px-2 py-1 font-bold text-xs"><?= e($sem['sem']) ?></span>
          </div>
          <h3 class="font-headline-md text-headline-md mb-4 group-hover:text-secondary transition-colors"><?= e($sem['title']) ?></h3>
          <ul class="space-y-4">
            <?php foreach ($sem['topics'] as $topic): ?>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-primary mt-0.5 text-lg"><?= e($topic['icon']) ?></span>
              <p class="font-body-md text-body-md"><?= e($topic['text']) ?></p>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <?php if (!empty($design['career_main'])): ?>
  <section class="py-section-gap bg-surface-container-low">
    <div class="max-w-container-max mx-auto px-gutter syllabus-animate">
      <div class="mb-12 text-center">
        <h2 class="font-headline-lg text-headline-lg text-primary">Your Future Career Paths</h2>
        <p class="font-body-md text-body-md text-on-surface-variant mt-2">Opportunities for <?= e($trade['name']) ?> graduates in the public and private sector.</p>
      </div>
      <div class="bento-grid">
        <?php $main = $design['career_main']; ?>
        <div class="col-span-12 md:col-span-7 bg-primary-container p-8 text-on-primary-container relative overflow-hidden group">
          <div class="relative z-10">
            <span class="material-symbols-outlined text-secondary-container text-5xl mb-4"><?= e($main['icon']) ?></span>
            <h3 class="font-headline-lg text-white mb-4"><?= e($main['title']) ?></h3>
            <p class="font-body-md text-white/80 max-w-md mb-6"><?= e($main['desc']) ?></p>
            <div class="flex flex-wrap gap-4">
              <?php foreach ($main['tags'] as $tag): ?>
              <span class="px-3 py-1 bg-white/10 border border-white/20 rounded-full text-xs font-label-sm"><?= e($tag) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          <div class="absolute right-0 bottom-0 opacity-20 group-hover:scale-110 transition-transform duration-700">
            <span class="material-symbols-outlined text-[200px]" style="font-variation-settings: 'FILL' 1;"><?= e($main['icon']) ?></span>
          </div>
        </div>
        <?php foreach ($design['career_grid'] as $item): ?>
          <?php if ($item['type'] === 'card'): ?>
          <div class="col-span-12 md:col-span-5 bg-white border border-outline-variant p-8 group hover:border-secondary-container transition-all">
            <span class="material-symbols-outlined text-primary text-4xl mb-4"><?= e($item['icon']) ?></span>
            <h3 class="font-headline-md text-primary mb-3"><?= e($item['title']) ?></h3>
            <p class="font-body-md text-on-surface-variant mb-6"><?= e($item['desc']) ?></p>
            <a class="inline-flex items-center gap-2 text-primary font-bold group-hover:gap-4 transition-all" href="<?= site_url('admission-process') ?>">
              View Career Pathways <span class="material-symbols-outlined">arrow_forward</span>
            </a>
          </div>
          <?php elseif ($item['type'] === 'gold'): ?>
          <div class="col-span-12 md:col-span-4 bg-secondary-container p-8 text-on-secondary-fixed group">
            <span class="material-symbols-outlined text-4xl mb-4"><?= e($item['icon']) ?></span>
            <h3 class="font-headline-md font-bold mb-3"><?= e($item['title']) ?></h3>
            <p class="font-body-md mb-6"><?= e($item['desc']) ?></p>
            <?php if (!empty($item['bullets'])): ?>
            <ul class="text-sm font-semibold space-y-2">
              <?php foreach ($item['bullets'] as $bullet): ?>
              <li class="flex items-center gap-2">• <?= e($bullet) ?></li>
              <?php endforeach; ?>
            </ul>
            <?php endif; ?>
          </div>
          <?php elseif ($item['type'] === 'image'): ?>
          <div class="col-span-12 md:col-span-8 relative min-h-[300px] flex items-center group overflow-hidden border border-outline-variant">
            <div class="absolute inset-0 bg-cover bg-center transition-transform duration-700 group-hover:scale-105" style="background-image: url('<?= e($item['image']) ?>')"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-primary to-transparent opacity-90"></div>
            <div class="relative z-10 p-12 max-w-md">
              <h3 class="font-headline-lg text-white mb-4"><?= e($item['title']) ?></h3>
              <p class="font-body-md text-white/80"><?= e($item['desc']) ?></p>
            </div>
          </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="max-w-container-max mx-auto px-gutter mb-section-gap syllabus-animate">
    <div class="bg-tertiary-container border-l-8 border-secondary-container p-12 flex flex-col md:flex-row items-center justify-between gap-12 relative overflow-hidden">
      <div class="relative z-10 max-w-2xl">
        <div class="flex items-center gap-4 mb-6">
          <div class="h-12 w-12 bg-white rounded-full flex items-center justify-center">
            <span class="material-symbols-outlined text-primary-container">account_balance</span>
          </div>
          <span class="font-label-sm text-label-sm text-tertiary-fixed tracking-[0.2em] uppercase">Student Credit Card Scheme</span>
        </div>
        <h2 class="font-display text-white text-4xl mb-4 leading-tight">Financial Support from Bihar Government</h2>
        <p class="font-body-lg text-tertiary-fixed-dim">Apply for the Bihar Student Credit Card (BSCC) and get up to ₹4 Lakhs for your ITI education with zero interest for female students and extremely low rates for male students.</p>
      </div>
      <div class="relative z-10 shrink-0">
        <a href="<?= site_url('bscc-info') ?>" class="inline-flex bg-white text-primary-container px-8 py-4 font-bold text-lg hover:bg-secondary-container hover:text-on-secondary-fixed transition-all items-center gap-3">
          BSCC Eligibility Check
          <span class="material-symbols-outlined">launch</span>
        </a>
      </div>
      <div class="absolute right-0 top-0 opacity-10 blur-2xl">
        <div class="h-64 w-64 bg-secondary-container rounded-full"></div>
      </div>
    </div>
  </section>

  <?php if ($relatedTrade): ?>
  <section class="py-section-gap border-t border-outline-variant">
    <div class="max-w-container-max mx-auto px-gutter text-center syllabus-animate">
      <span class="font-label-sm text-label-sm text-on-surface-variant block mb-4">EXPLORE MORE TRADES</span>
      <div class="inline-flex flex-col md:flex-row items-center gap-base">
        <span class="font-body-md text-body-md">Looking for other trades?</span>
        <a class="flex items-center gap-2 font-headline-md text-headline-md text-primary hover:text-secondary-container transition-all group underline decoration-secondary-container underline-offset-8" href="<?= site_url('trades/' . ($relatedTrade['slug'] ?? '')) ?>">
          View <?= e($relatedTrade['name']) ?> Trade Syllabus
          <span class="material-symbols-outlined group-hover:translate-x-2 transition-transform">arrow_forward</span>
        </a>
      </div>
    </div>
  </section>
  <?php endif; ?>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>

<?php endif; ?>

<script src="<?= asset('js/trade-syllabus.js') ?>"></script>
</body>
</html>
