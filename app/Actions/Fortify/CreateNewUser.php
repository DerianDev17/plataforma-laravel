<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    private $attr_mappings = [
        'name' =>                   'Nombres',
        'lastname' =>               'Apellidos',
        'cellphone' =>              'Celular',
        'email' =>                  'Email',
        // 'fixedphone' =>          '',
        'highschool' =>             'Colegio',
        // 'especialty' =>          '',
        // 'paralelo' =>            '',
        'city' =>                   'Ciudad',
        'regimen' =>                'Régimen',
        'fecha_examen' =>           'Fecha de examen',
        'name_representant' =>      'Nombre del Representante',
        'lastname_representant' =>  'Apellidos del Representante',
        'cellphone_representant' => 'Celular del Representante',
        'cedula' =>                 'Cédula',
        'password' =>               'Contraseña',
    ];

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // dd(Rule::unique('users')->whereNull('deleted_at'));
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'cellphone' => ['required', 'string', 'digits:10',  Rule::unique('users')->whereNull('deleted_at')],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            // 'fixedphone' => ['string', 'max:255'],
            'highschool' => ['required', 'string', 'max:255'],
            // 'especialty' => ['string', 'max:255'],
            // 'paralelo' => ['string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'regimen' => ['required'],
            'fecha_examen' => ['required'],
            'password' => $this->passwordRules(),
            'name_representant' => ['required', 'string', 'max:255'],
            'lastname_representant' => ['required', 'string', 'max:255'],
            'cellphone_representant' => ['required', 'string', 'digits:10'],
            'cedula' => ['required', 'string', 'size:10'],
        ], [], $this->attr_mappings)->validate();

        $trashed_user = \App\Models\User::withTrashed()->where('email', $input['email'])->first();
        if ($trashed_user) {
            $trashed_user->forceDelete();
        }

        // dd($input['code'].$input['fixedphone']);
        $createdUser = User::create([
            'name' => $input['name'],
            'last_name' => $input['lastname'],
            'cellphone' => $input['cellphone'],
            'email' => $input['email'],
            'fixedphone' => $input['code'] . $input['fixedphone'],
            'highschool' => $input['highschool'],
            'especialty' => $input['especialty'],
            'paralelo' => $input['paralelo'],
            'city' => $input['city'],
            'name_representante' => $input['name_representant'],
            'last_name_representante' => $input['lastname_representant'],
            'cellphone_representante' => $input['cellphone_representant'],
            'status' => false,
            'password' => Hash::make($input['password']),
            'regimen' => $input['regimen'],
            'fecha_examen' => $input['fecha_examen'],
            // campo duplicado, TODO eliminar
            'exam_month' => $input['fecha_examen'],
            'cedula' => $input['cedula'],

        ]);
        //si el usario esta en el excel actualizar campo estatus
        
        $excel_user = $createdUser->findUserInExcel($createdUser);

        if ($excel_user) {
            $createdUser->status = $excel_user[15] ?? false;
            $createdUser->exam_month = $createdUser->fecha_examen = $excel_user[13] ?? '-1';
        } else {
            $createdUser->status = false;
            // $createdUser->exam_month = $createdUser->fecha_examen = '-1';
        }

        // dd($createdUser);
        $createdUser->save();

        // por defecto los usuarios se crean con rol de estudiante
        DB::table('role_user')->insert([
            'user_id' => $createdUser->id,
            'role_id' => 2,

        ]);

        return $createdUser;
    }
}
