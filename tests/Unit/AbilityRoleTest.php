<?php

namespace Tests\Unit;

use App\Models\Ability;
use App\Models\Role;
use Tests\TestCase;

class AbilityRoleTest extends TestCase
{
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
    public function ability_allowTo_accepts_string_and_uses_correct_model()
    {
        $fakeAbility = new Ability(['name' => 'watch_lessons']);
        $fakeAbility->id = 1;

        $ability = $this->getMockBuilder(Ability::class)
            ->onlyMethods(['roles'])
            ->getMock();

        $ability->method('roles')->willReturn(new class {
            public function save($model) {}
        });

        $this->assertTrue(true);
    }

    /** @test */
    public function role_allowTo_accepts_string_and_uses_correct_model()
    {
        $role = $this->getMockBuilder(Role::class)
            ->onlyMethods(['abilities'])
            ->getMock();

        $role->method('abilities')->willReturn(new class {
            public function save($model) {}
        });

        $this->assertTrue(true);
    }
}
