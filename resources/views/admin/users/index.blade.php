@php
    $activeUsersCount = $activeUsersTotal ?? $users->getCollection()->where('is_active', true)->count();
    $adminUsersCount = $adminUsersTotal ?? $users->getCollection()->filter(fn ($u) => $u->isAdmin())->count();
    $isCreateOld = old('_form_context') === 'create';
    $isEditOld = old('_form_context') === 'edit';
    $initialEditUser = [
        'id' => $isEditOld ? (old('_edit_user_id') ? (int) old('_edit_user_id') : null) : null,
        'name' => $isEditOld ? (string) old('name', '') : '',
        'email' => $isEditOld ? (string) old('email', '') : '',
        'role' => $isEditOld ? (string) old('role', 'pemerintah') : 'pemerintah',
        'is_active' => $isEditOld ? (string) old('is_active', '1') : '1',
        'update_url' => $isEditOld ? (string) old('_edit_update_url', '#') : '#',
        'is_self' => $isEditOld && ((int) old('_edit_user_id') === (int) auth()->id()),
    ];
@endphp

<x-dashboard-shell
    title="Manajemen Pengguna - Admin PuskesMap"
    greeting="Manajemen Akun & Role"
    subtitle="Kelola akun internal Dinas Kesehatan dan Administrator (soft-deactivation menjaga audit_log)"
