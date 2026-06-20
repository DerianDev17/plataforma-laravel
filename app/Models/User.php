<?php

namespace App\Models;

use App\Concerns\HasIdentityValidation;
use App\Concerns\HasPayments;
use App\Concerns\HasRoles;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\StudentGroup;
use App\Utils\Horarios;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use SoftDeletes;
    use HasPayments;
    use HasRoles;
    use HasIdentityValidation;

    protected $fillable = [
        'name',
        'email',
        'password',
        'username',
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
        'telefonoMadre',
        'payment_status',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'profile_photo_url',
    ];

    protected $with = [
        'roles',
    ];

    public function course_sessions()
    {
        return $this->belongsToMany(CourseSession::class, 'attendances');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function encuestas()
    {
        return $this->hasMany(Encuesta::class);
    }

    public function student_group()
    {
        return $this->belongsTo(StudentGroup::class);
    }

    public function scopeStudents($query)
    {
        return $query->whereHas('roles', function ($q) {
            $q->where('name', 'student');
        });
    }

    public function scopeWithLiveClassPaymentAccess($query)
    {
        return $query->where(function ($query) {
            $query->whereIn('payment_status', self::LIVE_CLASS_PAYMENT_STATUSES)
                ->orWhere(function ($query) {
                    $query->whereNull('payment_status')
                        ->where('status', 1);
                });
        });
    }

    public function horario()
    {
        $this->loadMissing('student_group');
        $std_grp = $this->student_group;

        if (! $std_grp || ! $std_grp->code) {
            return [];
        }

        $horarios_obj = new Horarios;

        return $horarios_obj->get_horario($std_grp->code);
    }
}
