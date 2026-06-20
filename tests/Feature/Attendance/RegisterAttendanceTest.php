<?php

namespace Tests\Feature\Attendance;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\StudentGroup;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class RegisterAttendanceTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected StudentGroup $group;
    protected CourseSession $session;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);

        $this->group = StudentGroup::create(['code' => 'A', 'webinar_id' => 123]);
        $this->student = User::factory()->create(['student_group_id' => $this->group->id]);
        $this->student->assignRole('student');

        $this->session = CourseSession::create([
            'course_id' => 999,
            'student_groups_id' => $this->group->id,
            'date' => now()->toDateString(),
            'time' => now()->addMinutes(5)->format('H:i:s'),
            'subject' => 'Matematica',
            'module_number' => 1,
        ]);
    }

    /** @test */
    public function student_can_register_attendance_for_own_group_session()
    {
        Livewire::actingAs($this->student)
            ->test(\App\Http\Livewire\Asistencias\Show::class)
            ->call('registerAttendance', $this->session->id);

        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->student->id,
            'course_session_id' => $this->session->id,
        ]);
    }

    /** @test */
    public function registering_twice_does_not_create_duplicate_attendance()
    {
        $this->actingAs($this->student);

        Livewire::test(\App\Http\Livewire\Asistencias\Show::class)
            ->call('registerAttendance', $this->session->id);

        Livewire::test(\App\Http\Livewire\Asistencias\Show::class)
            ->call('registerAttendance', $this->session->id);

        $this->assertDatabaseCount('attendances', 1);
    }

    /** @test */
    public function student_cannot_register_attendance_for_other_group_session()
    {
        $otherGroup = StudentGroup::create(['code' => 'B', 'webinar_id' => 456]);
        $otherSession = CourseSession::create([
            'course_id' => 999,
            'student_groups_id' => $otherGroup->id,
            'date' => now()->toDateString(),
            'time' => now()->addMinutes(5)->format('H:i:s'),
            'subject' => 'Lenguaje',
            'module_number' => 1,
        ]);

        $this->actingAs($this->student);

        Livewire::test(\App\Http\Livewire\Asistencias\Show::class)
            ->call('registerAttendance', $otherSession->id);

        $this->assertDatabaseMissing('attendances', [
            'user_id' => $this->student->id,
            'course_session_id' => $otherSession->id,
        ]);
    }

    /** @test */
    public function admin_cannot_access_student_attendance_register()
    {
        $admin = User::factory()->create(['student_group_id' => 999]);
        $admin->assignRole('student');

        $this->actingAs($admin);

        $component = Livewire::test(\App\Http\Livewire\Asistencias\Show::class);

        $sessions = $component->viewData('availableSessions');

        $this->assertEmpty($sessions);
    }

    /** @test */
    public function attendance_has_belongs_to_user_relationship()
    {
        $this->actingAs($this->student);

        Livewire::test(\App\Http\Livewire\Asistencias\Show::class)
            ->call('registerAttendance', $this->session->id);

        $attendance = Attendance::first();

        $this->assertInstanceOf(User::class, $attendance->user);
        $this->assertEquals($this->student->id, $attendance->user->id);
    }

    /** @test */
    public function attendance_has_belongs_to_course_session_relationship()
    {
        $this->actingAs($this->student);

        Livewire::test(\App\Http\Livewire\Asistencias\Show::class)
            ->call('registerAttendance', $this->session->id);

        $attendance = Attendance::first();

        $this->assertInstanceOf(CourseSession::class, $attendance->courseSession);
        $this->assertEquals($this->session->id, $attendance->courseSession->id);
    }

    /** @test */
    public function student_without_group_cannot_register_attendance()
    {
        $noGroupStudent = User::factory()->create(['student_group_id' => 999]);
        $noGroupStudent->assignRole('student');

        $this->actingAs($noGroupStudent);

        $component = Livewire::test(\App\Http\Livewire\Asistencias\Show::class);

        $component->call('registerAttendance', $this->session->id);

        $this->assertDatabaseMissing('attendances', [
            'user_id' => $noGroupStudent->id,
        ]);
    }
}
