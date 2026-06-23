<?php

namespace App\Http\Livewire\Sesiones;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\CourseSession;
use App\Models\StudentGroup;
use App\Models\Subject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use AuthorizesLivewireActions;
    use WithPagination;

    public $course_id;
    public $student_groups_id;
    public $date;
    public $time;
    public $subject;
    public $module_number;
    public $datetime;
    public $session_id;
    public $user;
    public $isOpen = 0;
    public $searchTerm;

    public $subjects = [];
    public $student_groups = [];

    public function mount(): void
    {
        $this->authorizeAbility('edit_users');

        $this->subjects = Subject::all();
        $this->student_groups = StudentGroup::all();
    }

    public function render()
    {
        $this->authorizeAbility('edit_users');

        $searchTerm = trim((string) $this->searchTerm);

        return view('livewire.sesiones.show', [
            'sesiones' => CourseSession::query()
                ->with('student_group:id,code,name')
                ->orderBy('date', 'asc')
                ->orderBy('time', 'asc')
                ->when($searchTerm !== '', function (Builder $query) use ($searchTerm): void {
                    $query->where(function (Builder $query) use ($searchTerm): void {
                        $likeSearch = '%' . $searchTerm . '%';

                        $query->where('date', 'like', $likeSearch)
                            ->orWhere('time', 'like', $likeSearch)
                            ->orWhere('subject', 'like', $likeSearch)
                            ->orWhere('module_number', 'like', $likeSearch)
                            ->orWhereHas('student_group', function (Builder $groupQuery) use ($likeSearch): void {
                                $groupQuery->where('code', 'like', $likeSearch)
                                    ->orWhere('name', 'like', $likeSearch);
                            });
                    });
                })
                ->paginate(20),
            'stats' => $this->sessionStats(),
        ]);
    }

    public function create(): void
    {
        $this->authorizeAbility('edit_users');

        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal(): void
    {
        $this->isOpen = true;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->dispatch('modalOpened');
    }

    public function closeModal(): void
    {
        $this->isOpen = false;
    }

    public function updatingSearchTerm(): void
    {
        $this->resetPage();
    }

    public function store(): void
    {
        $this->authorizeAbility('edit_users');

        $dateTimeParts = preg_split('/\s+/', trim((string) $this->datetime), 2);
        $this->date = $dateTimeParts[0] ?? '';
        $this->time = $dateTimeParts[1] ?? '';

        $this->validate([
            'datetime' => 'required',
            'student_groups_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'subject' => 'required',
            'module_number' => 'required',
        ]);

        CourseSession::updateOrCreate(['id' => $this->session_id], [
            'course_id' => $this->student_groups_id,
            'student_groups_id' => $this->student_groups_id,
            'date' => $this->date,
            'time' => $this->time,
            'subject' => $this->subject,
            'module_number' => $this->module_number,
        ]);

        session()->flash('message', $this->session_id ? 'Sesion actualizada correctamente.' : 'Sesion creada correctamente.');

        $this->closeModal();
        $this->resetInputFields();
        $this->resetPage();
    }

    public function edit($id): void
    {
        $this->authorizeAbility('edit_users');

        $session = CourseSession::findOrFail($id);
        $this->session_id = $id;

        $this->course_id = $session->course_id;
        $this->student_groups_id = $session->student_groups_id;
        $this->date = $session->date;
        $this->time = $session->time;
        $this->subject = $session->subject;
        $this->module_number = $session->module_number;
        $this->datetime = $this->date . ' ' . $this->time;

        $this->openModal();
    }

    public function delete($id): void
    {
        $this->authorizeAbility('edit_users');

        $this->session_id = $id;
        CourseSession::findOrFail($id)->delete();
        session()->flash('message', 'Sesion eliminada correctamente.');
    }

    private function resetInputFields(): void
    {
        $this->course_id = '';
        $this->student_groups_id = '';
        $this->date = '';
        $this->time = '';
        $this->subject = '';
        $this->module_number = '';
        $this->session_id = '';
        $this->datetime = '';
    }

    private function sessionStats(): array
    {
        $today = Carbon::today()->toDateString();

        return [
            'total' => CourseSession::count(),
            'today' => CourseSession::whereDate('date', $today)->count(),
            'upcoming' => CourseSession::whereDate('date', '>=', $today)->count(),
            'groups' => CourseSession::whereNotNull('student_groups_id')->distinct('student_groups_id')->count('student_groups_id'),
        ];
    }
}
