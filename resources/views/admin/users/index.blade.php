<x-layout title="Manajemen Pengguna - Admin PuskesMap" body-class="min-h-dvh bg-[#f5f8f7] text-ink font-sans">
    <x-internal-nav />

    <main class="mx-auto max-w-6xl space-y-6 px-4 py-6 sm:px-6 lg:px-8">
        <div>
            <p class="text-xs font-extrabold uppercase tracking-wider text-primary">Panel Administrator</p>
            <h1 class="mt-1 text-2xl font-extrabold text-ink">Manajemen Akun Pengguna &amp; Role</h1>
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

        <form method="POST" action="{{ route('admin.users.store') }}"
              class="grid grid-cols-1 gap-4 rounded-2xl border border-gray-200 bg-white p-5 sm:grid-cols-6 sm:items-end">
            @csrf
            <div class="sm:col-span-2">
                <label for="name" class="block text-xs font-bold text-ink">Nama Lengkap</label>
                <input id="name" name="name" type="text" required value="{{ old('name') }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div class="sm:col-span-2">
                <label for="email" class="block text-xs font-bold text-ink">Email</label>
                <input id="email" name="email" type="email" required value="{{ old('email') }}"
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div>
                <label for="role" class="block text-xs font-bold text-ink">Peran</label>
                <select id="role" name="role" required
                        class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
                    @foreach ($roles as $role)
                        <option value="{{ $role->value }}" @selected(old('role') === $role->value)>{{ $role->label() }}</option>
                    @endforeach
                </select>
            </div>
            <div class="sm:col-span-2">
                <label for="password" class="block text-xs font-bold text-ink">Kata Sandi</label>
                <input id="password" name="password" type="password" required
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div class="sm:col-span-2">
                <label for="password_confirmation" class="block text-xs font-bold text-ink">Konfirmasi Kata Sandi</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                       class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2 text-sm text-ink">
            </div>
            <div class="sm:col-span-2">
                <button type="submit"
                        class="w-full rounded-xl bg-primary px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-primary-dark cursor-pointer">
                    + Tambah Akun
                </button>
            </div>
        </form>

        <section class="overflow-hidden rounded-2xl border border-gray-200 bg-white">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                    <thead class="bg-surface-mint/60 font-bold text-primary-dark">
                        <tr>
                            <th class="px-4 py-3">Nama</th>
                            <th class="px-4 py-3">Email</th>
                            <th class="px-4 py-3">Peran</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-bold text-ink">{{ $user->name }}</td>
                                <td class="px-4 py-3 text-muted">{{ $user->email }}</td>
                                <td class="px-4 py-3">{{ $user->role->label() }}</td>
                                <td class="px-4 py-3">
                                    <span class="rounded-full px-2.5 py-0.5 text-[11px] font-bold {{ $user->is_active ? 'bg-surface-mint text-primary-dark' : 'bg-red-100 text-red-700' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    @if ($user->is_active && ! auth()->user()?->is($user))
                                        <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="rounded-lg border border-red-200 px-3 py-1 font-bold text-red-700 hover:bg-red-50 cursor-pointer">
                                                Nonaktifkan
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[11px] text-muted">{{ auth()->user()?->is($user) ? 'Akun Aktif Anda' : 'Nonaktif' }}</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-gray-100 px-5 py-3">
                {{ $users->links() }}
            </div>
        </section>
    </main>
</x-layout>
