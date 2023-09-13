<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @if (Laravel\Fortify\Features::canUpdateProfileInformation())
            @livewire('profile.update-profile-information-form')

            <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
            <div class="mt-10 sm:mt-0">
                @livewire('profile.update-password-form')
            </div>

            <x-jet-section-border />
            @endif

            @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
            <div class="mt-10 sm:mt-0">
                @livewire('profile.two-factor-authentication-form')
            </div>

            <x-jet-section-border />
            @endif

            <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

            @if ( Auth::user()->hasRole('superadmin') || true )
            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                <div wire:id="OL3oyNJKrltreEP6vYbh" class="md:grid md:grid-cols-3 md:gap-6">
                    <div class="md:col-span-1">
                        <div class="px-4 sm:px-0">
                            <h3 class="text-lg font-medium text-gray-900">Certificado</h3>

                            <p class="mt-1 text-sm text-gray-600">
                                Descargue el certificado de participación en EUS3
                            </p>
                        </div>
                    </div>

                    <div class="mt-5 md:mt-0 md:col-span-2">
                        <form wire:submit.prevent="updateProfileInformation">
                            <div class="shadow overflow-hidden sm:rounded-md">
                                <div class="px-4 py-5 bg-white sm:p-6">
                                    <div class="grid grid-cols-6 gap-6">
                                        <div class="col-span-6 sm:col-span-4">
                                            Descargar certificado.
                                        </div>
                                    </div>

                                    @if ( !(Auth::user()->certif_intentos > 0))
                                    <div class="max-w-xl text-sm text-red-600">
                                        El certificado ya no está disponible.
                                    </div>
                                    @else
                                    <div class="max-w-xl text-sm text-red-600">
                                        Atención!!
                                        Solo podrá descargar el certificado una vez.
                                    </div>
                                    @endif

                                </div>

                                <div class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6">
                                    <x-jet-dropdown-link {{-- onclick="generarPdf(event)" --}} href="{{ Auth::user()->certif_intentos > 0 ? route('pdfview',['download'=>'pdf']) : '#'}}">
                                        {{ __('Certificado') }}
                                    </x-jet-dropdown-link>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            <x-jet-section-border />

            <div class="mt-10 sm:mt-0">
                @livewire('profile.delete-user-form')
            </div>
        </div>
    </div>

    @push('javascripts')
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>

    <script>
        function generarPdf(e) {
            e.preventDefault();
            var r = confirm("¿Está seguro que desea continuar con la descarga?");
            if (r == true) {
                console.log(e.target);
                $.get("/pdfview?download=pdf").done(function(data) {
                    // history.go(0);
                });

            } else {}
        }
    </script>
    @endpush
</x-app-layout>