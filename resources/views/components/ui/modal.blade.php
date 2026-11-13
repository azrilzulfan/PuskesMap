@props([
    'name',
    'title',
    'maxWidth' => 'max-w-2xl',
])

<div
    x-data="{ open: false }"
    x-on:open-modal.window="if ($event.detail === '{{ $name }}') open = true"
    x-on:close-modal.window="if ($event.detail === '{{ $name }}') open = false"
    x-on:keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    role="dialog"
    aria-modal="true"
    aria-labelledby="{{ $name }}-title"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
>
    <div
        x-show="open"
        x-transition.duration.150ms
        class="relative w-full {{ $maxWidth }} max-h-[90dvh] overflow-y-auto rounded-[12px] border border-[var(--border)] bg-[var(--base)] p-6 shadow-[var(--shadow)]"
    >
        <div class="flex items-start justify-between gap-4">
            <h2 id="{{ $name }}-title" class="text-xl font-semibold text-[var(--text)]">
                {{ $title }}
            </h2>
            <button
                type="button"
                @click="open = false"
                aria-label="Tutup dialog"
                class="inline-flex min-h-11 min-w-11 shrink-0 items-center justify-center rounded-[6px] border border-[var(--border)] bg-[var(--base)] text-[var(--text)] transition-colors duration-150 motion-reduce:transition-none hover:bg-[var(--surface)] cursor-pointer focus-visible:outline-2 focus-visible:outline-[var(--accent)] focus-visible:outline-offset-2"
            >
                <x-lucide-x class="size-5" aria-hidden="true" />
            </button>
        </div>

        <div class="mt-4">
            {{ $slot }}
        </div>

        @isset($footer)
            <div class="mt-6 flex flex-wrap items-center justify-end gap-3">
                {{ $footer }}
            </div>
        @endisset
    </div>
</div>
