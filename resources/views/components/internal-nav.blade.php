<header class="sticky top-3 z-40 mx-auto w-full max-w-7xl px-4 sm:px-6 lg:px-8" x-data="{ mobileOpen: false }">
    <div class="flex h-16 items-center justify-between gap-3 rounded-full border border-primary/15 bg-white/95 px-4 sm:px-6 shadow-nav backdrop-blur-md">
        <div class="flex items-center gap-4 lg:gap-6 min-w-0">
            <a href="{{ route('analitik.index') }}" class="group flex items-center gap-2.5 shrink-0 focus:outline-none">
                <img src="{{ asset('images/logo-sm.png') }}" alt="PuskesMap" class="h-8 w-auto object-contain transition-transform duration-200 group-hover:scale-105">
                <span class="text-lg font-extrabold tracking-tight text-primary">PuskesMap</span>
            </a>

            <nav aria-label="Navigasi Dashboard" class="hidden lg:flex items-center gap-1 text-xs font-bold">
                <a href="{{ route('analitik.index') }}"
                   class="rounded-full px-4 py-2 transition {{ request()->routeIs('analitik.index') ? 'bg-surface-mint text-primary-dark border border-primary/15 shadow-[0_2px_10px_rgba(0,121,121,0.10)]' : 'text-body hover:bg-surface-mint/70 hover:text-primary-dark' }}">
                    Analitik Dinkes
                </a>
                <a href="{{ route('analitik.peta') }}"
                   class="rounded-full px-4 py-2 transition {{ request()->routeIs('analitik.peta') ? 'bg-surface-mint text-primary-dark border border-primary/15 shadow-[0_2px_10px_rgba(0,121,121,0.10)]' : 'text-body hover:bg-surface-mint/70 hover:text-primary-dark' }}">
                    Peta Internal
                </a>
                @if (auth()->user()?->isAdmin())
                    <a href="{{ route('admin.puskesmas.index') }}"
                       class="rounded-full px-4 py-2 transition {{ request()->routeIs('admin.puskesmas.*') || request()->routeIs('admin.operasional.*') || request()->routeIs('admin.kunjungan.*') ? 'bg-surface-mint text-primary-dark border border-primary/15 shadow-[0_2px_10px_rgba(0,121,121,0.10)]' : 'text-body hover:bg-surface-mint/70 hover:text-primary-dark' }}">
                        Kelola Puskesmas
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                       class="rounded-full px-4 py-2 transition {{ request()->routeIs('admin.users.*') ? 'bg-surface-mint text-primary-dark border border-primary/15 shadow-[0_2px_10px_rgba(0,121,121,0.10)]' : 'text-body hover:bg-surface-mint/70 hover:text-primary-dark' }}">
                        Pengguna
                    </a>
                    <a href="{{ route('admin.audit-log.index') }}"
                       class="rounded-full px-4 py-2 transition {{ request()->routeIs('admin.audit-log.*') ? 'bg-surface-mint text-primary-dark border border-primary/15 shadow-[0_2px_10px_rgba(0,121,121,0.10)]' : 'text-body hover:bg-surface-mint/70 hover:text-primary-dark' }}">
                        Audit Log
                    </a>
                @endif
            </nav>
        </div>

        <div class="flex items-center gap-2.5 sm:gap-3 text-xs shrink-0">
            <div class="hidden md:block text-right leading-tight pr-1">
                <div class="font-extrabold text-ink">{{ auth()->user()?->name }}</div>
                <div class="text-[11px] font-medium text-muted">{{ auth()->user()?->role->label() }}</div>
            </div>

            <a href="{{ route('analitik.peta') }}"
               class="inline-flex min-h-10 items-center justify-center rounded-full bg-accent px-4 py-2 text-xs font-extrabold text-white shadow-xs transition hover:bg-accent-strong hover:-translate-y-0.5">
                Peta Internal
            </a>

            <form method="POST" action="{{ route('logout') }}" class="hidden sm:block">
                @csrf
                <button type="submit"
                        class="inline-flex min-h-10 items-center justify-center rounded-full border-[1.5px] border-primary/25 bg-white px-4 py-2 text-xs font-extrabold text-ink transition hover:border-accent hover:text-accent-strong cursor-pointer">
                    Keluar
                </button>
            </form>

            <button type="button"
                    @click="mobileOpen = !mobileOpen"
                    :aria-expanded="mobileOpen.toString()"
                    aria-label="Buka menu navigasi"
                    class="lg:hidden inline-flex size-10 items-center justify-center rounded-full border border-primary/15 bg-surface-mint text-primary-dark cursor-pointer">
                <svg x-show="!mobileOpen" class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="mobileOpen" x-cloak class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Menu Dropdown Mobile --}}
    <div x-show="mobileOpen"
         x-cloak
         @click.outside="mobileOpen = false"
         class="lg:hidden mt-2 rounded-2xl border border-primary/15 bg-white p-3 shadow-float space-y-1 text-xs font-bold">
        <div class="px-3.5 py-2 mb-1 border-b border-primary/10">
            <div class="font-extrabold text-ink">{{ auth()->user()?->name }}</div>
            <div class="text-[11px] font-medium text-muted">{{ auth()->user()?->role->label() }}</div>
        </div>
        <a href="{{ route('analitik.index') }}"
           class="block rounded-xl px-3.5 py-2.5 {{ request()->routeIs('analitik.index') ? 'bg-surface-mint text-primary-dark' : 'text-body hover:bg-surface-mint/50' }}">
            Analitik Dinkes
        </a>
        <a href="{{ route('analitik.peta') }}"
           class="block rounded-xl px-3.5 py-2.5 {{ request()->routeIs('analitik.peta') ? 'bg-surface-mint text-primary-dark' : 'text-body hover:bg-surface-mint/50' }}">
            Peta Internal
        </a>
        @if (auth()->user()?->isAdmin())
            <a href="{{ route('admin.puskesmas.index') }}"
               class="block rounded-xl px-3.5 py-2.5 {{ request()->routeIs('admin.puskesmas.*') || request()->routeIs('admin.operasional.*') || request()->routeIs('admin.kunjungan.*') ? 'bg-surface-mint text-primary-dark' : 'text-body hover:bg-surface-mint/50' }}">
                Kelola Puskesmas
            </a>
            <a href="{{ route('admin.users.index') }}"
               class="block rounded-xl px-3.5 py-2.5 {{ request()->routeIs('admin.users.*') ? 'bg-surface-mint text-primary-dark' : 'text-body hover:bg-surface-mint/50' }}">
                Pengguna
            </a>
            <a href="{{ route('admin.audit-log.index') }}"
               class="block rounded-xl px-3.5 py-2.5 {{ request()->routeIs('admin.audit-log.*') ? 'bg-surface-mint text-primary-dark' : 'text-body hover:bg-surface-mint/50' }}">
                Audit Log
            </a>
        @endif
        <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-primary/10 sm:hidden">
            @csrf
            <button type="submit"
                    class="w-full text-left rounded-xl px-3.5 py-2.5 font-extrabold text-accent-strong hover:bg-surface-mint/50 cursor-pointer">
                Keluar
            </button>
        </form>
    </div>
</header>
