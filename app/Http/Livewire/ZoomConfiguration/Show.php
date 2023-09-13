<?php

namespace App\Http\Livewire\ZoomConfiguration;

use App\Models\MeetingDetail;
use Livewire\Component;


class Show extends Component
{
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
        $this->zoom_febrero = MeetingDetail::where('course_id', 1)->first()->link;
        $this->zoom_junio = MeetingDetail::where('course_id', 2)->first()->link;
        $this->zoom_julio = MeetingDetail::where('course_id', 3)->first()->link;
    }

    public function mount()
    {
        $this->loadLinksData();
    }


    public function render()
    {
        // $this->loadLinksData();
        return view('livewire.zoom-configuration.show');
    }
}
