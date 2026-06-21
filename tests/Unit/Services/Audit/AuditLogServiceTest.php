<?php

namespace Tests\Unit\Services\Audit;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\Audit\AuditLogService;
use App\Services\Audit\Contracts\AuditLogger;
use App\Services\Audit\Loggers\DatabaseAuditLogger;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuditLogServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_resolves_the_default_logger_from_the_container()
    {
        $this->assertInstanceOf(DatabaseAuditLogger::class, app(AuditLogger::class));
    }

    /** @test */
    public function log_persists_action_actor_and_context()
    {
        $this->seed(PermissionsSeeder::class);

        $actor = User::factory()->create();

        app(AuditLogService::class)->log('student.imported', $actor, [
            'email' => 'new@test.com',
            'paralelo' => 'A',
        ]);

        $entry = AuditLog::firstOrFail();

        $this->assertSame('student.imported', $entry->action);
        $this->assertSame($actor->id, $entry->actor_id);
        $this->assertSame(['email' => 'new@test.com', 'paralelo' => 'A'], $entry->context);
        $this->assertNotNull($entry->created_at);
    }

    /** @test */
    public function log_allows_null_actor_for_system_events()
    {
        app(AuditLogService::class)->log('system.cron', null, ['task' => 'cleanup']);

        $entry = AuditLog::firstOrFail();

        $this->assertSame('system.cron', $entry->action);
        $this->assertNull($entry->actor_id);
        $this->assertSame(['task' => 'cleanup'], $entry->context);
    }

    /** @test */
    public function recent_returns_entries_latest_first_with_actor()
    {
        $this->seed(PermissionsSeeder::class);

        $actor = User::factory()->create();

        $service = app(AuditLogService::class);

        $service->log('first', $actor);
        sleep(1);
        $service->log('second', $actor);

        $recent = $service->recent(10);

        $this->assertCount(2, $recent);
        $this->assertSame('second', $recent->first()->action);
        $this->assertTrue($recent->first()->relationLoaded('actor'));
    }

    /** @test */
    public function for_action_filters_results()
    {
        $service = app(AuditLogService::class);

        $service->log('student.imported');
        $service->log('student.updated');
        $service->log('student.imported');

        $this->assertCount(2, $service->forAction('student.imported'));
        $this->assertCount(1, $service->forAction('student.updated'));
    }
}
