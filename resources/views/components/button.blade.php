@props([
    'variant' => 'primary', // primary, accent, outline
    'type' => 'button',
    'href' => null,
])

@php
    $base = 'inline-flex items-center justify-center font-semibold transition-colors cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed';

    $variants = [
        'primary' => 'h-10 px-6 text-xs rounded-full bg-primary text-white hover:bg-primary-dark',
        'outline' => 'h-10 px-6 text-xs rounded-full border-[1.5px] border-primary text-primary bg-white hover:bg-surface-mint',
        'accent' => 'h-12 px-8 text-lg font-medium rounded-sm bg-primary text-white hover:bg-primary-dark shadow-xs',
    ];

    $classes = $base.' '.($variants[$variant] ?? $variants['primary']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>{{ $slot }}</button>
@endif
