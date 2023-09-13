<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceUrl extends Model
{
    use HasFactory;

    public function resource()
    {
        return $this->belongsTo(TopicResource::class);
    }
}
