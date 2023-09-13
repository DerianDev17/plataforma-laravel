<?php

namespace App\Http\Livewire\Carreras;

use App\Exports\StudentsExport;
use App\Models\Career;
use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use Carbon\Carbon;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class CarrerasController extends Component
{

    use WithPagination;

    public
        $ies_nombre_institut,
        $ies_tipo_ies,
        $ies_tipo_financiamiento,
        $cam_direccion,
        $provincia_campus,
        $canton_campus,
        $car_nombre_carrera,
        $ofa_titulo,
        $modalidad,
        $jornada,
        $apc_descripcion_carrera,
        $cmc_duracion_carrera,
        $ofa_id,
        $puntaje_referencial,
        $apc_perfil_ocupacional,
        $porcentaje_beca,
        $provincia_campus_2;

    public $isOpen = 0;
    public $roles = [];
    public $from_create = null;

    public $searchTerm;
    public $searchedGrade;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';

        $data = null;
        if ($this->searchTerm == null && $this->searchedGrade == null) {
            $data = [
                'carrersForTable' => Career::orderBy('id', 'desc')
                    ->paginate(50)
            ];
        } else {
            $data = [
                'carrersForTable' => Career::orderBy('id', 'desc')
                    ->where('car_nombre_carrera', 'like', $searchTerm)
                    ->where('puntaje_referencial', 'like', '%'.$this->searchedGrade.'%')
                    // ->where('puntaje_referencial', '<', $this->searchedGrade + 50)
                    ->paginate(50)
            ];
        }

        return view('livewire.carreras.carrera', $data);
    }

    public function create()
    {
        $this->from_create = true;

        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function edit($id)
    {
        $student = User::findOrFail($id);
        $this->student_id = $id;
        $this->name = $student->name;
        $this->last_name = $student->last_name;
        $this->password = $student->password;
        $this->cedula = $student->cedula;
        $this->cellphone = $student->cellphone;
        $this->email = $student->email;
        $this->fixedphone = $student->fixedphone;
        $this->highschool = $student->highschool;
        $this->especialty = $student->especialty;
        $this->paralelo = $student->paralelo;
        $this->city = $student->city;
        $this->status = $student->status;
        $this->name_representante = $student->name_representante;
        $this->last_name_representante = $student->last_name_representante;
        $this->cellphone_representante = $student->cellphone_representante;
        $this->regimen = $student->regimen;
        // $this->fecha_examen = $student->fecha_examen;
        $this->exam_month = $student->exam_month;
        $this->openModal();
    }

    public function downloadStudents()
    {
        $current = Carbon::now()->format('YmdHs');

        return Excel::download(new StudentsExport, 'estudiantes' . $current . '.xlsx');
    }
}
