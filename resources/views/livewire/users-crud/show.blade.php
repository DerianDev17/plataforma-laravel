<div>

    @if (session()->has('message'))
    <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md my-3" role="alert">
        <div class="flex">
            <div>
                <p class="text-sm">{{ session('message') }}</p>
            </div>
        </div>
    </div>
    @endif
    @if($isOpen)
    @include('livewire.create')
    @endif

    <x-jet-input type="text" placeholder="Email" wire:model="searchTerm" />
    <x-jet-input type="text" placeholder="Pagado" wire:model="searchTerm2" />
    <button wire:click="downloadStudents()" class="bg-grey-light hover:bg-grey text-grey-darkest font-bold py-2 px-4 rounded inline-flex items-center">
        <svg class="w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
            <path d="M13 8V2H7v6H2l8 8 8-8h-5zM0 18h20v2H0v-2z" />
        </svg>
        <span>Descargar</span>
    </button>

    <div class="" style="overflow-x:auto;">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 w-20">No.</th>
                    <th class="px-4 py-2">Nombre</th>
                    <th class="px-4 py-2">Apellido</th>
                    <th class="px-4 py-2">Usuario</th>
                    <th class="px-4 py-2 w-10">Pagado</th>
                    <th class="px-4 py-2">Celular</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($studentsForTable as $student)
                @if ($student->hasRole('student'))
                <tr>
                    <td class="border px-4 py-2">{{ $student->id }}</td>
                    <td class="border px-4 py-2">{{ $student->name }}</td>
                    <td class="border px-4 py-2">{{ $student->last_name }}</td>
                    <td class="border px-4 py-2">{{ $student->username }}</td>
                    <td class="border px-4 py-2">{{ $student->status }}</td>
                    <td class="border px-4 py-2">{{ $student->cellphone }}</td>
                    <td class="border px-4 py-2">{{ $student->email }}</td>
                    <td class="border px-4 py-2">
                        <button wire:click="edit({{ $student->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Editar</button>
                        <!-- <button onclick="borrarUser({{{ $student->id }}})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Borrar</button> -->
                        <button wire:click="$emit('triggerDelete',{{ $student->id }})" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded">Eliminar</button>
                        <button wire:click="resetPassword({{ $student->id }})" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-1 px-2 rounded">Reset pass.</button>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $studentsForTable->links('components.ui.pagination',['is_livewire' => true])}}
</div>


@push('javascripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // console.log(companyId)
        Livewire.on('triggerDelete', studentId => {
            Swal.fire({
                title: 'Confirmar acción',
                text: 'El estudiante será eliminado.',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '¡Borrar!',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    @this.call('delete', studentId)
                } else {
                    console.log("Canceled");
                }
            });
        })
    })
</script>
@endpush