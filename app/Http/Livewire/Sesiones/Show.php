<?php

namespace App\Http\Livewire\Sesiones;

use App\Models\CourseSession;
use App\Models\StudentGroup;
use App\Models\Subject;
use Livewire\Component;

use Livewire\WithPagination;


class Show extends Component
{
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

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        return view('livewire.sesiones.show', [
            'sesiones' => CourseSession::orderBy('date', 'asc')
                ->where('date', 'like', $searchTerm)
                ->paginate(20),
        ]);
    }

    public function mount()
    {
        $this->subjects = Subject::all();
        $this->student_groups = StudentGroup::all();
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->resetErrorBag();
        $this->resetValidation();
        $this->emit('modalOpened');
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
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

    public function store()
    {
        $this->date = explode(' ', $this->datetime)[0];
        $this->time = explode(' ', $this->datetime)[1];

        $this->validate([
            // 'modulo' => 'required|unique:sessions,modulo,' . $this->attendance_id, // para poder actualizar
            'student_groups_id' => 'required',
            'date' => 'required',
            'time' => 'required',
            'subject' => 'required',
            'module_number' => 'required',
        ]);

        $data = array(
            'student_groups_id' => $this->student_groups_id,
            'date' => $this->date,
            'time' => $this->time,
            'subject' => $this->subject,
            'module_number' => $this->module_number,
        );

        $session = CourseSession::updateOrCreate(['id' => $this->session_id], $data);
        session()->flash('message', $this->session_id ? 'Sesión actualizada correctamente.' : 'Sesión creada correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
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

    public function delete($id)
    {
        $this->session_id = $id;
        CourseSession::find($id)->delete();
        session()->flash('message', 'Sesión eliminada correctamente.');
    }
}
