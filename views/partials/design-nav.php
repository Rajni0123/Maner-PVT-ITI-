<?php
$header = $header ?? \App\Models\SiteData::header();
$navActive = $navActive ?? '';
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$isActive = static function (string $key) use ($navActive): string {
    if ($key === $navActive) {
        return 'text-primary font-bold border-b-2 border-primary pb-1';
    }
    return 'text-on-surface-variant hover:text-primary transition-colors';
};
?>
<header class="bg-surface sticky top-0 z-40 border-b border-outline-variant">
  <div class="flex justify-between items-center px-gutter max-w-container-max mx-auto h-20 w-full">
    <a href="<?= site_url() ?>" class="font-headline-md text-headline-md font-bold text-primary">
      <?= e($logoText) ?>
    </a>
    <nav class="hidden md:flex items-center gap-8">
      <a class="font-body-md text-body-md <?= $isActive('home') ?>" href="<?= site_url() ?>">Home</a>
      <a class="font-body-md text-body-md <?= $isActive('courses') ?>" href="<?= site_url('trades') ?>">Trades</a>
      <a class="font-body-md text-body-md <?= $isActive('admission') ?>" href="<?= site_url('admission-process') ?>">Admission</a>
      <a class="font-body-md text-body-md <?= $isActive('bscc') ?>" href="<?= site_url('bscc-info') ?>">BSCC Info</a>
      <a class="font-body-md text-body-md <?= $isActive('contact') ?>" href="<?= site_url('contact') ?>">Contact</a>
    </nav>
    <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-6 py-2.5 font-bold rounded-lg hover:opacity-90 transition-all active:scale-95">
      Apply Now
    </a>
  </div>
</header>
