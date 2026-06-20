<x-guest-layout>
    <x-auth.shell compact>
        <div class="eus-card auth-compact-card">
            <div class="eus-card-body auth-compact-body">
                <x-auth.panel-header title="Verificar email" center mobile-logo />

                <p class="auth-panel-copy auth-copy-block">
                    Antes de continuar, verifica tu direcci&oacute;n de correo electr&oacute;nico desde el enlace que te enviamos.
                    Si no recibiste el email, puedes solicitar otro.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="eus-alert eus-alert-success" role="status">
                        Se ha enviado un nuevo enlace de verificaci&oacute;n a tu correo electr&oacute;nico.
                    </div>
                @endif

                <div class="auth-actions-row">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="eus-btn eus-btn-primary">
                            Reenviar email
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="eus-btn eus-btn-ghost">
                            Cerrar sesi&oacute;n
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </x-auth.shell>
</x-guest-layout>
