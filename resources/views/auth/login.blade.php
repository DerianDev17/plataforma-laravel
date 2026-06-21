<x-guest-layout>
    <x-auth.shell>
        <div class="auth-card">
            <x-auth.visual />

            <section class="auth-panel" aria-labelledby="login-title">
                <div class="auth-panel-card">
                    <x-auth.panel-header
                        eyebrow="Acceso seguro"
                        title="<span id='login-title'>Iniciar sesi&oacute;n</span>"
                        copy="Ingresa con tu usuario de Semilla Digital para continuar."
                        mobile-logo
                    />

                    <x-validation-errors class="eus-alert eus-alert-danger auth-alert-gap" />

                    @session('status')
                        <div class="eus-alert eus-alert-success" role="status">
                            {{ $value }}
                        </div>
                    @endsession

                    <form method="POST" action="{{ route('login') }}" class="auth-login-form">
                        @csrf

                        <div>
                            <label for="username" class="eus-label eus-label-required">Usuario</label>
                            <input
                                id="username"
                                class="eus-input"
                                type="text"
                                name="username"
                                value="{{ old('username') }}"
                                required
                                autofocus
                                autocomplete="username"
                                autocapitalize="none"
                                spellcheck="false"
                                @error('username') aria-invalid="true" @enderror
                            >
                        </div>

                        <div>
                            <label for="password" class="eus-label eus-label-required">Contrase&ntilde;a</label>
                            <input
                                id="password"
                                class="eus-input"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                @error('password') aria-invalid="true" @enderror
                            >

                            @if (Route::has('password.request'))
                                <a class="auth-forgot-link" href="{{ route('password.request') }}">
                                    Olvid&eacute; mi contrase&ntilde;a
                                </a>
                            @endif
                        </div>

                        <label for="remember_me" class="eus-checkbox">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span>Mantener sesi&oacute;n activa</span>
                        </label>

                        <button type="submit" class="eus-btn eus-btn-primary w-full">
                            Ingresar
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </x-auth.shell>
</x-guest-layout>
