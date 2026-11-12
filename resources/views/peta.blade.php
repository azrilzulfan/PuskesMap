<x-layout title="Peta Interaktif Puskesmas Kota Bogor - PuskesMap" body-class="h-dvh w-screen overflow-hidden bg-[#f4f7f6] flex flex-col text-ink font-sans">
    {{-- Header Bar --}}
    <header class="shrink-0 bg-white border-b border-gray-100 px-3.5 py-2.5 sm:py-0 sm:h-[68px] sm:px-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2.5 sm:gap-4 z-30">
        {{-- Baris Atas di HP / Sisi Kiri-Kanan di Desktop --}}
        <div class="flex items-center justify-between sm:contents">
            {{-- Brand Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0 group" aria-label="PuskesMap Beranda">
                <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-7 sm:h-8 w-auto transition-transform group-hover:scale-105">
                <span class="text-base sm:text-lg font-extrabold text-primary-dark tracking-tight">PuskesMap</span>
            </a>

            {{-- Kanan Header (Tampil Sebaris dengan Logo di HP, di Ujung Kanan pada Desktop) --}}
            <div class="flex items-center gap-1.5 sm:gap-3 shrink-0 sm:order-3">
                {{-- Pill Pemilih Kecamatan (Desktop/Tablet) --}}
                <div class="hidden md:flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-full bg-gray-50/90 border border-gray-100">
                    <span class="flex size-7 items-center justify-center rounded-full bg-accent/15 text-accent">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </span>
                    <select id="select-district" aria-label="Filter Kecamatan"
                            class="bg-transparent text-xs font-extrabold text-ink focus:outline-none cursor-pointer pr-1">
                        @foreach ($districts as $d)
                            <option value="{{ $d }}">{{ $d === 'Semua' ? 'Semua Kecamatan' : 'Kec. ' . $d }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Pill Pemilih Mode (Daftar Faskes vs SPK MAUT) --}}
                <div class="flex items-center rounded-full bg-gray-100/90 p-0.5 sm:p-1 border border-gray-200/60 text-[11px] sm:text-xs font-extrabold">
                    <button type="button" id="tab-nav-faskes"
                            class="nav-mode-btn rounded-full px-3 sm:px-3.5 py-1 sm:py-1.5 bg-primary text-white shadow-2xs transition-all flex items-center gap-1.5 cursor-pointer">
                        <span class="size-1.5 sm:size-2 rounded-full bg-white"></span>
                        <span class="hidden sm:inline">Daftar Faskes</span>
                        <span class="sm:hidden">Faskes</span>
                    </button>
                    <button type="button" id="tab-nav-maut"
                            class="nav-mode-btn rounded-full px-3 sm:px-3.5 py-1 sm:py-1.5 text-ink hover:text-primary transition-all flex items-center gap-1 cursor-pointer">
                        <svg class="size-3.5 text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Rekomendasi</span>
                    </button>
                </div>

                {{-- Toggle Sidebar Daftar di Tablet/Mobile --}}
                <button type="button" id="btn-toggle-sidebar" aria-label="Buka Daftar Puskesmas"
                        class="hidden sm:flex lg:hidden size-9 items-center justify-center rounded-xl bg-surface-mint text-primary-dark hover:bg-primary hover:text-white transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h10"/></svg>
                </button>

                <div class="h-6 w-px bg-gray-200 hidden sm:block"></div>

                {{-- Tombol Kembali ke Beranda --}}
                <a href="{{ route('home') }}" title="Kembali ke Beranda" aria-label="Kembali ke Beranda"
                   class="flex size-8 sm:size-9 items-center justify-center rounded-xl bg-gray-100/80 hover:bg-gray-200/80 text-ink transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </a>
            </div>
        </div>

        {{-- Baris Kedua di HP / Tengah di Desktop: Search Input & Tombol Filter --}}
        <div class="flex items-center gap-2 flex-1 max-w-xl min-w-0 sm:order-2">
            <div class="relative flex-1 flex items-center min-w-0">
                <svg class="absolute left-3.5 size-4 text-muted pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/>
                </svg>
                <input type="text" id="sidebar-search"
                       placeholder="Cari puskesmas, kelurahan, atau kecamatan..."
                       class="w-full h-9 sm:h-10 rounded-xl bg-gray-100/90 hover:bg-gray-100 focus:bg-white pl-9 sm:pl-10 pr-8 text-xs sm:text-sm font-bold text-ink placeholder:text-muted/70 placeholder:font-medium focus:ring-2 focus:ring-primary/25 focus:outline-none border border-transparent focus:border-primary/30 transition-all">
                <button type="button" id="btn-clear-search" aria-label="Hapus pencarian"
                        class="hidden absolute right-2.5 size-5 rounded-full bg-gray-200 hover:bg-gray-300 text-muted hover:text-ink flex items-center justify-center text-xs cursor-pointer">
                    <svg class="size-2.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Tombol Buka Drawer Filter & Radius di HP --}}
            <button type="button" id="btn-open-left-filter"
                    class="lg:hidden shrink-0 inline-flex h-9 items-center gap-1.5 rounded-xl bg-ink text-white px-3 text-xs font-extrabold shadow-2xs cursor-pointer">
                <svg class="size-3.5 text-accent" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/></svg>
                <span>Filter</span>
            </button>
        </div>
    </header>

    {{-- ================= BAR CHIP FILTER CEPAT KHUSUS HP & TABLET ================= --}}
    <div class="lg:hidden shrink-0 bg-white border-b border-gray-100 px-3.5 py-2 flex items-center gap-2 overflow-x-auto no-scrollbar z-20">
        {{-- Pemilih Kecamatan di HP --}}
        <div class="md:hidden shrink-0 flex items-center rounded-full bg-surface-mint border border-primary/20 px-3 py-1">
            <select id="select-district-mobile" aria-label="Pilih Kecamatan"
                    class="bg-transparent text-[11px] font-extrabold text-primary-dark focus:outline-none">
                @foreach ($districts as $d)
                    <option value="{{ $d }}">{{ $d === 'Semua' ? 'Semua Kec.' : 'Kec. ' . $d }}</option>
                @endforeach
            </select>
        </div>

        {{-- Chip Filter Layanan Cepat di HP --}}
        <button type="button" data-map-chip="all" aria-pressed="true"
                class="map-hashtag-chip shrink-0 rounded-full px-3 py-1 text-[11px] font-bold text-ink bg-gray-100 transition-colors aria-pressed:bg-primary aria-pressed:text-white cursor-pointer">
            #Semua
        </button>
        <button type="button" data-map-chip="igd" aria-pressed="false"
                class="map-hashtag-chip shrink-0 rounded-full px-3 py-1 text-[11px] font-bold text-ink bg-gray-100 transition-colors aria-pressed:bg-primary aria-pressed:text-white cursor-pointer">
            #IGD24Jam
        </button>
        <button type="button" data-map-chip="inap" aria-pressed="false"
                class="map-hashtag-chip shrink-0 rounded-full px-3 py-1 text-[11px] font-bold text-ink bg-gray-100 transition-colors aria-pressed:bg-primary aria-pressed:text-white cursor-pointer">
            #RawatInap
        </button>
        <button type="button" data-map-chip="nakes" aria-pressed="false"
                class="map-hashtag-chip shrink-0 rounded-full px-3 py-1 text-[11px] font-bold text-ink bg-gray-100 transition-colors aria-pressed:bg-primary aria-pressed:text-white cursor-pointer">
            #Nakes100%
        </button>
    </div>

    {{-- ================= MAIN 3-COLUMN WORKSPACE (Referensi 1 & 2) ================= --}}
    <main class="flex-1 relative flex overflow-hidden bg-white">
        {{-- Backdrop Gelap Saat Drawer HP Dibuka --}}
        <div id="mobile-drawer-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-30 hidden lg:hidden"></div>

        {{-- ================= KOLOM 1 (KIRI): PANEL FILTER & KONTROL SPASIAL (Drawer di HP, Kolom Tetap di Desktop) ================= --}}
        <aside id="left-filter-panel"
               class="fixed lg:relative inset-y-0 left-0 -translate-x-full lg:translate-x-0 flex w-[85vw] max-w-[310px] lg:w-[275px] xl:w-[295px] shrink-0 h-full flex-col justify-between border-r border-gray-100 bg-white px-5 sm:px-6 py-5 overflow-y-auto no-scrollbar select-none z-40 lg:z-20 shadow-2xl lg:shadow-none transition-transform duration-300 ease-in-out">
            <div class="space-y-6">
                {{-- Header Drawer Mobile --}}
                <div class="flex lg:hidden items-center justify-between pb-3 border-b border-gray-100">
                    <span class="text-sm font-extrabold text-ink">Filter &amp; Parameter Spasial</span>
                    <button type="button" id="btn-close-left-filter" aria-label="Tutup Filter"
                            class="flex size-7 items-center justify-center rounded-full bg-gray-100 text-ink text-xs font-bold cursor-pointer">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                {{-- Status Tampilan Peta --}}
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-sm font-extrabold text-ink">Map</span>
                        <span class="text-sm font-medium text-muted ml-0.5">View</span>
                    </div>
                    <div class="flex h-7 w-12 items-center justify-end rounded-full bg-gray-100 p-1 border border-gray-200/70">
                        <span class="flex size-5 items-center justify-center rounded-md bg-accent text-white shadow-2xs">
                            <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                        </span>
                    </div>
                </div>

                {{-- Kategori Mode Tampilan --}}
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-ink mb-2.5">Kategori Mode</h2>
                    <div class="grid grid-cols-2 gap-2.5">
                        <button type="button" id="subtab-btn-faskes"
                                class="subtab-btn h-11 rounded-xl bg-ink text-white text-xs font-extrabold flex items-center justify-center gap-2 shadow-xs transition-all cursor-pointer">
                            <span class="size-2 rounded-full bg-accent"></span>
                            <span>Faskes (25)</span>
                        </button>
                        <button type="button" id="subtab-btn-maut"
                                class="subtab-btn h-11 rounded-xl bg-white border border-gray-200 text-ink hover:border-primary text-xs font-extrabold flex items-center justify-center gap-1.5 transition-all cursor-pointer">
                            <svg class="size-3.5 text-accent" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>Rekomendasi</span>
                        </button>
                    </div>
                </div>

                {{-- Kriteria Layanan --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-ink">Filter Layanan</h2>
                        <span class="text-[11px] font-bold text-primary">Multikriteria</span>
                    </div>
                    <div class="space-y-2.5 text-xs">
                        <button type="button" data-filter-chip="all"
                                class="filter-chip is-active w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-bold text-ink group-hover:text-primary transition-colors">Semua Puskesmas Induk</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md bg-ink text-white transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>

                        <button type="button" data-filter-chip="igd"
                                class="filter-chip w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-semibold text-muted group-hover:text-ink transition-colors">Siaga IGD 24 Jam (11)</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md border border-gray-300 bg-white text-transparent transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>

                        <button type="button" data-filter-chip="inap"
                                class="filter-chip w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-semibold text-muted group-hover:text-ink transition-colors">Rawat Inap &amp; PONED</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md border border-gray-300 bg-white text-transparent transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>

                        <button type="button" data-filter-chip="nakes"
                                class="filter-chip w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-semibold text-muted group-hover:text-ink transition-colors">9 Profesi Nakes (100%)</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md border border-gray-300 bg-white text-transparent transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Radius Jangkauan Geodetik --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-ink">Radius</span>
                            <span class="text-xs font-medium text-muted ml-0.5">Jangkauan</span>
                        </div>
                        <select id="select-radius" aria-label="Pilih Radius Jangkauan"
                                class="rounded-lg bg-gray-50 border border-gray-200 px-2 py-1 text-[11px] font-bold text-ink focus:outline-none cursor-pointer">
                            <option value="1000">1,0 km</option>
                            <option value="3000" selected>3,0 km</option>
                            <option value="5000">5,0 km</option>
                            <option value="all">Semua</option>
                        </select>
                    </div>

                    {{-- Histogram Dekoratif & Track Slider --}}
                    <div class="pt-2 px-1">
                        <div class="flex items-end justify-between gap-1.5 h-8 px-3">
                            <span class="w-full h-3 rounded-t bg-gray-200"></span>
                            <span class="w-full h-5 rounded-t bg-primary/40"></span>
                            <span class="w-full h-8 rounded-t bg-primary"></span>
                            <span class="w-full h-6 rounded-t bg-primary"></span>
                            <span class="w-full h-7 rounded-t bg-primary/60"></span>
                            <span class="w-full h-4 rounded-t bg-gray-200"></span>
                            <span class="w-full h-2 rounded-t bg-gray-100"></span>
                        </div>
                        <div class="relative h-0.5 w-full bg-ink/80 flex items-center justify-between">
                            <span class="size-4 rounded-md bg-ink text-white shadow-xs flex items-center justify-center"><span class="size-1 rounded-full bg-white"></span></span>
                            <span class="size-4 rounded-md bg-ink text-white shadow-xs flex items-center justify-center"><span class="size-1 rounded-full bg-white"></span></span>
                        </div>
                        <div class="mt-2.5 flex items-center justify-between text-[11px]">
                            <span class="font-bold text-ink">1 km – 5 km</span>
                            <span class="text-muted">Aktif: <strong id="radius-label-val" class="text-ink">3 km</strong></span>
                        </div>
                    </div>
                </div>

                {{-- Skenario Prioritas Pencarian --}}
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <div>
                            <span class="text-xs font-extrabold uppercase tracking-wider text-ink">Prioritas</span>
                            <span class="text-xs font-medium text-muted ml-0.5">Lokasi</span>
                        </div>
                        <span class="text-[10px] font-bold text-accent">5 Kriteria</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" data-quick-scenario="darurat"
                                class="quick-scenario-btn h-9 rounded-xl bg-ink text-white text-[11px] font-extrabold flex items-center justify-center gap-1.5 shadow-2xs transition-all cursor-pointer">
                            <svg class="size-3 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>Darurat</span>
                        </button>
                        <button type="button" data-quick-scenario="reguler"
                                class="quick-scenario-btn h-9 rounded-xl bg-white border border-gray-200 text-ink hover:border-primary text-[11px] font-bold flex items-center justify-center transition-all cursor-pointer">
                            Reguler
                        </button>
                        <button type="button" data-quick-scenario="pemda"
                                class="quick-scenario-btn h-9 rounded-xl bg-white border border-gray-200 text-ink hover:border-primary text-[11px] font-bold flex items-center justify-center transition-all cursor-pointer">
                            Pemda
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kartu Informasi Titik Acuan --}}
            <div class="mt-6 rounded-2xl bg-[#fef6e6] border border-[#f5e4c3] p-4">
                <div class="flex items-center gap-3">
                    <div class="flex size-11 shrink-0 items-center justify-center rounded-2xl bg-[#f5ab35]/20 text-[#d97706]">
                        <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xs font-extrabold text-ink leading-snug">Titik Referensi Presisi</h3>
                        <p class="text-[11px] text-muted leading-tight mt-0.5">Ukur jarak geodetik dari posisi Anda</p>
                    </div>
                </div>
                <div class="mt-3.5 grid grid-cols-2 gap-2">
                    <button type="button" id="btn-geo-radius"
                            class="h-8 rounded-xl bg-[#f5b841] hover:bg-[#e5a62e] text-ink text-[11px] font-extrabold transition-colors flex items-center justify-center gap-1 shadow-2xs cursor-pointer">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                        <span>GPS Saya</span>
                    </button>
                    <button type="button" id="btn-pick-point"
                            class="h-8 rounded-xl bg-white hover:bg-gray-50 text-ink border border-[#e6d0a7] text-[11px] font-extrabold transition-colors flex items-center justify-center gap-1 cursor-pointer">
                        <span id="pick-point-label">Pilih di Peta</span>
                    </button>
                </div>
            </div>

            {{-- Tombol Terapkan Filter (Khusus Mobile Drawer) --}}
            <button type="button" id="btn-apply-left-filter-mobile"
                    class="lg:hidden mt-4 w-full h-11 rounded-xl bg-primary text-white text-xs font-extrabold shadow-xs hover:bg-primary-dark transition-colors flex items-center justify-center gap-2 shrink-0 cursor-pointer">
                <span>Terapkan &amp; Lihat Peta</span>
                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
            </button>
        </aside>

        {{-- Kolom 2: Kanvas Peta WebGIS Interaktif --}}
        <section class="flex-1 h-full relative p-0 sm:p-4 bg-white flex flex-col overflow-hidden">
            <div class="relative flex-1 w-full h-full sm:rounded-[24px] overflow-hidden sm:border border-gray-100 shadow-[0_8px_30px_rgba(15,23,42,0.05)] bg-[#eef3f1]">
                {{-- Leaflet Interactive Street Map Container --}}
                <div id="leaflet-map" class="absolute inset-0 w-full h-full z-10"></div>

                {{-- Banner Instruksi Mode Pilih Titik di Peta (US-05 / FR-05) --}}
                <div id="pick-point-banner" class="hidden absolute top-3 sm:top-16 inset-x-3 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 z-30">
                    <div class="rounded-2xl sm:rounded-full bg-accent text-white px-4 sm:px-5 py-2 text-[11px] sm:text-xs font-extrabold shadow-float flex items-center justify-between sm:justify-center gap-2 animate-bounce">
                        <span>Ketuk peta untuk menetapkan titik lokasi acuan</span>
                        <button type="button" id="btn-cancel-pick" class="ml-1 rounded-full bg-white/20 hover:bg-white/30 px-2.5 py-0.5 text-[10px] shrink-0 cursor-pointer">Batal</button>
                    </div>
                </div>

                {{-- Tombol Perluas Peta --}}
                <button type="button" id="btn-expand-map" title="Perluas / Kembalikan Ukuran Peta" aria-label="Perluas Peta"
                        class="hidden lg:flex absolute right-4 top-4 z-20 size-10 items-center justify-center rounded-xl bg-white text-ink shadow-float border border-gray-100 hover:bg-ink hover:text-white transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l6.75 6.75m0 0h-6m6 0v-6M20.25 10.5L13.5 3.75m0 0h6m-6 0v6"/></svg>
                </button>

                {{-- Floating Puskesmas Info Card Overlay --}}
                <aside id="map-floating-card" aria-label="Ringkasan Puskesmas Terpilih"
                       class="hidden sm:block absolute inset-x-3 bottom-16 sm:inset-x-auto sm:bottom-auto sm:right-4 sm:top-16 z-20 sm:w-[270px] rounded-2xl bg-white/95 backdrop-blur-md p-3.5 shadow-[0_20px_50px_rgba(15,23,42,0.18)] border border-gray-100 transition-all duration-200">
                    {{-- Header Visual Mini --}}
                    <div class="hidden sm:flex relative h-24 w-full rounded-xl bg-gradient-to-br from-surface-mint via-card-mint to-surface-sky overflow-hidden p-2.5 flex-col justify-between mb-3">
                        <div class="flex items-center justify-between z-10">
                            <span id="float-card-badge" class="inline-block rounded-full bg-white/90 px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark shadow-2xs">
                                Sorotan Puskesmas
                            </span>
                            <button type="button" id="btn-close-float-card" aria-label="Tutup kartu"
                                    class="size-6 rounded-full bg-white/90 hover:bg-white text-ink flex items-center justify-center text-[11px] font-bold shadow-2xs cursor-pointer">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>

                        {{-- Ilustrasi Mini Faskes --}}
                        <div class="my-auto flex items-center justify-center">
                            <div class="flex size-9 items-center justify-center rounded-xl bg-white/85 text-primary shadow-2xs">
                                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M12 7v4m-2-2h4"/></svg>
                            </div>
                        </div>

                        <div class="hidden">
                            <span id="float-card-crowd-text">Sedang</span>
                            <div id="float-card-crowd-bar"></div>
                        </div>
                    </div>

                    {{-- Judul, Skor Nakes & Tombol Tutup Mobile --}}
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <h3 id="float-card-name" class="text-sm font-extrabold text-ink leading-snug truncate">Puskesmas Tanah Sareal</h3>
                            <p id="float-card-sub" class="text-[11px] font-medium text-muted mt-0.5 truncate">Kec. Tanah Sareal · 1,1 km</p>
                        </div>
                        <div class="flex items-center gap-1.5 shrink-0">
                            <span class="inline-flex items-center gap-1 rounded-full bg-surface-mint px-2 py-0.5 text-[11px] font-extrabold text-primary-dark">
                                <svg class="size-3 text-primary" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                <span id="float-card-nakes">100%</span>
                            </span>
                            <button type="button" id="btn-close-float-card-mobile" aria-label="Tutup kartu"
                                    class="sm:hidden flex size-6 items-center justify-center rounded-full bg-gray-100 text-ink text-xs font-bold">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    <div id="float-card-tags" class="mt-2 flex flex-wrap gap-1">
                        <x-tag>Buka 08.00 – 16.00</x-tag>
                        <x-tag>IGD 24 jam</x-tag>
                    </div>

                    <div class="mt-2.5 sm:mt-3 grid grid-cols-2 gap-2">
                        <button type="button" id="float-card-detail-btn"
                                class="h-8.5 rounded-xl bg-gray-100 hover:bg-ink hover:text-white text-ink text-[11px] font-extrabold transition-colors flex items-center justify-center cursor-pointer">
                            Lihat detail
                        </button>
                        <a id="float-card-route-btn" href="https://www.google.com/maps/dir/?api=1&destination=-6.5612,106.7915" target="_blank" rel="noopener noreferrer"
                           class="h-8.5 rounded-xl bg-primary text-white hover:bg-primary-dark text-[11px] font-extrabold transition-colors flex items-center justify-center">
                            Rute Maps
                        </a>
                    </div>
                </aside>

                {{-- Kontrol Zoom & Lokasi --}}
                <div class="absolute right-3 top-3 sm:right-auto sm:top-auto sm:left-4 sm:bottom-4 z-20 flex flex-col gap-1.5 sm:gap-2">
                    <div class="flex flex-col rounded-2xl bg-white/95 backdrop-blur-md shadow-float border border-gray-100 overflow-hidden">
                        <button type="button" id="gis-zoom-in" aria-label="Perbesar peta" title="Perbesar (+)"
                                class="flex size-9 sm:size-10 items-center justify-center text-ink hover:bg-gray-50 text-base font-extrabold border-b border-gray-100 cursor-pointer">
                            +
                        </button>
                        <button type="button" id="gis-zoom-out" aria-label="Perkecil peta" title="Perkecil (−)"
                                class="flex size-9 sm:size-10 items-center justify-center text-ink hover:bg-gray-50 text-base font-extrabold cursor-pointer">
                            &minus;
                        </button>
                    </div>

                    <button type="button" id="gis-locate-me" aria-label="Lokasi saya" title="Gunakan Lokasi GPS Saya"
                            class="flex size-9 sm:size-10 items-center justify-center rounded-2xl bg-white/95 backdrop-blur-md text-ink shadow-float border border-gray-100 hover:bg-primary hover:text-white transition-colors cursor-pointer">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                    </button>

                    <button type="button" id="gis-pick-point" aria-label="Pilih titik di peta" title="Klik Titik Referensi di Peta"
                            class="flex size-9 sm:size-10 items-center justify-center rounded-2xl bg-white/95 backdrop-blur-md text-ink shadow-float border border-gray-100 hover:bg-accent hover:text-white transition-colors cursor-pointer">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </button>

                    <button type="button" id="gis-reset-view" aria-label="Reset tampilan peta" title="Reset ke Pusat Kota Bogor"
                            class="flex size-9 sm:size-10 items-center justify-center rounded-2xl bg-white/95 backdrop-blur-md text-ink shadow-float border border-gray-100 hover:bg-primary-dark hover:text-white transition-colors cursor-pointer">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3"/></svg>
                    </button>
                </div>

                {{-- Pill Tengah Bawah (Desktop/Tablet) --}}
                <div class="hidden sm:flex absolute bottom-4 left-1/2 -translate-x-1/2 z-20 items-center gap-2.5 rounded-2xl bg-white px-4 py-2.5 shadow-float border border-gray-100 text-xs font-extrabold text-ink">
                    <span class="flex size-5 items-center justify-center rounded-md bg-accent text-white shadow-2xs">
                        <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </span>
                    <span>Titik Referensi: <strong id="ref-coord-badge" class="text-primary">Pusat Kota Bogor</strong></span>
                </div>

                {{-- Bilah Aksi Bawah Mobile --}}
                <div class="flex lg:hidden absolute inset-x-3 bottom-3 z-20 items-center justify-between gap-2 rounded-2xl bg-ink/95 backdrop-blur-md p-1.5 text-white shadow-2xl border border-white/15">
                    <button type="button" id="mobile-dock-faskes"
                            class="flex-1 h-10 rounded-xl bg-white/15 hover:bg-white/25 text-xs font-extrabold flex items-center justify-center gap-1.5 cursor-pointer">
                        <span class="size-2 rounded-full bg-emerald-400"></span>
                        <span>Daftar (<span id="mobile-dock-counter">{{ count($puskesmas) }}</span>)</span>
                    </button>
                    <button type="button" id="mobile-dock-maut"
                            class="flex-1 h-10 rounded-xl bg-accent hover:bg-accent-strong text-white text-xs font-extrabold flex items-center justify-center gap-1.5 shadow-xs cursor-pointer">
                        <svg class="size-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        <span>Rekomendasi</span>
                    </button>
                    <button type="button" id="mobile-dock-filter"
                            class="px-3.5 h-10 rounded-xl bg-white/15 hover:bg-white/25 text-xs font-extrabold flex items-center justify-center gap-1.5 cursor-pointer">
                        <span>Radius</span>
                    </button>
                </div>
            </div>
        </section>

        {{-- Kolom 3: Daftar Puskesmas & Rekomendasi MAUT --}}
        <aside id="map-sidebar"
               class="fixed lg:relative inset-y-0 right-0 translate-x-full lg:translate-x-0 w-full sm:w-[360px] xl:w-[390px] shrink-0 h-full flex flex-col bg-white border-l border-gray-100 z-30 shadow-2xl lg:shadow-none transition-transform duration-300 ease-in-out">

            {{-- SUB-PANEL 1: DAFTAR FASKES & REKOMENDASI MAUT --}}
            <div id="panel-public" class="flex-1 flex flex-col h-full overflow-hidden">
                {{-- Header Sidebar Hasil --}}
                <div class="px-5 pt-5 pb-3.5 bg-white flex items-center justify-between shrink-0 border-b border-gray-100">
                    <div class="flex items-baseline gap-1.5">
                        <strong id="puskesmas-counter" class="text-base font-extrabold text-ink">{{ count($puskesmas) }}</strong>
                        <span class="text-sm font-medium text-muted">Puskesmas</span>
                    </div>

                    <div class="flex items-center gap-2">
                        <select id="select-sort" aria-label="Urutkan Puskesmas"
                                class="bg-transparent text-xs font-extrabold text-ink focus:outline-none cursor-pointer">
                            <option value="distance">Terdekat dulu</option>
                            <option value="nakes">Nakes Lengkap</option>
                            <option value="igd">Prioritas IGD 24 Jam</option>
                        </select>

                        {{-- Tombol Tutup Drawer di Mobile --}}
                        <button type="button" id="btn-close-sidebar-mobile" aria-label="Tutup Daftar"
                                class="lg:hidden flex size-8 items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-ink transition-colors cursor-pointer">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Subtab A: Daftar Kartu Puskesmas --}}
                <div id="subtab-content-faskes" class="flex-1 flex flex-col overflow-hidden">
                    @php
                        $pastelColors = ['bg-card-mint', 'bg-card-peach', 'bg-card-green', 'bg-card-salmon', 'bg-card-blue'];
                    @endphp
                    <div id="puskesmas-list" class="flex-1 overflow-y-auto px-5 py-4 space-y-5 bg-white no-scrollbar">
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
                                     class="group cursor-pointer transition-all">

                                {{-- Banner Visual Puskesmas --}}
                                <div class="relative h-[136px] w-full rounded-2xl {{ $pastelBg }} overflow-hidden p-3.5 flex flex-col justify-between transition-transform duration-200 group-hover:scale-[1.01]">
                                    {{-- Ornamen Arsitektur Gedung Puskesmas di Latar Banner --}}
                                    <svg class="pointer-events-none absolute -bottom-2 right-6 h-28 w-44 text-primary-dark/10" viewBox="0 0 180 110" fill="currentColor" aria-hidden="true">
                                        <rect x="20" y="25" width="140" height="85" rx="8"/>
                                        <rect x="65" y="8" width="50" height="25" rx="5"/>
                                        <rect x="35" y="42" width="22" height="22" rx="3" fill="white" fill-opacity="0.45"/>
                                        <rect x="79" y="42" width="22" height="22" rx="3" fill="white" fill-opacity="0.45"/>
                                        <rect x="123" y="42" width="22" height="22" rx="3" fill="white" fill-opacity="0.45"/>
                                    </svg>

                                    <div class="relative z-10 flex items-center justify-between">
                                        <span class="rounded-full bg-white/90 backdrop-blur-xs px-3 py-1 text-[10px] font-extrabold text-ink shadow-2xs">
                                             Kec. {{ $item['district'] }}
                                        </span>

                                        {{-- Tombol Sorot Peta --}}
                                        <button type="button" data-focus-btn title="Sorot di Peta"
                                                class="flex size-8 items-center justify-center rounded-full {{ $item['igd'] ? 'bg-accent text-white' : 'bg-white/90 text-ink' }} shadow-md transition-transform group-hover:scale-110 cursor-pointer">
                                            @if ($item['igd'])
                                                <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/></svg>
                                            @else
                                                <span class="text-[11px] font-extrabold">#{{ $index + 1 }}</span>
                                            @endif
                                        </button>
                                    </div>

                                    {{-- Ikon Faskes --}}
                                    <div class="relative z-10 my-auto flex items-center gap-3">
                                        <div class="flex size-11 items-center justify-center rounded-2xl bg-white/90 text-primary shadow-xs">
                                            <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M12 7v4m-2-2h4"/></svg>
                                        </div>
                                        <div>
                                            <span class="inline-block rounded-md bg-white/80 px-2 py-0.5 text-[10px] font-extrabold text-primary-dark">
                                                {{ $item['igd'] ? 'IGD 24 Jam' : 'Rawat Jalan' }}
                                            </span>
                                            @if ($item['inap'])
                                                <span class="inline-block rounded-md bg-primary-dark/85 px-2 py-0.5 text-[10px] font-bold text-white ml-1">
                                                    Rawat Inap
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="relative z-10 flex items-center justify-between text-[10px] font-bold text-ink/75">
                                        <span>{{ $item['address'] }}</span>
                                    </div>
                                </div>

                                {{-- Judul dan Skor Nakes --}}
                                <div class="mt-2.5 flex items-center justify-between gap-2 px-0.5">
                                    <h3 class="text-[15px] font-extrabold text-ink group-hover:text-primary transition-colors truncate">
                                        {{ $item['name'] }}
                                    </h3>
                                    <div class="flex items-center gap-1 shrink-0 text-xs font-extrabold text-ink">
                                        <span class="flex size-4 items-center justify-center rounded-full bg-surface-mint text-primary">
                                            <svg class="size-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        </span>
                                        <span>{{ $item['nakes_score'] }}%</span>
                                    </div>
                                </div>

                                {{-- Baris Meta Kedua (Responsif Tanpa Terpotong di HP) --}}
                                <div class="mt-1 flex flex-wrap items-center justify-between gap-x-2 gap-y-1 text-xs px-0.5">
                                    <div class="flex flex-wrap items-center gap-x-1.5 gap-y-0.5 text-muted font-medium">
                                        <strong data-card-distance class="font-extrabold text-ink">{{ $item['distance'] }}</strong>
                                        <span>&bull;</span>
                                        <span>{{ $item['hours'] }}</span>
                                    </div>

                                    <button type="button" data-detail-modal-btn="{{ $item['id'] }}"
                                            class="text-[11px] font-extrabold text-primary hover:underline shrink-0 cursor-pointer">
                                        Detail &rarr;
                                    </button>
                                </div>
                            </article>
                        @endforeach

                        <div id="sidebar-empty" class="hidden text-center py-12 px-4">
                            <div class="size-12 rounded-full bg-surface-mint mx-auto flex items-center justify-center text-primary">
                                <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            <h4 class="mt-3 text-sm font-extrabold text-ink">Puskesmas tidak ditemukan</h4>
                            <p class="text-xs text-muted mt-1">Coba ubah kata kunci atau ganti filter layanan di sebelah kiri.</p>
                        </div>
                    </div>
                </div>

                {{-- KONTEN SUBTAB B: MESIN REKOMENDASI MULTIKRITERIA MAUT (Tabel 3.3 & 3.4 SRS) --}}
                <div id="subtab-content-maut" class="flex-1 flex flex-col overflow-hidden hidden bg-[#f8faf9]">
                    <div class="p-4 bg-white border-b border-gray-100 space-y-3 shrink-0">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="size-8 rounded-xl bg-accent/15 flex items-center justify-center text-accent">
                                    <svg class="size-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-extrabold text-ink">Rekomendasi Puskesmas</h3>
                                    <p class="text-[11px] text-muted">Urutan terbaik dari lokasi Anda saat ini</p>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('subtab-btn-faskes')?.click()"
                                    class="text-[11px] font-bold text-primary hover:underline cursor-pointer">
                                Semua Faskes
                            </button>
                        </div>

                        {{-- Pilihan 3 Skenario Pembobotan (SRS Tabel 3.4) --}}
                        <div class="space-y-1.5 text-xs">
                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-surface-mint/60 hover:bg-surface-mint cursor-pointer border border-primary/20 transition-colors">
                                <input type="radio" name="maut-scenario" value="darurat" checked class="text-accent focus:ring-accent">
                                <div>
                                    <span class="font-extrabold text-ink block">Kedaruratan Medis</span>
                                    <span class="text-[10px] text-muted block">Jarak (40%), IGD 24 Jam (30%), Nakes (15%), Jam (15%)</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white hover:bg-surface-mint/40 cursor-pointer border border-gray-200 transition-colors">
                                <input type="radio" name="maut-scenario" value="reguler" class="text-accent focus:ring-accent">
                                <div>
                                    <span class="font-extrabold text-ink block">Layanan Umum Reguler</span>
                                    <span class="text-[10px] text-muted block">Jam Buka (30%), Jarak (20%), Nakes (20%), Keramaian (20%)</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white hover:bg-surface-mint/40 cursor-pointer border border-gray-200 transition-colors">
                                <input type="radio" name="maut-scenario" value="pemda" class="text-accent focus:ring-accent">
                                <div>
                                    <span class="font-extrabold text-ink block">Evaluasi Kebijakan Pemda</span>
                                    <span class="text-[10px] text-muted block">Kelengkapan 9 Nakes (30%), Jarak (20%), Keramaian (20%)</span>
                                </div>
                            </label>
                        </div>

                        <button type="button" id="btn-run-maut"
                                class="w-full h-10 rounded-xl bg-ink hover:bg-primary text-white text-xs font-extrabold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                            <span>Hitung Rekomendasi Terbaik</span>
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>

                    {{-- Hasil Rekomendasi Top 3 MAUT --}}
                    <div id="maut-results" class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar">
                        <div class="text-xs font-extrabold text-ink flex items-center justify-between pb-1">
                            <span>Top 3 Puskesmas Terdekat</span>
                            <span class="text-[10px] text-muted font-semibold">Dari Lokasi Anda</span>
                        </div>
                        <div id="maut-cards-container" class="space-y-3">
                            {{-- Diisi secara dinamis oleh JavaScript MAUT --}}
                        </div>
                    </div>
                </div>
            </div>
        </aside>
    </main>

    {{-- ================= MODAL DETAIL PUSKESMAS LENGKAP (FR-02 & FR-12) ================= --}}
    <div id="modal-faskes-detail" class="fixed inset-0 z-50 hidden bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
        <div class="w-full max-w-[560px] max-h-[90vh] bg-white rounded-3xl shadow-2xl overflow-hidden flex flex-col">
            <div class="p-5 bg-surface-mint border-b border-primary/15 flex items-center justify-between">
                <div>
                    <span id="modal-code" class="text-[10px] font-extrabold uppercase tracking-wider text-primary">P32710101</span>
                    <h3 id="modal-name" class="text-lg font-extrabold text-primary-dark leading-tight">Puskesmas Tanah Sareal</h3>
                </div>
                <button type="button" id="btn-close-modal" aria-label="Tutup" class="size-8 rounded-full bg-white hover:bg-gray-100 text-ink flex items-center justify-center transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="p-5 overflow-y-auto space-y-4 text-xs no-scrollbar">
                <div class="rounded-2xl bg-gray-50 p-3.5 space-y-1.5">
                    <div class="flex items-start gap-2">
                        <span class="text-muted shrink-0">Alamat:</span>
                        <span id="modal-address" class="font-bold text-ink">Jl. Kesehatan No. 1, Kota Bogor</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-muted shrink-0">Kontak:</span>
                        <span id="modal-phone" class="font-bold text-ink">(0251) 8332943</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-muted shrink-0">Jam Layanan:</span>
                        <span id="modal-hours" class="font-bold text-ink">Buka 08.00 – 16.00</span>
                    </div>
                </div>

                <div>
                    <h4 class="font-extrabold text-ink text-[11px] uppercase tracking-wider mb-2">Kesiapan Fasilitas Layanan</h4>
                    <div id="modal-tags" class="flex flex-wrap gap-1.5"></div>
                </div>

                <div class="rounded-2xl border border-gray-200 p-3.5 sm:p-4 space-y-2 bg-white">
                    <div class="flex flex-wrap items-center justify-between gap-1.5">
                        <h4 class="font-extrabold text-ink text-xs">Kelengkapan 9 Profesi Nakes (Permenkes 43/2019)</h4>
                        <span id="modal-nakes-badge" class="rounded-full bg-surface-mint text-primary-dark px-2.5 py-0.5 text-[10px] font-extrabold">100% Terpenuhi</span>
                    </div>
                    <div id="modal-nakes-grid" class="grid grid-cols-2 sm:grid-cols-3 gap-2 pt-1 text-[11px]"></div>
                </div>

                <div class="grid grid-cols-2 gap-2.5 text-center">
                    <div class="p-3.5 rounded-2xl bg-surface-mint/60 border border-primary/15">
                        <span class="text-xs text-muted block">Posyandu Binaan</span>
                        <span id="modal-posyandu" class="text-lg font-extrabold text-primary-dark block mt-0.5">32</span>
                    </div>
                    <div class="p-3.5 rounded-2xl bg-surface-mint/60 border border-primary/15">
                        <span class="text-xs text-muted block">Sekolah Binaan UKS</span>
                        <span id="modal-school" class="text-lg font-extrabold text-primary-dark block mt-0.5">24</span>
                    </div>
                </div>
            </div>

            <div class="p-4 bg-gray-50 border-t border-gray-100 grid grid-cols-2 sm:flex sm:items-center sm:justify-between gap-2.5 shrink-0">
                <a id="modal-official-link" href="https://kotabogor.go.id" target="_blank" rel="noopener noreferrer"
                   class="h-10 px-3 sm:px-4 rounded-full border border-gray-300 text-ink hover:bg-white text-[11px] sm:text-xs font-extrabold transition-colors inline-flex items-center justify-center gap-1.5">
                    <span>Portal Resmi</span>
                </a>
                <a id="modal-route-link" href="#" target="_blank" rel="noopener noreferrer"
                   class="h-10 px-3 sm:px-5 rounded-full bg-primary text-white hover:bg-primary-dark text-[11px] sm:text-xs font-extrabold transition-colors inline-flex items-center justify-center gap-1.5 shadow-sm">
                    <span>Rute Maps &rarr;</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Pass PHP Data to JS via JSON script tag --}}
    <script id="puskesmas-data" type="application/json">
        {!! json_encode($puskesmas) !!}
    </script>
</x-layout>
