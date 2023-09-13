<?php

namespace App\Imports;

use App\Models\StudentGroup;
use App\Models\User;
use App\Notifications\SendCredentials;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

// TODO: Dar la opción de enviar o no los correos
class StudentsImportar implements ToCollection, WithStartRow, WithChunkReading
{
    protected $delete;
    private $rows = 0;
    private $failed_updates = [];
    private $failed_emails = [];
    private $student_groups;

    public function __construct($delete)
    {
        $this->delete = $delete;
        $this->student_groups = StudentGroup::all();
    }

    public function borrarEstudiantes($estudiantes)
    {
        $to_delete = [];
        // $est_base = User::all();
        $est_base = User::students()->get();

        foreach ($est_base as $sb) {
            array_push($to_delete, $sb);
        }

        foreach ($to_delete as $sb => $val) {
            foreach ($estudiantes as $sx) {
                if ($val->email == $sx[7]) {
                    unset($to_delete[$sb]);
                }
            }
        }

        foreach ($to_delete as $key => $val) {
            if ($val->hasRole('superadmin')) {
                unset($to_delete[$key]);
            }
        }

        foreach ($to_delete as $td) {
            $td->forcedelete();
        }
        // dd($to_delete);
    }

    public function validate_emails($students)
    {
        $emails = [];
        $invalid_emails = [];
        foreach ($students as $s) {
            array_push($emails, $s[7]);
        }
        for ($i = 0; $i < count($emails); $i++) {
            if (!filter_var($emails[$i], FILTER_VALIDATE_EMAIL)) {
                array_push($invalid_emails, $emails[$i]);
            }
        }
        return $invalid_emails;
        // dd($invalid_emails);
    }

    public function collection(Collection $estudiantes)
    {
        // dd(User::students()->get());
        set_time_limit(0);

        $invalid_emails = $this->validate_emails($estudiantes);
        if ($invalid_emails) {
            $this->failed_emails = $invalid_emails;
            // return;
        }

        $db_students = User::students()->get();
        $keyed_db_stud = $db_students->keyBy(function ($std) {
            return trim($std['email']);
        })->all();
        // dd($keyed_db_stud);

        if ($this->delete) {
            $this->borrarEstudiantes($estudiantes);
        }
        // dd($estudiantes->slice(0,2));
        foreach ($estudiantes as $row) {
            $student_model = $this->existeUsuarioBase($row, $keyed_db_stud);
            // el estudiante existe en el excel, tiene status=0, pero no existe en el sistema
            if ($row[15] == 0 && !$student_model) {
                continue;
            }
            if ($student_model) {
                $this->actualizarUserData($row, $student_model);
                continue;
            }
            $this->crearUsuario($row);
        }
    }

    public function actualizarUserData($row, $user)
    {
        $paralelo = trim($row[13]);

        // $user = User::students()->where('email', $row[7])->first();

        $user->status = $row[15];
        $user->exam_month = $row[13];
        $user->diapago = $row[16];
        $user->enviarCorreo = $row[17];
        $user->student_group_id = $this->getStudentGroupbyCode($paralelo)->id;;
        $user->save();
    }

    public function existeUsuarioBase($excel_row, &$db_students)
    {
        $email = trim($excel_row[7]);
        if (isset($db_students[$email])) {
            // dd($db_students[$email]);
            return $db_students[$email];
        }
        return null;
    }

    public function getStudentGroupbyCode($code)
    {
        return $this->student_groups->firstWhere('code', $code);
    }

