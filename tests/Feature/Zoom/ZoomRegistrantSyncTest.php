<?php

namespace Tests\Feature\Zoom;

use App\Models\CourseSession;
use App\Models\StudentGroup;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ZoomRegistrantSyncTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['services.zoom.api_url' => 'https://api.zoom.test/']);

        Http::fake([
            'https://zoom.us/oauth/token' => Http::response(['access_token' => 'oauth-token'], 200),
            'https://api.zoom.test/webinars/*/registrants/status*' => Http::response([], 204),
            'https://api.zoom.test/webinars/*/registrants*' => Http::response(['registrants' => []], 200),
            'https://api.zoom.test/webinars/*' => Http::response([
                'occurrences' => [['occurrence_id' => 'occurrence-1']],
            ], 200),
            'https://api.zoom.test/*' => Http::response(['meetings' => []], 200),
        ]);
    }

    /** @test */
    public function set_zoom_ids_resets_all_student_zoom_fields()
    {
        $this->seed(PermissionsSeeder::class);

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 999]);
        $student = User::factory()->create([
            'email' => 'student@test.com',
            'student_group_id' => $group->id,
            'id_zoom' => 'old-zoom-id',
            'join_url' => 'https://zoom.us/j/old',
        ]);
        $student->assignRole('student');

        User::students()->update(['id_zoom' => 'old-zoom-id']);

        $this->assertEquals('old-zoom-id', $student->fresh()->id_zoom);
    }

    /** @test */
    public function get_webinar_registrants_handles_empty_response()
    {
        $controller = app(\App\Http\Controllers\Zoom\MeetingController::class);

        $registrants = (new \ReflectionClass($controller))
            ->getMethod('getWebinarRegistrants')
            ->invoke($controller, 999999999);

        $this->assertIsArray($registrants);
    }

    /** @test */
    public function register_to_webinar_uses_oauth_client()
    {
        $this->seed(PermissionsSeeder::class);

        $group = StudentGroup::create(['code' => 'Z', 'webinar_id' => 999]);

        $student = User::factory()->create([
            'email' => 'student@test.com',
            'student_group_id' => $group->id,
            'email_verified_at' => now(),
        ]);
        $student->assignRole('student');

        $controller = app(\App\Http\Controllers\Zoom\MeetingController::class);

        $response = (new \ReflectionClass($controller))
            ->getMethod('registerToWebinarZoom')
            ->invoke($controller, $student);

        $this->assertTrue($response->ok());

        Http::assertSent(function ($request) {
            return str_contains($request->url(), 'webinars/999/registrants')
                && $request->hasHeader('Authorization', 'Bearer oauth-token');
        });
    }

    /** @test */
    public function get_students_by_month_filters_by_exam_month_and_status()
    {
        $this->seed(PermissionsSeeder::class);

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 123]);

        User::factory()->create([
            'email' => 'feb1@test.com',
            'exam_month' => 'Febrero',
            'status' => true,
            'student_group_id' => $group->id,
            'email_verified_at' => now(),
        ])->assignRole('student');

        User::factory()->create([
            'email' => 'mar1@test.com',
            'exam_month' => 'Marzo',
            'status' => true,
            'student_group_id' => $group->id,
            'email_verified_at' => now(),
        ])->assignRole('student');

        User::factory()->create([
            'email' => 'feb_blocked@test.com',
            'exam_month' => 'Febrero',
            'status' => false,
            'student_group_id' => $group->id,
            'email_verified_at' => now(),
        ])->assignRole('student');

        $controller = app(\App\Http\Controllers\Zoom\MeetingController::class);

        $students = (new \ReflectionClass($controller))
            ->getMethod('getStudentsbyMonth')
            ->invoke($controller, 'Febrero');

        $this->assertCount(1, $students);
        $this->assertEquals('feb1@test.com', $students->first()->email);
    }

    /** @test */
    public function get_students_by_group_filters_by_group_id()
    {
        $this->seed(PermissionsSeeder::class);

        $groupA = StudentGroup::create(['code' => 'A', 'webinar_id' => 123]);
        $groupB = StudentGroup::create(['code' => 'B', 'webinar_id' => 456]);

        User::factory()->create([
            'email' => 'group_a@test.com',
            'status' => true,
            'student_group_id' => $groupA->id,
            'email_verified_at' => now(),
        ])->assignRole('student');

        User::factory()->create([
            'email' => 'group_b@test.com',
            'status' => true,
            'student_group_id' => $groupB->id,
            'email_verified_at' => now(),
        ])->assignRole('student');

        $controller = app(\App\Http\Controllers\Zoom\MeetingController::class);

        $students = (new \ReflectionClass($controller))
            ->getMethod('getStudentsbyGroupId')
            ->invoke($controller, $groupA->id);

        $this->assertCount(1, $students);
        $this->assertEquals('group_a@test.com', $students->first()->email);
    }

    /** @test */
    public function set_zoom_fields_handles_empty_registrants()
    {
        $this->seed(PermissionsSeeder::class);

        $group = StudentGroup::create(['code' => 'A', 'webinar_id' => 999999999]);

        $student = User::factory()->create([
            'email' => 'student@test.com',
            'student_group_id' => $group->id,
            'email_verified_at' => now(),
        ]);
        $student->assignRole('student');

        $student->id_zoom = null;
        $student->join_url = null;
        $student->save();

        $controller = app(\App\Http\Controllers\Zoom\MeetingController::class);

        $result = (new \ReflectionClass($controller))
            ->getMethod('set_zoom_fields')
            ->invoke($controller, 999999999);

        $this->assertNull($result);
    }

    /** @test */
    public function approve_students_uses_oauth_client()
    {
        $controller = app(\App\Http\Controllers\Zoom\MeetingController::class);

        $response = (new \ReflectionClass($controller))
            ->getMethod('approveStudents')
            ->invoke($controller, 999999, []);

        $this->assertSame(204, $response->status());
    }
}
