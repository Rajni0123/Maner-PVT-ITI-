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

/** Home-style primary items — exact order, same as homepage header. */
$primaryDefs = [
    ['key' => 'home', 'title' => 'Home', 'url' => '/', 'match' => ['', '/', 'home']],
    ['key' => 'courses', 'title' => 'Courses', 'url' => 'trades', 'match' => ['trades', 'courses']],
    ['key' => 'admission', 'title' => 'Admission', 'url' => 'admission-process', 'match' => ['admission-process', 'admission', 'apply-admission']],
    ['key' => 'bscc', 'title' => 'BSCC Info', 'url' => 'bscc-info', 'match' => ['bscc-info', 'bscc']],
    ['key' => 'contact', 'title' => 'Contact', 'url' => 'contact', 'match' => ['contact']],
];

$normalizeMenuPath = static function (string $url): string {
    if (preg_match('#^https?://#i', $url)) {
        return strtolower(rtrim($url, '/'));
    }
    $path = ltrim(parse_url($url, PHP_URL_PATH) ?: $url, '/');
    return strtolower($path === '' ? '/' : $path);
};

$usedPaths = [];
$primaryItems = [];
foreach ($primaryDefs as $def) {
    $chosen = null;
    foreach ($menus as $menu) {
        $path = $normalizeMenuPath((string) ($menu['url'] ?? ''));
        foreach ($def['match'] as $m) {
            $mNorm = $m === '' || $m === '/' ? '/' : strtolower(ltrim($m, '/'));
            if ($path === $mNorm || ($mNorm !== '/' && str_starts_with($path, $mNorm))) {
                $chosen = $menu;
                break 2;
            }
        }
    }
    $primaryItems[] = [
        'key' => $def['key'],
        'title' => $chosen['title'] ?? $def['title'],
        'url' => $chosen['url'] ?? $def['url'],
    ];
    $usedPaths[] = $normalizeMenuPath((string) ($chosen['url'] ?? $def['url']));
}

