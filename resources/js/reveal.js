/**
 * Animasi scroll landing page: reveal on scroll, stagger, parallax hero, dan progress bar.
 * Elemen memakai atribut: data-reveal (up|left|right|zoom|pop|fade), data-reveal-delay,
 * data-reveal-stagger (pada induk), data-parallax, data-parallax-max, data-parallax-title.
 */
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function setupReveal() {
    window.__revealReady = true;

    if (prefersReducedMotion) {
        document.documentElement.classList.remove('js');

        return;
    }

    const staggerParents = document.querySelectorAll('[data-reveal-stagger]');

    staggerParents.forEach((parent) => {
        const step = Number(parent.dataset.revealStagger) || 80;
        const type = parent.dataset.revealType || 'up';

        Array.from(parent.children).forEach((child, index) => {
            child.setAttribute('data-reveal', child.dataset.reveal || type);
            child.dataset.revealGrouped = 'true';
            child.style.setProperty('--reveal-delay', `${Math.min(index, 9) * step}ms`);
        });
    });

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) {
                    return;
                }

                const target = entry.target;

                if (target.hasAttribute('data-reveal-stagger')) {
                    Array.from(target.children).forEach((child) => child.classList.add('is-revealed'));
                } else {
                    target.classList.add('is-revealed');
                }

                observer.unobserve(target);
            });
        },
        { threshold: 0.1, rootMargin: '0px 0px -6% 0px' },
    );

    staggerParents.forEach((parent) => observer.observe(parent));

    document.querySelectorAll('[data-reveal]:not([data-reveal-grouped])').forEach((element) => {
        if (element.dataset.revealDelay) {
            element.style.setProperty('--reveal-delay', `${element.dataset.revealDelay}ms`);
        }

        observer.observe(element);
    });
}

function setupScrollEffects() {
    const hero = document.getElementById('beranda');

    if (!hero || prefersReducedMotion) {
        return;
    }

    const progressBar = document.createElement('div');
    progressBar.className = 'scroll-progress';
    progressBar.setAttribute('aria-hidden', 'true');
    document.body.appendChild(progressBar);

    const parallaxItems = Array.from(document.querySelectorAll('[data-parallax]'));
    const titleBlock = document.querySelector('[data-parallax-title]');
    let ticking = false;

    const update = () => {
        ticking = false;

        const scrollY = window.scrollY;
        const scrollable = document.documentElement.scrollHeight - window.innerHeight;
        const heroHeight = hero.offsetHeight || window.innerHeight;

        progressBar.style.transform = `scaleX(${scrollable > 0 ? Math.min(scrollY / scrollable, 1) : 0})`;

        if (scrollY > heroHeight * 1.2) {
            return;
        }

        parallaxItems.forEach((item) => {
            const factor = Number(item.dataset.parallax) || 0;
            const max = Number(item.dataset.parallaxMax) || 40;
            const shift = Math.max(-max, Math.min(max, -scrollY * factor));

            item.style.transform = `translate3d(0, ${shift.toFixed(1)}px, 0)`;
        });

        if (titleBlock) {
            const progress = Math.min(scrollY / (heroHeight * 0.55), 1);

            titleBlock.style.transform = `translate3d(0, ${(scrollY * 0.22).toFixed(1)}px, 0)`;
            titleBlock.style.opacity = String(1 - progress * 0.85);
        }
    };

    window.addEventListener(
        'scroll',
        () => {
            if (!ticking) {
                ticking = true;
                window.requestAnimationFrame(update);
            }
        },
        { passive: true },
    );

    window.addEventListener('resize', update, { passive: true });
    update();
}

function init() {
    setupReveal();
    setupScrollEffects();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

