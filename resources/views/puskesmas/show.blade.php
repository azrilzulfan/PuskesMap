@component('layouts.app', ['title' => $facility->nama_puskesmas.' - Detail Puskesmas | PuskesMap'])
@php
    $monthNames = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
    $chartLabels = $visitHistory->map(fn ($k) => ($monthNames[$k->bulan] ?? $k->bulan).' '.$k->tahun)->values()->all();
    $chartValues = $visitHistory->map(fn ($k) => (int) $k->total_pasien_bulanan)->values()->all();
    $defaultCapacity = (int) ($latestKunjungan?->kapasitas_maksimal_bulanan ?? config('sdss.capacity.default_monthly', 850));

    $spectrumVariant = match ($pswSpectrum) {
        'Tinggi' => 'error',
        'Ideal' => 'success',
        default => 'accent',
    };

    $crowdVariant = match ($crowdStatus) {
        'Padat' => 'error',
        'Sedang' => 'warning',
        default => 'success',
    };
@endphp

<main class="mx-auto max-w-[1160px] px-4 py-8 sm:px-6 lg:px-8 space-y-8">
    <div class="flex flex-wrap items-center justify-between gap-4">
        <a href="{{ route('peta') }}"
           class="inline-flex min-h-11 items-center gap-2 rounded-[6px] border border-[var(--border-control)] bg-[var(--base)] px-4 py-2 text-sm font-medium text-[var(--text)] transition-colors hover:bg-[var(--surface)]">
            <x-lucide-arrow-left class="size-4" />
            <span>Kembali ke Peta Utama</span>
        </a>

        <div class="flex flex-wrap items-center gap-2">
            @if ($facility->website_url)
                <a href="{{ $facility->website_url }}" target="_blank" rel="noopener noreferrer"
                   class="inline-flex min-h-11 items-center gap-2 rounded-[6px] border border-[var(--border-control)] bg-[var(--base)] px-4 py-2 text-sm font-medium text-[var(--accent)] transition-colors hover:bg-[var(--surface)]">
                    <x-lucide-external-link class="size-4" />
                    <span>Portal Resmi Dinkes</span>
                </a>
            @endif
            <a href="https://www.google.com/maps/dir/?api=1&destination={{ $facility->y_lat }},{{ $facility->x_long }}"
               target="_blank" rel="noopener noreferrer"
               class="inline-flex min-h-11 items-center gap-2 rounded-[6px] bg-[var(--accent)] px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-[var(--accent-hover)]">
                <x-lucide-navigation class="size-4" />
                <span>Petunjuk Arah</span>
            </a>
        </div>
    </div>

    <header class="rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-6 space-y-4">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-1.5">
                <p class="font-mono text-xs font-medium text-[var(--text-muted)]">
                    {{ $facility->kode_puskesmas }} &middot; Kec. {{ $facility->kecamatan?->nama_kecamatan ?? '-' }}
                </p>
                <h1 class="text-2xl font-semibold tracking-tight text-[var(--text)] [text-wrap:balance] sm:text-[28px]">
                    {{ $facility->nama_puskesmas }}
                </h1>
                <p class="max-w-[65ch] text-sm text-[var(--text-muted)] [text-wrap:pretty]">
                    {{ $facility->alamat }}
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <x-ui.badge :variant="$isOpenNow ? 'success' : 'neutral'">
                    {{ $isOpenNow ? 'Sedang Buka (WIB)' : 'Sedang Tutup (WIB)' }}
                </x-ui.badge>
                @if ($facility->operasional?->status_igd_24jam)
                    <x-ui.badge variant="error">IGD 24 Jam</x-ui.badge>
                @else
                    <x-ui.badge variant="neutral">Poli Reguler</x-ui.badge>
                @endif
            </div>
        </div>

        <dl class="grid grid-cols-1 gap-4 border-t border-[var(--border)] pt-4 text-sm sm:grid-cols-3">
            <div>
                <dt class="text-xs font-medium text-[var(--text-muted)]">Jam Layanan</dt>
                <dd class="mt-1 font-semibold text-[var(--text)]">{{ $hoursLabel }}</dd>
                @if ($facility->operasional?->status_igd_24jam)
                    <dd class="mt-0.5 text-xs text-[var(--text-muted)]">
                        Poli reguler {{ substr((string) $facility->operasional->jam_buka, 0, 5) }}–{{ substr((string) $facility->operasional->jam_tutup, 0, 5) }} WIB
                    </dd>
                @endif
            </div>
            <div>
                <dt class="text-xs font-medium text-[var(--text-muted)]">Kontak Resmi</dt>
                <dd class="mt-1 font-semibold text-[var(--text)] tabular-nums">
                    {{ $facility->operasional?->kontak_darurat ?? '-' }}
                </dd>
            </div>
            <div>
                <dt class="text-xs font-medium text-[var(--text-muted)]">Koordinat Geodetik</dt>
                <dd class="mt-1 font-mono text-xs font-semibold text-[var(--text)] tabular-nums">
                    {{ number_format((float) $facility->y_lat, 6) }}, {{ number_format((float) $facility->x_long, 6) }}
                </dd>
            </div>
        </dl>
    </header>

    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
        <div class="rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-5">
            <p class="text-xs font-medium text-[var(--text-muted)]">Kelengkapan 9 Nakes</p>
            <p class="mt-1 text-2xl font-semibold text-[var(--text)] tabular-nums">{{ $nakesPct }}%</p>
            <div class="mt-2">
                <x-ui.badge :variant="$nakesPct >= 100 ? 'success' : 'warning'">{{ $nakesLabel }}</x-ui.badge>
            </div>
        </div>

        <div class="rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-5">
            <p class="text-xs font-medium text-[var(--text-muted)]">Kunjungan Bulanan Terbaru</p>
            <p class="mt-1 text-2xl font-semibold text-[var(--text)] tabular-nums">
                {{ number_format((int) ($latestKunjungan?->total_pasien_bulanan ?? 0), 0, ',', '.') }}
            </p>
            <div class="mt-2 flex items-center gap-2">
                <x-ui.badge :variant="$crowdVariant">{{ $crowdStatus }}</x-ui.badge>
                <span class="text-xs text-[var(--text-muted)] tabular-nums">
                    {{ $latestKunjungan?->persentase_keramaian ?? 0 }}% dari {{ $defaultCapacity }}
                </span>
            </div>
        </div>

        <div class="rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-5">
            <p class="text-xs font-medium text-[var(--text-muted)]">Beban Binaan UKM</p>
            <p class="mt-1 text-2xl font-semibold text-[var(--text)] tabular-nums">
                {{ $facility->jumlah_posyandu }} <span class="text-sm font-normal text-[var(--text-muted)]">Posyandu</span>
            </p>
            <p class="mt-2 text-xs text-[var(--text-muted)] tabular-nums">
                {{ $facility->jumlah_sekolah }} Sekolah Binaan (SD–SMA)
            </p>
        </div>

        <div class="rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-5">
            <p class="text-xs font-medium text-[var(--text-muted)]">Skor PSW-Index</p>
            <p class="mt-1 text-2xl font-semibold text-[var(--text)] tabular-nums">{{ number_format($pswScore, 2) }}</p>
            <div class="mt-2">
                <x-ui.badge :variant="$spectrumVariant">Spektrum {{ $pswSpectrum }}</x-ui.badge>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-6 lg:grid-cols-12">
        <div class="lg:col-span-5 rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-6">
            <div class="flex items-center justify-between gap-2 pb-4 border-b border-[var(--border)]">
                <h2 class="text-lg font-semibold text-[var(--text)]">Matriks 9 Profesi Tenaga Kesehatan</h2>
                <span class="text-xs font-medium text-[var(--text-muted)] tabular-nums">Total {{ $totalNakes }} staf</span>
            </div>

            <table class="mt-3 w-full text-sm">
                <thead>
                    <tr class="border-b border-[var(--border)] text-left text-xs text-[var(--text-muted)]">
                        <th class="py-2 font-medium">Profesi</th>
                        <th class="py-2 text-right font-medium">Jumlah</th>
                        <th class="py-2 text-right font-medium">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[var(--border)]">
                    @foreach ($professionCounts as $profession => $count)
                        <tr>
                            <td class="py-2.5 font-medium text-[var(--text)]">{{ $profession }}</td>
                            <td class="py-2.5 text-right tabular-nums text-[var(--text)]">{{ $count }}</td>
                            <td class="py-2.5 text-right text-xs font-medium {{ $count > 0 ? 'text-[var(--success)]' : 'text-[var(--error)]' }}">
                                {{ $count > 0 ? 'Tersedia' : 'Kosong' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="lg:col-span-7 rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-6 flex flex-col justify-between">
            <div>
                <div class="flex flex-wrap items-center justify-between gap-2 pb-4 border-b border-[var(--border)]">
                    <div>
                        <h2 class="text-lg font-semibold text-[var(--text)]">Tren Kunjungan Pasien Baru (12 Bulan)</h2>
                        <p class="text-xs text-[var(--text-muted)]">
                            Dibandingkan dengan kapasitas acuan bulanan {{ number_format($defaultCapacity, 0, ',', '.') }} pasien/bulan
                        </p>
                    </div>
                </div>

                @if ($visitHistory->isNotEmpty())
                    <div class="mt-5 h-72 w-full">
                        <canvas id="visitTrendChart" aria-label="Grafik tren kunjungan pasien 12 bulan"></canvas>
                    </div>
                @else
                    <p class="mt-6 text-sm text-[var(--text-muted)]">Belum ada riwayat kunjungan bulanan untuk Puskesmas ini.</p>
                @endif
            </div>
        </div>
    </section>

    <script id="visit-trend-data" type="application/json">
        {!! json_encode([
            'labels' => $chartLabels,
            'values' => $chartValues,
            'capacity' => $defaultCapacity,
        ]) !!}
    </script>
</main>
@endcomponent
