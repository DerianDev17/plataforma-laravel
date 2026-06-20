@php
    use Carbon\Carbon;

    $statCards = [
        ['variant' => 'info', 'icon' => 'check', 'value' => number_format($stats['total']), 'label' => 'Asistencias totales'],
        ['variant' => 'success', 'icon' => 'calendar', 'value' => number_format($stats['month']), 'label' => 'Este mes'],
        ['variant' => 'purple', 'icon' => 'bell', 'value' => number_format($stats['week']), 'label' => 'Esta semana'],
        ['variant' => 'orange', 'icon' => 'book', 'value' => number_format($stats['next']), 'label' => 'Clases 7 dias'],
    ];
@endphp

<div class="dashboard-stack">
    <x-ui.alert />

    <section class="dashboard-hero attendance-hero" aria-label="Resumen de mis asistencias">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Seguimiento estudiantil</p>
                <h2 class="dashboard-title">Mis asistencias</h2>
                <p class="dashboard-copy">
                    Registra tu asistencia cuando la clase este habilitada y revisa tu historial academico.
                </p>
            </div>
            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="sprout" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores personales de asistencia">
        @foreach($statCards as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="eus-card" aria-label="Clases disponibles para asistencia">
        <div class="eus-card-header">
            <h3 class="eus-card-title">Clases disponibles</h3>
            <span class="eus-badge eus-badge-gray">{{ $availableSessions->count() }}</span>
        </div>

        @if($availableSessions->count())
            <div class="attendance-session-grid">
                @foreach($availableSessions as $session)
                    @php
                        $registered = $this->hasAttendance($session);
                        $enabled = $this->canRegisterAttendance($session);
                    @endphp

                    <article class="attendance-session-card">
                        <div>
                            <div class="attendance-session-date">
                                {{ Carbon::parse($session->date)->isoFormat('ddd D MMM') }}
                                <span>{{ substr($session->time, 0, 5) }}</span>
                            </div>
                            <h4>{{ $session->subject }}</h4>
                            <p>{{ optional($session->student_group)->name ?? 'Sin paralelo' }}</p>
                        </div>

                        @if($registered)
                            <span class="eus-badge eus-badge-green">Registrada</span>
                        @elseif($enabled)
                            <button type="button" class="eus-btn eus-btn-primary" wire:click="registerAttendance({{ $session->id }})">
                                Registrar asistencia
                            </button>
                        @else
                            <span class="eus-badge eus-badge-gray">Fuera de horario</span>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div class="eus-empty empty-compact">
                <div class="eus-empty-title">Sin clases disponibles</div>
                <div class="eus-empty-text">No hay clases cercanas para tu paralelo.</div>
            </div>
        @endif
    </section>

    <section class="eus-card" aria-label="Historial de mis asistencias">
        <div class="eus-card-header">
            <h3 class="eus-card-title">Historial</h3>
            <span class="eus-badge eus-badge-gray">{{ $history->total() }} registros</span>
        </div>

        @if($history->count())
            <div class="eus-table-wrapper">
                <table class="eus-table">
                    <thead>
                        <tr>
                            <th>Clase</th>
                            <th>Fecha de clase</th>
                            <th>Registrada</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($history as $attendance)
                            <tr>
                                <td>
                                    <div class="font-strong">{{ $attendance->courseSession->subject ?? 'Clase no disponible' }}</div>
                                    <div class="muted-small">{{ optional(optional($attendance->courseSession)->student_group)->name ?? 'Sin paralelo' }}</div>
                                </td>
                                <td>
                                    @if($attendance->courseSession)
                                        {{ Carbon::parse($attendance->courseSession->date)->format('d/m/Y') }}
                                        <span class="muted-small">{{ substr($attendance->courseSession->time, 0, 5) }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="muted-small">{{ $attendance->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $history->links('components.ui.pagination', ['is_livewire' => true]) }}
        @else
            <div class="eus-empty empty-compact">
                <div class="eus-empty-title">Aun no registras asistencias</div>
                <div class="eus-empty-text">Cuando registres una clase, aparecera en este historial.</div>
            </div>
        @endif
    </section>
</div>
