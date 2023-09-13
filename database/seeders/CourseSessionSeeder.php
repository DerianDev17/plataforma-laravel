<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSessionSeeder extends Seeder
{
    const LENG = 'Lengua y Literatura';
    const CCNN = 'Ciencias Naturales';
    const CCSS = 'Ciencias Sociales';
    const VOCA = 'Orientación Vocacional';
    const MATH = 'Matemática';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $first_monday = Carbon::create('2021-01-04');
        $first_tuesday = Carbon::create('2021-01-05');
        $first_wednesday = Carbon::create('2021-01-06');
        $first_thursday = Carbon::create('2021-01-07');
        $first_friday = Carbon::create('2021-01-08');
        $first_saturday = Carbon::create('2021-01-09');

        // lunes
        $monday_copy = clone $first_monday;
        for ($n_module = 1; $n_module <= 5; $n_module++) {
            for ($n_week = 1; $n_week <= 4; $n_week++) {

                // ccnn
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $monday_copy,
                    'time' =>           '19:00:00',
                    'subject' =>        self::CCNN,
                    'module_number' =>  $n_module,
                ]);

                // mate
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $monday_copy,
                    'time' =>           '18:00:00',
                    'subject' =>        self::MATH,
                    'module_number' =>  $n_module,
                ]);

                // ccss
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $monday_copy,
                    'time' =>           '17:00:00',
                    'subject' =>        self::CCSS,
                    'module_number' =>  $n_module,
                ]);

                // lengua
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $monday_copy,
                    'time' =>           '16:00:00',
                    'subject' =>        self::LENG,
                    'module_number' =>  $n_module,
                ]);

                // orient
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $monday_copy,
                    'time' =>           '15:00:00',
                    'subject' =>        self::VOCA,
                    'module_number' =>  $n_module,
                ]);

                $monday_copy = $monday_copy->next();
            }
        }

        // martes
        $tuesday_copy = clone $first_tuesday;
        for ($n_module = 1; $n_module <= 5; $n_module++) {
            for ($n_week = 1; $n_week <= 4; $n_week++) {
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $tuesday_copy,
                    'time' =>           '19:00:00',
                    'subject' =>        self::CCSS,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $tuesday_copy,
                    'time' =>           '18:00:00',
                    'subject' =>        self::CCNN,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $tuesday_copy,
                    'time' =>           '17:00:00',
                    'subject' =>        self::MATH,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $tuesday_copy,
                    'time' =>           '16:00:00',
                    'subject' =>        self::VOCA,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $tuesday_copy,
                    'time' =>           '15:00:00',
                    'subject' =>        self::LENG,
                    'module_number' =>  $n_module,
                ]);

                $tuesday_copy = $tuesday_copy->next();
            }
        }

        // miercoles
        $wednesday_copy = clone $first_wednesday;
        for ($n_module = 1; $n_module <= 5; $n_module++) {
            for ($n_week = 1; $n_week <= 4; $n_week++) {
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $wednesday_copy,
                    'time' =>           '19:00:00',
                    'subject' =>        self::LENG,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $wednesday_copy,
                    'time' =>           '18:00:00',
                    'subject' =>        self::VOCA,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $wednesday_copy,
                    'time' =>           '17:00:00',
                    'subject' =>        self::CCNN,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $wednesday_copy,
                    'time' =>           '16:00:00',
                    'subject' =>        self::MATH,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $wednesday_copy,
                    'time' =>           '15:00:00',
                    'subject' =>        self::CCSS,
                    'module_number' =>  $n_module,
                ]);

                $wednesday_copy = $wednesday_copy->next();
            }
        }

        // jueves
        $thursday_copy = clone $first_thursday;
        for ($n_module = 1; $n_module <= 5; $n_module++) {
            for ($n_week = 1; $n_week <= 4; $n_week++) {
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $thursday_copy,
                    'time' =>           '19:00:00',
                    'subject' =>        self::VOCA,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $thursday_copy,
                    'time' =>           '18:00:00',
                    'subject' =>        self::CCSS,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $thursday_copy,
                    'time' =>           '17:00:00',
                    'subject' =>        self::LENG,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $thursday_copy,
                    'time' =>           '16:00:00',
                    'subject' =>        self::CCNN,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $thursday_copy,
                    'time' =>           '15:00:00',
                    'subject' =>        self::MATH,
                    'module_number' =>  $n_module,
                ]);

                $thursday_copy = $thursday_copy->next();
            }
        }

        // viernes
        $friday_copy = clone $first_friday;
        for ($n_module = 1; $n_module <= 5; $n_module++) {
            for ($n_week = 1; $n_week <= 4; $n_week++) {
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $friday_copy,
                    'time' =>           '19:00:00',
                    'subject' =>        self::MATH,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $friday_copy,
                    'time' =>           '18:00:00',
                    'subject' =>        self::LENG,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $friday_copy,
                    'time' =>           '17:00:00',
                    'subject' =>        self::VOCA,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $friday_copy,
                    'time' =>           '16:00:00',
                    'subject' =>        self::CCSS,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $friday_copy,
                    'time' =>           '15:00:00',
                    'subject' =>        self::CCNN,
                    'module_number' =>  $n_module,
                ]);

                $friday_copy = $friday_copy->next();
            }
        }

        // sabado
        $saturday_copy = clone $first_saturday;
        for ($n_module = 1; $n_module <= 5; $n_module++) {
            for ($n_week = 1; $n_week <= 4; $n_week++) {
                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $saturday_copy,
                    'time' =>           '13:00:00',
                    'subject' =>        self::CCNN,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $saturday_copy,
                    'time' =>           '12:00:00',
                    'subject' =>        self::CCSS,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $saturday_copy,
                    'time' =>           '11:00:00',
                    'subject' =>        self::LENG,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $saturday_copy,
                    'time' =>           '10:00:00',
                    'subject' =>        self::VOCA,
                    'module_number' =>  $n_module,
                ]);

                DB::table('course_sessions')->insert([
                    'course_id' =>      1,
                    'date' =>           $saturday_copy,
                    'time' =>           '09:00:00',
                    'subject' =>        self::MATH,
                    'module_number' =>  $n_module,
                ]);

                $saturday_copy = $saturday_copy->next();
            }
        }
    }
}