$moreItems = [];
foreach ($menus as $menu) {
    $path = $normalizeMenuPath((string) ($menu['url'] ?? ''));
    $isPrimary = false;
    foreach ($primaryDefs as $def) {
        foreach ($def['match'] as $m) {
            $mNorm = $m === '' || $m === '/' ? '/' : strtolower(ltrim($m, '/'));
            if ($path === $mNorm || ($mNorm !== '/' && str_starts_with($path, $mNorm))) {
                $isPrimary = true;
                break 2;
            }
        }
    }
    if ($isPrimary) {
        continue;
    }
    $moreItems[] = $menu;
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
$moreActive = false;
foreach ($moreItems as $menu) {
    if (nav_key_from_menu_url((string) ($menu['url'] ?? '')) === $navActive) {
        $moreActive = true;
        break;
    }
}
?>
<nav class="site-top-nav bg-surface sticky top-0 z-50 border-b border-outline-variant w-full" aria-label="Main">
  <div class="site-top-nav__bar flex justify-between items-center gap-4 px-gutter max-w-container-max mx-auto h-20 w-full">
    <a href="<?= site_url() ?>" class="site-brand flex items-center gap-2.5 min-w-0 flex-shrink-0 no-underline">
      <?php if ($logoUrl !== ''): ?>
      <img src="<?= e($logoUrl) ?>" alt="" class="site-brand__logo" width="40" height="40" decoding="async">
      <?php endif; ?>
      <span class="site-brand__text font-headline-md text-headline-md font-bold text-primary"><?= e($logoText) ?></span>
    </a>

    <div class="site-top-nav__desktop hidden md:flex items-center gap-6 lg:gap-8 flex-shrink-0">
      <?php foreach ($primaryItems as $item): ?>
      <a class="<?= $navClass($item['key']) ?> whitespace-nowrap" href="<?= e(menu_url((string) $item['url'])) ?>"><?= e($item['title']) ?></a>
      <?php endforeach; ?>

      <?php if (!empty($moreItems)): ?>
      <div class="site-nav-dropdown<?= $moreActive ? ' is-active' : '' ?>">
        <button type="button" class="site-nav-dropdown__btn <?= $moreActive ? 'text-primary font-bold border-b-2 border-primary pb-1' : 'text-on-surface-variant hover:text-primary' ?> font-body-md text-body-md" aria-expanded="false" aria-haspopup="true">
          More
          <span class="material-symbols-outlined site-nav-dropdown__chevron" aria-hidden="true">expand_more</span>
        </button>
        <div class="site-nav-dropdown__menu" role="menu" hidden>
          <?php foreach ($moreItems as $menu): ?>
          <?php $key = nav_key_from_menu_url((string) ($menu['url'] ?? '')); ?>
          <a role="menuitem" class="site-nav-dropdown__item<?= $navActive === $key ? ' is-active' : '' ?>" href="<?= e(menu_url((string) ($menu['url'] ?? '/'))) ?>"><?= e($menu['title'] ?? '') ?></a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <a href="<?= site_url('apply-admission') ?>" class="bg-secondary-container text-on-secondary-container px-6 py-2.5 font-bold rounded-lg hover:opacity-90 transition-all active:scale-95 whitespace-nowrap">Apply Now</a>
    </div>

    <button type="button" id="mobileMenuToggle" class="md:hidden text-primary shrink-0" aria-label="Open menu" aria-expanded="false" aria-controls="mobileMenuPanel">
      <span class="material-symbols-outlined" data-menu-icon>menu</span>
    </button>
  </div>

  <div id="mobileMenuPanel" class="site-top-nav__panel md:hidden border-t border-outline-variant bg-surface" hidden>
    <?php foreach ($primaryItems as $item): ?>
    <a class="<?= $mobileNavClass($item['key']) ?>" href="<?= e(menu_url((string) $item['url'])) ?>"><?= e($item['title']) ?></a>
    <?php endforeach; ?>

    <?php if (!empty($moreItems)): ?>
    <details class="site-nav-mobile-more"<?= $moreActive ? ' open' : '' ?>>
      <summary class="px-4 py-3 font-body-md text-body-md font-semibold text-on-surface border-b border-outline-variant cursor-pointer">More links</summary>
      <?php foreach ($moreItems as $menu): ?>
      <?php $key = nav_key_from_menu_url((string) ($menu['url'] ?? '')); ?>
      <a class="<?= $mobileNavClass($key) ?> pl-8" href="<?= e(menu_url((string) ($menu['url'] ?? '/'))) ?>"><?= e($menu['title'] ?? '') ?></a>
      <?php endforeach; ?>
    </details>
    <?php endif; ?>

    <div class="p-4">
      <a href="<?= site_url('apply-admission') ?>" class="block text-center bg-secondary-container text-on-secondary-container px-6 py-3 font-bold rounded-lg hover:opacity-80 transition-all duration-200">Apply Now</a>
    </div>
  </div>
</nav>
<script>
(function () {
  var btn = document.getElementById('mobileMenuToggle');
  var panel = document.getElementById('mobileMenuPanel');
  if (btn && panel) {
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
  }

  document.querySelectorAll('.site-nav-dropdown').forEach(function (wrap) {
    var trigger = wrap.querySelector('.site-nav-dropdown__btn');
    if (!trigger) return;
    var menu = wrap.querySelector('.site-nav-dropdown__menu');
    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      var willOpen = !wrap.classList.contains('is-open');
      document.querySelectorAll('.site-nav-dropdown.is-open').forEach(function (other) {
        other.classList.remove('is-open');
        var ob = other.querySelector('.site-nav-dropdown__btn');
        var om = other.querySelector('.site-nav-dropdown__menu');
        if (ob) ob.setAttribute('aria-expanded', 'false');
        if (om) om.setAttribute('hidden', '');
      });
      if (willOpen) {
        wrap.classList.add('is-open');
        trigger.setAttribute('aria-expanded', 'true');
        if (menu) menu.removeAttribute('hidden');
      } else if (menu) {
        menu.setAttribute('hidden', '');
      }
    });
  });

  document.addEventListener('click', function () {
    document.querySelectorAll('.site-nav-dropdown.is-open').forEach(function (wrap) {
      wrap.classList.remove('is-open');
      var b = wrap.querySelector('.site-nav-dropdown__btn');
      var m = wrap.querySelector('.site-nav-dropdown__menu');
      if (b) b.setAttribute('aria-expanded', 'false');
      if (m) m.setAttribute('hidden', '');
    });
  });
})();
</script>
