<?php

namespace App\Http\Livewire;

use App\Models\Contact as ContactModel;
use Illuminate\View\View;
use Livewire\Component;

class Contact extends Component
{
    public $data;
    public $name;
    public $email;
    public $selected_id;
    public $updateMode = false;

    public function render(): View
    {
        $this->data = ContactModel::orderBy('name')->get();

        return view('livewire.contact');
    }

    public function store(): void
    {
        $this->validate($this->rules());

        ContactModel::create([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $contact = ContactModel::findOrFail($id);

        $this->selected_id = $contact->id;
        $this->name = $contact->name;
        $this->email = $contact->email;
        $this->updateMode = true;
    }

    public function update(): void
    {
        $this->validate($this->rules(requireSelectedId: true));

        ContactModel::findOrFail($this->selected_id)->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        $this->resetForm();
    }

    public function cancel(): void
    {
        $this->resetForm();
    }

    public function destroy(int $id): void
    {
        ContactModel::whereKey($id)->delete();
    }

    public function rules(bool $requireSelectedId = false): array
    {
        $rules = [
            'name' => 'required|min:5',
            'email' => 'required|email:rfc,dns|not_regex:/[\r\n]/',
        ];

        if ($requireSelectedId) {
            $rules['selected_id'] = 'required|numeric';
        }

        return $rules;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'selected_id']);
        $this->updateMode = false;
    }
}
