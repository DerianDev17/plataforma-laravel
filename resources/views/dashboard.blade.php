<x-app-layout>
    <x-slot name="header">Inicio</x-slot>

    <x-ui.alert />

    @if (!Auth::user()->email_verified_at)
    <div class="eus-alert eus-alert-warning alert-with-action" role="status">
        <div>
            <strong>Correo pendiente de verificaci&oacute;n.</strong>
            <span>Su direcci&oacute;n de correo electr&oacute;nico a&uacute;n no ha sido verificada.</span>
        </div>
        <form method="POST" action="{{ route('verification.send') }}" class="alert-action">
            @csrf
            <button type="submit" class="eus-btn eus-btn-sm eus-btn-secondary">
                Enviar verificaci&oacute;n
            </button>
        </form>
    </div>
    @endif

    @livewire('users.dashboard')
</x-app-layout>
