<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class RecordedClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_number',
        'group',
        'lenguaje_link',
        'math_link',
        'science_link',
        'social_link',
        'orientation_link',
        'created_at',
        'updated_at',
    ];

    /**
     * This is model Observer which helps to do the same actions automatically when you creating or updating models
     *
     * @var array
     */
    protected static function boot()
    {
        parent::boot();
        // static::creating(function ($model) {
        //     $model->created_by = Auth::id();
        //     $model->updated_by = Auth::id();
        // });
        // static::updating(function ($model) {
        //     $model->updated_by = Auth::id();
        // });
    }
}
