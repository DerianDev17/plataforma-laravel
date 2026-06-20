<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function landing_page_is_accessible()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /** @test */
    public function guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_dashboard()
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /** @test */
    public function student_dashboard_shows_class_schedule_when_group_is_assigned()
    {
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        \Illuminate\Support\Facades\DB::table('subjects')->insert([
            ['name' => 'Orientacion Vocacional', 'code' => 'ORIE'],
            ['name' => 'Razonamiento Numerico', 'code' => 'RANU'],
            ['name' => 'Razonamiento Logico', 'code' => 'RALO'],
            ['name' => 'Atencion y Concentracion', 'code' => 'RAES'],
            ['name' => 'Razonamiento Verbal', 'code' => 'RAVE'],
        ]);

        $user = User::factory()->create([
            'student_group_id' => 1,
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Horario de clases');
        $response->assertSee('Paralelo A');
        $response->assertSee('Razonamiento Numerico');
    }

    /** @test */
    public function admin_meetings_page_shows_all_group_schedules()
    {
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $admin = User::where('email', 'admin@mail.com')->first();

        $response = $this->actingAs($admin)->get('/dashboard/meetings');

        $response->assertStatus(200);
        $response->assertSee('Reuniones y horario semanal');
        $response->assertSee('Paralelo A');
        $response->assertSee('Paralelo B');
        $response->assertSee('Paralelo C');
        $response->assertSee('Paralelo D');
    }

    /** @test */
    public function student_meetings_page_shows_assigned_group_schedule()
    {
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $user = User::factory()->create([
            'student_group_id' => 1,
        ]);
        $user->assignRole('student');

        $response = $this->actingAs($user)->get('/dashboard/meetings');

        $response->assertStatus(200);
        $response->assertSee('Reuniones y horario semanal');
        $response->assertSee('Paralelo A');
        $response->assertDontSee('Paralelo B');
    }

    /** @test */
    public function user_assignRole_accepts_string()
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('student');

        $this->assertTrue($user->hasRole('student'));
    }

    /** @test */
    public function user_assignRole_accepts_model()
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $role = \App\Models\Role::where('name', 'student')->first();
        $user = User::factory()->create();
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('student'));
    }

    /** @test */
    public function guest_cannot_access_contacts()
    {
        $response = $this->get('/contacts');

        $response->assertRedirect('/login');
    }

    /** @test */
    public function authenticated_user_can_access_contacts()
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/contacts');

        $response->assertStatus(200);
    }

    /** @test */
    public function user_abilities_returns_collection()
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $user = User::factory()->create();
        $user->assignRole('student');

        $abilities = $user->abilities();
        
        $this->assertTrue($abilities->contains('watch_lessons'));
    }
}
