<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseProgramTopic extends Model
{
    use HasFactory;

    protected $table = 'course_program_topics';

    public function course()
    {
        return $this->belongsTo(CourseProgram::class);
    }

    public function resources()
    {
        return $this->hasMany(TopicResource::class, 'topic_id');
    }
}
