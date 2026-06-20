<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Http\Livewire\Students\Show as StudentsShow;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->admin = User::where('email', 'admin@mail.com')->first();
        $this->student = User::factory()->create([
            'email' => 'student@test.com',
            'username' => 'teststudent',
            'status' => 1,
            'exam_month' => 'Febrero',
        ]);
    }

    /** @test */
    public function admin_can_view_students_page()
    {
        $response = $this->actingAs($this->admin)->get('/student');

        $response->assertStatus(200);
    }

    /** @test */
    public function student_cannot_view_admin_page()
    {
        $response = $this->actingAs($this->student)->get('/admin/users');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_activate_user()
    {
        $user = User::factory()->create(['status' => 0]);
        
        $response = $this->actingAs($this->admin)
            ->from('/dashboard')
            ->patch("/dashboard/active/{$user->id}");
        
        $response->assertRedirect('/dashboard');
        $this->assertEquals(1, $user->fresh()->status);
    }

    /** @test */
    public function student_cannot_activate_user()
    {
        $user = User::factory()->create(['status' => 0]);
        
        $response = $this->actingAs($this->student)
            ->patch("/dashboard/active/{$user->id}");
        
        $response->assertStatus(403);
    }

    /** @test */
    public function superadmin_has_all_abilities()
    {
        $this->assertTrue($this->admin->hasRole('superadmin'));
        $this->assertTrue($this->admin->can('edit_users'));
        $this->assertTrue($this->admin->can('crud_drives'));
    }

    /** @test */
    public function student_has_no_admin_abilities()
    {
        $role = Role::where('name', 'student')->first();
        $this->student->roles()->sync([$role->id]);
        
        $this->assertTrue($this->student->fresh()->hasRole('student'));
        $this->assertFalse($this->student->fresh()->can('edit_users'));
        $this->assertFalse($this->student->fresh()->can('crud_drives'));
    }

    /** @test */
    public function admin_cannot_delete_nonexistent_user()
    {
        $response = $this->actingAs($this->admin)->delete('/users/99999');
        
        $response->assertStatus(404);
    }

    /** @test */
    public function status_controller_unlocks_all_users()
    {
        User::factory()->count(5)->create(['status' => false]);
        
        $this->assertEquals(5, User::where('status', false)->count());

        $response = $this->actingAs($this->admin)->post('/status');
        
        $response->assertRedirect();
        $this->assertEquals(0, User::where('status', false)->count());
    }

    /** @test */
    public function user_scope_students_filters_correctly()
    {
        $studentRole = Role::where('name', 'student')->first();

        $student = User::factory()->create();
        $student->roles()->attach($studentRole->id);

        $nonStudent = User::factory()->create();

        $students = User::students()->get();

        $studentIds = $students->pluck('id');
        $this->assertTrue($studentIds->contains($student->id));
        $this->assertFalse($studentIds->contains($nonStudent->id));
    }

    /** @test */
    public function admin_can_update_student_payment_status_individually()
    {
        $studentRole = Role::where('name', 'student')->first();

        $student = User::factory()->create([
            'payment_status' => 'paid',
            'status' => 1,
            'join_url' => 'https://classes.test/current',
            'id_zoom' => 'registrant-1',
        ]);
        $student->roles()->attach($studentRole->id);

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class)
            ->call('updatePaymentStatus', $student->id, 'overdue')
            ->assertHasNoErrors();

        $student = $student->fresh();
        $this->assertSame('overdue', $student->payment_status);
        $this->assertEquals(0, $student->status);
        $this->assertNull($student->join_url);
        $this->assertNull($student->id_zoom);

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class)
            ->call('updatePaymentStatus', $student->id, 'paid')
            ->assertHasNoErrors();

        $student = $student->fresh();
        $this->assertSame('paid', $student->payment_status);
        $this->assertEquals(1, $student->status);
    }
}
