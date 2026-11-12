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
