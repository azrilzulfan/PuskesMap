import L from "leaflet";

// Modul utama WebGIS & Spatial Decision Support System (/peta)
export function initPuskesMapSDSS() {
    const faskesDataEl = document.getElementById("puskesmas-data");
    if (!faskesDataEl) return;

    let puskesmasList = [];
    try {
        puskesmasList = JSON.parse(faskesDataEl.textContent);
    } catch (e) {
        console.error("Gagal membaca payload data:", e);
        return;
    }

    const BOGOR_CENTER = [-6.586372, 106.7706];
    const DEFAULT_ZOOM = 13;

    // Inisialisasi Map Leaflet
    const map = L.map("leaflet-map", {
        zoomControl: false,
        attributionControl: false,
    }).setView(BOGOR_CENTER, DEFAULT_ZOOM);

    L.control
        .attribution({ position: "bottomright" })
        .addAttribution(
            '&copy; <a href="https://openstreetmap.org" class="text-primary hover:underline">OpenStreetMap</a> &bull; PuskesMap SDSS',
        )
        .addTo(map);

    L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 19,
        attribution:
            '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors &bull; PuskesMap',
    }).addTo(map);

    map.whenReady(() => {
        map.invalidateSize();
        setTimeout(() => map.invalidateSize(), 300);
    });

    // Marker Icon Generator (terpusat tepat di [lat, lng] lewat iconSize & iconAnchor)
    const createPinIcon = (color = "#1B2524", isSpotlight = false) => {
        const bg = isSpotlight
            ? "#E37434"
            : color === "#E11D2E"
              ? "#1B2524"
              : color;
        const halo = isSpotlight
            ? "rgba(227,116,52,0.28)"
            : "rgba(0,121,121,0.18)";
        const pulseRing = isSpotlight
            ? '<span class="pin-pulse absolute inset-0 rounded-full" style="background: rgba(227,116,52,0.35);"></span>'
            : "";
        return L.divIcon({
            className: "custom-puskesmas-pin",
            html: `
                <div class="pin-wrapper relative flex items-center justify-center size-10 cursor-pointer transition-transform duration-200 ${isSpotlight ? "scale-125" : "hover:scale-125"}">
                    ${pulseRing}
                    <span class="absolute inset-0 rounded-full" style="background: ${halo};"></span>
                    <span class="relative flex size-6 items-center justify-center rounded-full border-2 border-white shadow-md" style="background: ${bg};">
                        <span class="size-2 rounded-full bg-white"></span>
                    </span>
                </div>
            `,
            iconSize: [40, 40],
            iconAnchor: [20, 20],
            popupAnchor: [0, -18],
        });
    };

    const userLocationIcon = L.divIcon({
        className: "user-location-pin",
        html: `
            <div class="relative flex items-center justify-center size-6">
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
    let userAccuracyCircle = null;
    let geodesicLine = null;
    let isPickPointMode = false;
    let isLocatingGps = false;
    let gpsWatchId = null;
    let currentUserCoords = BOGOR_CENTER;
    let currentReferenceMode = "default"; // 'default' | 'gps' | 'manual'

    // Formula Jarak Geodetik Haversine
    const getDistanceKm = (lat1, lon1, lat2, lon2) => {
        const R = 6371;
        const dLat = ((lat2 - lat1) * Math.PI) / 180;
        const dLon = ((lon2 - lon1) * Math.PI) / 180;
        const a =
            Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos((lat1 * Math.PI) / 180) *
                Math.cos((lat2 * Math.PI) / 180) *
                Math.sin(dLon / 2) *
                Math.sin(dLon / 2);
        return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
    };

    // Gambar Garis Hubung Geodetik dari Titik Referensi ke Puskesmas Sasaran (SRS Tabel 1.2 No. 1)
    const drawGeodesicLineTo = (item) => {
        if (geodesicLine) map.removeLayer(geodesicLine);
        if (!currentUserCoords || !item) return;

        geodesicLine = L.polyline([currentUserCoords, [item.lat, item.lng]], {
            color: "#E37434",
            weight: 2.5,
            dashArray: "6, 8",
            opacity: 0.9,
        }).addTo(map);
    };

    // Update Floating Puskesmas Info Card di Atas Peta (Design System 3.13)
    const floatCardEl = document.getElementById("map-floating-card");
    const updateFloatingCard = (
        item,
        badgeText = "Sorotan Puskesmas",
        shouldShow = true,
    ) => {
        if (!floatCardEl || !item) return;
        floatCardEl.classList.toggle("hidden", !shouldShow);

        const distKm = getDistanceKm(
            currentUserCoords[0],
            currentUserCoords[1],
            item.lat,
            item.lng,
        );
        const distFormatted = `${distKm.toFixed(1).replace(".", ",")} km`;

        const badgeEl = document.getElementById("float-card-badge");
        const nameEl = document.getElementById("float-card-name");
        const subEl = document.getElementById("float-card-sub");
        const nakesEl = document.getElementById("float-card-nakes");
        const tagsEl = document.getElementById("float-card-tags");
        const crowdTextEl = document.getElementById("float-card-crowd-text");
        const crowdBarEl = document.getElementById("float-card-crowd-bar");
        const detailBtn = document.getElementById("float-card-detail-btn");
        const routeBtn = document.getElementById("float-card-route-btn");

        if (badgeEl) badgeEl.textContent = badgeText;
        if (nameEl) nameEl.textContent = item.name;
        if (subEl) subEl.textContent = `${distFormatted} • ${item.address}`;
        if (nakesEl) nakesEl.textContent = `${item.nakes_score}%`;

        if (tagsEl) {
            tagsEl.innerHTML = `
                <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">${item.hours}</span>
                ${item.igd ? '<span class="inline-block rounded-full bg-accent/15 px-2.5 py-0.5 text-[11px] font-semibold text-accent-strong">IGD 24 Jam</span>' : ""}
                ${item.inap ? '<span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-semibold text-primary-dark">Rawat Inap</span>' : ""}
            `;
        }

        if (crowdTextEl) crowdTextEl.textContent = item.crowd;
        if (crowdBarEl) {
            crowdBarEl.className =
                "h-full rounded-full transition-all duration-300 " +
                (item.crowd === "Sepi"
                    ? "bg-crowd-low w-1/3"
                    : item.crowd === "Ramai"
                      ? "bg-accent w-11/12"
                      : "bg-primary w-2/3");
        }

        if (detailBtn) {
            detailBtn.onclick = () => window.openDetailModal(item.id);
        }
        if (routeBtn) {
            routeBtn.href = `https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.lng}`;
        }
    };

    const hideFloatingCard = () => {
        floatCardEl?.classList.add("hidden");
        highlightMarkerPin(null);
        highlightSidebarCard(null);
        if (geodesicLine) {
            map.removeLayer(geodesicLine);
            geodesicLine = null;
        }
    };

    document
        .getElementById("btn-close-float-card")
        ?.addEventListener("click", hideFloatingCard);
    document
        .getElementById("btn-close-float-card-mobile")
        ?.addEventListener("click", hideFloatingCard);

    // Sorot pin marker puskesmas yang sedang dipilih tanpa memunculkan popup Leaflet kedua
    const highlightMarkerPin = (selectedId) => {
        const targetId =
            selectedId !== null && selectedId !== undefined
                ? String(selectedId)
                : null;
        Object.keys(markers).forEach((mid) => {
            const isSelected = targetId !== null && String(mid) === targetId;
            markers[mid].setIcon(
                createPinIcon(isSelected ? "#E37434" : "#1B2524", isSelected),
            );
            markers[mid].setZIndexOffset(isSelected ? 1000 : 0);
        });
    };

    // Render 25 Puskesmas Markers & Service Area Buffers (UC-11)
    puskesmasList.forEach((item) => {
        const marker = L.marker([item.lat, item.lng], {
            icon: createPinIcon("#1B2524", false),
            title: item.name,
        }).addTo(map);

        marker.bindTooltip(item.name, {
            direction: "top",
            offset: [0, -14],
        });
        marker.on("click", () => {
            highlightMarkerPin(item.id);
            highlightSidebarCard(item.id);
            updateFloatingCard(item, "Terpilih", true);
            drawGeodesicLineTo(item);
        });
        markers[item.id] = marker;
    });

    // ==========================================
    // FILTER MULTIKRITERIA & PENCARIAN (FR-03 & FR-04)
    // ==========================================
    const searchInput = document.getElementById("sidebar-search");
    const clearSearchBtn = document.getElementById("btn-clear-search");
    const districtSelect = document.getElementById("select-district");
    const districtSelectMobile = document.getElementById(
        "select-district-mobile",
    );
    const radiusSelect = document.getElementById("select-radius");
    const filterChips = document.querySelectorAll("[data-filter-chip]");
    const mapHashtagChips = document.querySelectorAll("[data-map-chip]");
    const filterActiveBadge = document.getElementById("filter-active-badge");
    const faskesCards = document.querySelectorAll("[data-faskes-card]");
    const counterEl = document.getElementById("puskesmas-counter");
    const mobileDockCounterEl = document.getElementById("mobile-dock-counter");
    const emptyState = document.getElementById("sidebar-empty");

    // Multi-select state untuk Filter Layanan (kosong = 'all' / Semua Puskesmas Induk)
    const activeFilters = new Set();
    let debounceTimer = null;

    const syncFilterChipUI = () => {
        const isAllActive = activeFilters.size === 0;

        filterChips.forEach((c) => {
            const val = c.dataset.filterChip;
            const isActive =
                val === "all" ? isAllActive : activeFilters.has(val);
            c.classList.toggle("is-active", isActive);
            c.setAttribute("aria-pressed", String(isActive));

            const box = c.querySelector(".chip-box");
            const label = c.querySelector("span:first-child");
            if (box) {
                box.classList.toggle("bg-ink", isActive);
                box.classList.toggle("text-white", isActive);
                box.classList.toggle("border", !isActive);
                box.classList.toggle("border-gray-300", !isActive);
                box.classList.toggle("bg-white", !isActive);
                box.classList.toggle("text-transparent", !isActive);
            }
            if (label) {
                label.classList.toggle("font-bold", isActive);
                label.classList.toggle("text-ink", isActive);
                label.classList.toggle("font-semibold", !isActive);
                label.classList.toggle("text-muted", !isActive);
            }
        });

        mapHashtagChips.forEach((mc) => {
            const val = mc.dataset.mapChip;
            const isActive =
                val === "all" ? isAllActive : activeFilters.has(val);
            mc.setAttribute("aria-pressed", String(isActive));
        });

        if (filterActiveBadge) {
            filterActiveBadge.textContent = isAllActive
                ? "Multi-pilih"
                : `${activeFilters.size} aktif`;
        }
    };

    const toggleServiceFilter = (filterVal) => {
        if (!filterVal || filterVal === "all") {
            activeFilters.clear();
        } else if (activeFilters.has(filterVal)) {
            activeFilters.delete(filterVal);
        } else {
            activeFilters.add(filterVal);
        }
        syncFilterChipUI();
        applyFilters();
    };

    const applyFilters = () => {
        const query = (searchInput?.value || "").trim().toLowerCase();
        const selectedDistrict = districtSelect?.value || "Semua";
        const radiusMeters =
            radiusSelect?.value !== "all" ? parseInt(radiusSelect.value) : null;

        let visibleCount = 0;

        faskesCards.forEach((card) => {
            const name = card.dataset.name.toLowerCase();
            const district = card.dataset.district;
            const address = card.dataset.address.toLowerCase();
            const hasIgd = card.dataset.igd === "1";
            const hasInap = card.dataset.inap === "1";
            const nakesScore = parseFloat(card.dataset.nakes) || 0;
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            const id = card.dataset.id;

            const matchSearch =
                !query ||
                name.includes(query) ||
                address.includes(query) ||
                district.toLowerCase().includes(query);
            const matchDistrict =
                selectedDistrict === "Semua" ||
                district.toLowerCase() === selectedDistrict.toLowerCase();

            // Kombinasi multi-select filter layanan (AND logic untuk setiap kriteria yang dipilih)
            const matchChip =
                activeFilters.size === 0 ||
                ((!activeFilters.has("igd") || hasIgd) &&
                    (!activeFilters.has("inap") || hasInap) &&
                    (!activeFilters.has("nakes") || nakesScore >= 99));

            let matchRadius = true;
            if (radiusMeters && currentUserCoords) {
                const distKm = getDistanceKm(
                    currentUserCoords[0],
                    currentUserCoords[1],
                    lat,
                    lng,
                );
                matchRadius = distKm * 1000 <= radiusMeters;
            }

            const isVisible =
                matchSearch && matchDistrict && matchChip && matchRadius;
            card.classList.toggle("hidden", !isVisible);

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
        if (emptyState) emptyState.classList.toggle("hidden", visibleCount > 0);
        if (clearSearchBtn) clearSearchBtn.classList.toggle("hidden", !query);

        return visibleCount;
    };

    // Debounce input 300 milidetik (NFR-03 & FR-03 SRS)
    searchInput?.addEventListener("input", () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 300);
    });

    clearSearchBtn?.addEventListener("click", () => {
        if (searchInput) searchInput.value = "";
        applyFilters();
        searchInput?.focus();
    });

    districtSelect?.addEventListener("change", () => {
        if (districtSelectMobile)
            districtSelectMobile.value = districtSelect.value;
        applyFilters();
    });
    districtSelectMobile?.addEventListener("change", () => {
        if (districtSelect) districtSelect.value = districtSelectMobile.value;
        applyFilters();
    });
    radiusSelect?.addEventListener("change", () => {
        const radiusLabel = document.getElementById("radius-label-val");
        if (radiusLabel) {
            radiusLabel.textContent =
                radiusSelect.value === "all"
                    ? "Semua"
                    : `${parseInt(radiusSelect.value) / 1000} km`;
        }
        applyFilters();
        if (radiusSelect.value !== "all" && currentUserCoords) {
            drawUserRadius(parseInt(radiusSelect.value));
        } else if (userRadiusCircle) {
            map.removeLayer(userRadiusCircle);
        }
    });

    // Pengurutan Kartu Puskesmas di Kolom Kanan (Terdekat, Nakes Lengkap, IGD 24 Jam)
    const sortSelect = document.getElementById("select-sort");
    const listContainer = document.getElementById("puskesmas-list");
    const sortCards = () => {
        if (!listContainer) return;
        const sortBy = sortSelect?.value || "distance";
        const cardsArr = Array.from(faskesCards);

        cardsArr.sort((a, b) => {
            const distA = getDistanceKm(
                currentUserCoords[0],
                currentUserCoords[1],
                parseFloat(a.dataset.lat),
                parseFloat(a.dataset.lng),
            );
            const distB = getDistanceKm(
                currentUserCoords[0],
                currentUserCoords[1],
                parseFloat(b.dataset.lat),
                parseFloat(b.dataset.lng),
            );
            if (sortBy === "nakes") {
                return (
                    parseFloat(b.dataset.nakes) - parseFloat(a.dataset.nakes) ||
                    distA - distB
                );
            }
            if (sortBy === "igd") {
                return (
                    parseInt(b.dataset.igd) - parseInt(a.dataset.igd) ||
                    distA - distB
                );
            }
            return distA - distB;
        });

        cardsArr.forEach((c) => listContainer.appendChild(c));
        if (emptyState) listContainer.appendChild(emptyState);
    };

    sortSelect?.addEventListener("change", sortCards);

    filterChips.forEach((chip) => {
        chip.addEventListener("click", () => {
            toggleServiceFilter(chip.dataset.filterChip);
        });
    });

    mapHashtagChips.forEach((chip) => {
        chip.addEventListener("click", () => {
            toggleServiceFilter(chip.dataset.mapChip);
        });
    });

    // ==========================================
    // MESIN REKOMENDASI MULTIKRITERIA MAUT (FR-07 & FR-08)
    // ==========================================
    const runMautEngine = (closeMobileDrawer = true) => {
        const scenarioRadio = document.querySelector(
            'input[name="maut-scenario"]:checked',
        );
        const scenario = scenarioRadio ? scenarioRadio.value : "darurat";

        // Bobot Kriteria Sesuai Tabel 3.3 SRS
        const weights = {
            darurat: {
                distance: 0.4,
                igd: 0.3,
                hours: 0.15,
                nakes: 0.15,
                crowd: 0.0,
            },
            reguler: {
                hours: 0.3,
                distance: 0.2,
                nakes: 0.2,
                crowd: 0.2,
                igd: 0.1,
            },
            pemda: {
                nakes: 0.3,
                distance: 0.2,
                crowd: 0.2,
                igd: 0.15,
                hours: 0.15,
            },
        }[scenario];

        // 1. Ekstrak data mentah & hitung jarak geodetik
        const crowdNumeric = { Sepi: 1, Sedang: 2, Ramai: 3 };

        const itemsWithRaw = puskesmasList.map((item) => {
            const dist = getDistanceKm(
                currentUserCoords[0],
                currentUserCoords[1],
                item.lat,
                item.lng,
            );
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
            dist: {
                min: Math.min(...itemsWithRaw.map((i) => i.raw_dist)),
                max: Math.max(...itemsWithRaw.map((i) => i.raw_dist)),
            },
            hours: {
                min: Math.min(...itemsWithRaw.map((i) => i.raw_hours)),
                max: Math.max(...itemsWithRaw.map((i) => i.raw_hours)),
            },
            nakes: {
                min: Math.min(...itemsWithRaw.map((i) => i.raw_nakes)),
                max: Math.max(...itemsWithRaw.map((i) => i.raw_nakes)),
            },
            crowd: { min: 1, max: 3 },
        };

        // 3. Normalisasi Min-Max & Hitung Skor Utilitas Linier Si = Sum(wk * Nik)
        const scored = itemsWithRaw.map((item) => {
            // Cost attributes: (max - x) / (max - min)
            const nDist =
                minMax.dist.max === minMax.dist.min
                    ? 1
                    : (minMax.dist.max - item.raw_dist) /
                      (minMax.dist.max - minMax.dist.min);
            const nCrowd =
                (minMax.crowd.max - item.raw_crowd) /
                (minMax.crowd.max - minMax.crowd.min);

            // Benefit attributes: (x - min) / (max - min)
            const nIgd = item.raw_igd; // 1 atau 0
            const nHours =
                minMax.hours.max === minMax.hours.min
                    ? 1
                    : (item.raw_hours - minMax.hours.min) /
                      (minMax.hours.max - minMax.hours.min);
            const nNakes =
                minMax.nakes.max === minMax.nakes.min
                    ? 1
                    : (item.raw_nakes - minMax.nakes.min) /
                      (minMax.nakes.max - minMax.nakes.min);

            const utilityScore =
                weights.distance * nDist +
                weights.igd * nIgd +
                weights.hours * nHours +
                weights.nakes * nNakes +
                weights.crowd * nCrowd;

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
        const container = document.getElementById("maut-cards-container");
        if (container) {
            const medals = [
                {
                    badge: "#1",
                    color: "border-accent bg-accent/10 text-accent-strong",
                },
                {
                    badge: "#2",
                    color: "border-primary bg-primary/10 text-primary-dark",
                },
                {
                    badge: "#3",
                    color: "border-gray-300 bg-gray-50 text-gray-700",
                },
            ];

            container.innerHTML = top3
                .map(
                    (item, idx) => `
                <article class="rounded-xl bg-white p-3.5 shadow-card border-2 ${idx === 0 ? "border-accent ring-2 ring-accent/20" : "border-gray-100"} transition-all">
                    <div class="flex items-center justify-between pb-2 border-b border-gray-100">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-extrabold ${medals[idx].color}">
                            ${medals[idx].badge}
                        </span>
                        <span class="text-sm font-extrabold text-primary-dark">
                            Skor ${item.percentScore}%
                        </span>
                    </div>

                    <h4 class="text-sm font-bold text-ink mt-2">${item.name}</h4>
                    <p class="text-[11px] text-muted">Kec. ${item.district} &middot; <strong>${item.raw_dist.toFixed(1).replace(".", ",")} km</strong></p>

                    <div class="mt-2.5 flex flex-wrap gap-1">
                        <span class="rounded-full bg-surface-mint px-2 py-0.5 text-[10px] font-semibold text-primary-dark">${item.hours}</span>
                        ${item.igd ? '<span class="rounded-full bg-accent/20 px-2 py-0.5 text-[10px] font-semibold text-accent-strong">IGD 24 Jam</span>' : ""}
                        <span class="rounded-full bg-blue-50 px-2 py-0.5 text-[10px] font-semibold text-blue-700">Nakes ${item.nakes_score}%</span>
                    </div>

                    <div class="mt-3 grid grid-cols-2 gap-2">
                        <button type="button" onclick="window.spotlightPuskesmas(${item.id})"
                                class="h-8 rounded-full bg-primary text-white text-[11px] font-bold hover:bg-primary-dark transition-colors flex items-center justify-center gap-1 shadow-xs">
                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                            <span>Sorot</span>
                        </button>
                        <button type="button" onclick="window.openDetailModal(${item.id})"
                                class="h-8 rounded-full border border-primary text-primary hover:bg-surface-mint text-[11px] font-bold transition-colors">
                            <span>Detail</span>
                        </button>
                    </div>
                </article>
            `,
                )
                .join("");
        }

        // UC-08: Otomatis Sorot Lokasi Puskesmas Peringkat 1 di Peta
        if (top3.length > 0 && closeMobileDrawer) {
            window.spotlightPuskesmas(top3[0].id);
        }
    };

    document
        .getElementById("btn-run-maut")
        ?.addEventListener("click", () => runMautEngine(true));

    // ==========================================
    // UC-08: PENYOROTAN LOKASI REKOMENDASI DI PETA
    // ==========================================
    window.spotlightPuskesmas = (id) => {
        const item = puskesmasList.find((p) => p.id === id);
        if (!item || !markers[id]) return;

        // Beri pin-pulse spotlight pada marker terpilih
        highlightMarkerPin(id);
        highlightSidebarCard(id);
        updateFloatingCard(item, "Terbaik #1", true);
        drawGeodesicLineTo(item);
        map.flyTo([item.lat, item.lng], 15, { duration: 1.2 });

        if (window.innerWidth < 1024) toggleMobileSidebar(false);
    };

    // ==========================================
    // MODAL DETAIL FASKES LENGKAP (FR-02 & FR-12)
    // ==========================================
    const modalEl = document.getElementById("modal-faskes-detail");
    const modalCloseBtn = document.getElementById("btn-close-modal");

    window.openDetailModal = (id) => {
        const item = puskesmasList.find((p) => p.id === id);
        if (!item || !modalEl) return;

        document.getElementById("modal-code").textContent =
            item.code || "P32710101";
        document.getElementById("modal-name").textContent = item.name;
        document.getElementById("modal-address").textContent = item.address;
        document.getElementById("modal-phone").textContent = item.phone;
        document.getElementById("modal-hours").textContent = item.hours;
        document.getElementById("modal-posyandu").textContent =
            item.posyandu_count;
        document.getElementById("modal-school").textContent = item.school_count;

        const routeLink = document.getElementById("modal-route-link");
        if (routeLink)
            routeLink.href = `https://www.google.com/maps/dir/?api=1&destination=${item.lat},${item.lng}`;

        const distKm = getDistanceKm(
            currentUserCoords[0],
            currentUserCoords[1],
            item.lat,
            item.lng,
        );
        const distFormatted = `${distKm.toFixed(1).replace(".", ",")} km`;

        const tagsContainer = document.getElementById("modal-tags");
        if (tagsContainer) {
            tagsContainer.innerHTML = `
                <span class="rounded-full bg-surface-mint px-2.5 py-1 text-xs font-semibold text-primary-dark">${distFormatted}</span>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800">Buka</span>
                ${item.igd ? '<span class="rounded-full bg-accent/20 px-2.5 py-1 text-xs font-semibold text-accent-strong">IGD 24 Jam</span>' : ""}
                ${item.inap ? '<span class="rounded-full bg-sky-100 px-2.5 py-1 text-xs font-semibold text-sky-800">Rawat Inap</span>' : ""}
                <span class="rounded-full bg-purple-100 px-2.5 py-1 text-xs font-semibold text-purple-800">BPJS</span>
            `;
        }

        const nakesBadge = document.getElementById("modal-nakes-badge");
        if (nakesBadge) {
            nakesBadge.textContent = `${item.nakes_score}% Lengkap`;
        }

        const nakesGrid = document.getElementById("modal-nakes-grid");
        if (nakesGrid && item.nakes_details) {
            nakesGrid.innerHTML = Object.entries(item.nakes_details)
                .map(
                    ([prof, count]) => `
                <div class="p-2 rounded-lg bg-gray-50 border border-gray-100">
                    <span class="text-muted block text-[10px] leading-tight">${prof}</span>
                    <strong class="text-xs text-primary-dark font-bold">${count} staf</strong>
                </div>
            `,
                )
                .join("");
        }

        modalEl.classList.remove("hidden");
    };

    modalCloseBtn?.addEventListener("click", () =>
        modalEl.classList.add("hidden"),
    );
    modalEl?.addEventListener("click", (e) => {
        if (e.target === modalEl) modalEl.classList.add("hidden");
    });

    document.querySelectorAll("[data-detail-modal-btn]").forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.stopPropagation();
            window.openDetailModal(parseInt(btn.dataset.detailModalBtn));
        });
    });

    // ==========================================
    // GEOLOKASI, TITIK MANUAL & RADIUS SPASIAL (FR-05 & FR-06)
    // ==========================================
    const refPrecisionStatusEl = document.getElementById(
        "ref-precision-status",
    );
    const refPrecisionCoordsEl = document.getElementById(
        "ref-precision-coords",
    );
    const refPrecisionAccuracyEl = document.getElementById(
        "ref-precision-accuracy",
    );
    const refPrecisionFeedbackEl = document.getElementById(
        "ref-precision-feedback",
    );
    const geoRadiusBtn = document.getElementById("btn-geo-radius");
    const geoLabelEl = document.getElementById("btn-geo-label");
    const geoIconEl = document.getElementById("btn-geo-icon");
    const gisLocateMeBtn = document.getElementById("gis-locate-me");

    const showReferenceFeedback = (msg = "", isWarning = true) => {
        if (!refPrecisionFeedbackEl) return;
        if (!msg) {
            refPrecisionFeedbackEl.classList.add("hidden");
            refPrecisionFeedbackEl.textContent = "";
            return;
        }
        refPrecisionFeedbackEl.textContent = msg;
        refPrecisionFeedbackEl.classList.remove(
            "hidden",
            "bg-amber-100/80",
            "border-amber-300/70",
            "text-amber-900",
            "bg-emerald-100/80",
            "border-emerald-300/70",
            "text-emerald-900",
        );
        if (isWarning) {
            refPrecisionFeedbackEl.classList.add(
                "bg-amber-100/80",
                "border-amber-300/70",
                "text-amber-900",
            );
        } else {
            refPrecisionFeedbackEl.classList.add(
                "bg-emerald-100/80",
                "border-emerald-300/70",
                "text-emerald-900",
            );
        }
    };

    const drawUserRadius = (radiusMeters = 3000, fly = true) => {
        if (userRadiusCircle) map.removeLayer(userRadiusCircle);

        userRadiusCircle = L.circle(currentUserCoords, {
            radius: radiusMeters,
            color: "#007979",
            dashArray: "8, 8",
            weight: 2,
            fillColor: "#D3E5D9",
            fillOpacity: 0.35,
            interactive: false,
        }).addTo(map);

        if (fly) {
            map.flyTo(currentUserCoords, 14, { duration: 1 });
        }
    };

    const setReferencePoint = (
        coords,
        labelText = "Titik Referensi Anda",
        fly = true,
        options = {},
    ) => {
        const {
            accuracy = null,
            mode = "default",
            openPopup = false,
        } = options;
        currentUserCoords = [Number(coords[0]), Number(coords[1])];
        currentReferenceMode = mode;

        const coordFormatted = `${currentUserCoords[0].toFixed(5)}, ${currentUserCoords[1].toFixed(5)}`;
        const accuracyMeters =
            typeof accuracy === "number" && Number.isFinite(accuracy)
                ? Math.max(1, Math.round(accuracy))
                : null;
        const accuracyText = accuracyMeters ? `±${accuracyMeters} m` : "";

        // Render / perbarui lingkaran akurasi sinyal GPS jika tersedia
        if (userAccuracyCircle) {
            map.removeLayer(userAccuracyCircle);
            userAccuracyCircle = null;
        }
        if (accuracyMeters && accuracyMeters <= 2000) {
            userAccuracyCircle = L.circle(currentUserCoords, {
                radius: accuracyMeters,
                color: "#0e9191",
                weight: 1.5,
                fillColor: "#0e9191",
                fillOpacity: 0.14,
                interactive: false,
            }).addTo(map);
        }

        // Render marker titik referensi yang dapat digeser (draggable) untuk koreksi presisi
        if (userMarker) map.removeLayer(userMarker);
        userMarker = L.marker(currentUserCoords, {
            icon: userLocationIcon,
            draggable: true,
            title: `${labelText} (Geser pin untuk koreksi presisi)`,
            zIndexOffset: 900,
        }).addTo(map);

        userMarker.bindPopup(
            `<div class="p-2.5 font-sans text-ink">
                <div class="text-xs font-extrabold text-primary-dark">${labelText}</div>
                <div class="text-[11px] font-mono text-muted mt-0.5">${coordFormatted}${accuracyText ? ` &bull; ${accuracyText}` : ""}</div>
                <div class="text-[10px] text-primary font-semibold mt-1">Geser pin untuk koreksi presisi</div>
            </div>`,
        );

        userMarker.on("dragend", (ev) => {
            const newLatLng = ev.target.getLatLng();
            showReferenceFeedback("Pin digeser.", false);
            setReferencePoint(
                [newLatLng.lat, newLatLng.lng],
                "Titik Manual",
                false,
                { mode: "manual", openPopup: true },
            );
        });

        // Perbarui badge di kanvas bawah & kartu Titik Acuan di panel kiri
        const refBadge = document.getElementById("ref-coord-badge");
        if (refBadge) {
            refBadge.textContent = accuracyText
                ? `${labelText} (${accuracyText})`
                : `${labelText} (${currentUserCoords[0].toFixed(4)}, ${currentUserCoords[1].toFixed(4)})`;
        }
        if (refPrecisionStatusEl) {
            refPrecisionStatusEl.textContent = labelText;
        }
        if (refPrecisionCoordsEl) {
            refPrecisionCoordsEl.textContent = coordFormatted;
        }
        if (refPrecisionAccuracyEl) {
            if (accuracyText) {
                refPrecisionAccuracyEl.textContent = accuracyText;
                refPrecisionAccuracyEl.classList.remove("hidden");
            } else {
                refPrecisionAccuracyEl.classList.add("hidden");
                refPrecisionAccuracyEl.textContent = "";
            }
        }

        // Sinkronisasi status visual tombol GPS Saya vs Pilih di Peta
        if (geoRadiusBtn) {
            const isGpsActive = mode === "gps";
            geoRadiusBtn.classList.toggle("ring-2", isGpsActive);
            geoRadiusBtn.classList.toggle("ring-primary", isGpsActive);
        }

        // Perbarui jarak geodetik dinamis pada data puskesmas & kartu sidebar
        let nearestItem = null;
        let nearestDist = Infinity;

        puskesmasList.forEach((item) => {
            const distKm = getDistanceKm(
                currentUserCoords[0],
                currentUserCoords[1],
                item.lat,
                item.lng,
            );
            item.computed_distance_km = distKm;
            item.distance = `${distKm.toFixed(1).replace(".", ",")} km`;
            if (distKm < nearestDist) {
                nearestDist = distKm;
                nearestItem = item;
            }
        });

        faskesCards.forEach((card) => {
            const lat = parseFloat(card.dataset.lat);
            const lng = parseFloat(card.dataset.lng);
            const distKm = getDistanceKm(
                currentUserCoords[0],
                currentUserCoords[1],
                lat,
                lng,
            );
            const formatted = `${distKm.toFixed(1).replace(".", ",")} km`;
            card.dataset.distance = formatted;
            const distBadge = card.querySelector("[data-card-distance]");
            if (distBadge) {
                distBadge.textContent = formatted;
            }
        });

        // Urutkan ulang daftar puskesmas sesuai jarak ke titik referensi terbaru
        sortCards();

        // Terapkan filter & jika titik GPS/manual berada di luar radius aktif (0 hasil), perluas otomatis ke 'all'
        let visibleCount = applyFilters();
        if (
            visibleCount === 0 &&
            radiusSelect &&
            radiusSelect.value !== "all" &&
            mode !== "default"
        ) {
            radiusSelect.value = "all";
            const radiusLabel = document.getElementById("radius-label-val");
            if (radiusLabel) radiusLabel.textContent = "Semua";
            if (userRadiusCircle) map.removeLayer(userRadiusCircle);
            visibleCount = applyFilters();
            showReferenceFeedback(
                `Terdekat ${nearestDist.toFixed(1).replace(".", ",")} km. Radius diatur ke Semua.`,
                false,
            );
        }

        const selectedRad =
            radiusSelect?.value !== "all"
                ? parseInt(radiusSelect?.value || "3000")
                : null;
        if (selectedRad) {
            drawUserRadius(selectedRad, fly);
        } else if (fly) {
            map.flyTo(currentUserCoords, 15, { duration: 1 });
        }

        // Perbarui kartu sorotan hanya jika kartu sedang terbuka (tidak memunculkan popup otomatis sebelum puskesmas dipilih)
        if (
            nearestItem &&
            mode !== "default" &&
            floatCardEl &&
            !floatCardEl.classList.contains("hidden")
        ) {
            updateFloatingCard(
                nearestItem,
                mode === "gps" ? "Terdekat (GPS)" : "Terdekat",
                true,
            );
            drawGeodesicLineTo(nearestItem);
        }

        // Jika panel Rekomendasi MAUT sudah pernah dihitung, hitung ulang otomatis terhadap titik baru
        if (document.getElementById("maut-cards-container")?.children.length) {
            runMautEngine(false);
        }

        if (openPopup && userMarker) {
            if (fly) {
                setTimeout(() => userMarker?.openPopup(), 1050);
            } else {
                userMarker.openPopup();
            }
        }
    };

    // Mode Pilih Titik Manual di Peta (SRS US-05 / FR-05)
    const pickPointBanner = document.getElementById("pick-point-banner");
    const pickPointBtn = document.getElementById("btn-pick-point");
    const gisPickPointBtn = document.getElementById("gis-pick-point");
    const cancelPickBtn = document.getElementById("btn-cancel-pick");

    const togglePickPointMode = (state) => {
        isPickPointMode = state;
        pickPointBanner?.classList.toggle("hidden", !state);
        document
            .getElementById("leaflet-map")
            ?.classList.toggle("cursor-crosshair", state);
        if (pickPointBtn) {
            pickPointBtn.classList.toggle("bg-accent", state);
            pickPointBtn.classList.toggle("text-white", state);
            pickPointBtn.classList.toggle("border-accent", state);
        }
    };

    pickPointBtn?.addEventListener("click", () => {
        togglePickPointMode(!isPickPointMode);
        if (window.innerWidth < 1024 && isPickPointMode) {
            toggleMobileSidebar(false);
            toggleLeftFilter(false);
        }
    });
    gisPickPointBtn?.addEventListener("click", () =>
        togglePickPointMode(!isPickPointMode),
    );
    cancelPickBtn?.addEventListener("click", () => togglePickPointMode(false));

    map.on("click", (e) => {
        if (!isPickPointMode) return;
        togglePickPointMode(false);
        if (gpsWatchId !== null && navigator.geolocation) {
            navigator.geolocation.clearWatch(gpsWatchId);
            gpsWatchId = null;
        }
        showReferenceFeedback("Titik acuan diperbarui. Geser pin bila perlu.", false);
        setReferencePoint(
            [e.latlng.lat, e.latlng.lng],
            "Titik Peta",
            true,
            { mode: "manual", openPopup: true },
        );
    });

    const setGpsLoadingState = (loading) => {
        isLocatingGps = loading;
        geoRadiusBtn?.classList.toggle("animate-pulse", loading);
        geoRadiusBtn?.toggleAttribute("disabled", loading);
        gisLocateMeBtn?.classList.toggle("animate-pulse", loading);
        geoIconEl?.classList.toggle("animate-spin", loading);
        if (geoLabelEl) {
            geoLabelEl.textContent = loading ? "Mencari..." : "GPS Saya";
        }
        if (loading && refPrecisionStatusEl) {
            refPrecisionStatusEl.textContent = "Mencari GPS...";
        }
    };

    const locateUser = () => {
        if (isLocatingGps) return;
        if (window.innerWidth < 1024) toggleLeftFilter(false);

        if (gpsWatchId !== null && navigator.geolocation) {
            navigator.geolocation.clearWatch(gpsWatchId);
            gpsWatchId = null;
        }

        if (!navigator.geolocation) {
            showReferenceFeedback(
                "GPS tidak didukung. Pilih titik di peta.",
                true,
            );
            togglePickPointMode(true);
            return;
        }

        showReferenceFeedback("", false);
        setGpsLoadingState(true);

        const applyGpsPosition = (pos, isRefined = false) => {
            setGpsLoadingState(false);
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            const acc = pos.coords.accuracy;

            setReferencePoint([lat, lng], "GPS Saya", !isRefined, {
                accuracy: acc,
                mode: "gps",
                openPopup: !isRefined,
            });

            if (acc && acc > 150 && !isRefined) {
                showReferenceFeedback(
                    `Akurasi awal ±${Math.round(acc)} m, menyempurnakan sinyal...`,
                    false,
                );
            } else {
                showReferenceFeedback(
                    `GPS terkunci${acc ? ` (±${Math.round(acc)} m)` : ""}.`,
                    false,
                );
            }
        };

        const handleGpsError = (err) => {
            setGpsLoadingState(false);
            if (refPrecisionStatusEl) {
                refPrecisionStatusEl.textContent =
                    currentReferenceMode === "gps"
                        ? "GPS Saya"
                        : currentReferenceMode === "manual"
                          ? "Titik Peta"
                          : "Pusat Kota Bogor";
            }

            if (err && err.code === 1) {
                showReferenceFeedback(
                    "Izin GPS ditolak. Ketuk peta untuk titik manual.",
                    true,
                );
                togglePickPointMode(true);
                return;
            }

            showReferenceFeedback(
                "Sinyal GPS tidak tersedia. Ketuk peta untuk titik manual.",
                true,
            );
            togglePickPointMode(true);
        };

        // Tahap 1: Minta posisi presisi tinggi segar (maximumAge: 0, enableHighAccuracy: true)
        navigator.geolocation.getCurrentPosition(
            (pos) => {
                applyGpsPosition(pos, false);

                // Jika akurasi awal masih kasar (> 100m), pantau perbaikan kunci satelit/Wi-Fi selama 6 detik
                const bestAcc = pos.coords.accuracy || 9999;
                if (bestAcc > 100 && navigator.geolocation.watchPosition) {
                    let currentBest = bestAcc;
                    gpsWatchId = navigator.geolocation.watchPosition(
                        (watchPos) => {
                            const newAcc = watchPos.coords.accuracy || 9999;
                            if (newAcc < currentBest * 0.85) {
                                currentBest = newAcc;
                                applyGpsPosition(watchPos, true);
                                if (newAcc <= 40 && gpsWatchId !== null) {
                                    navigator.geolocation.clearWatch(gpsWatchId);
                                    gpsWatchId = null;
                                }
                            }
                        },
                        () => {},
                        {
                            enableHighAccuracy: true,
                            maximumAge: 0,
                            timeout: 6000,
                        },
                    );
                    setTimeout(() => {
                        if (gpsWatchId !== null) {
                            navigator.geolocation.clearWatch(gpsWatchId);
                            gpsWatchId = null;
                        }
                    }, 6500);
                }
            },
            (highAccErr) => {
                // Jika izin ditolak (code 1), langsung tangani tanpa mencoba ulang
                if (highAccErr && highAccErr.code === 1) {
                    handleGpsError(highAccErr);
                    return;
                }

                // Tahap 2 Fallback: Jika GPS perangkat keras timeout / tidak tersedia (umum pada desktop/laptop), gunakan geolokasi jaringan/Wi-Fi
                navigator.geolocation.getCurrentPosition(
                    (fallbackPos) => {
                        applyGpsPosition(fallbackPos, false);
                    },
                    (fallbackErr) => {
                        handleGpsError(fallbackErr);
                    },
                    {
                        enableHighAccuracy: false,
                        timeout: 10000,
                        maximumAge: 0,
                    },
                );
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 0,
            },
        );
    };

    geoRadiusBtn?.addEventListener("click", locateUser);
    gisLocateMeBtn?.addEventListener("click", locateUser);
    document
        .getElementById("gis-zoom-in")
        ?.addEventListener("click", () => map.zoomIn());
    document
        .getElementById("gis-zoom-out")
        ?.addEventListener("click", () => map.zoomOut());
    document.getElementById("gis-reset-view")?.addEventListener("click", () => {
        showReferenceFeedback("", false);
        setReferencePoint(BOGOR_CENTER, "Pusat Kota Bogor", true, {
            mode: "default",
        });
        map.flyTo(BOGOR_CENTER, DEFAULT_ZOOM, { duration: 1 });
    });

    // Inisialisasi Titik Referensi Default (kartu puskesmas tetap tersembunyi sampai user memilih puskesmas)
    setReferencePoint(BOGOR_CENTER, "Pusat Kota Bogor", false, {
        mode: "default",
    });

    // ==========================================
    // MODE SWITCHER: DAFTAR FASKES VS SPK MAUT
    // ==========================================
    const tabNavFaskes = document.getElementById("tab-nav-faskes");
    const tabNavMaut = document.getElementById("tab-nav-maut");
    const subtabBtnFaskes = document.getElementById("subtab-btn-faskes");
    const subtabBtnMaut = document.getElementById("subtab-btn-maut");
    const subtabContentFaskes = document.getElementById(
        "subtab-content-faskes",
    );
    const subtabContentMaut = document.getElementById("subtab-content-maut");

    const activateSubtab = (mode) => {
        const isFaskes = mode === "faskes";
        if (subtabBtnFaskes && subtabBtnMaut) {
            subtabBtnFaskes.classList.toggle("bg-ink", isFaskes);
            subtabBtnFaskes.classList.toggle("text-white", isFaskes);
            subtabBtnFaskes.classList.toggle("bg-white", !isFaskes);
            subtabBtnFaskes.classList.toggle("border", !isFaskes);
            subtabBtnFaskes.classList.toggle("border-gray-200", !isFaskes);
            subtabBtnFaskes.classList.toggle("text-ink", !isFaskes);

            subtabBtnMaut.classList.toggle("bg-ink", !isFaskes);
            subtabBtnMaut.classList.toggle("text-white", !isFaskes);
            subtabBtnMaut.classList.toggle("bg-white", isFaskes);
            subtabBtnMaut.classList.toggle("border", isFaskes);
            subtabBtnMaut.classList.toggle("border-gray-200", isFaskes);
            subtabBtnMaut.classList.toggle("text-ink", isFaskes);
        }

        if (tabNavFaskes && tabNavMaut) {
            tabNavFaskes.classList.toggle("bg-primary", isFaskes);
            tabNavFaskes.classList.toggle("text-white", isFaskes);
            tabNavFaskes.classList.toggle("shadow-2xs", isFaskes);
            tabNavFaskes.classList.toggle("text-ink", !isFaskes);

            tabNavMaut.classList.toggle("bg-primary", !isFaskes);
            tabNavMaut.classList.toggle("text-white", !isFaskes);
            tabNavMaut.classList.toggle("shadow-2xs", !isFaskes);
            tabNavMaut.classList.toggle("text-ink", isFaskes);
        }

        subtabContentFaskes?.classList.toggle("hidden", !isFaskes);
        subtabContentMaut?.classList.toggle("hidden", isFaskes);

        if (
            !isFaskes &&
            !document.getElementById("maut-cards-container")?.children.length
        ) {
            runMautEngine(false);
        }
    };

    tabNavFaskes?.addEventListener("click", () => {
        activateSubtab("faskes");
        toggleMobileSidebar(true);
    });

    tabNavMaut?.addEventListener("click", () => {
        activateSubtab("maut");
        toggleMobileSidebar(true);
    });

    subtabBtnFaskes?.addEventListener("click", () => {
        activateSubtab("faskes");
        toggleMobileSidebar(true);
    });
    subtabBtnMaut?.addEventListener("click", () => {
        activateSubtab("maut");
        toggleMobileSidebar(true);
    });

    // Tombol Skenario Cepat di Kolom Filter Kiri (Darurat, Reguler, Pemda)
    const quickScenarioBtns = document.querySelectorAll(
        "[data-quick-scenario]",
    );
    quickScenarioBtns.forEach((btn) => {
        btn.addEventListener("click", () => {
            const val = btn.dataset.quickScenario;
            quickScenarioBtns.forEach((b) => {
                const active = b === btn;
                b.classList.toggle("bg-ink", active);
                b.classList.toggle("text-white", active);
                b.classList.toggle("bg-white", !active);
                b.classList.toggle("border", !active);
                b.classList.toggle("border-gray-200", !active);
                b.classList.toggle("text-ink", !active);
            });

            const radio = document.querySelector(
                `input[name="maut-scenario"][value="${val}"]`,
            );
            if (radio) radio.checked = true;
            activateSubtab("maut");
            if (window.innerWidth < 1024) toggleLeftFilter(false);
            toggleMobileSidebar(true);
            runMautEngine();
        });
    });

    // Kontrol Panel Kiri (Filter & Radius) & Panel Kanan (Daftar Faskes / MAUT) — Default Full Kanvas
    const expandMapBtn = document.getElementById("btn-expand-map");
    const leftFilterPanel = document.getElementById("left-filter-panel");
    const openLeftFilterBtn = document.getElementById("btn-open-left-filter");
    const sidebar = document.getElementById("map-sidebar");
    const toggleBtn = document.getElementById("btn-toggle-sidebar");
    const mobileBackdrop = document.getElementById("mobile-drawer-backdrop");

    let isLeftPanelOpen = false;
    let isRightPanelOpen = false;

    const syncPanelStates = (resizeMap = true) => {
        const isDesktop = window.innerWidth >= 1024;

        if (leftFilterPanel) {
            leftFilterPanel.classList.toggle("lg:hidden", !isLeftPanelOpen);
            leftFilterPanel.classList.toggle(
                "-translate-x-full",
                isDesktop ? false : !isLeftPanelOpen,
            );
        }

        if (sidebar) {
            sidebar.classList.toggle("lg:hidden", !isRightPanelOpen);
            sidebar.classList.toggle(
                "translate-x-full",
                isDesktop ? false : !isRightPanelOpen,
            );
        }

        if (openLeftFilterBtn) {
            openLeftFilterBtn.classList.toggle("bg-primary", isLeftPanelOpen);
            openLeftFilterBtn.classList.toggle("bg-ink", !isLeftPanelOpen);
            openLeftFilterBtn.setAttribute(
                "aria-expanded",
                String(isLeftPanelOpen),
            );
        }

        if (toggleBtn) {
            toggleBtn.classList.toggle("bg-primary", isRightPanelOpen);
            toggleBtn.classList.toggle("text-white", isRightPanelOpen);
            toggleBtn.classList.toggle("bg-surface-mint", !isRightPanelOpen);
            toggleBtn.classList.toggle("text-primary-dark", !isRightPanelOpen);
            toggleBtn.setAttribute("aria-expanded", String(isRightPanelOpen));
        }

        if (expandMapBtn) {
            const anyOpen = isLeftPanelOpen || isRightPanelOpen;
            expandMapBtn.title = anyOpen
                ? "Kembalikan ke Peta Penuh"
                : "Tampilkan Panel Filter & Daftar";
            expandMapBtn.classList.toggle("bg-ink", anyOpen);
            expandMapBtn.classList.toggle("text-white", anyOpen);
            expandMapBtn.classList.toggle("bg-white", !anyOpen);
            expandMapBtn.classList.toggle("text-ink", !anyOpen);
        }

        if (mobileBackdrop) {
            mobileBackdrop.classList.toggle(
                "hidden",
                isDesktop || !(isLeftPanelOpen || isRightPanelOpen),
            );
        }

        if (resizeMap && isDesktop) {
            requestAnimationFrame(() => map.invalidateSize());
            setTimeout(() => map.invalidateSize(), 260);
        }
    };

    const toggleMobileSidebar = (forceState = null) => {
        if (!sidebar) return;
        const shouldOpen =
            forceState !== null ? Boolean(forceState) : !isRightPanelOpen;
        isRightPanelOpen = shouldOpen;
        if (shouldOpen && window.innerWidth < 1024) {
            isLeftPanelOpen = false;
        }
        syncPanelStates();
    };

    const toggleLeftFilter = (forceState = null) => {
        if (!leftFilterPanel) return;
        const shouldOpen =
            forceState !== null ? Boolean(forceState) : !isLeftPanelOpen;
        isLeftPanelOpen = shouldOpen;
        if (shouldOpen && window.innerWidth < 1024) {
            isRightPanelOpen = false;
        }
        syncPanelStates();
    };

    expandMapBtn?.addEventListener("click", () => {
        const shouldOpenAll = !(isLeftPanelOpen || isRightPanelOpen);
        isLeftPanelOpen = shouldOpenAll;
        isRightPanelOpen = shouldOpenAll;
        syncPanelStates();
    });

    toggleBtn?.addEventListener("click", () => toggleMobileSidebar());
    document
        .getElementById("btn-close-sidebar-mobile")
        ?.addEventListener("click", () => toggleMobileSidebar(false));

    openLeftFilterBtn?.addEventListener("click", () => toggleLeftFilter());
    document
        .getElementById("mobile-dock-filter")
        ?.addEventListener("click", () => toggleLeftFilter(true));
    document
        .getElementById("btn-close-left-filter")
        ?.addEventListener("click", () => toggleLeftFilter(false));
    document
        .getElementById("btn-apply-left-filter-mobile")
        ?.addEventListener("click", () => toggleLeftFilter(false));

    document
        .getElementById("mobile-dock-faskes")
        ?.addEventListener("click", () => {
            activateSubtab("faskes");
            toggleMobileSidebar(true);
        });

    document
        .getElementById("mobile-dock-maut")
        ?.addEventListener("click", () => {
            activateSubtab("maut");
            toggleMobileSidebar(true);
        });

    mobileBackdrop?.addEventListener("click", () => {
        isLeftPanelOpen = false;
        isRightPanelOpen = false;
        syncPanelStates(false);
    });

    window.addEventListener("resize", () => syncPanelStates(true));
    syncPanelStates(false);

    // Highlight kartu di sidebar
    function highlightSidebarCard(id) {
        faskesCards.forEach((card) => {
            if (card.dataset.id === String(id)) {
                card.classList.add(
                    "ring-2",
                    "ring-primary",
                    "bg-surface-mint/40",
                );
                card.scrollIntoView({ behavior: "smooth", block: "nearest" });
            } else {
                card.classList.remove(
                    "ring-2",
                    "ring-primary",
                    "bg-surface-mint/40",
                );
            }
        });
    }

    // Klik tombol fokus di kartu
    faskesCards.forEach((card) => {
        const id = card.dataset.id;
        const lat = parseFloat(card.dataset.lat);
        const lng = parseFloat(card.dataset.lng);

        const focus = () => {
            const item = puskesmasList.find((p) => String(p.id) === String(id));
            map.flyTo([lat, lng], 16, { duration: 1.2 });
            if (markers[id]) {
                highlightMarkerPin(id);
            }
            highlightSidebarCard(id);
            if (item) {
                updateFloatingCard(item, "Terpilih", true);
                drawGeodesicLineTo(item);
            }
            if (window.innerWidth < 1024) toggleMobileSidebar(false);
        };

        card.addEventListener("click", (e) => {
            if (e.target.closest("a") || e.target.closest("button")) return;
            focus();
        });

        card.querySelector("[data-focus-btn]")?.addEventListener(
            "click",
            (e) => {
                e.stopPropagation();
                focus();
            },
        );
    });
}
