@php
    $latestRecord = $records->first();
@endphp

<x-dashboard-shell
    title="Kunjungan Pasien Bulanan - Admin PuskesMap"
    :greeting="$puskesmas->nama_puskesmas"
    :subtitle="'Log Kunjungan Pasien Bulanan · Kapasitas Acuan '.$defaultCapacity.' pasien/bulan'"
>
    <x-slot:headerActions>
        <a href="{{ route('admin.operasional.edit', $puskesmas) }}"
           class="inline-flex h-11 items-center rounded-full bg-gradient-to-r from-primary to-primary-dark px-5 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95">
            Operasional &amp; Nakes
        </a>
        <a href="{{ route('admin.puskesmas.index') }}"
           class="inline-flex h-11 items-center rounded-full bg-white px-5 text-xs font-extrabold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            &larr; Kembali ke Daftar
        </a>
    </x-slot:headerActions>

    {{-- Row 1: Bento Input Form + Highlight Card --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <form method="POST" action="{{ route('admin.kunjungan.store', $puskesmas) }}"
              class="lg:col-span-8 flex flex-col justify-between rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            @csrf
            <div>
                <h2 class="text-base font-extrabold text-ink">Catat atau Perbarui Kunjungan Bulanan</h2>
                <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="bulan" class="block text-xs font-bold text-ink">Bulan (1-12)</label>
                        <input id="bulan" name="bulan" type="number" min="1" max="12" required value="{{ old('bulan', now()->month) }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink tabular-nums focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="tahun" class="block text-xs font-bold text-ink">Tahun</label>
                        <input id="tahun" name="tahun" type="number" min="2020" max="2100" required value="{{ old('tahun', 2025) }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink tabular-nums focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="total_pasien_bulanan" class="block text-xs font-bold text-ink">Total Pasien</label>
                        <input id="total_pasien_bulanan" name="total_pasien_bulanan" type="number" min="0" required value="{{ old('total_pasien_bulanan') }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink tabular-nums focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="kapasitas_maksimal_bulanan" class="block text-xs font-bold text-ink">Kapasitas Max</label>
                        <input id="kapasitas_maksimal_bulanan" name="kapasitas_maksimal_bulanan" type="number" min="1" value="{{ old('kapasitas_maksimal_bulanan', $defaultCapacity) }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink tabular-nums focus:bg-white focus:border-primary">
                    </div>
                </div>
            </div>

            <div class="mt-5 flex flex-wrap items-center justify-between gap-3">
                <p id="live-density-preview" class="text-xs font-semibold text-muted tabular-nums"></p>
                <button type="submit"
                        class="min-h-11 w-full sm:w-auto rounded-xl bg-gradient-to-r from-primary to-primary-dark px-6 py-2.5 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95 cursor-pointer">
                    Simpan Kunjungan
                </button>
            </div>
        </form>

        <article class="lg:col-span-4 flex flex-col justify-between rounded-[24px] bg-gradient-to-br from-[#138282] via-primary to-primary-dark p-6 text-white shadow-[0_14px_30px_rgba(0,121,121,0.22)]">
            <div>
                <p class="text-xs font-semibold text-white/80">Kunjungan Periode Terbaru</p>
                <p class="mt-2 text-3xl font-extrabold tracking-tight tabular-nums">
                    {{ $latestRecord ? number_format($latestRecord->total_pasien_bulanan, 0, ',', '.') : 0 }}
                    <span class="text-sm font-bold text-white/80">pasien</span>
                </p>
            </div>
            <div class="mt-5 flex items-center justify-between border-t border-white/15 pt-3 text-xs">
                <span class="text-white/80">Kepadatan</span>
                <span class="font-extrabold text-card-peach tabular-nums">
                    {{ $latestRecord ? $latestRecord->persentase_keramaian.'% · '.$latestRecord->status_kepadatan->value : 'Belum ada data' }}
                </span>
            </div>
        </article>
    </section>

    @if (session('status'))
        <div x-data="{ show: true }" x-show="show" role="alert"
             class="flex items-center justify-between gap-3 rounded-[20px] border-l-4 border-primary bg-white px-5 py-3.5 text-xs font-extrabold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)]">
            <span>{{ session('status') }}</span>
            <button type="button" @click="show = false" aria-label="Tutup notifikasi"
                    class="flex size-6 items-center justify-center rounded-full text-muted hover:bg-surface-mint hover:text-ink cursor-pointer">&times;</button>
        </div>
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-[20px] border-l-4 border-[#B42318] bg-white px-5 py-3.5 text-xs text-[#B42318] shadow-[0_6px_20px_rgba(0,121,121,0.06)]">
            <ul class="list-disc space-y-1 pl-4 font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Row 2: Tabel Riwayat Kunjungan --}}
    <section class="overflow-hidden rounded-[24px] bg-white shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-primary/10 text-left text-xs">
                <thead class="bg-surface-mint/70 text-[11px] font-extrabold uppercase tracking-wider text-primary-dark">
                    <tr>
                        <th class="px-6 py-3.5">Periode</th>
                        <th class="px-4 py-3.5 text-right">Total Pasien</th>
                        <th class="px-4 py-3.5 text-right">Kapasitas Maksimal</th>
                        <th class="px-4 py-3.5 text-right">Keramaian (%)</th>
                        <th class="px-6 py-3.5">Status Kepadatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @php
                        $monthNames = [1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'];
                    @endphp
                    @forelse ($records as $record)
                        @php
                            $density = $record->status_kepadatan->value;
                            $densityTone = match ($density) {
                                'Padat' => 'text-accent-strong font-extrabold',
                                'Sedang' => 'text-primary-dark font-bold',
                                default => 'text-muted font-semibold',
                            };
                        @endphp
                        <tr class="hover:bg-surface-mint/35 transition-colors">
                            <td class="px-6 py-3.5 font-extrabold text-ink tabular-nums">
                                {{ str_pad((string) $record->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $record->tahun }}
                                <span class="ml-1 text-[11px] font-medium text-muted">({{ $monthNames[$record->bulan] ?? '-' }})</span>
                            </td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ number_format($record->total_pasien_bulanan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell">{{ number_format($record->kapasitas_maksimal_bulanan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3.5 tabular-nums-cell font-extrabold text-primary-dark">{{ $record->persentase_keramaian }}%</td>
                            <td class="px-6 py-3.5 {{ $densityTone }}">{{ $density }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-muted">Belum ada catatan kunjungan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-primary/10 bg-surface-mint/25 px-6 py-3.5">
            {{ $records->links() }}
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const totalInput = document.getElementById('total_pasien_bulanan');
            const capInput = document.getElementById('kapasitas_maksimal_bulanan');
            const preview = document.getElementById('live-density-preview');
            if (!totalInput || !capInput || !preview) { return; }

            function updateDensity() {
                const total = Number(totalInput.value || 0);
                const cap = Number(capInput.value || 0);
                if (!totalInput.value || cap <= 0) {
                    preview.textContent = '';
                    return;
                }
                const pct = ((total / cap) * 100).toFixed(1);
                const status = pct >= 85 ? 'Padat' : (pct >= 50 ? 'Sedang' : 'Longgar');
                preview.textContent = 'Pratinjau keramaian: ' + pct + '% (' + status + ')';
            }

            totalInput.addEventListener('input', updateDensity);
            capInput.addEventListener('input', updateDensity);
        });
    </script>
</x-dashboard-shell>
