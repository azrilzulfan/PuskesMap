<x-layout title="Dasbor Analitik Spasial Dinas Kesehatan - PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:flex-row sm:items-center">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-primary">Dasbor Analitik Spasial Dinas Kesehatan</p>
                <h1 class="mt-1 text-2xl font-extrabold text-ink">Cakupan Layanan, Ekuitas &amp; Beban Wilayah (PSW)</h1>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('analitik.export.csv', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
                   class="inline-flex items-center gap-1.5 rounded-xl border border-primary px-4 py-2 text-xs font-bold text-primary transition hover:bg-surface-mint">
                    Unduh CSV
                </a>
                <a href="{{ route('analitik.export.pdf', ['kecamatan_id' => $selectedKecamatanId, 'buffer_km' => $bufferKm, 'tahun' => $tahun]) }}"
                   class="inline-flex items-center gap-1.5 rounded-xl bg-accent px-4 py-2 text-xs font-bold text-white transition hover:bg-accent-strong">
                    Unduh PDF
                </a>
            </div>
        </div>

        <form method="GET" action="{{ route('analitik.index') }}"
              class="grid grid-cols-1 gap-4 rounded-2xl border border-gray-200 bg-white p-4 sm:grid-cols-4 sm:items-end">
            <div>
                <label for="kecamatan_id" class="block text-xs font-bold text-muted">Filter Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id"
                        class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm font-medium text-ink">
                    <option value="">Seluruh Kota Bogor</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" @selected($selectedKecamatanId === $district->id)>
                            {{ $district->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="buffer_km" class="block text-xs font-bold text-muted">Radius Service Area (Buffer)</label>
                <select id="buffer_km" name="buffer_km"
                        class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm font-medium text-ink">
                    <option value="1.5" @selected(abs($bufferKm - 1.5) < 0.01)>1.5 km (Jalan Kaki / Dekat)</option>
                    <option value="3.0" @selected(abs($bufferKm - 3.0) < 0.01)>3.0 km (Kendaraan / Kota)</option>
                </select>
            </div>

            <div>
                <label for="tahun" class="block text-xs font-bold text-muted">Tahun Kunjungan</label>
                <input id="tahun" name="tahun" type="number" min="2020" max="2100" value="{{ $tahun }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm font-medium text-ink">
            </div>

            <div class="flex gap-2">
                <button type="submit"
                        class="w-full rounded-xl bg-primary px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary-dark cursor-pointer">
                    Terapkan Filter
                </button>
            </div>
        </form>

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <article class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-xs font-bold text-muted">Area Terlayani ({{ $bufferKm }} km)</p>
                <p class="mt-2 text-2xl font-extrabold text-primary">{{ $coverage['summary']['persentase_terlayani'] }}%</p>
                <p class="mt-1 text-xs text-muted">{{ $coverage['summary']['luas_terlayani_km2'] }} km&sup2; dari {{ $coverage['summary']['luas_kota_km2'] }} km&sup2;</p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-xs font-bold text-muted">Estimasi Blankspot</p>
                <p class="mt-2 text-2xl font-extrabold text-accent-strong">{{ $coverage['summary']['blankspot_km2'] }} km&sup2;</p>
                <p class="mt-1 text-xs text-muted">{{ $coverage['summary']['persentase_blankspot'] }}% wilayah belum terjangkau buffer</p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-xs font-bold text-muted">Rasio Ekuitas WHO (1:30.000)</p>
                <p class="mt-2 text-2xl font-extrabold text-ink">{{ $equity['city_totals']['puskesmas_count'] }} / {{ $equity['city_totals']['target_count'] }}</p>
                <p class="mt-1 text-xs text-muted">Defisit: {{ $equity['city_totals']['deficit'] }} unit Puskesmas</p>
            </article>

            <article class="rounded-2xl border border-gray-200 bg-white p-5">
                <p class="text-xs font-bold text-muted">Rata-rata Indeks PSW</p>
                <p class="mt-2 text-2xl font-extrabold text-ink">{{ $workload['summary']['average_score'] }}</p>
                <p class="mt-1 text-xs text-muted">
                    Tinggi: {{ $workload['summary']['counts']['Tinggi'] ?? 0 }} &bull;
                    Ideal: {{ $workload['summary']['counts']['Ideal'] ?? 0 }} &bull;
                    Rendah: {{ $workload['summary']['counts']['Rendah'] ?? 0 }}
                </p>
            </article>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="border-b border-gray-100 px-5 py-4">
                <h2 class="text-base font-extrabold text-ink">Analisis Ekuitas &amp; Cakupan Kecamatan</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-surface-mint/60 font-bold text-primary-dark">
                        <tr>
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3 text-right">Penduduk</th>
                            <th class="px-4 py-3 text-right">Eksisting</th>
                            <th class="px-4 py-3 text-right">Target</th>
                            <th class="px-4 py-3">Status Rasio</th>
                            <th class="px-4 py-3 text-right">Rata-rata PSW</th>
                            <th class="px-4 py-3">Spektrum Beban</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($equity['districts'] as $district)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold text-ink">{{ $district['nama_kecamatan'] }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($district['jumlah_penduduk'], 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ $district['puskesmas_count'] }}</td>
                                <td class="px-4 py-3 text-right">{{ $district['target_count'] }}</td>
                                <td class="px-4 py-3 font-semibold">{{ $district['ratio_status'] }}</td>
                                <td class="px-4 py-3 text-right font-bold">{{ $district['avg_psw_score'] }}</td>
                                <td class="px-4 py-3">{{ $district['psw_spectrum'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="flex items-center justify-between border-b border-gray-100 px-5 py-4">
                <h2 class="text-base font-extrabold text-ink">Peringkat Beban Kerja Wilayah (PSW-Index) Puskesmas</h2>
                <span class="text-xs font-semibold text-muted">{{ count($roadAccess['summary']) }} zona aksesibilitas jalan terpetakan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-surface-mint/60 font-bold text-primary-dark">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Puskesmas</th>
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3">IGD 24 Jam</th>
                            <th class="px-4 py-3 text-right">Kelengkapan Nakes</th>
                            <th class="px-4 py-3 text-right">Pasien Terbaru</th>
                            <th class="px-4 py-3 text-right">Keramaian</th>
                            <th class="px-4 py-3 text-right">Skor PSW</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($facilities as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-[11px] text-muted">{{ $item->kode_puskesmas }}</td>
                                <td class="px-4 py-3 font-bold text-ink">{{ $item->nama_puskesmas }}</td>
                                <td class="px-4 py-3">{{ $item->kecamatan?->nama_kecamatan }}</td>
                                <td class="px-4 py-3">{{ $item->operasional?->status_igd_24jam ? 'Ya' : 'Tidak' }}</td>
                                <td class="px-4 py-3 text-right">{{ $item->tenagaMedis?->persentase_kelengkapan ?? 0 }}%</td>
                                <td class="px-4 py-3 text-right">{{ number_format($item->latestKunjungan?->total_pasien_bulanan ?? 0, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ $item->latestKunjungan?->persentase_keramaian ?? 0 }}%</td>
                                <td class="px-4 py-3 text-right font-extrabold text-primary">{{ number_format((float) ($item->skor_psw ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-muted">Belum ada data Puskesmas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $facilities->links() }}
            </div>
        </section>
    </main>
</x-layout>
