<?php

namespace Tests\Feature\Roles;

use App\Models\Ability;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function superadmin_has_access_to_all_abilities_via_gate_after()
    {
        $this->seed(PermissionsSeeder::class);

        $superadmin = User::where('username', 'superadmin')->first();

        $this->assertTrue($superadmin->can('edit_users'));
        $this->assertTrue($superadmin->can('crud_drives'));
        $this->assertTrue($superadmin->can('invitar-estudiantes'));
        $this->assertTrue($superadmin->can('random-nonexistent-ability'));
    }

    /** @test */
    public function student_with_watch_lessons_can_watch_lessons()
    {
        $this->seed(PermissionsSeeder::class);

        $student = User::factory()->create();
        $student->assignRole('student');

        $this->assertTrue($student->can('watch_lessons'));
    }

    /** @test */
    public function student_without_edit_users_cannot_edit_users()
    {
        $this->seed(PermissionsSeeder::class);

        $student = User::factory()->create();
        $student->assignRole('student');

        $this->assertFalse($student->can('edit_users'));
        $this->assertFalse($student->can('crud_drives'));
    }

    /** @test */
    public function user_with_multiple_roles_has_combined_abilities()
    {
        $this->seed(PermissionsSeeder::class);

        $editUsersAbility = Ability::create(['name' => 'edit_users']);
        $adminRole = Role::create(['name' => 'editor']);
        $adminRole->abilities()->attach($editUsersAbility);

        $user = User::factory()->create();
        $user->assignRole('student');
        $user->assignRole($adminRole);

        $this->assertTrue($user->can('watch_lessons'));
        $this->assertTrue($user->can('edit_users'));
    }

    /** @test */
    public function user_abilities_method_returns_unique_ability_names()
    {
        $this->seed(PermissionsSeeder::class);

        $student = User::factory()->create();
        $student->assignRole('student');

        $abilities = $student->abilities();

        $this->assertContains('watch_lessons', $abilities);
        $this->assertCount(1, $abilities);
    }

    /** @test */
    public function user_abilities_is_cached_after_first_call()
    {
        $this->seed(PermissionsSeeder::class);

        $student = User::factory()->create();
        $student->assignRole('student');

        $first = $student->abilities();
        $second = $student->abilities();

        $this->assertSame($first, $second);
    }

    /** @test */
    public function assignRole_accepts_role_model()
    {
        $this->seed(PermissionsSeeder::class);

        $studentRole = Role::where('name', 'student')->first();
        $user = User::factory()->create();
        $user->assignRole($studentRole);

        $this->assertTrue($user->fresh()->hasRole('student'));
    }

    /** @test */
    public function gate_read_course_programs_allows_superadmin()
    {
        $this->seed(PermissionsSeeder::class);

        $superadmin = User::where('username', 'superadmin')->first();

        $this->assertTrue($superadmin->can('read_course_programs'));
    }

    /** @test */
    public function gate_read_course_programs_allows_user_with_ability()
    {
        $this->seed(PermissionsSeeder::class);

        $readAbility = Ability::create(['name' => 'read_course_programs']);
        $readerRole = Role::create(['name' => 'reader']);
        $readerRole->abilities()->attach($readAbility);

        $user = User::factory()->create();
        $user->assignRole('reader');

        $this->assertTrue($user->can('read_course_programs'));
    }

    /** @test */
    public function gate_invitar_estudiantes_only_allows_superadmin()
    {
        $this->seed(PermissionsSeeder::class);

        $superadmin = User::where('username', 'superadmin')->first();
        $student = User::factory()->create();
        $student->assignRole('student');

        $this->assertTrue($superadmin->can('invitar-estudiantes'));
        $this->assertFalse($student->can('invitar-estudiantes'));
    }

    /** @test */
    public function hasRole_returns_false_for_unassigned_role()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('student');

        $this->assertFalse($user->hasRole('superadmin'));
    }

    /** @test */
    public function abilities_cache_is_cleared_when_role_is_assigned()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('student');

        $beforeAssign = $user->abilities();

        $adminRole = Role::create(['name' => 'new_admin']);
        $adminRole->abilities()->attach(Ability::create(['name' => 'edit_users']));
        $user->assignRole('new_admin');

        $afterAssign = $user->abilities();

        $this->assertContains('edit_users', $afterAssign);
        $this->assertNotSame($beforeAssign, $afterAssign);
    }
}
