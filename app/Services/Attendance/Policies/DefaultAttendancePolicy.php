<?php

namespace App\Services\Attendance\Policies;

use App\Models\CourseSession;
use App\Models\User;
use App\Services\Attendance\Contracts\AttendancePolicy;
use Carbon\Carbon;

class DefaultAttendancePolicy implements AttendancePolicy
{
    public const OPEN_WINDOW_MINUTES_BEFORE = 15;

    public const OPEN_WINDOW_MINUTES_AFTER = 90;

    public function canStudentRegister(User $student, CourseSession $session): bool
    {
        return $this->studentRejectionReason($student, $session) === null;
    }

    public function studentRejectionReason(User $student, CourseSession $session): ?string
    {
        if (! $this->studentBelongsToSessionGroup($student, $session)) {
            return 'La clase seleccionada no esta disponible para tu paralelo.';
        }

        if (! $this->isWithinOpenWindow($session)) {
            return 'La asistencia solo puede registrarse durante la ventana habilitada para la clase.';
        }

        return null;
    }

    public function canAdminMark(User $student, CourseSession $session, User $marker): bool
    {
        return $this->adminRejectionReason($student, $session, $marker) === null;
    }

    public function adminRejectionReason(User $student, CourseSession $session, User $marker): ?string
    {
        if (! $marker->exists) {
            return 'El usuario que registra la marca no es valido.';
        }

        if (! $session->exists) {
            return 'La clase indicada no existe.';
        }

        return null;
    }

    private function studentBelongsToSessionGroup(User $student, CourseSession $session): bool
    {
        $groupId = (int) $student->student_group_id;
        $sessionGroupId = (int) $session->student_groups_id;

        if (! $groupId) {
            return false;
        }

        return $groupId === $sessionGroupId || $sessionGroupId === 999;
    }

    private function isWithinOpenWindow(CourseSession $session): bool
    {
        $start = Carbon::parse($session->date . ' ' . $session->time);
        $now = Carbon::now();

        return $now->between(
            $start->copy()->subMinutes(self::OPEN_WINDOW_MINUTES_BEFORE),
            $start->copy()->addMinutes(self::OPEN_WINDOW_MINUTES_AFTER)
        );
    }
}
