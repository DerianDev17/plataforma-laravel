<?php

namespace App\Http\Livewire\Drives;

use App\Models\Drive;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use Livewire\WithPagination;


class Show extends Component
{
    use WithPagination;

    public $modulo;
    public $materia;
    public $link;
    public $course_id;
    public $drive_id;
    public $isOpen = 0;
    public $searchTerm;


    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';
        
        $drives = DB::table('drives')
            ->join('courses', 'drives.course_id', '=', 'courses.id')
            ->select('drives.*', 'courses.code')
            ->where('courses.code', 'like', '%'.$searchTerm.'%')
            ->orWhere('drives.materia', 'like', '%'.$searchTerm.'%')
            ->orWhere('drives.modulo', 'like', '%'.$searchTerm.'%')
            ->paginate(25);

        // dd();

        return view('livewire.drives.show', [
            'drives' => $drives,
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
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->modulo = '';
        $this->materia = '';
        $this->link = '';
        $this->course_id = '';
        $this->drive_id = '';
    }

    public function store()
    {
        $this->validate([
            // 'modulo' => 'required|unique:drives,modulo,' . $this->drive_id,
            'modulo' => 'required',
            'materia' => 'required',
            'link' => 'required|url',
            'course_id' => 'required|numeric',
        ]);

        $data = array(
            'modulo' => $this->modulo,
            'materia' => $this->materia,
            'link' => $this->link,
            'course_id' => $this->course_id,
        );

        $drive = Drive::updateOrCreate(['id' => $this->drive_id], $data);
        session()->flash('message', $this->drive_id ? 'Enlace actualizado correctamente.' : 'Enlace creado correctamente.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $drive = Drive::findOrFail($id);
        $this->drive_id = $id;
        $this->modulo = $drive->modulo;
        $this->materia = $drive->materia;
        $this->link = $drive->link;
        $this->course_id = $drive->course_id;

        $this->openModal();
    }

    public function delete($id)
    {
        $this->drive_id = $id;
        Drive::find($id)->delete();
        session()->flash('message', 'Enlace eliminado correctamente.');
    }
}
