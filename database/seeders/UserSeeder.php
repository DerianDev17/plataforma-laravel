<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // User::factory()
        //     ->times(50)
        //     ->create();
        DB::table('users')->insert([
            'name' => 'Maria',
            'email' => 'maria@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'last_name' => '',
            'cellphone' => '',
            'fixedphone' =>'',
            'highschool' => '',
            'especialty' => '',
            'paralelo' => '',
            'city' => 'Quito',
            'status' => true,
            'name_representante'=> '',
            'last_name_representante'=> '',
            'cellphone_representante' => '',
            'cedula' => ''


        ]); // id: 11

        DB::table('users')->insert([
            'name' => 'Rodrigo',
            'email' => 'rodrigo@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'last_name' => '',
            'cellphone' => '',
            'fixedphone' =>'',
            'highschool' => '',
            'especialty' => '',
            'paralelo' => '',
            'city' => 'Quito',
            'status' => true,
            'name_representante'=> '',
            'last_name_representante'=> '',
            'cellphone_representante' => '',
            'cedula' => ''


        ]); // id: 11

        DB::table('users')->insert([
            'name' => 'Mike',
            'email' => 'mike@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'last_name' => '',
            'cellphone' => '',
            'fixedphone' =>'',
            'highschool' => '',
            'especialty' => '',
            'paralelo' => '',
            'city' => 'Quito',
            'status' => true,
            'name_representante'=> '',
            'last_name_representante'=> '',
            'cellphone_representante' => '',
            'cedula' => ''


        ]); // id: 11

        DB::table('users')->insert([
            'name' => 'Paul',
            'email' => 'paul@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'last_name' => '',
            'cellphone' => '',
            'fixedphone' =>'',
            'highschool' => '',
            'especialty' => '',
            'paralelo' => '',
            'city' => 'Quito',
            'status' => true,
            'name_representante'=> '',
            'last_name_representante'=> '',
            'cellphone_representante' => '',
            'regimen' => '',
            'fecha_examen' => '',
            'cedula' => ''


        ]); // id: 11

        DB::table('users')->insert([
            'name' => 'Michelle',
            'email' => 'michelle@mail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
            'last_name' => '',
            'cellphone' => '',
            'fixedphone' =>'',
            'highschool' => '',
            'especialty' => '',
            'paralelo' => '',
            'city' => 'Quito',
            'status' => true,
            'name_representante'=> '',
            'last_name_representante'=> '',
            'cellphone_representante' => '',
            'regimen' => '',
            'fecha_examen' => '',
            'cedula' => ''


        ]); // id: 11
    }
}
