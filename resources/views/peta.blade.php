<x-layout title="PuskesMap SDSS - Peta & Dasbor Spasial Puskesmas Kota Bogor" body-class="h-screen w-screen overflow-hidden bg-surface-mint flex flex-col text-ink font-sans">
    {{-- ================= TOP NAVBAR (Sesuai Design System Tanpa Tombol Login) ================= --}}
    <header class="h-16 shrink-0 bg-white/95 backdrop-blur-md border-b border-primary/15 px-4 sm:px-6 flex items-center justify-between z-30 shadow-xs">
        {{-- Kiri: Brand & Logo --}}
        <div class="flex items-center gap-6">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group" aria-label="PuskesMap Beranda">
                <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-8 w-auto transition-transform group-hover:scale-105">
                <span class="text-xl font-bold text-primary-dark tracking-tight">PuskesMap</span>
            </a>

            {{-- Navigasi Link Cepat ke Beranda --}}
            <nav aria-label="Navigasi halaman" class="hidden xl:flex items-center gap-6 text-sm font-semibold text-ink">
                <a href="{{ route('home') }}#tentang" class="hover:text-primary transition-colors">Tentang</a>
                <a href="{{ route('home') }}#fitur" class="hover:text-primary transition-colors">Fitur</a>
                <a href="{{ route('home') }}#puskesmas" class="hover:text-primary transition-colors">Puskesmas</a>
            </nav>
        </div>

        {{-- Tengah: Mode Peta Publik vs Dasbor SDSS --}}
        <div class="flex items-center rounded-full bg-surface-mint p-1 border border-primary/20 text-xs font-bold">
            <button type="button" id="tab-nav-public" class="nav-mode-btn rounded-full px-4 py-1.5 bg-primary text-white shadow-xs transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Eksplorasi & Rekomendasi MAUT</span>
            </button>
            <button type="button" id="tab-nav-dinkes" class="nav-mode-btn rounded-full px-4 py-1.5 text-primary-dark hover:text-primary transition-all flex items-center gap-1.5 cursor-pointer">
                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                <span>Analitik Spasial (Dinkes)</span>
            </button>
        </div>

        {{-- Kanan: GIS vs Vektor Art Toggle & Pill Peta Aktif --}}
        <div class="flex items-center gap-3">
            <div class="hidden sm:flex items-center rounded-full bg-surface-mint p-1 border border-primary/20 text-xs font-semibold">
                <button type="button" id="btn-mode-gis" class="rounded-full px-3.5 py-1 bg-white text-primary-dark shadow-xs transition-all font-bold cursor-pointer">
                    Peta GIS
                </button>
                <button type="button" id="btn-mode-art" class="rounded-full px-3.5 py-1 text-muted hover:text-primary transition-all cursor-pointer">
                    Peta Vektor
                </button>
            </div>

            <a href="{{ route('peta') }}" aria-current="page" class="hidden md:inline-flex items-center justify-center rounded-full bg-accent px-5 py-1.5 text-xs font-bold text-white shadow-xs">
                Peta
            </a>

            {{-- Toggle Sidebar Mobile --}}
            <button type="button" id="btn-toggle-sidebar" aria-label="Buka/Tutup Menu"
                    class="lg:hidden flex size-9 items-center justify-center rounded-full bg-surface-mint text-primary-dark hover:bg-primary hover:text-white transition-colors">
                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
            </button>
        </div>
    </header>

    {{-- ================= MAIN CONTENT WRAPPER ================= --}}
    <main class="flex-1 relative flex overflow-hidden">
        {{-- ================= SIDEBAR UTAMA ================= --}}
        <aside id="map-sidebar"
               class="absolute lg:relative inset-y-0 left-0 w-full sm:w-[440px] lg:w-[475px] h-full flex flex-col bg-white border-r border-primary/15 z-20 shadow-xl lg:shadow-none transition-transform duration-300 ease-in-out">
            
            {{-- Top Colored Notebook Bar ala Referensi Estetik --}}
            <div class="bg-gradient-to-r from-primary-dark via-primary to-[#198c8c] px-4 py-2 flex items-center justify-between text-white shrink-0">
                <div class="flex items-center gap-1.5">
                    <span class="size-2 rounded-full bg-white/70"></span>
                    <span class="size-2 rounded-full bg-white/40"></span>
                    <span class="size-2 rounded-full bg-white/20"></span>
                </div>
                <span class="text-[10px] font-extrabold tracking-[0.18em] uppercase text-white/95">
                    PUSKESMAP SPATIAL NOTE · KOTA BOGOR
                </span>
                <span class="text-[10px] font-bold text-white/80">25 Unit</span>
            </div>

            {{-- SUB-PANEL 1: PETA MASYARAKAT & REKOMENDASI MAUT --}}
            <div id="panel-public" class="flex-1 flex flex-col h-full overflow-hidden">
                {{-- Tab Navigasi Sub-Sidebar --}}
                <div class="flex border-b border-dotted border-primary/25 bg-surface-mint/40 px-4 pt-2.5 gap-2 text-xs font-extrabold shrink-0">
                    <button type="button" id="subtab-btn-faskes" class="subtab-btn pb-2.5 px-3 border-b-2 border-primary text-primary transition-all cursor-pointer">
                        Daftar Faskes (25)
                    </button>
                    <button type="button" id="subtab-btn-maut" class="subtab-btn pb-2.5 px-3 border-b-2 border-transparent text-muted hover:text-primary transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="size-2 rounded-full bg-accent animate-ping"></span>
                        <span>Rekomendasi MAUT</span>
                    </button>
                </div>

                {{-- KONTEN SUBTAB A: DAFTAR PUSKESMAS & FILTER --}}
                <div id="subtab-content-faskes" class="flex-1 flex flex-col overflow-hidden">
                    {{-- Header Pencarian & Filter Cepat --}}
                    <div class="p-4 bg-white border-b border-dotted border-primary/25 space-y-3 shrink-0">
                        {{-- Search Input Debounce 300ms --}}
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 size-4 text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/>
                            </svg>
                            <input type="text" id="sidebar-search" placeholder="Cari nama puskesmas, kelurahan, jalan..."
                                   class="w-full h-10 rounded-full bg-input pl-10 pr-9 text-xs text-ink placeholder:text-placeholder focus:ring-2 focus:ring-primary focus:outline-none border-0">
                            <button type="button" id="btn-clear-search" aria-label="Hapus pencarian" class="hidden absolute right-3 top-1/2 -translate-y-1/2 text-muted hover:text-ink">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Filter Chips Layanan --}}
                        <div class="flex items-center gap-1.5 overflow-x-auto no-scrollbar pb-1 text-xs font-semibold">
                            <button type="button" data-filter-chip="all" class="filter-chip shrink-0 rounded-full px-3 py-1 bg-primary text-white border border-primary transition-colors cursor-pointer">
                                Semua
                            </button>
                            <button type="button" data-filter-chip="igd" class="filter-chip shrink-0 rounded-full px-3 py-1 bg-surface-mint text-primary-dark hover:bg-primary/20 border border-primary/20 transition-colors cursor-pointer">
                                IGD 24 Jam
                            </button>
                            <button type="button" data-filter-chip="inap" class="filter-chip shrink-0 rounded-full px-3 py-1 bg-surface-mint text-primary-dark hover:bg-primary/20 border border-primary/20 transition-colors cursor-pointer">
                                Rawat Inap
                            </button>
                            <button type="button" data-filter-chip="nakes" class="filter-chip shrink-0 rounded-full px-3 py-1 bg-surface-mint text-primary-dark hover:bg-primary/20 border border-primary/20 transition-colors cursor-pointer">
                                Nakes Lengkap (100%)
                            </button>
                        </div>

                        {{-- Dropdown Kecamatan & Geolocation Radius --}}
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <div>
                                <select id="select-district" class="w-full h-9 rounded-full bg-input px-3 text-xs font-semibold text-ink border-0 focus:ring-2 focus:ring-primary cursor-pointer">
                                    @foreach ($districts as $d)
                                        <option value="{{ $d }}">{{ $d === 'Semua' ? 'Semua Kecamatan' : 'Kec. ' . $d }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <select id="select-radius" class="w-full h-9 rounded-full bg-input px-3 text-xs font-semibold text-ink border-0 focus:ring-2 focus:ring-primary cursor-pointer">
                                    <option value="all">Radius Bebas</option>
                                    <option value="1000">Radius 1 km</option>
                                    <option value="3000" selected>Radius 3 km</option>
                                    <option value="5000">Radius 5 km</option>
                                </select>
                            </div>
                        </div>

                        {{-- Tombol Lokasi GPS & Pilih Titik Manual di Peta (SRS US-05 / FR-05) --}}
                        <div class="grid grid-cols-2 gap-2 text-xs">
                            <button type="button" id="btn-geo-radius"
                                    class="h-8 rounded-full bg-surface-mint text-primary-dark hover:bg-primary hover:text-white font-bold flex items-center justify-center gap-1.5 transition-colors cursor-pointer border border-primary/20">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                                <span>Lokasi Saya</span>
                            </button>
                            <button type="button" id="btn-pick-point"
                                    class="h-8 rounded-full bg-white text-primary-dark hover:bg-surface-mint font-bold flex items-center justify-center gap-1.5 transition-colors cursor-pointer border border-primary">
                                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                                <span id="pick-point-label">Pilih Titik Peta</span>
                            </button>
                        </div>
                    </div>

                    {{-- Counter Bar --}}
                    <div class="px-4 py-2 bg-surface-mint/60 border-b border-dotted border-primary/20 flex items-center justify-between text-[11px] text-muted font-medium shrink-0">
                        <span>Menampilkan <strong id="puskesmas-counter" class="text-primary-dark">{{ count($puskesmas) }}</strong> puskesmas</span>
                        <span id="ref-coord-badge" class="text-primary font-bold">Pusat Kota Bogor</span>
                    </div>

                    {{-- List Kartu Puskesmas dengan Grid-Paper Background & Rounded-2xl Cards --}}
                    @php
                        $pastelColors = ['bg-card-mint', 'bg-card-peach', 'bg-card-green', 'bg-card-salmon', 'bg-card-blue'];
                    @endphp
                    <div id="puskesmas-list" class="flex-1 overflow-y-auto p-4 space-y-3.5 bg-[linear-gradient(to_right,#edf6f4_1px,transparent_1px),linear-gradient(to_bottom,#edf6f4_1px,transparent_1px)] bg-[size:22px_22px] bg-[#f8fcfb] no-scrollbar">
                        @foreach ($puskesmas as $index => $item)
                            @php
                                $pastelBg = $pastelColors[$index % count($pastelColors)];
                            @endphp
                            <article data-faskes-card
                                     data-id="{{ $item['id'] }}"
                                     data-name="{{ $item['name'] }}"
                                     data-district="{{ $item['district'] }}"
                                     data-address="{{ $item['address'] }}"
                                     data-lat="{{ $item['lat'] }}"
                                     data-lng="{{ $item['lng'] }}"
                                     data-igd="{{ $item['igd'] ? '1' : '0' }}"
                                     data-inap="{{ $item['inap'] ? '1' : '0' }}"
                                     data-open="{{ $item['is_open'] ? '1' : '0' }}"
                                     data-nakes="{{ $item['nakes_score'] }}"
                                     data-crowd="{{ $item['crowd'] }}"
                                     data-distance="{{ $item['distance'] }}"
                                     class="group rounded-2xl bg-white overflow-hidden shadow-card hover:shadow-float border border-primary/15 hover:border-primary/40 transition-all cursor-pointer">
                                
                                {{-- Pastel Header Strip (Design System 2.2) --}}
                                <div class="{{ $pastelBg }} px-3.5 py-2.5 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="flex size-6 items-center justify-center rounded-full bg-white/85 text-primary-dark text-xs font-extrabold shadow-2xs">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-[11px] font-extrabold text-primary-dark uppercase tracking-wide">
                                            Kec. {{ $item['district'] }}
                                        </span>
                                    </div>
                                    <span data-card-distance class="rounded-full bg-white/90 px-2.5 py-0.5 text-[11px] font-extrabold text-primary-dark shadow-2xs">
                                        {{ $item['distance'] }}
                                    </span>
                                </div>

                                <div class="p-3.5">
                                    <h3 class="text-sm font-bold text-ink group-hover:text-primary transition-colors leading-snug">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p class="text-xs text-muted mt-1 line-clamp-1">
                                        📍 {{ $item['address'] }}
                                    </p>

                                    <div class="mt-2.5 flex flex-wrap gap-1.5">
                                        <x-tag>{{ $item['hours'] }}</x-tag>
                                        @if ($item['igd'])
                                            <x-tag class="bg-accent/15 text-accent-strong">IGD 24 jam</x-tag>
                                        @endif
                                        @if ($item['inap'])
                                            <x-tag>Rawat Inap</x-tag>
                                        @endif
                                        <x-tag>9 Nakes: {{ $item['nakes_score'] }}%</x-tag>
                                    </div>

                                    {{-- Crowd Bar (Design System 3.16) --}}
                                    <x-crowd-bar :level="$item['crowd']" :label="'Estimasi keramaian: ' . $item['crowd']" />

                                    <div class="mt-3.5 grid grid-cols-2 gap-2">
                                        <button type="button" data-focus-btn
                                                class="h-8 rounded-full bg-primary text-white text-[11px] font-bold hover:bg-primary-dark transition-colors flex items-center justify-center gap-1 cursor-pointer">
                                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                                            <span>Fokus di Peta</span>
                                        </button>
                                        <button type="button" data-detail-modal-btn="{{ $item['id'] }}"
                                                class="h-8 rounded-full border border-primary text-primary hover:bg-surface-mint text-[11px] font-bold transition-colors flex items-center justify-center gap-1 cursor-pointer">
                                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span>Detail Lengkap</span>
                                        </button>
                                    </div>
                                </div>
                            </article>
                        @endforeach

                        <div id="sidebar-empty" class="hidden text-center py-12 px-4">
                            <div class="size-12 rounded-full bg-surface-mint mx-auto flex items-center justify-center text-primary text-xl">
                                🔍
                            </div>
                            <h4 class="mt-3 text-sm font-bold text-ink">Puskesmas tidak ditemukan</h4>
                            <p class="text-xs text-muted mt-1">Coba ubah kata kunci atau ganti filter kecamatan.</p>
                        </div>
                    </div>
                </div>

                {{-- KONTEN SUBTAB B: MESIN REKOMENDASI MULTIKRITERIA MAUT (Tabel 3.3 & 3.4 SRS) --}}
                <div id="subtab-content-maut" class="flex-1 flex flex-col overflow-hidden hidden bg-[#f8faf9]">
                    <div class="p-4 bg-white border-b border-gray-100 space-y-3 shrink-0">
                        <div class="flex items-center gap-2">
                            <div class="size-7 rounded-full bg-accent/20 flex items-center justify-center text-accent font-bold text-sm">
                                🎯
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-ink">Sistem Rekomendasi MAUT</h3>
                                <p class="text-[11px] text-muted">Multi-Attribute Utility Theory (5 Kriteria)</p>
                            </div>
                        </div>

                        {{-- Pilihan 3 Skenario Pembobotan (SRS Tabel 3.4) --}}
                        <div>
                            <label class="text-[11px] font-bold text-ink block mb-1.5">Pilih Skenario Kebutuhan:</label>
                            <div class="space-y-1.5 text-xs">
                                <label class="flex items-center gap-2.5 p-2 rounded-lg bg-surface-mint/60 hover:bg-surface-mint cursor-pointer border border-primary/20 transition-colors">
                                    <input type="radio" name="maut-scenario" value="darurat" checked class="text-accent focus:ring-accent">
                                    <div>
                                        <span class="font-bold text-ink block">🚨 Kedaruratan Medis</span>
                                        <span class="text-[10px] text-muted block">Jarak (40%), IGD 24 Jam (30%), Nakes (15%), Jam (15%)</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-2.5 p-2 rounded-lg bg-white hover:bg-surface-mint/40 cursor-pointer border border-gray-200 transition-colors">
                                    <input type="radio" name="maut-scenario" value="reguler" class="text-accent focus:ring-accent">
                                    <div>
                                        <span class="font-bold text-ink block">🩺 Layanan Umum Reguler</span>
                                        <span class="text-[10px] text-muted block">Jam Buka (30%), Jarak (20%), Nakes (20%), Keramaian (20%)</span>
                                    </div>
                                </label>

                                <label class="flex items-center gap-2.5 p-2 rounded-lg bg-white hover:bg-surface-mint/40 cursor-pointer border border-gray-200 transition-colors">
                                    <input type="radio" name="maut-scenario" value="pemda" class="text-accent focus:ring-accent">
                                    <div>
                                        <span class="font-bold text-ink block">📋 Evaluasi Kebijakan Pemda</span>
                                        <span class="text-[10px] text-muted block">Kelengkapan 9 Nakes (30%), Jarak (20%), Keramaian (20%)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <button type="button" id="btn-run-maut"
                                class="w-full h-10 rounded-full bg-accent hover:bg-accent-strong text-white text-xs font-bold transition-all shadow-md flex items-center justify-center gap-2 cursor-pointer active:scale-98">
                            <span>Hitung Rekomendasi Terbaik</span>
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>

                    {{-- Hasil Rekomendasi Top 3 MAUT --}}
                    <div id="maut-results" class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar">
                        <div class="text-xs font-bold text-primary-dark flex items-center justify-between pb-1">
                            <span>Top 3 Puskesmas Rekomendasi</span>
                            <span class="text-[10px] text-muted font-normal">Normalisasi Min-Max</span>
                        </div>
                        <div id="maut-cards-container" class="space-y-3">
                            {{-- Diisi secara dinamis oleh JavaScript MAUT --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUB-PANEL 2: DASBOR ANALITIK SPASIAL DINAS KESEHATAN (SDSS) --}}
            <div id="panel-dinkes" class="flex-1 flex flex-col h-full overflow-hidden hidden bg-[#f8faf9]">
                {{-- Header Analitik Eksekutif --}}
                <div class="p-4 bg-white border-b border-gray-100 shrink-0 space-y-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-bold text-primary-dark">Instrumen SDSS Dinas Kesehatan</h3>
                            <p class="text-[11px] text-muted">Permenkes No. 43/2019 & Beban Wilayah</p>
                        </div>
                        <span class="rounded-full bg-primary/10 text-primary px-2.5 py-0.5 text-[10px] font-bold">
                            Tahun 2026
                        </span>
                    </div>

                    {{-- 4 Kartu Indikator Ringkas --}}
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div class="p-2.5 rounded-lg bg-surface-mint border border-primary/20">
                            <span class="text-[10px] text-muted block">Total Faskes</span>
                            <span class="text-base font-extrabold text-primary-dark">{{ $citySummary['total_puskesmas'] }} Puskesmas</span>
                            <span class="text-[10px] text-primary block mt-0.5">IGD 24 Jam: <strong>{{ $citySummary['total_igd'] }}</strong></span>
                        </div>
                        <div class="p-2.5 rounded-lg bg-blue-50 border border-blue-200">
                            <span class="text-[10px] text-muted block">Kepatuhan 9 Nakes</span>
                            <span class="text-base font-extrabold text-blue-900">{{ $citySummary['avg_nakes_compliance'] }}%</span>
                            <span class="text-[10px] text-blue-700 block mt-0.5">Rawat Inap: <strong>{{ $citySummary['total_inap'] }}</strong></span>
                        </div>
                    </div>

                    {{-- Kontrol Layer Analitik Spasial --}}
                    <div class="pt-2 border-t border-gray-100 space-y-2 text-xs">
                        <span class="font-bold text-ink block text-[11px]">Lapisan Analitik Spasial (Layers):</span>
                        
                        <label class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border border-gray-200 cursor-pointer">
                            <span class="text-xs font-semibold">Cakupan Buffer Layanan (1,5 km)</span>
                            <input type="checkbox" id="layer-buffer-15" class="rounded text-primary focus:ring-primary size-4">
                        </label>

                        <label class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border border-gray-200 cursor-pointer">
                            <span class="text-xs font-semibold">Cakupan Buffer Standar (3,0 km)</span>
                            <input type="checkbox" id="layer-buffer-30" checked class="rounded text-primary focus:ring-primary size-4">
                        </label>

                        <label class="flex items-center justify-between p-2 rounded-lg bg-gray-50 border border-gray-200 cursor-pointer">
                            <span class="text-xs font-semibold">Peta Beban Kerja (PSW-Index)</span>
                            <input type="checkbox" id="layer-psw-index" class="rounded text-primary focus:ring-primary size-4">
                        </label>
                    </div>

                    {{-- Tombol Ekspor Data CSV --}}
                    <div class="grid grid-cols-2 gap-2 pt-1">
                        <button type="button" id="btn-export-csv" class="h-8 rounded-md bg-white border border-gray-300 text-ink text-xs font-bold hover:bg-gray-50 transition-colors flex items-center justify-center gap-1 shadow-xs">
                            <svg class="size-3.5 text-muted" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            <span>Unduh CSV</span>
                        </button>
                        <button type="button" onclick="window.print()" class="h-8 rounded-md bg-primary text-white text-xs font-bold hover:bg-primary-dark transition-colors flex items-center justify-center gap-1 shadow-xs">
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak Laporan</span>
                        </button>
                    </div>
                </div>

                {{-- Tabel Rasio Kependudukan & Beban per Kecamatan --}}
                <div class="flex-1 overflow-y-auto p-4 space-y-4 no-scrollbar">
                    <div>
                        <h4 class="text-xs font-bold text-ink uppercase tracking-wider mb-2">Evaluasi Rasio Penduduk (Permenkes 43/2019)</h4>
                        <div class="rounded-lg bg-white border border-gray-200 overflow-hidden shadow-xs text-xs">
                            <table class="w-full text-left">
                                <thead class="bg-gray-50 border-b border-gray-200 text-[10px] text-muted uppercase">
                                    <tr>
                                        <th class="p-2">Kecamatan</th>
                                        <th class="p-2">Populasi</th>
                                        <th class="p-2">Ada</th>
                                        <th class="p-2">Target</th>
                                        <th class="p-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100">
                                    @foreach ($districtStats as $ds)
                                        <tr class="hover:bg-gray-50/50">
                                            <td class="p-2 font-bold">{{ $ds['name'] }}</td>
                                            <td class="p-2 text-muted">{{ number_format($ds['population'], 0, ',', '.') }}</td>
                                            <td class="p-2 font-bold text-primary">{{ $ds['puskesmas_count'] }}</td>
                                            <td class="p-2 text-muted">{{ $ds['target_count'] }}</td>
                                            <td class="p-2">
                                                <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-bold {{ $ds['ratio_status'] === 'Memenuhi' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                                    {{ $ds['ratio_status'] }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Legenda Indeks Beban Spasial Wilayah (PSW-Index) --}}
                    <div class="rounded-lg bg-white p-3 border border-gray-200 shadow-xs space-y-2">
                        <span class="text-xs font-bold text-ink block">Spektrum PSW-Index (Spatial Workload):</span>
                        <div class="space-y-1.5 text-[11px]">
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-accent shrink-0"></span>
                                <span class="font-bold text-ink">Beban Kerja Tinggi:</span>
                                <span class="text-muted">Kec. Bogor Selatan, Bogor Utara, Tanah Sareal</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="size-3 rounded-full bg-primary shrink-0"></span>
                                <span class="font-bold text-ink">Beban Ideal:</span>
                                <span class="text-muted">Kec. Bogor Barat, Bogor Tengah, Bogor Timur</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ================= MAP CANVAS ================= --}}
        <section class="flex-1 h-full relative overflow-hidden bg-[#eaf1e4]">
            {{-- 1. Leaflet Interactive GIS Map Container --}}
            <div id="leaflet-map" class="absolute inset-0 w-full h-full z-10"></div>

            {{-- 2. Illustrated Vector Art Map Container (Mode Alternatif) --}}
            <div id="vector-map-view" class="absolute inset-0 w-full h-full z-10 hidden bg-[#eaf4f7] overflow-auto flex items-center justify-center p-4 sm:p-8">
                <div class="w-full max-w-[660px] bg-white rounded-xl p-6 shadow-float border-2 border-primary">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold text-primary-dark">Peta Vektor Wilayah Kota Bogor</h2>
                            <p class="text-xs text-muted">Klik salah satu kecamatan untuk melihat daftar puskesmas</p>
                        </div>
                        <span class="rounded-full bg-surface-mint px-3 py-1 text-xs font-bold text-primary">6 Kecamatan</span>
                    </div>
                    <x-bogor-map class="w-full" />
                </div>
            </div>

            {{-- Banner Instruksi Mode Pilih Titik di Peta (US-05 / FR-05) --}}
            <div id="pick-point-banner" class="hidden absolute top-16 left-1/2 -translate-x-1/2 z-30">
                <div class="rounded-full bg-accent text-white px-5 py-2 text-xs font-bold shadow-float flex items-center gap-2 animate-bounce">
                    <span>📍 Klik di mana saja pada peta untuk menetapkan titik lokasi referensi Anda</span>
                    <button type="button" id="btn-cancel-pick" class="ml-1 rounded-full bg-white/20 hover:bg-white/30 px-2 py-0.5 text-[10px] cursor-pointer">Batal</button>
                </div>
            </div>

            {{-- Floating Hashtag Filter Chips di Atas Peta (Design System 3.9) --}}
            <div class="absolute top-4 left-1/2 -translate-x-1/2 z-20 hidden md:flex items-center gap-2 bg-white/90 backdrop-blur-md px-3 py-1.5 rounded-full shadow-float border border-primary/15">
                <button type="button" data-map-chip="all" aria-pressed="true"
                        class="map-hashtag-chip rounded-full px-3.5 py-1 text-xs font-semibold text-ink bg-white border border-gray-200 transition-colors hover:border-primary aria-pressed:bg-primary aria-pressed:text-white aria-pressed:border-primary cursor-pointer">
                    #Semua
                </button>
                <button type="button" data-map-chip="igd" aria-pressed="false"
                        class="map-hashtag-chip rounded-full px-3.5 py-1 text-xs font-semibold text-ink bg-white border border-gray-200 transition-colors hover:border-primary aria-pressed:bg-primary aria-pressed:text-white aria-pressed:border-primary cursor-pointer">
                    #IGD24Jam
                </button>
                <button type="button" data-map-chip="inap" aria-pressed="false"
                        class="map-hashtag-chip rounded-full px-3.5 py-1 text-xs font-semibold text-ink bg-white border border-gray-200 transition-colors hover:border-primary aria-pressed:bg-primary aria-pressed:text-white aria-pressed:border-primary cursor-pointer">
                    #RawatInap
                </button>
                <button type="button" data-map-chip="nakes" aria-pressed="false"
                        class="map-hashtag-chip rounded-full px-3.5 py-1 text-xs font-semibold text-ink bg-white border border-gray-200 transition-colors hover:border-primary aria-pressed:bg-primary aria-pressed:text-white aria-pressed:border-primary cursor-pointer">
                    #NakesLengkap
                </button>
            </div>

            {{-- Floating Puskesmas Info Card Overlay (Design System 3.13) --}}
            <aside id="map-floating-card" aria-label="Ringkasan Puskesmas Terpilih"
                   class="absolute right-4 top-16 sm:top-4 z-20 w-[285px] rounded-lg bg-white/95 backdrop-blur-md p-4 shadow-float border border-primary/15 transition-all duration-200">
                <div class="flex items-start justify-between gap-2">
                    <div>
                        <span id="float-card-badge" class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[10px] font-bold text-primary-dark mb-1">
                            Sorotan Puskesmas
                        </span>
                        <h3 id="float-card-name" class="text-sm font-bold text-ink leading-snug">Puskesmas Tanah Sareal</h3>
                        <p id="float-card-sub" class="text-xs text-muted mt-0.5">Kec. Tanah Sareal · 1,1 km</p>
                    </div>
                    <button type="button" id="btn-close-float-card" aria-label="Tutup kartu"
                            class="size-6 rounded-full bg-gray-100 hover:bg-gray-200 text-muted hover:text-ink flex items-center justify-center text-xs cursor-pointer">
                        ✕
                    </button>
                </div>

                <div id="float-card-tags" class="mt-2.5 flex flex-wrap gap-1.5">
                    <x-tag>Buka 08.00 – 16.00</x-tag>
                    <x-tag>IGD 24 jam</x-tag>
                    <x-tag>Rawat Inap</x-tag>
                </div>

                <div class="mt-3">
                    <div class="flex items-center justify-between text-[11px] text-muted mb-1">
                        <span>Estimasi keramaian bulan ini</span>
                        <strong id="float-card-crowd-text" class="text-primary-dark">Sedang</strong>
                    </div>
                    <div class="h-1.5 w-full rounded-full bg-gray-200 overflow-hidden">
                        <div id="float-card-crowd-bar" class="h-full rounded-full bg-primary w-2/3 transition-all duration-300"></div>
                    </div>
                </div>

                <div class="mt-3.5 space-y-2">
                    <button type="button" id="float-card-detail-btn"
                            class="w-full h-8 rounded-full bg-primary text-white text-xs font-semibold hover:bg-primary-dark transition-colors flex items-center justify-center cursor-pointer shadow-xs">
                        Lihat detail
                    </button>
                    <a id="float-card-route-btn" href="https://www.google.com/maps/dir/?api=1&destination=-6.5612,106.7915" target="_blank" rel="noopener noreferrer"
                       class="w-full h-8 rounded-full border border-primary bg-white text-primary text-xs font-semibold hover:bg-surface-mint transition-colors flex items-center justify-center">
                        Rute Google Maps
                    </a>
                </div>
            </aside>

            {{-- Floating Map Controls di Kanan Bawah (Design System 3.14: Circular 40px Teal Buttons) --}}
            <div class="absolute right-5 bottom-8 z-20 flex flex-col gap-2.5">
                <button type="button" id="gis-locate-me" aria-label="Lokasi saya" title="Gunakan Lokasi GPS Saya"
                        class="flex size-10 items-center justify-center rounded-full bg-primary text-white shadow-float hover:bg-primary-dark transition-transform active:scale-95 cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                </button>
                <button type="button" id="gis-pick-point" aria-label="Pilih titik di peta" title="Klik Titik Referensi di Peta"
                        class="flex size-10 items-center justify-center rounded-full bg-white text-primary-dark border-2 border-primary shadow-float hover:bg-surface-mint transition-transform active:scale-95 cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                </button>
                <button type="button" id="gis-zoom-in" aria-label="Perbesar peta" title="Perbesar (+)"
                        class="flex size-10 items-center justify-center rounded-full bg-primary text-white shadow-float hover:bg-primary-dark text-lg font-bold transition-transform active:scale-95 cursor-pointer">
                    +
                </button>
                <button type="button" id="gis-zoom-out" aria-label="Perkecil peta" title="Perkecil (−)"
                        class="flex size-10 items-center justify-center rounded-full bg-primary text-white shadow-float hover:bg-primary-dark text-lg font-bold transition-transform active:scale-95 cursor-pointer">
                    −
                </button>
                <button type="button" id="gis-reset-view" aria-label="Reset tampilan peta" title="Reset ke Pusat Kota Bogor"
                        class="flex size-10 items-center justify-center rounded-full bg-primary-dark text-white shadow-float hover:bg-primary transition-transform active:scale-95 cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </button>
            </div>

            {{-- Floating Legenda Peta di Kiri Bawah --}}
            <div class="absolute left-4 bottom-6 z-20 hidden sm:block">
                <div class="bg-white/95 backdrop-blur-md rounded-lg p-3 shadow-float border border-primary/15 text-[11px] space-y-1.5">
                    <div class="font-bold text-primary-dark text-xs flex items-center gap-1.5">
                        <span class="size-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Legenda Peta Kota Bogor</span>
                    </div>
                    <div class="flex items-center gap-2 text-ink">
                        <span class="size-3 rounded-full bg-[#E11D2E] inline-block border border-white shadow-2xs"></span>
                        <span>Puskesmas Induk (25 Unit)</span>
                    </div>
                    <div class="flex items-center gap-2 text-ink">
                        <span class="size-3 rounded-full bg-accent inline-block border border-white shadow-2xs"></span>
                        <span>Rekomendasi Terbaik / Sorotan</span>
                    </div>
                    <div class="flex items-center gap-2 text-ink">
                        <span class="size-3 rounded-full bg-primary inline-block border border-white shadow-2xs"></span>
                        <span>Titik Referensi & Radius Jangkauan</span>
                    </div>
                </div>
            </div>
        </section>
    </main>

    {{-- ================= MODAL DETAIL PUSKESMAS LENGKAP (FR-02 & FR-12) ================= --}}
    <div id="modal-faskes-detail" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-[560px] max-h-[90vh] bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col animate-in fade-in zoom-in-95 duration-200">
            <div class="p-4 bg-surface-mint border-b border-primary/20 flex items-center justify-between">
                <div>
                    <span id="modal-code" class="text-[10px] font-bold uppercase tracking-wider text-primary">P32710101</span>
                    <h3 id="modal-name" class="text-base font-bold text-primary-dark leading-tight">Puskesmas Tanah Sareal</h3>
                </div>
                <button type="button" id="btn-close-modal" aria-label="Tutup" class="size-8 rounded-full bg-white/80 hover:bg-white text-muted hover:text-ink flex items-center justify-center transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 overflow-y-auto space-y-4 text-xs no-scrollbar">
                {{-- Alamat & Kontak --}}
                <div class="rounded-xl bg-gray-50 p-3 space-y-1.5">
                    <div class="flex items-start gap-2">
                        <span class="text-muted shrink-0">📍 Alamat:</span>
                        <span id="modal-address" class="font-semibold text-ink">Jl. Kesehatan No. 1, Kota Bogor</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-muted shrink-0">📞 Kontak:</span>
                        <span id="modal-phone" class="font-semibold text-ink">(0251) 8332943</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-muted shrink-0">⏰ Jam Buka:</span>
                        <span id="modal-hours" class="font-semibold text-ink">Buka 08.00 – 16.00</span>
                    </div>
                </div>

                {{-- Status Layanan --}}
                <div>
                    <h4 class="font-bold text-ink text-[11px] uppercase tracking-wider mb-2">Kesiapan Fasilitas Layanan</h4>
                    <div id="modal-tags" class="flex flex-wrap gap-1.5">
                        {{-- Diisi dinamis --}}
                    </div>
                </div>

                {{-- Standar 9 Profesi Tenaga Kesehatan (Permenkes No. 43/2019) --}}
                <div class="rounded-xl border border-gray-200 p-3.5 space-y-2 bg-white">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-ink text-xs">Kelengkapan 9 Profesi Nakes (Permenkes 43/2019)</h4>
                        <span id="modal-nakes-badge" class="rounded-full bg-blue-100 text-blue-800 px-2 py-0.5 text-[10px] font-bold">100% Terpenuhi</span>
                    </div>
                    <div id="modal-nakes-grid" class="grid grid-cols-3 gap-2 pt-1 text-[11px]">
                        {{-- Diisi dinamis --}}
                    </div>
                </div>

                {{-- Beban Wilayah Binaan (UKS & Posyandu) --}}
                <div class="grid grid-cols-2 gap-2 text-center">
                    <div class="p-3 rounded-xl bg-surface-mint/60 border border-primary/20">
                        <span class="text-xs text-muted block">Posyandu Binaan</span>
                        <span id="modal-posyandu" class="text-base font-extrabold text-primary-dark block mt-0.5">32</span>
                    </div>
                    <div class="p-3 rounded-xl bg-surface-mint/60 border border-primary/20">
                        <span class="text-xs text-muted block">Sekolah Binaan UKS</span>
                        <span id="modal-school" class="text-base font-extrabold text-primary-dark block mt-0.5">24</span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 flex items-center justify-between gap-3 shrink-0">
                <a id="modal-official-link" href="https://kotabogor.go.id" target="_blank" rel="noopener noreferrer"
                   class="h-9 px-4 rounded-full border border-primary text-primary hover:bg-surface-mint text-xs font-bold transition-colors inline-flex items-center gap-1.5">
                    <span>Situs Resmi Kota Bogor</span>
                    <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </a>
                <a id="modal-route-link" href="#" target="_blank" rel="noopener noreferrer"
                   class="h-9 px-5 rounded-full bg-primary text-white hover:bg-primary-dark text-xs font-bold transition-colors inline-flex items-center gap-1.5 shadow-sm">
                    <span>Petunjuk Rute Maps</span>
                    <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
                </a>
            </div>
        </div>
    </div>

    {{-- Pass PHP Data to JS via JSON script tag --}}
    <script id="puskesmas-data" type="application/json">
        {!! json_encode($puskesmas) !!}
    </script>
    <script id="district-data" type="application/json">
        {!! json_encode($districtStats) !!}
    </script>
</x-layout>
