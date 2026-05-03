import './bootstrap';

const navToggle = document.querySelector('[data-nav-toggle]');
const nav = document.querySelector('[data-site-nav]');

const closeNav = () => {
    nav?.classList.remove('is-open');
    navToggle?.setAttribute('aria-expanded', 'false');
};

navToggle?.addEventListener('click', () => {
    const isOpen = nav?.classList.toggle('is-open') ?? false;
    navToggle.setAttribute('aria-expanded', String(isOpen));
});

nav?.addEventListener('click', (event) => {
    if (event.target instanceof HTMLElement && event.target.closest('a')) {
        closeNav();
    }
});

document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        closeNav();
        closeLightbox();
    }
});

document.addEventListener('click', (event) => {
    if (! nav?.classList.contains('is-open') || !(event.target instanceof HTMLElement)) {
        return;
    }

    if (! event.target.closest('[data-site-nav]') && ! event.target.closest('[data-nav-toggle]')) {
        closeNav();
    }
});

const revealSelectors = [
    '.home-hero__grid > *',
    '.inner-hero__grid > *',
    '.section-kicker',
    '.section-title',
    '.section-lead',
    '.highlight-card',
    '.program-roadmap__item',
    '.learning-mode',
    '.feature-row',
    '.method-card',
    '.package-card',
    '.benefit-card',
    '.training-topics-panel',
    '.training-schedule-panel',
    '.training-agenda-card',
    '.training-empty-card',
    '.agenda-hero__grid > *',
    '.agenda-featured',
    '.agenda-card',
    '.agenda-empty',
    '.agenda-detail-hero__grid > *',
    '.gallery-card',
    '.story-text',
    '.story-image-stack',
    '.mission-item',
    '.map-panel',
    '.address-card',
    '.contact-card',
    '.lead-form',
    '.step-card',
    '.faq-item',
    '.cta-card',
];

const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

if (! prefersReducedMotion && 'IntersectionObserver' in window) {
    const revealItems = Array.from(document.querySelectorAll(revealSelectors.join(',')));

    revealItems.forEach((item, index) => {
        item.classList.add('reveal-item');
        item.style.setProperty('--reveal-delay', `${Math.min(index % 6, 5) * 45}ms`);
    });

    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (! entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.12,
    });

    revealItems.forEach((item) => revealObserver.observe(item));
}

const filterBar = document.querySelector('[data-gallery-filter]');
const galleryGrid = document.querySelector('[data-gallery-grid]');

filterBar?.addEventListener('click', (event) => {
    const button = event.target instanceof HTMLElement ? event.target.closest('button') : null;

    if (! button || ! galleryGrid) {
        return;
    }

    const filter = button.dataset.filter;

    filterBar.querySelectorAll('button').forEach((item) => item.classList.remove('is-active'));
    button.classList.add('is-active');

    galleryGrid.querySelectorAll('[data-category]').forEach((item) => {
        const shouldShow = filter === 'all' || item.getAttribute('data-category') === filter;

        item.classList.toggle('hidden', ! shouldShow);
    });
});

const lightbox = document.querySelector('[data-lightbox]');
const lightboxImage = document.querySelector('[data-lightbox-image]');
const lightboxCaption = document.querySelector('[data-lightbox-caption]');
const lightboxClose = document.querySelector('[data-lightbox-close]');

function openLightbox(src, title) {
    if (! lightbox || ! lightboxImage) {
        return;
    }

    lightboxImage.setAttribute('src', src);
    lightboxImage.setAttribute('alt', title);
    lightboxCaption && (lightboxCaption.textContent = title);
    lightbox.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    lightboxClose?.focus();
}

function closeLightbox() {
    if (! lightbox || ! lightboxImage) {
        return;
    }

    lightbox.classList.remove('is-open');
    lightboxImage.setAttribute('src', '');
    document.body.style.overflow = '';
}

galleryGrid?.addEventListener('click', (event) => {
    const button = event.target instanceof HTMLElement ? event.target.closest('[data-lightbox-src]') : null;

    if (! button) {
        return;
    }

    openLightbox(button.dataset.lightboxSrc ?? '', button.dataset.lightboxTitle ?? 'Foto galeri');
});

lightboxClose?.addEventListener('click', closeLightbox);

lightbox?.addEventListener('click', (event) => {
    if (event.target === lightbox) {
        closeLightbox();
    }
});
