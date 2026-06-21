<?php

namespace App\Services\Students;

use App\Models\User;
use Illuminate\Support\Facades\Cache;

class StudentDashboardCounterService
{
    public const CACHE_KEY = 'students.dashboard.counters';

    public function counters(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addMinutes(5), function (): array {
            $baseQuery = User::students()->setEagerLoads([]);

            return [
                'total_students_n' => (clone $baseQuery)->count(),
                'active_students_n' => (clone $baseQuery)->withLiveClassPaymentAccess()->count(),
                'blocked_students_n' => (clone $baseQuery)->where('payment_status', 'overdue')->count(),
                'students_without_group_n' => (clone $baseQuery)->whereIn('student_group_id', [3, 999])->count(),
            ];
        });
    }

    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
