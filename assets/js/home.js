window.addEventListener('scroll', () => {
  const header = document.querySelector('.site-top-nav') || document.querySelector('header');
  if (!header) return;
  if (window.scrollY > 50) {
    header.classList.add('shadow-md');
  } else {
    header.classList.remove('shadow-md');
  }
});

document.querySelectorAll('.group').forEach((el) => {
  el.addEventListener('mousedown', () => {
    el.style.transform = 'scale(0.98)';
    el.style.transition = 'transform 0.1s';
  });
  el.addEventListener('mouseup', () => {
    el.style.transform = 'scale(1)';
  });
});
