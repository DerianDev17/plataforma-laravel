@php
    use Carbon\Carbon;

    $statCards = [
        ['variant' => 'info', 'icon' => 'check', 'value' => number_format($stats['total']), 'label' => 'Registros totales'],
        ['variant' => 'success', 'icon' => 'calendar', 'value' => number_format($stats['today']), 'label' => 'Registradas hoy'],
        ['variant' => 'purple', 'icon' => 'users', 'value' => number_format($stats['students']), 'label' => 'Estudiantes con asistencia'],
        ['variant' => 'orange', 'icon' => 'book', 'value' => number_format($stats['sessions']), 'label' => 'Clases con registro'],
    ];
@endphp

<div class="dashboard-stack">
    <x-ui.alert />

    <section class="dashboard-hero attendance-hero" aria-label="Resumen administrativo de asistencias">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Administracion academica</p>
                <h2 class="dashboard-title">Control de asistencias</h2>
                <p class="dashboard-copy">
                    Registra asistencias manuales, revisa el historial y filtra por estudiante, clase o fecha.
                </p>
            </div>
            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="check" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores de asistencia">
        @foreach($statCards as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="attendance-admin-grid" aria-label="Registro y filtros">
        <article class="eus-card">
            <div class="eus-card-header">
                <h3 class="eus-card-title">Registrar asistencia</h3>
            </div>
            <div class="eus-card-body">
                <form wire:submit.prevent="store" class="attendance-form" novalidate>
                    <div>
                        <label for="attendance-user" class="eus-label eus-label-required">Estudiante</label>
                        <select
                            id="attendance-user"
                            class="eus-input"
                            wire:model.defer="formUserId"
                            required
                            aria-required="true"
                            @error('formUserId') aria-invalid="true" aria-describedby="attendance-user-error" @enderror
                        >
                            <option value="">Seleccionar estudiante</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} {{ $student->last_name }} - {{ $student->email }}
                                </option>
                            @endforeach
                        </select>
                        @error('formUserId')
                            <p id="attendance-user-error" class="eus-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="attendance-session" class="eus-label eus-label-required">Clase</label>
                        <select
                            id="attendance-session"
                            class="eus-input"
                            wire:model.defer="formCourseSessionId"
                            required
                            aria-required="true"
                            @error('formCourseSessionId') aria-invalid="true" aria-describedby="attendance-session-error" @enderror
                        >
                            <option value="">Seleccionar clase</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">
                                    {{ Carbon::parse($session->date)->format('d/m/Y') }} {{ substr($session->time, 0, 5) }} - {{ $session->subject }}
                                </option>
                            @endforeach
                        </select>
                        @error('formCourseSessionId')
                            <p id="attendance-session-error" class="eus-error" role="alert">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="eus-btn eus-btn-primary">
                        Guardar asistencia
                    </button>
                </form>
            </div>
        </article>

        <article class="eus-card">
            <div class="eus-card-header">
                <h3 class="eus-card-title">Filtros</h3>
                <button type="button" class="eus-btn eus-btn-sm eus-btn-ghost" wire:click="resetFilters">
                    Limpiar
                </button>
            </div>
            <div class="eus-card-body">
                <div class="attendance-filter-grid">
                    <div>
                        <label for="attendance-search" class="eus-label">Buscar</label>
                        <input
                            id="attendance-search"
                            type="search"
                            class="eus-input"
                            wire:model.debounce.400ms="search"
                            placeholder="Nombre, email o clase"
                        >
                    </div>

                    <div>
                        <label for="attendance-date" class="eus-label">Fecha</label>
                        <input id="attendance-date" type="date" class="eus-input" wire:model="date">
                    </div>

                    <div>
                        <label for="attendance-session-filter" class="eus-label">Clase</label>
                        <select id="attendance-session-filter" class="eus-input" wire:model="sessionFilter">
                            <option value="">Todas</option>
                            @foreach($sessions as $session)
                                <option value="{{ $session->id }}">
                                    {{ Carbon::parse($session->date)->format('d/m/Y') }} - {{ $session->subject }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="attendance-student-filter" class="eus-label">Estudiante</label>
                        <select id="attendance-student-filter" class="eus-input" wire:model="studentFilter">
                            <option value="">Todos</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">
                                    {{ $student->name }} {{ $student->last_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="eus-card-footer attendance-actions-row">
                <button type="button" wire:click="downloadAttendances" class="eus-btn eus-btn-secondary">
                    Descargar Excel
                </button>
            </div>
        </article>
    </section>

    <section class="eus-card" aria-label="Historial administrativo de asistencias">
        <div class="eus-card-header">
            <h3 class="eus-card-title">Historial de asistencias</h3>
            <span class="eus-badge eus-badge-gray">{{ $attendances->total() }} registros</span>
        </div>

        @if($attendances->count())
            <div class="eus-table-wrapper">
                <table class="eus-table">
                    <thead>
                        <tr>
                            <th>Estudiante</th>
                            <th>Clase</th>
                            <th>Fecha</th>
                            <th>Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($attendances as $attendance)
                            <tr>
                                <td>
                                    <div class="student-cell">
                                        <div class="avatar-token" aria-hidden="true">
                                            {{ strtoupper(substr($attendance->user->name ?? 'E', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="font-strong text-truncate">
                                                {{ $attendance->user->name }} {{ $attendance->user->last_name }}
                                            </div>
                                            <div class="muted-small text-truncate">{{ $attendance->user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="font-strong">{{ $attendance->courseSession->subject ?? 'Clase no disponible' }}</div>
                                    <div class="muted-small">
                                        {{ optional(optional($attendance->courseSession)->student_group)->name ?? 'Sin paralelo' }}
                                    </div>
                                </td>
                                <td>
                                    @if($attendance->courseSession)
                                        {{ Carbon::parse($attendance->courseSession->date)->format('d/m/Y') }}
                                        <span class="muted-small">{{ substr($attendance->courseSession->time, 0, 5) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="muted-small">
                                    {{ $attendance->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <button type="button" wire:click="delete({{ $attendance->id }})" class="eus-btn eus-btn-sm eus-btn-danger">
                                        Borrar
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $attendances->links('components.ui.pagination', ['is_livewire' => true]) }}
        @else
            <div class="eus-empty empty-compact">
                <div class="eus-empty-title">Sin asistencias</div>
                <div class="eus-empty-text">No hay registros que coincidan con los filtros aplicados.</div>
            </div>
        @endif
    </section>
</div>
