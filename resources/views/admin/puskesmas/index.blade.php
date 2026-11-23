@php
    $totalCount = (int) ($summaryStats['total'] ?? $puskesmas->total());
    $igdCount = (int) ($summaryStats['igd_count'] ?? $puskesmas->getCollection()->filter(fn ($p) => (bool) $p->operasional?->status_igd_24jam)->count());
    $nakesCompleteCount = (int) ($summaryStats['nakes_complete_count'] ?? $puskesmas->getCollection()->filter(fn ($p) => (float) ($p->tenagaMedis?->persentase_kelengkapan ?? 0) >= 100)->count());
    $avgPsw = (float) ($summaryStats['avg_psw'] ?? ($puskesmas->getCollection()->avg('skor_psw') ?? 0.0));
@endphp

<x-dashboard-shell
    title="Kelola Puskesmas - Admin PuskesMap"
    greeting="Manajemen Data Puskesmas"
    subtitle="Kelola profil fasilitas, koordinat geodetik, kesiapan IGD 24 jam, Nakes, dan kunjungan"
    :search-action="route('admin.puskesmas.index')"
    search-name="q"
    :search-value="$search"
    search-placeholder="Cari nama atau kode puskesmas..."
    :search-params="['kecamatan_id' => $selectedKecamatanId]"
