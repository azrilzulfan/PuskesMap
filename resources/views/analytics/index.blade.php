@php
    $coverageByDistrict = collect($coverage['per_kecamatan'] ?? [])->keyBy('kecamatan_id');
    $coveragePct = (float) ($coverage['summary']['persentase_terlayani'] ?? 0);
    $hasActiveFilters = $selectedKecamatanId !== null || abs($bufferKm - 1.5) >= 0.01 || (int) $tahun !== 2025;
    $isAdminUser = (bool) auth()->user()?->isAdmin();
@endphp

<x-dashboard-shell
    title="Dasbor Analitik Spasial Dinas Kesehatan - PuskesMap"
    :greeting="'Halo, '.(auth()->user()?->name ?? 'Dinas Kesehatan').'!'"
    subtitle="Dasbor Analitik Spasial Dinas Kesehatan — Cakupan, Ekuitas & PSW-Index Kota Bogor"
    :search-action="route('analitik.index')"
    search-name="tahun"
    :search-value="(string) $tahun"
    search-placeholder="Tahun kunjungan (mis. 2025)..."
    search-type="number"
    :search-params="['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm]"
>
    <x-slot:headerActions>
        <a href="{{ route('analitik.export.csv', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
           title="Unduh Laporan CSV"
           aria-label="Unduh CSV"
           class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-white px-4 text-xs font-bold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v12m0 0l-4-4m4 4l4-4M4 17v2a2 2 0 002 2h12a2 2 0 002-2v-2"/>
            </svg>
            <span class="hidden sm:inline">CSV</span>
        </a>
        <a href="{{ route('analitik.export.pdf', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
           title="Unduh Laporan PDF"
           aria-label="Unduh PDF"
           class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-white px-4 text-xs font-bold text-accent-strong shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-card-peach/40">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.1" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span class="hidden sm:inline">PDF</span>
        </a>
    </x-slot:headerActions>

    {{-- Row 1: 4 Compact Metric Cards (Kartu ke-4 Teal Gradient Highlight persis referensi) --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        {{-- Card 1: Cakupan Buffer + Mini Bar Sparkline --}}
        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <p class="text-xs font-semibold text-muted">Terlayani ({{ $bufferKm }} km)</p>
                <p class="mt-1.5 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $coveragePct }}%</p>
            </div>
            <div class="flex h-10 items-end gap-1.5" aria-hidden="true">
                <span class="w-1.5 h-5 rounded-full bg-surface-mint"></span>
                <span class="w-1.5 h-8 rounded-full bg-primary-dark"></span>
                <span class="w-1.5 h-6 rounded-full bg-primary/60"></span>
                <span class="w-1.5 h-10 rounded-full bg-primary"></span>
                <span class="w-1.5 h-4 rounded-full bg-primary-dark"></span>
            </div>
        </article>

        {{-- Card 2: Total Puskesmas + Soft Mint Circle + Smooth Curve --}}
        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div class="flex items-center gap-3.5">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-surface-mint text-primary-dark" aria-hidden="true">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold text-muted">Total Puskesmas</p>
                    <p class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $equity['city_totals']['puskesmas_count'] }}</p>
                </div>
            </div>
            <svg class="h-8 w-14 text-primary/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 56 32" aria-hidden="true">
                <path stroke-linecap="round" d="M2 28 C14 28, 18 16, 28 16 C38 16, 42 4, 54 4"/>
            </svg>
        </article>

        {{-- Card 3: Area Blankspot + Soft Peach Circle --}}
        <article class="flex items-center gap-3.5 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-card-peach/65 text-accent-strong" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M12 8v4l2.5 2.5"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-semibold text-muted">Area Blankspot</p>
                <p class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $coverage['summary']['blankspot_km2'] }} <span class="text-sm font-bold text-muted">km&sup2;</span></p>
            </div>
        </article>

        {{-- Card 4: Highlighted Teal Gradient Card (PSW-Index) --}}
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

    {{-- Row 2: 3-Column Asymmetric Bento Mosaic (6 + 3 + 3) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Left Wide Card (6 cols): Ekuitas & Cakupan + 2 Inner Cards + Smooth Area Chart --}}
        <article class="lg:col-span-6 flex flex-col justify-between rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2">
                    <div class="flex items-center gap-2.5">
                        <h2 class="text-base font-extrabold text-ink">Ekuitas &amp; Cakupan</h2>
                        <span class="inline-flex items-center gap-1 text-[11px] font-bold text-primary">
                            <span class="size-2 rounded-full bg-primary" aria-hidden="true"></span>
                            Rasio 1:30.000
                        </span>
                    </div>
                    <span class="text-xs font-semibold text-muted">Buffer {{ $bufferKm }} km &middot; {{ $tahun }}</span>
                </div>

                {{-- 2 Elevated Inner Sub-Cards (persis gaya Balance sub-cards) --}}
                <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2">
                    <div class="rounded-2xl bg-[#f7fbfa] p-4 shadow-[0_4px_14px_rgba(0,121,121,0.05)]">
                        <p class="text-[11px] font-semibold text-muted">Wilayah Terlayani</p>
                        <div class="mt-1.5 flex items-baseline gap-2">
                            <span class="text-xl font-extrabold text-ink tabular-nums">{{ $coverage['summary']['luas_terlayani_km2'] }} km&sup2;</span>
                            <span class="text-[11px] font-bold text-primary">{{ $coveragePct }}%</span>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[#f7fbfa] p-4 shadow-[0_4px_14px_rgba(0,121,121,0.05)]">
                        <p class="text-[11px] font-semibold text-muted">Kebutuhan Ideal Kota</p>
                        <div class="mt-1.5 flex items-baseline gap-2">
                            <span class="text-xl font-extrabold text-ink tabular-nums">{{ $equity['city_totals']['target_count'] }} Unit</span>
                            <span class="text-[11px] font-bold text-accent-strong">-{{ $equity['city_totals']['deficit'] }} defisit</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 h-44 w-full">
                <canvas id="equityAreaChart" aria-label="Grafik perbandingan puskesmas eksisting dan target ideal per kecamatan"></canvas>
            </div>
        </article>

        {{-- Middle Card (3 cols): Semi-Circle Gauge (persis gaya Earnings 80%) --}}
        <article class="lg:col-span-3 flex flex-col justify-between rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <h2 class="text-base font-extrabold text-ink">Cakupan Kota</h2>
                <p class="mt-0.5 text-xs font-medium text-muted">Total Penduduk Terdata</p>
                <p class="mt-2 text-2xl font-extrabold tracking-tight text-primary-dark tabular-nums">
                    {{ number_format($equity['city_totals']['population'], 0, ',', '.') }}
                </p>
                <p class="mt-1 text-xs font-medium text-muted">
                    {{ $coverage['summary']['luas_kota_km2'] }} km&sup2; luas administratif Kota Bogor
                </p>
            </div>

            <div class="relative mt-4 flex flex-col items-center">
                <div class="h-32 w-full max-w-[200px]">
                    <canvas id="coverageGaugeChart" aria-label="Gauge persentase cakupan layanan"></canvas>
                </div>
                <p class="-mt-10 text-2xl font-extrabold text-ink tabular-nums">{{ round($coveragePct) }}%</p>
                <p class="mt-1 text-[11px] font-semibold text-muted">Terjangkau {{ $bufferKm }} km</p>
            </div>
        </article>

        {{-- Right Card (3 cols): Profile & 3-Stat PSW Summary (persis gaya Profile card) --}}
        <article class="lg:col-span-3 flex flex-col items-center justify-between rounded-[24px] bg-white p-6 text-center shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div class="flex flex-col items-center pt-1">
                <div class="flex size-16 items-center justify-center rounded-full bg-surface-mint p-3 ring-4 ring-surface-mint/60">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="PuskesMap" class="h-9 w-auto object-contain">
                </div>
                <h2 class="mt-3.5 text-base font-extrabold text-ink">{{ auth()->user()?->name }}</h2>
                <p class="mt-0.5 text-xs font-medium text-muted">{{ auth()->user()?->role->label() }}</p>
            </div>

            <div class="mt-5 grid w-full grid-cols-3 gap-2 border-t border-primary/10 pt-4">
                <div>
                    <p class="text-[11px] font-medium text-muted">Tinggi</p>
                    <p class="mt-0.5 text-xl font-extrabold text-accent-strong tabular-nums">{{ $workload['summary']['counts']['Tinggi'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-muted">Ideal</p>
                    <p class="mt-0.5 text-xl font-extrabold text-primary-dark tabular-nums">{{ $workload['summary']['counts']['Ideal'] ?? 0 }}</p>
                </div>
                <div>
                    <p class="text-[11px] font-medium text-muted">Rendah</p>
                    <p class="mt-0.5 text-xl font-extrabold text-ink tabular-nums">{{ $workload['summary']['counts']['Rendah'] ?? 0 }}</p>
                </div>
            </div>
        </article>
    </section>

    {{-- Row 3: 3-Column Bottom Bento Mosaic (6 + 3 + 3) --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        {{-- Left Wide Split Card (6 cols): Filter & Visual Layered Cards --}}
        <article class="lg:col-span-6 grid grid-cols-1 items-center gap-6 rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)] sm:grid-cols-12">
            <form method="GET" action="{{ route('analitik.index') }}" class="sm:col-span-7 space-y-3">
                <h2 class="text-base font-extrabold leading-snug text-ink">Filter Analisis &amp; Laporan</h2>
                <p class="text-xs leading-relaxed text-muted">
                    Pilih kecamatan atau radius buffer untuk memfokuskan metrik ekuitas, cakupan, dan ekspor CSV/PDF.
                </p>

                <div class="grid grid-cols-1 gap-2.5 sm:grid-cols-2">
                    <div>
                        <label for="kecamatan_id" class="block text-[11px] font-bold text-primary-dark">Kecamatan</label>
                        <select id="kecamatan_id" name="kecamatan_id"
                                class="mt-1 min-h-10 w-full rounded-xl border border-[#7B8A97] bg-input px-3 py-1.5 text-xs font-medium text-ink focus:bg-white focus:border-primary">
                            <option value="">Seluruh Kota Bogor</option>
                            @foreach ($districts as $district)
                                <option value="{{ $district->id }}" @selected($selectedKecamatanId === $district->id)>
                                    {{ $district->nama_kecamatan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="buffer_km" class="block text-[11px] font-bold text-primary-dark">Radius Buffer</label>
                        <select id="buffer_km" name="buffer_km"
                                class="mt-1 min-h-10 w-full rounded-xl border border-[#7B8A97] bg-input px-3 py-1.5 text-xs font-medium text-ink focus:bg-white focus:border-primary">
                            <option value="1.5" @selected(abs($bufferKm - 1.5) < 0.01)>1.5 km (Dekat)</option>
                            <option value="3.0" @selected(abs($bufferKm - 3.0) < 0.01)>3.0 km (Kota)</option>
                        </select>
                    </div>
                </div>
                <input type="hidden" name="tahun" value="{{ $tahun }}">

                <div class="flex flex-wrap items-center gap-2 pt-1">
                    <button type="submit"
                            class="inline-flex min-h-10 items-center justify-center rounded-xl bg-gradient-to-r from-primary to-primary-dark px-5 py-2 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95 cursor-pointer">
                        Terapkan Filter
                    </button>
                    @if ($hasActiveFilters)
                        <a href="{{ route('analitik.index') }}"
                           class="inline-flex min-h-10 items-center justify-center rounded-xl border border-primary/20 bg-white px-4 py-2 text-xs font-bold text-ink transition hover:bg-surface-mint">
                            Reset
                        </a>
                    @endif
                    <a href="{{ route('analitik.export.pdf', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
                       class="inline-flex min-h-10 items-center justify-center rounded-xl bg-accent px-4 py-2 text-xs font-extrabold text-white transition hover:bg-accent-strong">
                        Unduh PDF
                    </a>
                    <a href="{{ route('analitik.export.csv', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
                       class="inline-flex min-h-10 items-center justify-center rounded-xl bg-surface-mint px-4 py-2 text-xs font-extrabold text-primary-dark transition hover:bg-card-mint">
                        Unduh CSV
                    </a>
                </div>
            </form>

            {{-- Stacked Layered Cards Visual (persis kartu miring di referensi, memakai warna welcome) --}}
            <div class="sm:col-span-5 relative flex h-44 items-center justify-center overflow-hidden rounded-2xl bg-surface-mint/60 p-4" aria-hidden="true">
                <div class="relative w-full max-w-[180px] h-28">
                    <div class="absolute inset-0 -rotate-12 translate-y-2 rounded-2xl bg-surface-sky p-3 shadow-sm">
                        <p class="text-[10px] font-bold text-primary-dark">Jaringan Jalan</p>
                    </div>
                    <div class="absolute inset-0 -rotate-6 translate-y-1 rounded-2xl bg-primary p-3.5 text-white shadow-md">
                        <p class="text-[10px] font-semibold text-white/80">Kota Bogor</p>
                        <p class="mt-2 text-sm font-extrabold">{{ count($equity['districts'] ?? []) }} Kecamatan</p>
                    </div>
                    <div class="absolute inset-0 rounded-2xl bg-gradient-to-br from-ink to-primary-dark p-4 text-white shadow-lg flex flex-col justify-between">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-bold text-card-peach uppercase tracking-wider">PuskesMap</span>
                            <span class="size-2.5 rounded-full bg-accent"></span>
                        </div>
                        <div>
                            <p class="text-lg font-extrabold tabular-nums">{{ $equity['city_totals']['puskesmas_count'] }} Puskesmas</p>
                            <p class="text-[10px] text-white/75">Dataset Resmi {{ $tahun }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </article>

        {{-- Middle Card (3 cols): Zona Akses Jalan (persis gaya "Your Transfers" vertical bar list) --}}
        <article class="lg:col-span-3 flex flex-col justify-between rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <h2 class="text-base font-extrabold text-ink">Zona Akses Jalan</h2>

            <ul class="mt-4 space-y-3">
                @forelse (array_slice($roadAccess['summary'], 0, 4) as $zone)
                    <li class="flex items-center justify-between gap-2 border-l-[3px] {{ $loop->even ? 'border-accent' : 'border-primary' }} pl-3">
                        <div class="min-w-0">
                            <p class="truncate text-xs font-extrabold text-ink">{{ $zone['kategori_jangkauan'] }}</p>
                            <p class="text-[11px] font-medium text-muted tabular-nums">{{ $zone['total_zones'] }} poligon zona</p>
                        </div>
                        <span class="shrink-0 text-xs font-extrabold text-primary-dark tabular-nums">
                            {{ $zone['total_area_km2'] }} km&sup2;
                        </span>
                    </li>
                @empty
                    <li class="text-xs text-muted">Belum ada data zona aksesibilitas jalan.</li>
                @endforelse
            </ul>
        </article>

        {{-- Right Card (3 cols): CTA Peta WebGIS (persis gaya "Keep you safe!" security card) --}}
        <article class="lg:col-span-3 flex flex-col items-center justify-between rounded-[24px] bg-white p-6 text-center shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="mt-1 flex size-14 items-center justify-center rounded-full bg-surface-mint text-primary-dark" aria-hidden="true">
                <svg class="size-7" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-5.6-6-10.5a6 6 0 1112 0C18 15.4 12 21 12 21z"/>
                    <circle cx="12" cy="10.5" r="2.2" fill="currentColor" stroke="none"/>
                </svg>
            </span>

            <div class="mt-3">
                <h2 class="text-base font-extrabold text-ink">Peta Spasial Internal</h2>
                <p class="mt-1 text-xs font-medium text-muted">Eksplorasi layer blankspot, PSW &amp; evaluasi Pemda</p>
            </div>

            <a href="{{ route('analitik.peta', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
               class="mt-4 inline-flex min-h-10 w-full items-center justify-center rounded-xl bg-gradient-to-r from-primary to-primary-dark px-5 py-2.5 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95">
                Buka Peta Internal
            </a>
        </article>
    </section>

    {{-- Row 4: Tabel Analisis Ekuitas & Cakupan Per Kecamatan --}}
    <section class="overflow-hidden rounded-[24px] bg-white shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <div class="flex flex-wrap items-baseline justify-between gap-2 border-b border-primary/10 px-6 py-4">
            <div>
                <h2 class="text-base font-extrabold text-ink">Analisis Ekuitas &amp; Cakupan Per Kecamatan</h2>
                <p class="text-xs text-muted">Rasio kecukupan 1:30.000 penduduk, luas terlayani buffer {{ $bufferKm }} km, dan rata-rata PSW. Klik nama kecamatan untuk fokus wilayah.</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-primary/10 text-left text-xs">
                <thead class="bg-surface-mint/70 text-[11px] font-extrabold uppercase tracking-wider text-primary-dark">
                    <tr>
                        <th class="px-6 py-3.5">Kecamatan</th>
                        <th class="px-4 py-3.5 text-right">Penduduk</th>
                        <th class="px-4 py-3.5 text-right">Eksisting</th>
                        <th class="px-4 py-3.5 text-right">Target</th>
                        <th class="px-4 py-3.5">Status Rasio</th>
                        <th class="px-4 py-3.5 text-right">Terlayani</th>
                        <th class="px-4 py-3.5 text-right">Blankspot</th>
                        <th class="px-4 py-3.5 text-right">Rata-rata PSW</th>
                        <th class="px-6 py-3.5">Spektrum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach ($equity['districts'] as $district)
                        @php
                            $distCov = $coverageByDistrict->get($district['id']);
                            $isSelectedDistrict = $selectedKecamatanId === $district['id'];
                            $spec = $district['psw_spectrum'];
                            $specTone = match ($spec) {
                                'Tinggi' => 'text-accent-strong font-extrabold',
                                'Ideal' => 'text-primary-dark font-bold',
                                default => 'text-muted font-semibold',
                            };
                        @endphp
                        <tr class="transition-colors {{ $isSelectedDistrict ? 'bg-surface-mint/60' : 'hover:bg-surface-mint/35' }}">
                            <td class="px-6 py-3.5 font-extrabold text-ink">
                                <a href="{{ route('analitik.index', ['kecamatan_id' => $isSelectedDistrict ? null : $district['id'], 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
                                   title="{{ $isSelectedDistrict ? 'Tampilkan seluruh Kota Bogor' : 'Fokus analisis '.$district['nama_kecamatan'] }}"
                                   class="inline-flex items-center gap-1.5 hover:text-primary-dark">
                                    <span>{{ $district['nama_kecamatan'] }}</span>
                                    @if ($isSelectedDistrict)
                                        <span class="text-[10px] font-bold text-primary-dark">(Aktif)</span>
                                    @endif
                                </a>
                            </td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ number_format($district['jumlah_penduduk'], 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell font-bold">{{ $district['puskesmas_count'] }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ $district['target_count'] }}</td>
                            <td class="px-4 py-3.5 font-bold {{ $district['deficit'] > 0 ? 'text-accent-strong' : 'text-primary-dark' }}">
                                {{ $district['ratio_status'] }}
                            </td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ $distCov ? $distCov['persentase_terlayani'].'%' : '-' }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ $distCov ? $distCov['blankspot_km2'].' km²' : '-' }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell font-extrabold text-primary-dark">{{ number_format((float) $district['avg_psw_score'], 2) }}</td>
                            <td class="px-6 py-3.5 {{ $specTone }}">{{ $spec }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- Row 5: Tabel Peringkat PSW-Index Puskesmas --}}
    <section id="tabel-psw" class="overflow-hidden rounded-[24px] bg-white shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <div class="flex flex-wrap items-baseline justify-between gap-2 border-b border-primary/10 px-6 py-4">
            <div>
                <h2 class="text-base font-extrabold text-ink">Peringkat Beban Kerja Wilayah (PSW-Index) Puskesmas</h2>
                <p class="text-xs text-muted">Diurutkan dari skor beban kerja tertinggi untuk prioritas intervensi SDM &amp; kapasitas.</p>
            </div>
            <span class="text-xs font-bold text-primary-dark tabular-nums">{{ $facilities->total() }} fasilitas</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-primary/10 text-left text-xs">
                <thead class="bg-surface-mint/70 text-[11px] font-extrabold uppercase tracking-wider text-primary-dark">
                    <tr>
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-4 py-3.5">Puskesmas</th>
                        <th class="px-4 py-3.5">Kecamatan</th>
                        <th class="px-4 py-3.5">Layanan</th>
                        <th class="px-4 py-3.5 text-right">Nakes</th>
                        <th class="px-4 py-3.5 text-right">Pasien/Bln</th>
                        <th class="px-4 py-3.5 text-right">Keramaian</th>
                        <th class="px-4 py-3.5 text-right">Skor PSW</th>
                        <th class="px-6 py-3.5">Spektrum</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($facilities as $item)
                        @php
                            $pswVal = (float) ($item->skor_psw ?? 0);
                            $spectrumLabel = \App\Enums\WorkloadSpectrum::fromScore($pswVal)->value;
                            $spectrumTone = match ($spectrumLabel) {
                                'Tinggi' => 'text-accent-strong font-extrabold',
                                'Ideal' => 'text-primary-dark font-bold',
                                default => 'text-muted font-semibold',
                            };
                        @endphp
                        <tr class="hover:bg-surface-mint/35 transition-colors">
                            <td class="px-6 py-3.5 font-mono text-[11px] text-muted">{{ $item->kode_puskesmas }}</td>
                            <td class="px-4 py-3.5 font-extrabold text-ink">
                                @if ($isAdminUser)
                                    <a href="{{ route('admin.operasional.edit', $item) }}" class="hover:text-primary-dark hover:underline">
                                        {{ $item->nama_puskesmas }}
                                    </a>
                                @else
                                    {{ $item->nama_puskesmas }}
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-body">{{ $item->kecamatan?->nama_kecamatan }}</td>
                            <td class="px-4 py-3.5 font-semibold {{ $item->operasional?->status_igd_24jam ? 'text-accent-strong' : 'text-body' }}">
                                {{ $item->operasional?->status_igd_24jam ? 'IGD 24 Jam' : 'Reguler' }}
                            </td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ $item->tenagaMedis?->persentase_kelengkapan ?? 0 }}%</td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ number_format($item->latestKunjungan?->total_pasien_bulanan ?? 0, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ $item->latestKunjungan?->persentase_keramaian ?? 0 }}%</td>
                            <td class="px-4 py-3.5 tabular-nums-cell font-extrabold text-primary-dark">{{ number_format($pswVal, 2) }}</td>
                            <td class="px-6 py-3.5 {{ $spectrumTone }}">{{ $spectrumLabel }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-10 text-center text-muted">Belum ada data Puskesmas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-primary/10 bg-surface-mint/25 px-6 py-3.5">
            {{ $facilities->links() }}
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (!window.Chart) { return; }

            const equityDistricts = @json($equity['districts'] ?? []);
            const coveragePct = @json($coveragePct);

            const areaCanvas = document.getElementById('equityAreaChart');
            if (areaCanvas && equityDistricts.length > 0) {
                const ctx = areaCanvas.getContext('2d');
                const grad = ctx.createLinearGradient(0, 0, 0, 170);
                grad.addColorStop(0, 'rgba(0, 121, 121, 0.20)');
                grad.addColorStop(1, 'rgba(0, 121, 121, 0.01)');

                new window.Chart(areaCanvas, {
                    type: 'line',
                    data: {
                        labels: equityDistricts.map((d) => d.nama_kecamatan.replace('Bogor ', '')),
                        datasets: [
                            {
                                label: 'Puskesmas Eksisting',
                                data: equityDistricts.map((d) => d.puskesmas_count),
                                borderColor: '#007979',
                                backgroundColor: grad,
                                borderWidth: 2.5,
                                tension: 0.42,
                                fill: true,
                                pointRadius: 3.5,
                                pointBackgroundColor: '#007979',
                            },
                            {
                                label: 'Target Ideal (1:30.000)',
                                data: equityDistricts.map((d) => d.target_count),
                                borderColor: '#e37434',
                                borderDash: [5, 4],
                                borderWidth: 2,
                                tension: 0.42,
                                fill: false,
                                pointRadius: 3,
                                pointBackgroundColor: '#e37434',
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } },
                        },
                        scales: {
                            x: { grid: { display: false }, ticks: { font: { size: 11 } } },
                            y: { beginAtZero: true, grid: { color: '#eef3f2' }, ticks: { precision: 0, font: { size: 11 } } },
                        },
                    },
                });
            }

            const gaugeCanvas = document.getElementById('coverageGaugeChart');
            if (gaugeCanvas) {
                const clamped = Math.min(100, Math.max(0, coveragePct));
                new window.Chart(gaugeCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: ['Terlayani', 'Blankspot'],
                        datasets: [{
                            data: [clamped, Math.max(0, 100 - clamped)],
                            backgroundColor: ['#007979', '#e8f4f2'],
                            borderWidth: 0,
                            borderRadius: 6,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        rotation: -90,
                        circumference: 180,
                        cutout: '76%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: true },
                        },
                    },
                });
            }
        });
    </script>
</x-dashboard-shell>
