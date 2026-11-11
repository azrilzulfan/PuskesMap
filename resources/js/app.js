import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
window.L = L;

document.addEventListener('DOMContentLoaded', () => {
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

    document.querySelectorAll('[data-chip]').forEach((chip) => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('[data-chip]').forEach((c) => c.setAttribute('aria-pressed', String(c === chip)));
            const tag = chip.textContent.trim().toLowerCase();
            const pins = document.querySelectorAll('#peta .puskesmas-pin');

            pins.forEach((pin) => {
                let show = true;
                if (tag.includes('igd')) {
                    show = pin.dataset.pinIgd === '1';
                } else if (tag.includes('inap')) {
                    show = pin.dataset.pinInap === '1';
                }
                pin.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
                pin.style.opacity = show ? '1' : '0.2';
                pin.style.transform = show ? 'scale(1)' : 'scale(0.8)';
            });
        });
    });

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

            const posX = parseFloat(popup.dataset.x) || 50;
            const posY = parseFloat(popup.dataset.y) || 50;
            popup.style.left = `${posX}%`;
            popup.style.top = `${posY}%`;

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
});

/**
 * Inisialisasi Penuh Web Map & Spatial Decision Support System (SRS.md)
 */
function initPuskesMapSDSS() {
    const faskesDataEl = document.getElementById('puskesmas-data');
    const districtDataEl = document.getElementById('district-data');
    if (!faskesDataEl) return;

    let puskesmasList = [];
    let districtList = [];
    try {
        puskesmasList = JSON.parse(faskesDataEl.textContent);
        if (districtDataEl) districtList = JSON.parse(districtDataEl.textContent);
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

    L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
        maxZoom: 19,
        subdomains: 'abcd',
    }).addTo(map);

    // Marker Icon Generator
    const createPinIcon = (color = '#E11D2E', isSpotlight = false) => {
        return L.divIcon({
            className: 'custom-puskesmas-pin',
            html: `
                <div class="pin-wrapper cursor-pointer transition-transform duration-200 ${isSpotlight ? 'scale-125 pin-pulse' : 'hover:scale-125'}" style="transform: translate(-50%, -100%);">
                    <div class="relative drop-shadow-md">
                        <svg width="34" height="42" viewBox="0 0 34 42" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17 42C17 42 34 26.5 34 17C34 7.61116 26.3888 0 17 0C7.61116 0 0 7.61116 0 17C0 26.5 17 42 17 42Z" fill="${color}" stroke="#FFFFFF" stroke-width="2"/>
                            <circle cx="17" cy="17" r="10" fill="white"/>
                            <path d="M12 17H22M17 12V22" stroke="${color}" stroke-width="2.5" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            `,
            iconSize: [34, 42],
            iconAnchor: [17, 42],
            popupAnchor: [0, -38],
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
    const buffer15Group = L.layerGroup();
    const buffer30Group = L.layerGroup();
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
    const updateFloatingCard = (item, badgeText = 'Sorotan Puskesmas') => {
        if (!floatCardEl || !item) return;
        floatCardEl.classList.remove('hidden');

        const distKm = getDistanceKm(currentUserCoords[0], currentUserCoords[1], item.lat, item.lng);
        const distFormatted = `${distKm.toFixed(1).replace('.', ',')} km`;

        const badgeEl = document.getElementById('float-card-badge');
        const nameEl = document.getElementById('float-card-name');
        const subEl = document.getElementById('float-card-sub');
        const tagsEl = document.getElementById('float-card-tags');
        const crowdTextEl = document.getElementById('float-card-crowd-text');
        const crowdBarEl = document.getElementById('float-card-crowd-bar');
        const detailBtn = document.getElementById('float-card-detail-btn');
        const routeBtn = document.getElementById('float-card-route-btn');

        if (badgeEl) badgeEl.textContent = badgeText;
        if (nameEl) nameEl.textContent = item.name;
        if (subEl) subEl.textContent = `Kec. ${item.district} · ${distFormatted}`;

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
                <p class="text-[11px] text-muted mt-1 leading-snug">📍 ${item.address}</p>
                
                <div class="mt-2.5 flex flex-wrap gap-1">
                    <span class="rounded-full bg-surface-mint px-2 py-0.5 text-[10px] font-semibold text-primary-dark">${item.hours}</span>
                    ${item.igd ? '<span class="rounded-full bg-accent/20 px-2 py-0.5 text-[10px] font-semibold text-accent-strong">IGD 24 Jam</span>' : ''}
                    ${item.inap ? '<span class="rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">Rawat Inap</span>' : ''}
                    <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">9 Nakes: ${item.nakes_score}%</span>
                </div>

                <div class="mt-3 pt-2 border-t border-gray-100 flex items-center justify-between text-[11px]">
                    <span class="text-muted">Beban Wilayah (PSW):</span>
                    <span class="font-bold ${item.psw_index === 'Tinggi' ? 'text-accent' : 'text-primary'}">${item.psw_index} (${item.psw_score})</span>
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
            updateFloatingCard(item, 'Puskesmas Terpilih');
            drawGeodesicLineTo(item);
        });
        markers[item.id] = marker;

        // Buat Buffer Geometri Servis Area (FR-09 / UC-11)
        L.circle([item.lat, item.lng], {
            radius: 1500,
            color: '#007979',
            weight: 1,
            fillColor: '#42A08C',
            fillOpacity: 0.12,
        }).addTo(buffer15Group);

        L.circle([item.lat, item.lng], {
            radius: 3000,
            color: '#0B5C5C',
            weight: 1,
            fillColor: '#BEE9F0',
            fillOpacity: 0.08,
        }).addTo(buffer30Group);
    });

    // Default aktifkan buffer 3,0 km
    buffer30Group.addTo(map);

    // ==========================================
    // FILTER MULTIKRITERIA & PENCARIAN (FR-03 & FR-04)
    // ==========================================
    const searchInput = document.getElementById('sidebar-search');
    const clearSearchBtn = document.getElementById('btn-clear-search');
    const districtSelect = document.getElementById('select-district');
    const radiusSelect = document.getElementById('select-radius');
    const filterChips = document.querySelectorAll('[data-filter-chip]');
    const mapHashtagChips = document.querySelectorAll('[data-map-chip]');
    const faskesCards = document.querySelectorAll('[data-faskes-card]');
    const counterEl = document.getElementById('puskesmas-counter');
    const emptyState = document.getElementById('sidebar-empty');

    let activeFilter = 'all';
    let debounceTimer = null;

    const syncFilterChipUI = (filterVal) => {
        activeFilter = filterVal;
        filterChips.forEach((c) => {
            const isActive = c.dataset.filterChip === filterVal;
            c.classList.toggle('bg-primary', isActive);
            c.classList.toggle('text-white', isActive);
            c.classList.toggle('bg-surface-mint', !isActive);
            c.classList.toggle('text-primary-dark', !isActive);
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

    districtSelect?.addEventListener('change', applyFilters);
    radiusSelect?.addEventListener('change', () => {
        applyFilters();
        if (radiusSelect.value !== 'all' && currentUserCoords) {
            drawUserRadius(parseInt(radiusSelect.value));
        } else if (userRadiusCircle) {
            map.removeLayer(userRadiusCircle);
        }
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
    const runMautEngine = () => {
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
                { badge: '🥇 Peringkat #1', color: 'border-accent bg-accent/10 text-accent-strong' },
                { badge: '🥈 Peringkat #2', color: 'border-primary bg-primary/10 text-primary-dark' },
                { badge: '🥉 Peringkat #3', color: 'border-gray-300 bg-gray-50 text-gray-700' },
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
        if (top3.length > 0) {
            window.spotlightPuskesmas(top3[0].id);
        }
    };

    document.getElementById('btn-run-maut')?.addEventListener('click', runMautEngine);

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
        updateFloatingCard(item, '🥇 Rekomendasi Terbaik MAUT');
        drawGeodesicLineTo(item);
        map.flyTo([item.lat, item.lng], 15, { duration: 1.2 });
        setTimeout(() => markers[id].openPopup(), 1200);

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
        userMarker.bindPopup(`<b>📍 ${labelText}</b><br><span class="text-xs text-muted">${coords[0].toFixed(4)}, ${coords[1].toFixed(4)}</span>`);

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
        if (window.innerWidth < 1024 && isPickPointMode) toggleMobileSidebar(false);
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

    // Inisialisasi Titik Referensi Default & Floating Card Awal
    setReferencePoint(BOGOR_CENTER, 'Pusat Kota Bogor', false);
    if (puskesmasList.length > 0) {
        updateFloatingCard(puskesmasList[0], 'Sorotan Puskesmas');
    }

    // ==========================================
    // DASBOR SDSS DINKES (LAYERS & CSV EXPORT)
    // ==========================================
    const layerBuffer15 = document.getElementById('layer-buffer-15');
    const layerBuffer30 = document.getElementById('layer-buffer-30');
    const layerPsw = document.getElementById('layer-psw-index');

    layerBuffer15?.addEventListener('change', (e) => {
        if (e.target.checked) buffer15Group.addTo(map);
        else map.removeLayer(buffer15Group);
    });

    layerBuffer30?.addEventListener('change', (e) => {
        if (e.target.checked) buffer30Group.addTo(map);
        else map.removeLayer(buffer30Group);
    });

    layerPsw?.addEventListener('change', (e) => {
        const isChecked = e.target.checked;
        puskesmasList.forEach((item) => {
            if (markers[item.id]) {
                const color = isChecked
                    ? (item.psw_index === 'Tinggi' ? '#E37434' : (item.psw_index === 'Ideal' ? '#007979' : '#1D5FA8'))
                    : '#E11D2E';
                markers[item.id].setIcon(createPinIcon(color, false));
            }
        });
    });

    // Ekspor Data Rekapitulasi CSV (FR-21 SRS)
    document.getElementById('btn-export-csv')?.addEventListener('click', () => {
        const headers = ['Kode', 'Nama Puskesmas', 'Kecamatan', 'Alamat', 'Latitude', 'Longitude', 'IGD 24 Jam', 'Rawat Inap', 'Kepatuhan Nakes (%)', 'Total Posyandu', 'Total Sekolah UKS', 'Kunjungan Bulanan', 'PSW-Index'];
        const rows = puskesmasList.map(p => [
            p.code, `"${p.name}"`, `"${p.district}"`, `"${p.address}"`, p.lat, p.lng,
            p.igd ? 'Ya' : 'Tidak', p.inap ? 'Ya' : 'Tidak', p.nakes_score, p.posyandu_count, p.school_count, p.monthly_visits, p.psw_index
        ]);

        const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(','), ...rows.map(r => r.join(','))].join('\n');
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', `Rekapitulasi_Puskesmas_Kota_Bogor_${new Date().toISOString().slice(0, 10)}.csv`);
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    });

    // ==========================================
    // NAV TABS SWITCHER (PETA PUBLIK VS DINKES)
    // ==========================================
    const tabPublic = document.getElementById('tab-nav-public');
    const tabDinkes = document.getElementById('tab-nav-dinkes');
    const panelPublic = document.getElementById('panel-public');
    const panelDinkes = document.getElementById('panel-dinkes');

    tabPublic?.addEventListener('click', () => {
        tabPublic.classList.add('bg-primary', 'text-white', 'shadow-xs');
        tabPublic.classList.remove('text-primary-dark');
        tabDinkes.classList.remove('bg-primary', 'text-white', 'shadow-xs');
        tabDinkes.classList.add('text-primary-dark');

        panelPublic?.classList.remove('hidden');
        panelDinkes?.classList.add('hidden');
    });

    tabDinkes?.addEventListener('click', () => {
        tabDinkes.classList.add('bg-primary', 'text-white', 'shadow-xs');
        tabDinkes.classList.remove('text-primary-dark');
        tabPublic.classList.remove('bg-primary', 'text-white', 'shadow-xs');
        tabPublic.classList.add('text-primary-dark');

        panelDinkes?.classList.remove('hidden');
        panelPublic?.classList.add('hidden');
    });

    // Subtab Switcher: Daftar Faskes vs MAUT
    const subtabBtnFaskes = document.getElementById('subtab-btn-faskes');
    const subtabBtnMaut = document.getElementById('subtab-btn-maut');
    const subtabContentFaskes = document.getElementById('subtab-content-faskes');
    const subtabContentMaut = document.getElementById('subtab-content-maut');

    subtabBtnFaskes?.addEventListener('click', () => {
        subtabBtnFaskes.classList.add('border-primary', 'text-primary');
        subtabBtnFaskes.classList.remove('border-transparent', 'text-muted');
        subtabBtnMaut.classList.remove('border-primary', 'text-primary');
        subtabBtnMaut.classList.add('border-transparent', 'text-muted');

        subtabContentFaskes?.classList.remove('hidden');
        subtabContentMaut?.classList.add('hidden');
    });

    subtabBtnMaut?.addEventListener('click', () => {
        subtabBtnMaut.classList.add('border-primary', 'text-primary');
        subtabBtnMaut.classList.remove('border-transparent', 'text-muted');
        subtabBtnFaskes.classList.remove('border-primary', 'text-primary');
        subtabBtnFaskes.classList.add('border-transparent', 'text-muted');

        subtabContentMaut?.classList.remove('hidden');
        subtabContentFaskes?.classList.add('hidden');

        // Jalankan rekomendasi pertama kali dibuka jika belum
        if (!document.getElementById('maut-cards-container')?.children.length) {
            runMautEngine();
        }
    });

    // Mode Switcher: GIS vs Vector Art
    const btnModeGis = document.getElementById('btn-mode-gis');
    const btnModeArt = document.getElementById('btn-mode-art');
    const leafletMapEl = document.getElementById('leaflet-map');
    const vectorMapEl = document.getElementById('vector-map-view');

    btnModeGis?.addEventListener('click', () => {
        btnModeGis.classList.add('bg-white', 'text-primary-dark', 'shadow-xs', 'font-bold');
        btnModeGis.classList.remove('text-muted');
        btnModeArt.classList.remove('bg-white', 'text-primary-dark', 'shadow-xs', 'font-bold');
        btnModeArt.classList.add('text-muted');

        leafletMapEl?.classList.remove('hidden');
        vectorMapEl?.classList.add('hidden');
        map.invalidateSize();
    });

    btnModeArt?.addEventListener('click', () => {
        btnModeArt.classList.add('bg-white', 'text-primary-dark', 'shadow-xs', 'font-bold');
        btnModeArt.classList.remove('text-muted');
        btnModeGis.classList.remove('bg-white', 'text-primary-dark', 'shadow-xs', 'font-bold');
        btnModeGis.classList.add('text-muted');

        leafletMapEl?.classList.add('hidden');
        vectorMapEl?.classList.remove('hidden');
    });

    // Mobile Sidebar Drawer Toggle
    const sidebar = document.getElementById('map-sidebar');
    const toggleBtn = document.getElementById('btn-toggle-sidebar');

    const toggleMobileSidebar = (forceState = null) => {
        if (!sidebar) return;
        const isClosed = sidebar.classList.contains('-translate-x-full');
        const shouldOpen = forceState !== null ? forceState : isClosed;
        sidebar.classList.toggle('-translate-x-full', !shouldOpen);
    };

    if (window.innerWidth < 1024 && sidebar) {
        sidebar.classList.add('-translate-x-full');
    }

    toggleBtn?.addEventListener('click', () => toggleMobileSidebar());

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
            if (markers[id]) setTimeout(() => markers[id].openPopup(), 1200);
            highlightSidebarCard(id);
            if (item) {
                updateFloatingCard(item, 'Puskesmas Terpilih');
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
