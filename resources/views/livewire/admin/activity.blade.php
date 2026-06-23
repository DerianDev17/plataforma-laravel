@php
    $statCards = [
        ['variant' => 'info', 'icon' => 'monitor', 'value' => number_format($stats['total']), 'label' => 'Eventos registrados'],
        ['variant' => 'success', 'icon' => 'calendar', 'value' => number_format($stats['today']), 'label' => 'Eventos hoy'],
        ['variant' => 'orange', 'icon' => 'users', 'value' => number_format($stats['imports']), 'label' => 'Importaciones'],
        ['variant' => 'purple', 'icon' => 'check', 'value' => number_format($stats['attendance']), 'label' => 'Asistencias auditadas'],
    ];
@endphp

<div class="dashboard-stack">
    <section class="dashboard-hero" aria-label="Resumen operativo">
        <div class="dashboard-hero-body">
            <div>
                <p class="dashboard-eyebrow">Operaciones</p>
                <h2 class="dashboard-title">Actividad del sistema</h2>
                <p class="dashboard-copy">
                    Revisa acciones sensibles, importaciones, cambios de usuarios y asistencias desde un solo historial.
                </p>
                <span class="eus-badge badge-on-dark">{{ $logs->total() }} resultados</span>
            </div>

            <div class="dashboard-mark" aria-hidden="true">
                <x-ui.icon name="monitor" :size="38" />
            </div>
        </div>
    </section>

    <section class="dashboard-grid" aria-label="Indicadores de actividad">
        @foreach($statCards as $stat)
            <x-dashboard.stat-card
                :variant="$stat['variant']"
                :icon="$stat['icon']"
                :value="$stat['value']"
                :label="$stat['label']"
            />
        @endforeach
    </section>

    <section class="eus-card" aria-label="Filtros de actividad">
        <div class="eus-card-header user-admin-card-header">
            <div>
                <h3 class="eus-card-title">Auditoria operativa</h3>
                <p class="muted-small">Filtra por accion, actor o rango de fechas. Exporta el resultado visible para soporte.</p>
            </div>
            <div class="user-admin-header-actions">
                <button type="button" wire:click="downloadCsv" class="eus-btn eus-btn-secondary eus-btn-sm">
                    <x-ui.icon name="book" :size="16" />
                    Exportar CSV
                </button>
                <button type="button" wire:click="resetFilters" class="eus-btn eus-btn-ghost eus-btn-sm">
                    Limpiar
                </button>
            </div>
        </div>

        <div class="eus-card-body user-admin-toolbar">
            <div class="user-admin-filters">
                <div>
                    <label for="activity-search" class="eus-label">Buscar</label>
                    <input
                        id="activity-search"
                        type="search"
                        class="eus-input"
                        placeholder="Accion, nombre o email"
                        wire:model.live.debounce.350ms="search"
                    >
                </div>

                <div>
                    <label for="activity-action" class="eus-label">Accion</label>
                    <select id="activity-action" class="eus-select" wire:model.live="action">
                        <option value="">Todas</option>
                        @foreach($actions as $availableAction)
                            <option value="{{ $availableAction }}">{{ $this->actionLabel($availableAction) }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="activity-actor" class="eus-label">Actor</label>
                    <select id="activity-actor" class="eus-select" wire:model.live="actorId">
                        <option value="">Todos</option>
                        @foreach($actors as $actor)
                            <option value="{{ $actor->id }}">
                                {{ trim($actor->name . ' ' . $actor->last_name) ?: $actor->username ?: $actor->email }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="activity-date-from" class="eus-label">Desde</label>
                    <input id="activity-date-from" type="date" class="eus-input" wire:model.live="dateFrom">
                </div>

                <div>
                    <label for="activity-date-to" class="eus-label">Hasta</label>
                    <input id="activity-date-to" type="date" class="eus-input" wire:model.live="dateTo">
                </div>
            </div>
        </div>

        @if($logs->count())
            <div class="eus-table-wrapper">
                <table class="eus-table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Accion</th>
                            <th>Actor</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>
                                    <div class="font-strong">{{ optional($log->created_at)->format('d/m/Y') }}</div>
                                    <div class="muted-small">{{ optional($log->created_at)->format('H:i') }}</div>
                                </td>
                                <td>
                                    <span class="eus-badge {{ $this->actionBadgeClass($log->action) }}">
                                        {{ $this->actionLabel($log->action) }}
                                    </span>
                                    <div class="muted-small">{{ $log->action }}</div>
                                </td>
                                <td>
                                    @if($log->actor)
                                        <div class="font-strong">{{ trim($log->actor->name . ' ' . $log->actor->last_name) ?: $log->actor->username }}</div>
                                        <div class="muted-small">{{ $log->actor->email }}</div>
                                    @else
                                        <span class="eus-badge eus-badge-gray">Sistema</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="muted-small">{{ $this->contextSummary($log->context) }}</div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="eus-card-footer user-admin-footer">
                {{ $logs->links('components.ui.pagination', ['is_livewire' => true]) }}
            </div>
        @else
            <div class="eus-empty empty-compact">
                <div class="eus-empty-title">Sin actividad</div>
                <div class="eus-empty-text">No hay eventos que coincidan con los filtros aplicados.</div>
                <button type="button" wire:click="resetFilters" class="eus-btn eus-btn-secondary eus-btn-sm">
                    Limpiar filtros
                </button>
            </div>
        @endif
    </section>
</div>
