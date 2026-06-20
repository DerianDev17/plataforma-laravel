<x-app-layout>
    <x-slot name="header">Estudiantes</x-slot>

    <div class="py-12">
    <div class="mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-4 py-4">
                @livewire('students.show')
            </div>
        </div>
    </div>
</x-app-layout>
