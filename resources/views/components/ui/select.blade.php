@props([
    'name',
    'id' => null,
    'label',
    'options' => [],
    'selected' => null,
    'placeholder' => null,
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
])

@php
    $selectId = $id ?? $name;
    $resolvedError = $error ?? (isset($errors) ? $errors->first($name) : null);
    $resolvedSelected = request()->hasSession() ? old($name, $selected) : $selected;
    $errorId = $selectId . '-error';
    $hintId = $selectId . '-hint';

    $describedBy = collect([
        $resolvedError ? $errorId : null,
        $hint ? $hintId : null,
    ])->filter()->implode(' ');

    $borderClass = $resolvedError
        ? 'border-[var(--error)] focus-visible:outline-[var(--error)]'
        : 'border-[var(--border-control)] hover:border-[var(--border-control-hover)] focus-visible:outline-[var(--accent)]';
@endphp

<div class="w-full">
    <label for="{{ $selectId }}" class="mb-2 block text-base font-medium text-[var(--text)]">
        {{ $label }}
        @if ($required)
            <span class="text-[var(--error)]" aria-hidden="true">*</span>
        @endif
    </label>

    <div class="relative">
        <select
            id="{{ $selectId }}"
            name="{{ $name }}"
            @if ($required) required @endif
            @if ($disabled) disabled @endif
            @if ($resolvedError) aria-invalid="true" @endif
            @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
            {{ $attributes->merge([
                'class' => "min-h-11 w-full appearance-none rounded-[6px] border {$borderClass} bg-[var(--base)] py-2.5 pl-3.5 pr-10 text-base text-[var(--text)] transition-colors duration-150 motion-reduce:transition-none focus-visible:outline-2 focus-visible:outline-offset-2 disabled:bg-[var(--surface)] disabled:text-[var(--text-muted)] disabled:cursor-not-allowed",
            ]) }}
        >
            @if ($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected((string) $resolvedSelected === (string) $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
            {{ $slot }}
        </select>
        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-[var(--text-muted)]" aria-hidden="true">
            <x-lucide-chevron-down class="size-4" />
        </span>
    </div>

    @if ($resolvedError)
        <p id="{{ $errorId }}" role="alert" class="mt-2 text-sm text-[var(--error)]">
            {{ $resolvedError }}
        </p>
    @endif

    @if ($hint)
        <p id="{{ $hintId }}" class="mt-2 text-sm text-[var(--text-muted)]">
            {{ $hint }}
        </p>
    @endif
</div>
