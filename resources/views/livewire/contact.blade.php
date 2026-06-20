<div class="dashboard-stack">
    <section class="eus-card">
        <div class="eus-card-header">
            <h2 class="eus-card-title">{{ $updateMode ? 'Editar contacto' : 'Nuevo contacto' }}</h2>
        </div>
        <div class="eus-card-body">
            <form class="contact-form" wire:submit.prevent="{{ $updateMode ? 'update' : 'store' }}" novalidate>
                <input type="hidden" wire:model="selected_id">

                <div>
                    <label for="contact-name" class="eus-label eus-label-required">Nombre</label>
                    <input
                        id="contact-name"
                        type="text"
                        class="eus-input"
                        wire:model.defer="name"
                        required
                        aria-required="true"
                        @error('name') aria-invalid="true" aria-describedby="contact-name-error" @enderror
                    >
                    @error('name')
                        <p id="contact-name-error" class="eus-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="contact-email" class="eus-label eus-label-required">Email</label>
                    <input
                        id="contact-email"
                        type="email"
                        class="eus-input"
                        wire:model.defer="email"
                        required
                        autocomplete="email"
                        aria-required="true"
                        @error('email') aria-invalid="true" aria-describedby="contact-email-error" @enderror
                    >
                    @error('email')
                        <p id="contact-email-error" class="eus-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="contact-actions">
                    @if($updateMode)
                        <button type="button" class="eus-btn eus-btn-secondary" wire:click="cancel">
                            Cancelar
                        </button>
                    @endif
                    <button type="submit" class="eus-btn eus-btn-primary">
                        {{ $updateMode ? 'Actualizar' : 'Guardar' }}
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="eus-card">
        <div class="eus-card-header">
            <h2 class="eus-card-title">Contactos registrados</h2>
            <span class="eus-badge eus-badge-gray">{{ $data->count() }}</span>
        </div>

        <div class="eus-table-wrapper">
            <table class="eus-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td class="font-strong">{{ $item->name }}</td>
                            <td>{{ $item->email }}</td>
                            <td>
                                <div class="table-actions">
                                    <button type="button" wire:click="edit({{ $item->id }})" class="eus-btn eus-btn-sm eus-btn-secondary">
                                        Editar
                                    </button>
                                    <button type="button" wire:click="destroy({{ $item->id }})" class="eus-btn eus-btn-sm eus-btn-danger">
                                        Borrar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="eus-empty empty-compact">
                                    <div class="eus-empty-title">Sin contactos</div>
                                    <div class="eus-empty-text">A&uacute;n no hay informaci&oacute;n registrada.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
