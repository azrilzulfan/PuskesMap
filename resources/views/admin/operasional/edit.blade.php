<x-layout title="Operasional & Tenaga Medis - Admin PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-5xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <p class="text-xs font-extrabold uppercase tracking-wider text-primary">{{ $puskesmas->kode_puskesmas }} &bull; Kec. {{ $puskesmas->kecamatan?->nama_kecamatan }}</p>
                <h1 class="mt-1 text-2xl font-extrabold text-ink">{{ $puskesmas->nama_puskesmas }}</h1>
            </div>
            <div class="flex items-center gap-3 text-xs font-bold">
                <a href="{{ route('admin.kunjungan.index', $puskesmas) }}" class="rounded-xl border border-gray-200 bg-white px-3.5 py-2 text-ink hover:border-primary hover:text-primary">
                    Kelola Kunjungan
                </a>
                <a href="{{ route('admin.puskesmas.index') }}" class="text-primary hover:underline">&larr; Kembali ke Daftar</a>
            </div>
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

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <form method="POST" action="{{ route('admin.operasional.update', $puskesmas) }}"
                  class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6">
                @csrf
                @method('PUT')
                <h2 class="border-b border-gray-100 pb-3 text-base font-extrabold text-ink">Status &amp; Jam Operasional</h2>

                <div class="grid grid-cols-2 gap-4">
                    <label class="flex items-center gap-2 text-xs font-bold text-ink">
                        <input type="checkbox" name="status_igd_24jam" value="1"
                               @checked(old('status_igd_24jam', $puskesmas->operasional?->status_igd_24jam ?? false))>
                        <span>IGD 24 Jam</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs font-bold text-ink">
                        <input type="checkbox" name="rawat_inap" value="1"
                               @checked(old('rawat_inap', $puskesmas->operasional?->rawat_inap ?? false))>
                        <span>Rawat Inap</span>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_buka" class="block text-xs font-bold text-ink">Jam Buka</label>
                        <input id="jam_buka" name="jam_buka" type="text" required
                               value="{{ old('jam_buka', $puskesmas->operasional?->jam_buka ?? '07:30:00') }}"
                               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                    </div>
                    <div>
                        <label for="jam_tutup" class="block text-xs font-bold text-ink">Jam Tutup</label>
                        <input id="jam_tutup" name="jam_tutup" type="text" required
                               value="{{ old('jam_tutup', $puskesmas->operasional?->jam_tutup ?? '15:00:00') }}"
                               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                    </div>
                </div>

                <div>
                    <label for="kontak_darurat" class="block text-xs font-bold text-ink">Kontak Darurat</label>
                    <input id="kontak_darurat" name="kontak_darurat" type="text"
                           value="{{ old('kontak_darurat', $puskesmas->operasional?->kontak_darurat ?? '') }}"
                           class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full rounded-xl bg-primary px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary-dark cursor-pointer">
                        Simpan Status Operasional
                    </button>
                </div>
            </form>

            <form method="POST" action="{{ route('admin.tenaga-medis.update', $puskesmas) }}"
                  class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6">
                @csrf
                @method('PUT')
                <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                    <h2 class="text-base font-extrabold text-ink">9 Jenis Tenaga Medis</h2>
                    <span class="rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-bold text-primary-dark">
                        {{ $puskesmas->tenagaMedis?->persentase_kelengkapan ?? 0 }}% ({{ $puskesmas->tenagaMedis?->kategori_jumlah_nakes?->value ?? 'Kurang Lengkap' }})
                    </span>
                </div>

                @php
                    $professions = [
                        'dokter_umum' => 'Dokter Umum',
                        'dokter_gigi' => 'Dokter Gigi',
                        'bidan' => 'Bidan',
                        'perawat' => 'Perawat',
                        'farmasi' => 'Farmasi / Apoteker',
                        'gizi' => 'Tenaga Gizi',
                        'kesling' => 'Kesehatan Lingkungan',
                        'kesmas' => 'Kesehatan Masyarakat',
                        'atlm' => 'ATLM / Laboratorium',
                    ];
                @endphp

                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                    @foreach ($professions as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-[11px] font-bold text-muted">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="number" min="0" required
                                   value="{{ old($field, $puskesmas->tenagaMedis?->{$field} ?? 0) }}"
                                   class="mt-1 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                        </div>
                    @endforeach
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full rounded-xl bg-ink px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary cursor-pointer">
                        Simpan &amp; Hitung Ulang Kelengkapan Nakes
                    </button>
                </div>
            </form>
        </div>
    </main>
</x-layout>
