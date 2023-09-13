<?php

namespace App\Http\Livewire\Components\UI\CardSingleClass;

use Livewire\Component;

class CardSingleClass extends Component
{
    public $titulo;
    public $imgurl;
    public $driveurl;
    public $colorurl;
    public $semanas;

    public function render()
    {   
        return view('livewire.components.u-i.card-single-class.card');
    }
}
