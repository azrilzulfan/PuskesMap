        {{-- Kolom 3: Daftar Puskesmas & Rekomendasi MAUT (Tersembunyi Default, Muncul Saat Dibutuhkan) --}}
        <aside id="map-sidebar"
               class="fixed lg:relative inset-y-0 right-0 translate-x-full lg:translate-x-0 lg:hidden w-full sm:w-[360px] xl:w-[390px] shrink-0 h-full flex flex-col bg-white border-l border-gray-100 z-30 shadow-2xl lg:shadow-none transition-transform duration-300 ease-in-out">

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
                            <option value="distance">Terdekat</option>
                            <option value="nakes">Nakes</option>
                            <option value="igd">IGD 24 Jam</option>
                        </select>

                        {{-- Tombol Tutup Panel Daftar --}}
                        <button type="button" id="btn-close-sidebar-mobile" aria-label="Tutup Daftar"
                                class="flex size-8 items-center justify-center rounded-full bg-gray-100 hover:bg-gray-200 text-ink transition-colors cursor-pointer">
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
                                        <span class="truncate">{{ $item['address'] }}</span>
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
                            <h4 class="mt-3 text-sm font-extrabold text-ink">Tidak ditemukan</h4>
                            <p class="text-xs text-muted mt-1">Ubah kata kunci atau filter.</p>
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
                                    <p class="text-[11px] text-muted">Terbaik dari titik acuan</p>
                                </div>
                            </div>
                            <button type="button" onclick="document.getElementById('subtab-btn-faskes')?.click()"
                                    class="text-[11px] font-bold text-primary hover:underline cursor-pointer">
                                Semua
                            </button>
                        </div>

                        {{-- Pilihan 3 Skenario Pembobotan (SRS Tabel 3.4) --}}
                        <div class="space-y-1.5 text-xs">
                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-surface-mint/60 hover:bg-surface-mint cursor-pointer border border-primary/20 transition-colors">
                                <input type="radio" name="maut-scenario" value="darurat" checked class="text-accent focus:ring-accent">
                                <div>
                                    <span class="font-extrabold text-ink block">Kedaruratan</span>
                                    <span class="text-[10px] text-muted block">Jarak 40% &middot; IGD 30% &middot; Nakes 15% &middot; Jam 15%</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white hover:bg-surface-mint/40 cursor-pointer border border-gray-200 transition-colors">
                                <input type="radio" name="maut-scenario" value="reguler" class="text-accent focus:ring-accent">
                                <div>
                                    <span class="font-extrabold text-ink block">Layanan Reguler</span>
                                    <span class="text-[10px] text-muted block">Jam 30% &middot; Jarak 20% &middot; Nakes 20% &middot; Antrean 20%</span>
                                </div>
                            </label>

                            <label class="flex items-center gap-2.5 p-2.5 rounded-xl bg-white hover:bg-surface-mint/40 cursor-pointer border border-gray-200 transition-colors">
                                <input type="radio" name="maut-scenario" value="pemda" class="text-accent focus:ring-accent">
                                <div>
                                    <span class="font-extrabold text-ink block">Evaluasi Pemda</span>
                                    <span class="text-[10px] text-muted block">Nakes 30% &middot; Jarak 20% &middot; Antrean 20%</span>
                                </div>
                            </label>
                        </div>

                        <button type="button" id="btn-run-maut"
                                class="w-full h-10 rounded-xl bg-ink hover:bg-primary text-white text-xs font-extrabold transition-all shadow-xs flex items-center justify-center gap-2 cursor-pointer">
                            <span>Cari Terbaik</span>
                            <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </button>
                    </div>

                    {{-- Hasil Rekomendasi Top 3 MAUT --}}
                    <div id="maut-results" class="flex-1 overflow-y-auto p-4 space-y-3 no-scrollbar">
                        <div class="text-xs font-extrabold text-ink flex items-center justify-between pb-1">
                            <span>Top 3 Rekomendasi</span>
                            <span class="text-[10px] text-muted font-semibold">MAUT</span>
                        </div>
                        <div id="maut-cards-container" class="space-y-3">
                            {{-- Diisi secara dinamis oleh JavaScript MAUT --}}
                        </div>
                    </div>
                </div>
            </div>
        </aside>
