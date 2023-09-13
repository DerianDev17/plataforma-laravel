<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = new User();
        $admin->name =                      'John';
        $admin->email =                     'admin@mail.com';
        $admin->email_verified_at =         now();
        $admin->password =                  Hash::make('holahola14');
        $admin->remember_token =            Str::random(10);
        $admin->last_name =                 '';
        $admin->cellphone =                 '';
        $admin->fixedphone =                '';
        $admin->highschool =                '';
        $admin->especialty =                '';
        $admin->paralelo =                  '';
        $admin->city =                      'Quito';
        $admin->status =                    true;
        $admin->name_representante =        '';
        $admin->last_name_representante =   '';
        $admin->cellphone_representante =   '';
        $admin->regimen =   '';
        $admin->fecha_examen =   '';
        $admin->cedula =   '';

        $admin->save();

        // DB::table('users')->insert([
        //     'name' => 'root',
        //     'email' => 'admin@mail.com',
        //     'email_verified_at' => now(),
        //     'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        //     'remember_token' => Str::random(10),
        //     'last_name' => '',
        //     'cellphone' => '',
        //     'fixedphone' => '',
        //     'highschool' => '',
        //     'especialty' => '',
        //     'paralelo' => '',
        //     'city' => 'Quito',
        //     'status' => true,
        //     'name_representante' => '',
        //     'last_name_representante' => '',
        //     'cellphone_representante' => ''
        // ]); // id: 11

        // roles
        DB::table('roles')->insert([
            'name' => 'superadmin',
        ]);

        DB::table('roles')->insert([
            'name' => 'student',
        ]);

        // permisos
        DB::table('abilities')->insert([
            'name' => 'watch_lessons',
        ]);

        // asignar permisos a un rol
        DB::table('ability_role')->insert([
            'ability_id' => 1,
            'role_id' => 2,
        ]);

        // superadmin
        DB::table('role_user')->insert([
            'user_id' => $admin->id,
            'role_id' => 1,
        ]);

        // // estudiantes
        // DB::table('role_user')->insert([
        //     'user_id' => 1,
        //     'role_id' => 2,
        // ]);

        // DB::table('role_user')->insert([
        //     'user_id' => 2,
        //     'role_id' => 2,
        // ]);

        // DB::table('role_user')->insert([
        //     'user_id' => 3,
        //     'role_id' => 2,
        // ]);

        // DB::table('role_user')->insert([
        //     'user_id' => 4,
        //     'role_id' => 2,
        // ]);
    }
}
