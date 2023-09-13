<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicResource extends Model
{
    use HasFactory;

    public function topic()
    {
        return $this->belongsTo(CourseProgramTopic::class);
    }

    public function resource_url()
    {
        return $this->hasOne(ResourceUrl::class);
    }

    public function resource_file()
    {
        return $this->hasOne(ResourceFile::class);
    }
}
