@props(['type' => 'success', 'dismissible' => true])

@php
$colors = [
    'success' => 'eus-alert-success',
    'error'   => 'eus-alert-danger',
    'warning' => 'eus-alert-warning',
    'info'    => 'eus-alert-info',
];
$bg = $colors[$type] ?? $colors['success'];
@endphp

@if (session()->has('message'))
<div id="alert" class="eus-alert {{ $bg }}" role="alert">
    <span>{{ session('message') }}</span>
    @if($dismissible)
    <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" onclick="this.parentElement.remove()" aria-label="Cerrar alerta">
        <span aria-hidden="true">&times;</span>
    </button>
    @endif
</div>
@endif
