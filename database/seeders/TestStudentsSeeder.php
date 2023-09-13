<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class TestStudentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // estudiantes de prueba de prueba
        // $user1 = new User;
        // $user1->name =                      'John';
        // $user1->email =                     'bp517638@gmail.com';
        // $user1->email_verified_at =         now();
        // $user1->password =                  Hash::make('Maria123');
        // $user1->remember_token =            Str::random(10);
        // $user1->last_name =                 '';
        // $user1->cellphone =                 '';
        // $user1->fixedphone =                '';
        // $user1->highschool =                '';
        // $user1->especialty =                '';
        // $user1->paralelo =                  '';
        // $user1->city =                      'Quito';
        // $user1->status =                    true;
        // $user1->name_representante =        '';
        // $user1->last_name_representante =   '';
        // $user1->cellphone_representante =   '';
        // $user1->regimen =                   '';
        // $user1->fecha_examen =              '';
        // $user1->cedula =                    '';
        // $user1->save();

        $user2 = new User;
        $user2->name =                      'Mary';
        $user2->email =                     'desadeve@gmail.com';
        $user2->email_verified_at =         now();
        $user2->password =                  Hash::make('Maria123');
        $user2->remember_token =            Str::random(10);
        $user2->last_name =                 'Mary';
        $user2->cellphone =                 '';
        $user2->fixedphone =                '';
        $user2->highschool =                '';
        $user2->especialty =                '';
        $user2->paralelo =                  '';
        $user2->city =                      'Quito';
        $user2->status =                    true;
        $user2->name_representante =        '';
        $user2->last_name_representante =   '';
        $user2->cellphone_representante =   '';
        $user2->regimen =                   '';
        $user2->fecha_examen =              '';
        $user2->cedula =                    '';
        $user2->save();

        $user3 = new User;
        $user3->name =                      'David';
        $user3->email =                     'david36mtl@hotmail.com';
        $user3->email_verified_at =         now();
        $user3->password =                  Hash::make('Maria123');
        $user3->remember_token =            Str::random(10);
        $user3->last_name =                 'David';
        $user3->cellphone =                 '';
        $user3->fixedphone =                '';
        $user3->highschool =                '';
        $user3->especialty =                '';
        $user3->paralelo =                  '';
        $user3->city =                      'Quito';
        $user3->status =                    true;
        $user3->name_representante =        '';
        $user3->last_name_representante =   '';
        $user3->cellphone_representante =   '';
        $user3->regimen =                   '';
        $user3->fecha_examen =              '';
        $user3->cedula =                    '';
        $user3->save();

        // $user4 = new User;
        // $user4->name =                      'Bryan';
        // $user4->email =                     'bryan36mtl@gmail.com';
        // $user4->email_verified_at =         now();
        // $user4->password =                  Hash::make('Maria123');
        // $user4->remember_token =            Str::random(10);
        // $user4->last_name =                 'Bryan';
        // $user4->cellphone =                 '';
        // $user4->fixedphone =                '';
        // $user4->highschool =                '';
        // $user4->especialty =                '';
        // $user4->paralelo =                  '';
        // $user4->city =                      'Quito';
        // $user4->status =                    true;
        // $user4->name_representante =        '';
        // $user4->last_name_representante =   '';
        // $user4->cellphone_representante =   '';
        // $user4->regimen =                   '';
        // $user4->fecha_examen =              '';
        // $user4->cedula =                    '';
        // $user4->save();
    }
}
