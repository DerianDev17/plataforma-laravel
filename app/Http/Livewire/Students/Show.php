<?php

namespace App\Http\Livewire\Students;

use App\Http\Controllers\Zoom\MeetingController;
use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Http\Livewire\Concerns\HasUserCrud;
use App\Models\User;
use App\Traits\ZoomJWT;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class Show extends Component
{
    use ZoomJWT;
    use WithPagination;
    use AuthorizesLivewireActions;
    use HasUserCrud;

    public $registrants_n;
    public $canceled_users_n;
    public $isloading = false;

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

        $counters = Cache::remember($this->studentCountersCacheKey(), now()->addMinutes(5), function (): array {
            $studentCounts = User::query()
                ->setEagerLoads([])
                ->whereHas('roles', function ($q) {
                    $q->where('role_id', 2);
                })
                ->selectRaw('SUM(CASE WHEN status = 1 THEN 1 ELSE 0 END) as canceled_users')
                ->selectRaw('SUM(CASE WHEN status = 1 AND join_url IS NOT NULL THEN 1 ELSE 0 END) as registrants')
                ->first();

            return [
                'canceled_users' => (int) ($studentCounts->canceled_users ?? 0),
                'registrants' => (int) ($studentCounts->registrants ?? 0),
            ];
        });

        $this->canceled_users_n = $counters['canceled_users'];
        $this->registrants_n = $counters['registrants'];
    }

    public function registerStudent($id)
    {
        $this->authorizeAbility('edit_users');

        $student = User::findOrFail($id);
        $meeting_id = $student->student_group->webinar_id;

        $path = 'webinars/' . $meeting_id . '/registrants';

        $response = $this->zoomPost($path, [
            'email' => $student->email,
            'first_name' => $student->name,
            'last_name' => $student->last_name,
        ]);

        session()->flash('message', 'response');
    }

    public function registerToWebinar()
    {
        $this->authorizeAbility('edit_users');

        $this->isloading = true;
        $to_register = User::whereHas('roles', function ($q) {
                $q->where('role_id', 2);
            })
            ->whereNull('join_url')
            ->where('status', '=', 1)
            ->where('student_group_id', '!=', 3)
            ->get();

        $failed_responses = [];
        $meet_ctrl = new MeetingController();

        foreach ($to_register as $student) {
            $resp = $meet_ctrl->registerToWebinarZoom($student);

            if ($resp->failed()) {
                if (isset($student->email)) {
                    $failed_responses[$student->email] = $resp;
                }
            } else {
                $to_approve = [
                    'id' => $resp->json()['registrant_id'],
                    'email' => $student->email,
                ];

                $resp2 = $meet_ctrl->approveStudents($student->student_group->webinar_id, [$to_approve]);

                if ($resp2->failed()) {
                    if (isset($student->email)) {
                        $failed_responses[$student->email] = $resp;
                    }
                }
            }
        }
        $this->isloading = false;
        $this->clearStudentCountersCache();
        $this->update_counters();
        session()->flash('message', 'Completado');
    }

    public function update_zoom_links()
    {
        $this->authorizeAbility('edit_users');

        $meet_ctrl = new MeetingController();
        $meet_ctrl->set_zoom_ids();
        User::students()->where('student_group_id', 3)->update(['join_url' => null]);
        $this->clearStudentCountersCache();
        $this->update_counters();
    }

    private function studentCountersCacheKey(): string
    {
        return 'students.zoom.counters';
    }

    private function clearStudentCountersCache(): void
    {
        Cache::forget($this->studentCountersCacheKey());
    }
}
