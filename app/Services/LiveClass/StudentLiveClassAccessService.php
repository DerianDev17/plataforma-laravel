<?php

namespace App\Services\LiveClass;

use App\Models\User;
use App\Services\LiveClass\Contracts\LiveClassProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class StudentLiveClassAccessService
{
    private $provider;

    public function __construct(LiveClassProvider $provider)
    {
        $this->provider = $provider;
    }

    public function providerLabel(): string
    {
        return $this->provider->label();
    }

    public function counters(): array
    {
        return Cache::remember($this->countersCacheKey(), now()->addMinutes(5), function (): array {
            $baseQuery = User::students()->setEagerLoads([]);

            return [
                'paid_students' => (clone $baseQuery)
                    ->withLiveClassPaymentAccess()
                    ->count(),
                'with_access' => (clone $baseQuery)
                    ->withLiveClassPaymentAccess()
                    ->whereNotNull('join_url')
                    ->count(),
                'pending_access' => (clone $baseQuery)
                    ->withLiveClassPaymentAccess()
                    ->whereNull('join_url')
                    ->whereNotIn('student_group_id', [3, 999])
                    ->count(),
                'without_group' => (clone $baseQuery)
                    ->withLiveClassPaymentAccess()
                    ->whereIn('student_group_id', [3, 999])
                    ->count(),
            ];
        });
    }

    public function registerStudent(User $student): LiveClassOperationResult
    {
        if (! $this->studentCanReceiveAccess($student)) {
            return LiveClassOperationResult::failure(
                'El estudiante debe estar al dia en pagos y tener un paralelo valido.'
            );
        }

        $result = $this->provider->registerStudent($student);

        if ($result->successful()) {
            $this->storeAccessResult($student, $result);
            $this->clearCountersCache();
        }

        return $result;
    }

    public function registerPending(): array
    {
        $summary = [
            'registered' => 0,
            'failed' => 0,
            'errors' => [],
        ];

        $this->pendingStudentsQuery()
            ->with('student_group')
            ->chunk(50, function ($students) use (&$summary): void {
                foreach ($students as $student) {
                    $result = $this->registerStudent($student);

                    if ($result->successful()) {
                        $summary['registered']++;
                    } else {
                        $summary['failed']++;
                        $summary['errors'][$student->email] = $result->message();
                    }
                }
            });

        $sync = $this->syncAccessLinks();

        if ($sync->hasErrors()) {
            $summary['errors']['sync'] = implode(' ', $sync->errors());
        }

        return $summary;
    }

    public function syncAccessLinks(): LiveClassSyncResult
    {
        $result = $this->provider->syncAccessLinks();
        $this->clearCountersCache();

        return $result;
    }

    public function pendingStudentsQuery(): Builder
    {
        return User::students()
            ->whereNull('join_url')
            ->withLiveClassPaymentAccess()
            ->whereNotIn('student_group_id', [3, 999]);
    }

    public function clearCountersCache(): void
    {
        Cache::forget($this->countersCacheKey());
    }

    private function studentCanReceiveAccess(User $student): bool
    {
        return $student->canAccessLiveClasses()
            && ! in_array((int) $student->student_group_id, [3, 999], true);
    }

    private function storeAccessResult(User $student, LiveClassOperationResult $result): void
    {
        if ($result->externalParticipantId()) {
            $student->id_zoom = $result->externalParticipantId();
        }

        if ($result->accessUrl()) {
            $student->join_url = $result->accessUrl();
        }

        $student->save();
    }

    private function countersCacheKey(): string
    {
        return 'students.live_class_access.counters';
    }
}
