@props(['level' => 'Sedang'])

@php
    $levels = [
        'Sepi' => ['w-1/4', 'bg-crowd-low'],
        'Sedang' => ['w-3/5', 'bg-primary'],
        'Ramai' => ['w-11/12', 'bg-accent'],
    ];
    [$width, $color] = $levels[$level] ?? $levels['Sedang'];
@endphp

<div {{ $attributes }}>
    <p class="text-xs text-muted">Keramaian: {{ $level }}</p>
    <div class="mt-1 h-1.5 rounded-full bg-track" role="img" aria-label="Keramaian {{ $level }}">
        <div class="h-full rounded-full {{ $width }} {{ $color }}"></div>
    </div>
</div>

