<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;


class AccountsImport implements ToCollection, WithStartRow
{
    private $rows = 0;
    private $failed_updates = [];

    public function collection(Collection $rows)
    {
        set_time_limit(0);
        foreach ($rows as $row) {

            if ($row[15] !== 1) {
                continue;
            }

            if (
                $row[7] == 'eus3pre@gmail.com' 
                || $row[7] == 'paucarevelyn222@gmail.com' 
                || $row[7] == 'admin@mail.com'
                || $row[7] == 'admin@mail.com'
                || $row[7] == 'johannacurillo.2003@gmail.com'


            ) {
                continue;
            }
            
            /**
             * nombre               1
             * apellido             2
             * celular              3
             * represent.           4
             * celular repres.      5
             * convencional         6
             * correo               7
             * regimen              8
             * ciudad               9
             * colegio              10
             * especialidad         11
             * paralelo             12
             * examen               13
             * fecha de pago        14
             * status               15
             */
            ++$this->rows;

            $user = new User();
            $user->name =                      $row[1];
            $user->last_name =                 $row[2];
            $user->cellphone =                 $row[3] ?? 'no data';
            $user->name_representante =        $row[4] ?? 'no data';
            $user->cellphone_representante =   $row[5] ?? 'no data';
            $user->fixedphone =                $row[6] ?? 'no data';
            $user->email =                     $row[7];
            $user->regimen =                   $row[8] ?? 'no data';
            $user->city =                      $row[9] ?? 'no data';
            $user->highschool =                $row[10] ?? 'no data';
            $user->especialty =                $row[11] ?? 'no data';
            $user->paralelo =                  $row[12] ?? 'no data';
            $user->fecha_examen =              $row[13];
            $user->exam_month =                $row[13]; // necesario
            $user->payment_day =               $row[14];
            $user->status =                    $row[15];
            $user->email_verified_at =         now();
            $user->remember_token =            Str::random(10);

            $user->username =                  $this->createUsername($row[1], $row[2]);
            $user->password =                  Hash::make($user->username);

            // campos que se pidieron al inicio
            $user->last_name_representante =   '-1';
            $user->cedula =                    '-1';

            $user->save();

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => 2,
    
            ]);
        }
    }

    public function createUsername($first_name, $last_name) {

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

    public function usernameExists($username) {
        return User::where('username', $username)->first();
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }

    public function getFailedUpdates(): array
    {
        return $this->failed_updates;
    }

    public function eliminar_acentos($cadena){
		
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
		$cadena );
 
		//Reemplazamos la I y i
		$cadena = str_replace(
		array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
		array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
		$cadena );
 
		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );
 
		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );
 
		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}
}
