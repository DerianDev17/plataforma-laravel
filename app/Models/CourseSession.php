<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;


class CourseSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'student_groups_id',
        'date',
        'time',
        'subject',
        'module_number',
        // 'created_by',
        // 'updated_by',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'attendances');
    }

    public function student_group(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class, 'student_groups_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * This is model Observer which helps to do the same actions automatically when you creating or updating models
     *
     * @var array
     */
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //         $model->created_by = Auth::id();
    //         $model->updated_by = Auth::id();
    //     });
    //     static::updating(function ($model) {
    //         $model->updated_by = Auth::id();
    //     });
    // }
}
