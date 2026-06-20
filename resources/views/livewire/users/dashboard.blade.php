@php
    $activePercent = $totalStudents > 0 ? min(100, round(($activeStudents / $totalStudents) * 100)) : 0;
    $stats = [
        [
            'variant' => 'info',
            'icon' => 'users',
            'value' => number_format($totalStudents),
            'label' => 'Estudiantes totales',
            'progress' => $activePercent,
            'note' => "{$activePercent}% activos",
        ],
        [
            'variant' => 'success',
            'icon' => 'check',
            'value' => number_format($activeStudents),
            'label' => 'Estudiantes activos',
            'progress' => $activePercent,
            'note' => 'de ' . number_format($totalStudents) . ' total',
        ],
        [
            'variant' => 'purple',
            'icon' => 'calendar',
            'value' => number_format($todaySessions),
            'label' => 'Sesiones hoy',
        ],
        [
            'variant' => 'orange',
            'icon' => 'bell',
            'value' => number_format($todayAttendances),
            'label' => 'Asistencias hoy',
        ],
    ];

    $actions = [
        ['route' => 'informacion', 'icon' => 'monitor', 'label' => 'Panel informativo'],
        ['route' => 'dashboard-meetings', 'icon' => 'monitor', 'label' => 'Clases en vivo'],
        ['route' => 'recursos', 'icon' => 'video', 'label' => 'Clases grabadas'],
        ['route' => 'material_digital', 'icon' => 'book', 'label' => 'Material digital'],
    ];

    $scheduleDays = [
        ['day' => 1, 'label' => 'Lunes', 'time' => 0, 'subject' => 1],
        ['day' => 2, 'label' => 'Martes', 'time' => 0, 'subject' => 2],
        ['day' => 3, 'label' => 'Mi&eacute;rcoles', 'time' => 0, 'subject' => 3],
        ['day' => 4, 'label' => 'Jueves', 'time' => 0, 'subject' => 4],
        ['day' => 5, 'label' => 'Viernes', 'time' => 0, 'subject' => 5],
        ['day' => 6, 'label' => 'S&aacute;bado', 'time' => 6, 'subject' => 7],
    ];
@endphp

<div class="dashboard-stack">
    <section class="dashboard-hero" aria-label="Resumen Semilla Digital">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">{{ now()->isoFormat('dddd, D [de] MMMM [de] YYYY') }}</p>
                <h2 class="dashboard-title">{{ $greeting }}, {{ $userName }}</h2>
                <p class="dashboard-copy">
                    Semilla Digital concentra tus clases, recursos y seguimiento acad&eacute;mico para que cada avance sea f&aacute;cil de revisar.
                </p>
                <span class="eus-badge badge-on-dark">{{ $userRole }}</span>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="sprout" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores principales">
        @foreach($stats as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
                :progress="$stat['progress'] ?? null"
                :note="$stat['note'] ?? null"
            />
        @endforeach
    </section>

    <article class="eus-card class-schedule-card">
        <div class="eus-card-header class-schedule-header">
            <div>
                <h3 class="eus-card-title">Horario de clases</h3>
                <p class="class-schedule-subtitle">
                    {{ $studentGroupName ? $studentGroupName : 'Sin paralelo asignado' }}
                </p>
            </div>
            <div class="class-schedule-actions">
                <span class="eus-badge {{ $studentGroupCode && $studentGroupCode !== 'Z' ? 'eus-badge-orange' : 'eus-badge-gray' }}">
                    {{ $studentGroupCode && $studentGroupCode !== 'Z' ? 'Paralelo ' . $studentGroupCode : 'Pendiente' }}
                </span>
                <a href="{{ route('dashboard-meetings') }}" class="eus-btn eus-btn-sm eus-btn-secondary">Reuniones</a>
            </div>
        </div>

        @if(!empty($classSchedule))
            <div class="class-schedule-wrapper" tabindex="0" aria-label="Tabla semanal de clases">
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
                        @foreach($classSchedule as $row)
                            <tr>
                                @foreach($scheduleDays as $day)
                                    <td class="{{ $currentScheduleDay === $day['day'] ? 'is-today' : '' }}">
                                        <span class="class-time">{{ $row[$day['time']] ?? '--:--' }}</span>
                                        <span class="class-subject">{{ $row[$day['subject']] ?? 'Clase por confirmar' }}</span>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="eus-card-body">
                <div class="eus-empty empty-compact">
                    <div class="eus-empty-icon">
                        <x-ui.icon name="calendar" :size="32" />
                    </div>
                    <div class="eus-empty-title">Horario no disponible</div>
                    <div class="eus-empty-text">Asigna un paralelo al usuario para mostrar sus clases semanales.</div>
                </div>
            </div>
        @endif
    </article>

    <section class="dashboard-two-col" aria-label="Accesos y sesiones">
        <article class="eus-card">
            <div class="eus-card-header">
                <h3 class="eus-card-title">Acciones r&aacute;pidas</h3>
            </div>
            <div class="eus-card-body quick-actions-grid">
                @foreach($actions as $action)
                    <x-dashboard.action-link :href="route($action['route'])" :icon="$action['icon']">
                        {{ $action['label'] }}
                    </x-dashboard.action-link>
                @endforeach
            </div>
        </article>

        <article class="eus-card">
            <div class="eus-card-header">
                <h3 class="eus-card-title">Pr&oacute;ximas sesiones</h3>
                <span class="eus-badge eus-badge-orange">7 d&iacute;as</span>
            </div>
            <div class="eus-card-body flush-card-body">
                @if($upcomingSessions && $upcomingSessions->count())
                    <div class="session-list">
                        @foreach($upcomingSessions as $session)
                        <div class="session-list-item">
                            <div class="date-token">
                                {{ \Carbon\Carbon::parse($session->date)->format('d') }}
                            </div>
                            <div class="min-w-0">
                                <div class="session-title text-truncate">
                                    {{ $session->subject ?? 'Sesion sin titulo' }}
                                </div>
                                <div class="session-meta">
                                    {{ \Carbon\Carbon::parse($session->date)->isoFormat('ddd D MMM') }} &middot; {{ $session->time ?? '--:--' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="eus-empty empty-compact">
                        <div class="eus-empty-icon">
                            <x-ui.icon name="calendar" :size="32" />
                        </div>
                        <div class="eus-empty-title">Sin sesiones pr&oacute;ximas</div>
                        <div class="eus-empty-text">No hay sesiones programadas para los pr&oacute;ximos 7 d&iacute;as.</div>
                    </div>
                @endif
            </div>
        </article>
    </section>

    @if($recentStudents && $recentStudents->count())
    <article class="eus-card">
        <div class="eus-card-header">
            <h3 class="eus-card-title">Estudiantes recientes</h3>
        </div>
        <div class="eus-table-wrapper">
            <table class="eus-table">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Email</th>
                        <th>Estado</th>
                        <th>Registro</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentStudents as $student)
                    <tr>
                        <td>
                            <div class="student-cell">
                                <div class="avatar-token" aria-hidden="true">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <span class="font-strong">{{ $student->name }} {{ $student->last_name ?? '' }}</span>
                            </div>
                        </td>
                        <td>{{ $student->email }}</td>
                        <td>
                            @if($student->status)
                                <span class="eus-badge eus-badge-green">Activo</span>
                            @else
                                <span class="eus-badge eus-badge-gray">Inactivo</span>
                            @endif
                        </td>
                        <td class="muted-small">
                            {{ $student->created_at->isoFormat('D MMM YYYY') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
    @endif
</div>
