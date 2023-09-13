<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgram extends Model
{
    use HasFactory;

    protected $table = 'courses_programs';

    public function topics()
    {
        return $this->hasMany(CourseProgramTopic::class);
    }
}
