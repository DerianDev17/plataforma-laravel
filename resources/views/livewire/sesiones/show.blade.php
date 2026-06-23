@php
    use Carbon\Carbon;

    $statCards = [
        ['variant' => 'info', 'icon' => 'calendar', 'value' => number_format($stats['total']), 'label' => 'Sesiones creadas'],
        ['variant' => 'success', 'icon' => 'check', 'value' => number_format($stats['today']), 'label' => 'Programadas hoy'],
        ['variant' => 'orange', 'icon' => 'monitor', 'value' => number_format($stats['upcoming']), 'label' => 'Proximas'],
        ['variant' => 'purple', 'icon' => 'users', 'value' => number_format($stats['groups']), 'label' => 'Paralelos activos'],
    ];
@endphp

<div class="dashboard-stack sessions-page">
    <x-ui.alert />

    <section class="dashboard-hero sessions-hero" aria-label="Resumen de sesiones">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Calendario academico</p>
                <h2 class="dashboard-title">Sesiones de clase</h2>
                <p class="dashboard-copy">
                    Programa, filtra y edita las clases que alimentan asistencias, reuniones y accesos de estudiantes.
                </p>
                <span class="eus-badge badge-on-dark">{{ $sesiones->total() }} resultados</span>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="calendar" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores de sesiones">
        @foreach($statCards as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="eus-card" aria-label="Gestion de sesiones">
        <div class="eus-card-header sessions-card-header">
            <div>
                <h3 class="eus-card-title">Listado de sesiones</h3>
                <p class="muted-small">Busca por fecha, hora, materia, modulo o paralelo.</p>
            </div>

            <div class="sessions-toolbar-actions">
                <button type="button" wire:click="create" class="eus-btn eus-btn-primary">
                    <x-ui.icon name="calendar-plus" :size="17" />
                    Crear sesion
                </button>
            </div>
        </div>

        <div class="eus-card-body sessions-toolbar">
            <div>
                <label for="session-search" class="eus-label">Buscar</label>
                <input
                    id="session-search"
                    type="search"
                    class="eus-input"
                    placeholder="Fecha, materia, modulo o paralelo"
                    wire:model.live.debounce.350ms="searchTerm"
                >
            </div>
        </div>

        @if($sesiones->count())
            <div class="eus-table-wrapper">
                <table class="eus-table sessions-table">
                    <thead>
                        <tr>
                            <th>Sesion</th>
                            <th>Fecha y hora</th>
                            <th>Paralelo</th>
                            <th>Modulo</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sesiones as $sesion)
                            @php
                                $date = filled($sesion->date) ? Carbon::parse($sesion->date) : null;
                                $time = filled($sesion->time) ? substr($sesion->time, 0, 5) : '--:--';
                                $group = $sesion->student_group;
                            @endphp
                            <tr>
                                <td>
                                    <div class="session-subject-cell">
                                        <div class="date-token session-date-token" aria-hidden="true">
                                            {{ $date ? $date->format('d') : '--' }}
                                            <span>{{ $date ? $date->isoFormat('MMM') : '---' }}</span>
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-strong text-truncate">{{ $sesion->subject ?: 'Sesion sin materia' }}</div>
                                            <div class="muted-small text-truncate">ID {{ $sesion->id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-strong">{{ $date ? $date->format('d/m/Y') : 'Sin fecha' }}</div>
                                    <div class="muted-small">{{ $time }}</div>
                                </td>
                                <td>
                                    @if($group)
                                        <span class="eus-badge eus-badge-blue">{{ $group->code ?? $group->name }}</span>
                                        @if($group->name && $group->name !== $group->code)
                                            <div class="muted-small">{{ $group->name }}</div>
                                        @endif
                                    @else
                                        <span class="eus-badge eus-badge-red">Sin paralelo</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="eus-badge eus-badge-gray">Modulo {{ $sesion->module_number ?: '-' }}</span>
                                </td>
                                <td>
                                    <div class="table-actions">
                                        <button type="button" wire:click="edit({{ $sesion->id }})" class="eus-btn eus-btn-secondary eus-btn-sm">
                                            Editar
                                        </button>
                                        <button type="button" wire:click="$emit('triggerDelete', {{ $sesion->id }})" class="eus-btn eus-btn-danger eus-btn-sm">
                                            Borrar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="eus-card-footer sessions-footer">
                {{ $sesiones->links('components.ui.pagination', ['is_livewire' => true]) }}
            </div>
        @else
            <div class="eus-empty sessions-empty">
                <div class="eus-empty-icon">
                    <x-ui.icon name="calendar" :size="28" />
                </div>
                <div class="eus-empty-title">Sin sesiones registradas</div>
                <div class="eus-empty-text">
                    Crea la primera sesion o ajusta la busqueda para revisar el calendario academico.
                </div>
                <button type="button" wire:click="create" class="eus-btn eus-btn-primary">
                    <x-ui.icon name="calendar-plus" :size="17" />
                    Crear sesion
                </button>
            </div>
        @endif
    </section>

    @if($isOpen)
        <x-ui.customised-modal>
            <x-slot name="content">
                <form class="sessions-form" wire:submit.prevent="store" novalidate>
                    <div class="eus-modal-header">
                        <div>
                            <h3 class="eus-modal-title">{{ $session_id ? 'Editar sesion' : 'Crear nueva sesion' }}</h3>
                            <p class="muted-small">Define materia, paralelo, fecha y modulo para la clase.</p>
                        </div>
                        <button type="button" wire:click="closeModal" class="eus-btn eus-btn-ghost eus-btn-icon" aria-label="Cerrar modal">
                            &times;
                        </button>
                    </div>

                    <div class="eus-modal-body sessions-form-grid">
                        <div>
                            <label for="session-subject" class="eus-label eus-label-required">Materia</label>
                            <select
                                id="session-subject"
                                class="eus-select"
                                wire:model.defer="subject"
                                required
                                @error('subject') aria-invalid="true" aria-describedby="session-subject-error" @enderror
                            >
                                <option value="">Seleccione una materia</option>
                                @foreach ($subjects as $subjectOption)
                                    <option value="{{ $subjectOption->id }}">{{ $subjectOption->name }}</option>
                                @endforeach
                            </select>
                            @error('subject')
                                <p id="session-subject-error" class="eus-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="session-group" class="eus-label eus-label-required">Grupo o paralelo</label>
                            <select
                                id="session-group"
                                class="eus-select"
                                wire:model.defer="student_groups_id"
                                required
                                @error('student_groups_id') aria-invalid="true" aria-describedby="session-group-error" @enderror
                            >
                                <option value="">Seleccione un grupo</option>
                                @foreach ($student_groups as $groupOption)
                                    <option value="{{ $groupOption->id }}">{{ $groupOption->code }} {{ $groupOption->name ? '- ' . $groupOption->name : '' }}</option>
                                @endforeach
                            </select>
                            @error('student_groups_id')
                                <p id="session-group-error" class="eus-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="datetime-session" class="eus-label eus-label-required">Fecha y hora</label>
                            <input
                                type="text"
                                class="eus-input datetime-session"
                                id="datetime-session"
                                placeholder="YYYY-MM-DD HH:mm"
                                wire:model.defer="datetime"
                                required
                                @error('datetime') aria-invalid="true" aria-describedby="session-datetime-error" @enderror
                            >
                            @error('datetime')
                                <p id="session-datetime-error" class="eus-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="session-module" class="eus-label eus-label-required">Modulo</label>
                            <select
                                id="session-module"
                                class="eus-select"
                                wire:model.defer="module_number"
                                required
                                @error('module_number') aria-invalid="true" aria-describedby="session-module-error" @enderror
                            >
                                <option value="">Seleccione un modulo</option>
                                @for($module = 1; $module <= 5; $module++)
                                    <option value="{{ $module }}">Modulo {{ $module }}</option>
                                @endfor
                            </select>
                            @error('module_number')
                                <p id="session-module-error" class="eus-error" role="alert">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="eus-modal-footer sessions-form-actions">
                        <button type="button" wire:click="closeModal" class="eus-btn eus-btn-secondary">
                            Cancelar
                        </button>
                        <button type="submit" class="eus-btn eus-btn-primary">
                            Guardar sesion
                        </button>
                    </div>
                </form>
            </x-slot>
        </x-ui.customised-modal>
    @endif
</div>

@push('estilos')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@endpush

@push('javascripts')
<script defer src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        Livewire.on('triggerDelete', sessionId => {
            Swal.fire({
                title: 'Confirmar accion',
                text: 'Se eliminara la sesion.',
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.value) {
                    @this.call('delete', sessionId)
                }
            });
        });
    });

    Livewire.on('modalOpened', () => {
        document.querySelectorAll('.datetime-session').forEach((input) => {
            if (input._flatpickr) {
                input._flatpickr.destroy();
            }

            flatpickr(input, {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
            });
        });
    });
</script>
@endpush
