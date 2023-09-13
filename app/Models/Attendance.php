<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_session_id',
        'user_id',
        // 'created_by',
        // 'updated_by',
    ];

}
/*
class Paralelo extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_course',
    ];

}*/
