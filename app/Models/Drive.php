<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;


class Drive extends Model
{
    use HasFactory;

    protected $fillable = [
        'modulo',
        'materia',
        'link',
        'course_id',
        'created_by',
        'updated_by'
    ];

    /**
     * This is model Observer which helps to do the same actions automatically when you creating or updating models
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = Auth::id();
            $model->updated_by = Auth::id();
        });
        static::updating(function ($model) {
            $model->updated_by = Auth::id();
        });
        static::saved(function ($model) {
            static::clearResourceCache($model->course_id);

            if ($model->wasChanged('course_id')) {
                static::clearResourceCache($model->getOriginal('course_id'));
            }
        });
        static::deleted(function ($model) {
            static::clearResourceCache($model->course_id);
        });
    }

    protected static function clearResourceCache($courseId): void
    {
        if ($courseId) {
            Cache::forget('resources.drives.group.' . $courseId);
        }
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
