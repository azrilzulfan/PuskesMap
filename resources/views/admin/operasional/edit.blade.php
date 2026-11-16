@php
    $nakesPct = (float) ($puskesmas->tenagaMedis?->persentase_kelengkapan ?? 0);
    $nakesLabel = $puskesmas->tenagaMedis?->kategori_jumlah_nakes?->value ?? 'Kurang Lengkap';
    $totalNakes = $puskesmas->tenagaMedis?->totalCount() ?? 0;
    $isIgd = (bool) ($puskesmas->operasional?->status_igd_24jam ?? false);
@endphp

<x-dashboard-shell
    title="Operasional & Tenaga Medis - Admin PuskesMap"
    :greeting="$puskesmas->nama_puskesmas"
    :subtitle="$puskesmas->kode_puskesmas.' · Kec. '.($puskesmas->kecamatan?->nama_kecamatan ?? '-')"
>
    <x-slot:headerActions>
        <a href="{{ route('admin.kunjungan.index', $puskesmas) }}"
           class="inline-flex h-11 items-center rounded-full bg-gradient-to-r from-primary to-primary-dark px-5 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95">
            Kelola Kunjungan
        </a>
        <a href="{{ route('admin.puskesmas.index') }}"
           class="inline-flex h-11 items-center rounded-full bg-white px-5 text-xs font-extrabold text-primary-dark shadow-[0_6px_20px_rgba(0,121,121,0.06)] transition hover:bg-surface-mint">
            &larr; Kembali ke Daftar
        </a>
    </x-slot:headerActions>

    {{-- Row 1: 4 Compact Bento Metric Cards --}}
    <section class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <p class="text-xs font-semibold text-muted">Kelengkapan 9 Nakes</p>
                <p class="mt-1.5 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $nakesPct }}%</p>
            </div>
            <div class="flex h-10 items-end gap-1.5" aria-hidden="true">
                <span class="w-1.5 h-5 rounded-full bg-surface-mint"></span>
                <span class="w-1.5 h-9 rounded-full bg-primary-dark"></span>
                <span class="w-1.5 h-7 rounded-full bg-primary"></span>
                <span class="w-1.5 h-10 rounded-full bg-primary-dark"></span>
            </div>
        </article>

        <article class="flex items-center gap-3.5 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-surface-mint text-primary-dark" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                    <circle cx="9" cy="7" r="4"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-semibold text-muted">Total Tenaga Medis</p>
                <p class="mt-1 text-2xl font-extrabold tracking-tight text-ink tabular-nums">{{ $totalNakes }}</p>
            </div>
        </article>

        <article class="flex items-center gap-3.5 rounded-[22px] bg-white p-5 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <span class="flex size-11 shrink-0 items-center justify-center rounded-full bg-card-peach/65 text-accent-strong" aria-hidden="true">
                <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <circle cx="12" cy="12" r="9"/>
                    <path stroke-linecap="round" d="M12 7v5l3 2"/>
                </svg>
            </span>
            <div>
                <p class="text-xs font-semibold text-muted">Jam Poli Reguler</p>
                <p class="mt-1 text-lg font-extrabold tracking-tight text-ink tabular-nums">
                    {{ substr((string) ($puskesmas->operasional?->jam_buka ?? '07:30'), 0, 5) }}–{{ substr((string) ($puskesmas->operasional?->jam_tutup ?? '14:00'), 0, 5) }}
                </p>
            </div>
        </article>

        <article class="flex items-center justify-between gap-3 rounded-[22px] bg-gradient-to-br from-[#138282] via-primary to-primary-dark p-5 text-white shadow-[0_14px_30px_rgba(0,121,121,0.22)]">
            <div>
                <p class="text-xs font-semibold text-white/80">Kesiagaan UGD</p>
                <p class="mt-1.5 text-xl font-extrabold tracking-tight text-white">{{ $isIgd ? 'IGD 24 Jam' : 'Poli Reguler' }}</p>
            </div>
            <svg class="h-9 w-16 text-white/80 shrink-0" fill="none" stroke="currentColor" stroke-width="2.4" viewBox="0 0 64 36" aria-hidden="true">
                <path stroke-linecap="round" d="M2 26 C14 8, 22 8, 30 20 C38 30, 48 10, 62 12"/>
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

    @if ($errors->any())
        <div role="alert" class="rounded-[20px] border-l-4 border-[#B42318] bg-white px-5 py-3.5 text-xs text-[#B42318] shadow-[0_6px_20px_rgba(0,121,121,0.06)]">
            <ul class="list-disc space-y-1 pl-4 font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Row 2: 2 Asymmetric Bento Cards (5 + 7) --}}
    <div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <form method="POST" action="{{ route('admin.operasional.update', $puskesmas) }}"
              class="lg:col-span-5 flex flex-col justify-between space-y-5 rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            @csrf
            @method('PUT')
            <div class="space-y-4">
                <h2 class="border-b border-primary/10 pb-3 text-base font-extrabold text-ink">Status &amp; Jam Operasional</h2>

                <div class="grid grid-cols-1 gap-2 rounded-2xl bg-[#f7fbfa] p-4 sm:grid-cols-2">
                    <label class="flex min-h-10 items-center gap-2.5 text-xs font-bold text-ink cursor-pointer">
                        <input type="checkbox" name="status_igd_24jam" value="1" class="size-4 accent-primary"
                               @checked(old('status_igd_24jam', $puskesmas->operasional?->status_igd_24jam ?? false))>
                        <span>IGD 24 Jam</span>
                    </label>
                    <label class="flex min-h-10 items-center gap-2.5 text-xs font-bold text-ink cursor-pointer">
                        <input type="checkbox" name="rawat_inap" value="1" class="size-4 accent-primary"
                               @checked(old('rawat_inap', $puskesmas->operasional?->rawat_inap ?? false))>
                        <span>Rawat Inap</span>
                    </label>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="jam_buka" class="block text-xs font-bold text-ink">Jam Buka Poli</label>
                        <input id="jam_buka" name="jam_buka" type="text" required placeholder="07:30:00"
                               value="{{ old('jam_buka', $puskesmas->operasional?->jam_buka ?? '07:30:00') }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="jam_tutup" class="block text-xs font-bold text-ink">Jam Tutup Poli</label>
                        <input id="jam_tutup" name="jam_tutup" type="text" required placeholder="14:00:00"
                               value="{{ old('jam_tutup', $puskesmas->operasional?->jam_tutup ?? '14:00:00') }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                    </div>
                </div>
                <p class="text-[11px] text-muted">Format waktu 24 jam, contoh <span class="font-mono font-semibold text-ink">07:30:00</span> atau <span class="font-mono font-semibold text-ink">00:00:00</span>.</p>

                <div>
                    <label for="kontak_darurat" class="block text-xs font-bold text-ink">Kontak Darurat</label>
                    <input id="kontak_darurat" name="kontak_darurat" type="text" placeholder="0251-8xxxxxx"
                           value="{{ old('kontak_darurat', $puskesmas->operasional?->kontak_darurat ?? '') }}"
                           class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">
                </div>
            </div>

            <button type="submit"
                    class="min-h-11 w-full rounded-xl bg-gradient-to-r from-primary to-primary-dark px-5 py-2.5 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95 cursor-pointer">
                Simpan Status Operasional
            </button>
        </form>

        <form method="POST" action="{{ route('admin.tenaga-medis.update', $puskesmas) }}"
              class="lg:col-span-7 flex flex-col justify-between space-y-5 rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            @csrf
            @method('PUT')
            <div>
                <div class="flex flex-wrap items-baseline justify-between gap-2 border-b border-primary/10 pb-3">
                    <h2 class="text-base font-extrabold text-ink">9 Jenis Tenaga Medis</h2>
                    <span id="live-nakes-summary" class="text-xs font-extrabold tabular-nums {{ $nakesPct >= 100 ? 'text-primary-dark' : 'text-accent-strong' }}">
                        {{ $nakesPct }}% &middot; {{ $nakesLabel }}
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

                <div class="mt-4 grid grid-cols-2 gap-3.5 sm:grid-cols-3">
                    @foreach ($professions as $field => $label)
                        <div>
                            <label for="{{ $field }}" class="block text-[11px] font-bold text-muted">{{ $label }}</label>
                            <input id="{{ $field }}" name="{{ $field }}" type="number" min="0" required
                                   value="{{ old($field, $puskesmas->tenagaMedis?->{$field} ?? 0) }}"
                                   class="nakes-count-input mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3 py-2 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                        </div>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                    class="min-h-11 w-full rounded-xl bg-accent px-5 py-2.5 text-xs font-extrabold text-white shadow-xs transition hover:bg-accent-strong cursor-pointer">
                Simpan &amp; Hitung Ulang Kelengkapan Nakes
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputs = Array.from(document.querySelectorAll('.nakes-count-input'));
            const summary = document.getElementById('live-nakes-summary');
            if (inputs.length === 0 || !summary) { return; }

            function updatePreview() {
                const filled = inputs.filter((el) => Number(el.value || 0) > 0).length;
                const pct = ((filled / 9) * 100).toFixed(2);
                const label = filled >= 9 ? 'Lengkap' : 'Kurang Lengkap';
                summary.textContent = pct + '% · ' + label + ' (' + filled + '/9 jenis)';
                summary.classList.toggle('text-primary-dark', filled >= 9);
                summary.classList.toggle('text-accent-strong', filled < 9);
            }

            inputs.forEach((el) => el.addEventListener('input', updatePreview));
        });
    </script>
</x-dashboard-shell>
