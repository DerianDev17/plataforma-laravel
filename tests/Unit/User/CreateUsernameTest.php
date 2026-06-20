<?php

namespace Tests\Unit\User;

use App\Imports\StudentsImportar;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateUsernameTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function username_is_generated_from_first_and_last_name()
    {
        $importer = new StudentsImportar(false);

        $username = (new \ReflectionClass($importer))
            ->getMethod('createUsername')
            ->invoke($importer, 'Juan', 'Perez Solis');

        $this->assertStringStartsWith('jperezs', $username);
        $this->assertStringEndsWith('EUS', $username);
    }

    /** @test */
    public function username_removes_accents()
    {
        $importer = new StudentsImportar(false);

        $username = (new \ReflectionClass($importer))
            ->getMethod('createUsername')
            ->invoke($importer, 'José', 'García López');

        $this->assertStringStartsWith('jgarcial', $username);
        $this->assertStringNotContainsString('é', $username);
        $this->assertStringNotContainsString('í', $username);
    }

    /** @test */
    public function username_becomes_lowercase()
    {
        $importer = new StudentsImportar(false);

        $username = (new \ReflectionClass($importer))
            ->getMethod('createUsername')
            ->invoke($importer, 'MARIA', 'RODRIGUEZ MENDOZA');

        $this->assertStringStartsWith('mrodriguezm', $username);
    }

    /** @test */
    public function username_handles_duplicate_by_generating_new_one()
    {
        User::factory()->create([
            'username' => 'testuserEUS',
            'email' => 'existing@test.com',
        ]);

        $importer = new StudentsImportar(false);

        $username = (new \ReflectionClass($importer))
            ->getMethod('createUsername')
            ->invoke($importer, 'Test', 'User');

        $this->assertStringEndsWith('EUS', $username);
        $this->assertNotEquals('testuserEUS', $username);
    }

    /** @test */
    public function username_handles_single_last_name()
    {
        $importer = new StudentsImportar(false);

        $username = (new \ReflectionClass($importer))
            ->getMethod('createUsername')
            ->invoke($importer, 'Ana', 'Lopez');

        $this->assertStringStartsWith('alopez', $username);
        $this->assertStringEndsWith('EUS', $username);
    }

    /** @test */
    public function eliminar_acentos_replaces_all_accented_characters()
    {
        $importer = new StudentsImportar(false);

        $result = (new \ReflectionClass($importer))
            ->getMethod('eliminar_acentos')
            ->invoke($importer, 'ÁÉÍÓÚáéíóúÑñÇç');

        $this->assertEquals('AEIOUaeiouNnCc', $result);
    }

    /** @test */
    public function user_has_username_in_fillable()
    {
        $user = new User;

        $this->assertContains('username', $user->getFillable());
    }
}
