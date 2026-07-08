<?php
use App\Core\PublicNav;

$header = $header ?? \App\Models\SiteData::header();
$navActive = PublicNav::activeGroup($navActive ?? '');
$menu = PublicNav::menu();
$logoText = $header['logo_text'] ?? 'Maner Private ITI';
if ($logoText === 'Maner Pvt ITI') {
    $logoText = 'Maner Private ITI';
}
$logoUrl = site_institute_logo_url();
?>
<header class="site-mega-nav bg-surface sticky top-0 z-50 border-b border-outline-variant w-full" id="siteMegaNav">
  <div class="site-mega-nav__bar flex items-center justify-between gap-4 px-gutter max-w-container-max mx-auto w-full">
    <a href="<?= site_url() ?>" class="site-mega-nav__brand flex items-center gap-2.5 min-w-0 shrink-0 no-underline">
      <?php if ($logoUrl !== ''): ?>
      <img src="<?= e($logoUrl) ?>" alt="" class="site-mega-nav__logo" width="40" height="40" decoding="async">
      <?php endif; ?>
      <span class="site-mega-nav__brand-text font-headline-md font-bold text-primary"><?= e($logoText) ?></span>
    </a>

    <nav class="site-mega-nav__desktop hidden lg:flex items-center flex-1 justify-center min-w-0" aria-label="Primary">
      <ul class="site-mega-nav__list" role="menubar">
        <?php foreach ($menu as $item): ?>
        <?php
            $isActive = PublicNav::isItemActive($item, $navActive);
            $type = (string) ($item['type'] ?? 'link');
        ?>
        <li class="site-mega-nav__item<?= $type === 'dropdown' ? ' site-mega-nav__item--dropdown' : '' ?><?= $isActive ? ' is-active' : '' ?>" role="none">
          <?php if ($type === 'dropdown'): ?>
          <button
            type="button"
            class="site-mega-nav__trigger<?= $isActive ? ' is-active' : '' ?>"
            role="menuitem"
            aria-haspopup="true"
            aria-expanded="false"
            data-nav-dropdown-trigger
            data-nav-key="<?= e((string) ($item['key'] ?? '')) ?>"
          >
            <span><?= e((string) ($item['title'] ?? '')) ?></span>
            <span class="material-symbols-outlined site-mega-nav__chevron" aria-hidden="true">expand_more</span>
          </button>
          <template data-nav-template="<?= e((string) ($item['key'] ?? '')) ?>">
            <div class="site-mega-nav__panel-inner" role="menu">
              <?php foreach ($item['items'] ?? [] as $child): ?>
              <?php $childActive = PublicNav::isChildActive($child, $navActive); ?>
              <a
                role="menuitem"
                class="site-mega-nav__panel-link<?= $childActive ? ' is-active' : '' ?>"
                href="<?= e((string) ($child['url'] ?? '#')) ?>"
                <?= !empty($child['external']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
              >
                <span class="material-symbols-outlined site-mega-nav__panel-icon" aria-hidden="true"><?= e((string) ($child['icon'] ?? 'link')) ?></span>
                <span><?= e((string) ($child['title'] ?? '')) ?></span>
              </a>
              <?php endforeach; ?>
            </div>
          </template>
          <?php else: ?>
          <a
            class="site-mega-nav__link<?= $isActive ? ' is-active' : '' ?>"
            role="menuitem"
            href="<?= e((string) ($item['url'] ?? '#')) ?>"
          >
            <?= e((string) ($item['title'] ?? '')) ?>
          </a>
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
    </nav>

    <div class="site-mega-nav__actions hidden lg:flex items-center gap-3 shrink-0">
      <a href="<?= site_url('apply-admission') ?>" class="site-mega-nav__cta">Apply Now</a>
    </div>

    <button
      type="button"
      class="site-mega-nav__toggle lg:hidden"
      id="siteNavToggle"
      aria-label="Open menu"
      aria-expanded="false"
      aria-controls="siteNavDrawer"
    >
      <span class="material-symbols-outlined" data-nav-toggle-icon>menu</span>
    </button>
  </div>

  <div class="site-mega-nav__drawer" id="siteNavDrawer" hidden>
    <button type="button" class="site-mega-nav__drawer-backdrop" aria-label="Close menu" data-nav-drawer-close></button>
    <div class="site-mega-nav__drawer-panel" role="dialog" aria-modal="true" aria-label="Navigation menu">
      <div class="site-mega-nav__drawer-head">
        <span class="font-headline-md font-bold text-primary">Menu</span>
        <button type="button" class="site-mega-nav__drawer-close" aria-label="Close menu" data-nav-drawer-close>
          <span class="material-symbols-outlined">close</span>
        </button>
      </div>
      <nav class="site-mega-nav__drawer-nav" aria-label="Mobile">
        <?php foreach ($menu as $item): ?>
        <?php
            $isActive = PublicNav::isItemActive($item, $navActive);
            $type = (string) ($item['type'] ?? 'link');
        ?>
        <?php if ($type === 'dropdown'): ?>
        <details class="site-mega-nav__accordion<?= $isActive ? ' is-active' : '' ?>"<?= $isActive ? ' open' : '' ?>>
          <summary class="site-mega-nav__accordion-summary">
            <span class="material-symbols-outlined" aria-hidden="true"><?= e((string) ($item['icon'] ?? 'folder')) ?></span>
            <span><?= e((string) ($item['title'] ?? '')) ?></span>
            <span class="material-symbols-outlined site-mega-nav__accordion-chevron" aria-hidden="true">expand_more</span>
          </summary>
          <div class="site-mega-nav__accordion-body">
            <?php foreach ($item['items'] ?? [] as $child): ?>
            <?php $childActive = PublicNav::isChildActive($child, $navActive); ?>
            <a
              class="site-mega-nav__drawer-link<?= $childActive ? ' is-active' : '' ?>"
              href="<?= e((string) ($child['url'] ?? '#')) ?>"
              <?= !empty($child['external']) ? 'target="_blank" rel="noopener noreferrer"' : '' ?>
            >
              <span class="material-symbols-outlined" aria-hidden="true"><?= e((string) ($child['icon'] ?? 'link')) ?></span>
              <span><?= e((string) ($child['title'] ?? '')) ?></span>
            </a>
            <?php endforeach; ?>
          </div>
        </details>
        <?php else: ?>
        <a class="site-mega-nav__drawer-link site-mega-nav__drawer-link--top<?= $isActive ? ' is-active' : '' ?>" href="<?= e((string) ($item['url'] ?? '#')) ?>">
          <span class="material-symbols-outlined" aria-hidden="true"><?= e((string) ($item['icon'] ?? 'link')) ?></span>
          <span><?= e((string) ($item['title'] ?? '')) ?></span>
        </a>
        <?php endif; ?>
        <?php endforeach; ?>
      </nav>
      <div class="site-mega-nav__drawer-footer">
        <a href="<?= site_url('apply-admission') ?>" class="site-mega-nav__cta site-mega-nav__cta--block">Apply Now</a>
      </div>
    </div>
  </div>
</header>
<script src="<?= asset('js/site-nav.js') ?>?v=<?= (int) @filemtime(base_path('assets/js/site-nav.js')) ?>" defer></script>
