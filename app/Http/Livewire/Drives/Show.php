<?php

namespace App\Http\Livewire\Drives;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\Drive;
use Livewire\Component;
use Livewire\WithPagination;


class Show extends Component
{
    use WithPagination;
    use AuthorizesLivewireActions;

    public $modulo;
    public $materia;
    public $link;
    public $course_id;
    public $drive_id;
    public $isOpen = 0;
    public $searchTerm;


    public function render()
    {
        $this->authorizeAbility('crud_drives');

        $searchTerm = '%' . $this->searchTerm . '%';

        $drives = Drive::query()
            ->when($this->searchTerm, function ($q) use ($searchTerm) {
                $q->where(function ($q) use ($searchTerm) {
                    $q->where('materia', 'like', $searchTerm)
                      ->orWhere('modulo', 'like', $searchTerm)
                      ->orWhereHas('course', function ($cq) use ($searchTerm) {
                          $cq->where('code', 'like', $searchTerm);
                      });
                });
            })
            ->paginate(25);

        return view('livewire.drives.show', [
            'drives' => $drives,
        ]);
    }

    public function create()
    {
        $this->authorizeAbility('crud_drives');

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
        $this->authorizeAbility('crud_drives');

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
        $this->authorizeAbility('crud_drives');

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
        $this->authorizeAbility('crud_drives');

        $this->drive_id = $id;
        Drive::findOrFail($id)->delete();
        session()->flash('message', 'Enlace eliminado correctamente.');
    }
}
