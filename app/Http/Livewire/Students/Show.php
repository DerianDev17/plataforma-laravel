<?php

namespace App\Http\Livewire\Students;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Http\Livewire\Concerns\HasUserCrud;
use App\Services\Students\StudentDashboardCounterService;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use WithPagination;
    use AuthorizesLivewireActions;
    use HasUserCrud;

    public $total_students_n;
    public $active_students_n;
    public $blocked_students_n;
    public $students_without_group_n;

    public function mount()
    {
        $this->authorizeAbility('edit_users');
        $this->update_counters();
    }

    public function render()
    {
        return $this->baseRender('livewire.students.show');
    }

    public function update_counters()
    {
        $this->authorizeAbility('edit_users');

        $counters = app(StudentDashboardCounterService::class)->counters();

        $this->total_students_n = $counters['total_students_n'];
        $this->active_students_n = $counters['active_students_n'];
        $this->blocked_students_n = $counters['blocked_students_n'];
        $this->students_without_group_n = $counters['students_without_group_n'];
    }
}
