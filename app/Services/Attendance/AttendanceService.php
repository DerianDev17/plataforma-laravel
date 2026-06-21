<?php

namespace App\Services\Attendance;

use App\Models\Attendance;
use App\Models\CourseSession;
use App\Models\User;
use App\Services\Attendance\Contracts\AttendancePolicy;
use App\Services\Audit\AuditLogService;
use Illuminate\Support\Facades\Cache;

class AttendanceService
{
    public function __construct(
        private AttendancePolicy $policy,
        private AuditLogService $audit,
    ) {}

    public function registerStudentAttendance(User $student, CourseSession $session): AttendanceResult
    {
        $reason = $this->policy->studentRejectionReason($student, $session);
        if ($reason !== null) {
            return AttendanceResult::rejected($reason, $reason);
        }

        $attendance = Attendance::firstOrCreate([
            'user_id' => $student->id,
            'course_session_id' => $session->id,
        ]);

        if ($attendance->wasRecentlyCreated) {
            $this->forgetStudentStatsCache($student);
            $this->audit->log('attendance.student.registered', $student, [
                'course_session_id' => $session->id,
            ]);
        }

        return $attendance->wasRecentlyCreated
            ? AttendanceResult::created($attendance)
            : AttendanceResult::alreadyExists($attendance);
    }

    public function markAttendanceManually(User $student, CourseSession $session, User $marker): AttendanceResult
    {
        $reason = $this->policy->adminRejectionReason($student, $session, $marker);
        if ($reason !== null) {
            return AttendanceResult::rejected($reason, $reason);
        }

        $attendance = Attendance::firstOrCreate([
            'user_id' => $student->id,
            'course_session_id' => $session->id,
        ]);

        if ($attendance->wasRecentlyCreated) {
            $this->forgetAdminStatsCache();
            $this->audit->log('attendance.admin.marked', $marker, [
                'student_id' => $student->id,
                'course_session_id' => $session->id,
            ]);
        }

        return $attendance->wasRecentlyCreated
            ? AttendanceResult::created($attendance, 'Asistencia registrada correctamente.')
            : AttendanceResult::alreadyExists($attendance, 'Este estudiante ya tiene asistencia registrada para esa clase.');
    }

    public function revokeAttendance(Attendance $attendance, User $actor): AttendanceResult
    {
        $sessionId = $attendance->course_session_id;
        $studentId = $attendance->user_id;
        $attendance->delete();

        $this->forgetAdminStatsCache();
        $this->audit->log('attendance.revoked', $actor, [
            'student_id' => $studentId,
            'course_session_id' => $sessionId,
        ]);

        return AttendanceResult::created($attendance, 'Asistencia eliminada correctamente.');
    }

    public function canStudentRegister(User $student, CourseSession $session): bool
    {
        return $this->policy->canStudentRegister($student, $session);
    }

    public function policy(): AttendancePolicy
    {
        return $this->policy;
    }

    public function forgetStudentStatsCache(User $student): void
    {
        Cache::forget('attendance.student.stats.' . $student->id . '.' . ($student->student_group_id ?: 'none') . '.' . now()->toDateString());
    }

    private function forgetAdminStatsCache(): void
    {
        Cache::forget('attendance.admin.stats.' . now()->toDateString());
    }
}
