<?php

namespace Tests\Unit\Services\Attendance;

use App\Models\CourseSession;
use App\Models\User;
use App\Services\Attendance\Policies\DefaultAttendancePolicy;
use Carbon\Carbon;
use Tests\TestCase;

class DefaultAttendancePolicyTest extends TestCase
{
    private DefaultAttendancePolicy $policy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->policy = new DefaultAttendancePolicy;
        Carbon::setTestNow(Carbon::parse('2026-06-20 10:05:00'));
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function studentInGroup(int $groupId): User
    {
        $student = new User;
        $student->student_group_id = $groupId;

        return $student;
    }

    private function sessionAt(string $date, string $time, int $groupId): CourseSession
    {
        $session = new CourseSession;
        $session->date = $date;
        $session->time = $time;
        $session->student_groups_id = $groupId;

        return $session;
    }

    /** @test */
    public function allows_registration_within_window_same_group()
    {
        $student = $this->studentInGroup(1);
        $session = $this->sessionAt('2026-06-20', '10:00:00', 1);

        $this->assertTrue($this->policy->canStudentRegister($student, $session));
        $this->assertNull($this->policy->studentRejectionReason($student, $session));
    }

    /** @test */
    public function rejects_registration_before_open_window()
    {
        $student = $this->studentInGroup(1);
        $session = $this->sessionAt('2026-06-20', '12:00:00', 1);

        $reason = $this->policy->studentRejectionReason($student, $session);

        $this->assertNotNull($reason);
        $this->assertStringContainsString('ventana habilitada', $reason);
    }

    /** @test */
    public function rejects_registration_after_close_window()
    {
        Carbon::setTestNow(Carbon::parse('2026-06-20 13:00:00'));
        $student = $this->studentInGroup(1);
        $session = $this->sessionAt('2026-06-20', '10:00:00', 1);

        $reason = $this->policy->studentRejectionReason($student, $session);

        $this->assertNotNull($reason);
    }

    /** @test */
    public function rejects_student_without_group()
    {
        $student = new User(['student_group_id' => null]);
        $session = $this->sessionAt('2026-06-20', '10:00:00', 1);

        $this->assertNotNull($this->policy->studentRejectionReason($student, $session));
    }

    /** @test */
    public function rejects_student_from_different_group()
    {
        $student = $this->studentInGroup(2);
        $session = $this->sessionAt('2026-06-20', '10:00:00', 1);

        $this->assertNotNull($this->policy->studentRejectionReason($student, $session));
    }

    /** @test */
    public function allows_student_to_register_for_group_999_even_from_other_group()
    {
        $student = $this->studentInGroup(2);
        $session = $this->sessionAt('2026-06-20', '10:00:00', 999);

        $this->assertTrue($this->policy->canStudentRegister($student, $session));
    }

    /** @test */
    public function admin_can_always_mark_attendance()
    {
        $admin = new User;
        $admin->exists = true;

        $student = $this->studentInGroup(1);
        $session = $this->sessionAt('2026-06-20', '10:00:00', 1);
        $session->exists = true;

        $this->assertTrue($this->policy->canAdminMark($student, $session, $admin));
        $this->assertNull($this->policy->adminRejectionReason($student, $session, $admin));
    }
}
