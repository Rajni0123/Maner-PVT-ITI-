<?php
$header = $header ?? \App\Models\SiteData::header();
$navActive = $navActive ?? '';
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$menus = \App\Models\SiteData::menus();
$hiddenMenuUrls = ['results', '/results', 'faculty', '/faculty', 'infrastructure', '/infrastructure', 'gallery', '/gallery', 'apply-admission', '/apply-admission', 'notices', '/notices'];
$menus = array_values(array_filter($menus, static function (array $menu) use ($hiddenMenuUrls): bool {
    $title = strtolower(trim((string) ($menu['title'] ?? '')));
    if (str_contains($title, 'latest news')) {
        return false;
    }
    $url = ltrim((string) ($menu['url'] ?? ''), '/');
    $normalized = $url === '' ? '/' : $url;
    return !in_array($normalized, $hiddenMenuUrls, true) && !in_array('/' . $url, $hiddenMenuUrls, true);
}));
if (empty($menus)) {
    $menus = [
        ['title' => 'Home', 'url' => '/', 'children' => []],
        ['title' => 'Courses', 'url' => 'trades', 'children' => []],
        ['title' => 'Admission', 'url' => 'admission-process', 'children' => []],
        ['title' => 'BSCC Info', 'url' => 'bscc-info', 'children' => []],
        ['title' => 'Contact', 'url' => 'contact', 'children' => []],
        [
            'title' => 'Important Links',
            'url' => '#',
            'children' => [
                ['title' => 'NCVT MIS', 'url' => 'https://ncvtmis.gov.in'],
                ['title' => 'Bharat Skill', 'url' => 'https://bharatskills.gov.in'],
                ['title' => 'DET Hunnar', 'url' => 'https://dethunar-bih.com/'],
                ['title' => 'ITI Higher Level Exam', 'url' => 'https://ncvtmis.gov.in'],
                ['title' => 'Post Matric Scholarship', 'url' => 'https://scholarships.gov.in'],
                ['title' => 'National Scholarship Portal', 'url' => 'https://scholarships.gov.in'],
            ],
        ],
    ];
}
$navClass = static function (string $page) use ($navActive): string {
    if ($navActive === $page) {
        return 'text-primary font-bold border-b-2 border-primary pb-1 font-body-md text-body-md';
    }
    return 'text-on-surface-variant hover:text-primary transition-colors font-body-md text-body-md';
};
$isExternal = static function (string $url): bool {
    return (bool) preg_match('#^https?://#i', $url);
};
?>
<nav class="bg-surface sticky top-0 z-50 border-b border-outline-variant w-full">
  <div class="flex justify-between items-center px-gutter max-w-container-max mx-auto h-20 w-full">
    <a href="<?= site_url() ?>" class="font-headline-md text-headline-md font-bold text-primary"><?= e($logoText) ?></a>
    <div class="hidden md:flex items-center gap-5 flex-shrink-0">
      <?php foreach ($menus as $menu): ?>
      <?php
        $children = $menu['children'] ?? [];
        $menuUrl = (string) ($menu['url'] ?? '/');
        $key = nav_key_from_menu_url($menuUrl);
        $hasChildren = !empty($children);
      ?>
      <?php if ($hasChildren): ?>
      <div class="nav-dropdown relative group">
        <button type="button" class="<?= $navClass($key) ?> whitespace-nowrap inline-flex items-center gap-1 cursor-pointer bg-transparent border-0">
          <?= e($menu['title'] ?? '') ?>
          <span class="material-symbols-outlined text-[18px] transition-transform group-hover:rotate-180">expand_more</span>
        </button>
        <div class="nav-dropdown-menu absolute top-full left-0 mt-2 min-w-[260px] bg-surface-container-lowest border border-outline-variant shadow-xl py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
          <?php foreach ($children as $child): ?>
          <?php
            $childUrl = (string) ($child['url'] ?? '#');
            $childHref = menu_url($childUrl);
            $childExternal = $isExternal($childUrl);
          ?>
          <a class="block px-4 py-2.5 text-on-surface-variant hover:bg-surface-container hover:text-primary font-body-md text-sm transition-colors"
             href="<?= e($childHref) ?>"
             <?= $childExternal ? 'target="_blank" rel="noopener"' : '' ?>>
            <?= e($child['title'] ?? '') ?>
            <?php if ($childExternal): ?>
            <span class="material-symbols-outlined text-[14px] align-middle ml-1 opacity-60">open_in_new</span>
            <?php endif; ?>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php else: ?>
      <a class="<?= $navClass($key) ?> whitespace-nowrap" href="<?= e(menu_url($menuUrl)) ?>"><?= e($menu['title'] ?? '') ?></a>
      <?php endif; ?>
      <?php endforeach; ?>
      <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-6 py-2 font-bold hover:opacity-80 transition-all duration-200">Apply Now</a>
    </div>
    <button type="button" class="md:hidden text-primary" id="mobileMenuToggle" aria-label="Open menu" aria-expanded="false">
      <span class="material-symbols-outlined">menu</span>
    </button>
  </div>

  <!-- Mobile menu -->
  <div id="mobileMenuPanel" class="md:hidden hidden border-t border-outline-variant bg-surface">
    <div class="px-gutter py-3 flex flex-col gap-1">
      <?php foreach ($menus as $menu): ?>
      <?php
        $children = $menu['children'] ?? [];
        $menuUrl = (string) ($menu['url'] ?? '/');
        $hasChildren = !empty($children);
      ?>
      <?php if ($hasChildren): ?>
      <details class="mobile-submenu">
        <summary class="py-3 font-body-md text-on-surface font-semibold cursor-pointer list-none flex items-center justify-between">
          <?= e($menu['title'] ?? '') ?>
          <span class="material-symbols-outlined text-[20px]">expand_more</span>
        </summary>
        <div class="pl-3 pb-2 flex flex-col">
          <?php foreach ($children as $child): ?>
          <?php
            $childUrl = (string) ($child['url'] ?? '#');
            $childHref = menu_url($childUrl);
            $childExternal = $isExternal($childUrl);
          ?>
          <a class="py-2.5 text-on-surface-variant hover:text-primary font-body-md text-sm border-l-2 border-outline-variant pl-3"
             href="<?= e($childHref) ?>"
             <?= $childExternal ? 'target="_blank" rel="noopener"' : '' ?>>
            <?= e($child['title'] ?? '') ?>
          </a>
          <?php endforeach; ?>
        </div>
      </details>
      <?php else: ?>
      <a class="py-3 font-body-md text-on-surface-variant hover:text-primary" href="<?= e(menu_url($menuUrl)) ?>"><?= e($menu['title'] ?? '') ?></a>
      <?php endif; ?>
      <?php endforeach; ?>
      <a href="<?= site_url('apply-admission') ?>" class="mt-2 mb-2 text-center bg-secondary-container text-on-secondary-container px-6 py-3 font-bold">Apply Now</a>
    </div>
  </div>
</nav>
<script>
(function () {
  var toggle = document.getElementById('mobileMenuToggle');
  var panel = document.getElementById('mobileMenuPanel');
  if (!toggle || !panel) return;
  toggle.addEventListener('click', function () {
    var open = panel.classList.toggle('hidden') === false;
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    toggle.querySelector('.material-symbols-outlined').textContent = open ? 'close' : 'menu';
  });
})();
</script>
<style>
.nav-dropdown-menu { transform: translateY(4px); }
.nav-dropdown:hover .nav-dropdown-menu,
.nav-dropdown:focus-within .nav-dropdown-menu { transform: translateY(0); }
.mobile-submenu summary::-webkit-details-marker { display: none; }
.mobile-submenu[open] summary .material-symbols-outlined { transform: rotate(180deg); }
</style>
