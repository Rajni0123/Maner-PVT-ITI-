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

  <!-- Mobile menu (app-style sheet) -->
  <div id="mobileMenuOverlay" class="mobile-menu-overlay md:hidden" hidden></div>
  <div id="mobileMenuPanel" class="mobile-menu-panel md:hidden" hidden>
    <div class="mobile-menu-panel-inner">
      <p class="mobile-menu-label">Menu</p>
      <?php foreach ($menus as $menu): ?>
      <?php
        $children = $menu['children'] ?? [];
        $menuUrl = (string) ($menu['url'] ?? '/');
        $hasChildren = !empty($children);
      ?>
      <?php if ($hasChildren): ?>
      <details class="mobile-submenu">
        <summary class="mobile-menu-link mobile-menu-summary">
          <?= e($menu['title'] ?? '') ?>
          <span class="material-symbols-outlined text-[20px]">expand_more</span>
        </summary>
        <div class="mobile-submenu-children">
          <?php foreach ($children as $child): ?>
          <?php
            $childUrl = (string) ($child['url'] ?? '#');
            $childHref = menu_url($childUrl);
            $childExternal = $isExternal($childUrl);
          ?>
          <a class="mobile-menu-sublink"
             href="<?= e($childHref) ?>"
             <?= $childExternal ? 'target="_blank" rel="noopener"' : '' ?>>
            <?= e($child['title'] ?? '') ?>
          </a>
          <?php endforeach; ?>
        </div>
      </details>
      <?php else: ?>
      <a class="mobile-menu-link" href="<?= e(menu_url($menuUrl)) ?>"><?= e($menu['title'] ?? '') ?></a>
      <?php endif; ?>
      <?php endforeach; ?>
      <a href="<?= site_url('apply-admission') ?>" class="mobile-menu-apply">Apply Now</a>
      <div class="mobile-menu-quick">
        <a href="<?= site_url('fee-structure') ?>">Fee Structure</a>
        <a href="<?= site_url('bscc-info') ?>">BSCC Info</a>
        <a href="<?= site_url('admission-process') ?>">Admission</a>
      </div>
    </div>
  </div>
</nav>
<script>
(function () {
  var toggle = document.getElementById('mobileMenuToggle');
  var panel = document.getElementById('mobileMenuPanel');
  var overlay = document.getElementById('mobileMenuOverlay');
  if (!toggle || !panel) return;

  function setOpen(open) {
    panel.hidden = !open;
    if (overlay) overlay.hidden = !open;
    document.body.classList.toggle('mobile-menu-open', open);
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    var icon = toggle.querySelector('.material-symbols-outlined');
    if (icon) icon.textContent = open ? 'close' : 'menu';
  }

  toggle.addEventListener('click', function () {
    setOpen(panel.hidden);
  });
  if (overlay) {
    overlay.addEventListener('click', function () { setOpen(false); });
  }
})();
</script>
<style>
.nav-dropdown-menu { transform: translateY(4px); }
.nav-dropdown:hover .nav-dropdown-menu,
.nav-dropdown:focus-within .nav-dropdown-menu { transform: translateY(0); }
.mobile-submenu summary::-webkit-details-marker { display: none; }
.mobile-submenu[open] summary .material-symbols-outlined { transform: rotate(180deg); }
.mobile-menu-overlay {
  position: fixed;
  inset: 0;
  top: 56px;
  background: rgba(15, 23, 42, 0.45);
  z-index: 40;
}
.mobile-menu-panel {
  position: fixed;
  left: 0;
  right: 0;
  top: 56px;
  z-index: 45;
  max-height: calc(100dvh - 56px - var(--bottom-nav-height, 64px) - env(safe-area-inset-bottom, 0px));
  overflow-y: auto;
  -webkit-overflow-scrolling: touch;
  background: #fff;
  border-bottom: 1px solid #e2e8f0;
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.12);
}
.mobile-menu-panel-inner {
  padding: 12px 16px 20px;
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.mobile-menu-label {
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: #94a3b8;
  margin: 4px 0 8px;
}
.mobile-menu-link,
.mobile-menu-summary {
  display: flex;
  align-items: center;
  justify-content: space-between;
  min-height: 48px;
  padding: 10px 12px;
  border-radius: 10px;
  color: #0f172a;
  font-weight: 600;
  font-size: 15px;
  text-decoration: none;
  list-style: none;
  cursor: pointer;
}
.mobile-menu-link:active,
.mobile-menu-summary:active {
  background: #f1f5f9;
}
.mobile-submenu-children {
  display: flex;
  flex-direction: column;
  padding: 0 0 8px 12px;
}
.mobile-menu-sublink {
  display: block;
  padding: 10px 12px;
  min-height: 44px;
  color: #475569;
  font-size: 14px;
  text-decoration: none;
  border-left: 2px solid #e2e8f0;
}
.mobile-menu-apply {
  display: block;
  margin-top: 10px;
  text-align: center;
  background: #fea619;
  color: #131b2e;
  font-weight: 800;
  padding: 14px 16px;
  border-radius: 12px;
  text-decoration: none;
}
.mobile-menu-quick {
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 8px;
  margin-top: 14px;
}
.mobile-menu-quick a {
  text-align: center;
  font-size: 11px;
  font-weight: 700;
  color: #334155;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  padding: 10px 6px;
  text-decoration: none;
}
body.mobile-menu-open {
  overflow: hidden;
}
@media (min-width: 768px) {
  .mobile-menu-overlay,
  .mobile-menu-panel { display: none !important; }
}
</style>
