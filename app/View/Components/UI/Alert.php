<?php

namespace App\View\Components\UI;

use Illuminate\View\Component;

class Alert extends Component
{


    public $message,
            $color,
            $showbutton;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($color, $message, $showbutton)
    {
           $this->color = $color;
           $this->showbutton = $showbutton;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.ui.alert');
    }
}
