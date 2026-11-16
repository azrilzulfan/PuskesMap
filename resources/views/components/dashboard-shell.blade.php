@props([
    'title' => 'Dashboard - PuskesMap',
    'greeting' => 'Halo, '.(auth()->user()?->name ?? 'Pengguna').'!',
    'subtitle' => 'Sistem Pendukung Keputusan Spasial Puskesmas Kota Bogor',
    'searchAction' => null,
    'searchName' => 'q',
    'searchValue' => '',
    'searchPlaceholder' => 'Cari data...',
    'searchType' => 'text',
    'searchParams' => [],
])

@php
    $user = auth()->user();
    $initials = collect(explode(' ', trim((string) ($user?->name ?? 'PM'))))
        ->filter()
        ->take(2)
        ->map(fn (string $part): string => mb_strtoupper(mb_substr($part, 0, 1)))
        ->implode('');

    $navItems = [
        [
            'label' => 'Analitik Dinkes',
            'href' => route('analitik.index'),
            'active' => request()->routeIs('analitik.index'),
            'icon' => 'grid',
            'visible' => true,
        ],
        [
            'label' => 'Peta Spasial Internal',
            'href' => route('analitik.peta'),
            'active' => request()->routeIs('analitik.peta'),
            'icon' => 'map',
            'visible' => true,
        ],
        [
            'label' => 'Kelola Puskesmas',
            'href' => route('admin.puskesmas.index'),
            'active' => request()->routeIs('admin.puskesmas.*') || request()->routeIs('admin.operasional.*') || request()->routeIs('admin.kunjungan.*'),
            'icon' => 'building',
            'visible' => (bool) $user?->isAdmin(),
        ],
        [
            'label' => 'Pengguna & Role',
            'href' => route('admin.users.index'),
            'active' => request()->routeIs('admin.users.*'),
            'icon' => 'users',
            'visible' => (bool) $user?->isAdmin(),
        ],
        [
            'label' => 'Audit Log',
            'href' => route('admin.audit-log.index'),
            'active' => request()->routeIs('admin.audit-log.*'),
            'icon' => 'shield',
            'visible' => (bool) $user?->isAdmin(),
        ],
    ];
@endphp

