<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Buscar carreras') }}
    </h2>
</x-slot>

<livewire:careers.careers-datatables
    searchable="name, email"
    exportable
/>