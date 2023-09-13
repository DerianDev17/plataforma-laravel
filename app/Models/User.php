<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use \App\Models\Role;
use App\Imports\UsersImport;
use App\Utils\ValidarIdentificacion;
use Illuminate\Database\Eloquent\SoftDeletes;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\StudentGroup;
use App\Utils\Horarios;

// class User extends Authenticatable
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_name',
        'cellphone',
        'fixedphone',
        'highschool',
        'especialty',
        'paralelo',
        'city',
        'status',
        'name_representante',
        'last_name_representante',
        'cellphone_representante',
        'regimen',
        'fecha_examen',
        'exam_month',
        'cedula',
        'cedulaPadre',
        'cedulaMadre',
        'nombresPadre',
        'nombresMadre',
        'emailPadre',
        'emailMadre',
        'telefonoPadre',
        'telefonoMadre'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'profile_photo_url',
    ];


    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->firstOrFail();
        }
        $this->roles()->sync($role, false);
    }

    public function abilities()
    {
        return $this->roles->map->abilities->flatten()->pluck('name')->unique();
    }

    // por el momento solo acepta un rol a la vez
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $this->roles->contains($role);
    }

    public function getPaymentDeadline()
    {
        // -1 si es nulo en la base
        if (is_null($this->payment_day)) {
            return -1;
        }
        // aislar solo el numero
        preg_match_all('!\d+!', $this->payment_day, $matches);

        return $matches[0][0];
    }

    // public function getExamMonth()
    // {
    //     // convertir minusculas
    //     return strtolower($this->fecha_examen);
    // }

    public function getExamMonth()
    {
        // si no logró actualizar desde el excel, poner el dato de la base
        if (is_null($this->exam_month)) {

            return trim(strtolower($this->fecha_examen));
        }

        // convertir minusculas
        return trim(strtolower($this->exam_month));
    }

    public function validatePhone($phone_xlx, $phone_db)
    {
        $phone_in_excel = false;
        $phones = explode('-', $phone_xlx);
        $conceros = function ($phone) {
            return '0' . $phone;
        };
        $transformed_phones = array_map($conceros, $phones);

        $phone_in_excel = in_array($phone_db, $transformed_phones);
        return $phone_in_excel;
    }

    // busca un usuario en el excel y retorna un array con los datos
    public function findUserInExcel($user)
    {
        $found_user = [];
        $students_excel = Excel::toArray(new UsersImport, 'Base_Alumnos.xlsx');
        foreach ($students_excel[0] as $i => $std_xlx) {
            if ($std_xlx[7] === $user->email) {
                $found_user = $std_xlx;
            };
        }
        return $found_user;
    }

    public function adeuda()
    {
        return  !$this->status;
    }

    public function diapago()
    {
        return $this->diapago;
    }

    public function cedulaPadre()
    {
        if (!isset($this->cedulaPadre) || trim($this->cedulaPadre) === '')
            return false;
        else
            return true;
    }

    public function getPaymentDeadlineExcel()
    {
        $user_excel = $this->findUserInExcel($this);

        // si no se encuentra el usuario en el excel retornar -1
        if (!$user_excel) {
            return -1;
        }

        // guardar el dia maximo de pago en una variable
        $deadline = $user_excel[14];

        // buscar solo el numero en el string que viene del excel
        preg_match_all('!\d+!', $deadline, $matches);

        return $matches[0][0];
    }

    public function getStatusExcel()
    {
        $user_excel = $this->findUserInExcel($this);

        // si no se encuentra el usuario en el excel retornar -1
        if (!$user_excel) {
            return 0;
        }

        return $user_excel[15];
    }

    public function course_sessions()
    {
        return $this->belongsToMany(CourseSession::class, 'attendances');
    }

    public function encuestas()
    {
        return $this->hasMany(Encuesta::class);
    }

    public function student_group()
    {
        return $this->belongsTo(StudentGroup::class);
    }

    public function check_cedula_validity()
    {
        $ci_validator = new ValidarIdentificacion();
        return $ci_validator->validarCedula($this->cedula);
    }

    public function scopeStudents($query)
    {
        return $query->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->where('role_id', '=', 2);
    }

    public function horario()
    {
        // $exam_month = $student->getExamMonth();
        $std_grp = $this->student_group;
        // dd($std_grp);
        $horarios_obj = new Horarios;
        return $horarios_obj->get_horario($std_grp->code);
    }
}
