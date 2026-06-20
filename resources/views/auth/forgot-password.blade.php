<x-guest-layout>
    <x-auth.shell compact>
        <div class="eus-card auth-compact-card">
            <div class="eus-card-body auth-compact-body">
                <x-auth.panel-header
                    eyebrow="Acceso"
                    title="Recuperar contrase&ntilde;a"
                    center
                    mobile-logo
                />

                <div class="eus-alert eus-alert-info">
                    <span>
                        <strong>&iexcl;Atenci&oacute;n!</strong>
                        Le enviaremos un enlace para restablecer la contrase&ntilde;a que le permitir&aacute; elegir una nueva.
                    </span>
                </div>
                <div class="eus-alert eus-alert-warning">
                    Por favor, revise el correo spam si el email no llega a su buz&oacute;n principal.
                </div>

                @if (session('status'))
                    <div class="eus-alert eus-alert-success" role="status">
                        {{ session('status') }}
                    </div>
                @endif

                <x-jet-validation-errors class="auth-alert-gap eus-alert eus-alert-danger" role="alert" />

                <form method="POST" action="{{ route('password.email') }}" class="auth-form-stack" novalidate>
                    @csrf

                    <div>
                        <label for="email" class="eus-label eus-label-required">Email</label>
                        <input id="email" class="eus-input" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email" aria-required="true">
                    </div>

                    <div class="auth-form-actions">
                        <a class="auth-secondary-link" href="{{ route('login') }}">
                            &larr; Volver al inicio de sesi&oacute;n
                        </a>
                        <button type="submit" class="eus-btn eus-btn-primary">
                            Enviar enlace
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-auth.shell>
</x-guest-layout>
