<?php

namespace App\Http\Livewire\Asistencias;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
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

        if (! $this->canRegisterAttendance($session)) {
            session()->flash('message', 'La asistencia solo puede registrarse durante la ventana habilitada para la clase.');
            return;
        }

        $attendance = Attendance::firstOrCreate([
            'user_id' => $user->id,
            'course_session_id' => $session->id,
        ]);

        if ($attendance->wasRecentlyCreated) {
            Cache::forget($this->studentStatsCacheKey($user));
        }

        session()->flash(
            'message',
            $attendance->wasRecentlyCreated
                ? 'Asistencia registrada correctamente.'
                : 'Ya registraste asistencia para esta clase.'
        );
    }

    public function canRegisterAttendance(CourseSession $session): bool
    {
        $start = Carbon::parse($session->date . ' ' . $session->time);
        $now = Carbon::now();

        return $now->between(
            $start->copy()->subMinutes(15),
            $start->copy()->addMinutes(90)
        );
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
            ->whereDate('date', '>=', Carbon::today()->subDay())
            ->whereDate('date', '<=', Carbon::today()->addDays(14))
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
            $baseQuery = Attendance::where('user_id', $user->id);

            return [
                'total' => (clone $baseQuery)->count(),
                'month' => (clone $baseQuery)
                    ->whereYear('created_at', Carbon::now()->year)
                    ->whereMonth('created_at', Carbon::now()->month)
                    ->count(),
                'week' => (clone $baseQuery)->whereBetween('created_at', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek(),
                ])->count(),
                'next' => $this->sessionsForUser($user)
                    ->whereDate('date', '>=', Carbon::today())
                    ->whereDate('date', '<=', Carbon::today()->addDays(7))
                    ->count(),
            ];
        });
    }

    private function studentStatsCacheKey(User $user): string
    {
        return 'attendance.student.stats.' . $user->id . '.' . ($user->student_group_id ?: 'none') . '.' . Carbon::today()->toDateString();
    }
}
