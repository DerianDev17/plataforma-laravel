<?php

namespace App\Http\Livewire\Students;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Http\Livewire\Concerns\HasUserCrud;
use App\Models\User;
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

        $baseQuery = User::students()->setEagerLoads([]);

        $this->total_students_n = (clone $baseQuery)->count();
        $this->active_students_n = (clone $baseQuery)->withLiveClassPaymentAccess()->count();
        $this->blocked_students_n = (clone $baseQuery)->where('payment_status', 'overdue')->count();
        $this->students_without_group_n = (clone $baseQuery)->whereIn('student_group_id', [3, 999])->count();
    }
}
