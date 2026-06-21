<?php

namespace Tests\Unit\Services\Attendance;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\StudentGroup;
use App\Models\User;
use App\Services\Attendance\AttendanceService;
use App\Services\Attendance\Contracts\AttendancePolicy;
use App\Services\Attendance\Policies\DefaultAttendancePolicy;
use Carbon\Carbon;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AttendanceServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionsSeeder::class);
    }

    private function service(?AttendancePolicy $policy = null): AttendanceService
    {
        return new AttendanceService(
            $policy ?? new DefaultAttendancePolicy,
            app(\App\Services\Audit\AuditLogService::class)
        );
    }

    private function makeSession(array $overrides = []): CourseSession
    {
        return CourseSession::create(array_merge([
            'course_id' => 1,
            'student_groups_id' => 1,
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->format('H:i:s'),
            'subject' => 'Matematica',
            'module_number' => 1,
        ], $overrides));
    }

    private function studentWithGroup(int $groupId): User
    {
        $student = User::factory()->create(['student_group_id' => $groupId]);
        $student->assignRole('student');

        return $student;
    }

    /** @test */
    public function resolves_default_policy_from_container()
    {
        $this->assertInstanceOf(DefaultAttendancePolicy::class, app(AttendancePolicy::class));
    }

    /** @test */
    public function student_can_register_when_session_is_within_window_and_same_group()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);
        $student = $this->studentWithGroup($group->id);
        $session = $this->makeSession([
            'student_groups_id' => $group->id,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        $result = $this->service()->registerStudentAttendance($student, $session);

        $this->assertTrue($result->allowed());
        $this->assertFalse($result->alreadyRegistered());
        $this->assertNotNull($result->attendance());
        $this->assertDatabaseHas('attendances', [
            'user_id' => $student->id,
            'course_session_id' => $session->id,
        ]);
    }

    /** @test */
    public function student_cannot_register_outside_time_window()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 14:00:00'));

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);
        $student = $this->studentWithGroup($group->id);
        $session = $this->makeSession([
            'student_groups_id' => $group->id,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        $result = $this->service()->registerStudentAttendance($student, $session);

        $this->assertTrue($result->denied());
        $this->assertStringContainsString('ventana habilitada', $result->message());
        $this->assertDatabaseCount('attendances', 0);
    }

    /** @test */
    public function student_cannot_register_for_other_group()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));

        $groupA = StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);
        $groupB = StudentGroup::create(['code' => 'B', 'webinar_id' => 0]);
        $student = $this->studentWithGroup($groupA->id);
        $session = $this->makeSession([
            'student_groups_id' => $groupB->id,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        $result = $this->service()->registerStudentAttendance($student, $session);

        $this->assertTrue($result->denied());
        $this->assertStringContainsString('paralelo', $result->message());
    }

    /** @test */
    public function student_can_register_for_group_999_even_from_other_group()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));

        $groupA = StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);
        $student = $this->studentWithGroup($groupA->id);
        $session = $this->makeSession([
            'student_groups_id' => 999,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        $result = $this->service()->registerStudentAttendance($student, $session);

        $this->assertTrue($result->allowed());
    }

    /** @test */
    public function registering_twice_returns_already_registered_result()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);
        $student = $this->studentWithGroup($group->id);
        $session = $this->makeSession([
            'student_groups_id' => $group->id,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        $service = $this->service();
        $first = $service->registerStudentAttendance($student, $session);
        $second = $service->registerStudentAttendance($student, $session);

        $this->assertTrue($first->allowed());
        $this->assertFalse($first->alreadyRegistered());
        $this->assertTrue($second->allowed());
        $this->assertTrue($second->alreadyRegistered());
        $this->assertDatabaseCount('attendances', 1);
    }

    /** @test */
    public function admin_can_mark_attendance_outside_window()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 14:00:00'));

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 0]);
        $student = $this->studentWithGroup($group->id);
        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $session = $this->makeSession([
            'student_groups_id' => $group->id,
            'date' => '2026-06-20',
            'time' => '10:00:00',
        ]);

        $result = $this->service()->markAttendanceManually($student, $session, $admin);

        $this->assertTrue($result->allowed());
        $this->assertNotNull($result->attendance());
    }

    /** @test */
    public function revoke_attendance_soft_deletes_and_audits()
    {
        $attendance = Attendance::create([
            'user_id' => User::factory()->create()->id,
            'course_session_id' => $this->makeSession()->id,
        ]);

        $admin = User::factory()->create();
        $admin->assignRole('superadmin');

        $this->service()->revokeAttendance($attendance, $admin);

        $this->assertNull(Attendance::find($attendance->id));
        $this->assertNotNull(Attendance::withTrashed()->find($attendance->id));
    }
}
