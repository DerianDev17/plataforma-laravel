<?php

namespace App\Http\Livewire\Components\UI;

use Livewire\Component;

class Card extends Component
{
    public $titulo;
    public $imgurl;
    public $driveurl;
    public $colorurl;
    public $links = [];

    public function render()
    {   
        return view('livewire.components.u-i.card');
    }
}
