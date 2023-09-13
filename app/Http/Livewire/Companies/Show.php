<?php

namespace App\Http\Livewire\Companies;

use App\Models\Company;
use Livewire\Component;

use Livewire\WithPagination;


class Show extends Component
{
    use WithPagination;

    public $title;
    public $company_id;
    public $isOpen = 0;
    public $searchTerm;


    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';

        return view('livewire.companies.show', [
            'companies' => Company::orderBy('id', 'asc')->where('title','like', $searchTerm)->paginate(10)
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
        $this->title = '';
        $this->company_id = '';
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|unique:companies,title,' . $this->company_id,
        ]);

        $data = array(
            'title' => $this->title
        );

        $company = Company::updateOrCreate(['id' => $this->company_id], $data);
        session()->flash('message', $this->company_id ? 'Company updated successfully.' : 'Company created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $company = Company::findOrFail($id);
        $this->company_id = $id;
        $this->title = $company->title;
        $this->openModal();
    }

    public function delete($id)
    {
        $this->company_id = $id;
        Company::find($id)->delete();
        session()->flash('message', 'Company deleted successfully.');
    }
}
