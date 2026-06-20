@props([
    'variant' => 'mark',
    'alt' => 'Semilla Digital',
])

@php
    $src = $variant === 'horizontal'
        ? asset('brand/semilla-logo-horizontal.svg')
        : asset('brand/semilla-logo-mark.svg');
@endphp

<img {{ $attributes->merge(['src' => $src, 'alt' => $alt]) }}>
