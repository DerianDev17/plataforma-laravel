<?php

namespace App\Http\Livewire\UsersCrud;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Http\Livewire\Concerns\HasUserCrud;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    use AuthorizesLivewireActions;
    use HasUserCrud;

    public function mount()
    {
        $this->authorizeAbility('edit_users');
    }

    public function render()
    {
        return $this->baseRender('livewire.users-crud.show');
    }
}
