const preference = window.matchMedia('(prefers-reduced-motion: reduce)');
const reveals = document.querySelectorAll<HTMLElement>('[data-reveal], .inner-section > .wrap');
const layers = [...document.querySelectorAll<HTMLElement>('[data-parallax]')].flatMap((element) => {
  const frame = element.parentElement;
  const distance = Number(element.dataset.parallax);
  return frame && Number.isFinite(distance) ? [{ element, frame, distance }] : [];
});
const backgrounds = [...document.querySelectorAll<HTMLElement>('[data-background-parallax]')];
const revealed = new WeakSet<Element>();
const revealThreshold = .1;
let observation: IntersectionObserver | undefined;
let frameId = 0;

function renderParallax() {
  frameId = 0;
  const height = window.innerHeight;
  const positions = layers.map(({ element, frame, distance }) => {
    const bounds = frame.getBoundingClientRect();
    const progress = (height / 2 - bounds.top - bounds.height / 2) / (height / 2 + bounds.height / 2);
    return { element, offset: Math.max(-1, Math.min(1, progress)) * distance };
  });
  positions.forEach(({ element, offset }) => {
    element.style.setProperty('--parallax-offset', offset.toFixed(1) + 'px');
  });
  backgrounds.forEach((element) => {
    const bounds = element.getBoundingClientRect();
    const progress = (height / 2 - bounds.top - bounds.height / 2) / (height / 2 + bounds.height / 2);
    const distance = Number(element.dataset.backgroundParallax);
    if (Number.isFinite(distance)) {
      element.style.setProperty('--background-offset', (Math.max(-1, Math.min(1, progress)) * distance).toFixed(1) + 'px');
    }
  });
}

function queueParallax() {
  if (!frameId) frameId = requestAnimationFrame(renderParallax);
}

function syncMotion() {
  if (preference.matches) {
    observation?.disconnect();
    reveals.forEach((element) => element.classList.remove('is-revealing'));
    removeEventListener('scroll', queueParallax);
    removeEventListener('resize', queueParallax);
    cancelAnimationFrame(frameId);
    frameId = 0;
    layers.forEach(({ element }) => element.style.removeProperty('--parallax-offset'));
    backgrounds.forEach((element) => element.style.removeProperty('--background-offset'));
    return;
  }

  if ('IntersectionObserver' in window) {
    observation ??= new IntersectionObserver((entries) => {
      for (const { target, intersectionRatio } of entries) {
        if (intersectionRatio < revealThreshold || preference.matches) continue;
        revealed.add(target);
        observation?.unobserve(target);
        if (target.contains(document.activeElement)) continue;
        target.classList.add('is-revealing');
      }
    }, { threshold: revealThreshold });
    reveals.forEach((element) => {
      if (!revealed.has(element)) observation?.observe(element);
    });
  }

  if (layers.length || backgrounds.length) {
    addEventListener('scroll', queueParallax, { passive: true });
    addEventListener('resize', queueParallax);
    queueParallax();
  }
}

document.addEventListener('animationend', (event) => {
  if (event.animationName === 'section-reveal' && event.target instanceof Element) {
    event.target.classList.remove('is-revealing');
  }
});

preference.addEventListener('change', syncMotion);
syncMotion();
