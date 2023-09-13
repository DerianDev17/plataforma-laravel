<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Usuarios
    </h2>
</x-slot>
<div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
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

            <x-jet-input type="text" placeholder="Carrera" wire:model="searchTerm" />
            <x-jet-input type="text" placeholder="Puntaje" wire:model="searchedGrade" />
            

            <div class="" style="overflow-x:auto;">
                <table class="w-full">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-4 py-2">Instituto</th>
                            <th class="px-4 py-2">Tipo de IES</th>
                            <th class="px-4 py-2">Financiamiento</th>
                            <th class="px-4 py-2">Carrera</th>
                            <th class="px-4 py-2">Puntaje referencial</th>
                            <th class="px-4 py-2">Cantón campus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($carrersForTable as $carrer)
                        <tr>
                            <td class="border px-4 py-2">{{ $carrer->ies_nombre_institut }}</td>
                            <td class="border px-4 py-2">{{ $carrer->ies_tipo_ies }}</td>
                            <td class="border px-4 py-2">{{ $carrer->ies_tipo_financiamiento }}</td>
                            <td class="border px-4 py-2">{{ $carrer->car_nombre_carrera }}</td>
                            <td class="border px-4 py-2">{{ $carrer->puntaje_referencial }}</td>
                            <td class="border px-4 py-2">{{ $carrer->canton_campus }}</td>
                               
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{ $carrersForTable->links('components.ui.pagination',['is_livewire' => true])}}

        </div>
    </div>
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