<?php

namespace App\Http\Livewire\ZoomConfiguration;

use App\Http\Livewire\Concerns\AuthorizesLivewireActions;
use App\Models\MeetingDetail;
use Livewire\Component;


class Show extends Component
{
    use AuthorizesLivewireActions;

    public $zoom_febrero;
    public $zoom_junio;
    public $zoom_julio;

    protected $rules = [
        'zoom_febrero' => 'required|url',
        'zoom_junio'   => 'required|url',
        'zoom_julio'   => 'required|url',
    ];

    public function saveLinks()
    {
        $this->authorizeAbility('edit_users');

        $this->validate();
        // dd($this->zoom_febrero);
        MeetingDetail::updateOrCreate([
            'course_id' => 1
        ], [
            'link' => $this->zoom_febrero,
        ]);

        MeetingDetail::updateOrCreate([
            'course_id' => 2
        ], [
            'link' => $this->zoom_junio,
        ]);

        MeetingDetail::updateOrCreate([
            'course_id' => 3
        ], [
            'link' => $this->zoom_julio,
        ]);

        $this->loadLinksData();

        session()->flash('message', 'Enlaces actualizados correctamente.');
    }

    public function loadLinksData()
    {
        $this->authorizeAbility('edit_users');

        $links = MeetingDetail::whereIn('course_id', [1, 2, 3])->pluck('link', 'course_id');

        $this->zoom_febrero = $links->get(1, '');
        $this->zoom_junio = $links->get(2, '');
        $this->zoom_julio = $links->get(3, '');
    }

    public function mount()
    {
        $this->authorizeAbility('edit_users');

        $this->loadLinksData();
    }


    public function render()
    {
        $this->authorizeAbility('edit_users');

        // $this->loadLinksData();
        return view('livewire.zoom-configuration.show');
    }
}
