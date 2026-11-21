const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');

for (const gallery of document.querySelectorAll<HTMLElement>('[data-gallery-carousel]')) {
  const viewport = gallery.querySelector<HTMLElement>('[data-gallery-viewport]');
  const slides = [...gallery.querySelectorAll<HTMLElement>('[data-gallery-slide]')];
  const playback = gallery.querySelector<HTMLButtonElement>('[data-gallery-playback]');
  const pagination = gallery.querySelector<HTMLElement>('[data-gallery-pagination]');
  if (!viewport || !playback || !pagination) continue;

  const dots = [...pagination.querySelectorAll('button')];
  let index = 0;
  let playing = !reducedMotion.matches;
  let inView = false;
  let hovered = false;
  let timer: number | undefined;
  let animation: Animation | undefined;

  const render = (next: number, direction: number) => {
    animation?.cancel();
    const previous = slides[index];
    const active = slides[next];
    const moving = previous !== active && !reducedMotion.matches;

    for (const [position, slide] of slides.entries()) {
      const selected = position === next;
      slide.hidden = !selected && !(moving && slide === previous);
      slide.toggleAttribute('data-active', selected);
      slide.setAttribute('aria-hidden', String(!selected));
      if (selected) dots[position].setAttribute('aria-current', 'true');
      else dots[position].removeAttribute('aria-current');
    }

    if (moving) {
      animation = active.animate(
        [{ transform: 'translateX(' + direction * 100 + '%)' }, { transform: 'translateX(0)' }],
        { duration: 430, easing: 'cubic-bezier(.2,.65,.3,1)' },
      );
      animation.onfinish = () => { previous.hidden = true; };
    }

    index = next;
  };

  const schedule = () => {
    window.clearTimeout(timer);
    if (!playing || !inView || hovered || document.hidden || reducedMotion.matches) return;
    timer = window.setTimeout(() => {
      render((index + 1) % slides.length, 1);
      schedule();
    }, 5500);
  };

  const setPlaying = (value: boolean) => {
    playing = value;
    playback.toggleAttribute('data-paused', !playing);
    playback.setAttribute('aria-label', playing ? 'Jeda putar otomatis' : 'Mulai putar otomatis');
    viewport.setAttribute('aria-live', playing ? 'off' : 'polite');
    schedule();
  };

  render(0, 1);
  gallery.dataset.ready = '';
  pagination.hidden = false;
  playback.hidden = reducedMotion.matches;
  setPlaying(playing);

  const visibility = new IntersectionObserver(([entry]) => {
    inView = entry.isIntersecting;
    schedule();
  }, { threshold: .35 });
  visibility.observe(viewport);

  document.addEventListener('visibilitychange', schedule);
  gallery.addEventListener('pointerenter', (event) => {
    if (event.pointerType !== 'mouse') return;
    hovered = true;
    schedule();
  });
  gallery.addEventListener('pointerleave', () => {
    hovered = false;
    schedule();
  });
  gallery.addEventListener('focusin', (event) => {
    if (event.target instanceof HTMLElement && event.target.matches(':focus-visible')) setPlaying(false);
  });
  reducedMotion.addEventListener('change', () => {
    playback.hidden = reducedMotion.matches;
    if (reducedMotion.matches) {
      render(index, 1);
      setPlaying(false);
    }
  });

  playback.addEventListener('click', () => setPlaying(!playing));
  for (const [position, dot] of dots.entries()) {
    dot.addEventListener('click', () => {
      setPlaying(false);
      render(position, Math.sign(position - index));
    });
  }
}
