(function () {
  // Grab elements
  const heroBg = document.querySelector('.hero-two-bg');
  const thumbs = Array.from(document.querySelectorAll('.thumb-card'));
  const titleEl = document.getElementById('hero-two-title');
  const descEl  = document.getElementById('hero-two-desc');

  if (!heroBg || thumbs.length === 0) return;

  // Set background + text instantly (no transition)
  function setHeroBackground(url, clickedButton) {

    heroBg.style.backgroundImage = `url("${url}")`;

    // Update text from the card
    if (clickedButton) {
      const newTitle = clickedButton.getAttribute('data-title');
      const newDesc  = clickedButton.getAttribute('data-desc');

      if (titleEl && newTitle !== null) titleEl.textContent = newTitle;
      if (descEl  && newDesc  !== null) descEl.textContent  = newDesc;
    }

    // Active highlight
    thumbs.forEach(t => t.classList.remove('active'));
    if (clickedButton) clickedButton.classList.add('active');
  }

  // Events
  thumbs.forEach(btn => {
    const imgUrl = btn.getAttribute('data-bg');

    btn.addEventListener('click', () => setHeroBackground(imgUrl, btn));

    btn.addEventListener('keydown', (e) => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        setHeroBackground(imgUrl, btn);
      }
    });
  });

  // Preload images
  thumbs.forEach(btn => {
    const u = btn.getAttribute('data-bg');
    const img = new Image();
    img.src = u;
  });

  // Initialize default state
  const initial = document.querySelector('.thumb-card.active') || thumbs[0];
  if (initial) {
    const initUrl = initial.getAttribute('data-bg');
    setHeroBackground(initUrl, initial);
  }

})();


