<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'meeting_password',
        'meeting_id',
        'link',
        'course_id',
        'created_at',
        'updated_at'

    ];
}
