<?php

namespace App\Http\Livewire\StudentAttendances;

use App\Exports\AsistenciasExport;
use App\Models\Attendance;
use Livewire\Component;

use Livewire\WithPagination;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

use function Psy\debug;

class Show extends Component
{
    use WithPagination;

    public $course_session_id;
    public $user_id;
    public $attendance_id;
    public $user;
    public $isOpen = 0;
    public $searchTerm;

    protected $listeners = [
        // 'studentSelected' => 'setIdStudent',
        // 'sessionSelected' => 'setIdSession',
    ];

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';

        return view('livewire.student-attendances.show', [
            'attendances' => Attendance::orderBy('id', 'asc')->where('id', 'like', $searchTerm)->paginate(20)
        ]);
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
        $this->course_session_id = '';
        $this->user_id = '';
        $this->attendance_id = '';
    }

    function checkCompositeUnique($user_id, $course_session_id)
    {
        return Attendance::where('user_id', $user_id)
            ->where('course_session_id', $course_session_id)
            ->first();
    }

    public function store()
    {
        $user_id = $this->user_id;
        $course_session_id = $this->course_session_id;

        $this->validate([
            // 'modulo' => 'required|unique:attendances,modulo,' . $this->attendance_id,
            'course_session_id' => [
                'required',
                function ($attribute, $value, $fail) use ($user_id, $course_session_id) {
                    // dd(!!$this->checkCompositeUnique($user_id, $course_session_id));
                    if (!!$this->checkCompositeUnique($user_id, $course_session_id)) {
                        $fail('El usuario ya registra una asistencia para esa clase.');
                    };
                }
            ],
            'user_id' => 'required',
        ]);

        // dd($this->user_id, $this->course_session_id);
        $data = array(
            'course_session_id' => $this->course_session_id,
            'user_id' => $this->user_id,
        );

        $attendance = Attendance::updateOrCreate(['id' => $this->attendance_id], $data);
        session()->flash('message', $this->attendance_id ? 'Asistencia actualizado correctamente.' : 'Asistencia creado correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $this->attendance_id = $id;
        $this->course_session_id = $attendance->course_session_id;
        $this->user_id = $attendance->user_id;

        $this->openModal();
    }

    public function delete($id)
    {
        $this->attendance_id = $id;
        Attendance::find($id)->delete();
        session()->flash('message', 'Enlace eliminado correctamente.');
    }

    public function downloadAttendances()
    {
        $current = Carbon::now()->format('YmdHs');

        return Excel::download(new AsistenciasExport, 'asistencias' . $current . '.xlsx');
    }
    // public function setIdStudent($id_student){
    //     $this->user_id = $id_student;
    // }

    // public function setSessionStudent($course_session_id){
    //     $this->course_session_id = $course_session_id;
    // }
}
