import L from 'leaflet';

// Modul interaksi khusus halaman Landing Page (welcome.blade.php)
export function initLandingPage() {
    // ==========================================
    // 1. GLOBAL & LANDING PAGE INTERACTION
    // ==========================================

    const navbar = document.getElementById('main-navbar');
    const navToggle = document.getElementById('nav-toggle');
    const navMenu = document.getElementById('nav-menu');
    navToggle?.addEventListener('click', () => {
        const isOpen = navToggle.getAttribute('aria-expanded') === 'true';
        navToggle.setAttribute('aria-expanded', String(!isOpen));
        navMenu.classList.toggle('hidden', isOpen);
    });

    document.querySelectorAll('[data-mobile-nav-link]').forEach((link) => {
        link.addEventListener('click', () => {
            navToggle?.setAttribute('aria-expanded', 'false');
            navMenu?.classList.add('hidden');
        });
    });

    if (navbar) {
        const navBrand = navbar.querySelector('.nav-brand');
        const navActions = navbar.querySelector('.nav-actions');
        const navLinksList = document.getElementById('nav-links-list');
        const hoverPill = document.getElementById('nav-hover-pill');
        const navLinks = Array.from(navbar.querySelectorAll('[data-nav-link]'));
        const mobileDockBar = document.getElementById('mobile-dock-bar');
        const mobileDockLinks = Array.from(document.querySelectorAll('[data-mobile-dock-link]'));
        let hoveredLink = null;
        let activeLink = null;
        let activeMobileDockLink = mobileDockLinks[0] || null;
        let dockClickLockUntil = 0;
        let manualExpanded = false;
        let expandedAtScrollY = 0;
        let isScrolled = false;
        let navTicking = false;

        // Cache sections to prevent layout thrashing (getBoundingClientRect) on every scroll frame
        const desktopSections = navLinks
            .map((link) => {
                const href = link.getAttribute('href');
                if (!href || !href.startsWith('#')) return null;
                const section = document.querySelector(href);
                return section ? { link, section, top: 0 } : null;
            })
            .filter(Boolean);

        const mobileSections = mobileDockLinks
            .map((dockLink) => {
                const href = dockLink.getAttribute('data-mobile-dock-link');
                if (!href || !href.startsWith('#')) return null;
                const section = document.querySelector(href);
                return section ? { dockLink, section, top: 0 } : null;
            })
            .filter(Boolean);

        const recalculateSectionOffsets = () => {
            const currentScroll = window.scrollY;
            desktopSections.forEach((item) => {
                item.top = item.section.getBoundingClientRect().top + currentScroll;
            });
            mobileSections.forEach((item) => {
                item.top = item.section.getBoundingClientRect().top + currentScroll;
            });
        };

        const positionMobileDockNotch = (target) => {
            if (!mobileDockBar || !target || mobileDockBar.offsetWidth === 0) return;
            const centerX = target.offsetLeft + target.offsetWidth / 2;
            mobileDockBar.style.setProperty('--dock-x', `${centerX}px`);
            if (!mobileDockBar.classList.contains('is-ready')) {
                window.requestAnimationFrame(() => mobileDockBar.classList.add('is-ready'));
            }
        };

        const activateMobileDock = (target) => {
            if (!target) return;
            activeMobileDockLink = target;
            mobileDockLinks.forEach((l) => l.classList.toggle('is-active', l === target));
            positionMobileDockNotch(target);
        };

        mobileDockLinks.forEach((dockLink) => {
            dockLink.addEventListener('click', (event) => {
                dockClickLockUntil = performance.now() + 720;
                activateMobileDock(dockLink);

                const routeUrl = dockLink.getAttribute('data-mobile-dock-route');
                if (routeUrl && !event.metaKey && !event.ctrlKey && !event.shiftKey) {
                    event.preventDefault();
                    const reduceMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
                    if (reduceMotion) {
                        window.location.assign(routeUrl);
                    } else {
                        setTimeout(() => window.location.assign(routeUrl), 240);
                    }
                }
            });
        });

        const moveHoverPill = (target) => {
            if (!hoverPill || !navLinksList) return;
            if (!target || navbar.classList.contains('is-scrolled')) {
                hoverPill.style.opacity = '0';
                return;
            }
            hoverPill.style.width = `${target.offsetWidth}px`;
            hoverPill.style.transform = `translate3d(${target.offsetLeft}px, -50%, 0)`;
            hoverPill.style.opacity = '1';
        };

        const syncScrollSpy = () => {
            const isDesktop = window.innerWidth >= 768;
            const scrollRef = window.scrollY + 180;

            if (isDesktop && desktopSections.length) {
                let matched = null;
                let matchedTop = -Infinity;

                for (let i = 0; i < desktopSections.length; i++) {
                    const item = desktopSections[i];
                    if (scrollRef >= item.top && item.top > matchedTop) {
                        matchedTop = item.top;
                        matched = item.link;
                    }
                }

                if (matched !== activeLink) {
                    activeLink = matched;
                    navLinks.forEach((link) => link.classList.toggle('is-active', link === activeLink));
                    if (!hoveredLink && !navbar.classList.contains('is-scrolled')) {
                        moveHoverPill(activeLink);
                    }
                }
            } else if (!isDesktop && mobileSections.length && performance.now() >= dockClickLockUntil) {
                let matchedDock = mobileSections[0]?.dockLink || mobileDockLinks[0];
                let dockMatchedTop = -Infinity;

                for (let i = 0; i < mobileSections.length; i++) {
                    const item = mobileSections[i];
                    if (scrollRef >= item.top && item.top > dockMatchedTop) {
                        dockMatchedTop = item.top;
                        matchedDock = item.dockLink;
                    }
                }

                if (matchedDock && matchedDock !== activeMobileDockLink) {
                    activateMobileDock(matchedDock);
                }
            }
        };

        const updateNavbarState = () => {
            navTicking = false;
            const scrollY = window.scrollY;

            if (scrollY <= 80) {
                manualExpanded = false;
            } else if (manualExpanded && Math.abs(scrollY - expandedAtScrollY) > 60) {
                manualExpanded = false;
            }

            const scrolled = scrollY > 80 && !manualExpanded;
            if (scrolled !== isScrolled) {
                isScrolled = scrolled;
                navbar.classList.toggle('is-scrolled', isScrolled);
            }

            if (scrolled && navToggle?.getAttribute('aria-expanded') === 'true') {
                navToggle.setAttribute('aria-expanded', 'false');
                navMenu?.classList.add('hidden');
            }

            syncScrollSpy();
        };

        navBrand?.addEventListener('click', (event) => {
            if (navbar.classList.contains('is-scrolled')) {
                event.preventDefault();
                manualExpanded = true;
                expandedAtScrollY = window.scrollY;
                navbar.classList.remove('is-scrolled');
                isScrolled = false;
                window.requestAnimationFrame(() => moveHoverPill(hoveredLink || activeLink));
            } else {
                manualExpanded = false;
            }
        });

        navActions?.addEventListener('transitionend', (event) => {
            if (event.propertyName === 'max-width' && !navbar.classList.contains('is-scrolled')) {
                moveHoverPill(hoveredLink || activeLink);
            }
        });

        document.addEventListener('click', (event) => {
            if (manualExpanded && !navbar.contains(event.target)) {
                manualExpanded = false;
                updateNavbarState();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && manualExpanded) {
                manualExpanded = false;
                updateNavbarState();
            }
        });

        navLinks.forEach((link) => {
            link.addEventListener('mouseenter', () => {
                hoveredLink = link;
                moveHoverPill(link);
            });
            link.addEventListener('focus', () => {
                hoveredLink = link;
                moveHoverPill(link);
            });
            link.addEventListener('click', () => {
                manualExpanded = false;
                activeLink = link;
                navLinks.forEach((l) => l.classList.toggle('is-active', l === link));
                moveHoverPill(link);
            });
        });

        navLinksList?.addEventListener('mouseleave', () => {
            hoveredLink = null;
            moveHoverPill(activeLink);
        });

        window.addEventListener('scroll', () => {
            if (!navTicking) {
                navTicking = true;
                window.requestAnimationFrame(updateNavbarState);
            }
        }, { passive: true });

        window.addEventListener('resize', () => {
            recalculateSectionOffsets();
            if (window.innerWidth >= 768) {
                moveHoverPill(hoveredLink || activeLink);
            } else {
                positionMobileDockNotch(activeMobileDockLink);
            }
        }, { passive: true });

        // Initial measurement
        recalculateSectionOffsets();
        updateNavbarState();
        window.addEventListener('load', () => {
            recalculateSectionOffsets();
            syncScrollSpy();
        });
    }

    document.querySelectorAll('[data-marquee]').forEach((marquee) => {
        const track = marquee.querySelector('[data-marquee-track]');
        const firstGroup = track?.querySelector('.marquee-group');
        if (!track || !firstGroup) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const autoSpeed = reduceMotion ? 0 : 42; // px per detik ke kiri
        let groupWidth = firstGroup.offsetWidth || 1;
        let offset = 0;
        let isHovered = false;
        let isFocused = false;
        let isDragging = false;
        let hasCaptured = false;
        let didDrag = false;
        let activePointerId = null;
        let startX = 0;
        let startOffset = 0;
        let lastX = 0;
        let lastMoveTime = 0;
        let velocity = 0;
        let lastFrameTime = performance.now();

        const normalizeOffset = () => {
            if (groupWidth <= 0) return;
            while (offset <= -groupWidth) offset += groupWidth;
            while (offset > 0) offset -= groupWidth;
        };

        const render = () => {
            normalizeOffset();
            track.style.transform = `translate3d(${offset.toFixed(2)}px, 0, 0)`;
        };

        const syncWidth = () => {
            groupWidth = firstGroup.offsetWidth || 1;
            render();
        };

        if (window.ResizeObserver) {
            new ResizeObserver(syncWidth).observe(firstGroup);
        } else {
            window.addEventListener('resize', syncWidth, { passive: true });
        }

        marquee.addEventListener('pointerenter', (event) => {
            if (event.pointerType === 'mouse') {
                isHovered = true;
            }
        });

        marquee.addEventListener('pointerleave', (event) => {
            if (event.pointerType === 'mouse') {
                isHovered = false;
            }
        });

        marquee.addEventListener('focusin', () => {
            isFocused = true;
        });

        marquee.addEventListener('focusout', (event) => {
            if (!marquee.contains(event.relatedTarget)) {
                isFocused = false;
            }
        });

        marquee.addEventListener('pointerdown', (event) => {
            if (event.pointerType === 'mouse' && event.button !== 0) return;
            isDragging = true;
            hasCaptured = false;
            didDrag = false;
            activePointerId = event.pointerId;
            startX = event.clientX;
            lastX = event.clientX;
            startOffset = offset;
            velocity = 0;
            lastMoveTime = performance.now();
        });

        marquee.addEventListener('pointermove', (event) => {
            if (!isDragging || event.pointerId !== activePointerId) return;
            const dx = event.clientX - startX;

            if (!hasCaptured && Math.abs(dx) > 6) {
                hasCaptured = true;
                didDrag = true;
                try {
                    marquee.setPointerCapture(event.pointerId);
                } catch {
                    // Abaikan jika browser menolak pointer capture
                }
            }

            if (hasCaptured) {
                const now = performance.now();
                const dt = Math.max(now - lastMoveTime, 1);
                velocity = ((event.clientX - lastX) / dt) * 1000;
                lastX = event.clientX;
                lastMoveTime = now;
                offset = startOffset + dx;
                render();
            }
        });

        const endDrag = (event) => {
            if (!isDragging || event.pointerId !== activePointerId) return;
            isDragging = false;
            if (hasCaptured) {
                try {
                    marquee.releasePointerCapture(event.pointerId);
                } catch {
                    // Abaikan jika sudah dilepas
                }
            }
            activePointerId = null;
            if (performance.now() - lastMoveTime > 90) {
                velocity = 0;
            }
        };

        marquee.addEventListener('pointerup', endDrag);
        marquee.addEventListener('pointercancel', endDrag);
        marquee.addEventListener('dragstart', (event) => event.preventDefault());

        marquee.addEventListener('click', (event) => {
            if (didDrag) {
                event.preventDefault();
                event.stopPropagation();
                didDrag = false;
            }
        }, true);

        marquee.addEventListener('wheel', (event) => {
            if (Math.abs(event.deltaX) > Math.abs(event.deltaY) && Math.abs(event.deltaX) > 0) {
                event.preventDefault();
                offset -= event.deltaX;
                velocity = 0;
                render();
            }
        }, { passive: false });

        const tick = (now) => {
            const dt = Math.min((now - lastFrameTime) / 1000, 0.05);
            lastFrameTime = now;

            if (!isDragging) {
                if (Math.abs(velocity) > 8) {
                    offset += velocity * dt;
                    velocity *= Math.pow(0.06, dt);
                    render();
                } else {
                    velocity = 0;
                    if (!isHovered && !isFocused && autoSpeed > 0) {
                        offset -= autoSpeed * dt;
                        render();
                    }
                }
            }

            window.requestAnimationFrame(tick);
        };

        window.requestAnimationFrame(tick);
    });

    document.querySelectorAll('[data-cursor-particles]').forEach((canvas) => {
        const container = canvas.closest('section') || canvas.parentElement;
        const ctx = canvas.getContext('2d');
        if (!container || !ctx) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        const isLightTheme = canvas.dataset.particleTheme === 'light';
        const palette = isLightTheme
            ? [
                [0, 121, 121],   // primary teal (#007979)
                [46, 158, 107],  // sage green (#2e9e6b)
                [11, 92, 92],    // deep teal (#0b5c5c)
                [227, 116, 52],  // accent orange (#e37434)
                [194, 86, 26],   // strong orange (#c2561a)
            ]
            : [
                [142, 243, 230], // mint aqua
                [92, 193, 209],  // bright aqua
                [255, 255, 255], // crisp white
                [255, 178, 107], // warm gold-orange
                [227, 116, 52],  // PuskesMap accent orange (#e37434)
            ];

        let width = 0;
        let height = 0;
        let dpr = 1;
        let particles = [];
        let isPointerActive = false;
        let isInViewport = true;
        let targetX = 0;
        let targetY = 0;
        let cursorX = 0;
        let cursorY = 0;
        let rafId = null;

        const sampleColor = (angleNorm) => {
            const scaled = ((angleNorm % 1) + 1) % 1 * palette.length;
            const idx = Math.floor(scaled);
            const nextIdx = (idx + 1) % palette.length;
            const frac = scaled - idx;
            const c1 = palette[idx];
            const c2 = palette[nextIdx];
            const r = Math.round(c1[0] + (c2[0] - c1[0]) * frac);
            const g = Math.round(c1[1] + (c2[1] - c1[1]) * frac);
            const b = Math.round(c1[2] + (c2[2] - c1[2]) * frac);
            return `${r}, ${g}, ${b}`;
        };

        const buildField = () => {
            const rect = container.getBoundingClientRect();
            const nextW = Math.max(1, Math.round(rect.width));
            const nextH = Math.max(1, Math.round(rect.height));
            const nextDpr = Math.min(window.devicePixelRatio || 1, 2);
            if (nextW === width && nextH === height && nextDpr === dpr && particles.length > 0) {
                return;
            }
            width = nextW;
            height = nextH;
            dpr = nextDpr;

            if (cursorX === 0 && cursorY === 0) {
                cursorX = width * 0.5;
                cursorY = height * 0.5;
                targetX = cursorX;
                targetY = cursorY;
            }

            canvas.width = Math.round(width * dpr);
            canvas.height = Math.round(height * dpr);
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);

            // Jarak antar titik lebih renggang / longgar menyerupai konstelasi Google Antigravity
            const spacing = width < 640 ? 34 : 42;
            const cols = Math.ceil(width / spacing) + 1;
            const rows = Math.ceil(height / spacing) + 1;
            const nextParticles = [];

            for (let r = 0; r < rows; r++) {
                for (let c = 0; c < cols; c++) {
                    const seed = Math.sin((c + 1) * 12.9898 + (r + 1) * 78.233) * 43758.5453;
                    const randA = seed - Math.floor(seed);
                    const randB = (seed * 1.37) - Math.floor(seed * 1.37);
                    const randC = (seed * 2.13) - Math.floor(seed * 2.13);
                    const hx = (c + 0.1 + randA * 0.8) * spacing;
                    const hy = (r + 0.1 + randB * 0.8) * spacing;

                    nextParticles.push({
                        hx,
                        hy,
                        x: hx,
                        y: hy,
                        vx: 0,
                        vy: 0,
                        size: 1.3 + randC * 1.3,
                        phase: randB * Math.PI * 2,
                        speed: 0.65 + randC * 0.7,
                    });
                }
            }

            particles = nextParticles;
            startLoop();
        };

        const startLoop = () => {
            if (rafId !== null || !isInViewport) return;
            rafId = window.requestAnimationFrame(renderFrame);
        };

        const renderFrame = (now) => {
            rafId = null;

            const tSec = now * 0.001;
            if (!isPointerActive) {
                // Gerakan acak lembut otomatis sebelum / sesudah kursor masuk
                targetX = width * (0.5 + 0.27 * Math.sin(tSec * 0.36) + 0.08 * Math.cos(tSec * 0.93));
                targetY = height * (0.5 + 0.22 * Math.cos(tSec * 0.44) + 0.07 * Math.sin(tSec * 0.81));
            }

            cursorX += (targetX - cursorX) * (isPointerActive ? 0.14 : 0.05);
            cursorY += (targetY - cursorY) * (isPointerActive ? 0.14 : 0.05);

            ctx.clearRect(0, 0, width, height);

            const outerRadius = Math.min(540, Math.max(260, width * 0.44));
            const innerClear = Math.min(130, outerRadius * 0.25);

            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                const dxHome = cursorX - p.hx;
                const dyHome = cursorY - p.hy;
                const hDist = Math.hypot(dxHome, dyHome);

                const inOuter = hDist < outerRadius;
                const outerT = inOuter ? (1 - hDist / outerRadius) : 0;
                const innerT = hDist < innerClear ? (hDist / innerClear) : 1;
                const ringStrength = outerT > 0 ? Math.pow(outerT, 0.95) * Math.pow(innerT, 0.75) : 0;

                // Drift acak halus untuk semua partikel agar selalu hidup
                const driftX = reduceMotion ? 0 : Math.sin(tSec * 0.9 * p.speed + p.phase) * 5.5;
                const driftY = reduceMotion ? 0 : Math.cos(tSec * 0.75 * p.speed + p.phase * 1.4) * 5.5;

                if (!reduceMotion && ringStrength > 0.01) {
                    const dist = Math.max(hDist, 1);
                    const nx = dxHome / dist;
                    const ny = dyHome / dist;
                    const txUnit = -ny;
                    const tyUnit = nx;

                    const pull = ringStrength * 20;
                    const swirl = ringStrength * 8;
                    const goalX = p.hx + driftX + nx * pull + txUnit * swirl;
                    const goalY = p.hy + driftY + ny * pull + tyUnit * swirl;

                    p.vx = (p.vx + (goalX - p.x) * 0.12) * 0.78;
                    p.vy = (p.vy + (goalY - p.y) * 0.12) * 0.78;
                    p.x += p.vx;
                    p.y += p.vy;
                } else {
                    const goalX = p.hx + driftX;
                    const goalY = p.hy + driftY;
                    p.vx = (p.vx + (goalX - p.x) * 0.08) * 0.80;
                    p.vy = (p.vy + (goalY - p.y) * 0.08) * 0.80;
                    p.x += p.vx;
                    p.y += p.vy;
                }

                const dx = cursorX - p.x;
                const dy = cursorY - p.y;
                const angle = Math.atan2(dy, dx);
                const arcNorm = (angle + Math.PI) / (Math.PI * 2) + now * 0.00003;
                const rgb = sampleColor(arcNorm);

                // Partikel di dalam cincin tampil lebih tegas & memanjang; di luar cincin tetap muncul sebagai titik acak samar
                const alpha = ringStrength > 0.02
                    ? Math.min(0.88, 0.14 + ringStrength * 0.78)
                    : 0.15;
                const dashLen = reduceMotion ? 0.4 : (ringStrength > 0.02 ? 1.2 + ringStrength * 5.2 : 0.4);
                const halfLen = dashLen * 0.5;
                const cos = Math.cos(angle);
                const sin = Math.sin(angle);

                ctx.strokeStyle = `rgba(${rgb}, ${alpha.toFixed(3)})`;
                ctx.lineWidth = p.size + ringStrength * 0.9;
                ctx.lineCap = 'round';
                ctx.beginPath();
                ctx.moveTo(p.x - cos * halfLen, p.y - sin * halfLen);
                ctx.lineTo(p.x + cos * (halfLen + 0.35), p.y + sin * (halfLen + 0.35));
                ctx.stroke();
            }

            if (!reduceMotion && isInViewport) {
                startLoop();
            }
        };

        const updatePointer = (event) => {
            if (event.pointerType && event.pointerType !== 'mouse' && event.pointerType !== 'pen') {
                return;
            }
            if (event.target.closest('[data-particle-exclude]')) {
                isPointerActive = false;
                startLoop();
                return;
            }
            const rect = container.getBoundingClientRect();
            targetX = event.clientX - rect.left;
            targetY = event.clientY - rect.top;
            isPointerActive = true;
            startLoop();
        };

        container.addEventListener('pointerenter', updatePointer, { passive: true });
        container.addEventListener('pointermove', updatePointer, { passive: true });
        container.addEventListener('pointerleave', () => {
            isPointerActive = false;
            startLoop();
        }, { passive: true });

        if (window.IntersectionObserver) {
            new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    isInViewport = entry.isIntersecting;
                    if (isInViewport) {
                        startLoop();
                    }
                });
            }, { threshold: 0.01 }).observe(container);
        }

        if (window.ResizeObserver) {
            new ResizeObserver(buildField).observe(container);
        } else {
            window.addEventListener('resize', buildField, { passive: true });
        }

        buildField();
    });

    document.querySelectorAll('[data-scroll-target]').forEach((button) => {
        button.addEventListener('click', () => {
            const target = document.querySelector(button.dataset.scrollTarget);
            const step = (target?.firstElementChild?.offsetWidth ?? 200) + 28;
            target?.scrollBy({ left: Number(button.dataset.scrollDir) * step, behavior: 'smooth' });
        });
    });

    const recTrack = document.getElementById('rec-track');
    const recCounter = document.getElementById('rec-counter');
    if (recTrack && recCounter) {
        const total = recTrack.children.length;
        recTrack.addEventListener('scroll', () => {
            const step = recTrack.firstElementChild.offsetWidth + 24;
            const index = Math.min(total, Math.round(recTrack.scrollLeft / step) + 1);
            recCounter.textContent = `${index} / ${total}`;
        }, { passive: true });
    }

    const districtButtons = document.querySelectorAll('[data-district-btn]');
    const cards = document.querySelectorAll('[data-district-card]');

    const selectDistrict = (districtName) => {
        districtButtons.forEach((b) => {
            const match = b.dataset.district.toLowerCase() === districtName.toLowerCase();
            b.setAttribute('aria-selected', String(match));
            if (match) {
                b.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
            }
        });
        cards.forEach((card) => {
            const cardDist = card.dataset.districtCard;
            const isAll = districtName === 'Semua';
            card.classList.toggle('hidden', !isAll && cardDist.toLowerCase() !== districtName.toLowerCase());
        });
        document.getElementById('card-track')?.scrollTo({ left: 0, behavior: 'smooth' });
    };

    districtButtons.forEach((button) => {
        button.addEventListener('click', () => selectDistrict(button.dataset.district));
    });

    document.querySelectorAll('[data-mode-btn]').forEach((button) => {
        button.addEventListener('click', () => {
            document.querySelectorAll('[data-mode-btn]').forEach((b) => b.setAttribute('aria-pressed', String(b === button)));
        });
    });

    // Peta Jalan Interaktif pada Section "Sekilas di Peta" di Landing Page
    const landingMapEl = document.getElementById('landing-leaflet-map');
    if (landingMapEl) {
        initLandingPreviewMap();
    }

    // Peta Vektor Ilustrasi
    document.querySelectorAll('[data-bogor-map]').forEach((map) => {
        const shapes = map.querySelectorAll('[data-district-shape]');
        const popups = map.querySelectorAll('[data-district-popup]');

        const closeAll = () => {
            popups.forEach((popup) => popup.classList.add('hidden'));
            shapes.forEach((shape) => shape.classList.remove('is-active'));
        };

        const open = (shape) => {
            closeAll();
            const popup = map.querySelector(`[data-district-popup="${shape.dataset.districtShape}"]`);
            if (!popup) return;

            shape.classList.add('is-active');
            popup.classList.remove('hidden');

            if (window.innerWidth < 640) {
                popup.style.left = '50%';
                popup.style.top = '50%';
                popup.style.transform = 'translate(-50%, -50%)';
                return;
            }

            const posX = parseFloat(popup.dataset.x) || 50;
            const posY = parseFloat(popup.dataset.y) || 50;
            popup.style.left = `${posX}%`;
            popup.style.top = `${posY}%`;
            popup.style.transform = 'translate(-50%, 16px)';

            const rect = popup.getBoundingClientRect();
            const parentRect = map.getBoundingClientRect();
            let shiftX = 0;
            let shiftY = 16;

            if (rect.right > parentRect.right) shiftX = parentRect.right - rect.right - 8;
            if (rect.left < parentRect.left) shiftX = parentRect.left - rect.left + 8;
            if (rect.bottom > parentRect.bottom) shiftY = -rect.height - 16;

            popup.style.transform = `translate(calc(-50% + ${shiftX}px), ${shiftY}px)`;
        };

        shapes.forEach((shape) => {
            shape.addEventListener('click', (event) => {
                event.stopPropagation();
                open(shape);
            });
            shape.addEventListener('keydown', (event) => {
                if (event.key === 'Enter' || event.key === ' ') {
                    event.preventDefault();
                    open(shape);
                }
            });
        });

        map.querySelectorAll('[data-select-district]').forEach((button) => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const district = button.dataset.selectDistrict;
                closeAll();
                selectDistrict(district);
                const cariSection = document.getElementById('cari');
                if (cariSection) cariSection.scrollIntoView({ behavior: 'smooth' });
            });
        });

        map.querySelectorAll('.puskesmas-pin').forEach((pin) => {
            pin.addEventListener('click', (e) => {
                e.stopPropagation();
                const nameEl = document.getElementById('info-card-name');
                const distEl = document.getElementById('info-card-distance');
                if (nameEl && pin.dataset.pinName) nameEl.textContent = pin.dataset.pinName;
                if (distEl && pin.dataset.pinDistance) distEl.textContent = `Kec. ${pin.dataset.pinDistrict} · Jarak ${pin.dataset.pinDistance}`;

                if (pin.dataset.pinDistrict) {
                    const slug = pin.dataset.pinDistrict.toLowerCase().replace(/\s+/g, '-');
                    const shape = map.querySelector(`[data-district-shape="${slug}"]`);
                    if (shape) open(shape);
                }
            });
        });

        map.querySelectorAll('[data-popup-close]').forEach((button) => button.addEventListener('click', closeAll));
        popups.forEach((popup) => popup.addEventListener('click', (event) => event.stopPropagation()));
        document.addEventListener('click', closeAll);
        document.addEventListener('keydown', (event) => event.key === 'Escape' && closeAll());
    });

}

