<x-layout title="Kunjungan Pasien Bulanan - Admin PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-5xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-primary">Log Kunjungan Bulanan</p>
                <h1 class="mt-1 text-2xl font-extrabold text-ink">{{ $puskesmas->nama_puskesmas }}</h1>
            </div>
            <a href="{{ route('admin.puskesmas.index') }}" class="text-xs font-bold text-primary hover:underline">&larr; Kembali ke Daftar</a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-primary/30 bg-surface-mint px-4 py-3 text-xs font-bold text-primary-dark">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.kunjungan.store', $puskesmas) }}"
              class="grid grid-cols-1 gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:grid-cols-5 sm:items-end">
            @csrf
            <div>
                <label for="bulan" class="block text-xs font-bold text-ink">Bulan (1-12)</label>
                <input id="bulan" name="bulan" type="number" min="1" max="12" required value="{{ old('bulan', now()->month) }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div>
                <label for="tahun" class="block text-xs font-bold text-ink">Tahun</label>
                <input id="tahun" name="tahun" type="number" min="2020" max="2100" required value="{{ old('tahun', 2025) }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div>
                <label for="total_pasien_bulanan" class="block text-xs font-bold text-ink">Total Pasien</label>
                <input id="total_pasien_bulanan" name="total_pasien_bulanan" type="number" min="0" required value="{{ old('total_pasien_bulanan') }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div>
                <label for="kapasitas_maksimal_bulanan" class="block text-xs font-bold text-ink">Kapasitas Max</label>
                <input id="kapasitas_maksimal_bulanan" name="kapasitas_maksimal_bulanan" type="number" min="1" value="{{ old('kapasitas_maksimal_bulanan', $defaultCapacity) }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <button type="submit"
                    class="rounded-xl bg-primary px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary-dark cursor-pointer">
                Simpan Kunjungan
            </button>
        </form>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-surface-mint/60 font-bold text-primary-dark">
                        <tr>
                            <th class="px-4 py-3">Periode</th>
                            <th class="px-4 py-3 text-right">Total Pasien</th>
                            <th class="px-4 py-3 text-right">Kapasitas Maksimal</th>
                            <th class="px-4 py-3 text-right">Keramaian (%)</th>
                            <th class="px-4 py-3">Status Kepadatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($records as $record)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold text-ink">{{ str_pad((string) $record->bulan, 2, '0', STR_PAD_LEFT) }}/{{ $record->tahun }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($record->total_pasien_bulanan, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right">{{ number_format($record->kapasitas_maksimal_bulanan, 0, ',', '.') }}</td>
                                <td class="px-4 py-3 text-right font-bold">{{ $record->persentase_keramaian }}%</td>
                                <td class="px-4 py-3 font-semibold">{{ $record->status_kepadatan->value }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-8 text-center text-muted">Belum ada catatan kunjungan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $records->links() }}
            </div>
        </section>
    </main>
</x-layout>