>
    <x-slot:headerActions>
        <a href="{{ route('admin.puskesmas.create') }}"
           class="inline-flex h-12 items-center justify-center gap-2 rounded-full bg-gradient-to-r from-primary to-primary-dark px-5 text-xs font-extrabold text-white shadow-[0_8px_20px_rgba(0,121,121,0.20)] transition hover:opacity-95">
            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/>
            </svg>
            <span>Tambah Puskesmas</span>
        </a>
    </x-slot:headerActions>

    {{-- Row 1: 4 Compact Bento Metric Cards (Kartu ke-4 Teal Gradient Highlight persis referensi) --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <p class="text-xs font-semibold text-muted">Total Puskesmas</p>
                <p class="mt-1.5 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $totalCount }}</p>
            </div>
            <div class="flex h-10 items-end gap-1.5" aria-hidden="true">
                <span class="w-1.5 h-5 rounded-full bg-surface-mint"></span>
                <span class="w-1.5 h-8 rounded-full bg-primary-dark"></span>
                <span class="w-1.5 h-6 rounded-full bg-primary/60"></span>
                <span class="w-1.5 h-10 rounded-full bg-primary"></span>
                <span class="w-1.5 h-7 rounded-full bg-primary-dark"></span>
            </div>
        </article>

        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div class="flex items-center gap-3.5">
                <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-surface-mint text-primary-dark" aria-hidden="true">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <p class="text-xs font-semibold text-muted">9 Nakes Lengkap</p>
                    <p class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $nakesCompleteCount }}</p>
                </div>
            </div>
            <svg class="h-8 w-14 text-primary/45 shrink-0" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 56 32" aria-hidden="true">
                <path stroke-linecap="round" d="M2 28 C14 28, 18 16, 28 16 C38 16, 42 4, 54 4"/>
            </svg>
        </article>

        <article class="flex items-center gap-3.5 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-card-peach/65 text-accent-strong" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M12 7v5l3 2"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-semibold text-muted">Siaga IGD 24 Jam</p>
                <p class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $igdCount }}</p>
            </div>
        </article>

        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-gradient-to-br from-[#138282] via-primary to-primary-dark p-5 text-white shadow-[0_14px_30px_rgba(0,121,121,0.22)]">
            <div>
                <p class="text-xs font-semibold text-white/80">Rata-rata PSW</p>
                <p class="mt-1.5 text-2xl font-extrabold tracking-tight text-white tabular-nums">{{ number_format((float) $avgPsw, 2) }}</p>
            </div>
            <svg class="h-9 w-20 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 80 36" aria-hidden="true">
                <path stroke-linecap="round" d="M2 28 C12 6, 22 6, 30 20 C38 34, 48 10, 62 12 C70 13, 74 20, 78 24"/>
            </svg>
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

    {{-- Row 2: Compact Filter Bar Card --}}
    <form method="GET" action="{{ route('admin.puskesmas.index') }}"
          class="grid grid-cols-1 gap-4 rounded-[24px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)] sm:grid-cols-12 sm:items-end">
        <div class="sm:col-span-5">
            <label for="q" class="block text-xs font-bold text-ink">Cari Nama atau Kode</label>
            <input id="q" name="q" type="text" value="{{ $search }}" placeholder="Contoh: Tanah Sareal atau P3271..."
                   class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink placeholder:text-placeholder transition focus:bg-white focus:border-primary">
        </div>
        <div class="sm:col-span-4">
            <label for="kecamatan_id" class="block text-xs font-bold text-ink">Filter Kecamatan</label>
            <select id="kecamatan_id" name="kecamatan_id"
                    class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink transition focus:bg-white focus:border-primary">
                <option value="">Semua Kecamatan</option>
                @foreach ($districts as $district)
                    <option value="{{ $district->id }}" @selected($selectedKecamatanId === $district->id)>
                        {{ $district->nama_kecamatan }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="sm:col-span-3 flex items-center gap-2">
            <button type="submit"
                    class="min-h-11 flex-1 rounded-xl bg-gradient-to-r from-primary to-primary-dark px-5 py-2 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95 cursor-pointer">
                Terapkan Filter
            </button>
            @if ($search !== '' || $selectedKecamatanId !== null)
                <a href="{{ route('admin.puskesmas.index') }}"
                   class="inline-flex min-h-11 items-center justify-center rounded-xl bg-surface-mint px-4 py-2 text-xs font-bold text-primary-dark hover:bg-card-mint">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Row 3: Tabel Daftar Fasilitas --}}
    <section class="overflow-hidden rounded-[24px] bg-white shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <div class="flex flex-wrap items-baseline justify-between gap-2 border-b border-primary/10 px-6 py-4">
            <h2 class="text-base font-extrabold text-ink">Daftar Fasilitas Puskesmas Induk</h2>
            <span class="text-xs font-bold text-muted tabular-nums">
                Menampilkan {{ $puskesmas->count() }} dari {{ $puskesmas->total() }} unit
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-primary/10 text-left text-xs">
                <thead class="bg-surface-mint/70 text-[11px] font-extrabold uppercase tracking-wider text-primary-dark">
                    <tr>
                        <th class="px-6 py-3.5">Kode</th>
                        <th class="px-4 py-3.5">Nama Puskesmas</th>
                        <th class="px-4 py-3.5">Kecamatan</th>
                        <th class="px-4 py-3.5">Layanan</th>
                        <th class="px-4 py-3.5 text-right">Nakes</th>
                        <th class="px-4 py-3.5 text-right">Skor PSW</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($puskesmas as $item)
                        @php
                            $pswScore = (float) ($item->skor_psw ?? 0);
                            $spectrum = \App\Enums\WorkloadSpectrum::fromScore($pswScore)->value;
                            $nakesPct = (float) ($item->tenagaMedis?->persentase_kelengkapan ?? 0);
                        @endphp
                        <tr class="hover:bg-surface-mint/35 transition-colors">
                            <td class="px-6 py-3.5 font-mono text-[11px] text-muted">{{ $item->kode_puskesmas }}</td>
                            <td class="px-4 py-3.5">
                                <div class="font-extrabold text-ink">{{ $item->nama_puskesmas }}</div>
                                <div class="text-[11px] text-muted tabular-nums">{{ number_format((float) $item->y_lat, 6) }}, {{ number_format((float) $item->x_long, 6) }}</div>
                            </td>
                            <td class="px-4 py-3.5 font-medium text-body">{{ $item->kecamatan?->nama_kecamatan }}</td>
                            <td class="px-4 py-3.5 font-bold {{ $item->operasional?->status_igd_24jam ? 'text-accent-strong' : 'text-primary-dark' }}">
                                {{ $item->operasional?->status_igd_24jam ? 'IGD 24 Jam' : $item->kategori_layanan->value }}
                            </td>
                            <td class="px-4 py-3.5 text-right tabular-nums font-bold {{ $nakesPct >= 100 ? 'text-primary-dark' : 'text-accent-strong' }}">
                                {{ $nakesPct }}%
                            </td>
                            <td class="px-4 py-3.5 text-right tabular-nums">
                                <span class="font-extrabold text-primary-dark">{{ number_format($pswScore, 2) }}</span>
                                <span class="ml-1 text-[11px] font-semibold text-muted">({{ $spectrum }})</span>
                            </td>
                            <td class="px-6 py-3.5 text-right whitespace-nowrap">
                                <div class="flex flex-wrap items-center justify-end gap-1.5">
                                    <a href="{{ route('admin.puskesmas.edit', $item) }}"
                                       class="inline-flex min-h-9 items-center rounded-xl bg-surface-mint/70 px-3 py-1 text-[11px] font-bold text-primary-dark transition hover:bg-surface-mint">
                                        Profil
                                    </a>
                                    <a href="{{ route('admin.operasional.edit', $item) }}"
                                       class="inline-flex min-h-9 items-center rounded-xl bg-surface-mint/70 px-3 py-1 text-[11px] font-bold text-primary-dark transition hover:bg-surface-mint">
                                        Operasional &amp; Nakes
                                    </a>
                                    <a href="{{ route('admin.kunjungan.index', $item) }}"
                                       class="inline-flex min-h-9 items-center rounded-xl bg-surface-mint/70 px-3 py-1 text-[11px] font-bold text-primary-dark transition hover:bg-surface-mint">
                                        Kunjungan
                                    </a>
                                    <form method="POST" action="{{ route('admin.puskesmas.destroy', $item) }}"
                                          onsubmit="return confirm('Hapus Puskesmas ini beserta data terkaitnya?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex min-h-9 items-center rounded-xl border border-[#B42318]/25 px-3 py-1 text-[11px] font-bold text-[#B42318] transition hover:bg-[#FDECEC] cursor-pointer">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center text-muted">
                                <p>Tidak ada data Puskesmas ditemukan.</p>
                                @if ($search !== '' || $selectedKecamatanId !== null)
                                    <a href="{{ route('admin.puskesmas.index') }}"
                                       class="mt-3 inline-flex min-h-9 items-center rounded-xl bg-surface-mint px-4 py-1.5 text-xs font-bold text-primary-dark hover:bg-card-mint">
                                        Tampilkan Semua Puskesmas
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-primary/10 bg-surface-mint/25 px-6 py-3.5">
            {{ $puskesmas->links() }}
        </div>
    </section>
</x-dashboard-shell>
