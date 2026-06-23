@php
    $stats = [
        ['variant' => 'info', 'icon' => 'users', 'value' => number_format($total_students_n), 'label' => 'Estudiantes'],
        ['variant' => 'success', 'icon' => 'check', 'value' => number_format($active_students_n), 'label' => 'Habilitados'],
        ['variant' => 'orange', 'icon' => 'bell', 'value' => number_format($blocked_students_n), 'label' => 'Vencidos'],
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

    @if($isOpen)
        @include('livewire.create')
    @endif

    <section class="dashboard-hero meetings-hero" aria-label="Resumen de estudiantes">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Estudiantes</p>
                <h2 class="dashboard-title">Directorio y estado academico</h2>
                <p class="dashboard-copy">
                    Administra datos, estados de pago, paralelos y credenciales de los estudiantes.
                </p>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="users" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores de estudiantes">
        @foreach($stats as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="eus-card" aria-label="Directorio de estudiantes">
        <div class="eus-card-header">
            <div>
                <h3 class="eus-card-title">Directorio de estudiantes</h3>
                <p class="muted-small">Filtra, exporta y gestiona datos individuales.</p>
            </div>
            <div class="user-admin-header-actions">
                <button type="button" wire:click="create" class="eus-btn eus-btn-primary eus-btn-sm">
                    <x-ui.icon name="users" :size="16" />
                    Nuevo estudiante
                </button>
                <button type="button" wire:click="downloadStudents" class="eus-btn eus-btn-secondary eus-btn-sm">
                    <x-ui.icon name="book" :size="16" />
                    Descargar
                </button>
            </div>
        </div>

        <div class="eus-card-body">
            <div class="attendance-filter-grid">
                <div>
                    <label class="eus-label" for="student-search">Buscar estudiante</label>
                    <input id="student-search" type="text" class="eus-input" placeholder="Nombre, usuario o email" wire:model.debounce.350ms="searchTerm" />
                </div>
                <div>
                    <label class="eus-label" for="student-payment">Estado de pago</label>
                    <select id="student-payment" class="eus-select" wire:model="searchTerm2">
                        <option value="">Todos</option>
                        <option value="access">Habilitados</option>
                        <option value="paid">Pagado</option>
                        <option value="pending">Pendiente</option>
                        <option value="scholarship">Becado</option>
                        <option value="blocked">Vencido</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="eus-table-wrapper">
            <table class="eus-table">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Estudiante</th>
                        <th>Usuario</th>
                        <th>Pago</th>
                        <th>Paralelo</th>
                        <th>Contacto</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($studentsForTable as $student)
                        @php
                            $isPaid = $student->canAccessLiveClasses();
                            $currentPaymentStatus = $student->effective_payment_status;
                            $hasGroup = ! in_array((int) $student->student_group_id, [3, 999], true);
                        @endphp
                        <tr>
                            <td>{{ $student->id }}</td>
                            <td>
                                <div class="font-strong">{{ $student->name }} {{ $student->last_name }}</div>
                                <div class="muted-small">{{ $student->email }}</div>
                            </td>
                            <td>{{ $student->username }}</td>
                            <td>
                                @if($isPaid)
                                    <span class="eus-badge eus-badge-green">{{ $student->payment_status_label ?? 'Al dia' }}</span>
                                @else
                                    <span class="eus-badge eus-badge-red">{{ $student->payment_status_label ?? 'Bloqueado' }}</span>
                                @endif
                                <select
                                    class="eus-select mt-2"
                                    aria-label="Actualizar estado de pago de {{ $student->name }} {{ $student->last_name }}"
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
                                    <span class="eus-badge eus-badge-gray">{{ optional($student->student_group)->name ?? 'Paralelo asignado' }}</span>
                                @else
                                    <span class="eus-badge eus-badge-red">Sin paralelo</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $student->cellphone }}</div>
                                <div class="muted-small">{{ $student->city }}</div>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <button type="button" wire:click="edit({{ $student->id }})" class="eus-btn eus-btn-secondary eus-btn-sm">Editar</button>
                                    <button type="button" wire:click="resetPassword({{ $student->id }})" class="eus-btn eus-btn-secondary eus-btn-sm">Reset pass.</button>
                                    <button type="button" wire:click="$emit('triggerDelete',{{ $student->id }})" class="eus-btn eus-btn-danger eus-btn-sm">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="eus-empty empty-compact">
                                    <div class="eus-empty-title">Sin estudiantes</div>
                                    <div class="eus-empty-text">Ajusta los filtros o carga estudiantes en la base.</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="eus-card-footer">
            {{ $studentsForTable->links('components.ui.pagination', ['is_livewire' => true]) }}
        </div>
    </section>
</div>

@push('javascripts')
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('triggerDelete', studentId => {
            Swal.fire({
                title: 'Confirmar accion',
                text: 'El estudiante sera eliminado.',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Borrar',
                cancelButtonText: 'Cancelar',
            }).then((result) => {
                if (result.value) {
                    @this.call('delete', studentId)
                }
            });
        })
    })
</script>
@endpush
