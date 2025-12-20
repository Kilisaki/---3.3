@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'alert alert-success mb-3 p-2 rounded']) }}>
        {{ $status }}
    </div>
@endif
