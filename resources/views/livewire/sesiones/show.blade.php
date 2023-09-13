<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Sesiones
    </h2>
</x-slot>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

    @if (session()->has('message'))
    <div id="alert" class="text-white px-6 py-4 border-0 rounded relative mb-4 bg-green-500">
        <span class="inline-block align-middle mr-8">
            {{ session('message') }}
        </span>
        <button class="absolute bg-transparent text-2xl font-semibold leading-none right-0 top-0 mt-4 mr-6 outline-none focus:outline-none" onclick="document.getElementById('alert').remove();">
            <span>×</span>
        </button>
    </div>
    @endif

    <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mt-10">Crear nueva sesión</button>
    <x-jet-input type="text" placeholder="Buscar" wire:model="searchTerm" />

    @if (count($sesiones) > 0)
    <div class="py-10">
        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Materia
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Fecha y Hora
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Grupo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Módulo
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sesiones as $sesion)
                    <tr>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($sesion->subject, 25) }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($sesion->date, 25) }} {{ Str::limit($sesion->time, 25) }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ $sesion->student_group->code }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($sesion->module_number, 25) }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif text-right">
                            <div class="inline-block whitespace-no-wrap">
                                <button wire:click="edit({{ $sesion->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button>
                                <button wire:click="$emit('triggerDelete',{{ $sesion->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Borrar</button>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $sesiones->links('components.ui.pagination',['is_livewire' => true]) }}
        </div>
    </div>
    @endif

    @if($isOpen)
    <x-ui.customised-modal>
        <x-slot name="content">
            @error('session_id') <span class="text-red-500">{{ $message }}</span>@enderror

            <form>
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Materia:</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="subject" placeholder="Materia" wire:model="subject">
                                <option value="" class="py-1" selected hidden>Seleccione una materia...</option>
                                @foreach ($subjects as $subject)
                                <option value="{{$subject->id}}" class="py-1">{{$subject->name}}</option>
                                @endforeach
                            </select>
                            @error('subject') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Fecha y hora de la sesión:</label>
                            <input type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline datetime-session" id="datetime-session" placeholder="Fecha y hora" wire:model="datetime">
                            @error('datetime') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Grupo (paralelo):</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="titleInput" placeholder="Grupo" wire:model="course_id">
                                <option value="" class="py-1" selected hidden>Seleccione un grupo...</option>
                                @foreach ($student_groups as $grp)
                                <option value="{{$grp->id}}" class="py-1">{{$grp->code}}</option>
                                @endforeach
                            </select>
                            @error('course_id') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Módulo:</label>
                            <select class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="titleInput" placeholder="Grupo" wire:model="module_number">
                                <option value="" class="py-1" selected hidden>Seleccione un módulo...</option>
                                <option value="1" class="py-1">Módulo 1</option>
                                <option value="2" class="py-1">Módulo 2</option>
                                <option value="3" class="py-1">Módulo 3</option>
                                <option value="4" class="py-1">Módulo 4</option>
                                <option value="5" class="py-1">Módulo 5</option>
                            </select>
                            @error('module_number') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <span class="flex w-full sm:ml-3 sm:w-auto">
                        <button wire:click.prevent="store()" type="button" class="inline-flex bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Guardar</button>
                    </span>
                    <span class="mt-3 flex w-full sm:mt-0 sm:w-auto">
                        <button wire:click="closeModal()" type="button" class="inline-flex bg-white hover:bg-gray-200 border border-gray-300 text-gray-500 font-bold py-2 px-4 rounded">Cancelar</button>
                    </span>
                </div>
            </form>
        </x-slot>
        </x-customised-modal>
        @endif
</div>

@push('estilos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('javascripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('triggerDelete', sessionId => {
            Swal.fire({
                title: 'Confirmar acción',
                text: '¡Se eliminará la sesión!',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar'
            }).then((result) => {
                if (result.value) {
                    @this.call('delete', sessionId)
                } else {
                    console.log("Canceled");
                }
            });
        });
    });

    Livewire.on('modalOpened', () => {
        // date picker
        $(".datetime-session").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });
    });
</script>
@endpush