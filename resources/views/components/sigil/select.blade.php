@props(['label', 'options' => [], 'placeholder' => 'Selecione...', 'hint' => null])

@php
    $field = $attributes->wire('model')->value();
    $id = $attributes->get('id', str_replace('.', '_', $field));
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'form-group']) }}>
    <label for="{{ $id }}">{{ $label }}</label>
    <select id="{{ $id }}"
        {{ $attributes->except('class')->class(['custom-select', 'is-invalid' => $errors->has($field)]) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </select>
    @if ($hint)
        <small class="form-text text-muted">{{ $hint }}</small>
    @endif
    @error($field)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
