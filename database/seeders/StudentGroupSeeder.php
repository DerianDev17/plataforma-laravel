<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now();

        $groups = [
            ['id' => 1, 'name' => 'Paralelo A', 'code' => 'A', 'webinar_id' => '0'],
            ['id' => 2, 'name' => 'Paralelo B', 'code' => 'B', 'webinar_id' => '0'],
            ['id' => 3, 'name' => 'Sin paralelo', 'code' => 'Z', 'webinar_id' => '0'],
            ['id' => 4, 'name' => 'Paralelo C', 'code' => 'C', 'webinar_id' => '0'],
            ['id' => 5, 'name' => 'Paralelo D', 'code' => 'D', 'webinar_id' => '0'],
            ['id' => 999, 'name' => 'Sin paralelo', 'code' => 'Z', 'webinar_id' => '0'],
        ];

        foreach ($groups as $group) {
            DB::table('student_groups')->updateOrInsert(
                ['id' => $group['id']],
                $group + ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }
}
