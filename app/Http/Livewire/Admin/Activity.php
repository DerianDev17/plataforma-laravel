<?php

namespace App\Http\Livewire\Admin;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Activity extends Component
{
    use AuthorizesLivewireActions;
    use WithPagination;

    public string $search = '';
    public string $action = '';
    public string $actorId = '';
    public string $dateFrom = '';
    public string $dateTo = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'action' => ['except' => ''],
        'actorId' => ['except' => ''],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function mount(): void
    {
        $this->authorizeAbility('edit_users');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingAction(): void
    {
        $this->resetPage();
    }

    public function updatingActorId(): void
    {
        $this->resetPage();
    }

    public function updatingDateFrom(): void
    {
        $this->resetPage();
    }

    public function updatingDateTo(): void
    {
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'action', 'actorId', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function downloadCsv(): StreamedResponse
    {
        $this->authorizeAbility('edit_users');

        $filename = 'actividad-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function (): void {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['Fecha', 'Accion', 'Actor', 'Email actor', 'Detalle']);

            $this->activityQuery()
                ->limit(10000)
                ->get()
                ->each(function (AuditLog $log) use ($handle): void {
                    fputcsv($handle, [
                        optional($log->created_at)->format('Y-m-d H:i:s'),
                        $this->actionLabel($log->action),
                        $log->actor ? trim($log->actor->name . ' ' . $log->actor->last_name) : 'Sistema',
                        $log->actor->email ?? '',
                        $this->contextSummary($log->context),
                    ]);
                });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function render()
    {
        $this->authorizeAbility('edit_users');

        return view('livewire.admin.activity', [
            'logs' => $this->activityQuery()->paginate(15),
            'actions' => $this->availableActions(),
            'actors' => $this->availableActors(),
            'stats' => $this->activityStats(),
        ]);
    }

    public function actionLabel(string $action): string
    {
        return [
            'attendance.admin.marked' => 'Asistencia registrada por admin',
            'attendance.revoked' => 'Asistencia eliminada',
            'attendance.student.registered' => 'Asistencia registrada por estudiante',
            'student.import.batch' => 'Importacion de estudiantes',
            'user.admin.created' => 'Usuario creado por admin',
            'user.admin.deleted' => 'Usuario eliminado por admin',
            'user.admin.password_reset' => 'Clave temporal generada',
            'user.admin.payment_status_updated' => 'Estado de pago actualizado',
            'user.admin.updated' => 'Usuario actualizado por admin',
            'user.registered' => 'Usuario registrado',
            'user.status.bulk_unlocked' => 'Cuentas desbloqueadas',
            'user.registration_emails.sent' => 'Correos de cuentas enviados',
        ][$action] ?? $action;
    }

    public function actionBadgeClass(string $action): string
    {
        if (str_starts_with($action, 'attendance.')) {
            return 'eus-badge-green';
        }

        if (str_starts_with($action, 'student.import')) {
            return 'eus-badge-orange';
        }

        if (str_starts_with($action, 'user.')) {
            return 'eus-badge-blue';
        }

        return 'eus-badge-gray';
    }

    public function contextSummary(?array $context): string
    {
        if (empty($context)) {
            return '-';
        }

        return collect($context)
            ->map(fn ($value, $key): string => $key . ': ' . $this->formatContextValue($value))
            ->implode(' | ');
    }

    private function activityQuery(): Builder
    {
        $query = AuditLog::query()
            ->with('actor:id,name,last_name,email,username')
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $search = trim($this->search);

        return $query
            ->when($search !== '', function (Builder $query) use ($search): void {
                $likeSearch = '%' . $search . '%';

                $query->where(function (Builder $query) use ($likeSearch): void {
                    $query->where('action', 'like', $likeSearch)
                        ->orWhereHas('actor', function (Builder $actorQuery) use ($likeSearch): void {
                            $actorQuery->where('name', 'like', $likeSearch)
                                ->orWhere('last_name', 'like', $likeSearch)
                                ->orWhere('username', 'like', $likeSearch)
                                ->orWhere('email', 'like', $likeSearch);
                        });
                });
            })
            ->when($this->action !== '', function (Builder $query): void {
                $query->where('action', $this->action);
            })
            ->when($this->actorId !== '', function (Builder $query): void {
                $query->where('actor_id', $this->actorId);
            })
            ->when($this->isValidDate($this->dateFrom), function (Builder $query): void {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->isValidDate($this->dateTo), function (Builder $query): void {
                $query->whereDate('created_at', '<=', $this->dateTo);
            });
    }

    private function availableActions()
    {
        return AuditLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');
    }

    private function availableActors()
    {
        return User::query()
            ->whereIn('id', AuditLog::query()->whereNotNull('actor_id')->select('actor_id'))
            ->orderBy('name')
            ->orderBy('last_name')
            ->limit(100)
            ->get(['id', 'name', 'last_name', 'email', 'username']);
    }

    private function activityStats(): array
    {
        $today = Carbon::today();

        return [
            'total' => AuditLog::count(),
            'today' => AuditLog::whereDate('created_at', $today->toDateString())->count(),
            'imports' => AuditLog::where('action', 'like', 'student.import%')->count(),
            'attendance' => AuditLog::where('action', 'like', 'attendance.%')->count(),
        ];
    }

    private function formatContextValue($value): string
    {
        if (is_bool($value)) {
            return $value ? 'si' : 'no';
        }

        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '';
        }

        if ($value === null) {
            return 'null';
        }

        return (string) $value;
    }

    private function isValidDate(string $value): bool
    {
        return (bool) preg_match('/^\d{4}-\d{2}-\d{2}$/', $value);
    }
}
