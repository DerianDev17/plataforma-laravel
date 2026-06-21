<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\StudentGroup;
use App\Http\Livewire\Sesiones\Show as SessionsShow;
use App\Http\Livewire\Students\Show as StudentsShow;
use App\Http\Livewire\UsersCrud\Show as UsersCrudShow;
use App\Mail\RegistrationMail;
use App\Services\Students\StudentDashboardCounterService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
    public function admin_users_page_shows_management_controls()
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('Usuarios y accesos');
        $response->assertSee('Nuevo usuario');
        $response->assertSee('Estado de pago');
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

    /** @test */
    public function admin_can_create_student_user_with_username_from_user_admin()
    {
        Livewire::actingAs($this->admin)
            ->test(UsersCrudShow::class)
            ->call('create')
            ->set('name', 'Ana')
            ->set('last_name', 'Torres')
            ->set('username', 'atorres')
            ->set('password', 'secret123')
            ->set('cedula', '1234567890')
            ->set('cellphone', '0999999999')
            ->set('email', 'ana.torres@example.com')
            ->set('highschool', 'Colegio Central')
            ->set('city', 'Quito')
            ->set('payment_status', 'pending')
            ->set('name_representante', 'Maria')
            ->set('last_name_representante', 'Torres')
            ->set('cellphone_representante', '0988888888')
            ->set('regimen', 'Sierra')
            ->set('exam_month', 'Junio')
            ->call('store')
            ->assertHasNoErrors();

        $user = User::where('username', 'atorres')->firstOrFail();

        $this->assertSame('ana.torres@example.com', $user->email);
        $this->assertTrue(Hash::check('secret123', $user->password));
        $this->assertTrue($user->hasRole('student'));
    }

    /** @test */
    public function editing_student_without_password_keeps_existing_password()
    {
        $studentRole = Role::where('name', 'student')->first();
        $student = User::factory()->create([
            'username' => 'keep-pass',
            'email' => 'keep-pass@example.com',
            'password' => Hash::make('original-password'),
            'payment_status' => 'paid',
            'cedula' => '1234567890',
            'name_representante' => 'Maria',
            'last_name_representante' => 'Perez',
            'cellphone_representante' => '0988888888',
        ]);
        $student->roles()->attach($studentRole->id);

        Livewire::actingAs($this->admin)
            ->test(UsersCrudShow::class)
            ->call('edit', $student->id)
            ->set('name', 'Updated')
            ->set('password', '')
            ->call('store')
            ->assertHasNoErrors();

        $student = $student->fresh();

        $this->assertSame('Updated', $student->name);
        $this->assertTrue(Hash::check('original-password', $student->password));
    }

    /** @test */
    public function students_table_only_uses_like_filter_when_search_has_value()
    {
        DB::enableQueryLog();

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class);

        $this->assertFalse($this->queryLogContainsLike(DB::getQueryLog()));

        DB::flushQueryLog();

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class)
            ->set('searchTerm', 'student');

        $this->assertTrue($this->queryLogContainsLike(DB::getQueryLog()));
    }

    /** @test */
    public function student_dashboard_counters_are_cached_and_refreshed_after_payment_update()
    {
        Cache::flush();

        $studentRole = Role::where('name', 'student')->first();
        $group = StudentGroup::create(['name' => 'Grupo A', 'code' => 'A', 'webinar_id' => '123']);
        $student = User::factory()->create([
            'payment_status' => 'paid',
            'status' => 1,
            'student_group_id' => $group->id,
        ]);
        $student->roles()->attach($studentRole->id);

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class)
            ->assertSet('total_students_n', 1)
            ->assertSet('active_students_n', 1)
            ->assertSet('blocked_students_n', 0)
            ->call('updatePaymentStatus', $student->id, 'overdue')
            ->assertSet('total_students_n', 1)
            ->assertSet('active_students_n', 0)
            ->assertSet('blocked_students_n', 1);

        $this->assertSame(1, Cache::get(StudentDashboardCounterService::CACHE_KEY)['blocked_students_n']);
    }

    /** @test */
    public function user_model_does_not_eager_load_roles_globally()
    {
        $this->assertArrayNotHasKey('roles', User::query()->getEagerLoads());
    }

    /** @test */
    public function sessions_table_only_uses_like_filter_when_search_has_value()
    {
        DB::enableQueryLog();

        Livewire::actingAs($this->admin)
            ->test(SessionsShow::class);

        $this->assertFalse($this->queryLogContainsLike(DB::getQueryLog()));

        DB::flushQueryLog();

        Livewire::actingAs($this->admin)
            ->test(SessionsShow::class)
            ->set('searchTerm', now()->toDateString());

        $this->assertTrue($this->queryLogContainsLike(DB::getQueryLog()));
    }

    /** @test */
    public function admin_reset_password_generates_temp_password_not_username()
    {
        Mail::fake();

        $student = User::factory()->create([
            'email' => 'reset@test.com',
            'username' => 'resetstudent',
            'password' => Hash::make('old-password'),
            'must_change_password' => false,
        ]);

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class)
            ->call('resetPassword', $student->id)
            ->assertHasNoErrors();

        $student->refresh();

        $this->assertFalse(Hash::check($student->username, $student->getAuthPassword()));
        $this->assertFalse(Hash::check('old-password', $student->getAuthPassword()));
        $this->assertTrue((bool) $student->must_change_password);

        Mail::assertSent(RegistrationMail::class, function (RegistrationMail $mail) use ($student) {
            $tempPassword = $mail->details['temp_password'] ?? null;

            return $mail->hasTo($student->email)
                && is_string($tempPassword)
                && $tempPassword !== ''
                && $tempPassword !== $student->username
                && Hash::check($tempPassword, $student->getAuthPassword());
        });
    }

    private function queryLogContainsLike(array $queries): bool
    {
        foreach ($queries as $query) {
            if (str_contains(strtolower($query['query']), ' like ')) {
                return true;
            }
        }

        return false;
    }
}