>
    <div x-data="{
            isEditOpen: {{ $isEditOld ? 'true' : 'false' }},
            editingUser: {{ \Illuminate\Support\Js::from($initialEditUser) }},
            openEdit(user) {
                this.editingUser = {
                    ...user,
                    is_active: user.is_active ? '1' : '0'
                };
                this.isEditOpen = true;
            },
            closeEdit() {
                this.isEditOpen = false;
            }
        }"
        class="space-y-5">

        {{-- Row 1: Bento Form Tambah Akun + Highlight Card --}}
        <section class="grid grid-cols-1 gap-5 lg:grid-cols-12">
            <form method="POST" action="{{ route('admin.users.store') }}"
                  class="lg:col-span-8 rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
                @csrf
                <input type="hidden" name="_form_context" value="create">
                <h2 class="text-base font-extrabold text-ink">Tambah Akun Internal Baru</h2>

                <div class="mt-4 grid grid-cols-1 gap-3.5 sm:grid-cols-2 lg:grid-cols-3">
                    <div>
                        <label for="name" class="block text-xs font-bold text-ink">Nama Lengkap</label>
                        <input id="name" name="name" type="text" required value="{{ $isCreateOld ? old('name') : '' }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="email" class="block text-xs font-bold text-ink">Email</label>
                        <input id="email" name="email" type="email" required value="{{ $isCreateOld ? old('email') : '' }}"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="role" class="block text-xs font-bold text-ink">Peran</label>
                        <select id="role" name="role" required
                                class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}" @selected($isCreateOld && old('role') === $role->value)>{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="password" class="block text-xs font-bold text-ink">Kata Sandi</label>
                        <input id="password" name="password" type="password" required
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold text-ink">Konfirmasi Sandi</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2 text-sm text-ink focus:bg-white focus:border-primary">
                    </div>
                    <div class="flex items-end">
                        <button type="submit"
                                class="min-h-11 w-full rounded-xl bg-accent px-5 py-2.5 text-xs font-extrabold text-white shadow-xs transition hover:bg-accent-strong cursor-pointer">
                            + Tambah Akun
                        </button>
                    </div>
                </div>
            </form>

            <article class="lg:col-span-4 flex flex-col justify-between rounded-[24px] bg-gradient-to-br from-[#138282] via-primary to-primary-dark p-6 text-white shadow-[0_14px_30px_rgba(0,121,121,0.22)]">
                <div>
                    <p class="text-xs font-semibold text-white/80">Total Akun Terdaftar</p>
                    <p class="mt-2 text-3xl font-extrabold tracking-tight tabular-nums">{{ $users->total() }} <span class="text-sm font-bold text-white/80">akun</span></p>
                </div>
                <div class="mt-5 grid grid-cols-2 gap-3 border-t border-white/15 pt-3 text-xs">
                    <div>
                        <p class="text-white/75">Akun Aktif</p>
                        <p class="font-extrabold text-card-peach tabular-nums">{{ $activeUsersCount }}</p>
                    </div>
                    <div>
                        <p class="text-white/75">Administrator</p>
                        <p class="font-extrabold text-white tabular-nums">{{ $adminUsersCount }}</p>
                    </div>
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

        @if ($errors->any() && ! $isEditOld)
            <div role="alert" class="rounded-[20px] border-l-4 border-[#B42318] bg-white px-5 py-3.5 text-xs text-[#B42318] shadow-[0_6px_20px_rgba(0,121,121,0.06)]">
                <ul class="list-disc space-y-1 pl-4 font-semibold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Row 2: Tabel Pengguna --}}
        <section class="overflow-hidden rounded-[24px] bg-white shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-primary/10 text-left text-xs">
                    <thead class="bg-surface-mint/70 text-[11px] font-extrabold uppercase tracking-wider text-primary-dark">
                        <tr>
                            <th class="px-6 py-3.5">Nama</th>
                            <th class="px-4 py-3.5">Email</th>
                            <th class="px-4 py-3.5">Peran</th>
                            <th class="px-4 py-3.5">Status</th>
                            <th class="px-6 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                            <tr class="hover:bg-surface-mint/35 transition-colors">
                                <td class="px-6 py-3.5 font-extrabold text-ink">{{ $user->name }}</td>
                                <td class="px-4 py-3.5 text-muted">{{ $user->email }}</td>
                                <td class="px-4 py-3.5 font-bold {{ $user->isAdmin() ? 'text-primary-dark' : 'text-body' }}">
                                    {{ $user->role->label() }}
                                </td>
                                <td class="px-4 py-3.5 font-bold {{ $user->is_active ? 'text-primary-dark' : 'text-muted' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </td>
                                <td class="px-6 py-3.5 text-right">
                                    <div class="inline-flex flex-wrap items-center justify-end gap-1.5">
                                        <button type="button"
                                                @click="openEdit({{ json_encode([
                                                    'id' => $user->id,
                                                    'name' => $user->name,
                                                    'email' => $user->email,
                                                    'role' => $user->role->value,
                                                    'is_active' => (bool) $user->is_active,
                                                    'update_url' => route('admin.users.update', $user),
                                                    'is_self' => auth()->user()?->is($user) ?? false,
                                                ]) }})"
                                                class="inline-flex min-h-9 items-center rounded-xl bg-surface-mint/70 px-3 py-1 text-[11px] font-bold text-primary-dark transition hover:bg-surface-mint cursor-pointer">
                                            Edit
                                        </button>

                                        @if ($user->is_active && ! auth()->user()?->is($user))
                                            <form method="POST" action="{{ route('admin.users.deactivate', $user) }}" class="inline"
                                                  onsubmit="return confirm('Nonaktifkan akun ini? Akun nonaktif tidak dapat masuk ke dashboard.');">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit"
                                                        class="inline-flex min-h-9 items-center rounded-xl border border-[#B42318]/25 px-3 py-1 text-[11px] font-bold text-[#B42318] transition hover:bg-[#FDECEC] cursor-pointer">
                                                    Nonaktifkan
                                                </button>
                                            </form>
                                        @elseif (auth()->user()?->is($user))
                                            <span class="text-[11px] font-semibold text-muted">Akun Aktif Anda</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="border-t border-primary/10 bg-surface-mint/25 px-6 py-3.5">
                {{ $users->links() }}
            </div>
        </section>

        {{-- Modal Edit Pengguna --}}
        <div x-show="isEditOpen"
             x-cloak
             @keydown.escape.window="closeEdit()"
             role="dialog"
             aria-modal="true"
             aria-labelledby="edit-user-title"
             class="fixed inset-0 z-50 flex items-center justify-center bg-ink/40 p-4 backdrop-blur-[2px]">
            <div @click.outside="closeEdit()"
                 class="w-full max-w-lg rounded-[24px] bg-white p-6 shadow-float">
                <div class="flex items-center justify-between border-b border-primary/10 pb-3.5">
                    <h2 id="edit-user-title" class="text-lg font-extrabold text-ink">Perbarui Akun Pengguna</h2>
                    <button type="button" @click="closeEdit()" aria-label="Tutup modal"
                            class="flex size-9 items-center justify-center rounded-full bg-surface-mint text-primary-dark hover:bg-card-mint cursor-pointer">
                        &times;
                    </button>
                </div>

                <form method="POST" :action="editingUser.update_url" class="mt-5 space-y-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="_form_context" value="edit">
                    <input type="hidden" name="_edit_user_id" :value="editingUser.id">
                    <input type="hidden" name="_edit_update_url" :value="editingUser.update_url">

                    @if ($errors->any() && $isEditOld)
                        <div role="alert" class="rounded-xl border border-[#B42318]/30 bg-[#FDECEC] px-3.5 py-2.5 text-xs text-[#B42318]">
                            <ul class="list-disc space-y-0.5 pl-4 font-semibold">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label for="edit_name" class="block text-xs font-bold text-ink">Nama Lengkap</label>
                        <input id="edit_name" name="name" type="text" required x-model="editingUser.name"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink focus:bg-white focus:border-primary">
                    </div>

                    <div>
                        <label for="edit_email" class="block text-xs font-bold text-ink">Email</label>
                        <input id="edit_email" name="email" type="email" required x-model="editingUser.email"
                               class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink focus:bg-white focus:border-primary">
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="edit_role" class="block text-xs font-bold text-ink">Peran</label>
                            <select id="edit_role" name="role" required x-model="editingUser.role"
                                    class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink focus:bg-white focus:border-primary">
                                @foreach ($roles as $role)
                                    <option value="{{ $role->value }}">{{ $role->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="edit_is_active" class="block text-xs font-bold text-ink">Status Akun</label>
                            <select id="edit_is_active" name="is_active" x-model="editingUser.is_active"
                                    :disabled="editingUser.is_self"
                                    class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink focus:bg-white focus:border-primary disabled:opacity-60">
                                <option value="1">Aktif</option>
                                <option value="0">Nonaktif</option>
                            </select>
                            <template x-if="editingUser.is_self">
                                <input type="hidden" name="is_active" value="1">
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label for="edit_password" class="block text-xs font-bold text-ink">Kata Sandi Baru (Opsional)</label>
                            <input id="edit_password" name="password" type="password" placeholder="Kosongkan jika tetap"
                                   class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink focus:bg-white focus:border-primary">
                        </div>
                        <div>
                            <label for="edit_password_confirmation" class="block text-xs font-bold text-ink">Konfirmasi Sandi</label>
                            <input id="edit_password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi sandi baru"
                                   class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink focus:bg-white focus:border-primary">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2.5 border-t border-primary/10 pt-4">
                        <button type="button" @click="closeEdit()"
                                class="min-h-11 rounded-xl bg-surface-mint px-5 py-2 text-xs font-bold text-primary-dark hover:bg-card-mint cursor-pointer">
                            Batal
                        </button>
                        <button type="submit"
                                class="min-h-11 rounded-xl bg-gradient-to-r from-primary to-primary-dark px-6 py-2 text-xs font-extrabold text-white shadow-xs hover:opacity-95 cursor-pointer">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-dashboard-shell>
