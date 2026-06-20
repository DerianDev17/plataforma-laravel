<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Services\ExcelStudentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    private $attr_mappings = [
        'name' =>                   'Nombres',
        'lastname' =>               'Apellidos',
        'cellphone' =>              'Celular',
        'email' =>                  'Email',
        'highschool' =>             'Colegio',
        'city' =>                   'Ciudad',
        'regimen' =>                'Régimen',
        'fecha_examen' =>           'Fecha de examen',
        'name_representant' =>      'Nombre del Representante',
        'lastname_representant' =>  'Apellidos del Representante',
        'cellphone_representant' => 'Celular del Representante',
        'cedula' =>                 'Cédula',
        'password' =>               'Contraseña',
    ];

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'cellphone' => ['required', 'string', 'digits:10',  Rule::unique('users')->whereNull('deleted_at')],
            'email' => ['required', 'string', 'email:rfc', 'not_regex:/[\r\n]/', 'max:255', Rule::unique('users')->whereNull('deleted_at')],
            'highschool' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'regimen' => ['required'],
            'fecha_examen' => ['required'],
            'password' => $this->passwordRules(),
            'name_representant' => ['required', 'string', 'max:255'],
            'lastname_representant' => ['required', 'string', 'max:255'],
            'cellphone_representant' => ['required', 'string', 'digits:10'],
            'cedula' => ['required', 'string', 'size:10'],
            'code' => ['nullable', 'string', 'max:10'],
            'fixedphone' => ['nullable', 'string', 'max:255'],
            'especialty' => ['nullable', 'string', 'max:255'],
            'paralelo' => ['nullable', 'string', 'max:255'],
        ], [], $this->attr_mappings)->validate();

        $trashed_user = User::withTrashed()->where('email', $input['email'])->first();
        if ($trashed_user) {
            $trashed_user->restore();

            throw ValidationException::withMessages([
                'email' => 'Este correo ya pertenece a una cuenta desactivada. Contacta al administrador para reactivarla.',
            ]);
        }

        $createdUser = User::create([
            'name' => $input['name'],
            'last_name' => $input['lastname'],
            'cellphone' => $input['cellphone'],
            'email' => $input['email'],
            'fixedphone' => ($input['code'] ?? '') . ($input['fixedphone'] ?? ''),
            'highschool' => $input['highschool'],
            'especialty' => $input['especialty'] ?? '',
            'paralelo' => $input['paralelo'] ?? '',
            'city' => $input['city'],
            'name_representante' => $input['name_representant'],
            'last_name_representante' => $input['lastname_representant'],
            'cellphone_representante' => $input['cellphone_representant'],
            'status' => true,
            'password' => Hash::make($input['password']),
            'regimen' => $input['regimen'],
            'fecha_examen' => $input['fecha_examen'],
            'exam_month' => $input['fecha_examen'],
            'cedula' => $input['cedula'],
        ]);

        $excel_user = (new ExcelStudentService)->findUserInExcel($createdUser);

        if ($excel_user) {
            $createdUser->exam_month = $createdUser->fecha_examen = $excel_user[13] ?? '-1';
        }

        $createdUser->save();

        DB::table('role_user')->insert([
            'user_id' => $createdUser->id,
            'role_id' => 2,
        ]);

        return $createdUser;
    }
}
