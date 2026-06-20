<?php

namespace App\Http\Livewire\LiveClassProvider;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\MeetingDetail;
use App\Services\LiveClass\StudentLiveClassAccessService;
use Livewire\Component;

class Show extends Component
{
    use AuthorizesLivewireActions;

    public $provider_label;
    public $provider_key;
    public $link_febrero;
    public $link_junio;
    public $link_julio;
    public $paid_students_n;
    public $students_with_access_n;
    public $pending_access_n;
    public $students_without_group_n;
    public $access_errors = [];
    public $isloading = false;

    protected $rules = [
        'link_febrero' => 'nullable|url',
        'link_junio' => 'nullable|url',
        'link_julio' => 'nullable|url',
    ];

    public function mount()
    {
        $this->authorizeAbility('edit_users');

        $this->provider_key = config('services.live_classes.provider', 'zoom');
        $this->provider_label = $this->liveClassAccess()->providerLabel();
        $this->loadLinksData();
        $this->updateCounters();
    }

    public function saveLinks()
    {
        $this->authorizeAbility('edit_users');

        $this->validate();

        $this->saveCourseLink(1, $this->link_febrero);
        $this->saveCourseLink(2, $this->link_junio);
        $this->saveCourseLink(3, $this->link_julio);

        $this->loadLinksData();

        session()->flash('message', 'Enlaces de clases actualizados correctamente.');
    }

    public function registerPendingStudents()
    {
        $this->authorizeAbility('edit_users');

        $this->isloading = true;

        $summary = $this->liveClassAccess()->registerPending();

        $this->isloading = false;
        $this->access_errors = $summary['errors'];
        $this->updateCounters();

        if ($summary['failed'] > 0) {
            session()->flash('error', 'Registro finalizado con ' . $summary['failed'] . ' error(es).');
            return;
        }

        session()->flash('message', 'Registro completado. Estudiantes registrados: ' . $summary['registered'] . '.');
    }

    public function syncAccessLinks()
    {
        $this->authorizeAbility('edit_users');

        $result = $this->liveClassAccess()->syncAccessLinks();

        $this->access_errors = $result->errors();
        $this->updateCounters();

        session()->flash(
            $result->hasErrors() ? 'error' : 'message',
            $result->message() . ' Accesos actualizados: ' . $result->updated() . '.'
        );
    }

    public function render()
    {
        $this->authorizeAbility('edit_users');

        return view('livewire.live-class-provider.show');
    }

    private function loadLinksData()
    {
        $links = MeetingDetail::whereIn('course_id', [1, 2, 3])->pluck('link', 'course_id');

        $this->link_febrero = $links->get(1, '');
        $this->link_junio = $links->get(2, '');
        $this->link_julio = $links->get(3, '');
    }

    private function saveCourseLink(int $courseId, ?string $link): void
    {
        MeetingDetail::updateOrCreate([
            'course_id' => $courseId,
        ], [
            'link' => $link,
        ]);
    }

    private function updateCounters(): void
    {
        $counters = $this->liveClassAccess()->counters();

        $this->paid_students_n = $counters['paid_students'];
        $this->students_with_access_n = $counters['with_access'];
        $this->pending_access_n = $counters['pending_access'];
        $this->students_without_group_n = $counters['without_group'];
    }

    private function liveClassAccess(): StudentLiveClassAccessService
    {
        return app(StudentLiveClassAccessService::class);
    }
}
