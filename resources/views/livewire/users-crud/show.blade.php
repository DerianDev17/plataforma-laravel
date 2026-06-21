@php
    $stats = [
        ['variant' => 'info', 'icon' => 'users', 'value' => number_format($total_students_n ?? 0), 'label' => 'Usuarios estudiante'],
        ['variant' => 'success', 'icon' => 'check', 'value' => number_format($active_students_n ?? 0), 'label' => 'Con acceso'],
        ['variant' => 'orange', 'icon' => 'bell', 'value' => number_format($blocked_students_n ?? 0), 'label' => 'Vencidos'],
        ['variant' => 'purple', 'icon' => 'users', 'value' => number_format($students_without_group_n ?? 0), 'label' => 'Sin paralelo'],
    ];
@endphp

<div class="dashboard-stack user-admin-page">
    <x-ui.alert type="info" />

    @if($isOpen)
        @include('livewire.create')
    @endif

    <section class="dashboard-hero user-admin-hero" aria-label="Resumen de usuarios">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Administracion</p>
                <h2 class="dashboard-title">Usuarios y accesos</h2>
                <p class="dashboard-copy">
                    Gestiona credenciales, contacto, estado de pago y acceso a clases desde una sola vista.
                </p>
                <span class="eus-badge badge-on-dark">{{ $studentsForTable->total() }} resultados</span>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="users" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores de usuarios">
        @foreach($stats as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="eus-card" aria-label="Directorio administrativo de usuarios">
        <div class="eus-card-header user-admin-card-header">
            <div>
                <h3 class="eus-card-title">Directorio de usuarios</h3>
                <p class="muted-small">Busca por nombre, apellido, usuario o email. Actualiza estados sin salir de la tabla.</p>
            </div>
            <div class="user-admin-header-actions">
                <button type="button" wire:click="create" class="eus-btn eus-btn-primary eus-btn-sm">
                    <x-ui.icon name="users" :size="16" />
                    Nuevo usuario
                </button>
                <button type="button" wire:click="downloadStudents" class="eus-btn eus-btn-secondary eus-btn-sm">
                    <x-ui.icon name="book" :size="16" />
                    Descargar
                </button>
            </div>
        </div>

        <div class="eus-card-body user-admin-toolbar">
            <div class="user-admin-filters">
                <div>
                    <label class="eus-label" for="user-search">Buscar usuario</label>
                    <input
                        id="user-search"
                        type="text"
                        class="eus-input"
                        placeholder="Nombre, usuario o email"
                        wire:model.live.debounce.350ms="searchTerm"
                    >
                </div>
                <div>
                    <label class="eus-label" for="user-payment-filter">Estado de pago</label>
                    <select id="user-payment-filter" class="eus-select" wire:model.live="searchTerm2">
                        <option value="">Todos</option>
                        <option value="access">Con acceso</option>
                        <option value="paid">Pagado</option>
                        <option value="pending">Pendiente</option>
                        <option value="scholarship">Becado</option>
                        <option value="blocked">Vencido</option>
                    </select>
                </div>
                <div class="user-admin-filter-actions">
                    <button type="button" wire:click="resetFilters" class="eus-btn eus-btn-ghost eus-btn-sm">
                        Limpiar filtros
                    </button>
                </div>
            </div>
        </div>

        <div class="eus-table-wrapper">
            <table class="eus-table user-admin-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Usuario</th>
                        <th>Acceso</th>
                        <th>Pago</th>
                        <th>Paralelo</th>
                        <th>Contacto</th>
                        <th>Examen</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentsForTable as $student)
                        @php
                            $fullName = trim($student->name . ' ' . $student->last_name);
                            $initials = strtoupper(substr($student->name ?? 'U', 0, 1) . substr($student->last_name ?? '', 0, 1));
                            $canAccess = $student->canAccessLiveClasses();
                            $currentPaymentStatus = $student->effective_payment_status;
                            $hasGroup = ! in_array((int) $student->student_group_id, [3, 999], true);
                        @endphp
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>
                                <div class="user-admin-identity">
                                    <span class="avatar-token" aria-hidden="true">{{ $initials ?: 'U' }}</span>
                                    <div class="min-w-0">
                                        <div class="font-strong text-truncate">{{ $fullName ?: 'Sin nombre' }}</div>
                                        <div class="muted-small text-truncate">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="font-strong">{{ $student->username ?: 'Sin usuario' }}</div>
                                @if($student->must_change_password)
                                    <span class="eus-badge eus-badge-orange user-admin-status-badge">Debe cambiar clave</span>
                                @else
                                    <span class="eus-badge eus-badge-gray user-admin-status-badge">Clave vigente</span>
                                @endif
                            </td>
                            <td>
                                @if($canAccess)
                                    <span class="eus-badge eus-badge-green">{{ $student->payment_status_label ?? 'Con acceso' }}</span>
                                @else
                                    <span class="eus-badge eus-badge-red">{{ $student->payment_status_label ?? 'Bloqueado' }}</span>
                                @endif
                                <select
                                    class="eus-select user-admin-payment-select"
                                    aria-label="Actualizar estado de pago de {{ $fullName ?: $student->username }}"
                                    wire:change="updatePaymentStatus({{ $student->id }}, $event.target.value)"
                                    wire:loading.attr="disabled"
                                    wire:target="updatePaymentStatus"
                                >
                                    @foreach($paymentStatusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ $currentPaymentStatus === $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                @if($hasGroup)
                                    <span class="eus-badge eus-badge-blue">{{ optional($student->student_group)->name ?? optional($student->student_group)->code ?? 'Asignado' }}</span>
                                @else
                                    <span class="eus-badge eus-badge-red">Sin paralelo</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $student->cellphone ?: 'Sin celular' }}</div>
                                <div class="muted-small">{{ $student->city ?: 'Sin ciudad' }}</div>
                            </td>
                            <td>
                                <span class="eus-badge eus-badge-gray">{{ $student->exam_month ?: 'Sin fecha' }}</span>
                            </td>
                            <td>
                                <div class="table-actions user-admin-actions">
                                    <button type="button" wire:click="edit({{ $student->id }})" class="eus-btn eus-btn-secondary eus-btn-sm">Editar</button>
                                    <button type="button" wire:click="resetPassword({{ $student->id }})" class="eus-btn eus-btn-secondary eus-btn-sm">Reset pass.</button>
                                    <button
                                        type="button"
                                        wire:click="delete({{ $student->id }})"
                                        wire:confirm="El usuario sera eliminado definitivamente. Continuar?"
                                        class="eus-btn eus-btn-danger eus-btn-sm"
                                    >
                                        Eliminar
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="user-admin-empty-cell">
                                <div class="eus-empty empty-compact">
                                    <div class="eus-empty-title">Sin usuarios</div>
                                    <div class="eus-empty-text">No hay registros que coincidan con los filtros aplicados.</div>
                                    <button type="button" wire:click="resetFilters" class="eus-btn eus-btn-secondary eus-btn-sm">
                                        Limpiar filtros
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="eus-card-footer user-admin-footer">
            {{ $studentsForTable->links('components.ui.pagination', ['is_livewire' => true]) }}
        </div>
    </section>
</div>
