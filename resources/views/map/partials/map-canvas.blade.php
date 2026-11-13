        {{-- Kolom 2: Kanvas Peta WebGIS Interaktif --}}
        <section class="flex-1 h-full relative p-0 sm:p-4 bg-white flex flex-col overflow-hidden">
            <div class="relative flex-1 w-full h-full sm:rounded-[24px] overflow-hidden sm:border border-gray-100 shadow-[0_8px_30px_rgba(15,23,42,0.05)] bg-[#eef3f1]">
                {{-- Leaflet Interactive Street Map Container --}}
                <div id="leaflet-map" class="absolute inset-0 w-full h-full z-10"></div>

                {{-- Banner Instruksi Mode Pilih Titik di Peta (US-05 / FR-05) --}}
                <div id="pick-point-banner" class="hidden absolute top-3 sm:top-16 inset-x-3 sm:inset-x-auto sm:left-1/2 sm:-translate-x-1/2 z-30">
                    <div class="rounded-2xl sm:rounded-full bg-accent text-white px-4 sm:px-5 py-2 text-[11px] sm:text-xs font-extrabold shadow-float flex items-center justify-between sm:justify-center gap-2 animate-bounce">
                        <span>Ketuk peta untuk titik acuan</span>
                        <button type="button" id="btn-cancel-pick" class="ml-1 rounded-full bg-white/20 hover:bg-white/30 px-2.5 py-0.5 text-[10px] shrink-0 cursor-pointer">Batal</button>
                    </div>
                </div>

                {{-- Tombol Tampilkan / Sembunyikan Semua Panel --}}
                <button type="button" id="btn-expand-map" title="Tampilkan Panel Filter & Daftar" aria-label="Tampilkan atau Sembunyikan Panel"
                        class="hidden lg:flex absolute right-4 top-4 z-20 size-10 items-center justify-center rounded-xl bg-white text-ink shadow-float border border-gray-100 hover:bg-ink hover:text-white transition-colors cursor-pointer">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l6.75 6.75m0 0h-6m6 0v-6M20.25 10.5L13.5 3.75m0 0h6m-6 0v6"/></svg>
                </button>

                {{-- Floating Puskesmas Info Card Overlay (tersembunyi sampai puskesmas dipilih) --}}
                <aside id="map-floating-card" aria-label="Ringkasan Puskesmas Terpilih"
                       class="hidden absolute inset-x-3 bottom-16 sm:inset-x-auto sm:bottom-auto sm:right-4 sm:top-16 z-20 sm:w-[270px] rounded-2xl bg-white/95 backdrop-blur-md p-3.5 shadow-[0_20px_50px_rgba(15,23,42,0.18)] border border-gray-100 transition-all duration-200">
                    {{-- Header Visual Mini --}}
                    <div class="hidden sm:flex relative h-24 w-full rounded-xl bg-gradient-to-br from-surface-mint via-card-mint to-surface-sky overflow-hidden p-2.5 flex-col justify-between mb-3">
                        <div class="flex items-center justify-between z-10">
                            <span id="float-card-badge" class="inline-block rounded-full bg-white/90 px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark shadow-2xs">
                                Terpilih
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
                            Detail
                        </button>
                        <a id="float-card-route-btn" href="https://www.google.com/maps/dir/?api=1&destination=-6.5612,106.7915" target="_blank" rel="noopener noreferrer"
                           class="h-8.5 rounded-xl bg-primary text-white hover:bg-primary-dark text-[11px] font-extrabold transition-colors flex items-center justify-center">
                            Rute
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
                    <span>Acuan: <strong id="ref-coord-badge" class="text-primary">Pusat Kota Bogor</strong></span>
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
