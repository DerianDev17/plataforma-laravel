<?php

namespace Tests\Feature;

use App\Http\Livewire\Asistencias\Show as StudentAttendanceComponent;
use App\Http\Livewire\StudentAttendances\Show as AdminAttendanceComponent;
use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Tests\TestCase;

class AttendanceFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    /** @test */
    public function student_can_register_own_attendance_during_enabled_window(): void
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));

        $student = User::factory()->create(['student_group_id' => 1]);
        $student->assignRole('student');

        $session = $this->createCourseSession([
            'student_groups_id' => 1,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        Livewire::actingAs($student)
            ->test(StudentAttendanceComponent::class)
            ->call('registerAttendance', $session->id)
            ->assertHasNoErrors();

        $this->assertDatabaseHas('attendances', [
            'user_id' => $student->id,
            'course_session_id' => $session->id,
        ]);
    }

    /** @test */
    public function student_cannot_register_attendance_for_other_group_session(): void
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));

        $student = User::factory()->create(['student_group_id' => 1]);
        $student->assignRole('student');

        $session = $this->createCourseSession([
            'student_groups_id' => 2,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        Livewire::actingAs($student)
            ->test(StudentAttendanceComponent::class)
            ->call('registerAttendance', $session->id);

        $this->assertDatabaseMissing('attendances', [
            'user_id' => $student->id,
            'course_session_id' => $session->id,
        ]);
    }

    /** @test */
    public function admin_can_create_manual_attendance_without_duplicates(): void
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);

        $admin = User::where('email', 'admin@mail.com')->first();
        $student = User::factory()->create(['student_group_id' => 1]);
        $student->assignRole('student');
        $session = $this->createCourseSession(['student_groups_id' => 1]);

        $component = Livewire::actingAs($admin)
            ->test(AdminAttendanceComponent::class)
            ->set('formUserId', $student->id)
            ->set('formCourseSessionId', $session->id)
            ->call('store')
            ->assertHasNoErrors();

        $component
            ->set('formUserId', $student->id)
            ->set('formCourseSessionId', $session->id)
            ->call('store')
            ->assertHasErrors(['formCourseSessionId']);

        $this->assertSame(1, Attendance::where('user_id', $student->id)
            ->where('course_session_id', $session->id)
            ->count());
    }

    /** @test */
    public function student_cannot_access_admin_attendance_page(): void
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $student = User::factory()->create();
        $student->assignRole('student');

        $this->actingAs($student)
            ->get('/admin/asistencias')
            ->assertStatus(403);
    }

    private function createCourseSession(array $overrides = []): CourseSession
    {
        DB::table('courses')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Curso base', 'code' => 'BASE']
        );

        DB::table('subjects')->updateOrInsert(
            ['id' => 1],
            ['name' => 'Matematica', 'code' => 'MAT', 'description' => null]
        );

        return CourseSession::create(array_merge([
            'course_id' => 1,
            'student_groups_id' => 1,
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->format('H:i:s'),
            'subject' => 'Matematica',
            'module_number' => 1,
        ], $overrides));
    }
}
