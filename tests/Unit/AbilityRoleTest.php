<?php

namespace Tests\Unit;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AbilityRoleTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function ability_has_roles_relationship()
    {
        $ability = new Ability(['name' => 'edit_users']);
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $ability->roles()
        );
    }

    /** @test */
    public function role_has_abilities_relationship()
    {
        $role = new Role(['name' => 'student']);
        
        $this->assertInstanceOf(
            \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
            $role->abilities()
        );
    }

    /** @test */
    public function ability_can_be_created_with_attributes()
    {
        $ability = Ability::create(['name' => 'test_ability']);

        $this->assertDatabaseHas('abilities', ['name' => 'test_ability']);
        $this->assertEquals('test_ability', $ability->name);
    }

    /** @test */
    public function role_can_be_created_with_attributes()
    {
        $role = Role::create(['name' => 'test_role']);

        $this->assertDatabaseHas('roles', ['name' => 'test_role']);
        $this->assertEquals('test_role', $role->name);
    }
}
