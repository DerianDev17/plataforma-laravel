<x-guest-layout>
    <x-auth.shell>
        <div class="auth-card" role="main" aria-label="Inicio de sesion">
            <x-auth.visual />

            <section class="auth-panel" aria-label="Formulario de inicio de sesion">
                <div class="auth-panel-card">
                    <x-auth.panel-header
                        eyebrow="Bienvenido"
                        title="Iniciar sesion"
                        copy="Usa las credenciales entregadas por el equipo acad&eacute;mico."
                        mobile-logo
                    />

                    <x-jet-validation-errors class="auth-alert-gap eus-alert eus-alert-danger" role="alert" />

                    @if (session('status'))
                        <div class="auth-alert-gap eus-alert eus-alert-success" role="status">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="auth-login-form" novalidate aria-describedby="login-help">
                        @csrf

                        <div>
                            <label for="username" class="eus-label">Nombre de usuario</label>
                            <input
                                id="username"
                                class="eus-input"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                aria-required="true"
                            >
                        </div>

                        <div>
                            <label for="password" class="eus-label">Contrase&ntilde;a</label>
                            <input
                                id="password"
                                class="eus-input"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                aria-required="true"
                            >
                            @if (Route::has('password.request'))
                                <a class="auth-forgot-link" href="{{ route('password.request') }}">
                                    &iquest;Olvidaste tu contrase&ntilde;a?
                                </a>
                            @endif
                        </div>

                        <label for="remember_me" class="eus-checkbox">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span>Recordarme</span>
                        </label>

                        <button type="submit" class="eus-btn eus-btn-primary eus-btn-lg eus-btn-full">
                            Ingresar
                        </button>
                    </form>

                    <p id="login-help" class="auth-help">
                        Si tienes inconvenientes para ingresar, escribe a
                        <a class="auth-help-link" href="mailto:soporte@semilladigital.com">soporte@semilladigital.com</a>.
                    </p>
                </div>
            </section>
        </div>
    </x-auth.shell>
</x-guest-layout>
