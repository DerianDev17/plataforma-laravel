<div class="eus-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="user-form-title">
    <form class="eus-modal user-admin-modal" wire:submit.prevent="store">
        <div class="eus-modal-header">
            <div>
                <h2 id="user-form-title" class="eus-modal-title">
                    {{ $from_create ? 'Nuevo usuario' : 'Editar usuario' }}
                </h2>
                <p class="muted-small user-admin-modal-copy">
                    {{ $from_create ? 'Crea la cuenta de acceso del estudiante.' : 'Actualiza datos de acceso, contacto y pago.' }}
                </p>
            </div>
            <button type="button" class="eus-btn eus-btn-ghost eus-btn-icon" wire:click="closeModal" aria-label="Cerrar formulario">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <div class="eus-modal-body user-admin-form">
            <section class="user-admin-form-section" aria-label="Identidad">
                <div class="user-admin-section-title">Identidad</div>
                <div class="user-admin-form-grid">
                    <div>
                        <label for="user-name" class="eus-label eus-label-required">Nombre</label>
                        <input id="user-name" type="text" class="eus-input" wire:model.defer="name" autocomplete="given-name">
                        @error('name') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-last-name" class="eus-label eus-label-required">Apellido</label>
                        <input id="user-last-name" type="text" class="eus-input" wire:model.defer="last_name" autocomplete="family-name">
                        @error('last_name') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-cedula" class="eus-label eus-label-required">Cedula</label>
                        <input id="user-cedula" type="text" class="eus-input" wire:model.defer="cedula" inputmode="numeric">
                        @error('cedula') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-exam-month" class="eus-label eus-label-required">Fecha de examen</label>
                        <select id="user-exam-month" class="eus-select" wire:model.defer="exam_month">
                            <option value="Junio">Junio</option>
                            <option value="Febrero">Febrero</option>
                            <option value="Julio">Julio</option>
                        </select>
                        @error('exam_month') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <section class="user-admin-form-section" aria-label="Acceso">
                <div class="user-admin-section-title">Acceso</div>
                <div class="user-admin-form-grid">
                    <div>
                        <label for="user-username" class="eus-label eus-label-required">Usuario</label>
                        <input id="user-username" type="text" class="eus-input" wire:model.defer="username" autocomplete="username">
                        @error('username') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-password" class="eus-label {{ $from_create ? 'eus-label-required' : '' }}">Contrasena</label>
                        <input
                            id="user-password"
                            type="password"
                            class="eus-input"
                            wire:model.defer="password"
                            autocomplete="{{ $from_create ? 'new-password' : 'off' }}"
                            placeholder="{{ $from_create ? 'Minimo 8 caracteres' : 'Dejar en blanco para conservarla' }}"
                        >
                        @error('password') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-email" class="eus-label eus-label-required">Email</label>
                        <input id="user-email" type="email" class="eus-input" wire:model.defer="email" autocomplete="email">
                        @error('email') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-payment-status" class="eus-label eus-label-required">Estado de pago</label>
                        <select id="user-payment-status" class="eus-select" wire:model.defer="payment_status">
                            @foreach($paymentStatusOptions ?? \App\Models\User::paymentStatusOptions() as $value => $label)
                                <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('payment_status') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <section class="user-admin-form-section" aria-label="Contacto y datos academicos">
                <div class="user-admin-section-title">Contacto y datos academicos</div>
                <div class="user-admin-form-grid">
                    <div>
                        <label for="user-cellphone" class="eus-label eus-label-required">Celular</label>
                        <input id="user-cellphone" type="text" class="eus-input" wire:model.defer="cellphone" inputmode="tel" autocomplete="tel">
                        @error('cellphone') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-city" class="eus-label eus-label-required">Ciudad</label>
                        <input id="user-city" type="text" class="eus-input" wire:model.defer="city" autocomplete="address-level2">
                        @error('city') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-highschool" class="eus-label eus-label-required">Colegio</label>
                        <input id="user-highschool" type="text" class="eus-input" wire:model.defer="highschool">
                        @error('highschool') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="user-regimen" class="eus-label eus-label-required">Regimen</label>
                        <input id="user-regimen" type="text" class="eus-input" wire:model.defer="regimen">
                        @error('regimen') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>

            <section class="user-admin-form-section" aria-label="Representante">
                <div class="user-admin-section-title">Representante</div>
                <div class="user-admin-form-grid">
                    <div>
                        <label for="representative-name" class="eus-label eus-label-required">Nombre</label>
                        <input id="representative-name" type="text" class="eus-input" wire:model.defer="name_representante">
                        @error('name_representante') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="representative-last-name" class="eus-label eus-label-required">Apellido</label>
                        <input id="representative-last-name" type="text" class="eus-input" wire:model.defer="last_name_representante">
                        @error('last_name_representante') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="user-admin-form-wide">
                        <label for="representative-cellphone" class="eus-label eus-label-required">Celular</label>
                        <input id="representative-cellphone" type="text" class="eus-input" wire:model.defer="cellphone_representante" inputmode="tel">
                        @error('cellphone_representante') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                </div>
            </section>
        </div>

        <div class="eus-modal-footer">
            <button type="button" class="eus-btn eus-btn-secondary" wire:click="closeModal">
                Cancelar
            </button>
            <button type="submit" class="eus-btn eus-btn-primary" wire:loading.attr="disabled" wire:target="store">
                <span wire:loading.remove wire:target="store">{{ $from_create ? 'Guardar usuario' : 'Actualizar usuario' }}</span>
                <span wire:loading wire:target="store">Guardando...</span>
            </button>
        </div>
    </form>
</div>
