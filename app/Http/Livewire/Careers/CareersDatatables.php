<?php

namespace App\Http\Livewire\Careers;

use App\Models\Career;
use Livewire\Component;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Mediconesystems\LivewireDatatables\NumberColumn;

class CareersDatatables extends LivewireDatatable
{
    public $model = Career::class;

    // public function render()
    // {
    //     return view('livewire.careers.careers-datatables');
    // }

    public function columns()
    {
        return [
            NumberColumn::name('id')
                ->label('ID')
                ->sortBy('id'),

            Column::name('ies_nombre_institut')
                ->label('Instituto'),

            Column::name('car_nombre_carrera')
                ->label('Instituto'),
        ];
    }
}
