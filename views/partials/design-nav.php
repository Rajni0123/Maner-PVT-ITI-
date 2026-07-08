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
$mobileNavClass = static function (string $page) use ($navActive): string {
    $base = 'block px-4 py-3 font-body-md text-body-md border-b border-outline-variant';
    if ($navActive === $page) {
        return $base . ' text-primary font-bold bg-surface-container-low';
    }
    return $base . ' text-on-surface-variant hover:text-primary';
};
$logoUrl = site_institute_logo_url();
?>
<nav class="site-top-nav bg-surface sticky top-0 z-50 border-b border-outline-variant w-full" aria-label="Main">
  <div class="site-top-nav__bar flex justify-between items-center gap-3 px-gutter max-w-container-max mx-auto h-20 w-full">
    <a href="<?= site_url() ?>" class="site-brand flex items-center gap-2.5 min-w-0 flex-shrink-0 no-underline">
      <?php if ($logoUrl !== ''): ?>
      <img src="<?= e($logoUrl) ?>" alt="" class="site-brand__logo" width="40" height="40" decoding="async">
      <?php endif; ?>
      <span class="site-brand__text font-headline-md text-headline-md font-bold text-primary"><?= e($logoText) ?></span>
    </a>

    <div class="site-top-nav__desktop hidden xl:flex items-center gap-3 2xl:gap-5 min-w-0 flex-1 justify-end">
      <div class="site-top-nav__links flex items-center gap-3 2xl:gap-5 min-w-0 overflow-x-auto">
        <?php foreach ($menus as $menu): ?>
        <?php $key = nav_key_from_menu_url((string) ($menu['url'] ?? '')); ?>
        <a class="<?= $navClass($key) ?> whitespace-nowrap shrink-0" href="<?= e(menu_url((string) ($menu['url'] ?? '/'))) ?>"><?= e($menu['title'] ?? '') ?></a>
        <?php endforeach; ?>
      </div>
      <a href="<?= site_url('apply-admission') ?>" class="shrink-0 bg-secondary-container text-on-secondary-container px-5 py-2 font-bold hover:opacity-80 transition-all duration-200 whitespace-nowrap">Apply Now</a>
    </div>

    <button type="button" id="mobileMenuToggle" class="xl:hidden text-primary shrink-0" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenuPanel">
      <span class="material-symbols-outlined" data-menu-icon>menu</span>
    </button>
  </div>

  <div id="mobileMenuPanel" class="site-top-nav__panel xl:hidden border-t border-outline-variant bg-surface" hidden>
    <?php foreach ($menus as $menu): ?>
    <?php $key = nav_key_from_menu_url((string) ($menu['url'] ?? '')); ?>
    <a class="<?= $mobileNavClass($key) ?>" href="<?= e(menu_url((string) ($menu['url'] ?? '/'))) ?>"><?= e($menu['title'] ?? '') ?></a>
    <?php endforeach; ?>
    <div class="p-4">
      <a href="<?= site_url('apply-admission') ?>" class="block text-center bg-secondary-container text-on-secondary-container px-6 py-3 font-bold hover:opacity-80 transition-all duration-200">Apply Now</a>
    </div>
  </div>
</nav>
<script>
(function () {
  var btn = document.getElementById('mobileMenuToggle');
  var panel = document.getElementById('mobileMenuPanel');
  if (!btn || !panel) return;
  var icon = btn.querySelector('[data-menu-icon]');
  btn.addEventListener('click', function () {
    var open = panel.hasAttribute('hidden');
    if (open) {
      panel.removeAttribute('hidden');
      btn.setAttribute('aria-expanded', 'true');
      btn.setAttribute('aria-label', 'Close menu');
      if (icon) icon.textContent = 'close';
    } else {
      panel.setAttribute('hidden', '');
      btn.setAttribute('aria-expanded', 'false');
      btn.setAttribute('aria-label', 'Open menu');
      if (icon) icon.textContent = 'menu';
    }
  });
})();
</script>
