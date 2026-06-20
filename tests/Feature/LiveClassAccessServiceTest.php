<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\LiveClass\Contracts\LiveClassProvider;
use App\Services\LiveClass\LiveClassOperationResult;
use App\Services\LiveClass\LiveClassSyncResult;
use App\Services\LiveClass\StudentLiveClassAccessService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LiveClassAccessServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    /** @test */
    public function it_registers_pending_students_with_the_configured_provider(): void
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);

        $provider = new FakeLiveClassProvider();
        $this->app->instance(LiveClassProvider::class, $provider);

        $student = User::factory()->create([
            'email' => 'paid@student.test',
            'student_group_id' => 1,
            'payment_status' => 'paid',
            'join_url' => null,
        ]);
        $student->assignRole('student');

        $overdue = User::factory()->create([
            'email' => 'overdue@student.test',
            'student_group_id' => 1,
            'payment_status' => 'overdue',
            'join_url' => null,
        ]);
        $overdue->assignRole('student');

        $withoutGroup = User::factory()->create([
            'email' => 'nogroup@student.test',
            'student_group_id' => 999,
            'payment_status' => 'paid',
            'join_url' => null,
        ]);
        $withoutGroup->assignRole('student');

        $summary = $this->app->make(StudentLiveClassAccessService::class)->registerPending();

        $this->assertSame(1, $summary['registered']);
        $this->assertSame(0, $summary['failed']);
        $this->assertSame(['paid@student.test'], $provider->registeredEmails);
        $this->assertSame('fake-' . $student->id, $student->fresh()->id_zoom);
        $this->assertSame('https://classes.test/' . $student->id, $student->fresh()->join_url);
        $this->assertNull($overdue->fresh()->join_url);
        $this->assertNull($withoutGroup->fresh()->join_url);
    }

    /** @test */
    public function counters_use_the_generic_live_class_payment_rule(): void
    {
        $this->seed(\Database\Seeders\PermissionsSeeder::class);
        $this->seed(\Database\Seeders\StudentGroupSeeder::class);

        $this->app->instance(LiveClassProvider::class, new FakeLiveClassProvider());

        $withAccess = User::factory()->create([
            'student_group_id' => 1,
            'payment_status' => 'scholarship',
            'join_url' => 'https://classes.test/ready',
        ]);
        $withAccess->assignRole('student');

        $pending = User::factory()->create([
            'student_group_id' => 2,
            'payment_status' => 'pending',
            'join_url' => null,
        ]);
        $pending->assignRole('student');

        $withoutGroup = User::factory()->create([
            'student_group_id' => 999,
            'payment_status' => 'paid',
            'join_url' => null,
        ]);
        $withoutGroup->assignRole('student');

        $blocked = User::factory()->create([
            'student_group_id' => 1,
            'payment_status' => 'overdue',
            'join_url' => null,
        ]);
        $blocked->assignRole('student');

        $counters = $this->app->make(StudentLiveClassAccessService::class)->counters();

        $this->assertSame(3, $counters['paid_students']);
        $this->assertSame(1, $counters['with_access']);
        $this->assertSame(1, $counters['pending_access']);
        $this->assertSame(1, $counters['without_group']);
    }
}

class FakeLiveClassProvider implements LiveClassProvider
{
    public $registeredEmails = [];

    public function label(): string
    {
        return 'Fake API';
    }

    public function registerStudent(User $student): LiveClassOperationResult
    {
        $this->registeredEmails[] = $student->email;

        return LiveClassOperationResult::success(
            'Fake access created.',
            'fake-' . $student->id,
            'https://classes.test/' . $student->id
        );
    }

    public function syncAccessLinks(): LiveClassSyncResult
    {
        return LiveClassSyncResult::completed(0, 'Fake sync completed.');
    }
}
