<?php

namespace Database\Seeders;

use App\Models\CourseProgram;
use App\Models\CourseProgramTopic;
use App\Models\TopicResource;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker;

class CourseProgramsSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker\Factory::create();

        // 5 cursos
        DB::table('courses_programs')->insert([
            ['id' => 1, 'course_name' => 'Razonamiento Lógico',],
            ['id' => 2, 'course_name' => 'Razonamiento Espacial',],
            ['id' => 3, 'course_name' => 'Razonamiento Verbal',],
            ['id' => 4, 'course_name' => 'Razonamiento Numérico',],
            ['id' => 5, 'course_name' => 'Orientación Vocacional',],
        ]);

        // temas
        $cp = CourseProgram::all();
        foreach ($cp as $course) {
            $n = rand(5, 15);
            for ($i = 0; $i < $n; $i++) {
                DB::table('course_program_topics')->insert(
                    [
                        'course_program_id' => $course->id,
                        'topic_title' => $faker->sentence(4, true),
                    ],
                );
            }
        }

        // recursos
        $topics = CourseProgramTopic::all();
        foreach ($topics as $topic) {
            $n = rand(0, 3);
            for ($i = 0; $i < $n; $i++) {
                DB::table('topic_resources')->insert(
                    [
                        'topic_id' => $topic->id,
                        'resource_title' => $faker->sentence(4, true),
                    ],
                );
            }
        }

        // urls y archivos
        $resources = TopicResource::all();
        
        foreach ($resources as $resource) {
            if (rand(0, 1)) {
                DB::table('resource_files')->insert(
                    [
                        'topic_resource_id' => $resource->id,
                        'path' => $faker->word(4, true) . '.pdf',
                    ],
                );
            } else {
                DB::table('resource_urls')->insert(
                    [
                        'topic_resource_id' => $resource->id,
                        'url' => $faker->url(),
                    ],
                );
            }
        }


    }
}
