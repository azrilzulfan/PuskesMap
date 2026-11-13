@props([
    'label' => 'Panel navigasi dan filter peta',
    'initiallyOpen' => true,
])

<aside
    x-data="{ open: @js($initiallyOpen) }"
    aria-label="{{ $label }}"
    {{ $attributes->merge(['class' => 'pointer-events-none fixed inset-x-3 bottom-3 z-20 flex flex-col md:inset-x-auto md:top-4 md:bottom-4 md:left-4 md:w-[400px]']) }}
>
    <div class="pointer-events-auto flex items-center justify-between gap-2 rounded-[12px] border border-[var(--border)] bg-[var(--base)] px-4 py-3 shadow-[var(--shadow)]">
        <div class="min-w-0 flex-1">
            {{ $header ?? '' }}
        </div>
        <button
            type="button"
            @click="open = !open"
            :aria-expanded="open.toString()"
            aria-label="Buka atau tutup panel samping"
            class="inline-flex min-h-11 min-w-11 shrink-0 items-center justify-center rounded-[6px] border border-[var(--border)] bg-[var(--base)] text-[var(--text)] transition-colors duration-150 motion-reduce:transition-none hover:bg-[var(--surface)] cursor-pointer focus-visible:outline-2 focus-visible:outline-[var(--accent)] focus-visible:outline-offset-2"
        >
            <x-lucide-panel-left-close x-show="open" class="size-5" aria-hidden="true" />
            <x-lucide-panel-left-open x-show="!open" x-cloak class="size-5" aria-hidden="true" />
        </button>
    </div>

    <div
        x-show="open"
        x-transition.duration.150ms
        class="pointer-events-auto mt-2 flex max-h-[60dvh] flex-col overflow-y-auto rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-4 shadow-[var(--shadow)] md:max-h-full md:flex-1"
    >
        {{ $slot }}
    </div>
</aside>
