<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \App\Models\Role;

class Ability extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function allowTo($role)
    {
        if (is_string($role))
        {
            $role = Ability::whereName($role)->firstOrFail();
        }
        $this->ability()->save($role);
    }
}
