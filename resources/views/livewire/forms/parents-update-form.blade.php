<div>
    {{-- The Master doesn't talk, he acts. --}}

    <section class="max-w-4xl p-6 mx-auto bg-white rounded-md dark:bg-gray-800">
        <h2 class="text-lg font-semibold text-gray-700 capitalize dark:text-white mb-1">Actualización de información</h2>
        <p class="text-sm italic">
            <span class="text-red-500">Atención</span>
            Le solicitamos de la manera más encarecida, actualice sus datos llenando el siguiente formulario. Estamos trabajando
            para poder brindarle un mejor servicio. Gracias. EUS3
        </p>
        <form>
            <div class="grid grid-cols-1 gap-6 mt-4 sm:grid-cols-2">
                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="name">Nombres <strong>Completos</strong> del representante 1:</label>
                    <input wire:model="name" id="name" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="lastname">Nombres <strong>Completos</strong> del representante 2:</label>
                    <input wire:model="last_name" id="last_name" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('last_name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="cedulap">Número de Cédula del representante 1:</label>
                    <input wire:model="cedulap" id="cedulap" type="text" required class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    <p class="text-xs text-blue-500"><i>Si es extranjero anteponga la letra "p" a su pasaporte. Ejemplo: p#####</i></p>
                    @error('cedulap') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="cedulam">Número de Cédula del representante 2:</label>
                    <input wire:model="cedulam" id="cedulam" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    <p class="text-xs text-blue-500"><i>Si es extranjero anteponga la letra "p" a su pasaporte. Ejemplo: p#####</i></p>
                    @error('cedulam') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="correop">Correo electrónico del representante 1:</label>
                    <input wire:model="correop" id="correop" type="email" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('correop') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="correom">Correo electrónico del representante 2:</label>
                    <input wire:model="correom" id="correom" type="email" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('correom') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="celularp">Número celular del representante 1:</label>
                    <input wire:model="celularp" id="celularp" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('celularp') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="celularm">Número celular del representante 2:</label>
                    <input wire:model="celularm" id="celularm" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('celularm') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button wire:click.prevent="store()" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Guardar</button>
            </div>
        </form>
    </section>

</div>