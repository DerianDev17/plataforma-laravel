<x-admin-layout>
    <x-slot name="header">Actualizar base</x-slot>

    <x-import.excel-upload-panel
        :action="route('actualizar_base.post')"
        title="Actualizar base de estudiantes"
        eyebrow="Carga masiva"
        description="Sincroniza estudiantes desde un archivo y decide si los ausentes deben eliminarse."
        submit-label="Subir excel"
        :show-delete-option="true"
        delete-label="Borrar usuarios ausentes"
        delete-help="Usalo solo cuando el archivo sea la base completa y validada."
        :show-payment-link="true"
        :payment-href="route('dashboard-students')"
    />
</x-admin-layout>
