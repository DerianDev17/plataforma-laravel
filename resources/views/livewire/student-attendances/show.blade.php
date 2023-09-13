<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Asistencias
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
    <div class="grid grid-cols-2">
        <div>
            <!-- <button wire:click="create()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ">Registrar Asistencia</button>
            <x-jet-input type="text" placeholder="Buscar" wire:model="searchTerm" /> -->
        </div>
        <div>
            <button wire:click="downloadAttendances()" class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-2 px-4 rounded inline-flex items-center">
                <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
                </svg>
                <span>Descargar</span>
            </button>
        </div>
    </div>

    @if (count($attendances) > 0)
    <div class="py-10">
        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Usuario
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Clase
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black bg-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($attendances as $attendance)
                    <tr>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{App\Models\User::find($attendance->user_id)->email}}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{App\Models\CourseSession::find($attendance->course_session_id)->subject}}
                            {{App\Models\CourseSession::find($attendance->course_session_id)->date}}
                            {{App\Models\CourseSession::find($attendance->course_session_id)->time}}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif text-right">
                            <div class="inline-block whitespace-no-wrap">
                                <button wire:click="edit({{ $attendance->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Editar</button>
                                <button wire:click="$emit('triggerDelete',{{ $attendance->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Borrar</button>
                            </div>
                        </td>

                    </tr>

                    @endforeach
                </tbody>
            </table>
            {{ $attendances->links('components.ui.pagination',['is_livewire' => true]) }}
        </div>
    </div>
    @endif

    @if($isOpen)
    <x-ui.customised-modal>
        <x-slot name="content">
            <form>
                <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Usuarios:</label>
                            <div wire:ignore>
                                <select class="select2-usuarios mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="user_id_input" placeholder="Módulo">
                                </select>
                            </div>
                            @error('user_id') <span class="text-red-500">{{ $message }}</span>@enderror
                        </div>
                    </div>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full md:w-2/2 px-3 mb-6 md:mb-0">
                            <label for="titleInput" class="block text-gray-700 text-sm font-bold mb-2">Clase:</label>
                            <div wire:ignore>
                                <select class="select2-clases mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" id="clase_id_input" placeholder="Grupo">
                                </select>
                            </div>
                            @error('course_session_id') <span class="text-red-500">{{ $message }}</span>@enderror
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
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
@endpush

@push('javascripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {


        @this.on('triggerDelete', companyId => {
            Swal.fire({
                title: 'Confirmar acción',
                text: '¡La asistencia será eliminada!',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar'
            }).then((result) => {
                if (result.value) {
                    @this.call('delete', companyId)
                } else {
                    console.log("Canceled");
                }
            });
        });
    })

    Livewire.on('modalOpened', () => {
        // select2
        // $('.select2-usuarios').on('select2:select', (e) => {
        //     var data = e.params.data;
        //     Livewire.emit('studentSelected', data.id);
        //     console.log(data);
        // });

        jQuery('.select2-usuarios').on('change', function(e) {
            var data = $('.select2-usuarios').select2('val');
            console.log('data1 :>> ', data);
            @this.set('user_id', data);
        });

        jQuery('.select2-clases').on('change', function(e) {
            var data = $('.select2-clases').select2('val');
            console.log('data2 :>> ', data);
            @this.set('course_session_id', data);
        });

        jQuery('.select2-usuarios').select2({

            ajax: {
                url: "{{route('ajax.students')}}",
                datatype: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page,
                    }
                    return query;
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    let parsedData = JSON.parse(data);
                    // console.log('data :>> ', parsedData);
                    let dataselect = parsedData.data.map((student) => {
                        return {
                            text: student.name + ' ' + student.last_name + ' | ' + student.email,
                            id: student.id
                        }
                    })
                    return {
                        results: dataselect,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true,
            }
        });

        jQuery('.select2-clases').select2({

            ajax: {
                url: "{{route('ajax.clases')}}",
                datatype: "json",
                delay: 250,
                data: function(params) {
                    var query = {
                        search: params.term,
                        page: params.page,
                    };
                    return query;
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    let parsedData = JSON.parse(data);
                    let dataselect = parsedData.data.map((clase) => {
                        return {
                            text: clase.subject + ' | ' + clase.date + ' | ' + clase.time,
                            id: clase.id
                        }
                    })
                    return {
                        results: dataselect,
                        pagination: {
                            more: (params.page * 30) < data.total_count
                        }
                    };
                },
                cache: true,
            }

        });

    });
</script>

@endpush