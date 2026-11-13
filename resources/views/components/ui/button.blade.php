@props([
    'variant' => 'primary',
    'type' => 'button',
    'href' => null,
    'loading' => false,
    'loadingText' => 'Memproses...',
    'disabled' => false,
])

@php
    $base = 'inline-flex min-h-11 items-center justify-center gap-2 rounded-[6px] px-4 py-2.5 text-sm font-semibold transition-colors duration-150 motion-reduce:transition-none cursor-pointer focus-visible:outline-2 focus-visible:outline-[var(--accent)] focus-visible:outline-offset-2 disabled:bg-[var(--border)] disabled:text-[var(--text-muted)] disabled:border-[var(--border)] disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'bg-[var(--accent)] text-[var(--base)] hover:bg-[var(--accent-hover)]',
        'secondary' => 'bg-[var(--base)] text-[var(--text)] border border-[var(--border-control)] hover:border-[var(--border-control-hover)] hover:bg-[var(--surface)]',
        'ghost' => 'bg-transparent text-[var(--accent)] hover:bg-[var(--accent-tint)]',
        'danger' => 'bg-[var(--error)] text-[var(--base)] hover:bg-[var(--text)]',
        'link' => 'min-h-11 px-1 py-2.5 bg-transparent text-[var(--accent)] font-medium hover:underline focus-visible:underline',
    ];

    $classes = $base . ' ' . ($variants[$variant] ?? $variants['primary']);
    $isDisabled = $disabled || $loading;
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type }}"
        @if ($isDisabled) disabled @endif
        @if ($loading) aria-busy="true" @endif
        {{ $attributes->merge(['class' => $classes]) }}
    >
        @if ($loading)
            <x-lucide-loader-2 class="size-4 shrink-0 animate-spin motion-reduce:animate-none" aria-hidden="true" />
            <span>{{ $loadingText }}</span>
        @else
            {{ $slot }}
        @endif
    </button>
@endif
