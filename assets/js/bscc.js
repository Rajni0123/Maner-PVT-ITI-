const observerOptions = { threshold: 0.1 };
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.add('opacity-100');
      entry.target.classList.remove('translate-y-8');
    }
  });
}, observerOptions);

document.querySelectorAll('.bento-card').forEach((el) => {
  el.classList.add('opacity-0', 'translate-y-8', 'transition-all', 'duration-700');
  observer.observe(el);
});