/**
 * Inisialisasi Peta Jalan Interaktif (Real Street Map) pada Section "Sekilas di Peta" Landing Page
 */
function initLandingPreviewMap() {
    const BOGOR_CENTER = [-6.5885, 106.7980];
    const map = L.map('landing-leaflet-map', {
        zoomControl: false,
        attributionControl: false,
        scrollWheelZoom: false,
    }).setView(BOGOR_CENTER, 13);

    L.control.attribution({ position: 'bottomright' })
        .addAttribution('&copy; OpenStreetMap &bull; PuskesMap Kota Bogor')
        .addTo(map);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors &bull; PuskesMap',
    }).addTo(map);

    map.whenReady(() => {
        map.invalidateSize();
        setTimeout(() => map.invalidateSize(), 300);
    });

    // Lingkaran Radius Geodetik 3 km
    const radiusCircle = L.circle(BOGOR_CENTER, {
        radius: 3000,
        color: '#007979',
        dashArray: '8, 8',
        weight: 2,
        fillColor: '#9ce1b4',
        fillOpacity: 0.22,
    }).addTo(map);

    const previewItems = [
        { name: 'Puskesmas Tanah Sareal', district: 'Tanah Sareal', lat: -6.5662, lng: 106.7955, distance: '1,1 km', igd: true, inap: true, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Sempur', district: 'Bogor Tengah', lat: -6.5918, lng: 106.8016, distance: '1,2 km', igd: false, inap: false, nakes: '89%', crowd: 'Sepi' },
        { name: 'Puskesmas Kedung Badak', district: 'Tanah Sareal', lat: -6.5574, lng: 106.8012, distance: '1,5 km', igd: true, inap: false, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Mekarwangi', district: 'Tanah Sareal', lat: -6.5495, lng: 106.7810, distance: '2,0 km', igd: true, inap: true, nakes: '100%', crowd: 'Ramai' },
        { name: 'Puskesmas Pasir Mulya', district: 'Bogor Barat', lat: -6.5988, lng: 106.7745, distance: '2,3 km', igd: true, inap: true, nakes: '100%', crowd: 'Sepi' },
        { name: 'Puskesmas Semplak', district: 'Bogor Barat', lat: -6.5640, lng: 106.7620, distance: '2,5 km', igd: true, inap: false, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Bogor Tengah', district: 'Bogor Tengah', lat: -6.5975, lng: 106.7942, distance: '1,4 km', igd: true, inap: false, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Bogor Timur', district: 'Bogor Timur', lat: -6.6095, lng: 106.8148, distance: '2,4 km', igd: true, inap: true, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Bogor Utara', district: 'Bogor Utara', lat: -6.5762, lng: 106.8165, distance: '2,6 km', igd: true, inap: true, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Tegal Gundil', district: 'Bogor Utara', lat: -6.5842, lng: 106.8130, distance: '2,1 km', igd: false, inap: false, nakes: '100%', crowd: 'Sepi' },
        { name: 'Puskesmas Bogor Selatan', district: 'Bogor Selatan', lat: -6.6195, lng: 106.7988, distance: '2,8 km', igd: true, inap: true, nakes: '100%', crowd: 'Sedang' },
        { name: 'Puskesmas Cipaku', district: 'Bogor Selatan', lat: -6.6335, lng: 106.8110, distance: '3,2 km', igd: true, inap: false, nakes: '89%', crowd: 'Sedang' },
    ];

    const makeLandingPin = (isActive = false, isIgd = true, animateEnter = false) => {
        const bg = isActive ? '#e37434' : (isIgd ? '#0b5c5c' : '#007979');
        const halo = isActive ? 'rgba(227,116,52,0.28)' : 'rgba(0,121,121,0.20)';
        const beacon = isActive
            ? '<span class="landing-pin-beacon absolute inset-0 rounded-full" style="background:rgba(227,116,52,0.36);"></span>'
            : '';
        const enterCls = animateEnter ? ' landing-pin-enter' : '';
        return L.divIcon({
            className: 'landing-street-pin',
            html: `
                <div class="relative flex items-center justify-center size-10 transition-transform duration-200 hover:scale-125${enterCls}" style="transform: translate(-50%, -50%);">
                    ${beacon}
                    <span class="absolute inset-0 rounded-full" style="background:${halo};"></span>
                    <span class="relative flex size-6 items-center justify-center rounded-full border-2 border-white shadow-md text-white font-extrabold text-xs" style="background:${bg};">
                        +
                    </span>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -16],
        });
    };

    const updateLandingCard = (item, animate = true) => {
        const bodyEl = document.getElementById('map-info-card-body');
        const nameEl = document.getElementById('info-card-name');
        const distEl = document.getElementById('info-card-distance');
        const scoreEl = document.getElementById('info-card-score');
        const tagsEl = document.getElementById('info-card-tags');
        if (nameEl) nameEl.textContent = item.name;
        if (distEl) distEl.textContent = `Kec. ${item.district} · Jarak ${item.distance}`;
        if (scoreEl) scoreEl.innerHTML = `<span class="text-primary inline-flex items-center"><svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></span> <span>Nakes ${item.nakes}</span>`;
        if (tagsEl) {
            tagsEl.innerHTML = `
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">${item.distance}</span>
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">Buka</span>
                ${item.igd ? '<span class="inline-block rounded-full bg-accent/15 px-2.5 py-0.5 text-[11px] font-semibold text-accent-strong">IGD 24 jam</span>' : ''}
                ${item.inap ? '<span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">Rawat inap</span>' : ''}
            `;
        }
        if (animate && bodyEl) {
            bodyEl.classList.remove('landing-card-swap');
            void bodyEl.offsetWidth;
            bodyEl.classList.add('landing-card-swap');
        }
    };

    const markerEntries = [];
    let activeEntryIndex = 0;
    let userInteracted = false;
    let isHovered = false;
    let isSectionVisible = false;
    let autoSpotlightTimer = null;

    const highlightMarkerEntry = (targetEntry, panMap = true, animateCard = true) => {
        markerEntries.forEach((entry, index) => {
            const isActive = entry === targetEntry;
            entry.marker.setIcon(makeLandingPin(isActive, entry.item.igd, false));
            if (isActive) {
                activeEntryIndex = index;
                entry.marker.setZIndexOffset(250);
            } else {
                entry.marker.setZIndexOffset(0);
            }
        });
        updateLandingCard(targetEntry.item, animateCard);
        if (panMap) {
            map.panTo([targetEntry.item.lat, targetEntry.item.lng], { animate: true, duration: 0.75 });
        }
    };

    const stopAutoSpotlight = () => {
        userInteracted = true;
        if (autoSpotlightTimer) {
            clearInterval(autoSpotlightTimer);
            autoSpotlightTimer = null;
        }
    };

    previewItems.forEach((item, idx) => {
        const marker = L.marker([item.lat, item.lng], {
            icon: makeLandingPin(idx === 0, item.igd, true),
            title: item.name,
            zIndexOffset: idx === 0 ? 250 : 0,
        }).addTo(map);

        const entry = { marker, item };
        marker.on('click', () => {
            stopAutoSpotlight();
            highlightMarkerEntry(entry, true, true);
        });

        markerEntries.push(entry);
    });

    // Rotasi sorotan pin halus & otomatis saat section terlihat (berhenti begitu user berinteraksi)
    const prefersReducedMotion = window.matchMedia?.('(prefers-reduced-motion: reduce)').matches;
    const petaSection = document.getElementById('peta');
    const mapFrameEl = document.getElementById('landing-leaflet-map')?.closest('.rounded-3xl');

    mapFrameEl?.addEventListener('pointerenter', () => {
        isHovered = true;
    });
    mapFrameEl?.addEventListener('pointerleave', () => {
        isHovered = false;
    });
    document.getElementById('landing-leaflet-map')?.addEventListener('pointerdown', stopAutoSpotlight, { passive: true });

    const startAutoSpotlight = () => {
        if (prefersReducedMotion || userInteracted || autoSpotlightTimer || !petaSection) return;
        autoSpotlightTimer = setInterval(() => {
            if (!isSectionVisible || isHovered || userInteracted) return;
            const visibleEntries = markerEntries.filter(({ marker }) => map.hasLayer(marker));
            if (visibleEntries.length <= 1) return;

            const currentEntry = markerEntries[activeEntryIndex];
            const currentPos = visibleEntries.indexOf(currentEntry);
            const nextEntry = visibleEntries[(currentPos + 1) % visibleEntries.length];
            highlightMarkerEntry(nextEntry, true, true);
        }, 4600);
    };

    if (petaSection && !prefersReducedMotion && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver(
            (entries) => {
                isSectionVisible = entries[0]?.isIntersecting ?? false;
                if (isSectionVisible) {
                    startAutoSpotlight();
                }
            },
            { threshold: 0.35 }
        );
        observer.observe(petaSection);
    }

    // Filter Chips di atas Peta Sekilas
    document.querySelectorAll('[data-chip]').forEach((chip) => {
        chip.addEventListener('click', () => {
            stopAutoSpotlight();
            document.querySelectorAll('[data-chip]').forEach((c) => c.setAttribute('aria-pressed', String(c === chip)));
            const tag = chip.textContent.trim().toLowerCase();
            let firstVisibleEntry = null;

            markerEntries.forEach((entry) => {
                const { marker, item } = entry;
                let show = true;
                if (tag.includes('igd')) show = item.igd;
                else if (tag.includes('inap')) show = item.inap;

                if (show) {
                    if (!map.hasLayer(marker)) {
                        marker.setIcon(makeLandingPin(false, item.igd, true));
                        marker.addTo(map);
                    }
                    if (!firstVisibleEntry) firstVisibleEntry = entry;
                } else if (map.hasLayer(marker)) {
                    map.removeLayer(marker);
                }
            });

            if (firstVisibleEntry) {
                highlightMarkerEntry(firstVisibleEntry, true, true);
            }
        });
    });

    document.getElementById('landing-zoom-in')?.addEventListener('click', () => {
        stopAutoSpotlight();
        map.zoomIn();
    });
    document.getElementById('landing-zoom-out')?.addEventListener('click', () => {
        stopAutoSpotlight();
        map.zoomOut();
    });
    document.getElementById('landing-zoom-reset')?.addEventListener('click', () => {
        stopAutoSpotlight();
        map.flyTo(BOGOR_CENTER, 13, { duration: 0.8 });
    });
    document.getElementById('landing-btn-locate')?.addEventListener('click', () => {
        if (!navigator.geolocation) {
            map.flyTo(BOGOR_CENTER, 14, { duration: 0.8 });
            return;
        }
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                const coords = [pos.coords.latitude, pos.coords.longitude];
                radiusCircle.setLatLng(coords);
                map.flyTo(coords, 14, { duration: 0.9 });
            },
            () => map.flyTo(BOGOR_CENTER, 14, { duration: 0.8 }),
            { timeout: 6000 }
        );
    });
}
