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
                    <label class="text-gray-700 dark:text-gray-200" for="name">Nombres <strong>Completos:</strong></label>
                    <input wire:model="name" id="name" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="lastname">Apellidos  <strong>Completos:</strong>:</label>
                    <input wire:model="last_name" id="last_name" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    @error('last_name') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

                <div>
                    <label class="text-gray-700 dark:text-gray-200" for="cedula">Número de Cédula:</label>
                    <input wire:model="cedula" id="cedula" type="text" class="block w-full px-4 py-2 mt-2 text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 focus:border-blue-500 dark:focus:border-blue-500 focus:outline-none focus:ring">
                    <p class="text-xs text-blue-500"><i>Si es extranjero anteponga la letra "p" a su pasaporte. Ejemplo: p#####</i></p>
                    @error('cedula') <span class="text-red-500">{{ $message }}</span>@enderror
                </div>

            </div>

            <div class="flex justify-end mt-6">
                <button wire:click.prevent="store()" class="px-6 py-2 leading-5 text-white transition-colors duration-200 transform bg-gray-700 rounded-md hover:bg-gray-600 focus:outline-none focus:bg-gray-600">Guardar</button>
            </div>
        </form>
    </section>

</div>