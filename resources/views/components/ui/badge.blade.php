@props([
    'variant' => 'neutral',
    'pill' => false,
])

@php
    $radius = $pill ? 'rounded-full' : 'rounded-[6px]';

    $variants = [
        'neutral' => 'bg-[var(--surface)] text-[var(--text)] border border-[var(--border)]',
        'accent' => 'bg-[var(--accent-tint)] text-[var(--accent)]',
        'success' => 'bg-[var(--success-tint)] text-[var(--success)]',
        'warning' => 'bg-[var(--warning-tint)] text-[var(--warning)]',
        'error' => 'bg-[var(--error-tint)] text-[var(--error)]',
    ];

    $classes = 'inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium leading-tight ' . $radius . ' ' . ($variants[$variant] ?? $variants['neutral']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
