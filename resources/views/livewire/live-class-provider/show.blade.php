@php
    $stats = [
        ['variant' => 'success', 'icon' => 'check', 'value' => number_format($paid_students_n), 'label' => 'Habilitados'],
        ['variant' => 'info', 'icon' => 'video', 'value' => number_format($students_with_access_n), 'label' => 'Con enlace'],
        ['variant' => 'orange', 'icon' => 'bell', 'value' => number_format($pending_access_n), 'label' => 'Pendientes'],
        ['variant' => 'purple', 'icon' => 'users', 'value' => number_format($students_without_group_n), 'label' => 'Sin paralelo'],
    ];
@endphp

<div class="dashboard-stack">
    <x-ui.alert />

    @if(session()->has('error'))
        <div class="eus-alert eus-alert-danger" role="alert">
            <span>{{ session('error') }}</span>
            <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" onclick="this.parentElement.remove()" aria-label="Cerrar alerta">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <section class="dashboard-hero meetings-hero" aria-label="Proveedor de clases en vivo">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Clases en vivo</p>
                <h2 class="dashboard-title">Proveedor y enlaces de acceso</h2>
                <p class="dashboard-copy">
                    Configura el proveedor activo, administra enlaces base y sincroniza accesos para estudiantes habilitados.
                </p>
                <span class="eus-badge badge-on-dark">Proveedor activo: {{ $provider_label }}</span>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="video" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores de proveedor">
        @foreach($stats as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="eus-card" aria-label="Configuracion del proveedor">
        <div class="eus-card-header">
            <div>
                <h3 class="eus-card-title">Configuracion del proveedor</h3>
                <p class="muted-small">La implementacion activa se controla con <code>LIVE_CLASS_PROVIDER</code>.</p>
            </div>
            <span class="eus-badge eus-badge-blue">{{ $provider_key }}</span>
        </div>

        <div class="eus-card-body">
            <form wire:submit.prevent="saveLinks">
                <div class="attendance-filter-grid">
                    <div>
                        <label for="link_febrero" class="eus-label">Enlace Febrero</label>
                        <input id="link_febrero" class="eus-input" type="url" wire:model.defer="link_febrero" placeholder="https://..." />
                        @error('link_febrero') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="link_junio" class="eus-label">Enlace Junio</label>
                        <input id="link_junio" class="eus-input" type="url" wire:model.defer="link_junio" placeholder="https://..." />
                        @error('link_junio') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label for="link_julio" class="eus-label">Enlace Julio</label>
                        <input id="link_julio" class="eus-input" type="url" wire:model.defer="link_julio" placeholder="https://..." />
                        @error('link_julio') <span class="eus-error">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="eus-btn eus-btn-primary" wire:loading.attr="disabled" wire:target="saveLinks">
                        <x-ui.icon name="check" :size="18" />
                        Guardar enlaces
                    </button>
                </div>
            </form>
        </div>
    </section>

    <section class="eus-card" aria-label="Operaciones de acceso">
        <div class="eus-card-header">
            <div>
                <h3 class="eus-card-title">Operaciones de acceso</h3>
                <p class="muted-small">Estas acciones usan el proveedor activo para registrar y sincronizar enlaces individuales.</p>
            </div>
            <span class="eus-badge eus-badge-gray">{{ $provider_label }}</span>
        </div>

        <div class="eus-card-body">
            <div class="quick-actions-grid">
                <button type="button" class="eus-btn eus-btn-primary action-button" wire:click="registerPendingStudents" wire:loading.attr="disabled">
                    <x-ui.icon name="users" :size="18" />
                    Registrar estudiantes pendientes
                </button>
                <button type="button" class="eus-btn eus-btn-secondary action-button" wire:click="syncAccessLinks" wire:loading.attr="disabled">
                    <x-ui.icon name="check" :size="18" />
                    Sincronizar enlaces del proveedor
                </button>
            </div>

            <div wire:loading class="eus-alert eus-alert-info" role="status">
                <span>Procesando proveedor de clases en vivo...</span>
            </div>

            @if(! empty($access_errors))
                <div class="eus-alert eus-alert-warning" role="status">
                    <div>
                        <strong>Hay acciones que requieren revision.</strong>
                        <ul class="mt-2">
                            @foreach(array_slice($access_errors, 0, 5, true) as $email => $error)
                                <li>{{ is_string($email) ? $email . ': ' : '' }}{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
