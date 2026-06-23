<x-admin-layout>
    <x-slot name="header">Actualizar pagos</x-slot>

    <x-import.excel-upload-panel
        :action="route('base.upload.post')"
        title="Actualizar pagos por Excel"
        eyebrow="Estado de acceso"
        description="Carga novedades de pago y fecha de examen sin borrar estudiantes existentes."
        submit-label="Actualizar pagos"
        :show-payment-link="true"
        :payment-href="route('dashboard-students')"
    />
</x-admin-layout>
