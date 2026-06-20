<?php

namespace Tests\Feature\Imports;

use App\Imports\StudentsImport;
use App\Models\User;
use Database\Seeders\PermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class StudentsImportPreviewTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function import_updates_existing_user_payment_status_and_exam_month()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'email' => 'student@test.com',
            'payment_status' => 'pending',
            'payment_day' => '',
            'exam_month' => 'Enero',
            'status' => true,
        ]);

        Excel::fake();

        $import = new StudentsImport;
        $import->collection(collect([
            [null, null, null, null, null, null, null, 'student@test.com', null, null, null, null, null, 'Febrero', '25', 'paid'],
        ]));

        $user->refresh();

        $this->assertEquals('paid', $user->payment_status);
        $this->assertEquals('Febrero', $user->exam_month);
        $this->assertEquals('25', $user->payment_day);
        $this->assertTrue((bool) $user->status);
    }

    /** @test */
    public function import_sets_status_false_for_overdue_payment()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'email' => 'student@test.com',
            'payment_status' => 'paid',
            'status' => true,
        ]);

        $import = new StudentsImport;
        $import->collection(collect([
            [null, null, null, null, null, null, null, 'student@test.com', null, null, null, null, null, 'Marzo', '10', 'overdue'],
        ]));

        $user->refresh();

        $this->assertEquals('overdue', $user->payment_status);
        $this->assertFalse((bool) $user->status);
    }

    /** @test */
    public function import_normalizes_scholarship_payment_status()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'email' => 'student@test.com',
            'payment_status' => 'pending',
            'status' => true,
        ]);

        $import = new StudentsImport;
        $import->collection(collect([
            [null, null, null, null, null, null, null, 'student@test.com', null, null, null, null, null, 'Abril', '5', 'becado'],
        ]));

        $user->refresh();

        $this->assertEquals('scholarship', $user->payment_status);
        $this->assertTrue((bool) $user->status);
    }

    /** @test */
    public function import_tracks_failed_updates_for_nonexistent_emails()
    {
        $import = new StudentsImport;
        $import->collection(collect([
            [null, null, null, null, null, null, null, 'nonexistent@test.com', null, null, null, null, null, 'Mayo', '15', 'paid'],
        ]));

        $failed = $import->getFailedUpdates();

        $this->assertCount(1, $failed);
        $this->assertStringContainsString('nonexistent@test.com', $failed[0]);
    }

    /** @test */
    public function import_counts_rows_processed()
    {
        $this->seed(PermissionsSeeder::class);

        User::factory()->create(['email' => 'student1@test.com']);
        User::factory()->create(['email' => 'student2@test.com']);

        $import = new StudentsImport;
        $import->collection(collect([
            [null, null, null, null, null, null, null, 'student1@test.com', null, null, null, null, null, 'Junio', '1', 'paid'],
            [null, null, null, null, null, null, null, 'student2@test.com', null, null, null, null, null, 'Julio', '20', 'pending'],
            [null, null, null, null, null, null, null, 'nonexistent@test.com', null, null, null, null, null, 'Agosto', '30', 'paid'],
        ]));

        $this->assertEquals(3, $import->getRowCount());
    }

    /** @test */
    public function import_start_row_is_two_skipping_header()
    {
        $import = new StudentsImport;

        $this->assertEquals(2, $import->startRow());
    }

    /** @test */
    public function import_clears_zoom_data_when_user_blocked()
    {
        $this->seed(PermissionsSeeder::class);

        $user = User::factory()->create([
            'email' => 'student@test.com',
            'payment_status' => 'paid',
            'status' => true,
            'id_zoom' => 'abc123',
            'join_url' => 'https://zoom.us/j/123',
        ]);

        $import = new StudentsImport;
        $import->collection(collect([
            [null, null, null, null, null, null, null, 'student@test.com', null, null, null, null, null, 'Enero', '1', 'overdue'],
        ]));

        $user->refresh();

        $this->assertFalse((bool) $user->status);
        $this->assertNull($user->id_zoom);
        $this->assertNull($user->join_url);
    }
}
