<x-guest-layout>
    <x-auth.shell compact>
        <div class="eus-card auth-compact-card">
            <div class="eus-card-body auth-compact-body">
                <x-auth.panel-header title="Crear cuenta" center mobile-logo />

                <x-jet-validation-errors class="auth-alert-gap eus-alert eus-alert-danger" role="alert" />

                <form method="POST" action="{{ route('register') }}" class="auth-form-stack" novalidate>
                    @csrf

                    <div>
                        <label for="name" class="eus-label eus-label-required">Nombre</label>
                        <input id="name" class="eus-input" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" aria-required="true">
                    </div>

                    <div>
                        <label for="email" class="eus-label eus-label-required">Email</label>
                        <input id="email" class="eus-input" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" aria-required="true">
                    </div>

                    <div>
                        <label for="password" class="eus-label eus-label-required">Contrase&ntilde;a</label>
                        <input id="password" class="eus-input" type="password" name="password" required autocomplete="new-password" aria-required="true">
                    </div>

                    <div>
                        <label for="password_confirmation" class="eus-label eus-label-required">Confirmar contrase&ntilde;a</label>
                        <input id="password_confirmation" class="eus-input" type="password" name="password_confirmation" required autocomplete="new-password" aria-required="true">
                    </div>

                    <div class="auth-form-actions">
                        <a class="auth-secondary-link" href="{{ route('login') }}">
                            &larr; Ya tengo cuenta
                        </a>
                        <button type="submit" class="eus-btn eus-btn-primary">
                            Registrarse
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </x-auth.shell>
</x-guest-layout>
