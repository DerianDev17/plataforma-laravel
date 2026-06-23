<x-admin-layout>
    <x-slot name="header">Importar estudiantes</x-slot>

    <x-import.excel-upload-panel
        :action="route('excel.upload.post')"
        title="Importar estudiantes"
        eyebrow="Nueva carga"
        description="Registra estudiantes desde Excel manteniendo una validacion clara antes de procesar."
        submit-label="Importar archivo"
    />
</x-admin-layout>
