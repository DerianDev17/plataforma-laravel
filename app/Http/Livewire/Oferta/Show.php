<?php

namespace App\Http\Livewire\Oferta;

use App\Models\SenecytCarrer;
use Livewire\Component;

use Livewire\WithPagination;


class Show extends Component
{
    use WithPagination;

    public $title;
    public $company_id;
    public $isOpen = 0;
    public $searchTerm;
    public $scoreSearchTerm;


    public function render()
    {
        $searchTerm = '%' . $this->searchTerm . '%';

        // dd($this->scoreSearchTerm);
        // var_dump(puntaje_referencial);
        // dd(SenecytCarrer::find(1));

        $query =  SenecytCarrer::orderBy('puntaje_referencial', 'desc');
        
        if ($this->scoreSearchTerm) {
            $query->where('puntaje_referencial', '<=', intval($this->scoreSearchTerm))
            // ->where('puntaje_referencial', '<=', intval($this->scoreSearchTerm))
            ;
        }
        $query->where(function ($query) use ($searchTerm) {
            $query->where('institucion', 'like', $searchTerm)
                  ->orWhere('carrera', 'like', $searchTerm);
        });
        
        return view('livewire.oferta.show', [
            'ofertas' => $query->paginate(10),
        ]);
    }
}
