<x-layout title="Kelola Puskesmas - Admin PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-col justify-between gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:flex-row sm:items-center">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-primary">Panel Administrator</p>
                <h1 class="mt-1 text-2xl font-extrabold text-ink">Manajemen Data Puskesmas Kota Bogor</h1>
            </div>
            <a href="{{ route('admin.puskesmas.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-primary px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary-dark">
                + Tambah Puskesmas
            </a>
        </div>

        @if (session('status'))
            <div class="rounded-xl border border-primary/30 bg-surface-mint px-4 py-3 text-xs font-bold text-primary-dark">
                {{ session('status') }}
            </div>
        @endif

        <form method="GET" action="{{ route('admin.puskesmas.index') }}"
              class="grid grid-cols-1 gap-3 rounded-2xl border border-gray-200 bg-white p-4 sm:grid-cols-4 sm:items-end">
            <div class="sm:col-span-2">
                <label for="q" class="block text-xs font-bold text-muted">Cari Nama atau Kode</label>
                <input id="q" name="q" type="text" value="{{ $search }}" placeholder="Contoh: Tanah Sareal atau P3271..."
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div>
                <label for="kecamatan_id" class="block text-xs font-bold text-muted">Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id"
                        class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                    <option value="">Semua Kecamatan</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" @selected($selectedKecamatanId === $district->id)>
                            {{ $district->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="rounded-xl bg-ink px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary cursor-pointer">
                Cari Data
            </button>
        </form>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-surface-mint/60 font-bold text-primary-dark">
                        <tr>
                            <th class="px-4 py-3">Kode</th>
                            <th class="px-4 py-3">Nama Puskesmas</th>
                            <th class="px-4 py-3">Kecamatan</th>
                            <th class="px-4 py-3">Layanan</th>
                            <th class="px-4 py-3 text-right">Nakes</th>
                            <th class="px-4 py-3 text-right">PSW</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($puskesmas as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-mono text-[11px] text-muted">{{ $item->kode_puskesmas }}</td>
                                <td class="px-4 py-3">
                                    <div class="font-bold text-ink">{{ $item->nama_puskesmas }}</div>
                                    <div class="text-[11px] text-muted">{{ $item->y_lat }}, {{ $item->x_long }}</div>
                                </td>
                                <td class="px-4 py-3">{{ $item->kecamatan?->nama_kecamatan }}</td>
                                <td class="px-4 py-3">
                                    <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-bold text-primary-dark">
                                        {{ $item->operasional?->status_igd_24jam ? 'IGD 24 Jam' : $item->kategori_layanan->value }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">{{ $item->tenagaMedis?->persentase_kelengkapan ?? 0 }}%</td>
                                <td class="px-4 py-3 text-right font-bold text-primary">{{ number_format((float) ($item->skor_psw ?? 0), 2) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex flex-wrap items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.puskesmas.edit', $item) }}"
                                           class="rounded-lg border border-gray-200 px-2.5 py-1 font-bold text-ink hover:border-primary hover:text-primary">
                                            Profil
                                        </a>
                                        <a href="{{ route('admin.operasional.edit', $item) }}"
                                           class="rounded-lg border border-gray-200 px-2.5 py-1 font-bold text-ink hover:border-primary hover:text-primary">
                                            Operasional &amp; Nakes
                                        </a>
                                        <a href="{{ route('admin.kunjungan.index', $item) }}"
                                           class="rounded-lg border border-gray-200 px-2.5 py-1 font-bold text-ink hover:border-primary hover:text-primary">
                                            Kunjungan
                                        </a>
                                        <form method="POST" action="{{ route('admin.puskesmas.destroy', $item) }}"
                                              onsubmit="return confirm('Hapus Puskesmas ini beserta data terkaitnya?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 px-2.5 py-1 font-bold text-red-700 hover:bg-red-50 cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-8 text-center text-muted">Tidak ada data Puskesmas ditemukan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $puskesmas->links() }}
            </div>
        </section>
    </main>
</x-layout>
