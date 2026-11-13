<x-layout title="Audit Log Aktivitas - Admin PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-7xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-primary">Keamanan &amp; Transparansi</p>
            <h1 class="mt-1 text-2xl font-extrabold text-ink">Audit Log Perubahan Data Sistem</h1>
        </div>

        <form method="GET" action="{{ route('admin.audit-log.index') }}"
              class="grid grid-cols-1 gap-3 rounded-2xl border border-gray-200 bg-white p-4 sm:grid-cols-3 sm:items-end">
            <div>
                <label for="aksi" class="block text-xs font-bold text-muted">Aksi</label>
                <select id="aksi" name="aksi"
                        class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                    <option value="">Semua Aksi</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action->value }}" @selected($selectedAction === $action->value)>{{ $action->value }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="tabel_target" class="block text-xs font-bold text-muted">Tabel Target</label>
                <select id="tabel_target" name="tabel_target"
                        class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                    <option value="">Semua Tabel</option>
                    @foreach (['puskesmas', 'operasional', 'tenaga_medis', 'kunjungan_pasien', 'users'] as $table)
                        <option value="{{ $table }}" @selected($selectedTable === $table)>{{ $table }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="rounded-xl bg-ink px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary cursor-pointer">
                Filter Log
            </button>
        </form>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-surface-mint/60 font-bold text-primary-dark">
                        <tr>
                            <th class="px-4 py-3">Waktu</th>
                            <th class="px-4 py-3">Pengguna</th>
                            <th class="px-4 py-3">Aksi</th>
                            <th class="px-4 py-3">Tabel Target</th>
                            <th class="px-4 py-3">Deskripsi Perubahan</th>
                            <th class="px-4 py-3">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($logs as $log)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 whitespace-nowrap text-muted">{{ $log->created_at?->timezone('Asia/Jakarta')->format('d M Y H:i') }}</td>
                                <td class="px-4 py-3 font-bold text-ink">{{ $log->user?->name ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full bg-surface-mint px-2.5 py-0.5 text-[11px] font-bold text-primary-dark">
                                        {{ $log->aksi->value }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-mono text-[11px]">{{ $log->tabel_target }}#{{ $log->target_id }}</td>
                                <td class="px-4 py-3">{{ $log->deskripsi_perubahan }}</td>
                                <td class="px-4 py-3 font-mono text-[11px] text-muted">{{ $log->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-muted">Belum ada catatan aktivitas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $logs->links() }}
            </div>
        </section>
    </main>
</x-layout>
