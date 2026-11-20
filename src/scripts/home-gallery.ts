const gallery = document.querySelector<HTMLElement>('[data-home-gallery]');
const track = gallery?.querySelector<HTMLElement>('.gallery-track');
const dialog = gallery?.querySelector<HTMLDialogElement>('[data-gallery-dialog]');
const dialogImage = dialog?.querySelector<HTMLImageElement>('[data-dialog-image]');
const dialogTitle = dialog?.querySelector('#gallery-photo-title');
const dialogDescription = dialog?.querySelector('#gallery-photo-description');
const closeButton = dialog?.querySelector<HTMLButtonElement>('.gallery-close');

if (gallery && track && dialog && dialogImage && dialogTitle && dialogDescription && closeButton) {
  const links = [...track.querySelectorAll<HTMLAnchorElement>('[data-gallery-photo]')];
  const slides = [...track.querySelectorAll<HTMLElement>('.gallery-figure')];
  const controls = gallery.querySelector<HTMLElement>('.gallery-controls');
  const dots = [...(controls?.querySelectorAll<HTMLButtonElement>('.gallery-dot') ?? [])];
  const playback = controls?.querySelector<HTMLButtonElement>('.gallery-playback');
  const motion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const hover = window.matchMedia('(hover: hover)');
  let visible = false;
  let paused = false;
  let selected = 0;
  let timer = 0;
  let opener: HTMLAnchorElement | undefined;
  let drag: { pointer: number; x: number; left: number; index: number; moved: boolean } | undefined;
  let suppressClick = false;

  const schedule = () => {
    window.clearTimeout(timer);
    if (!visible || paused || motion.matches || slides.length < 2 || document.hidden || dialog.open
      || drag || (hover.matches && track.matches(':hover')) || track.contains(document.activeElement)) return;
    timer = window.setTimeout(() => move((selected + 1) % slides.length), 4600);
  };

  const update = () => {
    selected = slides.reduce((closest, slide, index) => (
      Math.abs(slide.offsetLeft - track.scrollLeft) < Math.abs(slides[closest].offsetLeft - track.scrollLeft)
        ? index : closest
    ), 0);
    dots.forEach((dot, index) => dot.setAttribute('aria-pressed', String(index === selected)));
    schedule();
  };

  const move = (index: number) => {
    const slide = slides[index];
    if (slide) track.scrollTo({ left: slide.offsetLeft, behavior: motion.matches ? 'instant' : 'smooth' });
    schedule();
  };

  track.addEventListener('scroll', update, { passive: true });
  track.addEventListener('pointerenter', schedule);
  track.addEventListener('pointerleave', schedule);
  track.addEventListener('focusin', schedule);
  track.addEventListener('focusout', () => queueMicrotask(schedule));
  track.addEventListener('keydown', (event) => {
    if (slides.length < 2 || (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight')) return;
    const step = event.key === 'ArrowLeft' ? -1 : 1;
    event.preventDefault();
    const index = Math.max(0, Math.min(slides.length - 1, selected + step));
    move(index);
    if (event.target !== track) links[index]?.focus({ preventScroll: true });
  });

  track.addEventListener('pointerdown', (event) => {
    if (event.button !== 0) return;
    window.clearTimeout(timer);
    suppressClick = false;
    if (event.pointerType === 'mouse') {
      drag = { pointer: event.pointerId, x: event.clientX, left: track.scrollLeft, index: selected, moved: false };
    }
  });
  track.addEventListener('pointermove', (event) => {
    if (!drag || drag.pointer !== event.pointerId) return;
    const difference = drag.x - event.clientX;
    if (!drag.moved && Math.abs(difference) < 6) return;
    if (!drag.moved) {
      track.setPointerCapture(event.pointerId);
      track.classList.add('is-dragging');
      drag.moved = true;
    }
    event.preventDefault();
    track.scrollLeft = drag.left + difference;
  });
  const endDrag = (event: PointerEvent) => {
    if (!drag || drag.pointer !== event.pointerId) return;
    const travel = drag.x - event.clientX;
    const next = Math.abs(travel) > 50 ? drag.index + Math.sign(travel) : selected;
    suppressClick = drag.moved;
    drag = undefined;
    track.classList.remove('is-dragging');
    update();
    if (suppressClick) move(Math.max(0, Math.min(slides.length - 1, next)));
  };
  window.addEventListener('pointerup', endDrag);
  window.addEventListener('pointercancel', endDrag);
  track.addEventListener('touchend', schedule, { passive: true });
  track.addEventListener('dragstart', (event) => event.preventDefault());

  links.forEach((link) => link.addEventListener('click', (event) => {
    if (suppressClick && event.detail > 0) {
      event.preventDefault();
      suppressClick = false;
      return;
    }
    if (event.ctrlKey || event.metaKey || event.shiftKey || event.altKey || !dialog.showModal) return;
    event.preventDefault();
    opener = link;
    dialogTitle.textContent = link.dataset.title ?? '';
    dialogDescription.textContent = link.dataset.description ?? '';
    dialogImage.alt = link.querySelector('img')?.alt ?? '';
    dialogImage.src = link.href;
    dialog.showModal();
    schedule();
  }));
  closeButton.addEventListener('click', () => dialog.close());
  dialog.addEventListener('click', (event) => {
    if (event.target === dialog) dialog.close();
  });
  dialog.addEventListener('close', () => {
    opener?.focus({ preventScroll: true });
    schedule();
  });

  if (controls && playback) {
    controls.hidden = false;
    track.dataset.galleryReady = '';
    dots.forEach((dot, index) => dot.addEventListener('click', () => move(index)));
    playback.addEventListener('click', () => {
      paused = !paused;
      playback.textContent = paused ? 'Putar' : 'Jeda';
      schedule();
    });
    const syncMotion = () => {
      playback.hidden = motion.matches || !('IntersectionObserver' in window);
      schedule();
    };
    motion.addEventListener('change', syncMotion);
    document.addEventListener('visibilitychange', schedule);
    if ('IntersectionObserver' in window) {
      const observer = new IntersectionObserver(([entry]) => {
        visible = entry.isIntersecting && entry.intersectionRatio >= .45;
        schedule();
      }, { threshold: .45 });
      observer.observe(track);
    }
    syncMotion();
    update();
  }
}
