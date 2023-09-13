<?php

namespace App\Http\Livewire\Users;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;

    public $searchTerm;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%'.$this->searchTerm.'%';

        return view('livewire.users.show', [
            'users' => User::where('email', 'like', $searchTerm)->paginate(150),
        ]);
    }
}
