<?php

namespace App\Http\Livewire\CGrabadas;

use App\Models\Company;
use Livewire\Component;

use Livewire\WithPagination;
use App\Models\RecordedClass;

class ClasesGrabadas extends Component
{
    use WithPagination;

    public $clase_id;
    public $module_name;
    public $group;
    public $lenguaje_link;
    public $math_link;
    public $science_link;
    public $social_link;
    public $orientation_link;

    public $isOpen = 0;
    public $searchTerm;


    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';

        return view('livewire.cgrabadas.clases', [
            'clases' => RecordedClass::orderBy('id', 'asc')->where('group', 'like', $searchTerm)->paginate(10)
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
        $this->clase_id = '';
        $this->module_name = '';
        $this->group = '';
        $this->lenguaje_link = '';
        $this->math_link = '';
        $this->science_link = '';
        $this->social_link = '';
        $this->orientation_link = '';
    }

    public function store()
    {
        $this->validate([
            'module_name' => 'required',
            'group' => 'required',
        ]);

       
        $data = array(
            'module_number' => $this->module_name,
            'group' => $this->group,
            'lenguaje_link' => $this->lenguaje_link,
            'math_link' => $this->math_link,
            'science_link' => $this->science_link,
            'social_link' => $this->social_link,
            'orientation_link' => $this->orientation_link,
        );

        RecordedClass::updateOrCreate(['id' => $this->clase_id], $data);
        session()->flash('message', $this->clase_id ? 'Company updated successfully.' : 'Company created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $clases = RecordedClass::findOrFail($id);
        $this->clase_id = $clases->id;
        $this->module_name = $clases->module_number;
        $this->group = $clases->group;
        $this->lenguaje_link = $clases->lenguaje_link;
        $this->math_link = $clases->math_link;
        $this->science_link = $clases->science_link;
        $this->social_link = $clases->social_link;
        $this->orientation_link = $clases->orientation_link;
        $this->openModal();
    }

    public function delete($id)
    {
        $this->clase_id = $id;
        RecordedClass::find($id)->delete();
        session()->flash('message', 'Company deleted successfully.');
    }
}
