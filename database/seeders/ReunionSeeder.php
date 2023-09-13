<?php

namespace Database\Seeders;

use App\Models\Reunion;
use Illuminate\Database\Seeder;

class ReunionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $reunion1 = new Reunion();
        $reunion1->id_reunion = '98177358748';
        $reunion1->nombre = 'Febrero';
        $reunion1->save();

        $reunion2 = new Reunion();
        $reunion2->id_reunion = '96745102085';
        $reunion2->nombre = 'Junio';
        $reunion2->save();

        $reunion3 = new Reunion();
        $reunion3->id_reunion = '93175186452';
        $reunion3->nombre = 'Julio';
        $reunion3->save();

    }
}
