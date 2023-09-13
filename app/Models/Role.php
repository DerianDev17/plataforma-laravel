<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ability;

class Role extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function abilities()
    {
        return $this->belongsToMany(Ability::class)->withTimestamps();
    }

    public function allowTo($ability)
    {
        if (is_string($ability))
        {
            $ability = Role::whereName($ability)->firstOrFail();
        }
        $this->abilities()->save($ability);
    }
}
