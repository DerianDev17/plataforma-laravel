<x-action-section>
    <x-slot name="title">
        {{ __('Verificación en dos pasos') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Agregue seguridad adicional a su cuenta usando autenticación en dos pasos.') }}
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-gray-900">
            @if ($this->enabled)
                {{ __('Ha habilitado la autenticación en dos pasos.') }}
            @else
                {{ __('La autenticación en dos pasos está deshabilitada.') }}
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            <p>
                {{ __('Cuando se habilita la autenticación de dos factores, se le pedirá un token seguro y aleatorio durante la autenticación. Puedes obtener este token desde la aplicación Google Authenticator de tu teléfono.') }}
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        {{ __('La autenticación de dos factores está ahora activada. Escanee el siguiente código QR usando la aplicación de autentificación de tu teléfono.') }}
                    </p>
                </div>

                <div class="mt-4 dark:p-4 dark:w-56 dark:bg-white">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        {{ __('Guarda estos códigos de recuperación en un administrador de contraseñas seguro. Pueden utilizarse para recuperar el acceso a su cuenta si se pierde su dispositivo de autenticación de dos factores.') }}
                    </p>
                </div>
                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5">
            @if (! $this->enabled)
                <x-confirms-password wire:then="enableTwoFactorAuthentication">
                    <x-button type="button" wire:loading.attr="disabled">
                        {{ __('Habilitar') }}
                    </x-button>
                </x-confirms-password>
            @else
                @if ($showingRecoveryCodes)
                    <x-confirms-password wire:then="regenerateRecoveryCodes">
                        <x-secondary-button class="mr-3">
                            {{ __('Regenerar códigos de recuperación') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @else
                    <x-confirms-password wire:then="showRecoveryCodes">
                        <x-secondary-button class="mr-3">
                            {{ __('Mostrar códigos de recuperación') }}
                        </x-secondary-button>
                    </x-confirms-password>
                @endif

                <x-confirms-password wire:then="disableTwoFactorAuthentication">
                    <x-danger-button wire:loading.attr="disabled">
                        {{ __('Deshabilitar') }}
                    </x-danger-button>
                </x-confirms-password>
            @endif
        </div>
    </x-slot>
</x-action-section>
