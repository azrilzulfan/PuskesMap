@props([
    'variant' => 'primary', // primary, accent, outline
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center rounded-full font-semibold transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'h-10 px-6 text-xs bg-primary text-white hover:bg-primary-dark',
        'outline' => 'h-10 px-6 text-xs border-[1.5px] border-primary text-primary bg-white hover:bg-surface-mint',
        'accent' => 'h-11 px-8 text-base rounded-sm bg-accent-strong text-white hover:bg-accent',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
