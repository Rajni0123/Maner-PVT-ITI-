document.querySelectorAll('.doc-check-card').forEach((card) => {
  card.addEventListener('click', () => {
    const icon = card.querySelector('.doc-check-icon');
    if (!icon) return;
    if (icon.textContent.trim() === 'check_circle') {
      icon.textContent = 'verified';
      icon.classList.remove('text-outline-variant');
      icon.classList.add('text-secondary');
      card.classList.add('bg-surface-container-low');
    } else {
      icon.textContent = 'check_circle';
      icon.classList.add('text-outline-variant');
      icon.classList.remove('text-secondary');
      card.classList.remove('bg-surface-container-low');
    }
  });
});
