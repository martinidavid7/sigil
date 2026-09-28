@props(['label', 'type' => 'text', 'hint' => null])

@php
    $field = $attributes->wire('model')->value();
    $id = $attributes->get('id', str_replace('.', '_', $field));
@endphp

<div {{ $attributes->only('class')->merge(['class' => 'form-group']) }}>
    <label for="{{ $id }}">{{ $label }}</label>
    <input type="{{ $type }}" id="{{ $id }}"
        {{ $attributes->except('class')->class(['form-control', 'is-invalid' => $errors->has($field)]) }}>
    @if ($hint)
        <small class="form-text text-muted">{{ $hint }}</small>
    @endif
    @error($field)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
