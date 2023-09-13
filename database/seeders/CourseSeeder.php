<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('courses')->insert([
            'name' => 'Preuniversitario Región Costa',
            'code' => 'pre_febrero',
        ]);

        DB::table('courses')->insert([
            'name' => 'Preuniversitario Región Sierra 1',
            'code' => 'pre_junio',
        ]);

        DB::table('courses')->insert([
            'name' => 'Preuniversitario Región Sierra 2',
            'code' => 'pre_julio',
        ]);
    }
}
