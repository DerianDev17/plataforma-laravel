<x-guest-layout>
    <x-jet-authentication-card class="bg-purple-600 bg-opacity-100">
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="grid grid-cols-6 gap-6">

                <div class="col-span-6 sm:col-span-5 text-3xl font-bold leading-tight text-gray-900">Datos del estudiante</div>

                <div class="col-span-12 sm:col-span-4 ">
                    <x-jet-label class="text-red-700" for="name" value="{{ __('Campo obligatorio (*)') }}" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="name"> Nombres <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="lastname"> Apellidos <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="lastname" class="block mt-1 w-full" type="text" name="lastname" :value="old('lastname')" required autofocus autocomplete="lastname" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="cedula"> Cédula <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="cedula" class="block mt-1 w-full" type="cedula" name="cedula" :value="old('cedula')" required />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="cellphone"> Celular <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="cellphone" class="block mt-1 w-full" type="text" name="cellphone" :value="old('cellphone')" required autofocus autocomplete="cellphone" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="email"> Email <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                </div>

                <!-- <div class="col-span-6 sm:col-span-2">
                    <x-jet-label for="fixedphone"> Convencional </x-jet-label>
                    <x-jet-input id="fixedphone" class="block mt-1 w-full" type="text" name="fixedphone" :value="old('fixedphone')" required autofocus autocomplete="fixedphone" />
                </div> -->

                <div class="col-span-6 sm:col-span-3">
                    <label for="price" class="block text-sm font-medium text-gray-700">Convencional</label>
                    <div class="flex mt-1 relative shadow-sm">
                        <div class="flex items-center pointer-events-none">
                        </div>
                        <div class="flex items-center">
                            <label for="code" class="sr-only">Currency</label>
                            <select id="code" name="code" class="rselec h-full py-0 pl-2 pr-7 border bg-transparent text-gray-500 sm:text-sm" style="border-radius: 5px 0px 0px 5px;">
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                                <option>05</option>
                                <option>06</option>
                                <option>07</option>
                            </select>
                        </div>
                        <input type="text" name="fixedphone" id="fixedphone" class="form-input rselec  block w-full" placeholder="234567" style="border-radius: 0px 5px 5px 0px;">
                    </div>
                </div>



                <div class="col-span-6 sm:col-span-5">
                    <x-jet-label for="highschool"> Colegio <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="highschool" class="block mt-1 w-full" type="text" name="highschool" :value="old('highschool')" required autofocus autocomplete="highschool" />
                </div>

                <div class="col-span-6 sm:col-span-4">
                    <x-jet-label for="especialty"> Especialidad </x-jet-label>
                    <x-jet-input id="especialty" class="block mt-1 w-full" type="text" name="especialty" :value="old('especialty')" autofocus autocomplete="especialty" />
                </div>

                <div class="col-span-6 sm:col-span-2">
                    <x-jet-label for="paralelo"> Paralelo </x-jet-label>
                    <x-jet-input id="paralelo" class="block mt-1 w-full" type="text" name="paralelo" :value="old('paralelo')"  autofocus autocomplete="paralelo" />
                </div>

                <div class="col-span-6 sm:col-span-5">
                    <x-jet-label for="city"> Ciudad <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="city" class="block mt-1 w-full" type="text" name="city" :value="old('city')" required autofocus autocomplete="city" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="country"> Régimen <span class="text-red-700">*</span></x-jet-label>
                    <select id="country" name="regimen" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option>Sierra</option>
                        <option>Costa</option>
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="country"> Fecha de examen <span class="text-red-700">*</span></x-jet-label>
                    <select id="country" name="fecha_examen" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option>Febrero</option>
                        <option>Junio</option>
                    </select>
                </div>

                <div class="col-span-6 sm:col-span-5">
                    <x-jet-label for="password"> Contraseña <span class="text-red-800">*</span></x-jet-label>
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
                </div>

                <div class="col-span-6 sm:col-span-5">
                    <x-jet-label for="password_confirmation"> Confirmación de Contraseña <span class="text-red-700">*</span></x-jet-label>
                    <x-jet-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="col-span-6 sm:col-span-5 text-3xl font-bold leading-tight text-gray-900">Datos del representante</div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="name_representant"> Nombres <span class="text-red-800">*</span></x-jet-label>
                    <x-jet-input id="name_representant" class="block mt-1 w-full" type="text" name="name_representant" :value="old('name_representant')" required autofocus autocomplete="name" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="lastname_representant"> Apellidos <span class="text-red-800">*</span></x-jet-label>
                    <x-jet-input id="lastname_representant" class="block mt-1 w-full" type="text" name="lastname_representant" :value="old('lastname_representant')" required autofocus autocomplete="lastname" />
                </div>

                <div class="col-span-6 sm:col-span-3">
                    <x-jet-label for="cellphone_representant"> Celular <span class="text-red-800">*</span></x-jet-label>
                    <x-jet-input id="cellphone_representant" class="block mt-1 w-full" type="text" name="cellphone_representant" :value="old('cellphone_representant')" required autofocus autocomplete="cellphone" />
                </div>

                <!-- <div class="flex items-center justify-end mt-4"> -->
                <div class="col-span-6 sm:col-span-6 flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('¿Ya tiene una cuenta?') }}
                    </a>

                    <x-jet-button class="ml-4">
                        {{ __('Registrarse') }}
                    </x-jet-button>
                </div>
            </div>
            <input type="tel" id="demo" placeholder="" id="telephone">
        </form>
    </x-jet-authentication-card>

    @push('estilos')
    <link rel="stylesheet" href="build/css/intlTelInput.css">
    @endpush

    @push('javascripts')
    <script src="build/js/intlTelInput.min.js"></script>
    <script src="https://code.jquery.com/jquery-latest.min.js"></script>
    <script src="build/js/intlTelInput-jquery.min.js"></script>

    <script>
        var input = document.querySelector("#telephone");
        window.intlTelInput(input, ({}));
        $("#telephone").intlTelInput({});
    </script>
    @endpush

</x-guest-layout>
