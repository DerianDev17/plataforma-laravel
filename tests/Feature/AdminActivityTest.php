<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\Activity;
use App\Http\Livewire\Students\Show as StudentsShow;
use App\Models\AuditLog;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdminActivityTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $student;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->admin = User::where('email', 'admin@mail.com')->firstOrFail();
        $this->student = User::factory()->create([
            'email' => 'student-activity@test.com',
            'username' => 'studentactivity',
        ]);

        $studentRole = Role::where('name', 'student')->firstOrFail();
        $this->student->roles()->attach($studentRole->id);
    }

    /** @test */
    public function admin_can_view_activity_page(): void
    {
        $this->audit('student.import.batch', $this->admin, ['created' => 2, 'updated' => 1]);

        $response = $this->actingAs($this->admin)->get('/admin/activity');

        $response->assertOk();
        $response->assertSee('Actividad del sistema');
        $response->assertSee('Auditoria operativa');
        $response->assertSee('Importacion de estudiantes');
    }

    /** @test */
    public function student_cannot_view_activity_page(): void
    {
        $response = $this->actingAs($this->student)->get('/admin/activity');

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_filter_activity_by_action_and_actor(): void
    {
        $this->audit('student.import.batch', $this->admin, ['created' => 4, 'marker' => 'import-only-row']);
        $this->audit('attendance.revoked', $this->student, ['course_session_id' => 88, 'marker' => 'attendance-only-row']);

        Livewire::actingAs($this->admin)
            ->test(Activity::class)
            ->assertSee('import-only-row')
            ->assertSee('attendance-only-row')
            ->set('action', 'student.import.batch')
            ->assertSee('import-only-row')
            ->assertDontSee('attendance-only-row')
            ->set('action', '')
            ->set('actorId', (string) $this->student->id)
            ->assertSee('attendance-only-row')
            ->assertDontSee('import-only-row');
    }

    /** @test */
    public function admin_can_download_filtered_activity_as_csv(): void
    {
        $this->audit('user.admin.updated', $this->admin, ['user_id' => 123, 'marker' => 'csv-row']);

        Livewire::actingAs($this->admin)
            ->test(Activity::class)
            ->set('search', 'updated')
            ->call('downloadCsv')
            ->assertFileDownloaded();
    }

    /** @test */
    public function payment_status_changes_are_audited(): void
    {
        $student = User::factory()->create([
            'payment_status' => 'paid',
            'status' => 1,
        ]);
        $studentRole = Role::where('name', 'student')->firstOrFail();
        $student->roles()->attach($studentRole->id);

        Livewire::actingAs($this->admin)
            ->test(StudentsShow::class)
            ->call('updatePaymentStatus', $student->id, 'overdue')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('audit_logs', [
            'action' => 'user.admin.payment_status_updated',
            'actor_id' => $this->admin->id,
        ]);

        $log = AuditLog::where('action', 'user.admin.payment_status_updated')->firstOrFail();

        $this->assertSame($student->id, $log->context['user_id']);
        $this->assertSame('paid', $log->context['from']);
        $this->assertSame('overdue', $log->context['to']);
    }

    private function audit(string $action, ?User $actor = null, array $context = []): AuditLog
    {
        $log = AuditLog::create([
            'action' => $action,
            'actor_id' => $actor?->id,
            'context' => $context,
        ]);

        $log->forceFill(['created_at' => now()])->save();

        return $log;
    }
}
