<?php

namespace App\Http\Livewire\Asistencias;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
use App\Services\Attendance\AttendanceService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public function render()
    {
        $user = auth()->user();

        return view('livewire.asistencias.show', [
            'availableSessions' => $this->availableSessions($user),
            'history' => Attendance::with(['courseSession.student_group'])
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(8),
            'stats' => $this->studentStats($user),
        ]);
    }

    public function registerAttendance(int $sessionId): void
    {
        $user = auth()->user();

        $session = $this->sessionsForUser($user)
            ->whereKey($sessionId)
            ->first();

        if (! $session) {
            session()->flash('message', 'La clase seleccionada no esta disponible para tu paralelo.');
            return;
        }

        $result = app(AttendanceService::class)->registerStudentAttendance($user, $session);

        session()->flash('message', $result->message());
    }

    public function canRegisterAttendance(CourseSession $session): bool
    {
        return app(AttendanceService::class)->canStudentRegister(auth()->user(), $session);
    }

    public function hasAttendance(CourseSession $session): bool
    {
        return $session->attendances->contains('user_id', auth()->id());
    }

    private function availableSessions(User $user)
    {
        return $this->sessionsForUser($user)
            ->with(['student_group', 'attendances' => function ($query) use ($user): void {
                $query->where('user_id', $user->id);
            }])
            ->whereBetween('date', [
                Carbon::today()->subDay()->toDateString(),
                Carbon::today()->addDays(14)->toDateString(),
            ])
            ->orderBy('date')
            ->orderBy('time')
            ->limit(18)
            ->get();
    }

    private function sessionsForUser(User $user)
    {
        $groupId = $user->student_group_id;

        return CourseSession::query()
            ->when($groupId, function ($query) use ($groupId): void {
                $query->where(function ($query) use ($groupId): void {
                    $query->where('student_groups_id', $groupId)
                        ->orWhere('student_groups_id', 999);
                });
            });
    }

    private function studentStats(User $user): array
    {
        return Cache::remember($this->studentStatsCacheKey($user), now()->addMinutes(5), function () use ($user): array {
            $now = Carbon::now();
            $today = Carbon::today();
            $baseQuery = Attendance::where('user_id', $user->id);

            return [
                'total' => (clone $baseQuery)->count(),
                'month' => (clone $baseQuery)->whereBetween('created_at', [
                    $now->copy()->startOfMonth(),
                    $now->copy()->endOfMonth(),
                ])->count(),
                'week' => (clone $baseQuery)->whereBetween('created_at', [
                    $now->copy()->startOfWeek(),
                    $now->copy()->endOfWeek(),
                ])->count(),
                'next' => $this->sessionsForUser($user)
                    ->whereBetween('date', [
                        $today->toDateString(),
                        $today->copy()->addDays(7)->toDateString(),
                    ])
                    ->count(),
            ];
        });
    }

    private function studentStatsCacheKey(User $user): string
    {
        return 'attendance.student.stats.' . $user->id . '.' . ($user->student_group_id ?: 'none') . '.' . Carbon::today()->toDateString();
    }
}
