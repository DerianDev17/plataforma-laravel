<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Oferta Académica') }}
    </h2>
</x-slot>
<div class="mx-auto px-4 sm:px-6 lg:px-8">

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

    <x-jet-input type="text"  placeholder="Buscar" wire:model="searchTerm" />
    <x-jet-input type="text"  placeholder="Por puntaje" wire:model="scoreSearchTerm" />

    @if (count($ofertas)>0)
    <div class="py-10 o-table">
        <div class="inline-block min-w-full shadow rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead class="oferta-a">
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Institución') }}
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Carrera') }}
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Campus') }}
                        </th>
                        <!-- <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Provincia') }}
                        </th> -->
                        <!-- <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Modalidad') }}
                        </th> -->
                        <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Jornada') }}
                        </th>
                        <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            Puntaje referencial
                        </th>
                        <!-- <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Sitio web') }}
                        </th> -->
                        <th class="px-5 py-3 border-b-2 border-black text-left text-xs font-semibold text-white uppercase tracking-wider">
                            {{ __('Tipo de Institución') }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ofertas as $oferta)
                    <tr>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->institucion, 80) }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->carrera, 80) }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->campus, 25) }}
                        </td>
                        <!-- <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->provincia, 25) }}
                        </td> -->
                        <!-- <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->modalidad, 25) }}
                        </td> -->
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->jornada, 25) }}
                        </td>
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{intval($oferta->puntaje_referencial) ? $oferta->puntaje_referencial : 'Carrera nueva'}}
                        </td>
                        <!-- <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->website, 25) }}
                        </td> -->
                        <td class="px-5 py-5 bg-white text-sm @if (!$loop->last) border-gray-200 border-b @endif">
                            {{ Str::limit($oferta->tipo_institucion, 25) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $ofertas->links('components.ui.pagination',['is_livewire' => true]) }}
        </div>
    </div>
    @endif
</div>

@push('estilos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
@endpush

@push('javascripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/promise-polyfill@8/dist/polyfill.js"></script>
<script type="text/javascript">
</script>
@endpush
