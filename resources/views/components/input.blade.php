@php
    $input_id = $id ?? Str::kebab($name) . '-field';
@endphp
<div
    @class([ 'form-control-wrapper', 'is-invalid' => $errors->has($name) ])
>
    @isset($label)
    <label for="{{ $input_id }}">{{ $label }}</label>
    @endisset

    {{ $before_input ?? null }}

    @if (isset($type) && $type === 'textarea')
        <textarea
            name="{{ $name }}"
            id="{{ $input_id }}"
            placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}"
            {{ $attributes->class([
                'form-control',
                'is-invalid' => $errors->has($name),
            ]) }}
        >{{ $value ?? old($name) ?? '' }}</textarea>
    @else
        <input
            type="{{ $type ?? 'text' }}"
            name="{{ $name }}"
            id="{{ $input_id }}"
            value="{{ $value ?? old($name) ?? '' }}"
            placeholder="{{ isset($placeholder) ? __($placeholder) : '' }}"
            {{ $attributes->class([
                'form-control',
                'is-invalid' => $errors->has($name),
            ]) }}
        />
    @endif

    @error($name)
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror

    {{ $after_input ?? null }}
</div>
