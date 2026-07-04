const observerOptions = { threshold: 0.1 };
const observer = new IntersectionObserver((entries) => {
  entries.forEach((entry) => {
    if (entry.isIntersecting) {
      entry.target.classList.remove('opacity-0', 'translate-y-10');
      entry.target.classList.add('opacity-100', 'translate-y-0');
    }
  });
}, observerOptions);

document.querySelectorAll('.syllabus-animate').forEach((el) => {
  el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
  observer.observe(el);
});

const tradeHeader = document.getElementById('tradeSyllabusHeader');
const tradeNav = document.getElementById('tradeSyllabusNav');
if (tradeHeader && tradeNav) {
  window.addEventListener('scroll', () => {
    if (window.scrollY > 20) {
      tradeHeader.classList.add('shadow-md');
      tradeNav.classList.remove('h-20');
      tradeNav.classList.add('h-16');
    } else {
      tradeHeader.classList.remove('shadow-md');
      tradeNav.classList.add('h-20');
      tradeNav.classList.remove('h-16');
    }
  });
}
