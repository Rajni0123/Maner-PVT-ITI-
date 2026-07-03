// Header shadow on scroll
window.addEventListener('scroll', () => {
  const header = document.querySelector('header');
  if (!header) return;
  if (window.scrollY > 50) {
    header.classList.add('shadow-md');
  } else {
    header.classList.remove('shadow-md');
  }
});

// Scroll Reveal Animation
(function () {
  const animatedElements = document.querySelectorAll('[data-animate]');
  if (!animatedElements.length) return;

  const reveal = (el) => el.classList.add('animate-visible');

  // Immediately show anything already in (or near) the viewport
  animatedElements.forEach((el) => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight + 80) {
      reveal(el);
    }
  });

  if (!('IntersectionObserver' in window)) {
    animatedElements.forEach(reveal);
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          reveal(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.05, rootMargin: '0px 0px 40px 0px' }
  );

  animatedElements.forEach((el) => {
    if (!el.classList.contains('animate-visible')) {
      observer.observe(el);
    }
  });

  // Safety: never leave sections invisible
  setTimeout(() => {
    animatedElements.forEach(reveal);
  }, 1200);
})();

// Counter Animation for Stats
(function () {
  const counters = document.querySelectorAll('[data-count]');
  if (!counters.length) return;

  function animateCounter(el) {
    if (el.dataset.counted === '1') return;
    el.dataset.counted = '1';

    const target = el.getAttribute('data-count');
    const suffix = el.getAttribute('data-suffix') || '';
    const prefix = el.getAttribute('data-prefix') || '';
    const num = parseInt(target, 10);
    if (isNaN(num)) {
      el.textContent = prefix + target + suffix;
      return;
    }

    const duration = 1200;
    const start = performance.now();
    el.textContent = prefix + '0' + suffix;

    function update(now) {
      const elapsed = now - start;
      const progress = Math.min(elapsed / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 3);
      const current = Math.round(eased * num);
      el.textContent = prefix + current + suffix;
      if (progress < 1) requestAnimationFrame(update);
    }
    requestAnimationFrame(update);
  }

  counters.forEach((el) => {
    const rect = el.getBoundingClientRect();
    if (rect.top < window.innerHeight && rect.bottom > 0) {
      animateCounter(el);
    }
  });

  if (!('IntersectionObserver' in window)) {
    counters.forEach(animateCounter);
    return;
  }

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounter(entry.target);
          observer.unobserve(entry.target);
        }
      });
    },
    { threshold: 0.2, rootMargin: '0px 0px 40px 0px' }
  );

  counters.forEach((el) => {
    if (el.dataset.counted !== '1') observer.observe(el);
  });
})();

// Floating CTA buttons
(function () {
  const fab = document.getElementById('floatingCTA');
  if (!fab) return;
  setTimeout(() => {
    fab.classList.add('fab-visible');
  }, 800);
})();
