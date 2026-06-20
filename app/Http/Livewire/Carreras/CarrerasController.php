<?php

namespace App\Http\Livewire\Carreras;

use App\Models\Career;
use Livewire\Component;
use Livewire\WithPagination;

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
    public $career_id;
    public $searchTerm;
    public $searchedGrade;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';

        $query = Career::orderBy('id', 'desc');

        if ($this->searchTerm) {
            $query->where('car_nombre_carrera', 'like', $searchTerm);
        }

        if ($this->searchedGrade) {
            $query->where('puntaje_referencial', 'like', '%' . $this->searchedGrade . '%');
        }

        return view('livewire.carreras.carrera', [
            'carrersForTable' => $query->paginate(50)
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
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function edit($id)
    {
        $career = Career::findOrFail($id);
        $this->career_id = $id;
        $this->ies_nombre_institut = $career->ies_nombre_institut;
        $this->ies_tipo_ies = $career->ies_tipo_ies;
        $this->ies_tipo_financiamiento = $career->ies_tipo_financiamiento;
        $this->cam_direccion = $career->cam_direccion;
        $this->provincia_campus = $career->provincia_campus;
        $this->canton_campus = $career->canton_campus;
        $this->car_nombre_carrera = $career->car_nombre_carrera;
        $this->ofa_titulo = $career->ofa_titulo;
        $this->modalidad = $career->modalidad;
        $this->jornada = $career->jornada;
        $this->apc_descripcion_carrera = $career->apc_descripcion_carrera;
        $this->cmc_duracion_carrera = $career->cmc_duracion_carrera;
        $this->ofa_id = $career->ofa_id;
        $this->puntaje_referencial = $career->puntaje_referencial;
        $this->apc_perfil_ocupacional = $career->apc_perfil_ocupacional;
        $this->porcentaje_beca = $career->porcentaje_beca;
        $this->provincia_campus_2 = $career->provincia_campus_2;
        $this->openModal();
    }

    private function resetInputFields()
    {
        $this->ies_nombre_institut = '';
        $this->ies_tipo_ies = '';
        $this->ies_tipo_financiamiento = '';
        $this->cam_direccion = '';
        $this->provincia_campus = '';
        $this->canton_campus = '';
        $this->car_nombre_carrera = '';
        $this->ofa_titulo = '';
        $this->modalidad = '';
        $this->jornada = '';
        $this->apc_descripcion_carrera = '';
        $this->cmc_duracion_carrera = '';
        $this->ofa_id = '';
        $this->puntaje_referencial = '';
        $this->apc_perfil_ocupacional = '';
        $this->porcentaje_beca = '';
        $this->provincia_campus_2 = '';
        $this->career_id = '';
    }
}
