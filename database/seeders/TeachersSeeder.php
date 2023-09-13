<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class TeachersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // docente 1
        $tmp_user = new User();
        $tmp_user->name =                      'Darío Bladimir';
        $tmp_user->email =                     'roserodario03@gmail.com';
        $tmp_user->email_verified_at =         now();
        $tmp_user->password =                  Hash::make('droserom21EUS3');
        $tmp_user->remember_token =            Str::random(10);
        $tmp_user->last_name =                 'Rosero Mancheno';
        $tmp_user->cellphone =                 '';
        $tmp_user->fixedphone =                '';
        $tmp_user->highschool =                '';
        $tmp_user->especialty =                '';
        $tmp_user->paralelo =                  '';
        $tmp_user->city =                      'Quito';
        $tmp_user->status =                    true;
        $tmp_user->name_representante =        '';
        $tmp_user->last_name_representante =   '';
        $tmp_user->cellphone_representante =   '';
        $tmp_user->regimen =                   '';
        $tmp_user->fecha_examen =              '';
        $tmp_user->exam_month =                'Febrero';
        $tmp_user->cedula =                    '';
        $tmp_user->cuestionario_resuelto =     1;
        $tmp_user->certif_intentos =           0;
        $tmp_user->username =                  'droserom21EUS3';
        $tmp_user->save();
        DB::table('role_user')->insert([
            'user_id' => $tmp_user->id,
            'role_id' => 1,
        ]);

        // docente 2
        $tmp_user = new User();
        $tmp_user->name =                      'Inés Rocío';
        $tmp_user->email =                     'gualaninesita@gmail.com';
        $tmp_user->email_verified_at =         now();
        $tmp_user->password =                  Hash::make('igualana21EUS3');
        $tmp_user->remember_token =            Str::random(10);
        $tmp_user->last_name =                 'Gualán Aguirre';
        $tmp_user->cellphone =                 '';
        $tmp_user->fixedphone =                '';
        $tmp_user->highschool =                '';
        $tmp_user->especialty =                '';
        $tmp_user->paralelo =                  '';
        $tmp_user->city =                      'Quito';
        $tmp_user->status =                    true;
        $tmp_user->name_representante =        '';
        $tmp_user->last_name_representante =   '';
        $tmp_user->cellphone_representante =   '';
        $tmp_user->regimen =                   '';
        $tmp_user->fecha_examen =              '';
        $tmp_user->exam_month =                'Febrero';
        $tmp_user->cedula =                    '';
        $tmp_user->cuestionario_resuelto =     1;
        $tmp_user->certif_intentos =           0;
        $tmp_user->username =                  'igualana21EUS3';
        $tmp_user->save();
        DB::table('role_user')->insert([
            'user_id' => $tmp_user->id,
            'role_id' => 1,
        ]);

        // docente 3
        $tmp_user = new User();
        $tmp_user->name =                      'Andrea Belén';
        $tmp_user->email =                     'andrebelen1107@gmail.com';
        $tmp_user->email_verified_at =         now();
        $tmp_user->password =                  Hash::make('aguachaminq21EUS3');
        $tmp_user->remember_token =            Str::random(10);
        $tmp_user->last_name =                 'Guachamin Quishpe';
        $tmp_user->cellphone =                 '';
        $tmp_user->fixedphone =                '';
        $tmp_user->highschool =                '';
        $tmp_user->especialty =                '';
        $tmp_user->paralelo =                  '';
        $tmp_user->city =                      'Quito';
        $tmp_user->status =                    true;
        $tmp_user->name_representante =        '';
        $tmp_user->last_name_representante =   '';
        $tmp_user->cellphone_representante =   '';
        $tmp_user->regimen =                   '';
        $tmp_user->fecha_examen =              '';
        $tmp_user->exam_month =                'Febrero';
        $tmp_user->cedula =                    '';
        $tmp_user->cuestionario_resuelto =     1;
        $tmp_user->certif_intentos =           0;
        $tmp_user->username =                  'aguachaminq21EUS3';
        $tmp_user->save();
        DB::table('role_user')->insert([
            'user_id' => $tmp_user->id,
            'role_id' => 1,
        ]);

        // docente 4
        $tmp_user = new User();
        $tmp_user->name =                      'Nathaly Camila';
        $tmp_user->email =                     'nat.reina93@gmail.com';
        $tmp_user->email_verified_at =         now();
        $tmp_user->password =                  Hash::make('nreinam21EUS3');
        $tmp_user->remember_token =            Str::random(10);
        $tmp_user->last_name =                 'Reina Mosquera';
        $tmp_user->cellphone =                 '';
        $tmp_user->fixedphone =                '';
        $tmp_user->highschool =                '';
        $tmp_user->especialty =                '';
        $tmp_user->paralelo =                  '';
        $tmp_user->city =                      'Quito';
        $tmp_user->status =                    true;
        $tmp_user->name_representante =        '';
        $tmp_user->last_name_representante =   '';
        $tmp_user->cellphone_representante =   '';
        $tmp_user->regimen =                   '';
        $tmp_user->fecha_examen =              '';
        $tmp_user->exam_month =                'Febrero';
        $tmp_user->cedula =                    '';
        $tmp_user->cuestionario_resuelto =     1;
        $tmp_user->certif_intentos =           0;
        $tmp_user->username =                  'nreinam21EUS3';
        $tmp_user->save();
        DB::table('role_user')->insert([
            'user_id' => $tmp_user->id,
            'role_id' => 1,
        ]);

        // docente 5
        $tmp_user = new User();
        $tmp_user->name =                      'Karla Marlene';
        $tmp_user->email =                     'marh.kmdt@gmail.com';
        $tmp_user->email_verified_at =         now();
        $tmp_user->password =                  Hash::make('kdiazt21EUS3');
        $tmp_user->remember_token =            Str::random(10);
        $tmp_user->last_name =                 'Diaz Tituaña';
        $tmp_user->cellphone =                 '';
        $tmp_user->fixedphone =                '';
        $tmp_user->highschool =                '';
        $tmp_user->especialty =                '';
        $tmp_user->paralelo =                  '';
        $tmp_user->city =                      'Quito';
        $tmp_user->status =                    true;
        $tmp_user->name_representante =        '';
        $tmp_user->last_name_representante =   '';
        $tmp_user->cellphone_representante =   '';
        $tmp_user->regimen =                   '';
        $tmp_user->fecha_examen =              'Febrero';
        $tmp_user->exam_month =                'Febrero';
        $tmp_user->cedula =                    '';
        $tmp_user->cuestionario_resuelto =     1;
        $tmp_user->certif_intentos =           0;
        $tmp_user->username =                  'kdiazt21EUS3';
        $tmp_user->save();
        DB::table('role_user')->insert([
            'user_id' => $tmp_user->id,
            'role_id' => 1,
        ]);
    }
}
