<div class="flex flex-col">
    <div class="-my-2 overflow-x-auto">
        <div class="py-2 align-middle inline-block min-w-full">
            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                <p class="text-lg text-center font-bold m-5">Control de asistencias</p>
                <button wire:click="$emit('clickGuardar')">Guardar</button>

                @foreach ($sessions as $session)

                <table class="rounded-t-lg m-5 w-5/6 mx-auto bg-gray-200 text-gray-800">
                    <tr class="text-left border-b-2 border-gray-300">
                        <th class="px-4 py-3">ASIGNATURA</th>
                        <th class="px-4 py-3">L</th>
                        <th class="px-4 py-3">M</th>
                        <th class="px-4 py-3">M</th>
                        <th class="px-4 py-3">J</th>
                        <th class="px-4 py-3">V</th>
                        <th class="px-4 py-3">S</th>
                        <th class="px-4 py-3">TOTAL</th>
                    </tr>
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <td class="px-4 py-3">C. Naturales</td>
                        @foreach(array_slice($session, 0 , 6) as $s)
                        <td class="px-4 py-3">@livewire('eus-checkbox', ['sesion' => $s])</td>
                        @endforeach
                        <td class="px-4 py-3">0</td>
                    </tr>

                    <tr class="bg-gray-100 border-b border-gray-200">
                        <td class="px-4 py-3">C. Sociales</td>
                        @foreach(array_slice($session, 6 , 6) as $s )
                        <td class="px-4 py-3">@livewire('eus-checkbox', ['sesion' => $s])</td>
                        @endforeach
                        
                        <td class="px-4 py-3">0</td>
                    </tr>

                    <tr class="bg-gray-100 border-b border-gray-200">
                        <td class="px-4 py-3">Lengua y Lit.</td>
                        @foreach(array_slice($session, 12 , 6) as $s )
                        <td class="px-4 py-3">@livewire('eus-checkbox', ['sesion' => $s])</td>
                        @endforeach
                        <td class="px-4 py-3">0</td>
                    </tr>

                    <!-- each row -->
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <td class="px-4 py-3">Matemática</td>
                        @foreach(array_slice($session, 18 , 6) as $s )
                        <td class="px-4 py-3">@livewire('eus-checkbox', ['sesion' => $s])</td>
                        @endforeach
                        <td class="px-4 py-3">0</td>
                    </tr>
                    <!-- each row -->
                    <tr class="bg-gray-100 border-b border-gray-200">
                        <td class="px-4 py-3">Orientacion</td>
                        @foreach(array_slice($session, 24 , 6) as $s )
                        <td class="px-4 py-3">@livewire('eus-checkbox', ['sesion' => $s])</td>
                        @endforeach
                        <td class="px-4 py-3">0</td>
                    </tr>
                    <!-- each row -->

                </table>

                @endforeach

                <!-- classic design -->

            </div>
        </div>
    </div>
</div>