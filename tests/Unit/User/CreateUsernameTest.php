<?php

namespace Tests\Unit\User;

use App\Models\User;
use App\Services\StudentImport\StudentImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUsernameTest extends TestCase
{
    use RefreshDatabase;

    private function service(): StudentImportService
    {
        return app(StudentImportService::class);
    }

    /** @test */
    public function username_is_generated_from_first_and_last_name()
    {
        $username = $this->service()->generateUsername('Juan', 'Perez Solis');

        $this->assertStringStartsWith('jperezs', $username);
        $this->assertStringEndsWith('EUS', $username);
    }

    /** @test */
    public function username_removes_accents()
    {
        $username = $this->service()->generateUsername('José', 'García López');

        $this->assertStringStartsWith('jgarcial', $username);
        $this->assertStringNotContainsString('é', $username);
        $this->assertStringNotContainsString('í', $username);
    }

    /** @test */
    public function username_becomes_lowercase()
    {
        $username = $this->service()->generateUsername('MARIA', 'RODRIGUEZ MENDOZA');

        $this->assertStringStartsWith('mrodriguezm', $username);
    }

    /** @test */
    public function username_handles_duplicate_by_generating_new_one()
    {
        User::factory()->create([
            'username' => 'testuserEUS',
            'email' => 'existing@test.com',
        ]);

        $username = $this->service()->generateUsername('Test', 'User');

        $this->assertStringEndsWith('EUS', $username);
        $this->assertNotEquals('testuserEUS', $username);
    }

    /** @test */
    public function username_handles_single_last_name()
    {
        $username = $this->service()->generateUsername('Ana', 'Lopez');

        $this->assertStringStartsWith('alopez', $username);
        $this->assertStringEndsWith('EUS', $username);
    }

    /** @test */
    public function eliminar_acentos_replaces_all_accented_characters()
    {
        $result = $this->service()->stripAccents('ÁÉÍÓÚáéíóúÑñÇç');

        $this->assertEquals('AEIOUaeiouNnCc', $result);
    }

    /** @test */
    public function user_has_username_in_fillable()
    {
        $user = new User;

        $this->assertContains('username', $user->getFillable());
    }
}
