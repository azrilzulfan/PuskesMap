import './reveal.js';
import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
window.L = L;

function initPuskesMapApp() {
    // ==========================================
    // 1. GLOBAL & LANDING PAGE INTERACTION
    // ==========================================

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

    // Interaksi Tab Footer Baru (Referensi Chaumes-en-Retz)
    const footerTabs = document.querySelectorAll('[data-footer-tab]');
    footerTabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            footerTabs.forEach((t) => t.setAttribute('aria-selected', String(t === tab)));
            const addrEl = document.getElementById('footer-tab-address');
            const phoneEl = document.getElementById('footer-tab-phone');
            const hoursEl = document.getElementById('footer-tab-hours');
            if (addrEl && tab.dataset.tabAddress) addrEl.textContent = tab.dataset.tabAddress;
            if (phoneEl && tab.dataset.tabPhone) phoneEl.textContent = tab.dataset.tabPhone;
            if (hoursEl && tab.dataset.tabHours) hoursEl.textContent = tab.dataset.tabHours;
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
            const districtId = shape.dataset.districtShape;
            const popup = map.querySelector(`[data-district-popup="${districtId}"]`);
            if (!popup) return;

            map.querySelectorAll(`[data-district-shape="${districtId}"]`).forEach((el) => {
                el.classList.add('is-active');
            });
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
            popup.style.transform = 'translate(-50%, 36px)';

            const rect = popup.getBoundingClientRect();
            const parentRect = map.getBoundingClientRect();
            let shiftX = 0;
            let shiftY = 36;

            if (rect.right > parentRect.right) shiftX = parentRect.right - rect.right - 10;
            if (rect.left < parentRect.left) shiftX = parentRect.left - rect.left + 10;
            if (rect.bottom > parentRect.bottom) shiftY = -rect.height - 48;

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
            });
        });

        map.querySelectorAll('[data-popup-close]').forEach((button) => button.addEventListener('click', closeAll));
        popups.forEach((popup) => popup.addEventListener('click', (event) => event.stopPropagation()));
        document.addEventListener('click', closeAll);
        document.addEventListener('keydown', (event) => event.key === 'Escape' && closeAll());
    });

    // ==========================================
    // 2. HALAMAN PETA WEB GIS & SDSS (/peta)
    // ==========================================
    const leafletContainer = document.getElementById('leaflet-map');
    if (leafletContainer) {
        initPuskesMapSDSS();
    }
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPuskesMapApp);
} else {
    initPuskesMapApp();
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

    const makeLandingPin = (isActive = false, isIgd = true) => {
        const bg = isActive ? '#e37434' : (isIgd ? '#0b5c5c' : '#007979');
        const halo = isActive ? 'rgba(227,116,52,0.28)' : 'rgba(0,121,121,0.20)';
        return L.divIcon({
            className: 'landing-street-pin',
            html: `
                <div class="relative flex items-center justify-center size-10 transition-transform duration-200 hover:scale-125" style="transform: translate(-50%, -50%);">
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

    const updateLandingCard = (item) => {
        const nameEl = document.getElementById('info-card-name');
        const distEl = document.getElementById('info-card-distance');
        const scoreEl = document.getElementById('info-card-score');
        const tagsEl = document.getElementById('info-card-tags');
        if (nameEl) nameEl.textContent = item.name;
        if (distEl) distEl.textContent = `Kec. ${item.district} · Jarak ${item.distance}`;
        if (scoreEl) scoreEl.innerHTML = `<span class="text-primary inline-flex items-center"><svg class="size-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg></span> <span>Nakes ${item.nakes}</span>`;
        if (tagsEl) {
            tagsEl.innerHTML = `
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">${item.distance}</span>
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">Buka</span>
                ${item.igd ? '<span class="inline-block rounded-full bg-accent/15 px-2.5 py-0.5 text-[11px] font-semibold text-accent-strong">IGD 24 jam</span>' : ''}
                ${item.inap ? '<span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">Rawat inap</span>' : ''}
            `;
        }
    };

    const markerEntries = [];
    previewItems.forEach((item, idx) => {
        const marker = L.marker([item.lat, item.lng], {
            icon: makeLandingPin(idx === 0, item.igd),
            title: item.name,
        }).addTo(map);

        marker.on('click', () => {
            markerEntries.forEach((m) => m.marker.setIcon(makeLandingPin(false, m.item.igd)));
            marker.setIcon(makeLandingPin(true, item.igd));
            updateLandingCard(item);
            map.panTo([item.lat, item.lng], { animate: true });
        });

        markerEntries.push({ marker, item });
    });

    // Filter Hashtags di atas Peta Sekilas
    document.querySelectorAll('[data-chip]').forEach((chip) => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('[data-chip]').forEach((c) => c.setAttribute('aria-pressed', String(c === chip)));
            const tag = chip.textContent.trim().toLowerCase();
            let firstVisible = null;

            markerEntries.forEach(({ marker, item }) => {
                let show = true;
                if (tag.includes('igd')) show = item.igd;
                else if (tag.includes('inap')) show = item.inap;
                if (show) {
                    if (!map.hasLayer(marker)) marker.addTo(map);
                    if (!firstVisible) firstVisible = item;
                } else if (map.hasLayer(marker)) {
                    map.removeLayer(marker);
                }
            });

            if (firstVisible) updateLandingCard(firstVisible);
        });
    });

    document.getElementById('landing-zoom-in')?.addEventListener('click', () => map.zoomIn());
    document.getElementById('landing-zoom-out')?.addEventListener('click', () => map.zoomOut());
    document.getElementById('landing-zoom-reset')?.addEventListener('click', () => {
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

/**
 * Inisialisasi Penuh Web Map & Spatial Decision Support System (SRS.md)
 */
function initPuskesMapSDSS() {
    const faskesDataEl = document.getElementById('puskesmas-data');
    if (!faskesDataEl) return;

    let puskesmasList = [];
    try {
        puskesmasList = JSON.parse(faskesDataEl.textContent);
    } catch (e) {
        console.error('Gagal membaca payload data:', e);
        return;
    }

    const BOGOR_CENTER = [-6.5971, 106.8060];
    const DEFAULT_ZOOM = 13;

    // Inisialisasi Map Leaflet
    const map = L.map('leaflet-map', {
        zoomControl: false,
        attributionControl: false,
    }).setView(BOGOR_CENTER, DEFAULT_ZOOM);

    L.control.attribution({ position: 'bottomright' })
        .addAttribution('&copy; <a href="https://openstreetmap.org" class="text-primary hover:underline">OpenStreetMap</a> &bull; PuskesMap SDSS')
        .addTo(map);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors &bull; PuskesMap',
    }).addTo(map);

    map.whenReady(() => {
        map.invalidateSize();
        setTimeout(() => map.invalidateSize(), 300);
    });

    // Marker Icon Generator (Bergaya Lingkaran Modern dengan Halo seperti Referensi 1 & 2)
    const createPinIcon = (color = '#1B2524', isSpotlight = false) => {
        const bg = isSpotlight ? '#E37434' : (color === '#E11D2E' ? '#1B2524' : color);
        const halo = isSpotlight ? 'rgba(227,116,52,0.28)' : 'rgba(0,121,121,0.18)';
        return L.divIcon({
            className: 'custom-puskesmas-pin',
            html: `
                <div class="pin-wrapper cursor-pointer transition-transform duration-200 ${isSpotlight ? 'scale-125 pin-pulse' : 'hover:scale-125'}" style="transform: translate(-50%, -50%);">
                    <div class="relative flex items-center justify-center size-10">
                        <span class="absolute inset-0 rounded-full" style="background: ${halo};"></span>
                        <span class="relative flex size-6 items-center justify-center rounded-full border-2 border-white shadow-md" style="background: ${bg};">
                            <span class="size-2 rounded-full bg-white"></span>
                        </span>
                    </div>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -18],
        });
    };

    const userLocationIcon = L.divIcon({
        className: 'user-location-pin',
        html: `
            <div class="relative flex items-center justify-center size-6" style="transform: translate(-50%, -50%);">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                <span class="relative inline-flex rounded-full size-4 bg-primary border-2 border-white shadow-md"></span>
            </div>
        `,
        iconSize: [24, 24],
        iconAnchor: [12, 12],
    });

    const markers = {};
    let userMarker = null;
    let userRadiusCircle = null;
    let geodesicLine = null;
    let isPickPointMode = false;
    let currentUserCoords = BOGOR_CENTER;

    // Formula Jarak Geodetik Haversine
    const getDistanceKm = (lat1, lon1, lat2, lon2) => {
        const R = 6371;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
            Math.sin(dLon / 2) * Math.sin(dLon / 2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    };

    // Gambar Garis Hubung Geodetik dari Titik Referensi ke Puskesmas Sasaran (SRS Tabel 1.2 No. 1)
    const drawGeodesicLineTo = (item) => {
        if (geodesicLine) map.removeLayer(geodesicLine);
        if (!currentUserCoords || !item) return;

        geodesicLine = L.polyline([currentUserCoords, [item.lat, item.lng]], {
            color: '#E37434',
            weight: 2.5,
            dashArray: '6, 8',
            opacity: 0.9,
        }).addTo(map);
    };

    // Update Floating Puskesmas Info Card di Atas Peta (Design System 3.13)
    const floatCardEl = document.getElementById('map-floating-card');
    const updateFloatingCard = (item, badgeText = 'Sorotan Puskesmas', showOnMobile = true) => {
        if (!floatCardEl || !item) return;
        if (showOnMobile) {
            floatCardEl.classList.remove('hidden');
        } else {
            floatCardEl.classList.add('hidden', 'sm:block');
        }

        const distKm = getDistanceKm(currentUserCoords[0], currentUserCoords[1], item.lat, item.lng);
        const distFormatted = `${distKm.toFixed(1).replace('.', ',')} km`;

        const badgeEl = document.getElementById('float-card-badge');
        const nameEl = document.getElementById('float-card-name');
        const subEl = document.getElementById('float-card-sub');
        const nakesEl = document.getElementById('float-card-nakes');
        const tagsEl = document.getElementById('float-card-tags');
        const crowdTextEl = document.getElementById('float-card-crowd-text');
        const crowdBarEl = document.getElementById('float-card-crowd-bar');
        const detailBtn = document.getElementById('float-card-detail-btn');
        const routeBtn = document.getElementById('float-card-route-btn');

        if (badgeEl) badgeEl.textContent = badgeText;
        if (nameEl) nameEl.textContent = item.name;
        if (subEl) subEl.textContent = `${distFormatted} • ${item.address}`;
        if (nakesEl) nakesEl.textContent = `${item.nakes_score}%`;

        if (tagsEl) {
            tagsEl.innerHTML = `
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">${item.hours}</span>
                ${item.igd ? '<span class="inline-block rounded-full bg-accent/15 px-2.5 py-0.5 text-[11px] font-semibold text-accent-strong">IGD 24 jam</span>' : ''}
                ${item.inap ? '<span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">Rawat Inap</span>' : ''}
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">9 Nakes: ${item.nakes_score}%</span>
            `;
        }

        if (crowdTextEl) crowdTextEl.textContent = item.crowd;
        if (crowdBarEl) {
            crowdBarEl.className = 'h-full rounded-full transition-all duration-300 ' + (
                item.crowd === 'Sepi' ? 'bg-crowd-low w-1/3' : (item.crowd === 'Ramai' ? 'bg-accent w-11/12' : 'bg-primary w-2/3')
            );
        }

        if (detailBtn) {
            detailBtn.onclick = () => window.openDetailModal(item.id);
        }
        if (routeBtn) {
            routeBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.lng}`;
        }
    };

    document.getElementById('btn-close-float-card')?.addEventListener('click', () => {
        floatCardEl?.classList.remove('sm:block');
        floatCardEl?.classList.add('hidden');
    });
    document.getElementById('btn-close-float-card-mobile')?.addEventListener('click', () => {
        floatCardEl?.classList.remove('sm:block');
        floatCardEl?.classList.add('hidden');
    });

    // Render 25 Puskesmas Markers & Service Area Buffers (UC-11)
    puskesmasList.forEach((item) => {
        const marker = L.marker([item.lat, item.lng], {
            icon: createPinIcon('#E11D2E', false),
            title: item.name,
        }).addTo(map);

        const popupContent = `
            <div class="p-4 w-[270px] text-ink font-sans">
                <div class="flex items-center justify-between gap-1 pb-2 border-b border-gray-100">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-muted">Kec. ${item.district}</span>
                    <span class="inline-flex items-center rounded-full bg-surface-mint px-2 py-0.5 text-[10px] font-bold text-primary-dark">${item.distance}</span>
                </div>
                <h3 class="text-sm font-bold text-primary-dark mt-2 leading-tight">${item.name}</h3>
                <p class="text-[11px] text-muted mt-1 leading-snug">${item.address}</p>
                
                <div class="mt-2.5 flex flex-wrap gap-1">
                    <span class="rounded-full bg-surface-mint px-2 py-0.5 text-[10px] font-semibold text-primary-dark">${item.hours}</span>
                    ${item.igd ? '<span class="rounded-full bg-accent/20 px-2 py-0.5 text-[10px] font-semibold text-accent-strong">IGD 24 Jam</span>' : ''}
                    ${item.inap ? '<span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">Rawat Inap</span>' : ''}
                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">9 Nakes: ${item.nakes_score}%</span>
                </div>

                <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-[11px]">
                    <span class="text-muted">Estimasi Keramaian:</span>
                    <span class="font-bold ${item.crowd === 'Ramai' ? 'text-accent' : (item.crowd === 'Sepi' ? 'text-crowd-low' : 'text-primary')}">${item.crowd}</span>
                </div>

                <div class="mt-3.5 grid grid-cols-2 gap-1.5">
                    <button type="button" onclick="window.openDetailModal(${item.id})"
                       class="flex items-center justify-center gap-1 h-8 rounded-full border border-primary text-primary hover:bg-surface-mint text-[11px] font-bold transition-colors cursor-pointer">
                        <span>Detail Nakes</span>
                    </button>
                    <a href="https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.lng}" target="_blank" rel="noopener noreferrer"
                       class="flex items-center justify-center gap-1 h-8 rounded-full bg-primary text-white text-[11px] font-bold hover:bg-primary-dark transition-colors shadow-xs">
                        <span>Rute Maps</span>
                        <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                    </a>
                </div>
            </div>
        `;

        marker.bindPopup(popupContent, { maxWidth: 300, closeButton: true });
        marker.on('click', () => {
            highlightSidebarCard(item.id);
            updateFloatingCard(item, 'Puskesmas Terpilih', true);
            drawGeodesicLineTo(item);
            if (window.innerWidth < 640) {
                setTimeout(() => marker.closePopup(), 10);
            }
        });
        markers[item.id] = marker;
    });

    // ==========================================
    // FILTER MULTIKRITERIA & PENCARIAN (FR-03 & FR-04)
    // ==========================================
    const searchInput = document.getElementById('sidebar-search');
    const clearSearchBtn = document.getElementById('btn-clear-search');
    const districtSelect = document.getElementById('select-district');
    const districtSelectMobile = document.getElementById('select-district-mobile');
    const radiusSelect = document.getElementById('select-radius');
    const filterChips = document.querySelectorAll('[data-filter-chip]');
    const mapHashtagChips = document.querySelectorAll('[data-map-chip]');
    const faskesCards = document.querySelectorAll('[data-faskes-card]');
    const counterEl = document.getElementById('puskesmas-counter');
    const mobileDockCounterEl = document.getElementById('mobile-dock-counter');
    const emptyState = document.getElementById('sidebar-empty');

    let activeFilter = 'all';
    let debounceTimer = null;

    const syncFilterChipUI = (filterVal) => {
        activeFilter = filterVal;
        filterChips.forEach((c) => {
            const isActive = c.dataset.filterChip === filterVal;
            const box = c.querySelector('.chip-box');
            const label = c.querySelector('span:first-child');
            if (box) {
                box.classList.toggle('bg-ink', isActive);
                box.classList.toggle('text-white', isActive);
                box.classList.toggle('border', !isActive);
                box.classList.toggle('border-gray-300', !isActive);
                box.classList.toggle('bg-white', !isActive);
                box.classList.toggle('text-transparent', !isActive);
            }
            if (label) {
                label.classList.toggle('font-bold', isActive);
                label.classList.toggle('text-ink', isActive);
                label.classList.toggle('font-semibold', !isActive);
                label.classList.toggle('text-muted', !isActive);
            }
        });
        mapHashtagChips.forEach((mc) => {
            mc.setAttribute('aria-pressed', String(mc.dataset.mapChip === filterVal));
        });
    };

    const applyFilters = () => {
        const query = (searchInput?.value || '').trim().toLowerCase();
        const selectedDistrict = districtSelect?.value || 'Semua';
        const radiusMeters = radiusSelect?.value !== 'all' ? parseInt(radiusSelect.value) : null;

        let visibleCount = 0;

        faskesCards.forEach((card) => {
            const name = card.dataset.name.toLowerCase();
            const district = card.dataset.district;
            const address = card.dataset.address.toLowerCase();
            const hasIgd = card.dataset.igd === '1';
            const hasInap = card.dataset.inap === '1';
            const nakesScore = parseFloat(card.dataset.nakes) || 0;
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            const id = card.dataset.id;

            const matchSearch = !query || name.includes(query) || address.includes(query) || district.toLowerCase().includes(query);
            const matchDistrict = selectedDistrict === 'Semua' || district.toLowerCase() === selectedDistrict.toLowerCase();

            let matchChip = true;
            if (activeFilter === 'igd') matchChip = hasIgd;
            if (activeFilter === 'inap') matchChip = hasInap;
            if (activeFilter === 'nakes') matchChip = nakesScore >= 99;

            let matchRadius = true;
            if (radiusMeters && currentUserCoords) {
                const distKm = getDistanceKm(currentUserCoords[0], currentUserCoords[1], lat, lng);
                matchRadius = (distKm * 1000) <= radiusMeters;
            }

            const isVisible = matchSearch && matchDistrict && matchChip && matchRadius;
            card.classList.toggle('hidden', !isVisible);

            if (markers[id]) {
                if (isVisible) {
                    if (!map.hasLayer(markers[id])) map.addLayer(markers[id]);
                } else {
                    if (map.hasLayer(markers[id])) map.removeLayer(markers[id]);
                }
            }

            if (isVisible) visibleCount++;
        });

        if (counterEl) counterEl.textContent = visibleCount;
        if (mobileDockCounterEl) mobileDockCounterEl.textContent = visibleCount;
        if (emptyState) emptyState.classList.toggle('hidden', visibleCount > 0);
        if (clearSearchBtn) clearSearchBtn.classList.toggle('hidden', !query);
    };

    // Debounce input 300 milidetik (NFR-03 & FR-03 SRS)
    searchInput?.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 300);
    });

    clearSearchBtn?.addEventListener('click', () => {
        if (searchInput) searchInput.value = '';
        applyFilters();
        searchInput?.focus();
    });

    districtSelect?.addEventListener('change', () => {
        if (districtSelectMobile) districtSelectMobile.value = districtSelect.value;
        applyFilters();
    });
    districtSelectMobile?.addEventListener('change', () => {
        if (districtSelect) districtSelect.value = districtSelectMobile.value;
        applyFilters();
    });
    radiusSelect?.addEventListener('change', () => {
        const radiusLabel = document.getElementById('radius-label-val');
        if (radiusLabel) {
            radiusLabel.textContent = radiusSelect.value === 'all' ? 'Semua' : `${parseInt(radiusSelect.value) / 1000} km`;
        }
        applyFilters();
        if (radiusSelect.value !== 'all' && currentUserCoords) {
            drawUserRadius(parseInt(radiusSelect.value));
        } else if (userRadiusCircle) {
            map.removeLayer(userRadiusCircle);
        }
    });

    // Pengurutan Kartu Puskesmas di Kolom Kanan (Terdekat, Nakes Lengkap, IGD 24 Jam)
    const sortSelect = document.getElementById('select-sort');
    const listContainer = document.getElementById('puskesmas-list');
    sortSelect?.addEventListener('change', () => {
        if (!listContainer) return;
        const sortBy = sortSelect.value;
        const cardsArr = Array.from(faskesCards);

        cardsArr.sort((a, b) => {
            const distA = getDistanceKm(currentUserCoords[0], currentUserCoords[1], parseFloat(a.dataset.lat), parseFloat(a.dataset.lng));
            const distB = getDistanceKm(currentUserCoords[0], currentUserCoords[1], parseFloat(b.dataset.lat), parseFloat(b.dataset.lng));
            if (sortBy === 'nakes') {
                return (parseFloat(b.dataset.nakes) - parseFloat(a.dataset.nakes)) || (distA - distB);
            }
            if (sortBy === 'igd') {
                return (parseInt(b.dataset.igd) - parseInt(a.dataset.igd)) || (distA - distB);
            }
            return distA - distB;
        });

        cardsArr.forEach((c) => listContainer.appendChild(c));
        if (emptyState) listContainer.appendChild(emptyState);
    });

    filterChips.forEach((chip) => {
        chip.addEventListener('click', () => {
            syncFilterChipUI(chip.dataset.filterChip);
            applyFilters();
        });
    });

    mapHashtagChips.forEach((chip) => {
        chip.addEventListener('click', () => {
            syncFilterChipUI(chip.dataset.mapChip);
            applyFilters();
        });
    });

    // ==========================================
    // MESIN REKOMENDASI MULTIKRITERIA MAUT (FR-07 & FR-08)
    // ==========================================
    const runMautEngine = (closeMobileDrawer = true) => {
        const scenarioRadio = document.querySelector('input[name="maut-scenario"]:checked');
        const scenario = scenarioRadio ? scenarioRadio.value : 'darurat';

        // Bobot Kriteria Sesuai Tabel 3.3 SRS
        const weights = {
            darurat: { distance: 0.40, igd: 0.30, hours: 0.15, nakes: 0.15, crowd: 0.00 },
            reguler: { hours: 0.30, distance: 0.20, nakes: 0.20, crowd: 0.20, igd: 0.10 },
            pemda:   { nakes: 0.30, distance: 0.20, crowd: 0.20, igd: 0.15, hours: 0.15 },
        }[scenario];

        // 1. Ekstrak data mentah & hitung jarak geodetik
        const crowdNumeric = { 'Sepi': 1, 'Sedang': 2, 'Ramai': 3 };

        const itemsWithRaw = puskesmasList.map((item) => {
            const dist = getDistanceKm(currentUserCoords[0], currentUserCoords[1], item.lat, item.lng);
            return {
                ...item,
                raw_dist: dist,
                raw_igd: item.igd ? 1 : 0,
                raw_hours: item.hours_duration || 6,
                raw_nakes: item.nakes_score || 70,
                raw_crowd: crowdNumeric[item.crowd] || 2,
            };
        });

        // 2. Tentukan Min-Max untuk Normalisasi
        const minMax = {
            dist: { min: Math.min(...itemsWithRaw.map(i => i.raw_dist)), max: Math.max(...itemsWithRaw.map(i => i.raw_dist)) },
            hours: { min: Math.min(...itemsWithRaw.map(i => i.raw_hours)), max: Math.max(...itemsWithRaw.map(i => i.raw_hours)) },
            nakes: { min: Math.min(...itemsWithRaw.map(i => i.raw_nakes)), max: Math.max(...itemsWithRaw.map(i => i.raw_nakes)) },
            crowd: { min: 1, max: 3 },
        };

        // 3. Normalisasi Min-Max & Hitung Skor Utilitas Linier Si = Sum(wk * Nik)
        const scored = itemsWithRaw.map((item) => {
            // Cost attributes: (max - x) / (max - min)
            const nDist = minMax.dist.max === minMax.dist.min ? 1 : (minMax.dist.max - item.raw_dist) / (minMax.dist.max - minMax.dist.min);
            const nCrowd = (minMax.crowd.max - item.raw_crowd) / (minMax.crowd.max - minMax.crowd.min);

            // Benefit attributes: (x - min) / (max - min)
            const nIgd = item.raw_igd; // 1 atau 0
            const nHours = minMax.hours.max === minMax.hours.min ? 1 : (item.raw_hours - minMax.hours.min) / (minMax.hours.max - minMax.hours.min);
            const nNakes = minMax.nakes.max === minMax.nakes.min ? 1 : (item.raw_nakes - minMax.nakes.min) / (minMax.nakes.max - minMax.nakes.min);

            const utilityScore = (
                (weights.distance * nDist) +
                (weights.igd * nIgd) +
                (weights.hours * nHours) +
                (weights.nakes * nNakes) +
                (weights.crowd * nCrowd)
            );

            return {
                ...item,
                utilityScore: utilityScore,
                percentScore: (utilityScore * 100).toFixed(1),
            };
        });

        // 4. Urutkan Nilai Tertinggi & Ambil Top 3
        scored.sort((a, b) => b.utilityScore - a.utilityScore);
        const top3 = scored.slice(0, 3);

        // 5. Render Hasil Kartu Rekomendasi
        const container = document.getElementById('maut-cards-container');
        if (container) {
            const medals = [
                { badge: 'Peringkat #1', color: 'border-accent bg-accent/10 text-accent-strong' },
                { badge: 'Peringkat #2', color: 'border-primary bg-primary/10 text-primary-dark' },
                { badge: 'Peringkat #3', color: 'border-gray-300 bg-gray-50 text-gray-700' },
            ];

            container.innerHTML = top3.map((item, idx) => `
                <article class="rounded-xl bg-white p-3.5 shadow-card border-2 ${idx === 0 ? 'border-accent ring-2 ring-accent/20' : 'border-gray-100'} transition-all">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold ${medals[idx].color}">
                            ${medals[idx].badge}
                        </span>
                        <span class="text-sm font-extrabold text-primary-dark">
                            Skor: ${item.percentScore}%
                        </span>
                    </div>

                    <h4 class="text-sm font-bold text-ink mt-2">${item.name}</h4>
                    <p class="text-[11px] text-muted">Kec. ${item.district} · Jarak: <strong>${item.raw_dist.toFixed(2)} km</strong></p>

                    <div class="mt-2.5 flex flex-wrap gap-1">
                        <span class="rounded-full bg-surface-mint px-2 py-0.5 text-[10px] font-semibold text-primary-dark">${item.hours}</span>
                        ${item.igd ? '<span class="rounded-full bg-accent/20 px-2 py-0.5 text-[10px] font-semibold text-accent-strong">IGD 24 Jam</span>' : ''}
                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">9 Nakes: ${item.nakes_score}%</span>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button type="button" onclick="window.spotlightPuskesmas(${item.id})"
                                class="h-8 rounded-full bg-primary text-white text-[11px] font-bold hover:bg-primary-dark transition-colors flex items-center justify-center gap-1 shadow-xs">
                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                            <span>Sorot di Peta</span>
                        </button>
                        <button type="button" onclick="window.openDetailModal(${item.id})"
                                class="h-8 rounded-full border border-primary text-primary hover:bg-surface-mint text-[11px] font-bold transition-colors">
                            <span>Detail Nakes</span>
                        </button>
                    </div>
                </article>
            `).join('');
        }

        // UC-08: Otomatis Sorot Lokasi Puskesmas Peringkat 1 di Peta
        if (top3.length > 0 && closeMobileDrawer) {
            window.spotlightPuskesmas(top3[0].id);
        }
    };

    document.getElementById('btn-run-maut')?.addEventListener('click', () => runMautEngine(true));

    // ==========================================
    // UC-08: PENYOROTAN LOKASI REKOMENDASI DI PETA
    // ==========================================
    window.spotlightPuskesmas = (id) => {
        const item = puskesmasList.find(p => p.id === id);
        if (!item || !markers[id]) return;

        // Reset icon semua marker
        Object.keys(markers).forEach(mid => markers[mid].setIcon(createPinIcon('#E11D2E', false)));

        // Beri pin-pulse spotlight pada marker terpilih
        markers[id].setIcon(createPinIcon('#E37434', true));
        updateFloatingCard(item, 'Rekomendasi Terbaik MAUT', true);
        drawGeodesicLineTo(item);
        map.flyTo([item.lat, item.lng], 15, { duration: 1.2 });
        if (window.innerWidth >= 640) {
            setTimeout(() => markers[id].openPopup(), 1200);
        }

        if (window.innerWidth < 1024) toggleMobileSidebar(false);
    };

    // ==========================================
    // MODAL DETAIL FASKES LENGKAP (FR-02 & FR-12)
    // ==========================================
    const modalEl = document.getElementById('modal-faskes-detail');
    const modalCloseBtn = document.getElementById('btn-close-modal');

    window.openDetailModal = (id) => {
        const item = puskesmasList.find(p => p.id === id);
        if (!item || !modalEl) return;

        document.getElementById('modal-code').textContent = item.code || 'P32710101';
        document.getElementById('modal-name').textContent = item.name;
        document.getElementById('modal-address').textContent = item.address;
        document.getElementById('modal-phone').textContent = item.phone;
        document.getElementById('modal-hours').textContent = item.hours;
        document.getElementById('modal-posyandu').textContent = item.posyandu_count;
        document.getElementById('modal-school').textContent = item.school_count;

        const routeLink = document.getElementById('modal-route-link');
        if (routeLink) routeLink.href = `https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.lng}`;

        const tagsContainer = document.getElementById('modal-tags');
        if (tagsContainer) {
            tagsContainer.innerHTML = `
                <span class="rounded-full bg-surface-mint px-2.5 py-1 text-xs font-semibold text-primary-dark">Jarak: ${item.distance}</span>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Status: Buka Hari Ini</span>
                ${item.igd ? '<span class="rounded-full bg-accent/20 px-2.5 py-1 text-xs font-semibold text-accent-strong">Kesiapan IGD 24 Jam</span>' : ''}
                ${item.inap ? '<span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-800">Fasilitas Rawat Inap</span>' : ''}
                <span class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-800">Menerima BPJS Kesehatan</span>
            `;
        }

        const nakesBadge = document.getElementById('modal-nakes-badge');
        if (nakesBadge) {
            nakesBadge.textContent = `${item.nakes_score}% Terpenuhi (Permenkes 43/2019)`;
        }

        const nakesGrid = document.getElementById('modal-nakes-grid');
        if (nakesGrid && item.nakes_details) {
            nakesGrid.innerHTML = Object.entries(item.nakes_details).map(([prof, count]) => `
                <div class="p-2 rounded-lg bg-gray-50 border border-gray-100">
                    <span class="text-muted block text-[10px] leading-tight">${prof}</span>
                    <strong class="text-xs text-primary-dark font-bold">${count} Staf</strong>
                </div>
            `).join('');
        }

        modalEl.classList.remove('hidden');
    };

    modalCloseBtn?.addEventListener('click', () => modalEl.classList.add('hidden'));
    modalEl?.addEventListener('click', (e) => {
        if (e.target === modalEl) modalEl.classList.add('hidden');
    });

    document.querySelectorAll('[data-detail-modal-btn]').forEach((btn) => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            window.openDetailModal(parseInt(btn.dataset.detailModalBtn));
        });
    });

    // ==========================================
    // GEOLOKASI, TITIK MANUAL & RADIUS SPASIAL (FR-05 & FR-06)
    // ==========================================
    const drawUserRadius = (radiusMeters = 3000, fly = true) => {
        if (userRadiusCircle) map.removeLayer(userRadiusCircle);

        userRadiusCircle = L.circle(currentUserCoords, {
            radius: radiusMeters,
            color: '#007979',
            dashArray: '8, 8',
            weight: 2,
            fillColor: '#D3E5D9',
            fillOpacity: 0.35,
        }).addTo(map);

        if (fly) {
            map.flyTo(currentUserCoords, 14, { duration: 1 });
        }
    };

    const setReferencePoint = (coords, labelText = 'Titik Referensi Anda', fly = true) => {
        currentUserCoords = coords;

        if (userMarker) map.removeLayer(userMarker);
        userMarker = L.marker(currentUserCoords, { icon: userLocationIcon }).addTo(map);
        userMarker.bindPopup(`<b>${labelText}</b><br><span class="text-xs text-muted">${coords[0].toFixed(4)}, ${coords[1].toFixed(4)}</span>`);

        const refBadge = document.getElementById('ref-coord-badge');
        if (refBadge) refBadge.textContent = labelText;

        // Perbarui label jarak dinamis di setiap kartu sidebar
        faskesCards.forEach((card) => {
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            const distKm = getDistanceKm(currentUserCoords[0], currentUserCoords[1], lat, lng);
            const distBadge = card.querySelector('[data-card-distance]');
            if (distBadge) {
                distBadge.textContent = `${distKm.toFixed(1).replace('.', ',')} km`;
            }
        });

        const selectedRad = radiusSelect?.value !== 'all' ? parseInt(radiusSelect?.value || '3000') : 3000;
        if (radiusSelect?.value !== 'all') {
            drawUserRadius(selectedRad, fly);
        } else if (fly) {
            map.flyTo(currentUserCoords, 14, { duration: 1 });
        }

        applyFilters();
    };

    // Mode Pilih Titik Manual di Peta (SRS US-05 / FR-05)
    const pickPointBanner = document.getElementById('pick-point-banner');
    const pickPointBtn = document.getElementById('btn-pick-point');
    const gisPickPointBtn = document.getElementById('gis-pick-point');
    const cancelPickBtn = document.getElementById('btn-cancel-pick');

    const togglePickPointMode = (state) => {
        isPickPointMode = state;
        pickPointBanner?.classList.toggle('hidden', !state);
        document.getElementById('leaflet-map')?.classList.toggle('cursor-crosshair', state);
        if (pickPointBtn) {
            pickPointBtn.classList.toggle('bg-accent', state);
            pickPointBtn.classList.toggle('text-white', state);
            pickPointBtn.classList.toggle('border-accent', state);
        }
    };

    pickPointBtn?.addEventListener('click', () => {
        togglePickPointMode(!isPickPointMode);
        if (window.innerWidth < 1024 && isPickPointMode) {
            toggleMobileSidebar(false);
            toggleLeftFilter(false);
        }
    });
    gisPickPointBtn?.addEventListener('click', () => togglePickPointMode(!isPickPointMode));
    cancelPickBtn?.addEventListener('click', () => togglePickPointMode(false));

    map.on('click', (e) => {
        if (!isPickPointMode) return;
        togglePickPointMode(false);
        setReferencePoint([e.latlng.lat, e.latlng.lng], 'Titik Pilihan Peta', true);
        userMarker?.openPopup();
    });

    const locateUser = () => {
        if (window.innerWidth < 1024) toggleLeftFilter(false);
        if (!navigator.geolocation) {
            setReferencePoint(BOGOR_CENTER, 'Pusat Kota Bogor', true);
            return;
        }

        const geoRadiusBtn = document.getElementById('btn-geo-radius');
        geoRadiusBtn?.classList.add('animate-pulse');

        navigator.geolocation.getCurrentPosition(
            (pos) => {
                geoRadiusBtn?.classList.remove('animate-pulse');
                setReferencePoint([pos.coords.latitude, pos.coords.longitude], 'Lokasi GPS Saya', true);
                userMarker?.openPopup();
            },
            (err) => {
                geoRadiusBtn?.classList.remove('animate-pulse');
                console.warn('Geolocation warning:', err.message);
                setReferencePoint(BOGOR_CENTER, 'Pusat Kota Bogor', true);
            },
            { enableHighAccuracy: true, timeout: 8000 }
        );
    };

    document.getElementById('btn-geo-radius')?.addEventListener('click', locateUser);
    document.getElementById('gis-locate-me')?.addEventListener('click', locateUser);
    document.getElementById('gis-zoom-in')?.addEventListener('click', () => map.zoomIn());
    document.getElementById('gis-zoom-out')?.addEventListener('click', () => map.zoomOut());
    document.getElementById('gis-reset-view')?.addEventListener('click', () => {
        map.flyTo(BOGOR_CENTER, DEFAULT_ZOOM, { duration: 1 });
    });

    // Inisialisasi Titik Referensi Default & Floating Card Awal (Tidak Menutupi Peta Awal di HP)
    setReferencePoint(BOGOR_CENTER, 'Pusat Kota Bogor', false);
    if (puskesmasList.length > 0) {
        updateFloatingCard(puskesmasList[0], 'Sorotan Puskesmas', false);
    }

    // ==========================================
    // MODE SWITCHER: DAFTAR FASKES VS SPK MAUT
    // ==========================================
    const tabNavFaskes = document.getElementById('tab-nav-faskes');
    const tabNavMaut = document.getElementById('tab-nav-maut');
    const subtabBtnFaskes = document.getElementById('subtab-btn-faskes');
    const subtabBtnMaut = document.getElementById('subtab-btn-maut');
    const subtabContentFaskes = document.getElementById('subtab-content-faskes');
    const subtabContentMaut = document.getElementById('subtab-content-maut');

    const activateSubtab = (mode) => {
        const isFaskes = mode === 'faskes';
        if (subtabBtnFaskes && subtabBtnMaut) {
            subtabBtnFaskes.classList.toggle('bg-ink', isFaskes);
            subtabBtnFaskes.classList.toggle('text-white', isFaskes);
            subtabBtnFaskes.classList.toggle('bg-white', !isFaskes);
            subtabBtnFaskes.classList.toggle('border', !isFaskes);
            subtabBtnFaskes.classList.toggle('border-gray-200', !isFaskes);
            subtabBtnFaskes.classList.toggle('text-ink', !isFaskes);

            subtabBtnMaut.classList.toggle('bg-ink', !isFaskes);
            subtabBtnMaut.classList.toggle('text-white', !isFaskes);
            subtabBtnMaut.classList.toggle('bg-white', isFaskes);
            subtabBtnMaut.classList.toggle('border', isFaskes);
            subtabBtnMaut.classList.toggle('border-gray-200', isFaskes);
            subtabBtnMaut.classList.toggle('text-ink', isFaskes);
        }

        if (tabNavFaskes && tabNavMaut) {
            tabNavFaskes.classList.toggle('bg-primary', isFaskes);
            tabNavFaskes.classList.toggle('text-white', isFaskes);
            tabNavFaskes.classList.toggle('shadow-2xs', isFaskes);
            tabNavFaskes.classList.toggle('text-ink', !isFaskes);

            tabNavMaut.classList.toggle('bg-primary', !isFaskes);
            tabNavMaut.classList.toggle('text-white', !isFaskes);
            tabNavMaut.classList.toggle('shadow-2xs', !isFaskes);
            tabNavMaut.classList.toggle('text-ink', isFaskes);
        }

        subtabContentFaskes?.classList.toggle('hidden', !isFaskes);
        subtabContentMaut?.classList.toggle('hidden', isFaskes);

        if (!isFaskes && !document.getElementById('maut-cards-container')?.children.length) {
            runMautEngine(false);
        }
    };

    tabNavFaskes?.addEventListener('click', () => {
        activateSubtab('faskes');
        if (window.innerWidth < 1024) toggleMobileSidebar(true);
    });

    tabNavMaut?.addEventListener('click', () => {
        activateSubtab('maut');
        if (window.innerWidth < 1024) toggleMobileSidebar(true);
    });

    subtabBtnFaskes?.addEventListener('click', () => activateSubtab('faskes'));
    subtabBtnMaut?.addEventListener('click', () => activateSubtab('maut'));

    // Tombol Skenario Cepat di Kolom Filter Kiri (Darurat, Reguler, Pemda)
    const quickScenarioBtns = document.querySelectorAll('[data-quick-scenario]');
    quickScenarioBtns.forEach((btn) => {
        btn.addEventListener('click', () => {
            const val = btn.dataset.quickScenario;
            quickScenarioBtns.forEach((b) => {
                const active = b === btn;
                b.classList.toggle('bg-ink', active);
                b.classList.toggle('text-white', active);
                b.classList.toggle('bg-white', !active);
                b.classList.toggle('border', !active);
                b.classList.toggle('border-gray-200', !active);
                b.classList.toggle('text-ink', !active);
            });

            const radio = document.querySelector(`input[name="maut-scenario"][value="${val}"]`);
            if (radio) radio.checked = true;
            activateSubtab('maut');
            if (window.innerWidth < 1024) toggleLeftFilter(false);
            runMautEngine();
        });
    });

    // Tombol Perluas Peta (Expand Map Button di Kanan Atas Peta Tengah)
    const expandMapBtn = document.getElementById('btn-expand-map');
    const leftFilterPanel = document.getElementById('left-filter-panel');
    let isMapExpanded = false;
    expandMapBtn?.addEventListener('click', () => {
        isMapExpanded = !isMapExpanded;
        leftFilterPanel?.classList.toggle('lg:hidden', isMapExpanded);
        sidebar?.classList.toggle('lg:hidden', isMapExpanded);
        setTimeout(() => map.invalidateSize(), 260);
    });

    // Mobile Drawers (Kiri: Filter & Radius, Kanan: Daftar Faskes / MAUT) + Backdrop
    const sidebar = document.getElementById('map-sidebar');
    const toggleBtn = document.getElementById('btn-toggle-sidebar');
    const mobileBackdrop = document.getElementById('mobile-drawer-backdrop');

    const syncMobileBackdrop = () => {
        if (!mobileBackdrop) return;
        const isRightOpen = sidebar && !sidebar.classList.contains('translate-x-full');
        const isLeftOpen = leftFilterPanel && !leftFilterPanel.classList.contains('-translate-x-full');
        mobileBackdrop.classList.toggle('hidden', !(isRightOpen || isLeftOpen));
    };

    const toggleMobileSidebar = (forceState = null) => {
        if (!sidebar) return;
        const isClosed = sidebar.classList.contains('translate-x-full');
        const shouldOpen = forceState !== null ? forceState : isClosed;
        if (shouldOpen && leftFilterPanel) {
            leftFilterPanel.classList.add('-translate-x-full');
        }
        sidebar.classList.toggle('translate-x-full', !shouldOpen);
        syncMobileBackdrop();
    };

    const toggleLeftFilter = (forceState = null) => {
        if (!leftFilterPanel) return;
        const isClosed = leftFilterPanel.classList.contains('-translate-x-full');
        const shouldOpen = forceState !== null ? forceState : isClosed;
        if (shouldOpen && sidebar) {
            sidebar.classList.add('translate-x-full');
        }
        leftFilterPanel.classList.toggle('-translate-x-full', !shouldOpen);
        syncMobileBackdrop();
    };

    toggleBtn?.addEventListener('click', () => toggleMobileSidebar());
    document.getElementById('btn-close-sidebar-mobile')?.addEventListener('click', () => toggleMobileSidebar(false));

    document.getElementById('btn-open-left-filter')?.addEventListener('click', () => toggleLeftFilter(true));
    document.getElementById('mobile-dock-filter')?.addEventListener('click', () => toggleLeftFilter(true));
    document.getElementById('btn-close-left-filter')?.addEventListener('click', () => toggleLeftFilter(false));
    document.getElementById('btn-apply-left-filter-mobile')?.addEventListener('click', () => toggleLeftFilter(false));

    document.getElementById('mobile-dock-faskes')?.addEventListener('click', () => {
        activateSubtab('faskes');
        toggleMobileSidebar(true);
    });

    document.getElementById('mobile-dock-maut')?.addEventListener('click', () => {
        activateSubtab('maut');
        toggleMobileSidebar(true);
    });

    mobileBackdrop?.addEventListener('click', () => {
        toggleMobileSidebar(false);
        toggleLeftFilter(false);
    });

    // Highlight kartu di sidebar
    function highlightSidebarCard(id) {
        faskesCards.forEach((card) => {
            if (card.dataset.id === String(id)) {
                card.classList.add('ring-2', 'ring-primary', 'bg-surface-mint/40');
                card.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                card.classList.remove('ring-2', 'ring-primary', 'bg-surface-mint/40');
            }
        });
    }

    // Klik tombol fokus di kartu
    faskesCards.forEach((card) => {
        const id = card.dataset.id;
        const lat = parseFloat(card.dataset.lat);
        const lng = parseFloat(card.dataset.lng);

        const focus = () => {
            const item = puskesmasList.find(p => String(p.id) === String(id));
            map.flyTo([lat, lng], 16, { duration: 1.2 });
            if (markers[id] && window.innerWidth >= 640) {
                setTimeout(() => markers[id].openPopup(), 1200);
            }
            highlightSidebarCard(id);
            if (item) {
                updateFloatingCard(item, 'Puskesmas Terpilih', true);
                drawGeodesicLineTo(item);
            }
            if (window.innerWidth < 1024) toggleMobileSidebar(false);
        };

        card.addEventListener('click', (e) => {
            if (e.target.closest('a') || e.target.closest('button')) return;
            focus();
        });

        card.querySelector('[data-focus-btn]')?.addEventListener('click', (e) => {
            e.stopPropagation();
            focus();
        });
    });
}