<x-layout :title="$title" body-class="min-h-dvh bg-[#eef3f2] text-ink font-sans">
    <div x-data="{ mobileOpen: false }" class="mx-auto flex min-h-dvh max-w-[1440px] flex-col gap-5 p-4 sm:p-6 lg:flex-row lg:gap-7 lg:p-7">

        {{-- Mobile Top Pill Header (< lg, tetap terlihat saat discroll) --}}
        <div class="lg:hidden sticky top-3 z-40 space-y-2">
            <header class="flex items-center justify-between gap-3 rounded-full bg-white/95 px-4 py-2.5 shadow-[0_8px_24px_rgba(0,121,121,0.09)] backdrop-blur-md">
                <a href="{{ route('analitik.index') }}" class="flex items-center gap-2.5">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="PuskesMap" class="h-8 w-auto object-contain">
                    <span class="text-base font-extrabold tracking-tight text-primary">PuskesMap</span>
                </a>

                <div class="flex items-center gap-2">
                    <a href="{{ route('analitik.peta') }}"
                       class="inline-flex min-h-9 items-center rounded-full px-3.5 py-1.5 text-xs font-bold transition {{ request()->routeIs('analitik.peta') ? 'bg-ink text-white' : 'bg-primary-dark text-white hover:opacity-95' }}">
                        Peta Internal
                    </a>
                    <button type="button"
                            @click="mobileOpen = !mobileOpen"
                            :aria-expanded="mobileOpen.toString()"
                            aria-label="Buka menu navigasi"
                            class="inline-flex size-10 items-center justify-center rounded-full bg-surface-mint text-primary-dark cursor-pointer">
                        <svg x-show="!mobileOpen" class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg x-show="mobileOpen" x-cloak class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </header>

            {{-- Mobile Dropdown --}}
            <div x-show="mobileOpen" x-cloak @click.outside="mobileOpen = false" @keydown.escape.window="mobileOpen = false"
                 class="rounded-[22px] bg-white p-3 shadow-float space-y-1 text-xs font-bold">
                <div class="mb-1.5 flex items-center gap-2.5 rounded-xl bg-surface-mint/60 px-3.5 py-2.5">
                    <div class="flex size-8 shrink-0 items-center justify-center rounded-full bg-primary-dark text-[11px] font-extrabold text-white">
                        {{ $initials ?: 'PM' }}
                    </div>
                    <div class="min-w-0">
                        <p class="truncate text-xs font-extrabold text-ink">{{ $user?->name }}</p>
                        <p class="truncate text-[11px] font-medium text-muted">{{ $user?->role->label() }}</p>
                    </div>
                </div>
                @foreach ($navItems as $item)
                    @if ($item['visible'])
                        <a href="{{ $item['href'] }}"
                           @if ($item['active']) aria-current="page" @endif
                           class="block rounded-xl px-4 py-2.5 {{ $item['active'] ? 'bg-primary-dark text-white' : 'text-body hover:bg-surface-mint' }}">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
                <form method="POST" action="{{ route('logout') }}" class="pt-1 border-t border-primary/10">
                    @csrf
                    <button type="submit" class="w-full text-left rounded-xl px-4 py-2.5 font-extrabold text-accent-strong hover:bg-surface-mint cursor-pointer">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Left Floating Vertical Pill Sidebar (Desktop >= lg, sticky saat halaman discroll) --}}
        <aside class="hidden lg:flex lg:sticky lg:top-7 lg:self-start lg:h-[calc(100dvh-3.5rem)] w-[68px] shrink-0 flex-col items-center gap-5 pt-1 z-30" aria-label="Sidebar Navigasi Dashboard">
            <a href="{{ route('analitik.index') }}"
               title="Dasbor Analitik PuskesMap"
               class="group relative flex size-12 shrink-0 items-center justify-center rounded-full bg-white shadow-[0_8px_22px_rgba(0,121,121,0.08)] transition hover:scale-105">
                <img src="{{ asset('images/logo-sm.png') }}" alt="PuskesMap" class="h-7 w-auto object-contain">
                <span class="pointer-events-none invisible absolute left-full ml-3 whitespace-nowrap rounded-xl bg-ink px-3 py-1.5 text-[11px] font-bold text-white opacity-0 shadow-float transition-opacity duration-150 group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">
                    Dasbor PuskesMap
                </span>
            </a>

            <nav class="flex flex-1 min-h-0 w-full flex-col items-center justify-between rounded-full bg-white py-3.5 shadow-[0_10px_30px_rgba(0,121,121,0.08)]">
                <div class="flex flex-col items-center gap-2.5">
                    @foreach ($navItems as $item)
                        @if ($item['visible'])
                            <a href="{{ $item['href'] }}"
                               title="{{ $item['label'] }}"
                               aria-label="{{ $item['label'] }}"
                               @if ($item['active']) aria-current="page" @endif
                               class="group relative flex size-11 items-center justify-center rounded-full transition-all duration-200 {{ $item['active'] ? 'bg-ink text-white shadow-[0_6px_16px_rgba(29,43,39,0.28)]' : 'text-muted hover:bg-surface-mint hover:text-primary-dark' }}">
                                @switch($item['icon'])
                                    @case('grid')
                                        <svg class="size-[19px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
                                            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
                                            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
                                            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
                                        </svg>
                                        @break
                                    @case('building')
                                        <svg class="size-[19px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M5 21V7l7-4 7 4v14M9 21v-4h6v4M9 9h.01M15 9h.01M9 13h.01M15 13h.01"/>
                                        </svg>
                                        @break
                                    @case('users')
                                        <svg class="size-[19px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/>
                                        </svg>
                                        @break
                                    @case('shield')
                                        <svg class="size-[19px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                        </svg>
                                        @break
                                    @case('map')
                                        <svg class="size-[19px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                                        </svg>
                                        @break
                                @endswitch
                                <span class="pointer-events-none invisible absolute left-full ml-3 whitespace-nowrap rounded-xl bg-ink px-3 py-1.5 text-[11px] font-bold text-white opacity-0 shadow-float transition-opacity duration-150 group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">
                                    {{ $item['label'] }}
                                </span>
                            </a>
                        @endif
                    @endforeach
                </div>

                <div class="flex flex-col items-center gap-3 pt-4">
                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Keluar dari sesi dashboard?');">
                        @csrf
                        <button type="submit"
                                title="Keluar"
                                aria-label="Keluar dari akun"
                                class="group relative flex size-10 items-center justify-center rounded-full text-muted transition hover:bg-surface-mint hover:text-accent-strong cursor-pointer">
                            <svg class="size-[19px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            <span class="pointer-events-none invisible absolute left-full ml-3 whitespace-nowrap rounded-xl bg-ink px-3 py-1.5 text-[11px] font-bold text-white opacity-0 shadow-float transition-opacity duration-150 group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">
                                Keluar
                            </span>
                        </button>
                    </form>

                    <div tabindex="0"
                         title="{{ $user?->name }} ({{ $user?->role->label() }})"
                         aria-label="{{ $user?->name }} ({{ $user?->role->label() }})"
                         class="group relative flex size-10 items-center justify-center rounded-full bg-primary-dark text-xs font-extrabold text-white ring-2 ring-surface-mint">
                        {{ $initials ?: 'PM' }}
                        <span class="pointer-events-none invisible absolute left-full ml-3 whitespace-nowrap rounded-xl bg-ink px-3 py-1.5 text-[11px] font-bold text-white opacity-0 shadow-float transition-opacity duration-150 group-hover:visible group-hover:opacity-100 group-focus-visible:visible group-focus-visible:opacity-100">
                            {{ $user?->name }} &middot; {{ $user?->role->label() }}
                        </span>
                    </div>
                </div>
            </nav>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="flex-1 min-w-0 space-y-5">
            {{-- Top Greeting & Search Pill Header (persis layout referensi) --}}
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between pt-1">
                <div class="min-w-0">
                    <h1 class="text-2xl sm:text-[28px] font-extrabold tracking-tight text-ink leading-tight">
                        {{ $greeting }}
                    </h1>
                    <p class="mt-0.5 text-xs sm:text-sm font-medium text-muted">
                        {{ $subtitle }}
                    </p>
                </div>

                <div class="flex flex-wrap items-center gap-2.5">
                    @if ($searchAction)
                        <form method="GET" action="{{ $searchAction }}"
                              class="flex h-12 w-full sm:w-72 items-center justify-between gap-1.5 rounded-full bg-white pl-5 pr-1.5 shadow-[0_6px_20px_rgba(0,121,121,0.06)] focus-within:ring-2 focus-within:ring-primary/25">
                            @foreach ($searchParams as $paramKey => $paramVal)
                                @if ($paramVal !== null && $paramVal !== '')
                                    <input type="hidden" name="{{ $paramKey }}" value="{{ $paramVal }}">
                                @endif
                            @endforeach
                            <input id="header-search-input"
                                   type="{{ $searchType }}"
                                   @if ($searchType === 'number') min="2020" max="2100" @endif
                                   name="{{ $searchName }}"
                                   value="{{ $searchValue }}"
                                   placeholder="{{ $searchPlaceholder }}"
                                   aria-label="{{ $searchPlaceholder }}"
                                   class="w-full min-w-0 bg-transparent text-xs font-medium text-ink placeholder:text-placeholder focus:outline-none">
                            @if ((string) $searchValue !== '')
                                <a href="{{ $searchAction.(count(array_filter($searchParams, fn ($v) => $v !== null && $v !== '')) > 0 ? '?'.http_build_query(array_filter($searchParams, fn ($v) => $v !== null && $v !== '')) : '') }}"
                                   title="Bersihkan pencarian"
                                   aria-label="Bersihkan pencarian"
                                   class="flex size-7 shrink-0 items-center justify-center rounded-full text-muted transition hover:bg-surface-mint hover:text-ink">
                                    &times;
                                </a>
                            @endif
                            <button type="submit"
                                    aria-label="Cari"
                                    class="flex size-9 shrink-0 items-center justify-center rounded-full bg-ink text-white transition hover:bg-primary cursor-pointer">
                                <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                                    <circle cx="11" cy="11" r="7"/>
                                    <path stroke-linecap="round" d="M20 20l-3.5-3.5"/>
                                </svg>
                            </button>
                        </form>
                    @endif

                    {{ $headerActions ?? '' }}
                </div>
            </div>

            {{ $slot }}
        </div>
    </div>
</x-layout>