    public function crearUsuario($row)
    {
        $paralelo = trim($row[13]);
        $user = new User();
        $user->name =                      $row[1];
        $user->last_name =                 $row[2];
        $user->cellphone =                 $row[3] ?? 'no data';
        $user->name_representante =        $row[4] ?? 'no data';
        $user->cellphone_representante =   $row[5] ?? 'no data';
        $user->fixedphone =                $row[6] ?? 'no data';
        $user->email =                     trim($row[7]);
        $user->regimen =                   $row[8] ?? 'no data';
        $user->city =                      $row[9] ?? 'no data';
        $user->highschool =                $row[10] ?? 'no data';
        $user->especialty =                $row[11] ?? 'no data';
        $user->paralelo =                  $row[12] ?? 'no data';
        $user->fecha_examen =              $row[13];
        $user->exam_month =                $row[13]; // necesario
        $user->payment_day =               $row[14];
        $user->status =                    $row[15];
        $user->diapago =                   $row[16];
        $user->enviarCorreo =              $row[17];

        $user->email_verified_at =         now();
        $user->remember_token =            Str::random(10);
        $user->student_group_id =          $this->getStudentGroupbyCode($paralelo)->id;

        $user->username =                  $this->createUsername($row[1], $row[2]);
        $user->password =                  Hash::make($user->username);

        // campos que se pidieron al inicio
        $user->last_name_representante =   '-1';
        $user->cedula =                    '-1';

        $user->save();

        $this->asignarRol($user->id, 2);

        $this->enviarCorreoUsuario($user);

        ++$this->rows;
    }

    public function enviarCorreoUsuario($user)
    {
        $details = [
            'title' => 'Creación de cuenta Eus3',
            'body' => 'Saludos desde ',
            'user' => $user,
        ];
        // dd($f);
        if (filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
            Mail::to($user->email)->send(new \App\Mail\RegistrationMail($details));
            //$user->notify(new SendCredentials($user));
        }
        //usleep(250000);
    }

    public function asignarRol($id_user, $rol)
    {
        DB::table('role_user')->insert([
            'user_id' => $id_user,
            'role_id' => $rol,
        ]);
    }

    public function usernameExists($username)
    {
        return User::where('username', $username)->first();
    }

    public function createUsername($first_name, $last_name)
    {

        // remover acentos
        $first_name = $this->eliminar_acentos($first_name);
        $last_name = $this->eliminar_acentos($last_name);

        // a minusculas
        $first_name = mb_strtolower($first_name);
        $last_name = mb_strtolower($last_name);
        // dd($first_name, $last_name);

        // obtener $sigla_primer_nombre
        $sigla_primer_nombre = explode(' ', $first_name)[0][0];

        // obtener primer apellido
        $primer_apellido = explode(' ', $last_name)[0];
        $sigla_primer_apellido = $primer_apellido[0];

        // obtener sigla segundo apellido
        $sigla_segundo_apellido = explode(' ', $last_name)[1][0] ?? $sigla_primer_apellido;

        $pre_username = $sigla_primer_nombre . $primer_apellido . $sigla_segundo_apellido . 'EUS';

        // revisar si el username existe en la base de datos, modificar en caso
        if ($this->usernameExists($pre_username)) {
            $sigla_segundo_apellido .= rand(1, 9);
        }

        // concatenar $sigla_primer_nombre + $apellido + $sigla_segundo_apellido + EUS
        // dd($sigla_primer_nombre . $primer_apellido . $sigla_segundo_apellido . 'EUS');
        return $sigla_primer_nombre . $primer_apellido . $sigla_segundo_apellido . 'EUS';
    }

    public function startRow(): int
    {
        return 2;
    }

    public function chunkSize(): int
    {
        return 800;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getFailedUpdates(): array
    {
        return $this->failed_updates;
    }

    public function getFailedEmails(): array
    {
        return $this->failed_emails;
    }

    public function eliminar_acentos($cadena)
    {

        //Reemplazamos la A y a
        $cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
        );

        //Reemplazamos la E y e
        $cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena
        );

        //Reemplazamos la I y i
        $cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena
        );

        //Reemplazamos la O y o
        $cadena = str_replace(
            array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
            array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
            $cadena
        );

        //Reemplazamos la U y u
        $cadena = str_replace(
            array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
            array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
            $cadena
        );

        //Reemplazamos la N, n, C y c
        $cadena = str_replace(
            array('Ñ', 'ñ', 'Ç', 'ç'),
            array('N', 'n', 'C', 'c'),
            $cadena
        );

        return $cadena;
    }
}
