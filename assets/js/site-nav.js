(function () {
  'use strict';

  var nav = document.getElementById('siteMegaNav');
  if (!nav) return;

  var drawer = document.getElementById('siteNavDrawer');
  var toggle = document.getElementById('siteNavToggle');
  var dropdownItems = nav.querySelectorAll('.site-mega-nav__item--dropdown');
  var renderedPanels = {};
  var openDropdown = null;
  var closeTimer = null;

  function closeDropdown(item) {
    if (!item) return;
    item.classList.remove('is-open');
    var trigger = item.querySelector('[data-nav-dropdown-trigger]');
    var panel = item._navPanel;
    if (trigger) trigger.setAttribute('aria-expanded', 'false');
    if (panel) panel.setAttribute('hidden', '');
    if (openDropdown === item) openDropdown = null;
  }

  function closeAllDropdowns() {
    dropdownItems.forEach(closeDropdown);
  }

  function ensurePanel(item) {
    var key = item.querySelector('[data-nav-dropdown-trigger]')?.getAttribute('data-nav-key');
    if (!key) return null;
    if (renderedPanels[key]) {
      item._navPanel = renderedPanels[key];
      return renderedPanels[key];
    }

    var template = nav.querySelector('template[data-nav-template="' + key + '"]');
    if (!template) return null;

    var panel = document.createElement('div');
    panel.className = 'site-mega-nav__panel';
    panel.setAttribute('hidden', '');
    panel.setAttribute('data-nav-panel', key);
    panel.appendChild(template.content.cloneNode(true));
    item.appendChild(panel);
    item._navPanel = panel;
    renderedPanels[key] = panel;

    panel.addEventListener('mouseenter', function () {
      if (closeTimer) {
        clearTimeout(closeTimer);
        closeTimer = null;
      }
    });
    panel.addEventListener('mouseleave', function () {
      if (window.matchMedia('(min-width: 1024px)').matches) {
        closeDropdown(item);
      }
    });

    return panel;
  }

  function openDropdownItem(item) {
    var panel = ensurePanel(item);
    if (!panel) return;
    closeAllDropdowns();
    item.classList.add('is-open');
    panel.removeAttribute('hidden');
    var trigger = item.querySelector('[data-nav-dropdown-trigger]');
    if (trigger) {
      trigger.setAttribute('aria-expanded', 'true');
    }
    openDropdown = item;
  }

  dropdownItems.forEach(function (item) {
    var trigger = item.querySelector('[data-nav-dropdown-trigger]');
    if (!trigger) return;

    item.addEventListener('mouseenter', function () {
      if (window.matchMedia('(min-width: 1024px)').matches) {
        if (closeTimer) {
          clearTimeout(closeTimer);
          closeTimer = null;
        }
        openDropdownItem(item);
      }
    });

    item.addEventListener('mouseleave', function () {
      if (window.matchMedia('(min-width: 1024px)').matches) {
        closeTimer = setTimeout(function () {
          closeDropdown(item);
        }, 120);
      }
    });

    trigger.addEventListener('click', function (e) {
      e.preventDefault();
      if (item.classList.contains('is-open')) {
        closeDropdown(item);
      } else {
        openDropdownItem(item);
      }
    });

    trigger.addEventListener('keydown', function (e) {
      if (e.key === 'ArrowDown' || e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        openDropdownItem(item);
        var first = item._navPanel?.querySelector('.site-mega-nav__panel-link');
        if (first) first.focus();
      }
      if (e.key === 'Escape') {
        closeDropdown(item);
      }
    });
  });

  nav.addEventListener('keydown', function (e) {
    if (!openDropdown) return;
    var panel = openDropdown._navPanel;
    if (!panel || panel.hasAttribute('hidden')) return;
    var links = Array.prototype.slice.call(panel.querySelectorAll('.site-mega-nav__panel-link'));
    if (!links.length) return;
    var idx = links.indexOf(document.activeElement);
    if (e.key === 'ArrowDown') {
      e.preventDefault();
      links[Math.min(idx + 1, links.length - 1)].focus();
    }
    if (e.key === 'ArrowUp') {
      e.preventDefault();
      links[Math.max(idx - 1, 0)].focus();
    }
    if (e.key === 'Escape') {
      e.preventDefault();
      var active = openDropdown;
      closeDropdown(openDropdown);
      var trigger = active ? active.querySelector('[data-nav-dropdown-trigger]') : null;
      if (trigger) trigger.focus();
    }
  });

  document.addEventListener('click', function (e) {
    if (!nav.contains(e.target)) closeAllDropdowns();
  });

  window.addEventListener('resize', function () {
    if (!window.matchMedia('(min-width: 1024px)').matches) {
      closeAllDropdowns();
    }
  });

  function setDrawerOpen(open) {
    if (!drawer || !toggle) return;
    var icon = toggle.querySelector('[data-nav-toggle-icon]');
    if (open) {
      drawer.removeAttribute('hidden');
      document.body.classList.add('site-nav-drawer-open');
      toggle.setAttribute('aria-expanded', 'true');
      toggle.setAttribute('aria-label', 'Close menu');
      if (icon) icon.textContent = 'close';
    } else {
      drawer.setAttribute('hidden', '');
      document.body.classList.remove('site-nav-drawer-open');
      toggle.setAttribute('aria-expanded', 'false');
      toggle.setAttribute('aria-label', 'Open menu');
      if (icon) icon.textContent = 'menu';
    }
  }

  if (toggle && drawer) {
    toggle.addEventListener('click', function () {
      setDrawerOpen(drawer.hasAttribute('hidden'));
    });
    drawer.querySelectorAll('[data-nav-drawer-close]').forEach(function (btn) {
      btn.addEventListener('click', function () { setDrawerOpen(false); });
    });
    drawer.querySelectorAll('a[href]').forEach(function (link) {
      link.addEventListener('click', function () { setDrawerOpen(false); });
    });
  }

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape' && drawer && !drawer.hasAttribute('hidden')) {
      setDrawerOpen(false);
      if (toggle) toggle.focus();
    }
    if (e.key === 'Escape') closeAllDropdowns();
  });

  window.addEventListener('scroll', function () {
    if (window.scrollY > 50) {
      nav.classList.add('is-scrolled');
    } else {
      nav.classList.remove('is-scrolled');
    }
  }, { passive: true });
})();
