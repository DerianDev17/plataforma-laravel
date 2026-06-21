<?php

namespace App\Services\Attendance\Contracts;

use App\Models\CourseSession;
use App\Models\User;

interface AttendancePolicy
{
    public function canStudentRegister(User $student, CourseSession $session): bool;

    public function studentRejectionReason(User $student, CourseSession $session): ?string;

    public function canAdminMark(User $student, CourseSession $session, User $marker): bool;

    public function adminRejectionReason(User $student, CourseSession $session, User $marker): ?string;
}
