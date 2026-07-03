<?php
$relatedTrade = null;
if (!empty($design['related_slug'])) {
    foreach ($trades as $t) {
        if (($t['slug'] ?? '') === $design['related_slug']) {
            $relatedTrade = $t;
            break;
        }
    }
}
if (!$relatedTrade) {
    foreach ($trades as $t) {
        if (($t['slug'] ?? '') !== $slug) {
            $relatedTrade = $t;
            break;
        }
    }
}

$durationDisplay = preg_match('/^\d/', (string) $duration) ? $duration . ' Course' : $duration;
$certification = $design['certification'] ?? 'DGT - NCVT';
$bsccLogo = $design['bscc_logo'] ?? '';
?>
<main class="pt-20">
  <section class="relative h-[450px] flex items-center overflow-hidden bg-primary-container">
    <?php if (!empty($design['hero_image'])): ?>
    <div class="absolute inset-0 z-0 opacity-40">
      <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?= e($design['hero_image']) ?>')"></div>
    </div>
    <?php endif; ?>
    <div class="absolute inset-0 bg-gradient-to-r from-primary-container via-primary-container/80 to-transparent z-10"></div>
    <div class="relative z-20 max-w-container-max mx-auto px-gutter w-full">
      <div class="max-w-2xl">
        <div class="inline-flex items-center gap-2 bg-secondary-container/20 border border-secondary-container px-3 py-1 mb-6">
          <span class="material-symbols-outlined text-secondary-container" style="font-variation-settings: 'FILL' 1;">verified</span>
          <span class="text-secondary-container font-label-sm text-label-sm uppercase tracking-wider"><?= e($design['ncvt_code']) ?></span>
        </div>
        <h1 class="font-display text-display text-white mb-4"><?= e($trade['name']) ?> Trade Syllabus</h1>
        <p class="font-body-lg text-body-lg text-white/80 mb-8 max-w-lg"><?= e($design['hero_desc']) ?></p>
        <div class="flex flex-wrap gap-4">
          <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 border border-white/20">
            <span class="material-symbols-outlined text-white">schedule</span>
            <div class="flex flex-col">
              <span class="text-white/60 font-label-sm text-label-sm uppercase">Duration</span>
              <span class="text-white font-bold"><?= e($durationDisplay) ?></span>
            </div>
          </div>
          <div class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-5 py-3 border border-white/20">
            <span class="material-symbols-outlined text-white">military_tech</span>
            <div class="flex flex-col">
              <span class="text-white/60 font-label-sm text-label-sm uppercase">Certification</span>
              <span class="text-white font-bold"><?= e($certification) ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <?php if (!empty($design['semesters'])): ?>
  <section class="py-section-gap industrial-grid">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
        <div class="max-w-xl">
          <h2 class="font-headline-lg text-headline-lg text-primary mb-2">Curriculum Breakdown</h2>
          <p class="text-on-surface-variant"><?= e($design['curriculum_intro'] ?? '') ?></p>
        </div>
        <?php if ($syllabusPdf): ?>
        <a href="<?= e($syllabusPdf) ?>" target="_blank" rel="noopener" class="flex items-center gap-3 bg-primary text-white px-8 py-4 font-bold hover:bg-on-surface-variant transition-all">
          <span class="material-symbols-outlined">download</span>
          Download PDF Syllabus
        </a>
        <?php else: ?>
        <a href="<?= site_url('contact') ?>" class="flex items-center gap-3 bg-primary text-white px-8 py-4 font-bold hover:bg-on-surface-variant transition-all">
          <span class="material-symbols-outlined">download</span>
          Download PDF Syllabus
        </a>
        <?php endif; ?>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <?php foreach ($design['semesters'] as $sem): ?>
        <div class="bg-white border border-outline-variant p-8 relative hover:shadow-xl transition-shadow group">
          <div class="absolute top-0 right-0 p-4 font-display text-surface-container-high text-6xl font-bold group-hover:text-secondary-container/20 transition-colors"><?= e($sem['number'] ?? '') ?></div>
          <div class="flex items-center gap-3 mb-6">
            <div class="w-12 h-12 bg-primary-container flex items-center justify-center">
              <span class="material-symbols-outlined text-white"><?= e($sem['icon'] ?? 'school') ?></span>
            </div>
            <h3 class="font-headline-md text-headline-md text-primary"><?= e($sem['title']) ?></h3>
          </div>
          <ul class="space-y-4">
            <?php foreach ($sem['topics'] as $topic): ?>
            <li class="flex items-start gap-3">
              <span class="material-symbols-outlined text-secondary-container mt-1">check_circle</span>
              <div>
                <span class="font-bold text-primary block"><?= e($topic['title']) ?></span>
                <p class="text-sm text-on-surface-variant"><?= e($topic['desc']) ?></p>
              </div>
            </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <section class="py-section-gap bg-surface-container-low border-y border-outline-variant">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="flex flex-col md:flex-row gap-12">
        <div class="w-full md:w-1/3">
          <h2 class="font-headline-lg text-headline-lg text-primary mb-6">Career Opportunities</h2>
          <p class="text-on-surface-variant mb-8"><?= e($design['career_intro'] ?? '') ?></p>
          <div class="space-y-4">
            <?php foreach ($design['career_partners'] ?? [] as $partner): ?>
            <div class="flex items-center gap-4 bg-white p-4 border border-outline-variant shadow-sm">
              <div class="w-12 h-12 flex items-center justify-center bg-surface-container">
                <span class="material-symbols-outlined text-primary"><?= e($partner['icon']) ?></span>
              </div>
              <span class="font-bold text-primary"><?= e($partner['name']) ?></span>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php if ($relatedTrade): ?>
        <div class="w-full md:w-2/3">
          <div class="bg-primary-container p-10 h-full relative overflow-hidden">
            <div class="relative z-10">
              <h3 class="font-headline-lg text-white mb-6"><?= e($design['related_heading'] ?? 'Thinking of a different path?') ?></h3>
              <p class="text-white/70 mb-10 max-w-lg"><?= e($design['related_desc'] ?? '') ?></p>
              <a class="inline-flex items-center gap-4 group" href="<?= site_url('trades/' . ($relatedTrade['slug'] ?? '')) ?>">
                <div class="p-6 bg-secondary-container group-hover:bg-secondary transition-colors">
                  <span class="material-symbols-outlined text-on-primary-fixed font-bold" style="font-variation-settings: 'FILL' 1;"><?= e($design['related_icon'] ?? 'arrow_forward') ?></span>
                </div>
                <div class="flex flex-col">
                  <span class="text-secondary-container font-label-sm text-label-sm uppercase tracking-widest">Related Course</span>
                  <span class="text-white font-headline-md text-headline-md group-hover:translate-x-2 transition-transform"><?= e($design['related_label'] ?? ('Explore ' . $relatedTrade['name'] . ' Trade')) ?> →</span>
                </div>
              </a>
            </div>
            <?php if (!empty($design['related_bg_icon'])): ?>
            <div class="absolute -right-20 -bottom-20 opacity-10 rotate-12 scale-150 pointer-events-none">
              <span class="material-symbols-outlined text-[300px] text-white"><?= e($design['related_bg_icon']) ?></span>
            </div>
            <?php endif; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="py-16">
    <div class="max-w-container-max mx-auto px-gutter">
      <div class="border-2 border-on-tertiary-container bg-surface-container-lowest p-8 flex flex-col md:flex-row items-center gap-8 shadow-lg">
        <?php if ($bsccLogo): ?>
        <div class="w-24 h-24 flex-shrink-0 bg-surface-container flex items-center justify-center p-2">
          <div class="w-full h-full bg-cover bg-center" style="background-image: url('<?= e($bsccLogo) ?>')"></div>
        </div>
        <?php endif; ?>
        <div class="flex-grow text-center md:text-left">
          <span class="inline-block px-3 py-1 bg-on-tertiary-container/10 text-on-tertiary-container text-label-sm font-bold uppercase mb-2">Student Support Scheme</span>
          <h3 class="font-headline-md text-headline-md text-primary mb-2">Bihar Student Credit Card (BSCC) Scheme</h3>
          <p class="text-on-surface-variant">Maner Private ITI supports BSCC. Get zero-interest state-supported loans for your professional training.</p>
        </div>
        <a href="<?= site_url('bscc-info') ?>" class="bg-primary text-white px-8 py-3 font-bold hover:bg-secondary-container hover:text-on-secondary-container transition-all whitespace-nowrap">
          Check Eligibility
        </a>
      </div>
    </div>
  </section>
</main>
