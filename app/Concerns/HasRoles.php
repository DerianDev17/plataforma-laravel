<?php

namespace App\Concerns;

use App\Models\Role;

trait HasRoles
{
    protected $cachedAbilities;

    public function roles()
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->firstOrFail();
        }
        $this->roles()->sync($role, false);
        $this->unsetRelation('roles');
        $this->cachedAbilities = null;
    }

    public function abilities()
    {
        if ($this->cachedAbilities !== null) {
            return $this->cachedAbilities;
        }

        $this->loadMissing('roles.abilities');

        $this->cachedAbilities = $this->roles
            ->pluck('abilities')
            ->flatten()
            ->pluck('name')
            ->unique()
            ->values();

        return $this->cachedAbilities;
    }

    public function hasRole($role)
    {
        $this->loadMissing('roles');

        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }

        return $this->roles->contains($role);
    }
}
