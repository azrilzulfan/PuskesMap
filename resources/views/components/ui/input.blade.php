@props([
    'name',
    'id' => null,
    'label',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'error' => null,
    'hint' => null,
    'required' => false,
    'disabled' => false,
    'autocomplete' => null,
])

@php
    $inputId = $id ?? $name;
    $resolvedError = $error ?? (isset($errors) ? $errors->first($name) : null);
    $resolvedValue = request()->hasSession() ? old($name, $value) : $value;
    $errorId = $inputId . '-error';
    $hintId = $inputId . '-hint';

    $describedBy = collect([
        $resolvedError ? $errorId : null,
        $hint ? $hintId : null,
    ])->filter()->implode(' ');

    $borderClass = $resolvedError
        ? 'border-[var(--error)] focus-visible:outline-[var(--error)]'
        : 'border-[var(--border-control)] hover:border-[var(--border-control-hover)] focus-visible:outline-[var(--accent)]';
@endphp

<div class="w-full">
    <label for="{{ $inputId }}" class="mb-2 block text-base font-medium text-[var(--text)]">
        {{ $label }}
        @if ($required)
            <span class="text-[var(--error)]" aria-hidden="true">*</span>
        @endif
    </label>

    <input
        id="{{ $inputId }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ $resolvedValue }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($disabled) disabled @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($resolvedError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->merge([
            'class' => "min-h-11 w-full rounded-[6px] border {$borderClass} bg-[var(--base)] px-3.5 py-2.5 text-base text-[var(--text)] placeholder:text-[var(--text-muted)] transition-colors duration-150 motion-reduce:transition-none focus-visible:outline-2 focus-visible:outline-offset-2 disabled:bg-[var(--surface)] disabled:text-[var(--text-muted)] disabled:cursor-not-allowed",
        ]) }}
    >

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
