@php
    $scheduleDays = [
        ['day' => 1, 'label' => 'Lunes', 'time' => 0, 'subject' => 1],
        ['day' => 2, 'label' => 'Martes', 'time' => 0, 'subject' => 2],
        ['day' => 3, 'label' => 'Mi&eacute;rcoles', 'time' => 0, 'subject' => 3],
        ['day' => 4, 'label' => 'Jueves', 'time' => 0, 'subject' => 4],
        ['day' => 5, 'label' => 'Viernes', 'time' => 0, 'subject' => 5],
        ['day' => 6, 'label' => 'S&aacute;bado', 'time' => 6, 'subject' => 7],
    ];
@endphp

<div class="dashboard-stack meetings-page">
    @if($show_alert && session()->has('error'))
        <div class="eus-alert eus-alert-danger" role="alert">
            <span>{{ session('error') }}</span>
            <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" wire:click="closeAlert" aria-label="Cerrar alerta">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if($show_alert && session()->has('ok'))
        <div class="eus-alert eus-alert-success" role="status">
            <span>{{ session('ok') }}</span>
            <button type="button" class="eus-btn eus-btn-ghost eus-btn-sm" wire:click="closeAlert" aria-label="Cerrar alerta">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <section class="dashboard-hero meetings-hero" aria-label="Resumen de reuniones">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Clases en vivo</p>
                <h2 class="dashboard-title">Reuniones y horario semanal</h2>
                <p class="dashboard-copy">
                    Revisa las clases del dia y consulta el horario por paralelo desde un solo lugar.
                </p>
                @if($studentGroupCode && $studentGroupCode !== 'Z')
                    <span class="eus-badge badge-on-dark">Paralelo {{ $studentGroupCode }}</span>
                @else
                    <span class="eus-badge badge-on-dark">Vista administrativa</span>
                @endif
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="calendar" :size="38" />
            </div>
        </div>
    </section>

    <section class="eus-card" aria-label="Sesiones de hoy">
        <div class="eus-card-header">
            <div>
                <h3 class="eus-card-title">Sesiones de hoy</h3>
                <p class="class-schedule-subtitle">
                    {{ now()->isoFormat('dddd, D [de] MMMM') }}
                </p>
            </div>
            <span class="eus-badge eus-badge-gray">{{ count($today_sessions) }}</span>
        </div>

        @if(count($today_sessions))
            <div class="meeting-session-grid">
                @foreach($today_sessions as $session)
                    <article class="meeting-session-card">
                        <div>
                            <span class="meeting-session-time">{{ $session[0] ?? '--:--' }}</span>
                            <h4>{{ $session[1] ?? 'Clase por confirmar' }}</h4>
                            <p>{{ $studentGroupName ?? 'Paralelo asignado' }}</p>
                        </div>

                        @if($session['asistio'] ?? false)
                            <span class="eus-badge eus-badge-green">Registrada</span>
                        @else
                            <button
                                type="button"
                                class="eus-btn eus-btn-primary"
                                wire:click="registAttendance(@js($session[1] ?? ''), @js($session[0] ?? ''))"
                            >
                                Registrar asistencia
                            </button>
                        @endif
                    </article>
                @endforeach
            </div>
        @else
            <div class="eus-empty empty-compact">
                <div class="eus-empty-icon">
                    <x-ui.icon name="calendar" :size="32" />
                </div>
                <div class="eus-empty-title">Sin sesiones para hoy</div>
                <div class="eus-empty-text">
                    @if($studentGroupCode && $studentGroupCode !== 'Z')
                        No hay clases registradas para tu paralelo en este dia.
                    @else
                        La vista administrativa muestra los horarios semanales por paralelo abajo.
                    @endif
                </div>
            </div>
        @endif
    </section>

    <section class="meeting-schedule-stack" aria-label="Horarios semanales por paralelo">
        @forelse($scheduleGroups as $group)
            <article class="eus-card meeting-schedule-card">
                <div class="eus-card-header class-schedule-header">
                    <div>
                        <h3 class="eus-card-title">{{ $group['name'] }}</h3>
                        <p class="class-schedule-subtitle">Horario semanal de clases en vivo</p>
                    </div>
                    <span class="eus-badge eus-badge-orange">Paralelo {{ $group['code'] }}</span>
                </div>

                <div class="class-schedule-wrapper" tabindex="0" aria-label="Horario semanal del paralelo {{ $group['code'] }}">
                    <table class="eus-table class-schedule-table">
                        <thead>
                            <tr>
                                @foreach($scheduleDays as $day)
                                    <th scope="col" class="{{ $currentScheduleDay === $day['day'] ? 'is-today' : '' }}">
                                        <span class="class-day-label">{!! $day['label'] !!}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['schedule'] as $row)
                                <tr>
                                    @foreach($scheduleDays as $day)
                                        <td class="{{ $currentScheduleDay === $day['day'] ? 'is-today' : '' }}">
                                            <span class="class-time">{{ $row[$day['time']] ?? '--:--' }}</span>
                                            @if($zoom_link)
                                                <a class="class-subject meeting-open-link" href="{{ $zoom_link }}" target="_blank" rel="noopener">
                                                    {{ $row[$day['subject']] ?? 'Clase por confirmar' }}
                                                </a>
                                            @else
                                                <span class="class-subject">{{ $row[$day['subject']] ?? 'Clase por confirmar' }}</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
        @empty
            <article class="eus-card">
                <div class="eus-empty empty-compact">
                    <div class="eus-empty-icon">
                        <x-ui.icon name="calendar" :size="32" />
                    </div>
                    <div class="eus-empty-title">Horario no disponible</div>
                    <div class="eus-empty-text">Asigna un paralelo al estudiante para mostrar sus clases.</div>
                </div>
            </article>
        @endforelse
    </section>
</div>
