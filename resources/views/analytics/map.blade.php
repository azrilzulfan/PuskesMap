@php
    $coveragePct = (float) ($coverage['summary']['persentase_terlayani'] ?? 0);
    $isAdmin = (bool) auth()->user()?->isAdmin();
@endphp

<x-dashboard-shell
    title="Peta Spasial Internal - PuskesMap"
    greeting="Peta Spasial Internal"
    subtitle="Eksplorasi layer analitik Dinkes, area blankspot, spektrum PSW-Index & simulasi MAUT Evaluasi Pemda"
>
    <x-slot:headerActions>
        <a href="{{ route('analitik.index', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
           class="inline-flex h-11 items-center rounded-full bg-white px-5 text-xs font-extrabold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            &larr; Dasbor Analitik
        </a>
        <a href="{{ route('analitik.export.csv', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
           title="Unduh CSV"
           class="inline-flex h-11 items-center rounded-full bg-white px-4 text-xs font-bold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            CSV
        </a>
        <a href="{{ route('analitik.export.pdf', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
           title="Unduh PDF"
           class="inline-flex h-11 items-center rounded-full bg-gradient-to-r from-primary to-primary-dark px-5 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95">
            Unduh PDF
        </a>
    </x-slot:headerActions>

    {{-- Row 1: 4 Compact Bento Metric Cards --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <p id="kpi-coverage-label" class="text-xs font-semibold text-muted">Terlayani ({{ $bufferKm }} km)</p>
                <p id="kpi-coverage-pct" class="mt-1.5 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $coveragePct }}%</p>
            </div>
            <div class="flex h-10 items-end gap-1.5" aria-hidden="true">
                <span class="w-1.5 h-5 rounded-full bg-surface-mint"></span>
                <span class="w-1.5 h-8 rounded-full bg-primary-dark"></span>
                <span class="w-1.5 h-6 rounded-full bg-primary/60"></span>
                <span class="w-1.5 h-10 rounded-full bg-primary"></span>
                <span class="w-1.5 h-4 rounded-full bg-primary-dark"></span>
            </div>
        </article>

        <article class="flex items-center gap-3.5 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-surface-mint text-primary-dark" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-semibold text-muted">Total Puskesmas</p>
                <p id="kpi-facility-count" class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $equity['city_totals']['puskesmas_count'] }} Unit</p>
            </div>
        </article>

        <article class="flex items-center gap-3.5 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-card-peach/65 text-accent-strong" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M12 8v4l2.5 2.5"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-semibold text-muted">Area Blankspot</p>
                <p id="kpi-blankspot-km2" class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $coverage['summary']['blankspot_km2'] }} <span class="text-sm font-bold text-muted">km&sup2;</span></p>
            </div>
        </article>

        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-gradient-to-br from-[#138282] via-primary to-primary-dark p-5 text-white shadow-[0_14px_30px_rgba(0,121,121,0.22)]">
            <div>
                <p class="text-xs font-semibold text-white/80">Rata-rata PSW</p>
                <p class="mt-1.5 text-2xl font-extrabold tracking-tight text-white tabular-nums">{{ number_format((float) $workload['summary']['average_score'], 2) }}</p>
            </div>
            <svg class="h-9 w-20 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 80 36" aria-hidden="true">
                <path stroke-linecap="round" d="M2 28 C12 6, 22 6, 30 20 C38 34, 48 10, 62 12 C70 13, 74 20, 78 24"/>
            </svg>
        </article>
    </section>

    {{-- Row 2: Bento Workspace (8 Kolom Peta + 4 Kolom Kontrol & Simulator MAUT Pemda) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Kanvas Peta Interaktif --}}
        <article class="lg:col-span-8 flex flex-col rounded-[24px] bg-white p-4 sm:p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div class="mb-3.5 flex flex-wrap items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2">
                    <button type="button" id="basemap-osm"
                            class="min-h-9 rounded-full bg-ink px-4 py-1.5 text-xs font-bold text-white transition cursor-pointer">
                        OSM Standar
                    </button>
                    <button type="button" id="basemap-grey"
                            class="min-h-9 rounded-full bg-surface-mint px-4 py-1.5 text-xs font-bold text-primary-dark transition hover:bg-card-mint cursor-pointer">
                        Grey Lite
                    </button>
                </div>

                <div class="flex flex-wrap items-center gap-2">
                    <select id="district-focus-select" aria-label="Fokus Kecamatan"
                            class="min-h-9 rounded-xl border border-[#7B8A97] bg-input px-3 py-1.5 text-xs font-semibold text-ink focus:bg-white focus:border-primary">
                        <option value="">Seluruh Kota Bogor</option>
                        @foreach ($districts as $district)
                            <option value="{{ $district->id }}" @selected($selectedKecamatanId === $district->id)>
                                {{ $district->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button" id="btn-reset-internal-map"
                            class="min-h-9 rounded-xl bg-surface-mint px-3.5 py-1.5 text-xs font-bold text-primary-dark transition hover:bg-card-mint cursor-pointer">
                        Pusatkan Peta
                    </button>
                </div>
            </div>

            <div id="internal-map-wrap" class="relative h-[480px] lg:h-auto lg:min-h-[640px] lg:flex-1 w-full overflow-hidden rounded-[18px] border border-primary/15 bg-[#f4f7f6]">
                <div id="internal-leaflet-map" class="isolate z-0 absolute inset-0 h-full w-full" aria-label="Peta Spasial Internal Dinkes Kota Bogor"></div>

                {{-- Banner Mode Pilih Titik --}}
                <div id="pick-point-banner" class="hidden pointer-events-auto absolute top-3 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2.5 rounded-full bg-ink/95 px-4 py-2 text-[11px] font-bold text-white shadow-float backdrop-blur-md">
                    <span>Klik lokasi pada peta untuk simulasi MAUT</span>
                    <button type="button" id="btn-cancel-pick"
                            class="rounded-full bg-white/15 px-2.5 py-0.5 text-[10px] font-extrabold text-white hover:bg-white/25 cursor-pointer">
                        Batal
                    </button>
                </div>

                {{-- Legenda Ringkas Mengambang --}}
                <div id="map-legend-box" class="pointer-events-auto absolute bottom-3 left-3 z-20 max-w-[calc(100%-5.5rem)] rounded-2xl border border-primary/15 bg-white/95 px-3.5 py-2.5 text-[11px] shadow-float backdrop-blur-md">
                    <p id="legend-primary-title" class="font-extrabold text-ink">Legenda PSW-Index</p>
                    <div id="legend-primary-items" class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 font-semibold text-body">
                        <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#DC2626]"></span>Tinggi (&ge;0,70)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#16A34A]"></span>Ideal (0,40–0,69)</span>
                        <span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#2563EB]"></span>Rendah (&lt;0,40)</span>
                    </div>
                    <div id="legend-extra-items" class="hidden mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 border-t border-primary/10 pt-1.5 font-semibold text-body"></div>
                </div>
            </div>
        </article>

        {{-- Kolom Kanan: Kontrol Layer & Simulator MAUT Pemda --}}
        <aside class="lg:col-span-4 flex flex-col gap-5">
            {{-- Card Kontrol Layer Spasial --}}
            <div class="rounded-[24px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
                <div class="flex items-center justify-between gap-2 border-b border-primary/10 pb-3">
                    <h2 class="text-base font-extrabold text-ink">Layer Analitik Spasial</h2>
                    <span id="layer-loading-status" class="text-[11px] font-bold text-primary tabular-nums">Siap</span>
                </div>

                <div class="mt-4 space-y-2.5 text-xs font-bold text-ink">
                    <label class="flex items-center justify-between gap-2 rounded-xl bg-[#f7fbfa] px-3.5 py-2.5 transition hover:bg-surface-mint/60 cursor-pointer">
                        <span>Batas &amp; Ekuitas Kecamatan</span>
                        <input id="toggle-equity" type="checkbox" checked class="size-4 accent-primary cursor-pointer">
                    </label>

                    <label class="flex items-center justify-between gap-2 rounded-xl bg-[#f7fbfa] px-3.5 py-2.5 transition hover:bg-surface-mint/60 cursor-pointer">
                        <span>Spektrum Beban PSW-Index</span>
                        <input id="toggle-psw" type="checkbox" checked class="size-4 accent-primary cursor-pointer">
                    </label>

                    <div class="rounded-xl bg-[#f7fbfa] px-3.5 py-2.5 space-y-2">
                        <label class="flex items-center justify-between gap-2 cursor-pointer">
                            <span>Service Area &amp; Blankspot</span>
                            <input id="toggle-coverage" type="checkbox" class="size-4 accent-primary cursor-pointer">
                        </label>
                        <div class="flex items-center gap-2">
                            <select id="coverage-buffer-select" aria-label="Radius Buffer Service Area"
                                    class="min-h-9 w-full rounded-lg border border-[#7B8A97] bg-white px-2.5 py-1 text-xs font-semibold text-ink focus:border-primary">
                                <option value="1.5" @selected(abs($bufferKm - 1.5) < 0.01)>Buffer 1.5 km (Jalan Kaki)</option>
                                <option value="3.0" @selected(abs($bufferKm - 3.0) < 0.01)>Buffer 3.0 km (Kota)</option>
                            </select>
                        </div>
                    </div>

                    <label class="flex items-center justify-between gap-2 rounded-xl bg-[#f7fbfa] px-3.5 py-2.5 transition hover:bg-surface-mint/60 cursor-pointer">
                        <span>Zona Jangkauan Jalan (37 Poligon)</span>
                        <input id="toggle-road-access" type="checkbox" class="size-4 accent-primary cursor-pointer">
                    </label>

                    <label class="flex items-center justify-between gap-2 rounded-xl bg-[#f7fbfa] px-3.5 py-2.5 transition hover:bg-surface-mint/60 cursor-pointer">
                        <span>Jaringan Jalan Utama (941 Ruas)</span>
                        <input id="toggle-jalan-utama" type="checkbox" class="size-4 accent-primary cursor-pointer">
                    </label>
                </div>
            </div>

            {{-- Card Simulator MAUT Pemda & Detail Fasilitas --}}
            <div class="flex-1 flex flex-col justify-between rounded-[24px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
                <div>
                    <h2 class="border-b border-primary/10 pb-3 text-base font-extrabold text-ink">Simulasi MAUT Internal</h2>
                    <p class="mt-2.5 text-xs text-muted">
                        Klik titik di peta atau uji titik pusat kota untuk mengevaluasi peringkat Top-3 termasuk skenario khusus Pemda.
                    </p>

                    <div class="mt-3.5 grid grid-cols-2 gap-2.5">
                        <div>
                            <label for="maut-scenario" class="block text-[11px] font-bold text-primary-dark">Skenario</label>
                            <select id="maut-scenario"
                                    class="mt-1 min-h-10 w-full rounded-xl border border-[#7B8A97] bg-input px-2.5 py-1.5 text-xs font-semibold text-ink focus:bg-white focus:border-primary">
                                <option value="pemda" selected>Evaluasi Pemda</option>
                                <option value="darurat">Kedaruratan</option>
                                <option value="reguler">Layanan Reguler</option>
                            </select>
                        </div>
                        <div>
                            <label for="maut-radius" class="block text-[11px] font-bold text-primary-dark">Radius</label>
                            <select id="maut-radius"
                                    class="mt-1 min-h-10 w-full rounded-xl border border-[#7B8A97] bg-input px-2.5 py-1.5 text-xs font-semibold text-ink focus:bg-white focus:border-primary">
                                <option value="3000" selected>3 km</option>
                                <option value="1000">1 km</option>
                                <option value="5000">5 km</option>
                                <option value="">Seluruh Kota</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-3 flex gap-2">
                        <button type="button" id="btn-pick-point"
                                class="min-h-10 flex-1 rounded-xl bg-surface-mint px-3 py-2 text-xs font-extrabold text-primary-dark transition hover:bg-card-mint cursor-pointer">
                            Pilih Titik di Peta
                        </button>
                        <button type="button" id="btn-run-center-maut"
                                class="min-h-10 flex-1 rounded-xl bg-gradient-to-r from-primary to-primary-dark px-3 py-2 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95 cursor-pointer">
                            Uji Pusat Bogor
                        </button>
                    </div>

                    <div id="maut-results" class="mt-4 space-y-2.5 text-xs">
                        <p class="rounded-xl bg-[#f7fbfa] p-3 text-muted">Belum ada simulasi dijalankan. Klik pin Puskesmas atau jalankan simulasi MAUT.</p>
                    </div>
                </div>

                {{-- Panel Info Pin Terpilih --}}
                <div id="selected-facility-box" class="mt-4 hidden rounded-2xl border border-primary/15 bg-surface-mint/50 p-4 text-xs">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-[11px] font-extrabold uppercase tracking-wider text-primary-dark" id="sel-code"></p>
                            <h3 class="mt-0.5 text-sm font-extrabold text-ink" id="sel-name"></h3>
                        </div>
                        <button type="button" id="btn-close-facility-box"
                                title="Tutup detail fasilitas"
                                aria-label="Tutup detail fasilitas"
                                class="flex size-7 shrink-0 items-center justify-center rounded-full bg-white text-muted transition hover:text-ink cursor-pointer">
                            &times;
                        </button>
                    </div>
                    <p class="mt-0.5 text-muted" id="sel-meta"></p>
                    <div class="mt-2.5 grid grid-cols-3 gap-2 border-t border-primary/15 pt-2.5 tabular-nums">
                        <div>
                            <span class="block text-[10px] text-muted">Skor PSW</span>
                            <span class="font-extrabold text-primary-dark" id="sel-psw"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-muted">Nakes</span>
                            <span class="font-extrabold text-ink" id="sel-nakes"></span>
                        </div>
                        <div>
                            <span class="block text-[10px] text-muted">Pasien/Bln</span>
                            <span class="font-extrabold text-ink" id="sel-visits"></span>
                        </div>
                    </div>
                    @if ($isAdmin)
                        <a id="sel-admin-link" href="#"
                           class="mt-3 inline-flex min-h-9 w-full items-center justify-center rounded-xl bg-primary px-3 py-1.5 text-[11px] font-extrabold text-white transition hover:bg-primary-dark">
                            Edit Operasional &amp; Nakes
                        </a>
                    @endif
                </div>
            </div>
        </aside>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.L) { return; }

            const L = window.L;
            const bogorCenter = [{{ (float) ($bogorCenter['lat'] ?? -6.597147) }}, {{ (float) ($bogorCenter['lng'] ?? 106.806039) }}];
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
            const isAdmin = @json($isAdmin);
            const initialSummary = {
                bufferKm: @json((string) $bufferKm),
                coveragePct: @json($coveragePct),
                blankspotKm2: @json($coverage['summary']['blankspot_km2'] ?? 0),
            };

            const urls = {
                puskesmas: @json(route('api.v1.puskesmas.index')),
                rekomendasi: @json(route('api.v1.rekomendasi')),
                coverage: @json(route('api.v1.analisis.coverage')),
                equity: @json(route('api.v1.analisis.equity')),
                workload: @json(route('api.v1.analisis.workload')),
                roadAccess: @json(route('api.v1.analisis.road-access')),
                jalanUtama: @json(asset('geojson/jalan_utama.geojson')),
            };

            function escapeHtml(val) {
                return String(val ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            const map = L.map('internal-leaflet-map', { zoomControl: true }).setView(bogorCenter, 13);

            const osmLayer = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map);

            const greyLayer = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap &copy; CARTO'
            });

            const mapEl = document.getElementById('internal-leaflet-map');
            const mapWrapEl = document.getElementById('internal-map-wrap');
            if (window.ResizeObserver && mapWrapEl) {
                new ResizeObserver(() => map.invalidateSize()).observe(mapWrapEl);
            }

            const btnOsm = document.getElementById('basemap-osm');
            const btnGrey = document.getElementById('basemap-grey');
            const statusEl = document.getElementById('layer-loading-status');
            const btnPickPoint = document.getElementById('btn-pick-point');
            const pickBanner = document.getElementById('pick-point-banner');

            function setBasemap(mode) {
                if (mode === 'grey') {
                    if (map.hasLayer(osmLayer)) { map.removeLayer(osmLayer); }
                    if (!map.hasLayer(greyLayer)) { greyLayer.addTo(map); }
                    btnGrey.className = 'min-h-9 rounded-full bg-ink px-4 py-1.5 text-xs font-bold text-white transition cursor-pointer';
                    btnOsm.className = 'min-h-9 rounded-full bg-surface-mint px-4 py-1.5 text-xs font-bold text-primary-dark transition hover:bg-card-mint cursor-pointer';
                } else {
                    if (map.hasLayer(greyLayer)) { map.removeLayer(greyLayer); }
                    if (!map.hasLayer(osmLayer)) { osmLayer.addTo(map); }
                    btnOsm.className = 'min-h-9 rounded-full bg-ink px-4 py-1.5 text-xs font-bold text-white transition cursor-pointer';
                    btnGrey.className = 'min-h-9 rounded-full bg-surface-mint px-4 py-1.5 text-xs font-bold text-primary-dark transition hover:bg-card-mint cursor-pointer';
                }
            }

            btnOsm.addEventListener('click', () => setBasemap('osm'));
            btnGrey.addEventListener('click', () => setBasemap('grey'));

            const districtGroup = L.layerGroup().addTo(map);
            const coverageGroup = L.layerGroup();
            const roadAccessGroup = L.layerGroup();
            const jalanUtamaGroup = L.layerGroup();
            const facilityGroup = L.layerGroup().addTo(map);
            const mautGroup = L.layerGroup().addTo(map);

            let cachedFacilities = [];
            let cachedBoundaries = null;
            let cachedEquityById = {};
            let cachedWorkloadById = {};
            let facilityMarkersById = {};
            let selectedFacilityId = null;
            let pickMode = false;

            const roadColors = {
                'Sangat Dekat': '#007979',
                'Dekat': '#2e9e6b',
                'Sedang': '#bfe6ef',
                'Jauh': '#ffe2af',
                'Sangat Jauh': '#e37434',
            };

            function setPickMode(active) {
                pickMode = active;
                if (active) {
                    btnPickPoint.textContent = 'Klik Peta Sekarang...';
                    btnPickPoint.classList.remove('bg-surface-mint', 'text-primary-dark', 'hover:bg-card-mint');
                    btnPickPoint.classList.add('bg-accent', 'text-white', 'hover:bg-accent-strong');
                    pickBanner?.classList.remove('hidden');
                    mapEl?.classList.add('cursor-crosshair');
                } else {
                    btnPickPoint.textContent = 'Pilih Titik di Peta';
                    btnPickPoint.classList.remove('bg-accent', 'text-white', 'hover:bg-accent-strong');
                    btnPickPoint.classList.add('bg-surface-mint', 'text-primary-dark', 'hover:bg-card-mint');
                    pickBanner?.classList.add('hidden');
                    mapEl?.classList.remove('cursor-crosshair');
                }
            }

            function updateLegend() {
                const usePswColor = document.getElementById('toggle-psw').checked;
                const useCoverage = document.getElementById('toggle-coverage').checked;
                const useRoadAccess = document.getElementById('toggle-road-access').checked;

                const titleEl = document.getElementById('legend-primary-title');
                const itemsEl = document.getElementById('legend-primary-items');
                const extraEl = document.getElementById('legend-extra-items');

                if (usePswColor) {
                    titleEl.textContent = 'Legenda PSW-Index';
                    itemsEl.innerHTML =
                        '<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#DC2626]"></span>Tinggi (&ge;0,70)</span>' +
                        '<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#16A34A]"></span>Ideal (0,40–0,69)</span>' +
                        '<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#2563EB]"></span>Rendah (&lt;0,40)</span>';
                } else {
                    titleEl.textContent = 'Legenda Layanan Puskesmas';
                    itemsEl.innerHTML =
                        '<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#e37434]"></span>IGD 24 Jam</span>' +
                        '<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-full bg-[#007979]"></span>Poli Reguler</span>';
                }

                const extras = [];
                if (useCoverage) {
                    extras.push('<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-xs bg-[#007979]/50 border border-[#007979]"></span>Terlayani</span>');
                    extras.push('<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-xs bg-[#e37434]/60 border border-[#c2561a]"></span>Blankspot</span>');
                }
                if (useRoadAccess) {
                    extras.push('<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-xs bg-[#2e9e6b]"></span>Akses Jalan Dekat</span>');
                    extras.push('<span class="inline-flex items-center gap-1.5"><span class="size-2.5 rounded-xs bg-[#e37434]"></span>Akses Jalan Jauh</span>');
                }

                if (extras.length > 0) {
                    extraEl.innerHTML = extras.join('');
                    extraEl.classList.remove('hidden');
                } else {
                    extraEl.innerHTML = '';
                    extraEl.classList.add('hidden');
                }
            }

            function refreshMarkerStyles() {
                const usePswColor = document.getElementById('toggle-psw').checked;
                Object.entries(facilityMarkersById).forEach(([idStr, entry]) => {
                    const isSelected = selectedFacilityId !== null && Number(idStr) === Number(selectedFacilityId);
                    const p = entry.props;
                    const wl = cachedWorkloadById[p.id];
                    const fillColor = usePswColor && wl
                        ? wl.color
                        : (p.status_igd_24jam ? '#e37434' : '#007979');
                    const baseRadius = p.status_igd_24jam ? 9 : 7;

                    entry.marker.setStyle({
                        radius: isSelected ? baseRadius + 3 : baseRadius,
                        color: isSelected ? '#1d2b2b' : '#ffffff',
                        weight: isSelected ? 3 : 2,
                        fillColor: fillColor,
                        fillOpacity: 0.95,
                    });

                    if (isSelected) {
                        entry.marker.bringToFront();
                    }
                });
            }

            function showFacilityBox(props) {
                selectedFacilityId = props.id;
                refreshMarkerStyles();

                const box = document.getElementById('selected-facility-box');
                box.classList.remove('hidden');
                document.getElementById('sel-code').textContent = props.kode_puskesmas + ' · ' + (props.nama_kecamatan || '-');
                document.getElementById('sel-name').textContent = props.nama_puskesmas;
                document.getElementById('sel-meta').textContent = (props.status_igd_24jam ? 'IGD 24 Jam' : 'Poli Reguler') + ' · Spektrum ' + (props.spektrum_beban || '-');
                document.getElementById('sel-psw').textContent = props.skor_psw !== null ? Number(props.skor_psw).toFixed(2) : '-';
                document.getElementById('sel-nakes').textContent = (props.persentase_kelengkapan_nakes ?? 0) + '%';
                document.getElementById('sel-visits').textContent = props.total_pasien_bulanan ?? 0;

                if (isAdmin) {
                    const link = document.getElementById('sel-admin-link');
                    if (link) {
                        link.href = '/admin/puskesmas/' + props.id + '/operasional';
                    }
                }
            }

            function renderFacilities() {
                facilityGroup.clearLayers();
                facilityMarkersById = {};
                const usePswColor = document.getElementById('toggle-psw').checked;
                const selectedDistrict = document.getElementById('district-focus-select').value;
                let visibleCount = 0;

                cachedFacilities.forEach((feature) => {
                    const p = feature.properties;
                    if (selectedDistrict && String(p.kecamatan_id) !== String(selectedDistrict)) {
                        return;
                    }
                    visibleCount++;

                    const coords = feature.geometry.coordinates;
                    const wl = cachedWorkloadById[p.id];
                    const fillColor = usePswColor && wl
                        ? wl.color
                        : (p.status_igd_24jam ? '#e37434' : '#007979');
                    const isSelected = selectedFacilityId !== null && Number(p.id) === Number(selectedFacilityId);
                    const baseRadius = p.status_igd_24jam ? 9 : 7;

                    const marker = L.circleMarker([coords[1], coords[0]], {
                        radius: isSelected ? baseRadius + 3 : baseRadius,
                        color: isSelected ? '#1d2b2b' : '#ffffff',
                        weight: isSelected ? 3 : 2,
                        fillColor: fillColor,
                        fillOpacity: 0.95,
                    });

                    const popupHtml =
                        '<div class="min-w-[230px] p-3.5 pr-7 text-xs font-sans text-ink space-y-1.5">' +
                            '<div class="text-[10px] font-extrabold uppercase tracking-wider text-primary-dark">' + escapeHtml(p.kode_puskesmas) + ' &middot; Kec. ' + escapeHtml(p.nama_kecamatan || '-') + '</div>' +
                            '<div class="text-sm font-extrabold leading-snug text-ink">' + escapeHtml(p.nama_puskesmas) + '</div>' +
                            '<div class="grid grid-cols-3 gap-2 border-t border-primary/10 pt-2 tabular-nums">' +
                                '<div><span class="block text-[10px] text-muted">PSW</span><strong>' + escapeHtml(p.skor_psw !== null ? Number(p.skor_psw).toFixed(2) : '-') + '</strong></div>' +
                                '<div><span class="block text-[10px] text-muted">Nakes</span><strong>' + escapeHtml((p.persentase_kelengkapan_nakes ?? 0) + '%') + '</strong></div>' +
                                '<div><span class="block text-[10px] text-muted">Layanan</span><strong>' + (p.status_igd_24jam ? 'IGD 24J' : 'Reguler') + '</strong></div>' +
                            '</div>' +
                        '</div>';

                    marker.bindPopup(popupHtml, { maxWidth: 280 });
                    marker.on('click', () => showFacilityBox(p));
                    marker.addTo(facilityGroup);
                    facilityMarkersById[p.id] = { marker, props: p };
                });

                const countEl = document.getElementById('kpi-facility-count');
                if (countEl) {
                    countEl.textContent = visibleCount + ' Unit';
                }
                updateLegend();
            }

            function renderDistrictBoundaries() {
                districtGroup.clearLayers();
                if (!document.getElementById('toggle-equity').checked || !cachedBoundaries) {
                    return;
                }

                const selectedDistrict = document.getElementById('district-focus-select').value;

                const geo = L.geoJSON(cachedBoundaries, {
                    filter: (feature) => !selectedDistrict || String(feature.properties.id) === String(selectedDistrict),
                    style: (feature) => {
                        const eq = cachedEquityById[feature.properties.id];
                        const isDeficit = eq ? eq.deficit > 0 : false;
                        return {
                            color: isDeficit ? '#e37434' : '#007979',
                            weight: 2,
                            fillColor: isDeficit ? '#ffe2af' : '#cfedea',
                            fillOpacity: 0.25,
                        };
                    },
                    onEachFeature: (feature, layer) => {
                        const eq = cachedEquityById[feature.properties.id];
                        if (eq) {
                            layer.bindTooltip(
                                escapeHtml(eq.nama_kecamatan) + ': ' + eq.puskesmas_count + '/' + eq.target_count + ' (' + escapeHtml(eq.ratio_status) + ')',
                                { sticky: true }
                            );
                        }
                    },
                }).addTo(districtGroup);

                if (selectedDistrict && geo.getLayers().length > 0) {
                    map.fitBounds(geo.getBounds(), { padding: [24, 24] });
                }
            }

            async function loadInitialData() {
                statusEl.textContent = 'Memuat layer...';
                try {
                    const [facRes, eqRes, wlRes] = await Promise.all([
                        fetch(urls.puskesmas, { headers: { Accept: 'application/json' } }),
                        fetch(urls.equity, { headers: { Accept: 'application/json' } }),
                        fetch(urls.workload, { headers: { Accept: 'application/json' } }),
                    ]);

                    if (!facRes.ok || !eqRes.ok || !wlRes.ok) {
                        throw new Error('HTTP error');
                    }

                    const facData = await facRes.json();
                    const eqData = await eqRes.json();
                    const wlData = await wlRes.json();

                    cachedFacilities = facData.features || [];
                    cachedBoundaries = facData.meta?.kecamatan_boundaries || null;

                    cachedEquityById = {};
                    (eqData.districts || []).forEach((d) => { cachedEquityById[d.id] = d; });

                    cachedWorkloadById = {};
                    (wlData.features || []).forEach((f) => { cachedWorkloadById[f.properties.id] = f.properties; });

                    renderDistrictBoundaries();
                    renderFacilities();
                    statusEl.textContent = 'Aktif (' + cachedFacilities.length + ' unit)';
                } catch (err) {
                    statusEl.textContent = 'Gagal memuat layer';
                }
            }

            async function toggleCoverageLayer() {
                const checkbox = document.getElementById('toggle-coverage');
                coverageGroup.clearLayers();
                if (!checkbox.checked) {
                    if (map.hasLayer(coverageGroup)) { map.removeLayer(coverageGroup); }
                    document.getElementById('kpi-coverage-label').textContent = 'Terlayani (' + initialSummary.bufferKm + ' km)';
                    document.getElementById('kpi-coverage-pct').textContent = initialSummary.coveragePct + '%';
                    document.getElementById('kpi-blankspot-km2').innerHTML = initialSummary.blankspotKm2 + ' <span class="text-sm font-bold text-muted">km&sup2;</span>';
                    statusEl.textContent = 'Aktif (' + cachedFacilities.length + ' unit)';
                    updateLegend();
                    return;
                }

                checkbox.disabled = true;
                statusEl.textContent = 'Menghitung buffer...';
                try {
                    const bufferKm = document.getElementById('coverage-buffer-select').value;
                    const res = await fetch(urls.coverage + '?buffer_km=' + encodeURIComponent(bufferKm), {
                        headers: { Accept: 'application/json' }
                    });
                    if (!res.ok) { throw new Error('HTTP error'); }
                    const data = await res.json();

                    document.getElementById('kpi-coverage-label').textContent = 'Terlayani (' + bufferKm + ' km)';
                    document.getElementById('kpi-coverage-pct').textContent = (data.summary?.persentase_terlayani ?? 0) + '%';
                    document.getElementById('kpi-blankspot-km2').innerHTML = (data.summary?.blankspot_km2 ?? 0) + ' <span class="text-sm font-bold text-muted">km&sup2;</span>';

                    L.geoJSON(data, {
                        style: (feature) => {
                            const isBlankspot = feature.properties?.layer === 'blankspot_zone';
                            return {
                                color: isBlankspot ? '#c2561a' : '#007979',
                                weight: 1.5,
                                fillColor: isBlankspot ? '#e37434' : '#007979',
                                fillOpacity: isBlankspot ? 0.30 : 0.18,
                            };
                        }
                    }).addTo(coverageGroup);

                    coverageGroup.addTo(map);
                    statusEl.textContent = 'Buffer ' + bufferKm + ' km aktif';
                } catch (err) {
                    checkbox.checked = false;
                    statusEl.textContent = 'Gagal memuat buffer';
                } finally {
                    checkbox.disabled = false;
                    updateLegend();
                }
            }

            async function toggleRoadAccessLayer() {
                const checkbox = document.getElementById('toggle-road-access');
                roadAccessGroup.clearLayers();
                if (!checkbox.checked) {
                    if (map.hasLayer(roadAccessGroup)) { map.removeLayer(roadAccessGroup); }
                    statusEl.textContent = 'Aktif (' + cachedFacilities.length + ' unit)';
                    updateLegend();
                    return;
                }

                checkbox.disabled = true;
                statusEl.textContent = 'Memuat zona jalan...';
                try {
                    const res = await fetch(urls.roadAccess, { headers: { Accept: 'application/json' } });
                    if (!res.ok) { throw new Error('HTTP error'); }
                    const data = await res.json();

                    L.geoJSON(data, {
                        style: (feature) => {
                            const cat = feature.properties?.kategori_jangkauan || 'Sedang';
                            return {
                                color: '#ffffff',
                                weight: 1,
                                fillColor: roadColors[cat] || '#bfe6ef',
                                fillOpacity: 0.40,
                            };
                        },
                        onEachFeature: (feature, layer) => {
                            const p = feature.properties;
                            layer.bindTooltip(escapeHtml(p.kategori_jangkauan) + ' (' + escapeHtml(p.nama_kecamatan) + ')', { sticky: true });
                        }
                    }).addTo(roadAccessGroup);

                    roadAccessGroup.addTo(map);
                    statusEl.textContent = '37 zona jalan aktif';
                } catch (err) {
                    checkbox.checked = false;
                    statusEl.textContent = 'Gagal memuat zona jalan';
                } finally {
                    checkbox.disabled = false;
                    updateLegend();
                }
            }

            async function toggleJalanUtamaLayer() {
                const checkbox = document.getElementById('toggle-jalan-utama');
                jalanUtamaGroup.clearLayers();
                if (!checkbox.checked) {
                    if (map.hasLayer(jalanUtamaGroup)) { map.removeLayer(jalanUtamaGroup); }
                    statusEl.textContent = 'Aktif (' + cachedFacilities.length + ' unit)';
                    return;
                }

                checkbox.disabled = true;
                statusEl.textContent = 'Memuat ruas jalan...';
                try {
                    const res = await fetch(urls.jalanUtama);
                    if (!res.ok) { throw new Error('HTTP error'); }
                    const data = await res.json();

                    L.geoJSON(data, {
                        style: { color: '#0b5c5c', weight: 1.5, opacity: 0.65 }
                    }).addTo(jalanUtamaGroup);

                    jalanUtamaGroup.addTo(map);
                    statusEl.textContent = '941 ruas jalan aktif';
                } catch (err) {
                    checkbox.checked = false;
                    statusEl.textContent = 'Gagal memuat ruas jalan';
                } finally {
                    checkbox.disabled = false;
                }
            }

            async function runMautSimulation(lat, lng) {
                const scenario = document.getElementById('maut-scenario').value;
                const radiusVal = document.getElementById('maut-radius').value;
                const resultsEl = document.getElementById('maut-results');

                resultsEl.innerHTML = '<p class="rounded-xl bg-[#f7fbfa] p-3 font-semibold text-primary-dark">Menghitung peringkat MAUT...</p>';
                mautGroup.clearLayers();

                L.circleMarker([lat, lng], {
                    radius: 7,
                    color: '#ffffff',
                    weight: 2,
                    fillColor: '#1d2b2b',
                    fillOpacity: 1,
                }).bindTooltip('Titik Uji Referensi (' + lat.toFixed(4) + ', ' + lng.toFixed(4) + ')').addTo(mautGroup);

                if (radiusVal) {
                    L.circle([lat, lng], {
                        radius: Number(radiusVal),
                        color: '#007979',
                        weight: 1.5,
                        fillColor: '#007979',
                        fillOpacity: 0.08,
                        dashArray: '5 4',
                    }).addTo(mautGroup);
                }

                const payload = { lat, lng, scenario };
                if (radiusVal) { payload.radius = Number(radiusVal); }

                try {
                    const res = await fetch(urls.rekomendasi, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify(payload),
                    });

                    const data = await res.json();

                    if (!res.ok) {
                        const msg = data.errors?.lat?.[0] || data.message || 'Gagal menjalankan simulasi MAUT.';
                        resultsEl.innerHTML = '<p class="rounded-xl bg-[#FDECEC] p-3 font-semibold text-[#B42318]">' + escapeHtml(msg) + '</p>';
                        return;
                    }

                    if (!data.recommendations || data.recommendations.length === 0) {
                        resultsEl.innerHTML = '<p class="rounded-xl bg-[#f7fbfa] p-3 text-muted">Tidak ditemukan Puskesmas dalam radius pilihan.</p>';
                        return;
                    }

                    resultsEl.innerHTML = data.recommendations.map((item) => {
                        const f = item.facility;
                        return '<button type="button" data-id="' + Number(f.id) + '" data-lat="' + Number(f.coordinates.latitude) + '" data-lng="' + Number(f.coordinates.longitude) + '" ' +
                            'class="maut-pick-card w-full text-left rounded-xl border border-primary/15 bg-[#f7fbfa] p-3 transition hover:bg-surface-mint cursor-pointer">' +
                            '<div class="flex items-center justify-between gap-2">' +
                            '<span class="font-extrabold text-ink">#' + Number(item.rank) + ' ' + escapeHtml(f.nama_puskesmas) + '</span>' +
                            '<span class="shrink-0 font-extrabold text-primary-dark tabular-nums">' + Number(item.score).toFixed(3) + '</span>' +
                            '</div>' +
                            '<p class="mt-0.5 text-[11px] text-muted tabular-nums">' + escapeHtml(item.distance_km) + ' km · Nakes ' + escapeHtml(f.tenaga_medis?.persentase_kelengkapan ?? 0) + '% · PSW ' + escapeHtml(f.skor_psw ?? '-') + '</p>' +
                            '</button>';
                    }).join('');

                    resultsEl.querySelectorAll('.maut-pick-card').forEach((btn) => {
                        btn.addEventListener('click', () => {
                            const fId = Number(btn.dataset.id);
                            map.flyTo([Number(btn.dataset.lat), Number(btn.dataset.lng)], 15);
                            const entry = facilityMarkersById[fId];
                            if (entry) {
                                showFacilityBox(entry.props);
                                entry.marker.openPopup();
                            }
                        });
                    });

                    const topFacility = data.recommendations[0].facility;
                    const topEntry = facilityMarkersById[topFacility.id];
                    if (topEntry) {
                        showFacilityBox(topEntry.props);
                    }
                    map.flyTo([topFacility.coordinates.latitude, topFacility.coordinates.longitude], 14);
                } catch (err) {
                    resultsEl.innerHTML = '<p class="rounded-xl bg-[#FDECEC] p-3 font-semibold text-[#B42318]">Koneksi gagal saat menghitung MAUT.</p>';
                }
            }

            document.getElementById('toggle-equity').addEventListener('change', renderDistrictBoundaries);
            document.getElementById('toggle-psw').addEventListener('change', renderFacilities);
            document.getElementById('toggle-coverage').addEventListener('change', toggleCoverageLayer);
            document.getElementById('coverage-buffer-select').addEventListener('change', () => {
                if (document.getElementById('toggle-coverage').checked) { toggleCoverageLayer(); }
            });
            document.getElementById('toggle-road-access').addEventListener('change', toggleRoadAccessLayer);
            document.getElementById('toggle-jalan-utama').addEventListener('change', toggleJalanUtamaLayer);

            document.getElementById('district-focus-select').addEventListener('change', () => {
                renderDistrictBoundaries();
                renderFacilities();
                if (!document.getElementById('district-focus-select').value) {
                    map.setView(bogorCenter, 13);
                }
            });

            document.getElementById('btn-reset-internal-map').addEventListener('click', () => {
                document.getElementById('district-focus-select').value = '';
                selectedFacilityId = null;
                document.getElementById('selected-facility-box').classList.add('hidden');
                mautGroup.clearLayers();
                renderDistrictBoundaries();
                renderFacilities();
                map.setView(bogorCenter, 13);
            });

            document.getElementById('btn-close-facility-box')?.addEventListener('click', () => {
                selectedFacilityId = null;
                refreshMarkerStyles();
                document.getElementById('selected-facility-box').classList.add('hidden');
            });

            btnPickPoint.addEventListener('click', () => setPickMode(!pickMode));
            document.getElementById('btn-cancel-pick')?.addEventListener('click', () => setPickMode(false));

            map.on('click', (e) => {
                if (!pickMode) { return; }
                setPickMode(false);
                runMautSimulation(Number(e.latlng.lat.toFixed(6)), Number(e.latlng.lng.toFixed(6)));
            });

            document.getElementById('btn-run-center-maut').addEventListener('click', () => {
                setPickMode(false);
                runMautSimulation(bogorCenter[0], bogorCenter[1]);
            });

            loadInitialData();
        });
    </script>
</x-dashboard-shell>
