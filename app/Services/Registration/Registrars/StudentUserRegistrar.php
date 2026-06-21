<?php

namespace App\Services\Registration\Registrars;

use App\Actions\Fortify\PasswordValidationRules;
use App\Models\User;
use App\Services\Registration\Contracts\UserRegistrar;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class StudentUserRegistrar implements UserRegistrar
{
    use PasswordValidationRules;

    public const STUDENT_ROLE_ID = 2;

    private array $attributeMappings = [
        'name' => 'Nombres',
        'lastname' => 'Apellidos',
        'cellphone' => 'Celular',
        'email' => 'Email',
        'highschool' => 'Colegio',
        'city' => 'Ciudad',
        'regimen' => 'Régimen',
        'fecha_examen' => 'Fecha de examen',
        'name_representant' => 'Nombre del Representante',
        'lastname_representant' => 'Apellidos del Representante',
        'cellphone_representant' => 'Celular del Representante',
        'cedula' => 'Cédula',
        'password' => 'Contraseña',
    ];

    public function register(array $data): User
    {
        Validator::make($data, $this->rules(), [], $this->attributeMappings)->validate();

        $trashedUser = User::withTrashed()->where('email', $data['email'])->first();
        if ($trashedUser) {
            $trashedUser->restore();

            throw ValidationException::withMessages([
                'email' => 'Este correo ya pertenece a una cuenta desactivada. Contacta al administrador para reactivarla.',
            ]);
        }

        $user = User::create([
            'name' => $data['name'],
            'last_name' => $data['lastname'],
            'cellphone' => $data['cellphone'],
            'email' => $data['email'],
            'username' => $this->generateUniqueUsername($data['name'], $data['lastname']),
            'fixedphone' => ($data['code'] ?? '') . ($data['fixedphone'] ?? ''),
            'highschool' => $data['highschool'],
            'especialty' => $data['especialty'] ?? '',
            'paralelo' => $data['paralelo'] ?? '',
            'city' => $data['city'],
            'name_representante' => $data['name_representant'],
            'last_name_representante' => $data['lastname_representant'],
            'cellphone_representante' => $data['cellphone_representant'],
            'status' => true,
            'password' => Hash::make($data['password']),
            'regimen' => $data['regimen'],
            'fecha_examen' => $data['fecha_examen'],
            'exam_month' => $data['fecha_examen'],
            'cedula' => $data['cedula'],
        ]);

        $this->assignStudentRole($user);

        return $user;
    }

    private function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'cellphone' => ['required', 'string', 'digits:10', Rule::unique('users')->whereNull('deleted_at')],
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
        ];
    }

    private function generateUniqueUsername(string $firstName, string $lastName): string
    {
        $base = $this->buildUsernameBase($firstName, $lastName);
        $username = $base;

        for ($i = 0; $i < 10 && User::where('username', $username)->exists(); $i++) {
            $username = $base . random_int(1, 9);
        }

        return $username;
    }

    private function buildUsernameBase(string $firstName, string $lastName): string
    {
        $cleanFirst = $this->stripAccents(mb_strtolower($firstName));
        $cleanLast = $this->stripAccents(mb_strtolower($lastName));

        $firstInitial = explode(' ', $cleanFirst)[0][0] ?? 'x';
        $lastParts = explode(' ', $cleanLast);
        $primaryLast = $lastParts[0] ?? 'user';
        $secondaryInitial = $lastParts[1][0] ?? $primaryLast[0];

        return $firstInitial . $primaryLast . $secondaryInitial . 'EUS';
    }

    private function stripAccents(string $value): string
    {
        $accents = ['á', 'é', 'í', 'ó', 'ú', 'ñ', 'à', 'è', 'ì', 'ò', 'ù', 'ä', 'ë', 'ï', 'ö', 'ü'];
        $plain = ['a', 'e', 'i', 'o', 'u', 'n', 'a', 'e', 'i', 'o', 'u', 'a', 'e', 'i', 'o', 'u'];

        return str_replace($accents, $plain, $value);
    }

    private function assignStudentRole(User $user): void
    {
        DB::table('role_user')->insert([
            'user_id' => $user->id,
            'role_id' => self::STUDENT_ROLE_ID,
        ]);
    }
}
