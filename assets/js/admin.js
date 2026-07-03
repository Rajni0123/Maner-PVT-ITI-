document.querySelectorAll('button').forEach((button) => {
  button.addEventListener('mousedown', () => button.classList.add('scale-95'));
  button.addEventListener('mouseup', () => button.classList.remove('scale-95'));
  button.addEventListener('mouseleave', () => button.classList.remove('scale-95'));
});

document.querySelectorAll('form[data-confirm]').forEach((form) => {
  form.addEventListener('submit', (e) => {
    if (!confirm(form.getAttribute('data-confirm'))) {
      e.preventDefault();
    }
  });
});

(function () {
  var STORAGE_KEY = 'maner-admin-theme';
  var root = document.documentElement;
  var toggle = document.getElementById('adminThemeToggle');

  function applyTheme(theme) {
    var isDark = theme === 'dark';
    root.setAttribute('data-admin-theme', theme);
    root.classList.toggle('dark', isDark);
    root.classList.toggle('light', !isDark);
    localStorage.setItem(STORAGE_KEY, theme);
  }

  if (toggle) {
    toggle.addEventListener('click', function () {
      var next = root.getAttribute('data-admin-theme') === 'dark' ? 'light' : 'dark';
      applyTheme(next);
    });
  }
})();

(function () {
  var btn = document.getElementById('adminNotifBtn');
  var panel = document.getElementById('adminNotifPanel');
  var badge = document.getElementById('adminNotifBadge');
  var list = document.getElementById('adminNotifList');
  var countLabel = document.getElementById('adminNotifCountLabel');
  var feedUrl = window.ADMIN_NOTIF_FEED_URL;

  if (!btn || !panel) return;

  function setCount(count) {
    if (!badge || !countLabel) return;
    if (count > 0) {
      badge.textContent = count > 99 ? '99+' : String(count);
      badge.classList.remove('hidden');
      countLabel.textContent = count + ' new';
    } else {
      badge.classList.add('hidden');
      countLabel.textContent = '0 new';
    }
  }

  function renderItems(items) {
    if (!list) return;
    if (!items || !items.length) {
      list.innerHTML = '<p class="admin-notif-empty">No new messages or form submissions.</p>';
      return;
    }
    list.innerHTML = items.map(function (n) {
      var icon = n.type === 'admission' ? 'person_add' : 'mail';
      return (
        '<a href="' + n.url + '" class="admin-notif-item" data-type="' + n.type + '" data-id="' + n.id + '">' +
        '<span class="material-symbols-outlined admin-notif-icon">' + icon + '</span>' +
        '<span class="admin-notif-body">' +
        '<span class="admin-notif-title">' + escapeHtml(n.title) + '</span>' +
        '<span class="admin-notif-text">' + escapeHtml(n.text) + '</span>' +
        '<span class="admin-notif-meta">' + escapeHtml(n.meta) + ' · ' + escapeHtml(n.time_label) + '</span>' +
        '</span></a>'
      );
    }).join('');
  }

  function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text || '';
    return div.innerHTML;
  }

  function refreshNotifications() {
    if (!feedUrl) return;
    fetch(feedUrl, { credentials: 'same-origin', headers: { Accept: 'application/json' } })
      .then(function (r) { return r.json(); })
      .then(function (data) {
        setCount(data.count || 0);
        renderItems(data.items || []);
      })
      .catch(function () {});
  }

  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    var open = !panel.classList.contains('hidden');
    panel.classList.toggle('hidden', open);
    btn.setAttribute('aria-expanded', open ? 'false' : 'true');
    if (!open) refreshNotifications();
  });

  document.addEventListener('click', function (e) {
    if (!panel.contains(e.target) && !btn.contains(e.target)) {
      panel.classList.add('hidden');
      btn.setAttribute('aria-expanded', 'false');
    }
  });

  refreshNotifications();
  setInterval(refreshNotifications, 45000);
})();
