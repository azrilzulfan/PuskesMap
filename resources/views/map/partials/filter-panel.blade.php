        {{-- ================= KOLOM 1 (KIRI): PANEL FILTER & KONTROL SPASIAL (Tersembunyi Default, Muncul Saat Dibutuhkan) ================= --}}
        <aside id="left-filter-panel"
               class="fixed lg:relative inset-y-0 left-0 -translate-x-full lg:translate-x-0 lg:hidden flex w-[85vw] max-w-[310px] lg:w-[275px] xl:w-[295px] shrink-0 h-full flex-col justify-between border-r border-gray-100 bg-white px-5 sm:px-6 py-5 overflow-y-auto no-scrollbar select-none z-40 lg:z-20 shadow-2xl lg:shadow-none transition-transform duration-300 ease-in-out">
            <div class="space-y-6">
                {{-- Header Panel & Tombol Tutup --}}
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <span class="text-sm font-extrabold text-ink">Filter Peta</span>
                    <button type="button" id="btn-close-left-filter" aria-label="Tutup Filter"
                            class="flex size-7 items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-ink text-xs font-bold transition-colors cursor-pointer">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                {{-- Kategori Mode Tampilan --}}
                <div>
                    <h2 class="text-xs font-extrabold uppercase tracking-wider text-ink mb-2.5">Mode</h2>
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

                {{-- Kriteria Layanan (Multi-select) --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h2 class="text-xs font-extrabold uppercase tracking-wider text-ink">Layanan</h2>
                        <span id="filter-active-badge" class="text-[11px] font-bold text-primary">Multi-pilih</span>
                    </div>
                    <div class="space-y-2.5 text-xs" role="group" aria-label="Filter Kriteria Layanan Puskesmas">
                        <button type="button" data-filter-chip="all" aria-pressed="true"
                                class="filter-chip is-active w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-bold text-ink group-hover:text-primary transition-colors">Semua Puskesmas</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md bg-ink text-white transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>

                        <button type="button" data-filter-chip="igd" aria-pressed="false"
                                class="filter-chip w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-semibold text-muted group-hover:text-ink transition-colors">IGD 24 Jam (11)</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md border border-gray-300 bg-white text-transparent transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>

                        <button type="button" data-filter-chip="inap" aria-pressed="false"
                                class="filter-chip w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-semibold text-muted group-hover:text-ink transition-colors">Rawat Inap</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md border border-gray-300 bg-white text-transparent transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>

                        <button type="button" data-filter-chip="nakes" aria-pressed="false"
                                class="filter-chip w-full flex items-center justify-between py-1.5 text-left group cursor-pointer">
                            <span class="font-semibold text-muted group-hover:text-ink transition-colors">9 Nakes (100%)</span>
                            <span class="chip-box flex size-5 items-center justify-center rounded-md border border-gray-300 bg-white text-transparent transition-colors">
                                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        </button>
                    </div>
                </div>

                {{-- Radius Jangkauan Geodetik --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-ink">Radius</span>
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
                            <span class="font-bold text-ink">1–5 km</span>
                            <strong id="radius-label-val" class="text-ink">3 km</strong>
                        </div>
                    </div>
                </div>

                {{-- Skenario Prioritas Pencarian --}}
                <div>
                    <div class="flex items-center justify-between mb-2.5">
                        <span class="text-xs font-extrabold uppercase tracking-wider text-ink">Prioritas</span>
                        <span class="text-[10px] font-bold text-accent">MAUT</span>
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
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between gap-1">
                            <h3 class="text-xs font-extrabold text-ink leading-snug">Titik Acuan</h3>
                            <span id="ref-precision-accuracy" class="hidden rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-extrabold text-emerald-800 shrink-0"></span>
                        </div>
                        <p id="ref-precision-status" class="text-[11px] font-semibold text-primary-dark leading-tight mt-0.5 truncate">Pusat Kota Bogor</p>
                        <p id="ref-precision-coords" class="text-[10px] font-mono text-muted leading-tight mt-0.5">-6.59710, 106.80600</p>
                    </div>
                </div>
                <p id="ref-precision-feedback" class="hidden mt-2.5 rounded-xl bg-amber-100/80 border border-amber-300/70 px-2.5 py-1.5 text-[10px] font-semibold text-amber-900 leading-snug"></p>
                <div class="mt-3.5 grid grid-cols-2 gap-2">
                    <button type="button" id="btn-geo-radius"
                            class="h-8 rounded-xl bg-[#f5b841] hover:bg-[#e5a62e] text-ink text-[11px] font-extrabold transition-colors flex items-center justify-center gap-1 shadow-2xs cursor-pointer">
                        <svg id="btn-geo-icon" class="size-3.5 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                        <span id="btn-geo-label">GPS Saya</span>
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
                <span>Terapkan</span>
                <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
            </button>
        </aside>
