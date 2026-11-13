<header class="border-b border-gray-200 bg-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center justify-between gap-4 px-4 py-3.5 sm:px-6 lg:px-8">
        <div class="flex items-center gap-6">
            <a href="{{ route('peta') }}" class="flex items-center gap-2.5">
                <img src="{{ asset('images/logo-sm.png') }}" alt="PuskesMap" class="h-8 w-auto object-contain">
                <span class="text-base font-extrabold tracking-tight text-ink">PuskesMap <span class="text-primary">SDSS</span></span>
            </a>

            <nav class="flex flex-wrap items-center gap-2 text-xs font-bold">
                <a href="{{ route('peta') }}"
                   class="rounded-lg px-3 py-2 text-muted transition hover:bg-surface-mint hover:text-primary-dark">
                    Peta Publik
                </a>
                <a href="{{ route('analitik.index') }}"
                   class="rounded-lg px-3 py-2 transition {{ request()->routeIs('analitik.*') ? 'bg-surface-mint text-primary-dark' : 'text-muted hover:bg-surface-mint hover:text-primary-dark' }}">
                    Analitik Dinkes
                </a>
                @if (auth()->user()?->isAdmin())
                    <a href="{{ route('admin.puskesmas.index') }}"
                       class="rounded-lg px-3 py-2 transition {{ request()->routeIs('admin.puskesmas.*') || request()->routeIs('admin.operasional.*') || request()->routeIs('admin.kunjungan.*') ? 'bg-surface-mint text-primary-dark' : 'text-muted hover:bg-surface-mint hover:text-primary-dark' }}">
                        Kelola Puskesmas
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-lg px-3 py-2 transition {{ request()->routeIs('admin.users.*') ? 'bg-surface-mint text-primary-dark' : 'text-muted hover:bg-surface-mint hover:text-primary-dark' }}">
                        Pengguna
                    </a>
                    <a href="{{ route('admin.audit-log.index') }}"
                       class="rounded-lg px-3 py-2 transition {{ request()->routeIs('admin.audit-log.*') ? 'bg-surface-mint text-primary-dark' : 'text-muted hover:bg-surface-mint hover:text-primary-dark' }}">
                        Audit Log
                    </a>
                @endif
            </nav>
        </div>

        <div class="flex items-center gap-3 text-xs">
            <div class="text-right">
                <div class="font-bold text-ink">{{ auth()->user()?->name }}</div>
                <div class="text-[11px] font-medium text-muted">{{ auth()->user()?->role->label() }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="rounded-lg border border-gray-200 px-3 py-2 font-bold text-ink transition hover:border-accent hover:text-accent-strong cursor-pointer">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</header>
