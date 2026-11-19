const preference = window.matchMedia('(prefers-reduced-motion: reduce)');
const reveals = document.querySelectorAll<HTMLElement>('[data-reveal], .inner-section > .wrap');
const layers = [...document.querySelectorAll<HTMLElement>('[data-parallax]')].flatMap((element) => {
  const frame = element.parentElement;
  const distance = Number(element.dataset.parallax);
  return frame && Number.isFinite(distance) ? [{ element, frame, distance }] : [];
});
const revealed = new WeakSet<Element>();
const animations = new Set<Animation>();
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
}

function queueParallax() {
  if (!frameId) frameId = requestAnimationFrame(renderParallax);
}

function syncMotion() {
  if (preference.matches) {
    observation?.disconnect();
    animations.forEach((animation) => animation.cancel());
    removeEventListener('scroll', queueParallax);
    removeEventListener('resize', queueParallax);
    cancelAnimationFrame(frameId);
    frameId = 0;
    layers.forEach(({ element }) => element.style.removeProperty('--parallax-offset'));
    return;
  }

  if ('IntersectionObserver' in window && typeof Element.prototype.animate === 'function') {
    observation ??= new IntersectionObserver((entries) => {
      for (const { target, isIntersecting } of entries) {
        if (!isIntersecting || preference.matches) continue;
        revealed.add(target);
        observation?.unobserve(target);
        if (target.contains(document.activeElement)) continue;
        const animation = target.animate(
          [{ opacity: .6, transform: 'translateY(10px)' }, { opacity: 1, transform: 'translateY(0)' }],
          { duration: 420, easing: 'cubic-bezier(.2, .7, .25, 1)' },
        );
        animations.add(animation);
        const forget = () => animations.delete(animation);
        animation.addEventListener('finish', forget);
        animation.addEventListener('cancel', forget);
      }
    }, { threshold: .1 });
    reveals.forEach((element) => {
      if (!revealed.has(element)) observation?.observe(element);
    });
  }

  if (layers.length) {
    addEventListener('scroll', queueParallax, { passive: true });
    addEventListener('resize', queueParallax);
    queueParallax();
  }
}

preference.addEventListener('change', syncMotion);
syncMotion();
