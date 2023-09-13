<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Companies
        </h2>
    </x-slot>

    <div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="b-oferta overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                @livewire('oferta.show')
            </div>
        </div>
    </div>
</x-app-layout>
