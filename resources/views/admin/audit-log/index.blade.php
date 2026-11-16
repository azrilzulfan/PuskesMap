<x-dashboard-shell
    title="Audit Log Aktivitas - Admin PuskesMap"
    greeting="Audit Log Perubahan Data"
    subtitle="Keamanan & transparansi riwayat mutasi master Puskesmas, operasional, Nakes, dan akun"
>
    {{-- Row 1: Bento Filter + Highlight Count Card --}}
    <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
        <form method="GET" action="{{ route('admin.audit-log.index') }}"
              class="lg:col-span-8 flex flex-col justify-between rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div>
                <h2 class="text-base font-extrabold text-ink">Saring Catatan Aktivitas</h2>
                <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-3 sm:items-end">
                    <div>
                        <label for="aksi" class="block text-xs font-bold text-ink">Aksi</label>
                        <select id="aksi" name="aksi"
                                class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                            <option value="">Semua Aksi</option>
                            @foreach ($actions as $action)
                                <option value="{{ $action->value }}" @selected($selectedAction === $action->value)>{{ $action->value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="tabel_target" class="block text-xs font-bold text-ink">Tabel Target</label>
                        <select id="tabel_target" name="tabel_target"
                                class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                            <option value="">Semua Tabel</option>
                            @foreach (['puskesmas', 'operasional', 'tenaga_medis', 'kunjungan_pasien', 'users'] as $table)
                                <option value="{{ $table }}" @selected($selectedTable === $table)>{{ $table }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center gap-2">
                        <button type="submit"
                                class="min-h-11 flex-1 rounded-xl bg-gradient-to-r from-primary to-primary-dark px-5 py-2 text-xs font-extrabold text-white shadow-xs transition hover:opacity-95 cursor-pointer">
                            Filter Log
                        </button>
                        @if ($selectedAction !== null || $selectedTable !== null)
                            <a href="{{ route('admin.audit-log.index') }}"
                               class="inline-flex min-h-11 items-center justify-center rounded-xl bg-surface-mint px-4 py-2 text-xs font-bold text-primary-dark hover:bg-card-mint">
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <article class="lg:col-span-4 flex flex-col justify-between rounded-[24px] bg-gradient-to-br from-[#138282] via-primary to-primary-dark p-6 text-white shadow-[0_14px_30px_rgba(0,121,121,0.22)]">
            <div>
                <p class="text-xs font-semibold text-white/80">Total Catatan Audit</p>
                <p class="mt-2 text-3xl font-extrabold tracking-tight tabular-nums">{{ $logs->total() }} <span class="text-sm font-bold text-white/80">entri</span></p>
            </div>
            <p class="mt-4 border-t border-white/15 pt-3 text-xs font-medium text-white/85">
                Menampilkan {{ $logs->count() }} baris pada halaman ini
            </p>
        </article>
    </section>

    {{-- Row 2: Tabel Audit Log --}}
    <section class="overflow-hidden rounded-[24px] bg-white shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-primary/10 text-left text-xs">
                <thead class="bg-surface-mint/70 text-[11px] font-extrabold uppercase tracking-wider text-primary-dark">
                    <tr>
                        <th class="px-6 py-3.5">Waktu (WIB)</th>
                        <th class="px-4 py-3.5">Pengguna</th>
                        <th class="px-4 py-3.5">Aksi</th>
                        <th class="px-4 py-3.5">Tabel Target</th>
                        <th class="px-4 py-3.5">Deskripsi Perubahan</th>
                        <th class="px-6 py-3.5">IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($logs as $log)
                        @php
                            $actionValue = $log->aksi->value;
                            $actionTone = match ($actionValue) {
                                'DELETE', 'DEACTIVATE' => 'text-accent-strong font-extrabold',
                                'INSERT' => 'text-primary-dark font-extrabold',
                                default => 'text-primary font-bold',
                            };
                        @endphp
                        <tr class="hover:bg-surface-mint/35 transition-colors">
                            <td class="px-6 py-3.5 whitespace-nowrap text-muted tabular-nums">{{ $log->created_at?->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                            <td class="px-4 py-3.5 font-extrabold text-ink">{{ $log->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3.5 font-mono text-[11px] {{ $actionTone }}">{{ $actionValue }}</td>
                            <td class="px-4 py-3.5 font-mono text-[11px] text-primary-dark">{{ $log->tabel_target }}#{{ $log->target_id }}</td>
                            <td class="px-4 py-3.5 text-body">{{ $log->deskripsi_perubahan }}</td>
                            <td class="px-6 py-3.5 font-mono text-[11px] text-muted">{{ $log->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-muted">
                                <p>Belum ada catatan aktivitas.</p>
                                @if ($selectedAction !== null || $selectedTable !== null)
                                    <a href="{{ route('admin.audit-log.index') }}"
                                       class="mt-3 inline-flex min-h-9 items-center rounded-xl bg-surface-mint px-4 py-1.5 text-xs font-bold text-primary-dark hover:bg-card-mint">
                                        Tampilkan Semua Log
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="border-t border-primary/10 bg-surface-mint/25 px-6 py-3.5">
            {{ $logs->links() }}
        </div>
    </section>
</x-dashboard-shell>
