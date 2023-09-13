<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Información de su perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Actualice su información de perfil y su dirección de correo electrónico.') }}
    </x-slot>

    

    <x-slot name="actions">
        <x-jet-action-message class="mr-3" on="saved">
            {{ __('Guardado.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Guardar') }}
        </x-jet-button>
    </x-slot>
</x-jet-form-section>
