<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class PermissionsSeeder extends Seeder
{
    private const LOCAL_SUPERADMIN_PASSWORD = 'holahola14';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $admin = User::firstOrNew(['email' => 'admin@mail.com']);
        $admin->name =                      'John';
        $admin->email =                     'admin@mail.com';
        $admin->username =                  'superadmin';
        $admin->email_verified_at =         now();
        if (! $admin->exists || filled(env('SEED_SUPERADMIN_PASSWORD'))) {
            $admin->password = Hash::make($this->superAdminSeedPassword());
        }
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
        DB::table('roles')->updateOrInsert(['id' => 1], [
            'name' => 'superadmin',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        DB::table('roles')->updateOrInsert(['id' => 2], [
            'name' => 'student',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        // permisos
        DB::table('abilities')->updateOrInsert(['id' => 1], [
            'name' => 'watch_lessons',
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        // asignar permisos a un rol
        DB::table('ability_role')->updateOrInsert([
            'ability_id' => 1,
            'role_id' => 2,
        ], [
            'updated_at' => now(),
            'created_at' => now(),
        ]);

        // superadmin
        DB::table('role_user')->updateOrInsert([
            'user_id' => $admin->id,
            'role_id' => 1,
        ], [
            'updated_at' => now(),
            'created_at' => now(),
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

    private function superAdminSeedPassword(): string
    {
        // Local/testing seed fallback. Set SEED_SUPERADMIN_PASSWORD outside local environments.
        return env('SEED_SUPERADMIN_PASSWORD') ?: self::LOCAL_SUPERADMIN_PASSWORD;
    }
}
