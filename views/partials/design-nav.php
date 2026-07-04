<?php
$header = $header ?? \App\Models\SiteData::header();
$navActive = $navActive ?? '';
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$menus = \App\Models\SiteData::menus();
$hiddenMenuUrls = ['results', '/results', 'faculty', '/faculty', 'infrastructure', '/infrastructure', 'gallery', '/gallery', 'apply-admission', '/apply-admission'];
$menus = array_values(array_filter($menus, static function (array $menu) use ($hiddenMenuUrls): bool {
    $url = ltrim((string) ($menu['url'] ?? ''), '/');
    $normalized = $url === '' ? '/' : $url;
    return !in_array($normalized, $hiddenMenuUrls, true) && !in_array('/' . $url, $hiddenMenuUrls, true);
}));
if (empty($menus)) {
    $menus = [
        ['title' => 'Home', 'url' => '/'],
        ['title' => 'Courses', 'url' => 'trades'],
        ['title' => 'Admission', 'url' => 'admission-process'],
        ['title' => 'BSCC Info', 'url' => 'bscc-info'],
        ['title' => 'Contact', 'url' => 'contact'],
    ];
}
$navClass = static function (string $page) use ($navActive): string {
    if ($navActive === $page) {
        return 'text-primary font-bold border-b-2 border-primary pb-1 font-body-md text-body-md';
    }
    return 'text-on-surface-variant hover:text-primary transition-colors font-body-md text-body-md';
};
?>
<nav class="bg-surface sticky top-0 z-50 border-b border-outline-variant w-full">
  <div class="flex justify-between items-center px-gutter max-w-container-max mx-auto h-20 w-full">
    <a href="<?= site_url() ?>" class="font-headline-md text-headline-md font-bold text-primary"><?= e($logoText) ?></a>
    <div class="hidden md:flex items-center gap-5 flex-shrink-0">
      <?php foreach ($menus as $menu): ?>
      <?php $key = nav_key_from_menu_url((string) ($menu['url'] ?? '')); ?>
      <a class="<?= $navClass($key) ?> whitespace-nowrap" href="<?= e(menu_url((string) ($menu['url'] ?? '/'))) ?>"><?= e($menu['title'] ?? '') ?></a>
      <?php endforeach; ?>
      <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-6 py-2 font-bold hover:opacity-80 transition-all duration-200">Apply Now</a>
    </div>
    <button type="button" class="md:hidden text-primary" aria-label="Open menu">
      <span class="material-symbols-outlined">menu</span>
    </button>
  </div>
</nav>
