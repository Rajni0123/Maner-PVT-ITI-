<?php
$header = $header ?? \App\Models\SiteData::header();
$footer = $footer ?? \App\Models\SiteData::footer();
$notices = $notices ?? [];
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$pageTitle = $title ?? 'Latest News & Updates | Maner Private ITI';
$pageDescription = 'Latest news, notices, and updates from Maner Private ITI — admissions, examinations, and announcements.';
$navActive = 'news';
?>
<!DOCTYPE html>
<html class="scroll-smooth" lang="en">
<head>
<?php require base_path('views/partials/design-head.php'); ?>
<link rel="stylesheet" href="<?= asset('css/pwa.css') ?>">
</head>
<body class="bg-background text-on-surface font-body-md">

<?php require base_path('views/partials/design-nav.php'); ?>

<main>
  <section class="bg-primary-container text-white py-16 md:py-20">
    <div class="max-w-container-max mx-auto px-gutter">
      <span class="font-label-sm text-label-sm text-secondary-container uppercase tracking-widest mb-3 block">Announcements</span>
      <h1 class="font-display text-display mb-4">Latest News &amp; Updates</h1>
      <p class="font-body-lg text-on-primary-container max-w-2xl">
        Official notices, admission updates, examination information, and institute announcements from <?= e($logoText) ?>.
      </p>
    </div>
  </section>

  <section class="py-section-gap max-w-container-max mx-auto px-gutter">
    <?php if (!$notices): ?>
    <div class="bg-white border border-outline-variant p-10 text-center">
      <span class="material-symbols-outlined text-5xl text-on-surface-variant mb-4 block">campaign</span>
      <h2 class="font-headline-md text-headline-md text-primary mb-2">No updates yet</h2>
      <p class="text-on-surface-variant mb-6">New notices and announcements will appear here. Check back soon.</p>
      <a href="<?= site_url('contact') ?>" class="inline-flex bg-secondary-container text-on-secondary-container px-6 py-3 font-bold hover:opacity-90">Contact Office</a>
    </div>
    <?php else: ?>
    <div class="space-y-4">
      <?php foreach ($notices as $n): ?>
      <article class="bg-white border border-outline-variant p-6 md:p-8 hover:shadow-md transition-shadow">
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
          <div class="flex-1">
            <div class="flex flex-wrap items-center gap-3 mb-3">
              <span class="inline-flex items-center gap-1 bg-secondary-container/15 text-secondary px-3 py-1 font-label-sm text-label-sm uppercase tracking-wider">
                <span class="material-symbols-outlined text-[14px]">campaign</span> News
              </span>
              <time class="font-label-sm text-label-sm text-on-surface-variant"><?= format_date($n['created_at'] ?? null) ?></time>
            </div>
            <h2 class="font-headline-md text-headline-md text-primary mb-3"><?= e($n['title'] ?? '') ?></h2>
            <div class="font-body-md text-on-surface-variant leading-relaxed whitespace-pre-wrap"><?= e($n['description'] ?? '') ?></div>
          </div>
          <?php if (!empty($n['pdf'])): ?>
          <div class="shrink-0">
            <a href="<?= e(upload_url($n['pdf'])) ?>" target="_blank" class="inline-flex items-center gap-2 border border-primary text-primary px-4 py-2 font-bold hover:bg-primary hover:text-white transition-colors">
              <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
              Download PDF
            </a>
          </div>
          <?php endif; ?>
        </div>
      </article>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>

  <section class="bg-surface-container-highest border-t border-outline-variant py-16 px-gutter">
    <div class="max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center gap-8">
      <div>
        <h2 class="font-headline-md text-headline-md text-primary mb-2">Need more information?</h2>
        <p class="text-on-surface-variant">Contact the institute office for admission counseling and latest circulars.</p>
      </div>
      <div class="flex flex-wrap gap-4">
        <a href="<?= site_url('apply-admission') ?>" class="bg-primary text-white px-8 py-3 font-bold hover:opacity-90">Apply Online</a>
        <a href="<?= site_url('contact') ?>" class="border-2 border-primary text-primary px-8 py-3 font-bold hover:bg-primary hover:text-white transition-colors">Contact Us</a>
      </div>
    </div>
  </section>
</main>

<?php require base_path('views/partials/design-footer.php'); ?>
<?php require base_path('views/partials/mobile-bottom-nav.php'); ?>
<script src="<?= asset('js/pwa.js') ?>"></script>
</body>
</html>
