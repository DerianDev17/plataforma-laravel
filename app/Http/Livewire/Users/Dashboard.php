<?php

namespace App\Http\Livewire\Users;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Dashboard extends Component
{
    public $totalStudents;
    public $activeStudents;
    public $todaySessions;
    public $todayAttendances;
    public $upcomingSessions;
    public $recentStudents;
    public $userName;
    public $userRole;
    public $greeting;
    public $classSchedule = [];
    public $studentGroupName;
    public $studentGroupCode;
    public $currentScheduleDay;

    public function mount()
    {
        $user = auth()->user();
        $user->loadMissing('student_group');

        $this->userName = $user->name;
        $this->userRole = $user->roles->first()?->name ?? 'Usuario';
        $this->studentGroupName = $user->student_group->name ?? null;
        $this->studentGroupCode = $user->student_group->code ?? null;
        $this->currentScheduleDay = (int) now()->dayOfWeekIso;
        $this->classSchedule = $this->loadClassSchedule($user);

        $hour = (int) now()->format('H');
        $this->greeting = match (true) {
            $hour < 12 => 'Buenos dias',
            $hour < 18 => 'Buenas tardes',
            default => 'Buenas noches',
        };

        $today = Carbon::today();
        $todayDate = $today->toDateString();

        $stats = Cache::remember('dashboard.stats.' . $todayDate, now()->addMinutes(5), function () use ($today, $todayDate) {
            $studentCounts = User::query()
                ->setEagerLoads([])
                ->students()
                ->selectRaw('COUNT(*) as total_students, SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as active_students')
                ->first();

            return [
                'totalStudents' => (int) ($studentCounts->total_students ?? 0),
                'activeStudents' => (int) ($studentCounts->active_students ?? 0),
                'todaySessions' => CourseSession::where('date', $todayDate)->count(),
                'todayAttendances' => Attendance::whereBetween('created_at', [
                    $today->copy()->startOfDay(),
                    $today->copy()->endOfDay(),
                ])->count(),
            ];
        });

        $this->totalStudents = $stats['totalStudents'];
        $this->activeStudents = $stats['activeStudents'];
        $this->todaySessions = $stats['todaySessions'];
        $this->todayAttendances = $stats['todayAttendances'];

        $this->upcomingSessions = Cache::remember('dashboard.upcoming_sessions.' . $todayDate, now()->addMinutes(5), function () use ($todayDate) {
            return CourseSession::query()
                ->select(['id', 'date', 'time', 'subject'])
                ->whereBetween('date', [$todayDate, Carbon::today()->addDays(7)->toDateString()])
                ->orderBy('date')
                ->orderBy('time')
                ->take(6)
                ->get();
        });

        $this->recentStudents = Cache::remember('dashboard.recent_students', now()->addMinutes(5), function () {
            return User::query()
                ->setEagerLoads([])
                ->select(['id', 'name', 'last_name', 'email', 'status', 'created_at'])
                ->whereHas('roles', fn ($q) => $q->where('name', 'student'))
                ->latest()
                ->take(5)
                ->get();
        });
    }

    private function loadClassSchedule(User $user): array
    {
        $groupCode = $user->student_group->code ?? null;

        if (!$groupCode || $groupCode === 'Z') {
            return [];
        }

        return Cache::remember('dashboard.class_schedule.' . $groupCode, now()->addHours(6), function () use ($user) {
            return $user->horario();
        });
    }

    public function render()
    {
        return view('livewire.users.dashboard')->layout('layouts.admin', ['header' => 'Dashboard']);
    }
}
