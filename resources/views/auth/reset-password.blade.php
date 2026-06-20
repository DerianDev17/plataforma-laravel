<x-guest-layout>
    <x-auth.shell compact>
        <div class="eus-card auth-compact-card">
            <div class="eus-card-body auth-compact-body">
                <x-auth.panel-header
                    title="Restablecer contrase&ntilde;a"
                    copy="Elige una nueva contrase&ntilde;a para tu cuenta."
                    center
                    mobile-logo
                />

                <x-jet-validation-errors class="auth-alert-gap eus-alert eus-alert-danger" role="alert" />

                <form method="POST" action="{{ route('password.update') }}" class="auth-form-stack" novalidate>
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div>
                        <label for="email" class="eus-label">Email</label>
                        <input id="email" class="eus-input" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="email" readonly>
                    </div>

                    <div>
                        <label for="password" class="eus-label eus-label-required">Nueva contrase&ntilde;a</label>
                        <input id="password" class="eus-input" type="password" name="password" required autocomplete="new-password" aria-required="true">
                    </div>

                    <div>
                        <label for="password_confirmation" class="eus-label eus-label-required">Confirmar contrase&ntilde;a</label>
                        <input id="password_confirmation" class="eus-input" type="password" name="password_confirmation" required autocomplete="new-password" aria-required="true">
                    </div>

                    <button type="submit" class="eus-btn eus-btn-primary eus-btn-lg eus-btn-full">
                        Restablecer contrase&ntilde;a
                    </button>
                </form>
            </div>
        </div>
    </x-auth.shell>
</x-guest-layout>
