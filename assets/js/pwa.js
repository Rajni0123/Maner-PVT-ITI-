// Service Worker Registration
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker
      .register('/sw.js')
      .then((reg) => {
        reg.addEventListener('updatefound', () => {
          const newWorker = reg.installing;
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'activated' && navigator.serviceWorker.controller) {
              if (confirm('New version available! Reload to update?')) {
                window.location.reload();
              }
            }
          });
        });
      })
      .catch((err) => console.log('SW registration failed:', err));
  });
}

// PWA Install Prompt
let deferredPrompt = null;
const installBanner = document.getElementById('pwaInstallBanner');
const installBtn = document.getElementById('pwaInstallBtn');
const installClose = document.getElementById('pwaInstallClose');

window.addEventListener('beforeinstallprompt', (e) => {
  e.preventDefault();
  deferredPrompt = e;

  const dismissed = localStorage.getItem('pwa-install-dismissed');
  if (dismissed && Date.now() - parseInt(dismissed) < 86400000) return;

  setTimeout(() => {
    if (installBanner) installBanner.classList.add('show');
  }, 3000);
});

if (installBtn) {
  installBtn.addEventListener('click', async () => {
    if (!deferredPrompt) return;
    deferredPrompt.prompt();
    const result = await deferredPrompt.userChoice;
    if (result.outcome === 'accepted') {
      if (installBanner) installBanner.classList.remove('show');
    }
    deferredPrompt = null;
  });
}

if (installClose) {
  installClose.addEventListener('click', () => {
    if (installBanner) installBanner.classList.remove('show');
    localStorage.setItem('pwa-install-dismissed', Date.now().toString());
  });
}

window.addEventListener('appinstalled', () => {
  if (installBanner) installBanner.classList.remove('show');
  deferredPrompt = null;
});

// Bottom Nav - hide on scroll down, show on scroll up
(function () {
  const nav = document.getElementById('bottomNav');
  if (!nav) return;

  let lastScroll = 0;
  let ticking = false;

  window.addEventListener('scroll', () => {
    if (!ticking) {
      requestAnimationFrame(() => {
        const current = window.scrollY;
        if (current > lastScroll && current > 100) {
          nav.style.transform = 'translateY(100%)';
          nav.style.transition = 'transform 0.3s ease';
        } else {
          nav.style.transform = 'translateY(0)';
          nav.style.transition = 'transform 0.3s ease';
        }
        lastScroll = current;
        ticking = false;
      });
      ticking = true;
    }
  });
})();

// Add body class for bottom nav padding
(function () {
  if (window.innerWidth <= 768) {
    document.body.classList.add('has-bottom-nav');
  }
  window.addEventListener('resize', () => {
    if (window.innerWidth <= 768) {
      document.body.classList.add('has-bottom-nav');
    } else {
      document.body.classList.remove('has-bottom-nav');
    }
  });
})();

// Vibration feedback on nav tap (if supported)
document.querySelectorAll('.bottom-nav-item').forEach((item) => {
  item.addEventListener('click', () => {
    if (navigator.vibrate) navigator.vibrate(10);
  });
});
