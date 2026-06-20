<?php

namespace App\Http\Livewire\StudentAttendances;

use App\Exports\AsistenciasExport;
use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    use AuthorizesLivewireActions;

    public $search = '';
    public $date = '';
    public $sessionFilter = '';
    public $studentFilter = '';
    public $formUserId = '';
    public $formCourseSessionId = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'date' => ['except' => ''],
        'sessionFilter' => ['except' => ''],
        'studentFilter' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDate(): void
    {
        $this->resetPage();
    }

    public function updatingSessionFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStudentFilter(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->authorizeAbility('edit_users');

        $attendancesQuery = Attendance::query()
            ->with(['user.student_group', 'courseSession.student_group'])
            ->when(trim($this->search) !== '', function ($query): void {
                $term = '%' . trim($this->search) . '%';

                $query->where(function ($query) use ($term): void {
                    $query->whereHas('user', function ($userQuery) use ($term): void {
                        $userQuery->where('name', 'like', $term)
                            ->orWhere('last_name', 'like', $term)
                            ->orWhere('username', 'like', $term)
                            ->orWhere('email', 'like', $term);
                    })->orWhereHas('courseSession', function ($sessionQuery) use ($term): void {
                        $sessionQuery->where('subject', 'like', $term);
                    });
                });
            })
            ->when($this->date, function ($query): void {
                $query->whereHas('courseSession', function ($sessionQuery): void {
                    $sessionQuery->whereDate('date', $this->date);
                });
            })
            ->when($this->sessionFilter, function ($query): void {
                $query->where('course_session_id', $this->sessionFilter);
            })
            ->when($this->studentFilter, function ($query): void {
                $query->where('user_id', $this->studentFilter);
            })
            ->latest();

        return view('livewire.student-attendances.show', [
            'attendances' => $attendancesQuery->paginate(15),
            'students' => $this->studentsForSelect(),
            'sessions' => $this->sessionsForSelect(),
            'stats' => $this->attendanceStats(),
        ]);
    }

    public function store(): void
    {
        $this->authorizeAbility('edit_users');

        $this->validate([
            'formUserId' => 'required|integer|exists:users,id',
            'formCourseSessionId' => 'required|integer|exists:course_sessions,id',
        ], [], [
            'formUserId' => 'estudiante',
            'formCourseSessionId' => 'clase',
        ]);

        $exists = Attendance::where('user_id', $this->formUserId)
            ->where('course_session_id', $this->formCourseSessionId)
            ->exists();

        if ($exists) {
            $this->addError('formCourseSessionId', 'Este estudiante ya tiene asistencia registrada para esa clase.');
            return;
        }

        Attendance::create([
            'user_id' => $this->formUserId,
            'course_session_id' => $this->formCourseSessionId,
        ]);

        $this->clearAttendanceStatsCache();

        $this->reset(['formUserId', 'formCourseSessionId']);
        session()->flash('message', 'Asistencia registrada correctamente.');
    }

    public function delete(int $attendanceId): void
    {
        $this->authorizeAbility('edit_users');

        Attendance::findOrFail($attendanceId)->delete();
        $this->clearAttendanceStatsCache();
        session()->flash('message', 'Asistencia eliminada correctamente.');
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'date', 'sessionFilter', 'studentFilter']);
        $this->resetPage();
    }

    public function downloadAttendances()
    {
        $this->authorizeAbility('edit_users');

        $current = Carbon::now()->format('YmdHis');

        return Excel::download(new AsistenciasExport, 'asistencias-' . $current . '.xlsx');
    }

    private function studentsForSelect()
    {
        $students = User::students()
            ->with('student_group')
            ->orderBy('name')
            ->orderBy('last_name')
            ->limit(250)
            ->get();

        if ($this->formUserId && ! $students->contains('id', (int) $this->formUserId)) {
            $selectedStudent = User::with('student_group')->find($this->formUserId);

            if ($selectedStudent) {
                $students->push($selectedStudent);
            }
        }

        return $students;
    }

    private function sessionsForSelect()
    {
        $sessions = CourseSession::with('student_group')
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->limit(250)
            ->get();

        if ($this->formCourseSessionId && ! $sessions->contains('id', (int) $this->formCourseSessionId)) {
            $selectedSession = CourseSession::with('student_group')->find($this->formCourseSessionId);

            if ($selectedSession) {
                $sessions->push($selectedSession);
            }
        }

        return $sessions;
    }

    private function attendanceStats(): array
    {
        return Cache::remember($this->attendanceStatsCacheKey(), now()->addMinutes(5), function (): array {
            return [
                'total' => Attendance::count(),
                'today' => Attendance::whereDate('created_at', Carbon::today())->count(),
                'students' => Attendance::distinct('user_id')->count('user_id'),
                'sessions' => Attendance::distinct('course_session_id')->count('course_session_id'),
            ];
        });
    }

    private function attendanceStatsCacheKey(): string
    {
        return 'attendance.admin.stats.' . Carbon::today()->toDateString();
    }

    private function clearAttendanceStatsCache(): void
    {
        Cache::forget($this->attendanceStatsCacheKey());
    }
}
