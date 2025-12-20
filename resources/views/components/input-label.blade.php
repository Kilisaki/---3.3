@props(['value'])

<label {{ $attributes->merge(['class' => 'block font-medium text-sm text-timberwolf form-label']) }}>
    {{ $value ?? $slot }}
</label>
